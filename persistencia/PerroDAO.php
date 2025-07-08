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

    public function editarFoto() {
        return "UPDATE perro 
                SET foto = '" . $this->foto . "' 
                WHERE idPerro = " . $this->id;
    }

    public function editarObservaciones() {
        return "UPDATE perro 
                SET observaciones = '" . $this->observaciones . "' 
                WHERE idPerro = " . $this->id;
    }
    
    public function perritosPorPaseo($fecha, $hora_inicio, $paseador) {
        return "SELECT e.nombre, e.foto, r.idRaza, r.nombre, o.idPropietario, o.nombre, o.apellido
            FROM paseo p
            JOIN perro e ON p.Perro_idPerro = e.idPerro
            JOIN propietario o ON e.Propietario_idPropietario = o.idPropietario
            JOIN raza r ON e.Raza_idRaza = r.idRaza
            WHERE p.fecha = '$fecha'
              AND p.hora_inicio = '$hora_inicio'
              AND p.Paseador_idPaseador = '$paseador'";
    }
    
    public function perritosPorPaseoCancelado($fecha, $hora_inicio, $paseador,$estado) {
        return "SELECT e.nombre, e.foto, r.idRaza, r.nombre, o.idPropietario, o.nombre, o.apellido
            FROM paseo p
            JOIN perro e ON p.Perro_idPerro = e.idPerro
            JOIN propietario o ON e.Propietario_idPropietario = o.idPropietario
            JOIN raza r ON e.Raza_idRaza = r.idRaza
            JOIN estado s on p.Estado_idEstado = s.idEstado
            WHERE p.fecha = '$fecha'
              AND p.hora_inicio = '$hora_inicio'
              AND p.Paseador_idPaseador = '$paseador'
              AND p.Estado_idEstado != '$estado'";
    }
}
