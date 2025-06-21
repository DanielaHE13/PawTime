<?php
abstract class Persona {
    protected $id;
    protected $nombre;
    protected $apellido;
    protected $telefono;
    protected $correo;
    protected $clave;
    
    public function __construct($id = "", $nombre="", $apellido="", $telefono="", $correo="", $clave="") {
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> telefono = $telefono;
        $this -> correo = $correo;
        $this -> clave = $clave;
    }
    
    public function getId(){
        return $this -> id;
    }
    
    public function getNombre(){
        return $this -> nombre;
    }
    
    public function getApellido(){
        return $this -> apellido;
    }
    
    public function getTelefono(){
        return $this -> telefono;
    }
    
    public function getCorreo(){
        return $this -> correo;
    }
    
    public function getClave(){
        return $this -> clave;
    }
}
?>