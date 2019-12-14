<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual     = addslashes($_SESSION['usuario']);
$cod_seguridad     = intval($_SESSION['cod_seguridad']);
$cod_base_caja     = intval($_SESSION['cod_base_caja']);
$cod_dependencia   = intval($_SESSION['cod_dependencia']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$buscar = addslashes($_POST['buscar']);

if($buscar <> NULL) {

if ($cod_seguridad==3) {
$mostrar_datos_sql = "SELECT * FROM productos WHERE ((cod_productos_var = '$buscar') OR (nombre_productos like '%$buscar%')) ORDER BY nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$matriz_consulta = mysql_fetch_assoc($consulta);
}
elseif ($cod_seguridad==1) {
$mostrar_datos_sql = "SELECT * FROM productos WHERE ((cod_productos_var = '$buscar') OR (nombre_productos like '%$buscar%')) ORDER BY nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$matriz_consulta = mysql_fetch_assoc($consulta);
}
else {
$mostrar_datos_sql = "SELECT * FROM productos WHERE ((cod_productos_var = '$buscar') OR (nombre_productos like '%$buscar%') AND (cod_dependencia = '$cod_dependencia')) ORDER BY nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$matriz_consulta = mysql_fetch_assoc($consulta);
}
echo "<font color='yellow'><strong>".$total_resultados." Resultados para: ".$buscar."</strong></font><br>";
} else { }

if ($total_resultados <> 0) {
$pagina = 'temporal_todo.php';
?>
<br>
<center>
<table width="100%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>PV</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
</tr>
<?php do { 
$cod_productos_var      = $matriz_consulta['cod_productos_var'];
$nombre_productos       = $matriz_consulta['nombre_productos'];
$unidades_faltantes     = $matriz_consulta['unidades_faltantes'];
$detalles               = $matriz_consulta['detalles'];
$precio_venta           = $matriz_consulta['precio_venta'];
?>
<td><?php echo $cod_productos_var; ?></td>

<td><a href="../modificar_eliminar/agregar_lista_temporal.php?cod_productos=<?php echo $cod_productos_var?>&pagina=<?php echo $pagina?>&cod_factura=0"><?php echo utf8_encode($nombre_productos)?></a></td>
<td align="center"><font size= "+2"><?php echo $unidades_faltantes; ?></font></td>
<td><font size= "+2"><?php echo $detalles; ?></font></td>
<td align="right"><font size= "+2"><?php echo number_format($precio_venta, 0, ",", "."); ?></font></td>
</tr>
<?php 
}
while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
}
?>