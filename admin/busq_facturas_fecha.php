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
include ("../registro_movimientos/registro_movimientos.php");

if (isset($_POST['opcion_envio'])) { $opcion_envio    = addslashes($_POST['opcion_envio']); }
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">

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
myajax.Link('guardar_devoluciones_facturas.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
</head>

<body onLoad="myajax = new isiAJAX();">
<center>
<br>
<td><strong><font color='white'>BUSCAR FACTURA DE VENTA</font></strong></td><br>
<br>
<form method="post" name="formulario" action="">
<table align="center" id="table">

<input name="buscar" required autofocus>

<select name="opcion_envio" class="selectpicker" data-show-subtext="true" data-live-search="true">
  <option value="cod_factura">POR FACTURA</option>
  <option value="cod_productos">POR CODIGO PRODUCTO</option>
  <option value="nombre_productos">POR NOMBRE PRODUCTO</option>
  <option value="nombres">POR NOMBRE CLIENTE</option>
  <option value="fecha_anyo">POR FECHA VENTA</option>
</select>

<input type="submit" name="buscador" value="BUSCAR INFORMACION" />
</table>
</form>

<?php
if (isset($_POST['opcion_envio'])) {

$pagina = $_SERVER['PHP_SELF'];

if ($opcion_envio == 'cod_factura') {
$buscar    = intval($_POST['buscar']);
$sql = "SELECT ventas.cod_ventas, ventas.cod_productos, ventas.nombre_productos, ventas.unidades_vendidas, ventas.detalles, 
ventas.precio_compra, ventas.precio_venta, ventas.vlr_total_venta, ventas.cod_factura, ventas.tipo_pago, ventas.fecha_anyo, 
ventas.fecha_hora, ventas.vendedor, ventas.comentario, ventas.descuento, ventas.cod_clientes, clientes.nombres, clientes.apellidos 
FROM ventas, clientes WHERE (ventas.cod_clientes = clientes.cod_clientes) AND (ventas.cod_factura LIKE '$buscar')  ORDER BY ventas.fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
}
if ($opcion_envio == 'cod_productos') {
$buscar    = addslashes($_POST['buscar']);
$sql = "SELECT ventas.cod_ventas, ventas.cod_productos, ventas.nombre_productos, ventas.unidades_vendidas, ventas.detalles, 
ventas.precio_compra, ventas.precio_venta, ventas.vlr_total_venta, ventas.cod_factura, ventas.tipo_pago, ventas.fecha_anyo, 
ventas.fecha_hora, ventas.vendedor, ventas.comentario, ventas.descuento, ventas.cod_clientes, clientes.nombres, clientes.apellidos 
FROM ventas, clientes WHERE (ventas.cod_clientes = clientes.cod_clientes) AND (ventas.cod_productos LIKE '$buscar%')  ORDER BY ventas.fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
}
if ($opcion_envio == 'nombre_productos') {
$buscar    = addslashes($_POST['buscar']);
$sql = "SELECT ventas.cod_ventas, ventas.cod_productos, ventas.nombre_productos, ventas.unidades_vendidas, ventas.detalles, 
ventas.precio_compra, ventas.precio_venta, ventas.vlr_total_venta, ventas.cod_factura, ventas.tipo_pago, ventas.fecha_anyo, 
ventas.fecha_hora, ventas.vendedor, ventas.comentario, ventas.descuento, ventas.cod_clientes, clientes.nombres, clientes.apellidos 
FROM ventas, clientes WHERE (ventas.cod_clientes = clientes.cod_clientes) AND (ventas.nombre_productos LIKE '$buscar%')  ORDER BY ventas.fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
}
if ($opcion_envio == 'nombres') {
$buscar    = addslashes($_POST['buscar']);
$sql = "SELECT ventas.cod_ventas, ventas.cod_productos, ventas.nombre_productos, ventas.unidades_vendidas, ventas.detalles, 
ventas.precio_compra, ventas.precio_venta, ventas.vlr_total_venta, ventas.cod_factura, ventas.tipo_pago, ventas.fecha_anyo, 
ventas.fecha_hora, ventas.vendedor, ventas.comentario, ventas.descuento, ventas.cod_clientes, clientes.nombres, clientes.apellidos 
FROM ventas, clientes WHERE (ventas.cod_clientes = clientes.cod_clientes) AND (clientes.nombres LIKE '$buscar%')  ORDER BY ventas.fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
}
if ($opcion_envio == 'fecha_anyo') {
$buscar    = addslashes($_POST['buscar']);
$sql = "SELECT ventas.cod_ventas, ventas.cod_productos, ventas.nombre_productos, ventas.unidades_vendidas, ventas.detalles, 
ventas.precio_compra, ventas.precio_venta, ventas.vlr_total_venta, ventas.cod_factura, ventas.tipo_pago, ventas.fecha_anyo, 
ventas.fecha_hora, ventas.vendedor, ventas.comentario, ventas.descuento, ventas.cod_clientes, clientes.nombres, clientes.apellidos 
FROM ventas, clientes WHERE (ventas.cod_clientes = clientes.cod_clientes) AND (ventas.fecha_anyo LIKE '$buscar')  ORDER BY ventas.fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
}
?>
<br>
<table width="100%">
<tr>
<td align="center"><strong>IMP</strong></td>
<td align="center"><strong>DUPL</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UNDS</strong></td>
<td align="center"><strong>V. UNIT</strong></td>
<td align="center"><strong>V. TOTAL</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>ELIM</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas               = $datos['cod_ventas'];
$cod_productos            = $datos['cod_productos'];
$nombre_productos         = $datos['nombre_productos'];
$unidades_vendidas        = $datos['unidades_vendidas'];
$detalles                 = $datos['detalles'];
$precio_compra            = $datos['precio_compra'];
$precio_venta             = $datos['precio_venta'];
$vlr_total_venta          = $datos['vlr_total_venta'];
$cod_factura              = $datos['cod_factura'];
$nombres                  = $datos['nombres'];
$apellidos                = $datos['apellidos'];
$tipo_pago                = $datos['tipo_pago'];
$fecha_anyo               = $datos['fecha_anyo'];
$fecha_hora               = $datos['fecha_hora'];
$vendedor                 = $datos['vendedor'];
$comentario               = $datos['comentario'];
$descuento                = $datos['descuento'];
$cod_clientes             = $datos['cod_clientes'];
?>
<tr>
<td align='center'><a href="../admin/buscar_facturas_listado_no_edit.php?cod_factura=<?php echo $cod_factura?>&fecha_anyo=<?php echo $fecha_anyo?>&descuento=<?php echo $descuento?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>"><img src="../imagenes/imprimir_directa_pos_peq.png"></a></td> 
<td align='center'><a href="../admin/agregar_duplicar_factura_venta.php?cod_factura=<?php echo $cod_factura?>&fecha_anyo=<?php echo $fecha_anyo?>&descuento=<?php echo $descuento?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>"><img src="../imagenes/duplicar_factura_venta.png"></a></td>
<td align='center'><a href="../admin/buscar_facturas_listado.php?cod_factura=<?php echo $cod_factura?>"><?php echo $cod_factura?></a></td>
<td><font><?php echo $cod_productos; ?></td></font>
<td><font><?php echo $nombre_productos; ?></td></font>
<td align="right"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_ventas;?>)" class="cajextragrand" id="b<?php echo $cod_ventas;?>" value="<?php echo $unidades_vendidas;?>" size="9"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $precio_venta;?>" size="9"></td>
<td align="right"><font><?php echo number_format($vlr_total_venta, 0, ",", "."); ?></td></font>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'comentario', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $comentario;?>" size="3"></td>
<td align="left"><font><?php echo $nombres." ".$apellidos;?></td></font>
<td align="center"><font><?php echo $tipo_pago?></td></font>
<td align="right"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fecha_anyo', <?php echo $cod_ventas;?>)" class="cajbarras" id="b<?php echo $cod_ventas;?>" value="<?php echo $fecha_anyo;?>" size="3"></td>
<td align="center"><font><?php echo $fecha_hora; ?></td></font>
<td align="center"><font><?php echo $vendedor; ?></td></font>
<td  nowrap><a href="../modificar_eliminar/eliminar_productos_factura.php?cod_ventas=<?php echo $cod_ventas?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
</tr>	 
<?php } ?>
<?php } ?>
</center>
</body>
</html> 