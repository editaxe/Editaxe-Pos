<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");

$pagina = $_SERVER['PHP_SELF'];

$tipo_barra = addslashes($_GET['tipo_barra']);
//---------------------------------------------PARA CALCULAR PLUS BARRAS LARGA---------------------------------------------------------//
//---------------------------------------------PARA CALCULAR PLUS BARRAS LARGA---------------------------------------------------------//
$max_cod_barra_larga = "SELECT CONVERT(cod_productos_var, SIGNED INTEGER) AS cod_productos_var FROM productos WHERE CONVERT(cod_productos_var, SIGNED INTEGER) < 10000000 ORDER BY cod_productos_var DESC LIMIT 0 , 1";
//$max_cod_barra_larga = "SELECT cod_productos_var FROM productos WHERE cod_productos_var REGEXP '^[0-9]+$' ORDER BY cod_productos_var DESC LIMIT 0 , 1";
$consulta_max_cod_barra_larga = mysql_query($max_cod_barra_larga, $conectar) or die(mysql_error());
$datos_cod_barra_larga = mysql_fetch_assoc($consulta_max_cod_barra_larga);

$cod_barra_larga = $datos_cod_barra_larga['cod_productos_var'];
//--------------------------------------------------------------------//
//--------------------------CON ESTE CONDICIONAL CREAMOS LA BARRA LARGA------------------------------------//
if ($tipo_barra == '' || $tipo_barra == 'larga') {
//--------------------------------AQUI INCREMENTAMOS EN UNO LA BARRA LARGA CUANDO ES UN NUMERO------------------------------------//
$plus = $cod_barra_larga+1;
}
//--------AQUI CREAMOS LA BARRA CORTA DE 4 DIGITOS APARTIR LA CONSULTA QUE SOLO MUESTRA LOS VALORES NUMERICOS DEL CAMPO cod_productos_var------//
else {
//---------------------------------------------PARA CALCULAR PLUS BARRAS CORTA---------------------------------------------------------//
//---------------------- SE CONSULTAN SOLO LOS VALORES NUMERICOS DEL CAMPO cod_productos_var CON REGEXP '^[0-9]+$' ---------------------------//
$max_cod_barra_corta9999 = "SELECT CONVERT(cod_productos_var, SIGNED INTEGER) AS cod_productos_var FROM productos WHERE CONVERT(cod_productos_var, SIGNED INTEGER) <= 9999
ORDER BY cod_productos_var DESC LIMIT 0 , 1";
//$max_cod_barra_corta9999 = "SELECT cod_productos_var FROM productos WHERE CONVERT(cod_productos_var, SIGNED INTEGER) IS NOT NULL ORDER BY cod_productos_var ASC LIMIT 0 , 1";
$consulta_max_cod_barra_corta9999 = mysql_query($max_cod_barra_corta9999, $conectar) or die(mysql_error());
$datos_cod_barra_corta9999 = mysql_fetch_assoc($consulta_max_cod_barra_corta9999);

$cod_barra_corta9999 = $datos_cod_barra_corta9999['cod_productos_var'];
//------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------//
$plus = $cod_barra_corta9999+1;
}	
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>
<br>
<center>
<a href="../admin/cargar_factura_temporal.php"><font color='white'><strong>REGRESAR</font></strong></font></a></td><br><br>
<td><strong><font color='yellow'>REGISTRAR PRODUCTO NUEVO: </font></strong></td>
<br><br>
<form method="post" name="formulario" action="agregar_productos_nuevos.php">
<table align="center">
<span id="envio_mensaje"></span><br><br>
<tr>
<table align="center" width="100%">
<td align="center"></td>
<?php
//-------------HABILITAR ESTA OPCION CUANDO LA BARRAS ES LARGA----------------//
if ($tipo_barra == '' || $tipo_barra == 'larga') { ?>
<td align="center"><strong>PLUS 1L</strong></td>
<?php
//-------------HABILITAR ESTA OPCION CUANDO LA BARRAS ES CORTA----------------//
} if ($tipo_barra == 'corta') { ?>
<td align="center"><strong>PLUS 2C</strong></td>
<?php } ?>
<td align="center" title="Codigo asignado al producto nuevo"><strong>C&Oacute;D BARRAS</strong></td>
<td align="center" title="Nombre del producto"><strong>NOMBRE</strong></td>
<td align="center" title="Unidad de medida del producto"><strong>DEPENDENCIA</strong></td>
<td align="center" title="Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1."><strong>U.PQ</strong></td>
<td align="center" title="Unidad de medida del producto"><strong>MET</strong></td>
<td align="center" title="Si el productos se vende menudiado coloque aqui cuantas bolsas o paquetes compro, si no es menudiado sino q se vende completo coloque aqui cuantas unidades de ese producto tiene"><strong>UND</strong></td>
<td align="center" title="Nombre de la marca"><strong>MARCA</strong></td>
</tr>
<tr>
<?php
//-------------HABILITAR ESTA OPCION CUANDO LA BARRAS ES LARGA----------------//
if ($tipo_barra == '' || $tipo_barra == 'larga') { ?>
<td align="center"><a href="<?php echo $pagina ?>?tipo_barra=<?php echo 'corta'?>"><img src=../imagenes/plus2.png alt="P2"></a></td>
<td align="center"><font size='+1'><a href="<?php echo $pagina ?>?plus=<?php echo $plus?>&tipo_barra=<?php echo 'larga'?>"><?php echo $plus ?></a></font></td>
<?php
//-------------HABILITAR ESTA OPCION CUANDO LA BARRAS ES CORTA----------------//
} if ($tipo_barra == 'corta') { ?>
<td align="center"><a href="<?php echo $pagina ?>?tipo_barra=<?php echo 'larga'?>"><img src=../imagenes/plus1.png alt="P1"></a></td>
<td align="center"><font size='+2'><a href="<?php echo $pagina ?>?plus=<?php echo $plus?>&tipo_barra=<?php echo 'corta'?>"><?php echo $plus ?></a></font></td>
<?php } ?>

