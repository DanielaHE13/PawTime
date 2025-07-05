<?php
class PaseoDAO{
    private $id;
    private $fecha;
    private $hora_inicio;
    private $hora_fin;
    private $precio_total;
    private $Paseador_idPaseador;
    private $Perro_idPerro;
    private $Estado_idEstado;
    
    public function __construct($id = "", $fecha = "", $hora_inicio = "", $hora_fin = "", $precio_total = "", $Paseador_idPaseador = "", $Perro_idPerro ="", $Estado_idEstado =""){
        $this->id = $id;
        $this->fecha = $fecha;
        $this->hora_inicio = $hora_inicio;
        $this->hora_fin = $hora_fin;
        $this->precio_total = $precio_total;
        $this->Paseador_idPaseador = $Paseador_idPaseador;
        $this->Perro_idPerro = $Perro_idPerro;
        $this->Estado_idEstado = $Estado_idEstado;
    }
    
    public function consultarTodos(){
        return "select idPaseo, fecha, hora_inicio, hora_fin, precio_total, Paseador_idPaseador, a.nombre, Perro_idPerro, e.nombre,	Estado_idEstado, s.nombre	
                from paseo p join paseador a on (p.Paseador_idPaseador = a.idPaseador)
                             join perro e on (p.Perro_idPerro = e.idPerro)
                             join estado s on (p.Estado_idEstado = s.idEstado)";
    }
    
    public function buscar($filtro){
        $consulta = "select p.idPaseo, p.fecha, p.hora_inicio, p.hora_fin, p.precio_total, a.nombre, e.nombre, s.nombre	
                from paseo p join paseador a on (p.Paseador_idPaseador = a.idPaseador)
                             join perro e on (p.Perro_idPerro = e.idPerro)
                             join estado s on (p.Estado_idEstado = s.idEstado)
                where " ;
        foreach ($filtro as $text){
            $text = trim($text);
            $consulta .= "(p.idPaseo like '%" . $text . "%' or  p.fecha like '%" .  $text . "%' or p.hora_inicio like '%" .  $text . "%' or p.hora_fin like '%" .  $text . "%' or precio_total like '%" .  $text . "%' or a.nombre like '%" .  $text . "%' or e.nombre like '%" .  $text . "%' or s.nombre like '%" .  $text . "%') and ";
        }
        $consulta = substr($consulta, 0, strlen($consulta)-4);
        return $consulta;
    }
}