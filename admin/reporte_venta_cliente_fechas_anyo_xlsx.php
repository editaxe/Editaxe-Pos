<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar);
$dat = mysql_fetch_assoc($consultar_informacion);

$icono_emp                      = $dat['icono'];
$cabecera_emp                   = $dat['cabecera'];
$localidad_emp                  = $dat['localidad'];
$res_emp                        = $dat['res'];
$res1_emp                       = $dat['res1'];
$res2_emp                       = $dat['res2'];
$direccion_emp                  = $dat['direccion'];
$telefono_emp                   = $dat['telefono'];
$nit_emp                        = $dat['nit'];
      
$desarrollador_emp              = $dat['desarrollador'];
$correo_desarrolladoremp        = $dat['correo_desarrollador'];
$tel_desarrollador_emp          = $dat['tel_desarrollador'];
$pag_desarrollador_emp          = $dat['pag_desarrollador'];
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("America/Bogota");

if (PHP_SAPI == 'cli')
	die('Este ejemplo solo debe ejecutarse desde un navegador web');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';

$fecha              = intval($_GET['fecha']);
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()
->setCreator($cabecera_emp)
->setLastModifiedBy($cabecera_emp)
->setTitle("REPORTE VENTAS POR CLIENTES - ".$cabecera_emp)
->setSubject("REPORTE VENTAS POR CLIENTES - ".$cabecera_emp)
->setDescription($cabecera_emp)
->setKeywords($cabecera_emp)
->setCategory($cabecera_emp);
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NIT')
            ->setCellValue('B1', 'NOMBRE CLIENTE')
            ->setCellValue('C1', 'TOTAL VENTA')
            ->setCellValue('D1', 'TOTAL IVA');

$mostrar_datos_sql = "SELECT Sum(ventas.vlr_total_venta-(ventas.vlr_total_venta*(ventas.descuento_ptj/100))) As total_venta_contado, 
Sum(((ventas.vlr_total_venta/((ventas.iva/100)+(100/100)))*(ventas.iva/100))) As sum_iva, 
clientes.nombres, clientes.apellidos, clientes.cedula, ventas.cod_clientes 
FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes
WHERE (ventas.anyo = '$fecha') GROUP BY ventas.cod_clientes ORDER BY clientes.nombres ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$increment = 1;
while ($datos = mysql_fetch_assoc($consulta)) {

$cedula                 = $datos['cedula'];
$nombres                = $datos['nombres'];
$apellidos              = $datos['apellidos'];
$total_venta_contado    = $datos['total_venta_contado'];
$sum_iva                = $datos['sum_iva'];
$increment ++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$increment, $cedula)
            ->setCellValue('B'.$increment, $nombres.' '.$apellidos)
            ->setCellValue('C'.$increment, round($total_venta_contado, 0))
            ->setCellValue('D'.$increment, round($sum_iva, 0));
}
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$nombre_archivo = 'reporte_ventas_clientes_año_'.$fecha;
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nombre_archivo.'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
