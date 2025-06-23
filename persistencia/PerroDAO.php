<?php
class PerroDAO {
    private $id;
    private $nombre;
    private $observaciones;
    private $foto;
    private $idRaza;
    private $idPropietario;

    public function __construct($id = "", $nombre = "", $observaciones = "", $foto = "", $idRaza = "", $idPropietario = "") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->observaciones = $observaciones;
        $this->foto = $foto;
        $this->idRaza = $idRaza;
        $this->idPropietario = $idPropietario;
    }

    public function registrar() {
        return "INSERT INTO perro (nombre, observaciones, foto, Raza_idRaza, Propietario_idPropietario)
                VALUES (
                    '" . $this->nombre . "',
                    '" . $this->observaciones . "',
                    '" . $this->foto . "',
                    " . $this->idRaza . ",
                    " . $this->idPropietario . "
                )";
    }

    public function consultar() {
        return "SELECT nombre, observaciones, foto, Raza_idRaza, Propietario_idPropietario 
                FROM perro 
                WHERE idPerro = " . $this->id;
    }

    public function listarPorPropietario() {
        return "SELECT idPerro, nombre, observaciones, foto, Raza_idRaza, Propietario_idPropietario 
                FROM perro 
                WHERE Propietario_idPropietario = " . $this->idPropietario;
    }
    public function eliminar() {
    return "DELETE FROM perro WHERE idPerro = " . $this->id;
}



}
