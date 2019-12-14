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
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'EGRESOS POR CONCEPTOS'
        },
        subtitle: {
            text: '<?php echo $empresa ?>'
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        series: [{
            name: 'Total Costo',
            data: [
<?php
$sql = "SELECT conceptos, costo FROM egresos GROUP BY conceptos ORDER BY costo DESC";
$result = mysql_query($sql, $conectar);
while ($registros = mysql_fetch_array($result)) {

$conceptos = $registros["conceptos"];
$costo = intval($registros["costo"]);
?>
                ['<?php echo $conceptos ?>', <?php echo $costo ?>],
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
<script src="../js/highcharts-3d.js"></script>
<script src="../js/modules/exporting.js"></script>
<div id="container" style="height: 400px"></div>
	</body>
</html>
