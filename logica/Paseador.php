<?php
require_once("persistencia/Conexion.php");
require_once("logica/Persona.php");
require_once("persistencia/PaseadorDAO.php");

class Paseador extends Persona {
    private $foto;
    private $tarifa;
    private $estado;
    
    public function getFoto()
    {
        return $this->foto;
    }

    public function getTarifa()
    {
        return $this->tarifa;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function __construct($id="", $nombre="", $apellido="", $telefono="", $correo="", $clave="", $foto="", $tarifa="", $estado="") {
        parent::__construct($id, $nombre, $apellido, $telefono, $correo, $clave);
    }
    
    
    public function autenticar(){
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO("","","","",$this -> correo, $this -> clave);
        $conexion -> abrir();
        $conexion -> ejecutar($paseadorDAO -> autenticar());
        if($conexion -> filas() == 1){
            $this -> id = $conexion -> registro()[0];
            $conexion->cerrar();
            return true;
        }else{
            $conexion->cerrar();
            return false;
        }
    }
}