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
    min-width: 10%; 
    max-width: 100%;
    margin: 0 auto;
}
        </style>
               <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'scatter',
            zoomType: 'xy'
        },
        title: {
            text: 'VENTAS MESES'
        },
        subtitle: {
            text: '<?php echo $empresa ?>'
        },
        xAxis: {
            title: {
                enabled: true,
                text: 'Tiempo (T)'
            },
            startOnTick: true,
            endOnTick: true,
            showLastLabel: true
        },
        yAxis: {
            title: {
                text: 'Pesos ($)'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 100,
            y: 70,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
            borderWidth: 1
        },
        plotOptions: {
            scatter: {
                marker: {
                    radius: 5,
                    states: {
                        hover: {
                            enabled: true,
                            lineColor: 'rgb(100,100,100)'
                        }
                    }
                },
                states: {
                    hover: {
                        marker: {
                            enabled: false
                        }
                    }
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br>',
                    pointFormat: '{point.x} T, {point.y} $'
                }
            }
        },
        series: [{
            name: 'T.Compra - T.Venta',
            color: 'rgba(223, 83, 83, .5)',
            data: [
<?php
$sql = "SELECT SUM(vlr_total_compra) AS vlr_total_compra, SUM(vlr_total_venta) AS vlr_total_venta, fecha_mes FROM ventas GROUP BY fecha_mes ORDER BY fecha ASC";
$result = mysql_query($sql, $conectar);
while ($registros = mysql_fetch_array($result)) {

$vlr_total_compra = intval($registros["vlr_total_compra"]);
$vlr_total_venta = intval($registros["vlr_total_venta"]);
?>
[
<?php
echo $vlr_total_compra ?>, <?php echo $vlr_total_venta ?>
],
<?php
}
?>
             ]

        }, {
            name: 'P.Costo - P.Venta',
            color: 'rgba(119, 152, 191, .5)',
            data: [
<?php
$sql = "SELECT SUM(precio_costo) AS precio_costo, SUM(precio_venta) AS precio_venta FROM ventas GROUP BY fecha_mes ORDER BY fecha ASC";
$result = mysql_query($sql, $conectar);
while ($registros = mysql_fetch_array($result)) {

$precio_costo = intval($registros["precio_costo"]);
$precio_venta = intval($registros["precio_venta"]);
?>
[
<?php
echo $precio_costo ?>, <?php echo $precio_venta ?>
],
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
<script src="../js/highcharts.js"></script>
<!--<script src="highcharts-3d.js"></script>-->
<script src="../js/modules/exporting.js"></script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</body>
</html>
