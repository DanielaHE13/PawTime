<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PerroDAO.php");

class Perro
{
    private $id;
    private $nombre;
    private $observaciones;
    private $foto;
    private $idRaza;
    private $idPropietario;
    private $raza; // Agregar propiedad para objeto Raza

    public function __construct($id = "", $nombre = "", $observaciones = "", $foto = "", $idRaza = "", $idPropietario = "")
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->observaciones = $observaciones;
        $this->foto = $foto;
        $this->idRaza = $idRaza;
        $this->idPropietario = $idPropietario;
    }

    public function registrar()
    {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO($this->id, $this->nombre, $this->observaciones, $this->foto, $this->idRaza, $this->idPropietario);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->registrar());
        $conexion->cerrar();
    }

    public function consultar()
    {
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

    public function editarFoto()
    {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO($this->id, $this->nombre, $this->observaciones, $this->foto, $this->idRaza, $this->idPropietario);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->editarFoto());
        $conexion->cerrar();
    }

    public function editarObservaciones()
    {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO($this->id, $this->nombre, $this->observaciones, $this->foto, $this->idRaza, $this->idPropietario);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->editarObservaciones());
        $conexion->cerrar();
    }

    public function eliminar()
    {
        $conexion = new Conexion();
        
        // Primero eliminar todos los paseos relacionados con este perro
        require_once("persistencia/PaseoDAO.php");
        $paseoDAO = new PaseoDAO("", "", "", "", "", "", $this->id, "");
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->eliminarPorPerro());
        
        // Luego eliminar el perro
        $perroDAO = new PerroDAO($this->id);
        $conexion->ejecutar($perroDAO->eliminar());
        $conexion->cerrar();
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

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getObservaciones()
    {
        return $this->observaciones;
    }
    public function getFoto()
    {
        return $this->foto;
    }
    public function getIdRaza()
    {
        return $this->idRaza;
    }
    public function getIdPropietario()
    {
        return $this->idPropietario;
    }
    public function getRaza() {
        if ($this->raza == null) {
            $this->raza = new Raza($this->idRaza);
            $this->raza->consultar();
        }
        return $this->raza;
    }

    // Setters
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
    public function setIdRaza($idRaza)
    {
        $this->idRaza = $idRaza;
    }
    public function setIdPropietario($idPropietario)
    {
        $this->idPropietario = $idPropietario;
    }
    public function setRaza($raza) {
        $this->raza = $raza;
    }
    
    public function perritosPorPaseo($fecha, $hora_inicio, $paseador) {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO();
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->perritosPorPaseo($fecha, $hora_inicio, $paseador));        
        $perritos = array();
        while(($datos = $conexion->registro()) != null){
            $raza = new Raza($datos[2], $datos[3]);
            $propietario = new Propietario($datos[4], $datos[5], $datos[6]);
            $perrito = new Perro("", $datos[0], "", $datos[1], $raza, $propietario);
            array_push($perritos, $perrito);
        }
        $conexion->cerrar();
        return $perritos;
    }
    
    public function perritosPorPaseoCancelado($fecha, $hora_inicio, $paseador,$estado) {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO();
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->perritosPorPaseoCancelado($fecha, $hora_inicio, $paseador, $estado));
        $perritos = array();
        while(($datos = $conexion->registro()) != null){
            $raza = new Raza($datos[2], $datos[3]);
            $propietario = new Propietario($datos[4], $datos[5], $datos[6]);
            $perrito = new Perro("", $datos[0], "", $datos[1], $raza, $propietario);
            array_push($perritos, $perrito);
        }
        $conexion->cerrar();
        return $perritos;
    }
    
}
