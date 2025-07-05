<?php
require_once("persistencia/Conexion.php");
require_once("logica/Persona.php");
require_once("persistencia/PropietarioDAO.php");

class Propietario extends Persona
{
    private $foto;
    private $direccion;

    public function __construct($id = "", $nombre = "", $apellido = "", $telefono = "", $correo = "", $clave = "", $direccion = "", $foto = "")
    {
        parent::__construct($id, $nombre, $apellido, $telefono, $correo, $clave);
        $this->direccion = $direccion;
        $this->foto = $foto;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function autenticar()
    {
        $conexion = new Conexion();
        $propietarioDAO = new PropietarioDAO("", "", "", "", $this->correo, $this->clave, "");
        $conexion->abrir();
        $conexion->ejecutar($propietarioDAO->autenticar());

        if ($conexion->filas() == 1) {
            $this->id = $conexion->registro()[0];
            $conexion->cerrar();
            return true;
        } else {
            $conexion->cerrar();
            return false;
        }
    }

    public function consultar()
    {
        $conexion = new Conexion();
        $propietarioDAO = new PropietarioDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($propietarioDAO->consultar());
        $datos = $conexion->registro();
        
        $this->nombre = $datos[0];
        $this->apellido = $datos[1];
        $this->telefono = $datos[2];
        $this->correo = $datos[3];
        $this->foto = $datos[4];
        $this->direccion = $datos[5];
        
        $conexion->cerrar();
    }
    
    public function consultarTodos(){
        $conexion = new Conexion();
        $propietarioDAO = new PropietarioDAO();
        $conexion->abrir();
        $conexion->ejecutar($propietarioDAO->consultarTodos());
        $propietarios = array();
        while(($datos = $conexion -> registro()) != null){
            $propietario = new Propietario($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6]);
            array_push($propietarios, $propietario);
        }
        $conexion->cerrar();
        return $propietarios;
    }
    
    public function buscar($filtro){
        $conexion = new Conexion();
        $propietarioDAO = new PropietarioDAO();
        $conexion -> abrir();
        $conexion -> ejecutar($propietarioDAO -> buscar($filtro));
        $propietarios = array();
        while (($datos = $conexion->registro()) != null) {
            $propietario = new Propietario($datos[0], $datos[1], $datos[2], "", $datos[3]);
            array_push($propietarios, $propietario);
        }
        $conexion->cerrar();
        return $propietarios;
    }

    public function editarPerfil(){
        $conexion = new Conexion();
        $propietarioDAO = new PropietarioDAO(
            $this->id,
            $this->nombre,
            $this->apellido,
            $this->telefono,
            $this->correo,
            "", // clave no se actualiza aquí
            $this->direccion,
            $this->foto
        );
        $conexion->abrir();
        $conexion->ejecutar($propietarioDAO->editarPerfil());
        $conexion->cerrar();
    }

    public function editarFoto(){
        $conexion = new Conexion();
        $conexion -> abrir();
        $propietarioDAO = new PropietarioDAO(
            $this->id,
            $this->nombre,
            $this->apellido,
            $this->telefono,
            $this->correo,
            "", // clave no se actualiza aquí
            $this->direccion,
            $this->foto
        );        
        $conexion -> ejecutar($propietarioDAO -> editarFoto());
        $conexion -> cerrar();
    }

    public function registrar(){
    $conexion = new Conexion();
    $propietarioDAO = new PropietarioDAO($this->id, $this->nombre, $this->apellido, $this->telefono, $this->correo, $this->clave, $this->direccion);
    $conexion->abrir();
    $conexion->ejecutar($propietarioDAO->registrar());
    $conexion->cerrar();
    }
    
    public function modificar(){
        $conexion = new Conexion();
        $propietarioDAO = new PropietarioDAO($this->id, $this->nombre, $this->apellido, $this->telefono, $this->correo, $this->clave, $this->direccion);
        $conexion->abrir();
        $conexion->ejecutar($propietarioDAO->modificar());
        $conexion->cerrar();
    }


}
