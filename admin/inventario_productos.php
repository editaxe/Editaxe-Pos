<?php error_reporting(E_ALL ^ E_NOTICE);
require_once("menu_inventario.php");

if (isset($_GET['campo'])) { $campo = addslashes($_GET['campo']); $ord = addslashes($_GET['ord']); } 
else { $campo = 'nombre_productos'; $ord = 'asc'; }
?>
<center>
<form action="" method="post">
<td><strong><font color='white'>BUSCAR PRODUCTOS: </font></strong></td><input name="buscar" required autofocus />
<input type="submit" name="buscador" value="Buscar productos" />
</form>
</center>
<?php
$buscar = addslashes($_POST['buscar']);

$total_productos_inventario = "SELECT * FROM productos";
$consulta_inventario = mysql_query($total_productos_inventario, $conectar) or die(mysql_error());
$total_productos = mysql_num_rows($consulta_inventario);
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$total_unidades = "SELECT Sum(unidades_faltantes) AS total_unidades_faltantes FROM productos WHERE detalles <> 'PVAR'";
$consulta_inventario_total_unidades = mysql_query($total_unidades, $conectar) or die(mysql_error());
$inventario_total_unidades = mysql_fetch_assoc($consulta_inventario_total_unidades);

$total_unidades_faltantes        = $inventario_total_unidades['total_unidades_faltantes'];
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$total_unidades_pvar = "SELECT Sum(unidades_faltantes) AS total_unidades_faltantes FROM productos WHERE detalles = 'PVAR'";
$consulta_inventario_total_unidades_pvar = mysql_query($total_unidades_pvar, $conectar) or die(mysql_error());
$inventario_total_unidades_pvar = mysql_fetch_assoc($consulta_inventario_total_unidades_pvar);

$total_unidades_faltantes_pvar   = $inventario_total_unidades_pvar['total_unidades_faltantes'];
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$calculos_inventario = "SELECT Sum(precio_costo * unidades_faltantes) As tot_precio_costo, Sum(precio_venta * unidades_faltantes) As tot_precio_venta, 
Sum(unidades_faltantes * precio_compra) As tot_precio_compra FROM productos WHERE detalles <> 'PVAR'";
$consulta_calculos_inventario = mysql_query($calculos_inventario, $conectar) or die(mysql_error());
$matriz_inventario = mysql_fetch_assoc($consulta_calculos_inventario);

$tot_precio_compra               = $matriz_inventario['tot_precio_compra'];
$tot_precio_venta                = $matriz_inventario['tot_precio_venta'];
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$calculos_inventario_pvar = "SELECT Sum(unidades_faltantes) As tot_precio_compra FROM productos WHERE detalles = 'PVAR'";
$consulta_calculos_inventario_pvar = mysql_query($calculos_inventario_pvar, $conectar) or die(mysql_error());
$matriz_inventario_pvar = mysql_fetch_assoc($consulta_calculos_inventario_pvar);

$tot_precio_compra_pvar          = $matriz_inventario_pvar['tot_precio_compra'];
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$sql_ultima_llave_productos = "SELECT max(cod_productos) AS ultimo FROM productos";
$consulta_ultima_llave_productos = mysql_query($sql_ultima_llave_productos, $conectar) or die(mysql_error());
$resultado = mysql_fetch_assoc($consulta_ultima_llave_productos);

