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

$edicion_de_formulario = $_SERVER['PHP_SELF'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {

$cod_informacion_almacen           = intval($_POST['cod_informacion_almacen']);
$titulo                            = addslashes($_POST['titulo']);
$nombre                            = addslashes($_POST['nombre']);
$res                               = addslashes($_POST['res']);
$res1                              = addslashes($_POST['res1']);
$res2                              = addslashes($_POST['res2']);
$cabecera                          = addslashes($_POST['cabecera']);
$telefono                          = addslashes($_POST['telefono']);
$direccion                         = addslashes($_POST['direccion']);
$localidad                         = addslashes($_POST['localidad']);
$nit                               = addslashes($_POST['nit']);
$info_legal                        = addslashes($_POST['info_legal']);
$icono                             = addslashes($_POST['icono']);
$eslogan                           = addslashes($_POST['eslogan']);
$ciudad                            = addslashes($_POST['ciudad']);
$regimen                           = addslashes($_POST['regimen']);
$iva_global                        = intval($_POST['iva_global']);
$nombre_impresora1                 = addslashes($_POST['nombre_impresora1']);
$propietario_nombres_apellidos     = addslashes($_POST['propietario_nombres_apellidos']);
$propietario_nit                   = addslashes($_POST['propietario_nit']);
$propietario_url_firma             = addslashes($_POST['propietario_url_firma']);

$prefijo_resolucion                = addslashes($_POST['prefijo_resolucion']);
$vigencia_res                      = addslashes($_POST['vigencia_res']);
$fecha_res                         = addslashes($_POST['fecha_res']);
$nombre_bolsa                      = addslashes($_POST['nombre_bolsa']);
$precio_bolsa                      = addslashes($_POST['precio_bolsa']);
 	 	 	 	
$sql_data = sprintf("UPDATE informacion_almacen SET titulo = '$titulo', nombre = '$nombre', res = '$res', res1 = '$res1', res2 = '$res2', 
cabecera = '$cabecera', telefono = '$telefono', direccion = '$direccion', localidad = '$localidad', nit = '$nit', info_legal = '$info_legal', 
icono = '$icono', eslogan = '$eslogan', ciudad = '$ciudad', regimen = '$regimen', iva_global = '$iva_global', nombre_impresora1 = '$nombre_impresora1', 
propietario_nombres_apellidos = '$propietario_nombres_apellidos', propietario_nit = '$propietario_nit', propietario_url_firma = '$propietario_url_firma', 
prefijo_resolucion = '$prefijo_resolucion', vigencia_res = '$vigencia_res', fecha_res = '$fecha_res', nombre_bolsa = '$nombre_bolsa', precio_bolsa = '$precio_bolsa' 
WHERE cod_informacion_almacen = '$cod_informacion_almacen'");
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/informacion_almacen.php">';
}
$cod_informacion_almacen = intval($_GET['cod_informacion_almacen']);

$sql_modificar_consulta = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '$cod_informacion_almacen'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>Almacen</title>
</head>
<br>
<body>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<!--
<tr valign="baseline">
<td nowrap align="left">TITULO:</td>
<td><input type="text" name="titulo" value="<?php echo $datos['titulo']; ?>" size="100"></td>
</tr>
-->
<input type="hidden" name="titulo" value="<?php echo $datos['titulo']; ?>" size="100">
<tr valign="baseline">
<td nowrap align="left">NOMRE:</td>
<td><input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CABECERA FACTURA:</td>
<td><input type="text" name="cabecera" value="<?php echo $datos['cabecera']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">ESLOGAN:</td>
<td><input type="text" name="eslogan" value="<?php echo $datos['eslogan']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">REGIMEN:</td>
<td><input type="text" name="regimen" value="<?php echo $datos['regimen']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">RES:</td>
<td><input type="text" name="res" value="<?php echo $datos['res']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">DE:</td>
<td><input type="text" name="res1" value="<?php echo $datos['res1']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">A:</td>
<td><input type="text" name="res2" value="<?php echo $datos['res2']; ?>" size="100"></td>
</tr>


<tr valign="baseline">
<td nowrap align="left">PREFIJO RESOLUCION:</td>
<td><input type="text" name="prefijo_resolucion" value="<?php echo $datos['prefijo_resolucion']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">VIGENCIA RESOLUCION:</td>
<td><input type="text" name="vigencia_res" value="<?php echo $datos['vigencia_res']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">FECHA RESOLUCION:</td>
<td><input type="text" name="fecha_res" value="<?php echo $datos['fecha_res']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NOMBRE IMPUESTO BOLSA:</td>
<td><input type="text" name="nombre_bolsa" value="<?php echo $datos['nombre_bolsa']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">PRECIO IMPUESTO BOLSA:</td>
<td><input type="text" name="precio_bolsa" value="<?php echo $datos['precio_bolsa']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">IVA GLOBAL:</td>
<td><input type="text" name="iva_global" value="<?php echo $datos['iva_global']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">TELEFONO:</td>
<td><input type="text" name="telefono" value="<?php echo $datos['telefono']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">DIRECCION:</td>
<td><input type="text" name="direccion" value="<?php echo $datos['direccion']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">LOCALIDAD:</td>
<td><input type="text" name="localidad" value="<?php echo $datos['localidad']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CIUDAD:</td>
<td><input type="text" name="ciudad" value="<?php echo $datos['ciudad']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NIT EMPRESA:</td>
<td><input type="text" name="nit" value="<?php echo $datos['nit']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">PROPIETARIO:</td>
<td><input type="text" name="propietario_nombres_apellidos" value="<?php echo $datos['propietario_nombres_apellidos']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NIT PROPIETARIO:</td>
<td><input type="text" name="propietario_nit" value="<?php echo $datos['propietario_nit']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">FIRMA PROPIETARIO:</td>
<td><input type="text" name="propietario_url_firma" value="<?php echo $datos['propietario_url_firma']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">IMPRESORA:</td>
<td><input type="text" name="nombre_impresora1" value="<?php echo $datos['nombre_impresora1']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">INFORMACION LEGAL:</td>
<td><textarea name="info_legal" cols="72" rows="6"><?php echo $datos['info_legal']; ?></textarea></td>
</tr>
<!--<td nowrap align="left">Logotipo:</td>
<td>
<?php
/*$sql_consulta = "SELECT * FROM icono_logo";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<input type="radio" name="logotipo" value="<?php echo $contenedor['nombre_icono_logo'] ?>"checked>
<img src=<?php echo $contenedor['url_icono_logo']?> width="30" height="30">
<?php 
}*/
?></td>-->
<tr>
<td nowrap align="left">ICONO:</td>
<td>
<?php
$sql_consulta = "SELECT * FROM icono_logo";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<input type="radio" name="icono" value="<?php echo $contenedor['nombre_icono_logo'] ?>"checked>
<img src=<?php echo $contenedor['url_icono_logo']?> width="30" height="30">
<?php 
}
?></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_informacion_almacen" value="<?php echo $datos['cod_informacion_almacen']; ?>">
</form>
</center>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
