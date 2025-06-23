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

    public function consultar()
    {
        return "SELECT nombre, apellido, telefono, correo, foto, direccion
                FROM Propietario
                WHERE idPropietario = " . $this->id;
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
    public function registrar()
{
    return "INSERT INTO propietario (nombre, apellido, telefono, correo, clave)
            VALUES (
                '" . $this->nombre . "',
                '" . $this->apellido . "',
                '" . $this->telefono . "',
                '" . $this->correo . "',
                '" . $this->clave . "'
            )";
}


}
