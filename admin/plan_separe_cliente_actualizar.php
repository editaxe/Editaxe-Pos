<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);

if (isset($_GET["cod_clientes"])) {
$cod_clientes = intval($_GET['cod_clientes']);
$pagina       = addslashes($_GET['pagina']);

$sql_plan_separe = "SELECT cod_plan_separe FROM plan_separe WHERE cod_clientes = '$cod_clientes'";
$consulta_plan_separe = mysql_query($sql_plan_separe, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_plan_separe);

while ($datos_plan_separe = mysql_fetch_assoc($consulta_plan_separe)) {

$cod_plan_separe                    = $datos_plan_separe['cod_plan_separe'];

$sql_plan_separe_factura = "SELECT total_plan_separe FROM plan_separe WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_plan_separe_factura = mysql_query($sql_plan_separe_factura, $conectar) or die(mysql_error());
$dato_plan_separe_factura = mysql_fetch_assoc($consulta_plan_separe_factura);

$sql_total_abono_factura = "SELECT SUM(abono_plan_separe) AS abono_plan_separe FROM plan_separe_abono WHERE cod_plan_separe = '$cod_plan_separe'";
$consulta_total_abono_factura = mysql_query($sql_total_abono_factura, $conectar) or die(mysql_error());
$total_abono_factura = mysql_fetch_assoc($consulta_total_abono_factura);

$total_plan_separe              = $dato_plan_separe_factura['total_plan_separe'];
$total_abono_plan_separe        = $total_abono_factura['abono_plan_separe'];
$total_saldo_plan_separe        = $total_plan_separe - $total_abono_plan_separe;

$actualizar_sql1 = sprintf("UPDATE plan_separe SET total_abono_plan_separe = '$total_abono_plan_separe', total_saldo_plan_separe = '$total_saldo_plan_separe' WHERE cod_plan_separe = '$cod_plan_separe'");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; <?php echo $pagina ?>">
<?php } ?>