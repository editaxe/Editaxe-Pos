<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

require_once("menu_estadisticas.php");
include ("menu_grafico_ventas.php");

$sql_info = "SELECT nombre FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta_info = mysql_query($sql_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);

$empresa = $info['nombre'];
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="jquery.min.js"></script>
		<style type="text/css">
#container {
    height: 400px; 
    min-width: 10%; 
    max-width: 100%;
    margin: 0 auto;
}
		</style>
		<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column',
            margin: 100,
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 25,
                depth: 200
            }
        },
        title: {
            text: '<?php echo utf8_decode("VENTAS POR AÑO")?>'
        },
        subtitle: {
            text: '<?php echo $empresa ?>'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: {
            categories: [
<?php
$sql = "SELECT anyo FROM ventas GROUP BY anyo ORDER BY anyo ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
while ($registros = mysql_fetch_array($result)) {
?>
'<?php echo $registros["anyo"] ?>',
<?php
}
?>
            ]
        },
        yAxis: {
            title: {
                text: null
            }
        },
        series: [{
            name: 'VENTAS',
            data: [
<?php
$sql = "SELECT SUM(vlr_total_venta) AS vlr_total_venta FROM ventas GROUP BY anyo ORDER BY anyo ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
while ($registros = mysql_fetch_array($result)) {
$vlr_total_venta = intval($registros["vlr_total_venta"]);
?>
<?php echo $vlr_total_venta ?>,
<?php
}
?>
            ]
        }]
    });
});
		</script>
	</head>
	<body>
<script src="highcharts.js"></script>
<!--<script src="highcharts-3d.js"></script>-->
<script src="exporting.js"></script>
<div id="container" style="height: 500px"></div>
</body>
</html>
