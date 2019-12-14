<?php
$datos_factura = "SELECT cod_plan_separe_temporal FROM plan_separe_temporal WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$suma_plan_separe_temporal = "SELECT  Sum(vlr_total_venta) As total_venta, Sum(vlr_total_compra) As total_compra FROM plan_separe_temporal WHERE vendedor = '$cuenta_actual'";
$consulta_plan_separe_temporal = mysql_query($suma_plan_separe_temporal, $conectar) or die(mysql_error());
$matriz_plan_separe_temporal = mysql_fetch_assoc($consulta_plan_separe_temporal);

$maxima_factura = "SELECT Max(cod_plan_separe) AS cod_plan_separe FROM plan_separe_info_impuesto";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);

$datos_info_factura = "SELECT * FROM plan_separe_info_impuesto WHERE estado = 'abierto' AND vendedor = '$cuenta_actual'";
$consulta_info_factura = mysql_query($datos_info_factura, $conectar) or die(mysql_error());
$info_factura = mysql_fetch_assoc($consulta_info_factura);
$factura_ocupada = mysql_num_rows($consulta_info_factura);

$cod_plan_separe_info_impuesto  = $info_factura['cod_plan_separe_info_impuesto'];
$cod_plan_separe                    = $info_factura['cod_plan_separe'];
$fecha_anyo                     = $info_factura['fecha_anyo'];
$cod_clientes                   = $info_factura['cod_clientes'];
$vlr_cancelado                  = $info_factura['vlr_cancelado'];
$descuento                      = $info_factura['descuento'];
$tipo_pago                      = $info_factura['tipo_pago'];
$bolsa                          = $info_factura['bolsa'];
$fecha_ini_plan_separe          = $info_factura['fecha_ini_plan_separe'];
$fecha_fin_plan_separe          = $info_factura['fecha_fin_plan_separe'];
$total_plan_separe              = $info_factura['total_plan_separe'];
$total_abono_plan_separe        = $info_factura['total_abono_plan_separe'];
$total_saldo_plan_separe        = $info_factura['total_saldo_plan_separe'];

$datos_info_cli = "SELECT * FROM clientes WHERE cod_clientes = '$cod_clientes'";
$consulta_info_cli = mysql_query($datos_info_cli, $conectar) or die(mysql_error());
$info_cli = mysql_fetch_assoc($consulta_info_cli);

$direccion_cli                  = $info_cli['direccion'];
$ciudad_cli                     = $info_cli['ciudad'];
$telefono_cli                   = $info_cli['telefono'];
?>
<script src="js/jquery-3.2.1.js"></script>

<script language="javascript">
$(document).ready(function(){
    $("#cod_clientes").on('change', function () {
        $("#cod_clientes option:selected").each(function () {
            var valor = $(this).val();
            var campo = "cod_clientes";
            $.post("guardar_plan_separe_producto_cliente_factura_venta_ajax.php", { valor:valor, campo:campo, id: <?php echo $cod_plan_separe_info_impuesto; ?> }, function(data){
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
            $.post("guardar_plan_separe_producto_cliente_factura_venta_ajax.php", { valor:valor, campo:campo, id: <?php echo $cod_plan_separe_info_impuesto; ?> }, function(data){
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

            $.post("guardar_plan_separe_producto_cliente_factura_venta_ajax.php", { valor:valor, campo:campo, id: <?php echo $cod_plan_separe_info_impuesto; ?> }, function(data){
            $("#modelo").html(data);
            });     
        });
   });
});
</script>

<script language="javascript">
$(document).ready(function(){
    $("#fecha_ini_plan_separe").on('change', function () {
            var valor = $(this).val();
            var campo = "fecha_ini_plan_separe";
            $.post("guardar_plan_separe_producto_cliente_factura_venta_ajax.php", { valor:valor, campo:campo, id: <?php echo $cod_plan_separe_info_impuesto; ?> }, function(data){
            $("#modelo").html(data);
        });
   });
});
</script>

<script language="javascript">
$(document).ready(function(){
    $("#fecha_fin_plan_separe").on('change', function () {
            var valor = $(this).val();
            var campo = "fecha_fin_plan_separe";
            $.post("guardar_plan_separe_producto_cliente_factura_venta_ajax.php", { valor:valor, campo:campo, id: <?php echo $cod_plan_separe_info_impuesto; ?> }, function(data){
            $("#modelo").html(data);
        });
   });
});
</script>

<center>
<form method="post" name="formulario" action="../admin/venta_plan_separe_productos.php">
<table id="table" width="100%">

<td style="text-align:center;"><strong>FACTURA: </strong><input style="text-align:center; font-size:18px" name="numero_factura" onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'numero_factura', <?php echo $cod_plan_separe_info_impuesto;?>)" id="numero_factura" value="<?php echo $cod_plan_separe ?>" size="7" required></td>

<td style="text-align:center;"><strong>FECHA INICIO: </strong><input style="text-align:center; font-size:18px" name="fecha_ini_plan_separe" id="fecha_ini_plan_separe" value="<?php echo $fecha_ini_plan_separe ?>" size="10" required></td>

<td style="text-align:center;"><strong>FECHA VENCIMIENTO: </strong><input style="text-align:center; font-size:18px" name="fecha_fin_plan_separe" id="fecha_fin_plan_separe" value="<?php echo $fecha_fin_plan_separe ?>" size="10" required></td>

<input type="hidden" name="monto_deuda" value="<?php echo $matriz_plan_separe_temporal['total_venta']; ?>" size="4">

<input type="hidden" style="font-size:18px" name="iva" value="0" size="4" required autofocus>

<td style="text-align:center;"><strong>CLIENTE: </strong>
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

<!--<td><strong>T.COSTO: </strong><font size= "+3"><?php echo number_format($matriz_plan_separe_temporal['total_compra'], 0, ",", "."); ?></font></td>-->
<td style="text-align:center; font-size:20;"><strong>TOTAL: </strong></td>
<td style="text-align:center; font-size:30;" id="total_venta"><?php echo number_format($matriz_plan_separe_temporal['total_venta'], 0, ",", "."); ?></td>

<td style="text-align:center;"><strong>ABONAR: </strong><input type="text" style="font-size:28px" name="vlr_cancelado" value="" size="8" required autofocus></td>

<input type="hidden" name="total_datos" value="<?php echo $total_datos; ?>" size="4">
<?php while ($datos = mysql_fetch_assoc($consulta)) {?>
<input type="hidden" name="cod_plan_separe_temporal[]" value="<?php echo $datos['cod_plan_separe_temporal']; ?>" size="4">
<?php } ?>
<?php
$pagina ='plan_separe_temporal_admin.php'
?>
<input type="hidden" name="pagina" value="<?php echo $pagina?>" size="15">
<input type="hidden" name="flete" value="0" size="15">
<input type="hidden" name="verificacion_envio" value="1" size="15">
<td><a><input type="image" src="../imagenes/ok.png" tabindex=3 name="vender" value="Guardar" /></a></td>
</table>