<?php
class PropietarioDAO
{
    private $id;
    private $nombre;
    private $apellido;
    private $telefono;
    private $correo;
    private $clave;
    private $direccion;
    private $foto;

    public function __construct($id = 0, $nombre = "", $apellido = "", $telefono = "", $correo = "", $clave = "", $direccion = "", $foto = "")
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->clave = $clave;
        $this->direccion = $direccion;
        $this->foto = $foto;
    }

    public function autenticar()
    {
        return "SELECT idPropietario
                FROM Propietario
                WHERE correo = '" . $this->correo . "' AND clave = '" . md5($this->clave) . "'";
    }
    
    public function consultarTodos()
    {
        return "SELECT idPropietario, nombre, apellido, telefono, correo, foto, direccion
                FROM Propietario";
    }
    
    public function consultar()
    {
        return "SELECT nombre, apellido, telefono, correo, foto, direccion
                FROM Propietario
                WHERE idPropietario = " . $this->id;
    }
    
    public function buscar($filtro){
        $consulta = "select p.idPropietario, p.nombre, p.apellido, p.correo
                from Propietario p
                where " ;
        foreach ($filtro as $text){
            $text = trim($text);
            $consulta .= "(p.nombre like '%" . $text . "%' or p.apellido like '%" .  $text . "%' or p.idPropietario like '%" .  $text . "%' or p.correo like '%" .  $text . "%') and ";
        }
        $consulta = substr($consulta, 0, strlen($consulta)-4);
        return $consulta;
    }

    public function editarPerfil()
    {
        return "UPDATE Propietario SET 
                    nombre = '" . $this->nombre . "',
                    apellido = '" . $this->apellido . "',
                    correo = '" . $this->correo . "',
                    telefono = '" . $this->telefono . "',
                    direccion = '" . $this->direccion . "',
                    foto = '" . $this->foto . "'
                WHERE idPropietario = " . $this->id;
    }
     public function editarFoto(){
        return "update Propietario
                set foto = '" . $this -> foto . "'
                where idPropietario= '" . $this -> id . "'";
    }
    public function registrar(){
    return "INSERT INTO propietario (idPropietario, nombre, apellido, telefono, correo, clave, direccion)
            VALUES (
                '" . $this->id . "',
                '" . $this->nombre . "',
                '" . $this->apellido . "',
                '" . $this->telefono . "',
                '" . $this->correo . "',
                MD5('" . $this->clave . "'),
                '" . $this->direccion . "'
            )";
    }


}
