<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
/*
$datos_factura = "SELECT * FROM stiker_productos_estante_temporal WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
*/
$sql = "SELECT * FROM stiker_productos_estante_temporal WHERE vendedor = '$cuenta_actual' ORDER BY cod_stiker_productos_estante_temporal DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
require_once("busqueda_inmediata_stiker_productos_estante_temporal_por_cod.php");
if ($total_datos <> 0) {
require_once("informacion_stiker_productos_estante.php");
}
?>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'activo';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inactivo';
if (last != valor)
myajax.Link('guardar_stiker_productos_estante_temporal.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
if ($total_datos <> 0) {
?>
<table width="100%">
<tr>
<td align='center' title="Eliminar registro de la factura."><strong>ELM</strong></td>
<td align='center' title="Codigo del producto."><font><strong>C&Oacute;DIGO</strong></font></td>
<td align='center' title="Nombre del producto."><font><strong>PRODUCTO</strong></font></td>
<td align='center' title="Presentacion. cantidad de unidades de ese producto."><strong>UND</strong></td>
<td align='center' title="Total precio venta del producto. (T.FACTDO * +%)"><strong>P.VENTA1</strong></td>
<td align='center' title="Precio venta 2"><strong>P.VENTA2</strong></td>
<td align='center' title="Precio venta 3"><strong>P.VENTA3</strong></td>
<td align='center' title="Precio venta 3"><strong>P.VENTA4</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_stiker_productos_estante_temporal = $datos['cod_stiker_productos_estante_temporal'];
$cod_productos = $datos['cod_productos'];
$integrado = $cod_stiker_productos_estante_temporal.'-'.$cod_productos;
$nombre_productos = $datos['nombre_productos'];
$cajas = $datos['cajas'];
$unidades = $datos['unidades'];
$unidades_vendidas = $datos['unidades_vendidas'];
$unidades_total = $datos['unidades_total'];
$tope_min = $datos['tope_min'];
$precio_compra = $datos['precio_compra'];
$precio_venta = $datos['precio_venta'];
$precio_venta2 = $datos['precio_venta2'];
$precio_venta3 = $datos['precio_venta3'];
$precio_venta4 = $datos['precio_venta4'];
$precio_venta5 = $datos['precio_venta5'];
$dto1 = $datos['dto1'];
$dto2 = $datos['dto2'];
$iva = $datos['iva'];
$valor_iva = $datos['valor_iva'];
$ptj_ganancia = $datos['ptj_ganancia'];
$precio_costo = $datos['precio_costo'];
$vlr_total_compra = $datos['vlr_total_compra'];
$porcentaje_vendedor = $datos['porcentaje_vendedor'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
$vlr_total_venta = $datos['vlr_total_venta'];
$precio_compra_con_descuento = $datos['precio_compra_con_descuento'];
$descuento = $datos['descuento'];
$detalles = $datos['detalles'];
$total_precio_compra = $datos['precio_compra'] * $datos['cajas'];
$vlr_total_venta = $datos['vlr_total_venta'];
$ptj_ganancia = $datos['ptj_ganancia'];
?>
<tr>
<td title="Eliminar este registro de la factura."><a href="../modificar_eliminar/eliminar_stiker_productos_estante_temporal.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_stiker_productos_estante_temporal=<?php echo $cod_stiker_productos_estante_temporal;?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td title="Codigo del producto."><?php echo $cod_productos;?></td>
<td title="Nombre del producto."><?php echo $nombre_productos;?></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_stiker_productos_estante_temporal;?>)" class="cajpequena" id="<?php echo $cod_stiker_productos_estante_temporal;?>" value="<?php echo $cajas;?>" size="3"></td>
<td align='center'><?php echo $precio_venta;?></td>
<td align='center'><?php echo $precio_venta2;?></td>
<td align='center'><?php echo $precio_venta3;?></td>
<td align='center'><?php echo $precio_venta4;?></td>
</tr>
<?php } ?>
</table>
<?php
} else {
}
?>
</form>