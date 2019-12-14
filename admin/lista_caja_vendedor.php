<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>

<br><br>
<center>
<?php
if (isset($_GET['pagina'])) { $pagina = addslashes($_GET['pagina']); } else { $pagina = 'temporal.php'; }
?>
<td><strong><font color='white'>CAJAS VENDEDOR</font> <a href="../admin/crear_caja_vendedor_sesion.php?pagina=<?php echo $pagina ?>"><font color='yellow'>CREAR CAJA</font></a>

<br>
<table width="10%">
<?php
$mostrar_datos_sql = "SELECT cod_base_caja FROM temporal WHERE vendedor = '$cuenta_actual' GROUP BY cod_base_caja";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

while ($datos = mysql_fetch_assoc($consulta)) {
$cod_base_caja = $datos['cod_base_caja'];
?>
<tr>
<td><font size='3'><a href="../admin/entrar_caja_vendedor_sesion.php?cod_base_caja=<?php echo $cod_base_caja ?>&pagina=<?php echo $pagina ?>">CAJA <?php echo $cod_base_caja;?></a></font></td>
</tr>
<?php } ?>
</table>

</body>
</html>
