<?php
/** 
* Este archivo es parte de escpos-php: biblioteca de impresoras de recibos PHP para usar con
* Impresoras térmicas y de impacto compatibles con ESC / POS.
*
* Derechos de autor (c) 2014-16 Michael Billington <michael.billington@gmail.com>,
* Incorporando modificaciones por otros.  Ver CONTRIBUIDORES.md para una lista completa.
*
* Este software se distribuye bajo los términos de la licencia MIT.  Ver LICENCIA.md
* para detalles.  
*/

namespace Mike42\Escpos\PrintConnectors;

use Exception;
use BadMethodCallException;

/**
* Conector para enviar trabajos de impresión a
* - puertos locales en Windows (COM1, LPT1, etc.)
* - impresoras compartidas (SMB) desde cualquier plataforma (smb: // server / foo)
* Para impresoras USB u otros puertos, el truco consiste en compartir la impresora con un
* controlador de texto genérico, luego conecte a la impresora compartida localmente.
*/

class WindowsPrintConnector implements PrintConnector
{
    /**
     * @var array $buffer
     *  Líneas de salida acumuladas para su uso posterior.
     */
    private $buffer;

/** 
* @var string $ nombre de host
* El nombre de host de la máquina de destino, o nulo si se trata de una conexión local.   
*/
    private $hostname;

/** 
* @var boolean $ isLocal
* Verdadero si se usa un puerto directamente (debe ser Windows), falso si se usarán recursos compartidos de red.
*/
    private $isLocal;

/** 
* @var int $ platform
* Plataforma en la que estamos ejecutando, para seleccionar diferentes comandos.  Ver PLATFORM_ * constantes.
*/
    private $platform;

/** 
* @var string $ printerName
* El nombre de la impresora de destino (por ejemplo, "Foo Printer") o el puerto ("COM1", "LPT1").
*/
    private $printerName;

/** 
* @var string $ userName
* Nombre de inicio de sesión para la impresora de red, o nulo si no se usa autenticación.
*/
    private $userName;

/** 
* @var string $ userPassword
* Contraseña para la impresora de red, o nula si no se requiere contraseña.
*/
    private $userPassword;

/** 
* @var string $ workgroup
* Grupo de trabajo en el que se encuentra la impresora.
*/
    private $workgroup;

/** 
* Representa Linux
*/
    const PLATFORM_LINUX = 0;

/** 
* Representa a Mac
*/
    const PLATFORM_MAC = 1;

/** 
* Representa Windows
*/
    const PLATFORM_WIN = 2;

    /**
     * Valid local ports.
     */
    const REGEX_LOCAL = "/^(LPT\d|COM\d)$/";

/** 
* Nombre de impresora válido.
*/
    const REGEX_PRINTERNAME = "/^[\d\w\#-]+(\s[\d\w-]+)*$/";
    //const REGEX_PRINTERNAME = "/^[\d\w-]+(\s[\d\w-]+)*$/";

/** 
* Smb válido: // URI que contiene nombre de host e impresora con usuario opcional y contraseña opcional únicamente.
*/
    //const REGEX_SMB = "/^smb:\/\/([\s\d\w\#-]+(:[\s\d\w\#-+]+)?@)?([\d\w\#-]+\.)*[\d\w\#-]+\/([\d\w\#-]+\/)?[\d\w\#-]+(\s[\d\w\#-]+)*$/";
    const REGEX_SMB = "/^smb:\/\/([\s\d\w-]+(:[\s\d\w-+]+)?@)?([\d\w-]+\.)*[\d\w-]+\/([\d\w-]+\/)?[\d\w-]+(\s[\d\w-]+)*$/";

