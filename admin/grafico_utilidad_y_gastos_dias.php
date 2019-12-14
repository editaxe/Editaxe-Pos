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
include ("menu_grafico_utilidad_y_gastos.php");

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
            zoomType: 'xy'
        },
        title: {
            text: 'UTILIDAD VS GASTOS POR DIAS'
        },
        subtitle: {
            text: '<?php echo $empresa ?>'
        },
        xAxis: [{
            categories: [
<?php
$sql = "SELECT fecha FROM egresos GROUP BY fecha ORDER BY fecha ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
$total = mysql_num_rows($result);
while ($registros = mysql_fetch_array($result)) {
$fecha_anyo_seg = $registros["fecha"];
$fecha_anyo = date("d/m/Y", $fecha_anyo_seg);
?>
'<?php echo $fecha_anyo ?>',
<?php
}
?>
            ],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Costos',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Ventas',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: [{
            name: 'TOTAL UTILIDAD',
            type: 'column',
            yAxis: 1,
            data: [
<?php
$sql = "SELECT SUM(vlr_total_venta-vlr_total_compra) AS vlr_total_venta FROM ventas GROUP BY fecha ORDER BY fecha ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
while ($registros = mysql_fetch_array($result)) {
$vlr_total_venta = intval($registros["vlr_total_venta"]);
?>
<?php echo $vlr_total_venta ?>,
<?php
}
?>
            ],
            tooltip: {
                valueSuffix: ''
            }

        }, {
            name: 'TOTAL GASTOS',
            type: 'spline',
            yAxis: 1,
            data: [
<?php
$sql = "SELECT SUM(costo) AS costo FROM egresos GROUP BY fecha_invert ORDER BY fecha_invert ASC";
$result = mysql_query($sql, $conectar) or die(mysql_error());
while ($registros = mysql_fetch_array($result)) {
$costo = intval($registros["costo"]);
?>
<?php echo $costo ?>,
<?php
}
?>
            ],
            tooltip: {
                valueSuffix: ''
            }

        }, ]
    });
});
        </script>
    </head>
    <body>
<script src="highcharts.js"></script>
<script src="exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</body>
</html>