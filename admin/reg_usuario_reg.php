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
?>
<title>ALMACEN</title>
<head></head>
<body>
<br>
<?php
// verificamos si se han enviado ya las variables necesarias.
if (isset($_POST["nombre_usuario"])) {
$nombres            = strtoupper($_POST["nombres"]);
$apellidos          = strtoupper($_POST["apellidos"]);
$cuenta             = stripslashes($_POST["nombre_usuario"]);
$cuenta             = strip_tags($cuenta);
$sexo               = addslashes($_POST["sexo"]);
$correo             = addslashes($_POST["correo"]);
$estilo_css         = addslashes($_POST["diseno"]);
$cod_seguridad      = intval($_POST["cod_seguridad"]);
$cod_dependencia    = intval($_POST["cod_dependencia"]);
$fecha_hora         = date("H:i:s");
$fecha              = date("d-m-Y");
$pagina             = addslashes($_POST["pagina"]);

$contrasena1        = ($_POST['contrasena1']);
$contrasena2        = ($_POST["contrasena2"]);
$contrasena         = sha1($contrasena1);

// Hay campos en blanco
if($nombres==NULL || $apellidos==NULL || $cuenta==NULL || $contrasena1==NULL || $contrasena2==NULL || $cod_seguridad==NULL) {
echo "<font color='yellow' size='3' align='center'><br><br><strong><font color='yellow'><center> <img src=../imagenes/advertencia.gif alt='Advertencia'> Faltan campos por llenar. <img src=../imagenes/advertencia.gif alt='Advertencia'><font color='yellow'><center></strong></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="4; <?php echo $pagina;?>">
<?php
} else {
// ¿Coinciden las contraseñas?
if($contrasena1 != $contrasena2) {
echo "<br><br><font color='yellow' size='3' align='center'><strong><font color='yellow'><center><img src=../imagenes/advertencia.gif alt='Advertencia'> Las contrase&ntilde;as no coinciden. <img src=../imagenes/advertencia.gif alt='Advertencia'><font color='yellow'><center></strong></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="3; <?php echo $pagina;?>">
<?php
} else {
if($cod_seguridad < 1 || $cod_seguridad > 3) {
echo "<font color='yellow' size='3' align='center'><br><br><font color='yellow'><center><img src=../imagenes/advertencia.gif alt='Advertencia'> El codigo para el nivel de seguridad es invalido. <img src=../imagenes/advertencia.gif alt='Advertencia'><font color='yellow'><center></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="4; <?php echo $pagina;?>">
<?php
}
else {
// Comprobamos si el nombre de usuario o la cuenta de correo ya existían
$verificar_cuenta = mysql_query("SELECT cuenta FROM administrador WHERE cuenta = '$cuenta'");
$existenciar_cuenta = mysql_num_rows($verificar_cuenta);

if ($existenciar_cuenta>0) {
echo "<br><br><font color='yellow' size='3' align='center'><strong><font color='yellow'><center><img src=../imagenes/advertencia.gif alt='Advertencia'> El nombre de la cuenta ya esta en uso. <img src=../imagenes/advertencia.gif alt='Advertencia'><font color='yellow'><center></strong></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="4; <?php echo $pagina;?>">
<?php
} else {
$consulta = "INSERT INTO administrador (nombres, apellidos, sexo, cuenta, contrasena, correo, cod_seguridad, estilo_css, creador, fecha_hora, fecha, cod_dependencia) 
VALUES ('$nombres', '$apellidos', '$sexo', '$cuenta', '$contrasena', '$correo', '$cod_seguridad', '$estilo_css', '$creador', '$fecha_hora', '$fecha', '$cod_dependencia')";
$resultado_actualiza_productos = mysql_query($consulta, $conectar) or die(mysql_error());

echo "<br><br><font color='yellow' size='3' align='center'><strong>$nombres $apellidos </strong> HA SIDO REGISTRAD$sexo DE MANERA SATISFACTORIA.</font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="5; <?php echo $pagina;?>">
<?php
         }
      }
   }
 }
}else {

}
?>
</body>
</html>