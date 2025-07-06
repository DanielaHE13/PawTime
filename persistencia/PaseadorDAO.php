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
    
    public function consultarTodos()
    {
        return "SELECT idPaseador, nombre, apellido, telefono, correo, clave, foto, tarifa, estado
                FROM Paseador";
    }
    
    public function consultar(){
        return "SELECT nombre, apellido, telefono, correo, foto, tarifa, estado
                FROM Paseador
                WHERE idPaseador = " . $this->id;
    }
    
    public function editarPerfil(){
        return "UPDATE Paseador SET
                    nombre = '" . $this->nombre . "',
                    apellido = '" . $this->apellido . "',
                    correo = '" . $this->correo . "',
                    telefono = '" . $this->telefono . "',
                    tarifa = '" . $this->tarifa . "',
                    estado = '" . $this->estado . "'
                WHERE idPaseador = " . $this->id;
    }
    
    public function buscar($filtro){
        $consulta = "select p.idPaseador, p.nombre, p.apellido, p.correo, p.tarifa, p.estado
                from Paseador p
                where " ;
        foreach ($filtro as $text){
            $text = trim($text);
            $consulta .= "(p.nombre like '%" . $text . "%' or p.apellido like '%" .  $text . "%' or p.idPaseador like '%" .  $text . "%' or p.correo like '%" .  $text . "%' or p.tarifa like '%" .  $text . "%' or p.estado like '%" .  $text . "%') and ";
        }
        $consulta = substr($consulta, 0, strlen($consulta)-4);
        return $consulta;
    }
    
    public function registrar(){
        return "INSERT INTO paseador (idPaseador, nombre, apellido, telefono, correo, clave, foto, tarifa, estado)
            VALUES (
                '" . $this->id . "',
                '" . $this->nombre . "',
                '" . $this->apellido . "',
                '" . $this->telefono . "',
                '" . $this->correo . "',
                MD5('" . $this->clave . "'),
                '" . $this->foto . "',
                " . ($this->tarifa !== "" ? $this->tarifa : 0) . ",
                " . ($this->estado !== "" ? $this->estado : 1) . "
            )";
    }
    
    public function editarFoto(){
        return "UPDATE paseador SET foto = '" . $this->foto . "' WHERE idPaseador = " . $this->id;
    }
}