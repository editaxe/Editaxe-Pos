<?php
$datos_factura = "SELECT cod_temporal FROM temporal WHERE (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja')";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$suma_temporal = "SELECT  Sum(vlr_total_venta) As total_venta, Sum(vlr_total_compra) As total_compra FROM temporal WHERE (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja')";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$matriz_temporal = mysql_fetch_assoc($consulta_temporal);

$maxima_factura = "SELECT Max(cod_factura) AS cod_factura FROM info_impuesto_facturas";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);

$datos_info_factura = "SELECT * FROM info_impuesto_facturas WHERE (estado = 'abierto') AND (vendedor = '$cuenta_actual') AND (cod_base_caja = '$cod_base_caja')";
$consulta_info_factura = mysql_query($datos_info_factura, $conectar) or die(mysql_error());
$info_factura = mysql_fetch_assoc($consulta_info_factura);
$factura_ocupada = mysql_num_rows($consulta_info_factura);

$cod_info_impuesto_facturas  = $info_factura['cod_info_impuesto_facturas'];
$cod_factura                 = $info_factura['cod_factura'];
$fecha_anyo                  = $info_factura['fecha_anyo'];
$cod_clientes                = $info_factura['cod_clientes'];
$vlr_cancelado               = $info_factura['vlr_cancelado'];
$descuento                   = $info_factura['descuento'];
$tipo_pago                   = $info_factura['tipo_pago'];
$bolsa                       = $info_factura['bolsa'];

$datos_info_cli = "SELECT * FROM clientes WHERE cod_clientes = '$cod_clientes'";
$consulta_info_cli = mysql_query($datos_info_cli, $conectar) or die(mysql_error());
$info_cli = mysql_fetch_assoc($consulta_info_cli);

$direccion_cli               = $info_cli['direccion'];
$ciudad_cli                  = $info_cli['ciudad'];
$telefono_cli                = $info_cli['telefono'];

require("funcion_verificar.php");
$requerir_funcion = new bloquear_multiple_intento;
?>
<script src="js/jquery-3.2.1.js"></script>

<script language="javascript">
$(document).ready(function(){
    $("#cod_clientes").on('change', function () {
        $("#cod_clientes option:selected").each(function () {
            var valor = $(this).val();
            var campo = "cod_clientes";
            $.post("guardar_temporal_cliente_factura_venta_ajax.php", { valor:valor, campo:campo, id: <?php echo $cod_info_impuesto_facturas; ?> }, function(data){
                $("#modelo").html(data);
            });     
        });
   });
});
</script>

<script language="javascript">
$(document).ready(function(){
    $("#descuento").on('change', function () {
        $("#descuento option:selected").each(function () {
            var valor = $(this).val();
            var campo = "descuento";
            $.post("guardar_temporal_cliente_factura_venta_ajax.php", { valor:valor, campo:campo, id: <?php echo $cod_info_impuesto_facturas; ?> }, function(data){
                $("#modelo").html(data);
            });     
        });
   });
});
</script>

<script language="javascript">
$(document).ready(function(){
var tipo_pago = $('#tipo_pago').val();
if (tipo_pago==1) { $('#fecha_pago').hide(); } else { $('#fecha_pago').show(); }
});
</script>

<script language="javascript">
$(document).ready(function(){
    $("#tipo_pago").on('change', function () {
        $("#tipo_pago option:selected").each(function () {
            var valor = $(this).val();
            var campo = "tipo_pago";

            if( valor == '2' ){ $('#fecha_pago').show(); } else { $('#fecha_pago').hide(); }

            $.post("guardar_temporal_cliente_factura_venta_ajax.php", { valor:valor, campo:campo, id: <?php echo $cod_info_impuesto_facturas; ?> }, function(data){
                $("#modelo").html(data);
            });     
        });
   });
});
</script>

<script>  
 $(document).ready(function(){  
$('input[name="bolsa"]').change(function(){  
var valor = $(this).val();
var campo = $(this).attr("name");  
let id = this.id;
var total_venta = $("#total_venta").html();
var valor_bolsa = 0;
var total_venta1 = 0;

if( $(this).is(':checked') ){ 
$(".bolsa").val("1"); 
valor = '1';
valor_bolsa = <?php echo $precio_bolsa_emp; ?> * valor;
total_venta1 =  Number(total_venta) + Number(valor_bolsa);
//document.getElementById("total_venta").innerHTML=total_venta1.toLocaleString("es-ES");
} 
else { $(".bolsa").val("0"); 
valor = '0';
valor_bolsa = <?php echo $precio_bolsa_emp; ?> * valor;
total_venta1 =  Number(total_venta) + Number(valor_bolsa);
//document.getElementById("total_venta").innerHTML=total_venta1.toLocaleString("es-ES");
}
           $.ajax({  
                url:"guardar_temporal_cliente_factura_venta_ajax.php",  
                method:"POST",  
                data:{valor:valor, campo:campo, id:id},  
                success:function(data){  
                     $('#result').html(data);  
                }  
           });  
      });
 });  
 </script>

