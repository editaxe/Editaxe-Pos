<?php
$cuenta_actual = addslashes($_SESSION['usuario']);

$suma_factura = "SELECT sum(vlr_total_venta) as vlr_total_venta, sum(precio_costo * unidades_total) as vlr_total_compra, sum(descuento) as descuento,
sum(precio_compra_con_descuento) as precio_compra_con_descuento, sum(valor_iva * unidades_total) as valor_iva , sum(precio_costo) as precio_costo 
FROM cargar_factura_temporal WHERE vendedor = '$cuenta_actual'";
$consulta_suma = mysql_query($suma_factura, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_suma);

$datos_factura = "SELECT * FROM cargar_factura_temporal WHERE vendedor = '$cuenta_actual'";
$consultan = mysql_query($datos_factura, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consultan);
?>
<center>
<table width="100%">
<form method="post" name="formulario" action="../admin/guardar_factura_productos.php">
<td ><a href="../admin/productos.php" tabindex=3><img src=../imagenes/mas.png alt="mas"></a></td>
<td><strong>FECHA: </strong><input type="text" style="font-size:15px" tabindex=3 name="fecha" value="<?php echo date("d/m/Y")?>" size="10" required>

<td><strong>FECHA PAGO: </strong><br>
<input type="text" style="font-size:15px" tabindex=3 name="fecha_pago" value="<?php echo date("d/m/Y")?>" size="10">

<td><strong>FACTURA: </strong><input type="text" style="font-size:15px" tabindex=3 name="numero_factura" value="" size="7" required>
	
<td><strong>PROVEEDOR: </strong><select name="cod_proveedores" required tabindex=3>
<?php $sql_consulta="SELECT cod_proveedores, nombre_proveedores FROM proveedores ORDER BY proveedores.cod_proveedores ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['cod_proveedores'] ?>"><?php echo $contenedor['nombre_proveedores'] ?></option>
<?php }?></select></td>
<!--<td><strong>%DTO1:</strong><input type="text" style="font-size:15px" name="dto1" value="0" size="1" required autofocus></td>
<td><strong>%DTO2:</strong><input type="text" style="font-size:15px" name="dto2" value="0" size="1" required autofocus></td>
<td><strong>%IVA:</strong>-->
<input type="hidden" style="font-size:15px" name="iva" value="0" size="1" required autofocus>
<!--</td>-->
<input type="hidden" name="verificacion" value="verificacion" size="1">

<td><label><input type="radio" name="tipo_pago" tabindex=3 value="contado" checked>
<strong>CONTADO</strong></label>
<br>
<label><input type="radio" name="tipo_pago" tabindex=3 value="credito"> 
<strong>CREDITO</strong></label></td>

<td align="right"><strong>V.BRUTO(SN.IVA):</strong><input type="text" style="font-size:15px" tabindex=3 name="valor_bruto" value="<?php echo $suma['vlr_total_compra'];?>" size="6" required autofocus></td>
<td align="right"><strong>DESCUENTO:</strong><input type="text" style="font-size:15px" tabindex=3 name="descuento_factura" value="<?php echo $suma['descuento'];?>" size="6" required autofocus></td>
<td align="right"><strong>V.NETO:</strong><input type="text" style="font-size:15px" tabindex=3 name="valor_neto" value="<?php echo $suma['vlr_total_compra'];?>" size="6" required autofocus></td>
<td align="right"><strong>V.IVA:</strong><input type="text" style="font-size:15px" tabindex=3 name="valor_iva" value="<?php echo $suma['valor_iva'];?>" size="6" required autofocus></td>
<td align="right"><strong>TOTAL:</strong><input type="text" style="font-size:15px" tabindex=3 name="total" value="<?php echo $suma['precio_compra_con_descuento'];?>" size="6" required autofocus></td>

<input type="hidden" name="total_datos" value="<?php echo $total_datos; ?>" size="4">

<?php while ($datos = mysql_fetch_assoc($consultan)) {?>
<input type="hidden" name="cod_cargar_factura_temporal[]" value="<?php echo $datos['cod_cargar_factura_temporal']; ?>" size="4">
<?php 
}
$pagina ='cargar_factura_temporal.php'
?>
<td align="right"><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>
</table>
</form>