<td align="center"><input onblur="validar_codigo(this);" type="text" style="font-size:20px" name="cod_productos_var" value="<?php echo $_GET['plus']?>" id="cod_productos_var" size="15" required autofocus/></td>

<td align="center" title="Nombre del producto"><input type="text" style="font-size:20px" name="nombre_productos" value="" size="30" required autofocus></td>


<td align="center" title="Dependencia"><select name="cod_dependencia">
<?php $sql_consulta="SELECT * FROM dependencia ORDER BY cod_dependencia ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['cod_dependencia'] ?>"><?php echo $contenedor['nombre_dependencia'] ?></option>
<?php }?>
</select></td>


<td align="center" title="Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende el producto completo coloque el valor 1."><input type="text" style="font-size:20px" name="unidades" value="" size="1" required autofocus></td>

<td align="center" title="Unidad de medida del producto"><select name="nombre_metrica">
<?php $sql_consulta="SELECT cod_metrica, nombre_metrica FROM metrica ORDER BY metrica.cod_metrica ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['nombre_metrica'] ?>"><?php echo $contenedor['nombre_metrica'] ?></option>
<?php }?>
</select></td>

<td align="center" title="Cantidad de unidades de este producto"><input type="text" style="font-size:20px" name="cajas" value="" size="1" required autofocus></td>

<td align="center" title="Nombre de la marca"><select name="cod_marcas">
<?php $sql_consulta="SELECT cod_marcas, nombre_marcas FROM marcas ORDER BY marcas.cod_marcas ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['cod_marcas'] ?>"><?php echo $contenedor['nombre_marcas'] ?></option>
<?php }?>
</select></td>
</tr>
</table>
<br><br>
<table align="center" width="100%">
<tr>
<td align="center" title="Tipo"><strong>TIPO</strong></td>
<td align="center" title="Estante del producto"><strong>ESTNT</strong></td>
<td align="center" title="Linea del producto"><strong>LINEA</strong></td>
<td align="center" title="Linea del producto"><strong>IVA</strong></td>
<td align="center" title="Precio costo total del producto"><strong>P.COMPRA</strong></td>
<td align="center" title="Precio venta 1"><strong>P.VENTA1</strong></td>
<td align="center" title="Precio venta 2"><strong>P.VENTA2</strong></td>
<td align="center" title="Precio venta 2"><strong>P.VENTA3</strong></td>
<td align="center" title="Precio venta 2"><strong>P.VENTA4</strong></td>
</tr>
<tr>
<td align="center" title="Tipo"><select name="cod_tipo">
<?php $sql_consulta="SELECT * FROM tipo ORDER BY cod_tipo ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['cod_tipo'] ?>"><?php echo $contenedor['nombre_tipo'] ?></option>
<?php }?>
</select></td>

<td align="center" title="Estante del producto"><select name="cod_nomenclatura">
<?php $sql_consulta="SELECT * FROM nomenclatura ORDER BY cod_nomenclatura ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['cod_nomenclatura'] ?>"><?php echo $contenedor['nombre_nomenclatura'] ?></option>
<?php }?>
</select></td>

<td align="center" title="Linea del producto"><select name="cod_lineas">
<?php $sql_consulta="SELECT * FROM lineas ORDER BY cod_lineas ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['cod_lineas'] ?>"><?php echo $contenedor['nombre_lineas'] ?></option>
<?php }?>
</select></td>

<td align="center"><input type="text" style="font-size:20px" name="iva" value="" size="1"></td>
<td align="center" title="Precio costo total del producto"><input type="text"style="font-size:20px" name="precio_compra" value="" size="5" required autofocus></td>
<td align="center" title="Precio venta 1"><input type="text" style="font-size:20px" name="precio_venta" value="" size="5" required autofocus></td>
<td align="center" title="Precio venta 2"><input type="text" style="font-size:20px" name="precio_venta2" value="" size="5"></td>
<td align="center" title="Precio venta 3"><input type="text" style="font-size:20px" name="precio_venta3" value="" size="5"></td>
<td align="center" title="Precio venta 3"><input type="text" style="font-size:20px" name="precio_venta4" value="" size="5"></td>
</tr>
</table>
<br><br>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Agregar"></td>
</tr>
<input type="hidden" name="insertar_datos" value="formulario">
<input type="hidden" style="font-size:20px" name="porcentaje_vendedor" value="No" size="2">
<input type="hidden" style="font-size:20px" name="fechas_vencimiento" value="0" size="10">
<input type="hidden" name="cod_paises" value="1" size="30" required autofocus>
<input type="hidden" name="cod_original" value="" size="4">
<input type="hidden" name="tope_minimo" value="1" size="2">
<input type="hidden" name="flete" value="0" size="1">
<input type="hidden" name="ptj_ganancia" value="0" size="1">
<input type="hidden" name="pagina" value="<?php echo $pagina?>">
</form>
</center>

<script src="prototype.js" type="text/javascript"></script>
<script type="text/javascript">
function validar_codigo(usuario)        {
var url = 'validar_codigo_barras.php';
var parametros='cod_productos_var='+cod_productos_var.value;
var ajax = new Ajax.Updater('envio_mensaje',url,{method: 'get', parameters: parametros});
}
</script>
</body>
</html>