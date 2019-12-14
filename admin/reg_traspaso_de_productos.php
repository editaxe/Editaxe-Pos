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

if (isset($_GET['cod_productos'])) {
$cod_productos                    = intval($_GET['cod_productos']);
$pagina                           = addslashes($_GET['pagina']);

$mostrar_datos_sql = "SELECT super_tiendavenida.productos.* FROM super_tiendavenida.productos WHERE (super_tiendavenida.productos.cod_productos = '$cod_productos')";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$cod_productos                   = $datos['cod_productos'];
$cod_productos_var               = $datos['cod_productos_var'];
$nombre_productos                = $datos['nombre_productos'];
$cod_marcas                      = $datos['cod_marcas'];
$cod_proveedores                 = $datos['cod_proveedores'];
$cod_nomenclatura                = $datos['cod_nomenclatura'];
$cod_tipo                        = $datos['cod_tipo'];
$cod_lineas                      = $datos['cod_lineas'];
$cod_ccosto                      = $datos['cod_ccosto'];
$cod_paises                      = $datos['cod_paises'];
$numero_factura                  = $datos['numero_factura'];
$unidades                        = $datos['unidades'];
$cajas                           = $datos['cajas'];
$unidades_total                  = $datos['unidades_total'];
$unidades_faltantes              = $datos['unidades_faltantes'];
$unidades_vendidas               = $datos['unidades_vendidas'];
$und_orig                        = $datos['und_orig'];
$precio_compra                   = $datos['precio_compra'];
$precio_costo                    = $datos['precio_costo'];
$precio_venta                    = $datos['precio_venta'];
$precio_venta2                   = $datos['precio_venta2'];
$precio_venta3                   = $datos['precio_venta3'];
$vlr_total_compra                = $datos['vlr_total_compra'];
$vlr_total_venta                 = $datos['vlr_total_venta'];
$cod_interno                     = $datos['cod_interno'];
$tope_minimo                     = $datos['tope_minimo'];
$utilidad                        = $datos['utilidad'];
$total_utilidad                  = $datos['total_utilidad'];
$total_mercancia                 = $datos['total_mercancia'];
$total_venta                     = $datos['total_venta'];
$gasto                           = $datos['gasto'];
$descuento                       = $datos['descuento'];
$tipo_pago                       = $datos['tipo_pago'];
$ip                              = $datos['ip'];
$codificacion                    = $datos['codificacion'];
$url                             = $datos['url'];
$cod_original                    = $datos['cod_original'];
$detalles                        = $datos['detalles'];
$descripcion                     = $datos['descripcion'];
$dto1                            = $datos['dto1'];
$dto2                            = $datos['dto2'];
$iva                             = $datos['iva'];
$iva_v                           = $datos['iva_v'];
$fechas_dia                      = $datos['fechas_dia'];
$fechas_mes                      = $datos['fechas_mes'];
$fechas_anyo                     = $datos['fechas_anyo'];
$fechas_hora                     = $datos['fechas_hora'];
$fechas_vencimiento              = $datos['fechas_vencimiento'];
$porcentaje_vendedor             = $datos['porcentaje_vendedor'];
$nombre_peso                     = $datos['nombre_peso'];
$fechas_vencimiento_seg          = $datos['fechas_vencimiento_seg'];
$fechas_agotado                  = $datos['fechas_agotado'];
$fechas_agotado_seg              = $datos['fechas_agotado_seg'];
$vendedor                        = $datos['vendedor'];
$cuenta                          = $datos['cuenta'];
$nombre_metrica                  = $detalles;
}
?>
<br>
<center>
<a href="../admin/inventario_productos_no_existente_base_datos_tablas_relacionadas.php"><font color='white'><strong>REGRESAR</font></strong></font></a></td><br><br>

