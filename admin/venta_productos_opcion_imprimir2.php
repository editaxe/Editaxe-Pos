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
$cod_factura    = 1;
$pagina         = addslashes($_GET['pagina']);

?>
<center>
<table>
<input id="cod_factura" type="hidden" name="cod_factura" value="<?php echo $cod_factura ?>"/>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="btnImprimir"><img src="../imagenes/imprimir_directa_pos.png" alt="imprimir"></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</table>
</center>

<script src="js/jquery-3.1.1.min.js"></script>
<script>
    $(document).ready(function(){
        $('#btnImprimir').click(function(){
           $.ajax({
               url: '../admin/imprimir_factura_venta_ticket_pos_barcode_prueba.php',
               type: 'POST',
               data: $("#cod_factura").serialize(),
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