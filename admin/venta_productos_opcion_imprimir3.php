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

$cod_factura                = intval($_GET['cod_factura']);
$pagina                     = addslashes($_GET['pagina']);
?>
<!DOCTYPE html>
<html lang="es">  
  <head>    
    <title>Título de la WEB</title>    
    <meta charset="UTF-8">
    <meta name="title" content="Título de la WEB">
    <meta name="description" content="Descripción de la WEB">    
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/jquery.jqprint-0.3.js" type="text/javascript"></script>
<link rel="stylesheet" href="../estilo_css/test.css">  
  </head>  
  <body>

<table>
<input id="cod_factura" type="hidden" name="cod_factura" value="<?php echo $cod_factura ?>"/>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/<?php echo $pagina?>" id="listo"><img src="../imagenes/listo.png" alt="listo"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<!--<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="btnImprimir"><img src="../imagenes/imprimir_directa_pos.png" alt="imprimir"></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>-->
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="PrintButton"><img src="../imagenes/imprimir_directa_pos.png" alt="imprimir"></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/imprimir_factura.php?numero_factura=<?php echo $cod_factura?>&descuento=<?php echo $descuento?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src="../imagenes/imprimir_1.png" id="btnImprimir" alt="imprimir"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../admin/imprimir_factura_grande.php?numero_factura=<?php echo $cod_factura?>&descuento=<?php echo $descuento?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src=../imagenes/imprimir_.png alt="imprimir"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</table>



<script language="javascript" type="text/javascript">
$(function() {
  $("#PrintButton").click( function() {
      $('#divToPrint').jqprint();
      return false;
  });
});
</script>


<div id="divToPrint" class="test">
    <header>
      <h1>Título de la WEB</h1>      
    </header>    
    <nav>
      <a href="http://dominio.com/seccion2.html">IR SECCIÓN 2</a>
      <a href="http://dominio.com/seccion2.html">IR SECCIÓN 3</a>
    </nav>
    <section>      
      <article>
        <h2>CONTENIDO PRINCIPAL</h2>
        <p>Este es el contenido principal de mi web</p>
        <div>
          <p>Aquí tenéis una imagen.</p>
          <img src="http://dominio.com/imagen.jpg" alt="paisaje">          
        </div>
      </article>      
    </section>
    <aside>
      <h3>Banner de publicidad</h3>
      <a href="http://dominio-externo.com">
        <img src="http://dominio.com/banner-publicidad.png" alt="banner de publicidad">
      </a>
      <h3>Testimonios</h3>
      <p>Me gusta mucho esta página.</p>
    </aside>
    <footer>
      <h4>Avisos legales</h4>
      <a href="http://dominio.com/aviso-legal">Política de cookies</a>
      <h4>Redes sociales</h4>
      <a href="http://facebook.com/mi-pagina-de-facebook">Mi Facebook</a>
    </footer>
</div>


  </body>  
</html>