    /**
     * @param string $dest
     * @throws BadMethodCallException
     */
    public function __construct($dest)
    {
        $this -> platform = $this -> getCurrentPlatform();
        $this -> isLocal = false;
        $this -> buffer = null;
        $this -> userName = null;
        $this -> userPassword = null;
        $this -> workgroup = null;
        if (preg_match(self::REGEX_LOCAL, $dest) == 1) {
            // Straight to LPT1, COM1 or other local port. Allowed only if we are actually on windows.
            if ($this -> platform !== self::PLATFORM_WIN) {
                throw new BadMethodCallException("WindowsPrintConnector can only be " .
                    "used to print to a local printer ('".$dest."') on a Windows computer.");
            }
            $this -> isLocal = true;
            $this -> hostname = null;
            $this -> printerName = $dest;
        } elseif (preg_match(self::REGEX_SMB, $dest) == 1) {
            // Connect to samba share, eg smb://host/printer
            $part = parse_url($dest);
            $this -> hostname = $part['host'];
            /* Printer name and optional workgroup */
            $path = ltrim($part['path'], '/');
            if (strpos($path, "/") !== false) {
                $pathPart = explode("/", $path);
                $this -> workgroup = $pathPart[0];
                $this -> printerName = $pathPart[1];
            } else {
                $this -> printerName = $path;
            }
            /* Username and password if set */
            if (isset($part['user'])) {
                $this -> userName = $part['user'];
                if (isset($part['pass'])) {
                    $this -> userPassword = $part['pass'];
                }
            }
        } elseif (preg_match(self::REGEX_PRINTERNAME, $dest) == 1) {
            // Just got a printer name. Assume it's on the current computer.
            $hostname = gethostname();
            if (!$hostname) {
                $hostname = "localhost";
            }
            $this -> hostname = $hostname;
            $this -> printerName = $dest;
        } else {
            throw new BadMethodCallException("Printer '" . $dest . "' is not a valid " .
                "printer name. Use local port (LPT1, COM1, etc) or smb://computer/printer notation.");
        }
        $this -> buffer = array();
    }

    public function __destruct()
    {
        if ($this -> buffer !== null) {
            trigger_error("Print connector was not finalized. Did you forget to close the printer?", E_USER_NOTICE);
        }
    }

    public function finalize()
    {
        $data = implode($this -> buffer);
        $this -> buffer = null;
        if ($this -> platform == self::PLATFORM_WIN) {
            $this -> finalizeWin($data);
        } elseif ($this -> platform == self::PLATFORM_LINUX) {
            $this -> finalizeLinux($data);
        } else {
            $this -> finalizeMac($data);
        }
    }

    /**
     * Send job to printer -- platform-specific Linux code.
     *
     * @param string $data Print data
     * @throws Exception
     */
    protected function finalizeLinux($data)
    {
        /* Non-Windows samba printing */
        $device = "//" . $this -> hostname . "/" . $this -> printerName;
        if ($this -> userName !== null) {
            $user = ($this -> workgroup != null ? ($this -> workgroup . "\\") : "") . $this -> userName;
            if ($this -> userPassword == null) {
                // No password
                $command = sprintf(
                    "smbclient %s -U %s -c %s -N",
                    escapeshellarg($device),
                    escapeshellarg($user),
                    escapeshellarg("print -")
                );
                $redactedCommand = $command;
            } else {
                // With password
                $command = sprintf(
                    "smbclient %s %s -U %s -c %s",
                    escapeshellarg($device),
                    escapeshellarg($this -> userPassword),
                    escapeshellarg($user),
                    escapeshellarg("print -")
                );
                $redactedCommand = sprintf(
                    "smbclient %s %s -U %s -c %s",
                    escapeshellarg($device),
                    escapeshellarg("*****"),
                    escapeshellarg($user),
                    escapeshellarg("print -")
                );
            }
        } else {
            // No authentication information at all
            $command = sprintf(
                "smbclient %s -c %s -N",
                escapeshellarg($device),
                escapeshellarg("print -")
            );
            $redactedCommand = $command;
        }
        $retval = $this -> runCommand($command, $outputStr, $errorStr, $data);
        if ($retval != 0) {
            throw new Exception("Failed to print. Command \"$redactedCommand\" " .
                "failed with exit code $retval: " . trim($errorStr) . trim($outputStr));
        }
    }

    /**
     * Send job to printer -- platform-specific Mac code.
     *
     * @param string $data Print data
     * @throws Exception
     */
    protected function finalizeMac($data)
    {
        throw new Exception("Mac printing not implemented.");
    }
    
