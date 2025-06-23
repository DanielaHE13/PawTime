<?php
class RazaDAO {
    private $id;
    private $nombre;

    public function __construct($id = "", $nombre = "") {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function consultar() {
        return "SELECT nombre FROM raza WHERE idRaza = " . $this->id;
    }

    public function listar() {
        return "SELECT idRaza, nombre FROM raza ORDER BY nombre ASC";
    }
}
