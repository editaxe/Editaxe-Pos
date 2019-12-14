<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
?>

<title>ALMACEN</title>
<head><link rel="stylesheet" type="text/css" href="../estilo_css/estilo_acceso.css">
<br>
<form id="acceso" action="../admin/reg_usuario_reg.php" method="post">
<legend><font size="4">FORMULARIO DE REGISTRO</font></legend>
<br>
<li>
<label for="nombre">Nombres</label>
<input id="nombres" name="nombres" type=text placeholder="Escribe tus nombres" required autofocus>
</li>
<br>
<li>
<label for="nombre">Apellidos</label>
<input id="apellidos" name="apellidos" type=text placeholder="Escribe tus apellidos" required autofocus>
</li>
<br>
<li>
<label for="nombre">Sexo</label>
<select id="acceso" name="sexo">
<option value="O">Masculino</option>
<option value="A">Femenino</option>
</select> 
</li>
<br>
<li>
<label for="nombre">Usuario</label>
<input id="nombre_usuario" name="nombre_usuario" type=text placeholder="Escribe tu nombre de usuario" required>
</li>

<br>

<li>
<label for="nombre">Tipo Usuario</label>
<select name="cod_seguridad" id="acceso">
<?php $sql_consulta = "SELECT cod_seguridad, nombre_seguridad FROM seguridad ORDER BY cod_seguridad ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_assoc($resultado)) { ?>
<option value="<?php echo $contenedor['cod_seguridad'] ?>"><?php echo $contenedor['nombre_seguridad'] ?></option>
<?php } ?>
</select>
</li>

<br>
<li>
<label for="nombre">Dependencia</label>
<select name="cod_dependencia" id="acceso">
<?php $sql_consulta = "SELECT cod_dependencia, nombre_dependencia FROM dependencia ORDER BY cod_dependencia ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_assoc($resultado)) { ?>
<option value="<?php echo $contenedor['cod_dependencia'] ?>"><?php echo $contenedor['nombre_dependencia'] ?></option>
<?php } ?>
</select>
</li>


<br>
<li>
<label for="nombre">Contrase&ntilde;a</label>
<input id="contrasena1" name="contrasena1" type="password" placeholder="Escribe tu contrase&ntilde;a" required>
</li>
<br>
<li>
<label for="nombre">Contrase&ntilde;a</label>
<input id="contrasena2" name="contrasena2" type="password" placeholder="Repita la contrase&ntilde;a" required>
</li>
<br>
<li>
<label for="email">Email</label>
<input id="correo" name="correo" type="email" placeholder="ejemplo@dominio.com">
<input id="diseno" name="diseno" type="hidden" value="azul_verdoso.css">
<input id="pagina" name="pagina" type="hidden" value="../admin/ver_administrador.php">
</li>
<br><br>
 <input type="submit" id="submit" value="Registrar" />
</form>