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
$cuenta_actual = addslashes($_SESSION['usuario']);
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

$mostrar_datos_sql = "SELECT facturas_cargadas_inv.cod_productos, facturas_cargadas_inv.nombre_productos, proveedores.nombre_proveedores, 
facturas_cargadas_inv.tipo_pago, Sum(facturas_cargadas_inv.unidades_total) AS SumaDeunidades_total, facturas_cargadas_inv.precio_costo, 
facturas_cargadas_inv.fecha_mes, proveedores.cod_proveedores
FROM proveedores RIGHT JOIN facturas_cargadas_inv ON proveedores.cod_proveedores = facturas_cargadas_inv.cod_proveedores
GROUP BY facturas_cargadas_inv.cod_productos, proveedores.nombre_proveedores, facturas_cargadas_inv.fecha_mes
HAVING (((facturas_cargadas_inv.cod_productos)='$buscar')) OR (((facturas_cargadas_inv.nombre_productos) LIKE '$buscar%')) 
ORDER BY facturas_cargadas_inv.fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_reg = mysql_num_rows($consulta);

 echo "<font color='yellow'><strong>".$tota_reg." Resultados para: ".$buscar."</strong></font><br>";
?>
<br>
<center>
<table width='90%'>
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>UND COMPRA</strong></td>
<td align="center"><strong>PRECIO COSTO</strong></td>
<!--<td align="center"><strong>TOTAL COSTO</strong></td>-->
<td align="center"><strong>MES DE COMPRA</strong></td>
</tr>
<?php while ($datos = mysql_fetch_assoc($consulta)) { 
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$nombre_proveedores = $datos['nombre_proveedores'];
$tipo_pago = $datos['tipo_pago'];
$SumaDeunidades_total = $datos['SumaDeunidades_total'];
$precio_costo = $datos['precio_costo'];
$precio_compra_con_descuento = $datos['precio_compra_con_descuento'];
$fecha_mes = $datos['fecha_mes'];
$cod_proveedores = $datos['cod_proveedores'];
?>
<td><font size ='3px'><?php echo $cod_productos; ?></font></td>
<td><a href="../admin/buscar_para_hacer_compra_de_producto_historial_detallado.php?cod_productos=<?php echo $cod_productos?>&fecha_mes=<?php echo $fecha_mes?>"><font size ='3px'><?php echo $nombre_productos; ?></a></font></td>
<td><a href="../admin/buscar_para_hacer_compra_de_producto_historial_detallado.php?cod_productos=<?php echo $cod_productos?>&cod_proveedores=<?php echo $cod_proveedores?>"><font size ='3px'><?php echo $nombre_proveedores; ?></a></font></td>
<td><font size ='3px'><?php echo strtoupper($tipo_pago); ?></font></td>
<td align="right"><font size ='3px'><?php echo $SumaDeunidades_total; ?></font></td>
<td align="right"><font size ='3px'><?php echo number_format($precio_costo, 0, ",", "."); ?></font></td>
<!--<td align="right"><font size ='3px'><?php echo number_format($precio_compra_con_descuento, 0, ",", "."); ?></font></td>-->
<td align="center"><font size ='3px'><?php echo $fecha_mes; ?></font></td>
</tr>
<?php } ?>
</table>
