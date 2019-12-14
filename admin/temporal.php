<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_base_caja = intval($_SESSION['cod_base_caja']);

include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
$consql = "SELECT * FROM temporal WHERE (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja')";
$getanz = mysql_query($consql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($getanz);

require_once("busqueda_inmediata_act.php");
if ($total_datos <> 0) {
require_once("informacion_factura_venta.php");
}
?>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'cajhabiltada';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'cajdeshabiltada';
if (last != valor)
myajax.Link('guardar_cargar_factura_temporal1.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
$unidad = 'unidad';
$caja = 'caja';
$pagina = $_SERVER['PHP_SELF'];

$sql = "SELECT * FROM temporal WHERE (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja') ORDER BY cod_temporal DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_registr = mysql_num_rows($consulta);

if ($total_datos <> 0) {
?>
<table width="100%">
<tr>
<td align="center" title="Eliminar registro del carrito de venta"><strong><font size='3'>ELIM</font></strong></td>
<td align="center" title="Codigo del producto"><strong><font size='3'>C&Oacute;DIGO</font></strong></td>
<td align="center" title="Nombre del producto"><strong><font size='3'>PRODUCTO</font></strong></td>
<td align="center" title="Cantidad a vender"><strong><font size='3'>UND</font></strong></td>
<td align="center"><strong><font size='3'></font></strong></td>

<td align="center" title="Tipo de venta"><strong><font size='3'>T.V</font></strong></td>
<td align="center" title="Tipo de venta"><strong><font size='3'>T.P</font></strong></td>
<td align="center" title="Precio venta del producto en metrica, vender segun su respectica metrica"><strong><font size='3'>P.VENTA</font></strong></td>
<!--<td align="center" title="Porcentaje de ganancia en caso de vender menudiado"><strong><font size='3'>+%</font></strong></td>-->
<td align="center" title="Valor total de la venta"><strong><font size='3'>V.TOTAL</font></strong></td>
<td align="center" title="Aceptar los valores insertados"><strong><font size='3'>OK</font></strong></td>
</tr>
<?php
$tab                            = 'temporal';
$campo                          = 'cod_temporal';
$tipo                           = 'eliminar';
$incre                          = 0;

while ($datos = mysql_fetch_assoc($consulta)) {
$cod_temporal                   = $datos['cod_temporal'];
$cod_productos                  = $datos['cod_productos'];
$nombre_productos               = $datos['nombre_productos'];
$unidades_cajas                 = $datos['unidades_cajas'];
$unidades_vendidas              = $datos['unidades_vendidas'];
$precio_venta                   = $datos['precio_venta'];
$precio_costo                   = $datos['precio_costo'];
$vlr_total_venta                = $datos['vlr_total_venta'];
$descripcion                    = $datos['fecha_mes'];
$detalles                       = $datos['detalles'];
$iva_v                          = $datos['iva_v'];
$precio_compra_con_descuento    = $datos['precio_compra_con_descuento'];
$diferencia                     = $precio_venta - $precio_compra_con_descuento;
//$ptj_difrencia = ($diferencia / $precio_compra_con_descuento) * 100;
$tipo_venta                     = $datos['tipo_venta'];
$und_min_precio_venta_desc      = $datos['und_min_precio_venta_desc'];
$incre++;
?>
<tr id="tr<?php echo $cod_temporal;?>">
<!--<td class="service_list" id="cod_temporal<?php echo $cod_temporal ?>" align='center' data="<?php echo $cod_temporal ?>"><a class="eliminar" id="cod_temporal<?php echo $cod_temporal ?>"><img src="../imagenes/eliminar.png" class="img-polaroid" alt=""></a></td>-->
<td ><a href="../modificar_eliminar/eliminar_temporal_productos.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_temporal=<?php echo $datos['cod_temporal']?>&pagina=<?php echo $pagina?>" tabindex=3><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td id="cod_productos_<?php echo $cod_temporal;?>"><?php echo $cod_productos;?></td>
<td id="nombre_productos_<?php echo $cod_temporal;?>"><?php echo $nombre_productos;?></td>
<td id="unidades_vendidas_<?php echo $cod_temporal;?>" align='center'><input onFocus="Focus(this.id, this.value)" onChange="calc_total_venta();" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_temporal;?>)" class="cajund" id="unidades_vendidas<?php echo $incre;?>" value="<?php echo $unidades_vendidas;?>" size="4"></td>
<td id="mensaje_alerta<?php echo $incre;?>"></td>

<?php
if ($unidades_cajas == '1') { ?> <td id="unidades_cajas_<?php echo $cod_temporal;?>" align='center'><a href="../admin/actualizar_unidades_cajas.php?tipo_unidades_cajas=1&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><img src="../imagenes/vender_por_caja.png" alt="Precio por mayor"></a></td> <?php } 
elseif ($unidades_cajas == '0') { ?> <td id="unidades_cajas_<?php echo $cod_temporal;?>" align='center'><a href="../admin/actualizar_unidades_cajas.php?tipo_unidades_cajas=1&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><img src="../imagenes/vender_por_caja.png" alt="Precio por mayor"></a></td> <?php } 
else { ?> <td id="unidades_cajas_<?php echo $cod_temporal;?>" align='center'><a href="../admin/actualizar_unidades_cajas.php?tipo_unidades_cajas=caja&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><img src="../imagenes/vender_por_caja.png" alt="Precio por mayor"></a></td> 
<?php } ?>

<?php 
if ($detalles == 'PV1') { ?> <td id="tipo_precio_venta_<?php echo $cod_temporal;?>" align='center'><a href="../admin/actualizar_precio_venta.php?tipo_precio_venta=PV2&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><img src="../imagenes/PV1.png" alt="Precio venta"></a></td> <?php } 
elseif ($detalles == 'PV2') { ?> <td id="tipo_precio_venta_<?php echo $cod_temporal;?>" align='center'><a href="../admin/actualizar_precio_venta.php?tipo_precio_venta=PV3&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><img src="../imagenes/PV2.png" alt="Precio descuento"></a></td> <?php }
elseif ($detalles == 'PV3') { ?> <td id="tipo_precio_venta_<?php echo $cod_temporal;?>" align='center'><a href="../admin/actualizar_precio_venta.php?tipo_precio_venta=PV4&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><img src="../imagenes/PV3.png" alt="Precio por mayor"></a></td> <?php }
elseif ($detalles == 'PV4') { ?> <td id="tipo_precio_venta_<?php echo $cod_temporal;?>" align='center'><a href="../admin/actualizar_precio_venta.php?tipo_precio_venta=PV1&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><img src="../imagenes/PV4.png" alt="Precio por mayor"></a></td> <?php } 
?>

<?php if ($detalles == 'PVAR') { ?>
<td id="PVAR_<?php echo $cod_temporal;?>" align='center'><img src="../imagenes/PVAR.png" alt="Precio variable"></a></td> 
<td id="precio_venta_<?php echo $cod_temporal;?>" align='center'><input onFocus="Focus(this.id, this.value)" onChange="calc_total_venta();" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_temporal;?>)" class="cajvtotal" id="precio_venta<?php echo $incre;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<?php } 
else { ?> <td id="precio_venta_no_modif_<?php echo $cod_temporal;?>" style="text-align:right; font-size:30;" align='right'><?php echo number_format($precio_venta, 0, ",", ".");?></td> <?php } ?>

<input type="hidden" id="precio_venta<?php echo $incre;?>" value="<?php echo $precio_venta;?>">

<td style="text-align:right; font-size:30;" align='right' id="vlr_total_venta<?php echo $incre;?>"><?php echo number_format($vlr_total_venta, 0, ",", ".");?></td>
<td id="btn_listo<?php echo $cod_temporal;?>"><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td> 
</tr id="tr<?php echo $cod_temporal;?>">
<?php } } else { } ?>
</table>
</form>

<script type="text/javascript" src="../admin/js/jquery.min.js"></script>
<script>
function calc_total_venta(){

var i=0;
var incre = <?php echo $total_registr;?>;
var unidades_vendidas_text = "";
var precio_venta_text = "";
var vlr_total_venta_text = "";
var smtr_total_venta = 0;
var total_venta = 0;
var Max_Length = 4;
var length = 0;

for (i=1; i<=incre; i++){

unidades_vendidas_text = "unidades_vendidas"+i;
precio_venta_text = "precio_venta"+i;
vlr_total_venta_text = "vlr_total_venta"+i;
mensaje_alerta_text = "mensaje_alerta"+i;

mensaje_alerta = document.getElementById(mensaje_alerta_text).value;
unidades_vendidas = document.getElementById(unidades_vendidas_text).value;
precio_venta = document.getElementById(precio_venta_text).value;
vlr_total_venta = (unidades_vendidas * precio_venta);
smtr_total_venta = smtr_total_venta + vlr_total_venta;

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////// -->
length = document.getElementById(unidades_vendidas_text).value.length;
if (length > Max_Length) {
var objeto_mostrar_mensaje = document.getElementById("mensaje_alerta"+i);
objeto_mostrar_mensaje.parentNode.innerHTML = objeto_mostrar_mensaje.parentNode.innerHTML + "<p style='color:yellow'>Verificar</p>";
//  address1.parentNode.innerHTML = address1.parentNode.innerHTML + "<p style='color:red'>the max length of "+Max_Length + " characters is reached, you typed in  " + length + "characters</p>";
console.log(objeto_mostrar_mensaje);
} else {  }
<!-- ////////////////////////////////////////////////////////////////////////////////////////////////// -->

document.getElementById(vlr_total_venta_text).innerHTML=vlr_total_venta.toLocaleString("es-ES");
}
total_venta = smtr_total_venta;
document.getElementById("total_venta").innerHTML=total_venta.toLocaleString("es-ES");
}
</script>

<script type="text/javascript">
$(document).ready(function() {

    $('.eliminar').click(function(){

        var parent = $(this).parent().attr('id');
        var cod_temporal = $(this).parent().attr('data');
        var dataString = 'llave='+cod_temporal+'&'+'tab='+'<?php echo $tab ?>'+'&'+'campo='+'<?php echo $campo ?>'+'&'+'tipo='+'<?php echo $tipo ?>';

        $.ajax({
            type: "POST",
            url: "../modificar_eliminar/eliminar_temporal_productos_ajax.php",
            data: dataString,
            success: function() {           
                $('#eliminar-ok').empty();
                $('#eliminar-ok').append('<div align="center" class="correcto">Se ha eliminado correctamente el codigo = '+cod_temporal+'.</div>').fadeIn("slow");
                $('#'+parent).fadeOut("slow");
                $('#cod_productos_'+cod_temporal).fadeOut("slow");
                $('#nombre_productos_'+cod_temporal).fadeOut("slow");
                $('#unidades_vendidas_'+cod_temporal).fadeOut("slow");
                $('#unidades_cajas_'+cod_temporal).fadeOut("slow");
                $('#tipo_precio_venta_'+cod_temporal).fadeOut("slow");
                $('#PVAR_'+cod_temporal).fadeOut("slow");
                $('#precio_vent_a'+cod_temporal).fadeOut("slow");
                $('#precio_venta_no_modif_'+cod_temporal).fadeOut("slow");
                $('#vlr_total_venta_'+cod_temporal).fadeOut("slow");
                $('#btn_listo'+cod_temporal).fadeOut("slow");
                $('#tr'+cod_temporal).fadeOut("slow");
            }
        });

    });

});
</script>

</body>
</html>