<?php
class AdministradorDAO{
    private $id;
    private $nombre;
    private $apellido;
    private $telefono;
    private $correo;
    private $clave;
    
    public function __construct($id = 0, $nombre = "", $apellido = "", $telefono="", $correo = "", $clave = ""){
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> telefono = $telefono;
        $this -> correo = $correo;
        $this -> clave = $clave;
    }
    
    public function autenticar(){
        return "select idAdministrador
                from Administrador
                where correo = '" . $this -> correo . "' and clave = '" . md5($this -> clave) . "'";
    }
    
    public function consultar(){
        return "select nombre, apellido, correo
                from Administrador
                where idAdmin = '" . $this -> id . "'";
    }
}