<center>
<form method="post" name="formulario" action="../admin/venta_productos.php">
<table id="table" width="100%">

<!--<td style="text-align:center;" ><strong>FACTURA: </strong>-->
<input type="hidden" style="text-align:center; font-size:18px" name="numero_factura" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'numero_factura', <?php echo $cod_info_impuesto_facturas;?>)" id="numero_factura" value="<?php echo $cod_factura ?>" size="7">
<!--</td>-->

<input type="hidden" style="font-size:20px" name="cod_info_impuesto_facturas" value="<?php echo $cod_info_impuesto_facturas ?>" size="10">

<td style="text-align:center;" ><strong>FECHA VENTA: </strong><font size= "+1"><?php echo $fecha_anyo ?></font></td>
<input type="hidden" name="fecha_anyo" value="<?php echo $fecha_anyo ?>" size="10" required autofocus>

<td style="text-align:center;" ><strong>FECHA PAGO: </strong>
<input type="text" style="font-size:20px" tabindex=3 name="fecha_pago" id="fecha_pago" value="<?php echo  date("d/m/Y")?>" size="10" required autofocus></td>

<input type="hidden" name="monto_deuda" value="<?php echo $matriz_temporal['total_venta']; ?>" size="4">
<input type="hidden" style="font-size:20px" name="descuento_factura" value="0" size="10">

<input type="hidden" style="font-size:20px" name="iva" value="0" size="4" required autofocus>

<td style="text-align:center;" ><strong>TIPO PAGO: </strong>
<select name="tipo_pago" id="tipo_pago" class="selectpicker" data-show-subtext="true" data-live-search="true">
<?php if (isset($tipo_pago)) { echo "<option value='1' >...</option>";
} else { echo  "<option value='1' selected ></option>"; }
$consulta2_sql = "SELECT cod_tipo_pago, nombre_tipo_pago FROM tipo_pago ORDER BY cod_tipo_pago ASC";
$consulta2 = mysql_query($consulta2_sql, $conectar) or die(mysql_error());
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($tipo_pago) and $tipo_pago == $datos2['cod_tipo_pago']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['cod_tipo_pago'];
$nombre = $datos2['nombre_tipo_pago'];
echo "<option value='".$codigo."' $seleccionado >".$nombre."</option>"; } ?></select>
</td>

<td style="text-align:center;" ><strong>CLIENTE: </strong>
<select name="cod_clientes" id="cod_clientes" class="selectpicker" data-show-subtext="true" data-live-search="true">
<?php if (isset($cod_clientes)) { echo "<option value='1' >...</option>";
} else { echo  "<option value='1' selected ></option>"; }
$consulta2_sql = "SELECT cod_clientes, nombres, apellidos FROM clientes ORDER BY nombres ASC";
$consulta2 = mysql_query($consulta2_sql, $conectar) or die(mysql_error());
while ($datos2 = mysql_fetch_assoc($consulta2)) {
if(isset($cod_clientes) and $cod_clientes == $datos2['cod_clientes']) {
$seleccionado = "selected"; } else { $seleccionado = ""; }
$codigo = $datos2['cod_clientes'];
$nombres = $datos2['nombres'];
$apellidos = $datos2['apellidos'];
echo "<option value='".$codigo."' $seleccionado >".$nombres.' '.$apellidos."</option>"; } ?></select>
</td>

<td style="text-align:center;" ><strong>BOLSA </strong><span></span>
<input name="bolsa" type="checkbox" value='<?php echo $bolsa ?>' id="<?php echo $cod_info_impuesto_facturas ?>" class="bolsa" <?php if($bolsa=='1'){ echo 'checked'; } ?> ></td>

<!--<td><strong>T.COSTO: </strong><font size= "+3"><?php echo number_format($matriz_temporal['total_compra'], 0, ",", "."); ?></font></td>-->
<td style="text-align:center; font-size:20;"><strong>TOTAL VENTA: </strong></td>
<td style="text-align:center; font-size:30;"><span id="total_venta"><?php echo number_format($matriz_temporal['total_venta'], 0, ",", "."); ?></span></td>

<td><strong>RECIBIDO: </strong><input type="text" style="font-size:28px" name="vlr_cancelado" value="" size="8" required autofocus></td>

<input type="hidden" name="total_datos" value="<?php echo $total_datos; ?>" size="4">
<?php while ($datos = mysql_fetch_assoc($consulta)) {?>
<input type="hidden" name="cod_temporal[]" value="<?php echo $datos['cod_temporal']; ?>" size="4">
<?php } ?>
<?php 
$pagina ='temporal.php'; 
?>
<input type="hidden" name="pagina" value="<?php echo $pagina?>" size="15">
<input type="hidden" name="flete" value="0" size="15">
<input type="hidden" name="verificacion_envio" value="1" size="2">
<td><a><input type="image" src="../imagenes/ok.png" tabindex=3 name="vender" value="Guardar" /></a></td>
</table>
<?php $requerir_funcion->iniciar(); ?>