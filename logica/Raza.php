<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/RazaDAO.php");

class Raza {
    private $id;
    private $nombre;

    public function __construct($id = "", $nombre = "") {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function consultar() {
        $conexion = new Conexion();
        $razaDAO = new RazaDAO($this->id);
        $conexion->abrir();
        $conexion->ejecutar($razaDAO->consultar());
        $datos = $conexion->registro();

        $this->nombre = $datos[0];

        $conexion->cerrar();
    }

    public static function listar() {
        $conexion = new Conexion();
        $razaDAO = new RazaDAO();
        $conexion->abrir();
        $conexion->ejecutar($razaDAO->listar());

        $razas = [];
        while ($registro = $conexion->registro()) {
            $razas[] = ["id" => $registro[0], "nombre" => $registro[1]];
        }

        $conexion->cerrar();
        return $razas;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
}
