<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseoDAO.php");

class Paseo{
    private $id;
    private $fecha;
    private $hora_inicio;
    private $hora_fin;
    private $precio_total;
    private $Paseador_idPaseador;
    private $Perro_idPerro;
    private $Estado_idEstado;
       
    public function getId()
    {
        return $this->id;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getHora_inicio()
    {
        return $this->hora_inicio;
    }

    public function getHora_fin()
    {
        return $this->hora_fin;
    }

    public function getPrecio_total()
    {
        return $this->precio_total;
    }

    public function getPaseador_idPaseador()
    {
        return $this->Paseador_idPaseador;
    }

    public function getPerro_idPerro()
    {
        return $this->Perro_idPerro;
    }

    public function getEstado_idEstado()
    {
        return $this->Estado_idEstado;
    }

    public function __construct($id = "", $fecha = "", $hora_inicio = "", $hora_fin = "", $precio_total = "", $Paseador_idPaseador = "", $Perro_idPerro ="", $Estado_idEstado =""){
        $this->id = $id;
        $this->fecha = $fecha;
        $this->hora_inicio = $hora_inicio;
        $this->hora_fin = $hora_fin;
        $this->precio_total = $precio_total;
        $this->Paseador_idPaseador = $Paseador_idPaseador;
        $this->Perro_idPerro = $Perro_idPerro;
        $this->Estado_idEstado = $Estado_idEstado;
    }
    
    
    
    public function registrar()
    {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO($this->id, $this->nombre, $this->observaciones, $this->foto, $this->idRaza, $this->idPropietario);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->registrar());
        $conexion->cerrar();
    }
    
    public function consultar(){
        $conexion = new Conexion();
        $perroDAO = new PerroDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->consultar());
        $datos = $conexion->registro();
        
        $this->nombre = $datos[0];
        $this->observaciones = $datos[1];
        $this->foto = $datos[2];
        $this->idRaza = $datos[3];
        $this->idPropietario = $datos[4];
        
        $conexion->cerrar();
    }
    
    public function consultarTodos(){
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarTodos());
        $paseos = array();
        while(($datos = $conexion -> registro()) != null){
            $paseador = new Paseador($datos[5],$datos[6]);
            $perro = new Perro($datos[7],$datos[8]);
            $estado =  new Estado($datos[9],$datos[10]);
            $paseo = new Paseo($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $paseador, $perro, $estado);
            array_push($paseos, $paseo);
        }
        $conexion->cerrar();
        return $paseos;
    }
    
    public function buscar($filtro){
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion -> abrir();
        $conexion -> ejecutar($paseoDAO -> buscar($filtro));
        $paseos = array();
        while (($datos = $conexion->registro()) != null) {
            $paseo = new Paseo($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6], $datos[7]);
            array_push($paseos, $paseo);
        }
        $conexion->cerrar();
        return $paseos;
    }
    
    public static function listarPorPropietario($idPropietario)
    {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO("", "", "", "", "", $idPropietario);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->listarPorPropietario());
        
        $perros = [];
        while (($registro = $conexion->registro()) != null) {
            $perros[] = new Perro($registro[0], $registro[1], $registro[2], $registro[3], $registro[4], $registro[5]);
        }
        
        $conexion->cerrar();
        return $perros;
    }
    public function getRazaNombre()
    {
        $raza = new Raza($this->getIdRaza());
        $raza->consultar();
        return $raza->getNombre();
    }
}
