<?php
$cuenta_actual = addslashes($_SESSION['usuario']);

$suma_factura = "SELECT sum(vlr_total_venta) as vlr_total_venta, sum(precio_costo * unidades_total) as vlr_total_compra, sum(descuento) as descuento,
sum(precio_compra_con_descuento) as precio_compra_con_descuento, sum(valor_iva * unidades_total) as valor_iva , sum(precio_costo) as precio_costo 
FROM stiker_productos_estante_temporal WHERE vendedor = '$cuenta_actual'";
$consulta_suma = mysql_query($suma_factura, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_suma);

$datos_factura = "SELECT * FROM stiker_productos_estante_temporal WHERE vendedor = '$cuenta_actual'";
$consultan = mysql_query($datos_factura, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consultan);
?>
<center>
<table width="100%">
<form method="post" name="formulario" action="../admin/guardar_stiker_productos_estante.php">

<td align="left"><strong>FECHA: </strong><input type="text" style="font-size:15px" tabindex=3 name="fecha" value="<?php echo date("d/m/Y")?>" size="10">

<td align="right"><strong>FACTURA: </strong><input type="text" style="font-size:15px" tabindex=3 name="cod_factura" value="" size="10">

<input type="hidden" name="total_datos" value="<?php echo $total_datos; ?>" size="4">

<?php while ($datos = mysql_fetch_assoc($consultan)) {?>
<input type="hidden" name="cod_stiker_productos_estante_temporal[]" value="<?php echo $datos['cod_stiker_productos_estante_temporal']; ?>" size="4">
<?php 
}
$pagina ='stiker_productos_estante_por_factura.php'
?>
<input type="hidden" name="pagina" value="<?php echo $pagina; ?>" size="4">
<td align="right"><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>
</table>
</form>