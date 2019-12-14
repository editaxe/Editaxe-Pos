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
require("funcion_verificar.php");
$requerir_funcion = new bloquear_multiple_intento;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php
$cod_plan_separe    = intval($_GET['cod_plan_separe']);
$pagina         = addslashes($_GET['pagina']);
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_info_fact = "SELECT cod_plan_separe_info_impuesto, descuento, iva, flete, cod_plan_separe, cod_clientes, vlr_cancelado, vlr_vuelto, vendedor, estado, 
fecha_dia, fecha_mes, fecha_anyo, anyo, fecha_hora FROM plan_separe_info_impuesto WHERE cod_plan_separe = '$cod_plan_separe'";
$resultado_info_fact = mysql_query($obtener_info_fact, $conectar);
$info_fact = mysql_fetch_assoc($resultado_info_fact);

$cod_plan_separe_info_impuesto = $info_fact['cod_plan_separe_info_impuesto'];
$fecha_anyo                 = $info_fact['fecha_anyo'];
$vendedor                   = $info_fact['vendedor'];
$descuento                  = $info_fact['descuento'];
$iva                        = $info_fact['iva'];
$flete                      = $info_fact['flete'];
$cod_clientes               = $info_fact['cod_clientes'];
$vlr_cancelado              = $info_fact['vlr_cancelado'];
$estado                     = $info_fact['estado'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$obtener_tipopagoventa = "SELECT tipo_pago, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora FROM plan_separe_producto WHERE cod_plan_separe = '$cod_plan_separe'";
$resultado_tipopagoventa = mysql_query($obtener_tipopagoventa, $conectar);
$dato_tipopagoventa = mysql_fetch_assoc($resultado_tipopagoventa);

$tipo_pago = $dato_tipopagoventa['tipo_pago'];

$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*(descuento_ptj/100))) As total_venta, 
Sum(((vlr_total_venta - ((descuento_ptj/100)*vlr_total_venta))/((iva/100)+(100/100)))) As subtotal_base, 
Sum(((vlr_total_venta - ((descuento_ptj/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*(descuento_ptj/100)) AS total_desc, Sum(vlr_total_compra) AS vlr_total_compra FROM plan_separe_producto WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma_datos = mysql_fetch_assoc($consulta_temporal);

$subtotal_base       = $suma_datos['subtotal_base'];
$total_desc          = $suma_datos['total_desc'];
$total_iva           = $suma_datos['total_iva'];
$total_venta_temp    = ($suma_datos['total_venta']);
$vlr_total_compra    = ($suma_datos['vlr_total_compra']);
$vlr_vuelto          = $vlr_cancelado - $total_venta_temp;

$obtener_cliente = "SELECT nombres, apellidos FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar) or die(mysql_error());
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cliente = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];
//----------------------------- CALCULO TOTAL VENTA -----------------------------//
?>
<center>
<table>
<td><font color='yellow' size= "+3">FACTURA NO:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo $cod_plan_separe; ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">CLIENTE:</font></td><td><font color='yellow' size= "+3"><?php echo $nombre_cliente; ?></td>
<tr></tr>
<td><font color='yellow' size= "+2">SUBTOTAL:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo number_format($subtotal_base, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='green' size= "+2">IVA:</font></td><td align='right'><font color='green' size= "+2"><?php echo number_format($total_iva, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">TOTAL PLAN SEPARE:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo number_format($total_venta_temp, 0, ",", "."); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">ABONADO:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo number_format($vlr_cancelado, 0, ",", "."); ?></td>
</table>
<br>

<table>
<input id="cod_plan_separe" type="hidden" name="cod_plan_separe" value="<?php echo $cod_plan_separe ?>"/>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/<?php echo $pagina?>" id="listo"><img src="../imagenes/listo.png" alt="listo"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="btnImprimir"><img src="../imagenes/imprimir_directa_pos.png" alt="imprimir"></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/imprimir_factura_plan_separe.php?numero_factura=<?php echo $cod_plan_separe?>&descuento=<?php echo $descuento?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>" id="imprimir_pdf" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</table>
</center>

<script src="js/jquery-3.1.1.min.js"></script>
<script>
    $(document).ready(function(){
        $('#btnImprimir').click(function(){
          document.getElementById("listo").focus();
           $.ajax({
               url: '../admin/imprimir_factura_plan_separe_ticket_pos.php',
               type: 'POST',
               data: $("#cod_plan_separe").serialize(),
               success: function(response){
                   if(response==1){
                       //alert('Imprimiendo....');
                   }else{
                       alert('Error');
                   }
               }
           }); 
        });
    });
</script>

</body>
</html>
<script>
window.onload = function() {
document.getElementById("imprimir_pdf").focus();
}
</script>