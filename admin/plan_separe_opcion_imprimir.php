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
$cod_plan_separe_abono        = intval($_GET['cod_plan_separe_abono']);
$cod_plan_separe              = intval($_GET['cod_plan_separe']);
$cod_clientes                 = intval($_GET['cod_clientes']);
$cliente                      = addslashes($_GET['cliente']);
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
?>
<center>
<table>
<input id="cod_plan_separe" type="hidden" name="cod_plan_separe" value="<?php echo $cod_plan_separe ?>"/>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/plan_separe_abonos.php?cod_plan_separe_abono=<?php echo $cod_plan_separe_abono ?>&cod_plan_separe=<?php echo $cod_plan_separe ?>&cod_clientes=<?php echo $cod_clientes ?>&cliente=<?php echo $cliente ?>" id="listo"><img src="../imagenes/listo.png" alt="listo"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/plan_separe_abono_imprimir.php?cod_plan_separe_abono=<?php echo $cod_plan_separe_abono ?>&cod_plan_separe=<?php echo $cod_plan_separe ?>&cod_clientes=<?php echo $cod_clientes ?>&cliente=<?php echo $cliente ?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
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
                       alert('Imprimiendo....');
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
document.getElementById("btnImprimir").focus();
}
</script>