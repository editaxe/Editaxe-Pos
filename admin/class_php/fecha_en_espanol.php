<?php
function fecha_en_espanol ($fecha) {

$fecha = $fecha;
$numeroDia = date('d', $fecha);
$dia = date('l', $fecha);
$mes = date('F', $fecha);
$anio = date('Y', $fecha);
$hora = date('H:i', $fecha);

$dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
$dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
$nombredia = str_replace($dias_EN, $dias_ES, $dia);
$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$nombreMes = str_replace($meses_EN, $meses_ES, $mes);

return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
}
?>