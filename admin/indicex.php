<html>
<head>
<script src="js/jquery.min.js" type="text/javascript"></script>
<script src="js/jquery.jqprint-0.3.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
$(function() {
  $("#PrintButton").click( function() {
      $('#divToPrint').jqprint();
      return false;
  });
});
</script>
</head>
<body>
<input id="PrintButton" type="button" name="Print" value="Print" />

<div id="divToPrint" class="test">
Print Me, I'm in DIV

<table align="center" border="0" width="95%" cellspacing="0" cellpadding="0" style="font-family: Courier; font-size:40pt;">
<tr>
<td align="center"><p style="font-size:50px"><img src="../imagenes/logo_empresa_factura_pos_blanco_negro.jpg"></p></td>
</tr>
</table>

</div>
<body>
</html>