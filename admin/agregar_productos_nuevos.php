<?php
require_once('../conexiones/conexione.php'); 
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

if (isset($_POST["pagina"])) { $pagina = addslashes($_POST["pagina"]); }

if (isset($_POST["nombre_productos"])) {
$cod_productos_var       = addslashes($_POST["cod_productos_var"]);
$cod_marcas              = intval($_POST["cod_marcas"]);
$cod_nomenclatura        = intval($_POST["cod_nomenclatura"]);
$unidades                = addslashes($_POST["unidades"]);
$cajas                   = intval($_POST["cajas"]);
$detalles                = addslashes($_POST["nombre_metrica"]);
$nombre_productos        = addslashes($_POST["nombre_productos"]);
$tope_minimo             = '1';
$cod_tipo                = intval($_POST["cod_tipo"]);
$cod_paises              = '1';
$cod_proveedores         = '1';
$precio_compra           = addslashes($_POST["precio_compra"]);
$precio_venta            = addslashes($_POST["precio_venta"]);
$precio_venta2           = addslashes($_POST["precio_venta2"]);
$precio_venta3           = addslashes($_POST["precio_venta3"]);
$precio_venta4           = addslashes($_POST["precio_venta4"]);
$iva                     = intval($_POST["iva"]);
$precio_costo            = $precio_compra - ($precio_compra - ($precio_compra/(($iva/100)+1)));
$vlr_total_compra        = $precio_compra;
$vlr_total_venta         = $precio_venta;
$cod_lineas              = intval($_POST["cod_lineas"]);
$unidades_total          = $unidades * $cajas;
$unidades_faltantes      = $unidades * $cajas;
$und_orig                = $unidades_total;
$dto1                    = '0';
$dto2                    = '0';
$cuenta                  = $cuenta_actual;
$fechas_dia_invert       = date("Y/m/d");
$fechas_dia              = strtotime($fechas_dia_invert);
$fechas_mes              = date("m/Y");
$fechas_anyo             = date("d/m/Y");
$fechas_hora             = date("H:i:s");
$ip                      = $_SERVER['REMOTE_ADDR'];
$cod_dependencia         =  intval($_POST["cod_dependencia"]);
$fechas_vencimiento      =  addslashes($_POST["fechas_vencimiento"]);
$dato_fecha              = explode('/', $fechas_vencimiento);
$dia                     = $dato_fecha[0];
$mes                     = $dato_fecha[1];
$anyo                    = $dato_fecha[2];
$fechas_vencimiento_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg  = strtotime($fechas_vencimiento_Y_m_d);

$verificar_cod_productos = mysql_query("SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'");
$existencia_cod_productos = mysql_num_rows($verificar_cod_productos);

if ($existencia_cod_productos > 0) {
echo "<center><font color='yellow' size='+6'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> El codigo: <strong> ".$cod_productos_var. " </strong>ya existe. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></font><center>";
echo"<br><td><a href='../admin/productos.php'><center><font color='yellow' size='+3'>REGRESAR</font></center></a></td>";
echo "<br><center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed></center>";
}
// Hay campos en blanco
elseif($cod_productos_var==NULL || $nombre_productos==NULL || $unidades==NULL) {
echo "<font color='yellow'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> Ha dejado campos vacios. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed></center>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {

$agregar_registros_sql1 = "INSERT INTO productos (cod_productos_var, cod_marcas, cod_proveedores, cod_nomenclatura,   
nombre_productos, unidades, cajas, tope_minimo, unidades_faltantes, unidades_total, und_orig, detalles, precio_costo, 
precio_compra, precio_venta, precio_venta2, precio_venta3, precio_venta4, vlr_total_compra, vlr_total_venta, cod_tipo, cod_lineas, 
cod_paises, vendedor, cuenta, dto1, dto2, iva, iva_v, ip, fechas_vencimiento, 
fechas_vencimiento_seg, fechas_anyo, fechas_dia, fechas_mes, fechas_hora, cod_dependencia) 
VALUES ('$cod_productos_var', '$cod_marcas', '$cod_proveedores', '$cod_nomenclatura', UPPER('$nombre_productos'), 
'$unidades', '$cajas', '$tope_minimo', '$unidades_faltantes', '$unidades_total', '$und_orig', '$detalles', '$precio_costo', 
'$precio_compra', '$precio_venta', '$precio_venta2', '$precio_venta3', '$precio_venta4', '$vlr_total_compra', '$vlr_total_venta', '$cod_tipo', '$cod_lineas', 
'$cod_paises', '$cuenta_actual', '$cuenta', '$dto1', '$dto2', '$iva', '$iva_v', '$ip','$fechas_vencimiento', 
'$fechas_vencimiento_seg', '$fechas_anyo', '$fechas_dia', '$fechas_mes', '$fechas_hora', '$cod_dependencia')";
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="3; <?php echo $pagina?>">
<?php
echo "<br><center><font color='yellow' size='15px'> Se ha ingresado correctamente el producto <strong>".$nombre_productos.".</strong></font><center>";
}
      }
   }
?>