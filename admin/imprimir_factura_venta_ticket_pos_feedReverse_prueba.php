 
<?php 
require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\CapabilityProfiles\EposTepCapabilityProfile;
/*  Este ejemplo imprime un Ticket de venta desde una impresora térmica */
/* Aquí, en lugar de "POS" (que es el nombre de mi impresora)   escribe el nombre de la tuya. Recuerda que debes compartirla    desde el panel de control */

$connector = new WindowsPrintConnector("POS-80C");
$printer = new Printer($connector);
// Most simple example
$pdf = '../pdf/archpdf.pdf';
/* Text */
$printer->text("Hello world\n");
$printer->cut();
/* Line feeds */
$printer->text("ABC");
$printer->feed(7);
$printer->text("DEF");
$printer->feedReverse(3);
$printer->text("GHI");
$printer->feed();
$printer->cut();
/* Font modes */
$modes = array(Printer::MODE_FONT_B, Printer::MODE_EMPHASIZED, Printer::MODE_DOUBLE_HEIGHT, Printer::MODE_DOUBLE_WIDTH, Printer::MODE_UNDERLINE);
for ($i = 0; $i < pow(2, count($modes)); $i++) {
    $bits = str_pad(decbin($i), count($modes), "0", STR_PAD_LEFT);
    $mode = 0;
    for ($j = 0; $j < strlen($bits); $j++) {
        if (substr($bits, $j, 1) == "1") {
            $mode |= $modes[$j];
        }
    }
    $printer->selectPrintMode($mode);
    $printer->text("ABCDEFGHIJabcdefghijk\n");