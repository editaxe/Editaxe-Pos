<!DOCTYPE html>
<html>
<head>
<title>How to Print Page Area using JavaScript by CodexWorld.com</title>
<script>
function printPageArea(areaID){
	var printContent = document.getElementById(areaID);
	var WinPrint = window.open('', '', 'width=400,height=600');
	WinPrint.document.write(printContent.innerHTML);
	WinPrint.document.close();
	WinPrint.focus();
	WinPrint.print();
	WinPrint.close();
}
</script>
    </head>
<body>

<div style="width: 200px;float: left;">
	<a href="javascript:void(0);" id="print_button1" onclick="printPageArea('wrapper')">Print Full Content</a>
	<br>
	<a href="javascript:void(0);" id="print_button2" onclick="printPageArea('btnImprimir')">Print Main Content</a>
</div>
<div id="wrapper" style="width: 302px;">


<div id="btnImprimir" style="width: 302px;text-align: center;"><div>


			

<table border="0" width="250x" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td style="text-align: center; width: 250x; font-family: Courier; font-size:10pt;"><img src="../imagenes/logo_empresa_factura_pos_blanco_negro.jpg" width="60px"></td>
</tr>
</table>

<table border="0" width="250x" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:13pt;">
<tr>
<td style="text-align: center; width: 250x; font-family: Courier; font-size:13pt;"><strong>SUPER MERCADO LA AVENIDA DE CERETE</strong></td>
</tr>
</table>

<table border="0" width="250x" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td style="text-align: center; width: 250x; font-family: Courier; font-size:10pt;"><strong>CERETE - CORDOBA</strong></td>
</tr>
</table>

<table border="0" width="250x" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td style="text-align: center; width: 250x; font-family: Courier; font-size:10pt;"><strong>NIT: 70698240-1</strong></td>
</tr>
</table>

<table border="0" width="250x" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td style="text-align: center; width: 250x; font-family: Courier; font-size:10pt;"><strong>DIRECCION: Cll 15 N. 9E - 47 BARRIO SANTA CLARA</strong></td>
</tr>
</table>

<table border="0" width="250x" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td style="text-align: center; width: 250x; font-family: Courier; font-size:10pt;"><strong>TELEFONO: 3205754390</strong></td>
</tr>
</table>


<table border="0" width="302px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">---------------------------------------</td>
</tr>
</table>


<table border="0" width="302px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
  <tr>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>FECHA: </strong></td>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>27/05/2019</strong></td>
  </tr>
  <tr>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>FACTURA DE VENTA: </strong></td>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>537</strong></td>
  </tr>
<!--
  <tr>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TIPO DE PAGO: </strong></td>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>'.$nombre_tipo_pago.'</strong></td>
  </tr>
-->
  <tr>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>CLIENTE: </strong></td>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>MOSTRADOR</strong></td>
  </tr>
  <tr>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>VENDEDOR (A): </strong></td>
    <td style="text-align: left; width: 302px; font-family: Courier; font-size:10pt;"><strong>administrador</strong></td>
  </tr>
</table>

<table border="0" width="302px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">---------------------------------------</td>
</tr>
</table>

<table border="0" width="250px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center" width="250px" colspan="4"></td>
</tr>
<tr>
<td style="text-align: left; width: 5px; font-family: Courier; font-size:10pt;"><strong>CANT</strong></td>
<td style="text-align: left; width: 30px; font-family: Courier; font-size:10pt;"><strong>ARTICULO</strong></td>
<td style="text-align: left; width: 2px; font-family: Courier; font-size:10pt;"><strong>P.UNIT</strong></td>
<td style="text-align: left; width: 2px; font-family: Courier; font-size:10pt;"><strong>P.TOTAL</strong></td>
</tr>
<tr>
<td align="center" width="250px" colspan="4">---------------------------------------</td>
</tr>
<?php
for ($i=1; $i < 11; $i++) { 
$cant = $i;
$punit = $cant * 1000;
$ptotal = $punit *$cant;
?>
<tr>
<td style="text-align: center; width: 5px; font-family: Courier; font-size:10pt;"><strong><?php echo $cant ?></strong></td>
<td style="text-align: left; width: 30px; font-family: Courier; font-size:10pt;"><strong>ARTICULO <?php echo $i ?></strong></td>
<td style="text-align: left; width: 2px; font-family: Courier; font-size:10pt;"><strong><?php echo number_format($punit, 0, ",", ".") ?></strong></td>
<td style="text-align: left; width: 2px; font-family: Courier; font-size:10pt;"><strong><?php echo number_format($ptotal, 0, ",", ".") ?></strong></td>
</tr>
<?php } ?>
</table>

<table border="0" width="302px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">---------------------------------------</td>
</tr>
</table>

<table border="0" width="302px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong>SUBTOTAL</strong></p></td>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong><?php echo number_format(111445, 0, ",", ".") ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong>%DESC</strong></p></td>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong><?php echo number_format(0, 0, ",", ".") ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong>$DESC</strong></p></td>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong><?php echo number_format(0, 0, ",", ".") ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong>IVA</strong></td>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong><?php echo number_format(4455, 0, ",", ".") ?></strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong>TOTAL</strong></td>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong><?php echo number_format(115900, 0, ",", ".") ?></strong></td>
  </tr>
</table>

<table border="0" width="302px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
<tr>
<td align="center">---------------------------------------</td>
</tr>
</table>

<table border="0" width="302px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:8pt;">
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:8pt;"><strong>FACTURA POR COMPUTADOR</strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:8pt;"><strong>RES DIAN: 13028014144934 DESDE JG 00001 AL JG 800000</strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:8pt;"><strong>REGIMEN RESPONSABLE DEL IMPUESTO A LAS VENTAS</strong></td>
  </tr>
</table>

<table border="0" width="302px" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:10pt;">
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;">**************************************</td>
  </tr>
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;"><strong>Muchas gracias por su compra</strong></td>
  </tr>
  <tr>
    <td style="text-align: center; width: 302px; font-family: Courier; font-size:10pt;">**************************************</td>
  </tr>
</table>

		</div>
	</div>
</div>
</body>
</html>