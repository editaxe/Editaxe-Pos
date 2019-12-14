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
    $('#container').highcharts({
        xAxis: {
            min: -0.5,
            max: 50
        },
        yAxis: {
            min: 0
        },
        title: {
            text: 'VENTAS MESES REGRESION'
        },
        series: [{
            type: 'line',
            name: 'Line de Regresion',
            data: [[0, 0], [160000, 160000]],
            marker: {
                enabled: false
            },
            states: {
                hover: {
                    lineWidth: 0
                }
            },
            enableMouseTracking: false
        }, {
            type: 'scatter',
            name: 'Datos',
            data: [
<?php
$sql = "SELECT SUM(vlr_total_compra) AS vlr_total_compra FROM ventas GROUP BY fecha_mes ORDER BY fecha ASC";
$result = mysql_query($sql, $conectar);
while ($registros = mysql_fetch_array($result)) {

$vlr_total_compra = intval($registros["vlr_total_compra"]);
?>
<?php
echo $vlr_total_compra ?>, 
<?php
}
?>
            ],
            marker: {
                radius: 4
            }
        }]
    });
});
        </script>
    </head>
    <body>
<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>
<div id="container" style="height: 400px"></div>
	</body>
</html>
