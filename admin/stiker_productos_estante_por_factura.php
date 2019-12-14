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
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$pagina = $_SERVER['PHP_SELF'];
//$mostrar_datos_sql = "SELECT * FROM devoluciones WHERE (doc = '$buscar' OR nombre_y_apellidos LIKE '%$buscar%') ORDER BY nombre_y_apellidos DESC";
$mostrar_datos_sql = "SELECT cod_factura, fecha_anyo FROM stiker_productos_estante GROUP BY cod_factura ORDER BY cod_factura DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);

//require_once("menu_facturas_compra.php");
?>
<br>
<center>
<table width="60%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>FECHA</strong></td>
</tr>
<?php do { 
$cod_factura = $datos['cod_factura'];
$fecha_anyo = $datos['fecha_anyo'];
?>
<td align="center"><a href="../modificar_eliminar/eliminar.php?tipo=eliminar&tab=stiker_productos_estante&cod_factura=<?php echo $cod_factura?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></center></a></td>
<td align="center"><a href="../admin/ver_stiker_productos_estante.php?cod_factura=<?php echo $cod_factura?>"><?php echo str_pad($cod_factura, 5, "0", STR_PAD_LEFT); ?></a></td>
<td align="center"><a href="../admin/ver_stiker_productos_estante.php?cod_factura=<?php echo $cod_factura?>"><?php echo $fecha_anyo; ?></a></td>
</tr>
<?php 
}
while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
</table>
</form>
</center>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>