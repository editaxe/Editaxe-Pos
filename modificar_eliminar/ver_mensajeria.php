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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$estado_leido = "SI";
$cod_mensajeria = intval($_GET['cod_mensajeria']);
$actualizar_estado = sprintf("UPDATE mensajeria SET leido=%s WHERE cod_mensajeria = $buscar",
envio_valores_tipo_sql($estado_leido, "text"), envio_valores_tipo_sql($_POST['cod_mensajeria'], "text"));
$resultado_actualizar_estado = mysql_query($actualizar_estado, $conectar) or die(mysql_error());

$mostrar_datos_sql = "SELECT * FROM mensajeria WHERE cod_mensajeria = '$cod_mensajeria'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php require_once("../busquedas/menu_mensajeria.php");?>
<br>
<center>
<table border="1" id="table">
<tr>
<td><div align="center"><strong>Remitente</strong></div></td>
<td><div align="center"><strong>Asunto</strong></div></td>
<td><div align="center"><strong>Mensaje</strong></div></td>
<td><div align="center"><strong>Fecha</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['de']; ?></td>
<td ><?php echo $matriz_consulta['asunto']; ?></td>
<td ><?php echo $matriz_consulta['mensaje']; ?></td>
<td ><?php echo $matriz_consulta['fecha']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>