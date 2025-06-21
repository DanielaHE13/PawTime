<?php
require_once("persistencia/Conexion.php");
require_once("logica/Persona.php");
require_once("persistencia/AdministradorDAO.php");

class Administrador extends Persona {
    
    public function __construct($id = "", $nombre = "", $apellido = "", $telefono="", $correo = "", $clave = ""){
        parent::__construct($id, $nombre, $apellido, $telefono, $correo, $clave);
    }
    
    public function autenticar(){
        $conexion = new Conexion();
        $administradorDAO = new AdministradorDAO("","","","", $this -> correo, $this -> clave);
        $conexion -> abrir();
        $conexion -> ejecutar($administradorDAO -> autenticar());
        if($conexion -> filas() == 1){
            $this -> id = $conexion -> registro()[0];
            $conexion->cerrar();
            return true;
        }else{
            $conexion->cerrar();
            return false;
        }
    }
    
    public function consultar(){
        $conexion = new Conexion();
        $administradorDAO = new AdministradorDAO($this -> id);
        $conexion -> abrir();
        $conexion -> ejecutar($administradorDAO -> consultar());
        $datos = $conexion -> registro();
        $this -> nombre = $datos[0];
        $this -> apellido = $datos[1];
        $this -> correo = $datos[2];
        $conexion->cerrar();
    }
}