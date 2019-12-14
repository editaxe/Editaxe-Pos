<?php error_reporting(E_ALL ^ E_NOTICE);
include_once('../conexiones/conexione.php'); 
include_once('../evitar_mensaje_error/error.php'); 
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

include_once("menu_estadisticas.php");
include ("menu_grafico_extras.php");

$sql_info = "SELECT nombre FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta_info = mysql_query($sql_info, $conectar);
$info = mysql_fetch_array($consulta_info);

$empresa = $info['nombre'];
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<style type="text/css">
#container {
    height: 400px; 
    min-width: 100%; 
    max-width: 100%;
    margin: 0 auto;
}
		</style>
		        <script type="text/javascript">
$(function () {

    // Give the points a 3D feel by adding a radial gradient
    Highcharts.getOptions().colors = $.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.4,
                cy: 0.3,
                r: 0.5
            },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.2).get('rgb')]
            ]
        };
    });

    // Set up the chart
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'container',
            margin: 100,
            type: 'scatter',
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 30,
                depth: 250,
                viewDistance: 5,

                frame: {
                    bottom: { size: 1, color: 'rgba(0,0,0,0.02)' },
                    back: { size: 1, color: 'rgba(0,0,0,0.04)' },
                    side: { size: 1, color: 'rgba(0,0,0,0.06)' }
                }
            }
        },
        title: {
            text: 'VENTAS MESES 3D'
        },
        subtitle: {
            text: '<?php echo $empresa ?>'
        },
        plotOptions: {
            scatter: {
                width: 100,
                height: 100,
                depth: 10
            }
        },
        yAxis: {
            min: 0,
            max: 100000000,
            title: null
        },
        xAxis: {
            min: 0,
            max: 100000000,
            gridLineWidth: 1
        },
        zAxis: {
            min: 0,
            max: 100000000,
            showFirstLabel: false
        },
        legend: {
            enabled: false
        },
        series: [{
            name: 'Reading',
            colorByPoint: true,
            data: [
<?php
$sql = "SELECT SUM(vlr_total_compra) AS vlr_total_compra, SUM(vlr_total_venta) AS vlr_total_venta, SUM(precio_compra) AS precio_compra FROM ventas GROUP BY fecha_mes ORDER BY fecha ASC";
$result = mysql_query($sql, $conectar);
while ($registros = mysql_fetch_array($result)) {

$vlr_total_compra = intval($registros["vlr_total_compra"]);
$vlr_total_venta = intval($registros["vlr_total_venta"]);
$precio_compra = intval($registros["precio_compra"]);
?>
[
<?php
echo $vlr_total_compra ?>, <?php echo $vlr_total_venta ?>, <?php echo $precio_compra ?>
],
<?php
}
?>
            ]
        }]
    });


    // Add mouse events for rotation
    $(chart.container).bind('mousedown.hc touchstart.hc', function (e) {
        e = chart.pointer.normalize(e);

        var posX = e.pageX,
            posY = e.pageY,
            alpha = chart.options.chart.options3d.alpha,
            beta = chart.options.chart.options3d.beta,
            newAlpha,
            newBeta,
            sensitivity = 5; // lower is more sensitive

        $(document).bind({
            'mousemove.hc touchdrag.hc': function (e) {
                // Run beta
                newBeta = beta + (posX - e.pageX) / sensitivity;
                chart.options.chart.options3d.beta = newBeta;

                // Run alpha
                newAlpha = alpha + (e.pageY - posY) / sensitivity;
                chart.options.chart.options3d.alpha = newAlpha;

                chart.redraw(false);
            },
            'mouseup touchend': function () {
                $(document).unbind('.hc');
            }
        });
    });

});
        </script>
    </head>
    <body>
<script src="../js/highcharts.js"></script>
<script src="../js/highcharts-3d.js"></script>
<script src="../js/modules/exporting.js"></script>
<div id="container" style="height: 400px"></div>
	</body>
</html>
