<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);


$suma_temporal = "SELECT  Sum(vlr_total_venta) As total_venta FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$matriz_temporal = mysql_fetch_assoc($consulta_temporal);

$total_venta = $matriz_temporal['total_venta'];

echo $total_venta;
?>