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

$cod_factura = intval($_GET['cod_factura']);

$sql = "SELECT * FROM stiker_productos_estante WHERE cod_factura = '$cod_factura' ORDER BY cod_stiker_productos_estante DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>

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
myajax.Link('guardar_stiker_productos_estante_ajax.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>

<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
if ($total_datos <> 0) {
?>
<center>
<fieldset><legend></legend>
<table width='40%'>
<br>
<tr>
<td align="center"><strong>STIKER SIN BARRAS</td>
<td align="center"><strong>STIKER CON BARRAS </td>
<td align="center"><strong>STIKER SIN BARRAS GRANDE</td>
</tr>
<tr>
<td align="center"><a href="../admin/imprimir_stiker_productos_estante_sin_barras.php?cod_factura=<?php echo $cod_factura?>" target="_blank"><img src=../imagenes/sticker_estandar_128.jpg alt="sticker"></a></td>
<td align="center"><a href="../admin/imprimir_stiker_productos_estante_con_barras.php?cod_factura=<?php echo $cod_factura?>" target="_blank"><img src=../imagenes/sticker_estandar_128.jpg alt="sticker"></a></td>
<td align="center"><a href="../admin/imprimir_stiker_productos_estante_sin_barras_grande.php?cod_factura=<?php echo $cod_factura?>" target="_blank"><img src=../imagenes/sticker_estandar_128.jpg alt="sticker"></a></td>
</tr>
</table>
</center>
</fieldset>
<br>
<fieldset><legend><font color='yellow' size='+3'>STIKER FACTURA: <?php echo str_pad($cod_factura, 5, "0", STR_PAD_LEFT);?></font></legend>

<table width="100%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>IP</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_stiker_productos_estante = $datos['cod_stiker_productos_estante'];
$cod_productos = $datos['cod_productos'];
$integrado = $cod_stiker_productos_estante.'-'.$cod_productos;
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
$cod_factura = $datos['cod_factura'];
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
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_total', <?php echo $cod_stiker_productos_estante;?>)" class="cajpequena" id="<?php echo $cod_stiker_productos_estante;?>" value="<?php echo $unidades_total;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_factura', <?php echo $cod_stiker_productos_estante;?>)" class="cajpequena" id="<?php echo $cod_stiker_productos_estante;?>" value="<?php echo $cod_factura;?>" size="3"></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="center"><?php echo $datos['fecha_anyo']; ?></td>
<td align="center"><?php echo $datos['fecha_hora']; ?></td>
<td align="center"><?php echo $datos['ip']; ?></td>
</tr>
<?php } ?>
</table>
<?php
} else {
}
?>
</form>