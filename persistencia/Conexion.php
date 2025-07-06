<?php
class Conexion {
    private $conexion;
    private $resultado;
    
    public function abrir() {
        $this->conexion = new mysqli("localhost", "root", "", "pawtime", 3307);
        if ($this->conexion->connect_error) {
            throw new Exception("Error de conexión: " . $this->conexion->connect_error);
        }
    }
    
    public function cerrar() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
    
    public function ejecutar($sentencia) {
        $this->resultado = $this->conexion->query($sentencia);
        if (!$this->resultado) {
            throw new Exception("Error en la consulta: " . $this->conexion->error . " - SQL: " . $sentencia);
        }
    }
    
    public function registro(){
        return $this -> resultado -> fetch_row();
    }
    
    public function filas(){
        return $this -> resultado -> num_rows;
    }
}