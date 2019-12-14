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
include ("../registro_movimientos/registro_movimientos.php");
?>
<br>
<center>
<td><strong><font color='yellow' size='+1'>LISTADO ULTIMAS 3000 FACTURAS DE VENTA </font></strong></td>
<br><br>
<table width="80%" align="center">
<tr>
<td align="center">IMP</td>
<td align="center">IMP2</td>
<td align="center">FACTURA</td>
<td align="center">TOTAL VENTA</td>
<td align="center">FECHA DE VENTA</td>
<td align="center">HORA DE VENTA</td>
<td align="center">VENDEDOR</td>
<td align="center">MAQUINA</td>
<td align="center">RECIBIDO</td>
</tr>
<?php $sql_consulta1 = "SELECT cod_factura, vendedor, fecha, fecha_anyo, fecha_hora, descuento, tipo_pago, cod_clientes, ip, nombre_peso, SUM(vlr_total_venta) AS vlr_total_venta 
FROM ventas GROUP BY cod_factura ORDER BY cod_factura DESC LIMIT 0,3000";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_assoc($resultado)) { 

$cod_factura           = $contenedor['cod_factura'];
$fecha                 = $contenedor['fecha'];
$fecha_anyo            = $contenedor['fecha_anyo'];
$vendedor              = $contenedor['vendedor'];
$fecha_hora            = $contenedor['fecha_hora'];
$vlr_total_venta       = $contenedor['vlr_total_venta'];
$descuento             = $contenedor['descuento'];
$tipo_pago             = $contenedor['tipo_pago'];
$cod_clientes          = $contenedor['cod_clientes'];
$ip                    = $contenedor['ip'];
$nombre_peso           = $contenedor['nombre_peso'];
?>
<tr>
<td align='center'><a href="../admin/buscar_facturas_listado.php?cod_factura=<?php echo $cod_factura?>&fecha_anyo=<?php echo $fecha_anyo?>&descuento=<?php echo $descuento?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>"><img src="../imagenes/imprimir_directa_pos_peq.png"></a></td> 
<td align='center'><a href="../admin/imprimir_factura.php?numero_factura=<?php echo $cod_factura?>&fecha_anyo=<?php echo $fecha_anyo?>&descuento=<?php echo $descuento?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src="../imagenes/imprimir_imgpeq.png"></a></td> 
<td align='center'><font size='+1'><?php echo $cod_factura ?></font></td> 
<td align="right"><font color='yellow' size='+1'><?php echo number_format($vlr_total_venta, 0, ",", "."); ?></font></td>
<td align="center"><font size='+1'><?php echo $fecha_anyo ?></font></td>
<td align="center"><font size='+1'><?php echo $fecha_hora ?></font></td>
<td align="center"><font size='+1'><?php echo $vendedor ?></font></td>
<td align="center"><font size='+1'><?php echo $ip ?></font></td>
<td align="right"><font size='+1'><?php echo number_format($nombre_peso, 0, ",", "."); ?></font></td>
</tr>
<?php } ?>
</table>

</center>
