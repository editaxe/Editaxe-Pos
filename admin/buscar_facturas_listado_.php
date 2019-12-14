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
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$cod_factura                = intval($_GET['cod_factura']);

$datos_factura = "SELECT vendedor, fecha_anyo FROM ventas WHERE cod_factura = '$cod_factura' GROUP BY cod_factura";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$vendedor                   = $matriz_consulta['vendedor'];
$fecha_anyo                 = $matriz_consulta['fecha_anyo'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$datos_cliente = "SELECT clientes.cod_clientes, clientes.nombres, clientes.apellidos 
FROM ventas, clientes WHERE ventas.cod_clientes = clientes.cod_clientes AND cod_factura = '$cod_factura'";
$consulta_cliente = mysql_query($datos_cliente, $conectar) or die(mysql_error());
$cliente = mysql_fetch_assoc($consulta_cliente);

$cod_clientes               = $cliente['cod_clientes'];
$nombres                    = $cliente['nombres'];
$apellidos                  = $cliente['apellidos'];
//---------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------------------------------------------------------------------------//
$datos_total_factura = "SELECT  Sum(vlr_total_venta) as vlr_totl FROM ventas WHERE cod_factura like '$cod_factura'";
$consulta_total = mysql_query($datos_total_factura, $conectar) or die(mysql_error());
$matriz_total_consulta = mysql_fetch_assoc($consulta_total);

$calculo_subtotal           = $matriz_total_consulta['vlr_totl'] - $_GET['descuento_factura']; 
$calculo_total              = $calculo_subtotal;
$pagina                     = $_SERVER['PHP_SELF'];
//$pagina ="buscar_facturas_fecha";
?>
<br>
<center>
<table id="numero_factura" width="90%">

<td nowrap align="right"><strong><font size="4">FACTURA:</td><td><font size="4"><?php echo $cod_factura; ?></font></td>

<td nowrap align="right"><strong><font size="4">FECHA:</font></td><td><font size="4"><?php echo $fecha_anyo; ?></font></td>

<td nowrap align="right"><strong><font size="4">CLIENTE:</font></td><td><font size="4"><?php echo $nombres.' '.$apellidos; ?></font></td>

<td nowrap align="right"><strong><font size="4">VENDEDOR:</font></td><td><font size="4"><?php echo $vendedor; ?></font></td>
 </tr>
</table>
</center>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ALMACEN</title>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)
myajax.Link('guardar_devoluciones_facturas.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<!--<form method="post" name="formulario" action="../admin/buscar_imprimir_factura.php" accept-charset="UTF-8" target="_blank">-->
<form name="form1" id="form1" action="#" method="GET" style="margin:0;">  
<center>
<table id="table" width="90%">
<input type="hidden" name="numero_factura" value="<?php echo $_GET['cod_factura']; ?>" size="8">
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>UNDS</strong></td>
<td align="center"><strong>T.V</strong></td>
<td align="center"><strong>V. UNITARIO</strong></td>
<td align="center"><strong>V. TOTAL</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center">><strong>ELIM</strong></td>
</tr>
<?php
$sql = "SELECT * FROM ventas WHERE cod_factura = '$cod_factura'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());

while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas                 = $datos['cod_ventas'];
$cod_productos              = $datos['cod_productos'];
$nombre_productos           = $datos['nombre_productos'];
$unidades_vendidas          = $datos['unidades_vendidas'];
$precio_compra              = $datos['precio_compra'];
$precio_venta               = $datos['precio_venta'];
$vlr_total_venta            = $datos['vlr_total_venta'];
$cod_facturan               = $datos['cod_factura'];
$comentario                 = $datos['comentario'];
$detalles                   = $datos['detalles'];
$fecha_anyo                 = $datos['fecha_anyo'];
$fecha_hora                 = $datos['fecha_hora'];
?>
<tr>
<td><font><?php echo $datos['cod_productos']; ?></td></font>
<td><font><?php echo $datos['nombre_productos']; ?></td></font>
<td align="right"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_ventas;?>)" class="cajpequena" id="b<?php echo $cod_ventas;?>" value="<?php echo $unidades_vendidas;?>" size="1"></td>
<?php if ($detalles == 'PV1') { ?> <td id="tipo_precio_venta<?php echo $cod_ventas;?>" align='center'><a href="../admin/actualizar_precio_venta_facturado.php?tipo_precio_venta=PV2&cod_ventas=<?php echo $cod_ventas?>&pagina=<?php echo $pagina?>"><img src="../imagenes/PV1.png" alt="Precio venta"></a></td> <?php } 
elseif ($detalles == 'PV2') { ?> <td id="tipo_precio_venta<?php echo $cod_ventas;?>" align='center'><a href="../admin/actualizar_precio_venta_facturado.php?tipo_precio_venta=PV3&cod_ventas=<?php echo $cod_ventas?>&pagina=<?php echo $pagina?>"><img src="../imagenes/PV2.png" alt="Precio descuento"></a></td> <?php }
elseif ($detalles == 'PV3') { ?> <td id="tipo_precio_venta<?php echo $cod_ventas;?>" align='center'><a href="../admin/actualizar_precio_venta_facturado.php?tipo_precio_venta=PV1&cod_ventas=<?php echo $cod_ventas?>&pagina=<?php echo $pagina?>"><img src="../imagenes/PV3.png" alt="Precio por mayor"></a></td> <?php } 
elseif ($detalles == 'PVAR') { ?> <td id="PVAR<?php echo $cod_ventas;?>" align='center'><img src="../imagenes/PVAR.png" alt="Precio variable"></a></td> <?php } ?>

<?php if ($detalles == 'PVAR') { ?> <td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $precio_venta;?>" size="3"></td> <?php } 
else { ?> <td id="precio_venta_no_modif<?php echo $cod_ventas;?>" align='right'><?php echo number_format($precio_venta, 0, ",", ".");?></td> <?php } ?>

<td align="right"><font><?php echo number_format($vlr_total_venta, 0, ",", "."); ?></td></font>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'comentario', <?php echo $cod_ventas;?>)" class="cajbarras" id="<?php echo $cod_ventas;?>" value="<?php echo $comentario;?>" size="3"></td>
<td align="center"><font><?php echo $fecha_anyo; ?></td></font>
<td align="center"><font><?php echo $fecha_hora; ?></td></font>
<td><a href="../modificar_eliminar/eliminar_productos_factura.php?cod_ventas=<?php echo $cod_ventas?>&cod_productos=<?php echo $cod_productos?>&cod_factura=<?php echo $cod_factura;?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
</tr>	 
<?php 
}
?>
</form>

<table>
<br>
<input id="cod_factura" type="hidden" name="cod_factura" value="<?php echo $cod_factura ?>"/>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="btnImprimir"><img src=../imagenes/imprimir_directa_pos.png alt="imprimir"></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_factura.php?numero_factura=<?php echo $cod_factura?>&fecha_anyo=<?php echo $fecha_anyo?>&descuento=0&tipo_pago=1&cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_factura_grande.php?numero_factura=<?php echo $cod_factura?>&fecha_anyo=<?php echo $fecha_anyo?>&descuento=0&tipo_pago=1&cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src=../imagenes/imprimir_.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</table>
</body>
</html>

<script src="js/jquery-3.1.1.min.js"></script>
<script>
    $(document).ready(function(){
        $('#btnImprimir').click(function(){
           $.ajax({
               url: '../admin/imprimir_factura_venta_ticket_pos.php',
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