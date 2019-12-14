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
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

if (isset($_GET['cod_productos']) && isset($_GET['fecha_mes'])) {

$cod_productos = addslashes($_GET['cod_productos']);
$fecha_mes = addslashes($_GET['fecha_mes']);

$mostrar_datos_sql = "SELECT facturas_cargadas_inv.cod_productos, facturas_cargadas_inv.nombre_productos, proveedores.nombre_proveedores, 
facturas_cargadas_inv.tipo_pago, facturas_cargadas_inv.unidades_total, facturas_cargadas_inv.precio_costo, facturas_cargadas_inv.cod_factura, 
facturas_cargadas_inv.fecha_mes, facturas_cargadas_inv.fecha_anyo, facturas_cargadas_inv.fecha_hora, proveedores.cod_proveedores AS idproveedores, 
facturas_cargadas_inv.cod_proveedores
FROM proveedores RIGHT JOIN facturas_cargadas_inv ON proveedores.cod_proveedores = facturas_cargadas_inv.cod_proveedores
HAVING (((facturas_cargadas_inv.cod_productos)='$cod_productos')) AND (((facturas_cargadas_inv.fecha_mes) = '$fecha_mes')) 
ORDER BY facturas_cargadas_inv.fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_reg = mysql_num_rows($consulta);
}
if (isset($_GET['cod_productos']) && isset($_GET['cod_proveedores'])) {

$cod_productos = addslashes($_GET['cod_productos']);
$cod_proveedores = intval($_GET['cod_proveedores']);

$mostrar_datos_sql = "SELECT facturas_cargadas_inv.cod_productos, facturas_cargadas_inv.nombre_productos, proveedores.nombre_proveedores, 
facturas_cargadas_inv.tipo_pago, facturas_cargadas_inv.unidades_total, facturas_cargadas_inv.precio_costo, facturas_cargadas_inv.cod_factura, 
facturas_cargadas_inv.fecha_mes, facturas_cargadas_inv.fecha_anyo, facturas_cargadas_inv.fecha_hora, proveedores.cod_proveedores AS idproveedores, 
facturas_cargadas_inv.cod_proveedores
FROM proveedores RIGHT JOIN facturas_cargadas_inv ON proveedores.cod_proveedores = facturas_cargadas_inv.cod_proveedores
HAVING (((facturas_cargadas_inv.cod_productos)='$cod_productos')) AND (((facturas_cargadas_inv.cod_proveedores) = '$cod_proveedores')) 
ORDER BY facturas_cargadas_inv.fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_reg = mysql_num_rows($consulta);
}
?>
<br>
<center>
<a href="../admin/buscar_para_hacer_compra_de_producto_historial.php"><font size='+2' color='yellow'><strong>Resultados para Codigo: <?php echo $cod_productos ?></strong></font></a>
<br>
<table width='90%'>
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>UND COMPRA</strong></td>
<td align="center"><strong>PRECIO COSTO</strong></td>
<!--<td align="center"><strong>TOTAL COSTO</strong></td>-->
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php while ($datos = mysql_fetch_assoc($consulta)) { 
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$nombre_proveedores = $datos['nombre_proveedores'];
$tipo_pago = $datos['tipo_pago'];
$unidades_total = $datos['unidades_total'];
$precio_costo = $datos['precio_costo'];
$precio_compra_con_descuento = $datos['precio_compra_con_descuento'];
$fecha_anyo = $datos['fecha_anyo'];
$cod_proveedores = $datos['cod_proveedores'];
$fecha_hora = $datos['fecha_hora'];
$cod_factura = $datos['cod_factura'];
?>
<td><font size ='3px'><?php echo $cod_productos; ?></font></td>
<td><font size ='3px'><?php echo $nombre_productos; ?></font></td>
<td><font size ='3px'><?php echo $cod_factura; ?></font></td>
<td><font size ='3px'><?php echo $nombre_proveedores; ?></font></td>
<td><font size ='3px'><?php echo strtoupper($tipo_pago); ?></font></td>
<td align="right"><font size ='3px'><?php echo $unidades_total; ?></font></td>
<td align="right"><font size ='3px'><?php echo number_format($precio_costo, 0, ",", "."); ?></font></td>
<!--<td align="right"><font size ='3px'><?php echo number_format($precio_compra_con_descuento, 0, ",", "."); ?></font></td>-->
<td align="center"><font size ='3px'><?php echo $fecha_anyo; ?></font></td>
<td align="center"><font size ='3px'><?php echo $fecha_hora; ?></font></td>
</tr>
<?php } ?>
</table>