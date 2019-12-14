
<?php
include_once('../conexiones/conexione_cryp.php');

$sql_cryptor = "SELECT cod_cryptor, method, secret_key, secret_iv FROM cryptor WHERE cod_cryptor = '1'";
$consulta_cryptor = mysqli_query($conectar3, $sql_cryptor) or die(mysqli_error($conectar3));
$datos_cryptor = mysqli_fetch_assoc($consulta_cryptor);

$method = $datos_cryptor['method'];
$secret_key = $datos_cryptor['secret_key'];
$secret_iv = $datos_cryptor['secret_iv'];

define('METHOD', $method);
define('SECRET_KEY', $secret_key);
define('SECRET_IV', $secret_iv);

class DAXCRYPTOR {
public static function encriptardax($string){
	$output = FALSE;
	$key    = hash('sha256', SECRET_KEY);
	$iv     = substr(hash('sha256', SECRET_IV), 0, 16);
	$output = openssl_encrypt($string, METHOD, $key, 0, $iv);
	$output = base64_encode($output);
	return $output;
}
public static function descriptardax($string){
	$key    = hash('sha256', SECRET_KEY);
	$iv     = substr(hash('sha256', SECRET_IV), 0, 16);
	$output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
	return $output;
}
}
?>