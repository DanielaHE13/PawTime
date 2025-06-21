<?php
class PropietarioDAO {
    private $id;
    private $nombre;
    private $apellido;
    private $telefono;
    private $correo;
    private $clave;
    private $direccion;
    
    public function __construct($id = 0, $nombre = "", $apellido = "", $telefono="", $correo = "", $clave = "", $direccion="") {
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> telefono = $telefono;
        $this -> correo = $correo;
        $this -> clave = $clave;
        $this -> direccion = $direccion;
    }
    
    public function autenticar() {
        return "SELECT idPropietario
                FROM Propietario
                WHERE correo = '" . $this -> correo . "' AND clave = '" . md5($this -> clave) . "'";
    }
    
    public function consultar() {
        return "SELECT nombre, apellido, correo
                FROM Propietario
                WHERE idPropietario = " . $this->id;
    }
}