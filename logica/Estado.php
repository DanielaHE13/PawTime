<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/EstadoDAO.php");

class Estado{
    private $id;
    private $nombre;
    
    
    public function getId(){
        return $this->id;
    }
    
    public function getNombre(){
        return $this->nombre;
    }
    
    public function __construct($id = "", $nombre = ""){
        $this->id = $id;
        $this->nombre = $nombre;
    }
    
    
}