<?php
class PaseadorDAO {
    private $id;
    private $nombre;
    private $apellido;
    private $telefono;
    private $correo;
    private $clave;
    private $foto;
    private $tarifa;
    private $estado;
    
    public function __construct($id = 0, $nombre = "", $apellido = "", $telefono="", $correo = "", $clave = "", $foto="", $tarifa="", $estado="") {
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> telefono = $telefono;
        $this -> correo = $correo;
        $this -> clave = $clave;
        $this -> foto = $foto;
        $this -> tarifa = $tarifa;
        $this -> estado = $estado;
    }
    
    public function autenticar() {
        return "SELECT idPaseador
                FROM Paseador
                WHERE correo = '" . $this -> correo . "' AND clave = '" . md5($this -> clave) . "'";
    }
    
}