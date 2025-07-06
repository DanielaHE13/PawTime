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
        $this ->foto = $foto;
        $this ->estado = $estado;
        $this ->tarifa = $tarifa;
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
    
    public function consultar(){
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultar());
        $datos = $conexion->registro();
        
        $this->nombre = $datos[0];
        $this->apellido = $datos[1];
        $this->telefono = $datos[2];
        $this->correo = $datos[3];
        $this->foto = $datos[4];
        $this->tarifa = $datos[5];
        $this->estado = $datos[6];
        
        $conexion->cerrar();
    }
    
    public function consultarTodos(){
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->consultarTodos());
        $paseadores = array();
        while(($datos = $conexion -> registro()) != null){
            $paseador = new Paseador($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6], $datos[7], $datos[8]);
            array_push($paseadores, $paseador);
        }
        $conexion->cerrar();
        return $paseadores;
    }
      
    public function editarPerfil(){
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO(
            $this->id,
            $this->nombre,
            $this->apellido,
            $this->telefono,
            $this->correo,
            "", // clave no se actualiza aquí
            $this->tarifa,
            $this->estado
            );
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->editarPerfil());
        $conexion->cerrar();
    }
    
    public function buscar($filtro){
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO();
        $conexion -> abrir();
        $conexion -> ejecutar($paseadorDAO -> buscar($filtro));
        $paseadores = array();
        while (($datos = $conexion->registro()) != null) {
            $paseador = new Paseador($datos[0], $datos[1], $datos[2], "", $datos[3], $datos[4], $datos[5]);
            array_push($paseadores, $paseador);
        }
        $conexion->cerrar();
        return $paseadores;
    }
    
    public function registrar(){
        $conexion = new Conexion();
        $paseadorDAO = new PaseadorDAO($this->id, $this->nombre, $this->apellido, $this->telefono, $this->correo, $this->clave, $this->foto, $this->tarifa, $this->estado);
        $conexion->abrir();
        $conexion->ejecutar($paseadorDAO->registrar());
        $conexion->cerrar();
    }
    
    public function setTarifa($tarifa) {
        $this->tarifa = $tarifa;
    }
    
    public function setFoto($foto) {
        $this->foto = $foto;
    }
}