<?php
require_once("persistencia/Conexion.php");
require_once("logica/Persona.php");
require_once("persistencia/PropietarioDAO.php");

class Propietario extends Persona {
    private $direccion;
    
    public function __construct($id="", $nombre="", $apellido="", $telefono="", $correo="", $clave="", $direccion="") {
        parent::__construct($id, $nombre, $apellido, $telefono, $correo, $clave);
    }
    
    public function getDireccion(){
        return $this -> direccion; 
    }
    
    public function autenticar(){
        $conexion = new Conexion();
        $propietarioDAO = new PropietarioDAO("","","","", $this -> correo, $this -> clave);
        $conexion -> abrir();
        $conexion -> ejecutar($propietarioDAO -> autenticar());
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
        $propietarioDAO = new PropietarioDAO($this -> id);
        $conexion -> abrir();
        $conexion -> ejecutar($propietarioDAO -> consultar());
        $datos = $conexion -> registro();
        $this -> nombre = $datos[0];
        $this -> apellido = $datos[1];
        $this -> correo = $datos[2];
        $conexion->cerrar();
    }
    
}