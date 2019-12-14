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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$buscar = addslashes($_POST['palabra']);
$mostrar_datos_sql = "SELECT * FROM productos WHERE (unidades_faltantes <= '0') OR (nombre_productos like '%$buscar%' 
  OR cod_productos_var like '$buscar%') ORDER BY unidades_faltantes";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php if ($total_datos <> 0) {
//require_once("../busquedas/busqueda_productos_agotados.php");?>
<body>
<center>
<td><strong><font color='white'>PRODUCTOS AGOTADOS: </font></strong></td><br><br>
<table width="80%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
</tr>
<?php while ($datos = mysql_fetch_assoc($consulta)) { ?>
<tr>
<td><?php echo $datos['cod_productos_var'];?></td>
<td><?php echo $datos['nombre_productos'];?></td>
<td align="right"><?php echo $datos['unidades_faltantes'];?></td>
<td align="left"><?php echo $datos['detalles']; ?></td>
<td align="right"><?php echo $datos['precio_venta'];?></td>

</tr>
<?php } ?>
</table>
<?php } else {
echo "<center><font color='yellow'>No existen productos agotados</font></center>";
} ?>
</body>
</html>
<?php mysql_free_result($consulta);?>