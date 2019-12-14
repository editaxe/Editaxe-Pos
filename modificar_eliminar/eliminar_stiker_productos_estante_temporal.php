<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$cod_stiker_productos_estante_temporal = intval($_GET['cod_stiker_productos_estante_temporal']);

if (isset($_GET['cod_stiker_productos_estante_temporal'])) {
$borrar_sql = sprintf("DELETE FROM stiker_productos_estante_temporal WHERE cod_stiker_productos_estante_temporal = '$cod_stiker_productos_estante_temporal'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/cargar_stiker_productos_estante_temporal.php">';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
</body>
</html>