$ultimo_id = $resultado['ultimo'];
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor) {
myajax.Link('guardar_inventario_productos.php?valor='+valor+'&campo='+campo+'&id='+id);
}
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<td align="center"><font color="yellow" size="+2"><strong>INVENTARIO DE PRODUCTOS - </font><a href="../admin/inventario_productos_no_editable.php"><font color="yellow" size="+2">OTRO</strong></font></a></td>
<br><br>
<!-- Total mercancia venta y utilidad mes -->
<table width='70%'  border='1'>
<td align="center">TOTAL CODIGOS</td>
<td align="center">TOTAL ITEMS</td>
<td align="center">TOTAL COMPRA INVENTARIO</td>
<td align="center">TOTAL COMPRA INVENTARIO PVAR</td>
<td align="center">TOTAL VENTA (PROYEC)</td>
<td align="center">ULTIMO ID</td>
<tr>
<td align="center"><font color="yellow" size="+1"><strong><?php echo $total_productos; ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_unidades_faltantes, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($tot_precio_compra, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($tot_precio_compra_pvar, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($tot_precio_venta, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo $ultimo_id; ?></font></td>

</tr>
</table>
<br>
<table width='100%' border='1'>
<tr>
<?php
if ($ord == 'desc') {?>
<td align="center">C&Oacute;DIGO <br><a href="../admin/inventario_productos.php?campo=cod_productos_var&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">PRODUCTO <br><a href="../admin/inventario_productos.php?campo=nombre_productos&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">T.UND <br><a href="../admin/inventario_productos.php?campo=unidades_faltantes&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">T.P <br><a href="../admin/inventario_productos.php?campo=detalles&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<!--
<td align="center">P.COSTO <br><a href="../admin/inventario_productos.php?campo=precio_costo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
-->
<td align="center">P.COMPRA <br><a href="../admin/inventario_productos.php?campo=precio_compra&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">P.VENTA1 <br><a href="../admin/inventario_productos.php?campo=precio_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">P.VENTA2 <br><a href="../admin/inventario_productos.php?campo=precio_venta2&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">P.VENTA3 <br><a href="../admin/inventario_productos.php?campo=precio_venta3&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">P.VENTA4 <br><a href="../admin/inventario_productos.php?campo=precio_venta4&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">IVA <br><a href="../admin/inventario_productos.php?campo=iva&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">U.PQ <br><a href="../admin/inventario_productos.php?campo=unidades&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">U.MIN.PV2 <br><a href="../admin/inventario_productos.php?campo=und_min_precio_venta_desc&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">DEPENDENCIA</td>
<?php } else { ?>
<td align="center">C&Oacute;DIGO <br><a href="../admin/inventario_productos.php?campo=cod_productos_var&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">PRODUCTO <br><a href="../admin/inventario_productos.php?campo=nombre_productos&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">T.UND <br><a href="../admin/inventario_productos.php?campo=unidades_faltantes&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">T.P <br><a href="../admin/inventario_productos.php?campo=detalles&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<!--
<td align="center">P.COSTO <br><a href="../admin/inventario_productos.php?campo=precio_costo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
-->
<td align="center">P.COMPRA <br><a href="../admin/inventario_productos.php?campo=precio_compra&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">P.VENTA1 <br><a href="../admin/inventario_productos.php?campo=precio_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">P.VENTA2 <br><a href="../admin/inventario_productos.php?campo=precio_venta2&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">P.VENTA3 <br><a href="../admin/inventario_productos.php?campo=precio_venta3&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">P.VENTA4 <br><a href="../admin/inventario_productos.php?campo=precio_venta4&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">IVA <br><a href="../admin/inventario_productos.php?campo=iva&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">U.PQ <br><a href="../admin/inventario_productos.php?campo=unidades&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">U.MIN.PV2 <br><a href="../admin/inventario_productos.php?campo=und_min_precio_venta_desc&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">DEPENDENCIA</td>
<?php } ?>
</tr>
<?php
$mostrar_datos_sql = "SELECT * FROM productos WHERE (cod_productos_var = '$buscar' OR nombre_productos LIKE '%$buscar%') ORDER BY $campo $ord";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
while ($datos = mysql_fetch_assoc($consulta)) {

$cod_productos                  = $datos['cod_productos'];
$cod_productos_var              = $datos['cod_productos_var'];
$nombre_productos               = $datos['nombre_productos'];
$unidades                       = $datos['unidades'];
$unidades_faltantes             = $datos['unidades_faltantes'];
$detalles                       = $datos['detalles'];
$precio_costo                   = $datos['precio_costo'];
$precio_venta                   = $datos['precio_venta'];
$precio_venta2                  = $datos['precio_venta2'];
$precio_venta3                  = $datos['precio_venta3'];
$precio_venta4                  = $datos['precio_venta4'];
$precio_venta5                  = $datos['precio_venta5'];
$precio_compra                  = $datos['precio_compra'];
$vlr_total_venta                = $datos['vlr_total_venta'];
$iva                            = $datos['iva'];
$cod_dependencia                = $datos['cod_dependencia'];
$und_min_precio_venta_desc      = $datos['und_min_precio_venta_desc'];
?>
<tr>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_productos_var', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $cod_productos_var;?>" size="3"></td>
<td align='left'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_productos', <?php echo $cod_productos;?>)" class="cajsuper" id="<?php echo $cod_productos;?>" value="<?php echo $nombre_productos;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_faltantes', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $unidades_faltantes;?>" size="3"></td>
<td align="center">
<select name="detalles" id="detalles-<?php echo $cod_productos;?>" class="<?php echo $cod_productos;?>">
<?php if (isset($detalles)) { echo "<option style='font-size:20px' value='' >Selecione</option>";
} else { echo  "<option value='' selected >Selecione</option>"; }
$sql_consulta2 = "SELECT cod_metrica, nombre_metrica FROM metrica ORDER BY cod_metrica ASC";
$consulta2 = mysql_query($sql_consulta2, $conectar);
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($detalles) and $detalles == $datos2['nombre_metrica']) {
$seleccionado = "selected";
} else { $seleccionado = ""; }
$codigo = $datos2['nombre_metrica'];
$nombre = $datos2['nombre_metrica'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?>
</td>
<!--
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_costo', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_costo;?>" size="3"></td>
-->
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_compra;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta2', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta2;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta3', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta3;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta4', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta4;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $iva;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'und_min_precio_venta_desc', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $und_min_precio_venta_desc;?>" size="3"></td>

<td align="center">
<select name="cod_dependencia" id="cod_dependencia-<?php echo $cod_productos;?>" class="<?php echo $cod_productos;?>">
<?php if (isset($cod_dependencia)) { echo "<option style='font-size:20px' value='' >Selecione</option>";
} else { echo  "<option value='' selected >Selecione</option>"; }
$sql_consulta2 = "SELECT cod_dependencia, nombre_dependencia FROM dependencia ORDER BY cod_dependencia ASC";
$consulta2 = mysql_query($sql_consulta2, $conectar);
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($cod_dependencia) and $cod_dependencia == $datos2['cod_dependencia']) {
$seleccionado = "selected";
} else { $seleccionado = ""; }
$codigo = $datos2['cod_dependencia'];
$nombre = $datos2['nombre_dependencia'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?>
</td>

</tr>
<?php } ?>
</table>
</form>

<script type="text/javascript" src="../admin/js/jquery.min.js"></script>
<script>  
 $(document).ready(function(){  

  $('select[name="cod_dependencia"]').change(function(){ 
  var cod_dependencia = $(this).val();  
  let id = this.id;
    $.ajax({ url:"guardar_inventario_productos.php", method:"GET", data:{valor:cod_dependencia, campo:"cod_dependencia", id:id }, success:function(data){ $('#result').html(data); }  
    });  
  });
 });  
 </script>

<script>  
 $(document).ready(function(){  

  $('select[name="detalles"]').change(function(){ 
  var detalles = $(this).val();  
  let id = this.id;
    $.ajax({ url:"guardar_inventario_productos.php", method:"GET", data:{valor:detalles, campo:"detalles", id:id }, success:function(data){ $('#result').html(data); }  
    });  
  });
 });  
 </script>

</body>
</html>