    /**
     * Send data to printer -- platform-specific Windows code.
     *
     * @param string $data
     */
    protected function finalizeWin($data)
    {
        /* Windows-friendly printing of all sorts */
        if (!$this -> isLocal) {
            /* Networked printing */
            $device = "\\\\" . $this -> hostname . "\\" . $this -> printerName;
            if ($this -> userName !== null) {
                /* Log in */
                $user = "/user:" . ($this -> workgroup != null ? ($this -> workgroup . "\\") : "") . $this -> userName;
                if ($this -> userPassword == null) {
                    $command = sprintf(
                        "net use %s %s",
                        escapeshellarg($device),
                        escapeshellarg($user)
                    );
                    $redactedCommand = $command;
                } else {
                    $command = sprintf(
                        "net use %s %s %s",
                        escapeshellarg($device),
                        escapeshellarg($user),
                        escapeshellarg($this -> userPassword)
                    );
                    $redactedCommand = sprintf(
                        "net use %s %s %s",
                        escapeshellarg($device),
                        escapeshellarg($user),
                        escapeshellarg("*****")
                    );
                }
                $retval = $this -> runCommand($command, $outputStr, $errorStr);
                if ($retval != 0) {
                    throw new Exception("Failed to print. Command \"$redactedCommand\" " .
                        "failed with exit code $retval: " . trim($errorStr));
                }
            }
            /* Final print-out */
            $filename = tempnam(sys_get_temp_dir(), "escpos");
            file_put_contents($filename, $data);
            if (!$this -> runCopy($filename, $device)) {
                throw new Exception("Failed to copy file to printer");
            }
            unlink($filename);
        } else {
            /* Drop data straight on the printer */
            if (!$this -> runWrite($data, $this -> printerName)) {
                throw new Exception("Failed to write file to printer at " . $this -> printerName);
            }
        }
    }
    
    /**
     * @return string Current platform. Separated out for testing purposes.
     */
    protected function getCurrentPlatform()
    {
        if (PHP_OS == "WINNT") {
            return self::PLATFORM_WIN;
        }
        if (PHP_OS == "Darwin") {
            return self::PLATFORM_MAC;
        }
        return self::PLATFORM_LINUX;
    }
    
    /* (non-PHPdoc)
	 * @see PrintConnector::read()
	 */
    public function read($len)
    {
        /* Two-way communication is not supported */
        return false;
    }
    
    /**
     * Run a command, pass it data, and retrieve its return value, standard output, and standard error.
     *
     * @param string $command the command to run.
     * @param string $outputStr variable to fill with standard output.
     * @param string $errorStr variable to fill with standard error.
     * @param string $inputStr text to pass to the command's standard input (optional).
     * @return number
     */
    protected function runCommand($command, &$outputStr, &$errorStr, $inputStr = null)
    {
        $descriptors = array(
                0 => array("pipe", "r"),
                1 => array("pipe", "w"),
                2 => array("pipe", "w"),
        );
        $process = proc_open($command, $descriptors, $fd);
        if (is_resource($process)) {
            /* Write to input */
            if ($inputStr !== null) {
                fwrite($fd[0], $inputStr);
            }
            fclose($fd[0]);
            /* Read stdout */
            $outputStr = stream_get_contents($fd[1]);
            fclose($fd[1]);
            /* Read stderr */
            $errorStr = stream_get_contents($fd[2]);
            fclose($fd[2]);
            /* Finish up */
            $retval = proc_close($process);
            return $retval;
        } else {
            /* Method calling this should notice a non-zero exit and print an error */
            return -1;
        }
    }
    
    /**
     * Copy a file. Separated out so that nothing is actually printed during test runs.
     *
     * @param string $from Source file
     * @param string $to Destination file
     * @return boolean True if copy was successful, false otherwise
     */
    protected function runCopy($from, $to)
    {
        return copy($from, $to);
    }
    
    /**
     * Write data to a file. Separated out so that nothing is actually printed during test runs.
     *
     * @param string $data Data to print
     * @param string $filename Destination file
         * @return boolean True if write was successful, false otherwise
     */
    protected function runWrite($data, $filename)
    {
        return file_put_contents($filename, $data) !== false;
    }

    public function write($data)
    {
        $this -> buffer[] = $data;
    }
}