<?php if (isset($_GET['cod_productos'])) { ?>

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
<td align="center" title="Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1."><strong>MED</strong></td>
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

<td align="center"><input onblur="validar_codigo(this);" type="text" style="font-size:20px" name="cod_productos_var" value="<?php echo $cod_productos_var?>" id="cod_productos_var" size="15" required autofocus/></td>

<td align="center" title="Nombre del producto"><input type="text" style="font-size:20px" name="nombre_productos" value="<?php echo $nombre_productos?>" size="30" required autofocus></td>

<td align="center" title="Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende el producto completo coloque el valor 1."><input type="text" style="font-size:20px" name="unidades" value="<?php echo $unidades?>" size="1" required autofocus></td>

<td align="center" title="Unidad de medida del producto">
<select name="nombre_metrica" id="nombre_metrica" class="nombre_metrica">
<?php if (isset($nombre_metrica)) { echo "<option value='' >Seleccione</option>";
} else { echo  "<option value='' selected >Seleccione</option>"; }
$consulta2_sql = ("SELECT cod_metrica, nombre_metrica FROM metrica");
$consulta2 = mysql_query($consulta2_sql, $conectar);
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($nombre_metrica) and $nombre_metrica == $datos2['nombre_metrica']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['nombre_metrica'];
$nombre = $datos2['nombre_metrica'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?>
</select>
</td>

<td align="center" title="Cantidad de unidades de este producto"><input type="text" style="font-size:20px" name="cajas" value="<?php echo $cajas?>" size="1" required autofocus></td>

<td align="center" title="Nombre de la marca">
<select name="cod_marcas" id="cod_marcas" class="cod_marcas">
<?php if (isset($cod_marcas)) { echo "<option value='' >Seleccione</option>";
} else { echo  "<option value='' selected >Seleccione</option>"; }
$consulta2_sql = ("SELECT cod_marcas, nombre_marcas FROM marcas");
$consulta2 = mysql_query($consulta2_sql, $conectar);
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_marcas) and $cod_marcas == $datos2['cod_marcas']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['cod_marcas'];
$nombre = $datos2['cod_marcas'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?>
</select>
</td>
</tr>
</table>
<br><br>
<table align="center" width="100%">
<tr>
<td align="center" title="Tipo"><strong>TIPO</strong></td>
<td align="center" title="Estante del producto"><strong>ESTNT</strong></td>
<td align="center" title="Linea del producto"><strong>LINEA</strong></td>
<td align="center" title="Linea del producto"><strong>IVA</strong></td>
<td align="center" title="Precio costo total del producto"><strong>P.COSTO</strong></td>
<td align="center" title="Precio venta 1"><strong>P.VENTA1</strong></td>
<td align="center" title="Precio venta 2"><strong>P.VENTA2</strong></td>
<td align="center" title="Precio venta 2"><strong>P.VENTA3</strong></td>
</tr>
<tr>
<td align="center" title="Tipo">
<select name="cod_tipo" id="cod_tipo" class="cod_tipo">
<?php if (isset($cod_tipo)) { echo "<option value='' >Seleccione</option>";
} else { echo  "<option value='' selected >Seleccione</option>"; }
$consulta2_sql = ("SELECT cod_tipo, nombre_tipo FROM tipo");
$consulta2 = mysql_query($consulta2_sql, $conectar);
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_tipo) and $cod_tipo == $datos2['cod_tipo']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['cod_tipo'];
$nombre = $datos2['nombre_tipo'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?>
</select>
</td>

<td align="center" title="Estante del producto">
<select name="cod_nomenclatura" id="cod_nomenclatura" class="cod_nomenclatura">
<?php if (isset($cod_nomenclatura)) { echo "<option value='' >Seleccione</option>";
} else { echo  "<option value='' selected >Seleccione</option>"; }
$consulta2_sql = ("SELECT cod_nomenclatura, nombre_nomenclatura FROM nomenclatura");
$consulta2 = mysql_query($consulta2_sql, $conectar);
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_nomenclatura) and $cod_nomenclatura == $datos2['cod_nomenclatura']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['cod_nomenclatura'];
$nombre = $datos2['nombre_nomenclatura'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?>
</select>
</td>

<td align="center" title="Linea del producto">
<select name="cod_lineas" id="cod_lineas" class="cod_lineas">
<?php if (isset($cod_lineas)) { echo "<option value='' >Seleccione</option>";
} else { echo  "<option value='' selected >Seleccione</option>"; }
$consulta2_sql = ("SELECT cod_lineas, nombre_lineas FROM lineas");
$consulta2 = mysql_query($consulta2_sql, $conectar);
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_lineas) and $cod_lineas == $datos2['cod_lineas']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['cod_lineas'];
$nombre = $datos2['nombre_lineas'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?>
</select>
</td>

<td align="center"><input type="text" style="font-size:20px" name="iva" value="<?php echo $iva?>" size="1"></td>
<td align="center" title="Precio costo total del producto"><input type="text"style="font-size:20px" name="precio_compra" value="<?php echo $precio_compra?>" size="5" required autofocus></td>
<td align="center" title="Precio venta 1"><input type="text" style="font-size:20px" name="precio_venta" value="<?php echo $precio_venta?>" size="5" required autofocus></td>
<td align="center" title="Precio venta 2"><input type="text" style="font-size:20px" name="precio_venta2" value="<?php echo $precio_venta2?>" size="5"></td>
<td align="center" title="Precio venta 3"><input type="text" style="font-size:20px" name="precio_venta3" value="<?php echo $precio_venta3?>" size="5"></td>
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
<?php } ?>

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