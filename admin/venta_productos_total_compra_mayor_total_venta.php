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
date_default_timezone_set("America/Bogota");
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$cod_factura                          = intval($_GET['cod_factura']);
$pagina                               = addslashes($_GET['pagina']);
$venta_total                          = addslashes($_GET['venta_total']);
$vlr_total_compra                     = addslashes($_GET['vlr_total_compra']);
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>

<center>

<table border='1'>
<td><font color='yellow' size= '+3'><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> EL TOTAL DE COMPRA ES MAYOR QUE EL VALOR TOTAL DE VENTA. </strong><img src=../imagenes/advertencia.gif alt='Advertencia'></font></td>
</table>

<br>

<table border='1'>
<td><font color='yellow' size= "+3">TOTAL COMPRA: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($vlr_total_compra, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">TOTAL VENTA: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($venta_total, 0, ",", "."); ?></td>
</table>

<br>

<table border='1'>
<td><a href="../admin/<?php echo $pagina;?>"><img src='../imagenes/regresar.png'></a></td>
</table>

</center>
<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
</body>
</html>