<?php
$cuenta_actual = addslashes($_SESSION['usuario']);
$suma_factura = "SELECT sum(precio_compra_con_descuento * unidades_total) as precio_compra_con_descuento FROM cargar_transferencias_temporal WHERE vendedor = '$cuenta_actual'";
$consulta_suma = mysql_query($suma_factura, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_suma);

$transferencias_temporal = "SELECT cod_cargar_transferencias_temporal, cod_productos FROM cargar_transferencias_temporal WHERE vendedor = '$cuenta_actual'";
$consulta_transferencias = mysql_query($transferencias_temporal, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_transferencias);

$maxima_factura = "SELECT Max(cod_factura) AS cod_factura FROM info_transferencias";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);
?>
<center>
<table id="table" width="100%">
<form method="post" name="formulario" action="../admin/guardar_transferencias.php">

<input type="hidden" name="cod_factura" value="<?php echo $maxima['cod_factura']+1?>" size="1">

<td><strong>FECHA: </strong><input type="text" style="font-size:15px" name="fecha" value="<?php echo date("d/m/Y")?>" size="10" required autofocus>
	
<td><strong>ALMACEN: </strong><select name="cod_transferencias_almacenes">
<?php $sql_consulta = "SELECT cod_transferencias_almacenes, nombre_almacen FROM transferencias_almacenes ORDER BY nombre_almacen ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['cod_transferencias_almacenes'] ?>"><?php echo $contenedor['nombre_almacen'] ?></option>
<?php }?></select></td>
<!--<td><strong>%DTO1:</strong><input type="text" style="font-size:15px" name="dto1" value="0" size="1" required autofocus></td>
<td><strong>%DTO2:</strong><input type="text" style="font-size:15px" name="dto2" value="0" size="1" required autofocus></td>
<td><strong>%IVA:</strong>-->
<!--</td>-->
<input type="hidden" name="verificacion" value="verificacion" size="1">
<td><div align="right"><strong>TOTAL:</strong><input type="text" style="font-size:15px" name="total" value="<?php echo $suma['precio_compra_con_descuento'];?>" size="6" required autofocus></div></td>

<input type="hidden" name="total_datos" value="<?php echo $total_datos?>" size="1">

<?php while ($datos = mysql_fetch_assoc($consulta_transferencias)) { ?>
<input type="hidden" name="cod_cargar_transferencias_temporal[]" value="<?php echo $datos['cod_cargar_transferencias_temporal']; ?>">
<?php } ?>

<td><div align="right"><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>
</table>
</form>