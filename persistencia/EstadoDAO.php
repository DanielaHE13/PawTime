<?php
class EstadoDAO {
    private $id;
    private $nombre;
    
    public function __construct($id = "", $nombre = "") {
        $this->id = $id;
        $this->nombre = $nombre;
    }
    
    public function consultar() {
        return "SELECT nombre FROM estado WHERE idEstado = " . $this->id;
    }
    
    public function consultarTodos() {
        return "SELECT idEstado, nombre FROM estado ORDER BY nombre";
    }
}
