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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<table>
<tr>
<td align="center"><strong><a href="inventario_productos.php">INVENTARIO PRODUCTOS</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="inventario_productos_solo_iva_editable.php">INVENTARIO PRODUCTOS SOLO IVA</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="descargar_productos.php">DESCARGAR INVENTARIO</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="descargar_ventas.php">DESCARGAR VENTAS</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="productos_temporal_para_revision_menu.php">PRODUCTOS PARA REVISI&Oacute;N</a></strong></td>
</tr>
</table>
<br>