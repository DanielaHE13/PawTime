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
    
    public function consultarTodos($rol, $idP) {
        $consulta = "SELECT
                    p.idPaseo,
                    p.fecha,
                    p.hora_inicio,
                    p.hora_fin,
                    p.precio_total,
                    a.idPaseador,
                    a.nombre,
                    a.apellido,
                    e.idPerro,
                    e.nombre,
                    s.idEstado,
                    s.nombre
                FROM paseo p
                JOIN paseador a ON p.Paseador_idPaseador = a.idPaseador
                JOIN perro e ON p.Perro_idPerro = e.idPerro
                JOIN estado s ON p.Estado_idEstado = s.idEstado";
        
        if ($rol == "paseador") {
            $consulta .= " WHERE a.idPaseador = " . $idP;
        }
        
        return $consulta;
    }
    
    
    public function consultarPorPaseadorProgramados($idP){
        return "select idPaseo, fecha, hora_inicio, hora_fin, precio_total, Paseador_idPaseador, a.nombre, a.apellido, Perro_idPerro, e.nombre, Estado_idEstado, s.nombre
                from paseo p join paseador a on (p.Paseador_idPaseador = a.idPaseador)
                             join perro e on (p.Perro_idPerro = e.idPerro)
                             join estado s on (p.Estado_idEstado = s.idEstado)
                where p.Paseador_idPaseador = '" . $idP . "' and p.Estado_idEstado = '1'" ;
        }
        
        public function consultarPorPaseadorPendientes($idP){
            return "select idPaseo, fecha, hora_inicio, hora_fin, precio_total, Paseador_idPaseador, a.nombre, a.apellido, Perro_idPerro, e.nombre, Estado_idEstado, s.nombre
                from paseo p join paseador a on (p.Paseador_idPaseador = a.idPaseador)
                             join perro e on (p.Perro_idPerro = e.idPerro)
                             join estado s on (p.Estado_idEstado = s.idEstado)
                where p.Paseador_idPaseador = '" . $idP . "' and p.Estado_idEstado != '1' and p.Estado_idEstado != '4' and p.Estado_idEstado != '3'
                ORDER BY p.fecha, p.hora_inicio";
        }
    
        public function consultarPorPaseadorCompletados($idP){
            return "select idPaseo, fecha, hora_inicio, hora_fin, precio_total, Paseador_idPaseador, a.nombre, a.apellido, Perro_idPerro, e.nombre, Estado_idEstado, s.nombre
                from paseo p join paseador a on (p.Paseador_idPaseador = a.idPaseador)
                             join perro e on (p.Perro_idPerro = e.idPerro)
                             join estado s on (p.Estado_idEstado = s.idEstado)
                where p.Paseador_idPaseador = '" . $idP . "' and p.Estado_idEstado = '3'" ;
        }
        
    public function consultar(){
        return "SELECT fecha, hora_inicio, hora_fin, precio_total, Paseador_idPaseador, Perro_idPerro, Estado_idEstado
                FROM Paseo
                WHERE idPaseo = " . $this->id;
    }
    
    public function buscar($filtro){
        $consulta = "select p.idPaseo, p.fecha, p.hora_inicio, p.hora_fin, p.precio_total, a.idPaseador, a.nombre, e.nombre, s.nombre	
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
    
    public function buscarPaseador($filtro){
        $consulta = "select p.idPaseo, p.fecha, p.hora_inicio, p.hora_fin, p.precio_total, a.idPaseador, a.nombre, e.idPerro, e.nombre, s.idEstado, s.nombre
                from paseo p join paseador a on (p.Paseador_idPaseador = a.idPaseador)
                             join perro e on (p.Perro_idPerro = e.idPerro)
                             join estado s on (p.Estado_idEstado = s.idEstado)
                where " ;
        foreach ($filtro as $text){
            $text = trim($text);
            $consulta .= "(p.idPaseo like '%" . $text . "%' or  p.fecha like '%" .  $text . "%' or p.hora_inicio like '%" .  $text . "%' or p.hora_fin like '%" .  $text . "%' or precio_total like '%" .  $text . "%' or e.nombre like '%" .  $text . "%' or s.nombre like '%" .  $text . "%') and ";
        }
        $consulta = substr($consulta, 0, strlen($consulta)-4);
        return $consulta;
    }
    
    public function registrar(){
        return "INSERT INTO paseo (fecha, hora_inicio, hora_fin, precio_total, Paseador_idPaseador, Perro_idPerro, Estado_idEstado)
                VALUES (
                    '" . $this->fecha . "',
                    '" . $this->hora_inicio . "',
                    '" . $this->hora_fin . "',
                    " . $this->precio_total . ",
                    " . $this->Paseador_idPaseador . ",
                    " . $this->Perro_idPerro . ",
                    " . $this->Estado_idEstado . "
                )";
    }
    
    public function consultarPaseosPorPropietario($idPropietario){
        return "SELECT p.idPaseo, p.fecha, p.hora_inicio, p.hora_fin, p.precio_total, 
                       p.Paseador_idPaseador, p.Perro_idPerro, p.Estado_idEstado,
                       pa.nombre as nombre_paseador, pa.apellido as apellido_paseador, pa.tarifa,
                       pe.nombre as nombre_perro, pe.foto as foto_perro, pe.observaciones as obs_perro,
                       r.nombre as raza_perro,
                       e.nombre as estado_nombre
                FROM paseo p 
                JOIN paseador pa ON (p.Paseador_idPaseador = pa.idPaseador)
                JOIN perro pe ON (p.Perro_idPerro = pe.idPerro)
                JOIN propietario pr ON (pe.Propietario_idPropietario = pr.idPropietario)
                JOIN estado e ON (p.Estado_idEstado = e.idEstado)
                JOIN raza r ON (pe.Raza_idRaza = r.idRaza)
                WHERE pr.idPropietario = " . $idPropietario . "
                ORDER BY p.fecha DESC, p.hora_inicio DESC";
    }

    public function eliminarPorPerro(){
        return "DELETE FROM paseo WHERE Perro_idPerro = " . $this->Perro_idPerro;
    }
    
    public function actualizarEstadoPaseo($estado){
        return "UPDATE `paseo` SET `Estado_idEstado`='" . $estado . "' WHERE idPaseo = '" . $this->id . "'";
    }
    
    public function cantidadDePaseosPorPaseador(){
        return "SELECT p.idPaseador, p.nombre, p.apellido, COUNT(a.idPaseo)
                from paseador p LEFT JOIN paseo a on (p.idPaseador = a.Paseador_idPaseador)
                group by p.idPaseador";
    }
    
    public function cantidadCompletadosVsCancelados() {
        return "SELECT e.nombre, COUNT(p.idPaseo)
            FROM paseo p
            INNER JOIN estado e ON (p.Estado_idEstado = e.idEstado)
            WHERE e.nombre IN ('Completado', 'Cancelado')
            GROUP BY e.nombre";
    }
    
    public function cantidadDePaseosPorMes() {
        return "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(idPaseo)
            FROM paseo
            GROUP BY mes
            ORDER BY mes ASC";
    }
    
    public function cantidadPaseosUltimos6Meses($idPaseador) {
        return "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, COUNT(idPaseo)
            FROM paseo
            WHERE Paseador_idPaseador = $idPaseador
              AND fecha >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY mes
            ORDER BY mes ASC";
    }
    
    public function totalPaseos($idPaseador) {
        return "SELECT COUNT(idPaseo) FROM paseo WHERE Paseador_idPaseador = $idPaseador";
    }
    
    public function totalGanado($idPaseador) {
        return "SELECT SUM(precio_total)
            FROM paseo
            INNER JOIN estado ON paseo.Estado_idEstado = estado.idEstado
            WHERE paseo.Paseador_idPaseador = $idPaseador
              AND estado.nombre = 'Completado'";
    }
    
    public function distribucionEstadosPorPaseador($idPaseador) {
        return "SELECT estado.nombre, COUNT(idPaseo)
            FROM paseo
            INNER JOIN estado ON paseo.Estado_idEstado = estado.idEstado
            WHERE paseo.Paseador_idPaseador = $idPaseador
              AND estado.nombre IN ('Completado', 'Programado', 'Cancelado', 'En curso', 'Aceptado')
            GROUP BY estado.nombre";
    }
    
    
    public function contarPaseosAceptadosSolapados($idPaseador, $fecha, $horaInicio, $horaFin) {
        return "SELECT COUNT(*)
            FROM paseo
            WHERE Paseador_idPaseador = '$idPaseador'
              AND fecha = '$fecha'
              AND Estado_idEstado IN (2, 5) -- Estados: En curso (2) y Aceptado (5)
              AND (
                    (hora_inicio < '$horaFin' AND hora_fin > '$horaInicio')
                  )";
    }
    
    
    
}