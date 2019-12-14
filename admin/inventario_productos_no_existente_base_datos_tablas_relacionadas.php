<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
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

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
<body>
<br>
<center>
<br>
<center>
<td><strong><font color='white' size='5px'><font color='yellow' size='5px'><strong>PRODUCTOS DE SUPER TIENDA LA AVENIDA QUE NO HAN SIDO AGREGARDOS A ABASTOS LA AVENIDA</font></a></strong></td>
<br><br>
<td><strong><font color='white' size='5px'><font color='yellow' size='5px'><strong>SUPER TIENDA LA AVENIDA >>>>> ABASTOS LA AVENIDA</font></a></strong></td><br><br>

<table width='100%' border='1'>
<tr>
<td align="center">AGREGAR</td>
<td align="center">C&Oacute;DIGO</td>
<td align="center">PRODUCTO</td>
<td align="center">T.UND</td>
<td align="center">MET</td>
<td align="center">P.COMPRA</td>
<td align="center">P.VENTA1</td>
<td align="center">P.VENTA2</td>
<td align="center">P.VENTA3</td>
<td align="center">IVA</td>
</tr>
<?php 
$mostrar_datos_sql = "SELECT super_abastosavenida.productos.cod_productos_var AS cod_productos_var_super_abastosavenida, 
super_tiendavenida.productos.cod_productos, super_tiendavenida.productos.cod_productos_var, 
super_tiendavenida.productos.nombre_productos, super_tiendavenida.productos.unidades, super_tiendavenida.productos.unidades_faltantes, 
super_tiendavenida.productos.detalles, super_tiendavenida.productos.precio_costo, super_tiendavenida.productos.precio_venta, 
super_tiendavenida.productos.precio_venta2, super_tiendavenida.productos.precio_venta3, super_tiendavenida.productos.precio_compra, 
super_tiendavenida.productos.vlr_total_venta, super_tiendavenida.productos.iva 
FROM super_tiendavenida.productos LEFT JOIN super_abastosavenida.productos ON super_tiendavenida.productos.cod_productos_var = super_abastosavenida.productos.cod_productos_var
WHERE (super_abastosavenida.productos.cod_productos_var IS NULL) ORDER BY super_tiendavenida.productos.nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos             = $datos['cod_productos'];
$cod_productos_var         = $datos['cod_productos_var'];
$nombre_productos          = $datos['nombre_productos'];
$unidades                  = $datos['unidades'];
$unidades_faltantes        = $datos['unidades_faltantes'];
$detalles                  = $datos['detalles'];
$precio_costo              = $datos['precio_costo'];
$precio_venta              = $datos['precio_venta'];
$precio_venta2             = $datos['precio_venta2'];
$precio_venta3             = $datos['precio_venta3'];
$precio_compra             = $datos['precio_compra'];
$vlr_total_venta           = $datos['vlr_total_venta'];
$iva                       = $datos['iva'];
?>
<tr>
<td align='center'><a href="../admin/reg_traspaso_de_productos.php?cod_productos=<?php echo $cod_productos ?>&pagina=<?php echo $pagina ?>"><center><img src=../imagenes/agregar.png alt="agregar"></center></a></td> 
<td align='center'><?php echo $cod_productos_var;?></td>
<td align='left'><?php echo $nombre_productos;?></td>
<td align='center'><?php echo $unidades_faltantes;?></td>
<td align='center'><?php echo $detalles;?></td>
<td align='center'><?php echo $precio_costo;?></td>
<td align='center'><?php echo $precio_venta;?></td>
<td align='center'><?php echo $precio_venta2;?></td>
<td align='center'><?php echo $precio_venta3;?></td>
<td align='center'><?php echo $iva;?></td>
</tr>
<?php } ?>
</table>
</body>
</html>