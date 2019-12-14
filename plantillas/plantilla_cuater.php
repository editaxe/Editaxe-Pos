<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php');
require_once('../session/funciones_admin.php');
mysql_select_db($base_datos, $conectar);
date_default_timezone_set("America/Bogota");
?>
<!doctype html>
<html>
<head>
<meta content="text/html; charset=iso-8859-1" http-equiv=Content-Type> 
<?php
$cuenta_actual     = addslashes($_SESSION['usuario']);
$cod_seguridad     = intval($_SESSION['cod_seguridad']);
$cod_base_caja     = intval($_SESSION['cod_base_caja']);
$cod_dependencia   = intval($_SESSION['cod_dependencia']);

$obtener_diseno_plantilla = "SELECT * FROM administrador WHERE cuenta = '$cuenta_actual'";
$resultado_diseno_plantilla = mysql_query($obtener_diseno_plantilla, $conectar) or die(mysql_error());
$matriz_plantilla = mysql_fetch_assoc($resultado_diseno_plantilla);

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);

$titulo_emp                           = $matriz_informacion['titulo'];
$nombre_emp                           = $matriz_informacion['nombre'];
$eslogan_emp                          = $matriz_informacion['eslogan'];
$res_emp                              = $matriz_informacion['res'];
$res1_emp                             = $matriz_informacion['res1'];
$res2_emp                             = $matriz_informacion['res2'];
$vigencia_res_emp                     = $matriz_informacion['vigencia_res'];
$fecha_res_emp                        = $matriz_informacion['fecha_res'];
$localidad_emp                        = $matriz_informacion['localidad'];
$direccion_emp                        = $matriz_informacion['direccion'];
$ciudad_emp                           = $matriz_informacion['ciudad'];
$cabecera_emp                         = $matriz_informacion['cabecera'];
$img_cabecera_emp                     = $matriz_informacion['img_cabecera'];
$telefono_emp                         = $matriz_informacion['telefono'];
$nit_emp                              = $matriz_informacion['nit'];
$regimen_emp                          = $matriz_informacion['regimen'];
$info_legal_emp                       = $matriz_informacion['info_legal'];
$iva_global_emp                       = $matriz_informacion['iva_global'];
$logotipo_emp                         = $matriz_informacion['logotipo'];
$icono_emp                            = $matriz_informacion['icono'];
$version_emp                          = $matriz_informacion['version'];
$desarrollador_emp                    = $matriz_informacion['desarrollador'];
$correo_desarrollador_emp             = $matriz_informacion['correo_desarrollador'];
$tel_desarrollador_emp                = $matriz_informacion['tel_desarrollador'];
$pag_desarrollador_emp                = $matriz_informacion['pag_desarrollador'];
$anyo_emp                             = $matriz_informacion['anyo'];
$propietario_nombres_apellidos_emp    = $matriz_informacion['propietario_nombres_apellidos'];
$propietario_nit_emp                  = $matriz_informacion['propietario_nit'];
$propietario_url_firma_emp            = $matriz_informacion['propietario_url_firma'];
$smtp_correo_host_emp                 = $matriz_informacion['smtp_correo_host'];
$smtp_correo_auth_emp                 = $matriz_informacion['smtp_correo_auth'];
$smtp_correo_username_emp             = $matriz_informacion['smtp_correo_username'];
$smtp_correo_password_emp             = $matriz_informacion['smtp_correo_password'];
$smtp_correo_secure_emp               = $matriz_informacion['smtp_correo_secure'];
$smtp_correo_port_emp                 = $matriz_informacion['smtp_correo_port'];
$nombre_impresora1_emp                = $matriz_informacion['nombre_impresora1'];
$nombre_impresora2_emp                = $matriz_informacion['nombre_impresora2'];
$nombre_bolsa_emp                     = $matriz_informacion['nombre_bolsa'];
$precio_bolsa_emp                     = $matriz_informacion['precio_bolsa'];
$ptj_bolsa_emp                        = $matriz_informacion['ptj_bolsa'];

$obtener_notificacion_alerta = "SELECT * FROM notificacion_alerta ORDER BY rand() LIMIT 1";
$resultado_notificacion_alerta = mysql_query($obtener_notificacion_alerta, $conectar) or die(mysql_error());
$total_alerta = mysql_num_rows($resultado_notificacion_alerta);
$alerta = mysql_fetch_assoc($resultado_notificacion_alerta);

$cod_notificacion_alerta              = $alerta['cod_notificacion_alerta'];
$tipo_notificacion_alerta             = $alerta['tipo_notificacion_alerta'];
$msj                                  = $alerta['nombre_notificacion_alerta'];
$cod_productos_ance                   = $alerta['cod_productos_var'];
$nombre_productos_ance                = $alerta['nombre_productos'];
$nombre_proveedores_ance              = $alerta['nombre_proveedores'];
$nombre_clientes_ance                 = $alerta['nombre_clientes'];
$producto_alerta                      = $nombre_productos_ance;
$cod_factura_ance                     = $alerta['cod_factura'];

$fecha_invert = intval($alerta['fecha_invert']);
$fecha_hoy_seg = strtotime(date("Y/m/d"));

$ipss = $_SERVER['REMOTE_ADDR'];
$fecha_hoy = date("d/m/Y");
?>
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" href="../estilo_css/<?php echo $matriz_plantilla['estilo_css'];?>">

<title><?php echo $titulo_emp;?></title>
</head>
<body>
<center>
<fieldset class="menu">
<h3><div align="left"><td><strong><font color="yellow">FECHA: <?php echo $fecha_hoy;?></font></strong></td></div></h3>

<h2><?php echo $matriz_informacion['nombre'];?></h2>
<h1><?php //echo $matriz_pagina_actual['nombre_pagina_actual'];?></h1>
<h4><?php echo "BIENVENID".$matriz_plantilla['sexo'].' '.$matriz_plantilla['nombres'].' '.$matriz_plantilla['apellidos'];?></h4>
</fieldset>
<ul class="menu">

<li><a href="#">Ventas</a>
		 <ul>
		 	<li><a href="../admin/temporal.php">Manual</a></li>
		 	<li><a href="../admin/factura_eletronica.php">Electronica</a></li>
		 </ul>

	<li><a href="#">Caja</a>
			<ul>
				<li><a href="../admin/caja_registro.php">Reg Caja</a></li>
			</ul>
	</li>


<li><a href="../admin/clientes_vendedor.php">Clientes</a></li>
<li><a href="../admin/selecionar_diseno.php">Dise&ntilde;os</a></li>
		
<li><a href="../admin/mensajeria.php">Mensajeria</a></li>
<!--<li><a href="../admin/exportacion_csv_vendedor.php">Actualizar</a></li>-->
<li><a href="../session/salir_admin.php">Cerrar</a></li>
</ul>
</center>
<br><br><br>
