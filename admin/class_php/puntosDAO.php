<?php
//include_once 'conex.php';//INCLUIR CONEXION DE BASE DE DATOS
include_once('../conexiones/conex.php');

class puntosDao {
    private $r;
    public function __construct() {
        $this->r = array();
    }
    public function grabar($region, $latitud,$longitud) {
    //METODO PARA GRABAR A LA BD
        $con = conex::con();
        $region = mysqli_real_escape_string($con,$region);
        $latitud = mysqli_real_escape_string($con,$latitud);
        $longitud = mysqli_real_escape_string($con,$longitud);
        $q = "INSERT INTO sesiones (region, latitud, longitud)"."VALUES ('".addslashes($region)."','".addslashes($latitud)."','".addslashes($longitud)."')";
        $rpta = mysqli_query($con, $q);
        mysqli_close($con);
        if($rpta==1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function listar_todo() {
        $q = "SELECT * FROM sesiones";
        $con = conex::con();
        $rpta = mysqli_query($con, $q);
        mysqli_close($con);
        while($fila = mysqli_fetch_assoc($rpta)) {
            $this->r[] = $fila;
        }
        return $this->r;
    }
    public function borrar($cod_sesiones) {
    //METODO PARA BORRAR DE LA BD
        $con = conex::con();
        $cod_sesiones = mysqli_real_escape_string($con,$cod_sesiones);
        $q = "DELETE FROM sesiones WHERE cod_sesiones = ".(int)$cod_sesiones;
        $rpta = mysqli_query($con, $q);
        mysqli_close($con);
        if($rpta==1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function actualizar($Id, $region, $latitud,$longitud) {
    //METODO PARA ACTUALIZAR A LA BD
        $con = conex::con();
        $Id = mysqli_real_escape_string($con,$Id);
        $region = mysqli_real_escape_string($con,$region);
        $latitud = mysqli_real_escape_string($con,$latitud);
        $longitud = mysqli_real_escape_string($con,$longitud);
        $q = "update sesiones set region='".$region."', latitud='".$latitud."' , longitud ='".$longitud."' WHERE cod_sesiones =".$Id;
        $rpta = mysqli_query($con, $q);
        mysqli_close($con);
        if($rpta==1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function buscar($p) {
        $con = conex::con();
        //SEGURIDAD
        $p = mysqli_real_escape_string($con,$p);
        $q = "SELECT * FROM sesiones WHERE region LIKE '%".$p."%'";
        $rpta = mysqli_query($con, $q);
        mysqli_close($con);
        while($fila = mysqli_fetch_assoc($rpta)) {
            $this->r[] = $fila;
        }
        return $this->r;
    }
}
?>