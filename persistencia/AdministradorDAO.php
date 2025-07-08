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
        return "select nombre, apellido, correo, telefono
                from Administrador
                where idAdministrador = '" . $this -> id . "'";
    }
    
    public function editarPerfil(){
        $sql = "UPDATE Administrador SET 
                    nombre = '" . $this->nombre . "',
                    apellido = '" . $this->apellido . "',
                    correo = '" . $this->correo . "',
                    telefono = '" . $this->telefono . "'";
        
        // Solo actualizar la clave si se proporciona
        if (!empty($this->clave)) {
            $sql .= ", clave = '" . md5($this->clave) . "'";
        }
        
        $sql .= " WHERE idAdministrador = " . $this->id;
        
        return $sql;
    }
}