<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseoDAO.php");

class Paseo{
    private $id;
    private $fecha;
    private $hora_inicio;
    private $hora_fin;
    private $precio_total;
    private $Paseador_idPaseador;
    private $Perro_idPerro;
    private $Estado_idEstado;
       
    public function getId()
    {
        return $this->id;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getHora_inicio()
    {
        return $this->hora_inicio;
    }

    public function getHora_fin()
    {
        return $this->hora_fin;
    }

    public function getPrecio_total()
    {
        return $this->precio_total;
    }

    public function getPaseador_idPaseador()
    {
        return $this->Paseador_idPaseador;
    }

    public function getPerro_idPerro()
    {
        return $this->Perro_idPerro;
    }

    public function getEstado_idEstado()
    {
        return $this->Estado_idEstado;
    }

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
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarTodos());
        $paseos = array();
        while(($datos = $conexion -> registro()) != null){
            $paseador = new Paseador($datos[5],$datos[6]);
            $perro = new Perro($datos[7],$datos[8]);
            $estado =  new Estado($datos[9],$datos[10]);
            $paseo = new Paseo($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $paseador, $perro, $estado);
            array_push($paseos, $paseo);
        }
        $conexion->cerrar();
        return $paseos;
    }
    
    public function buscar($filtro){
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion -> abrir();
        $conexion -> ejecutar($paseoDAO -> buscar($filtro));
        $paseos = array();
        while (($datos = $conexion->registro()) != null) {
            $paseo = new Paseo($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $datos[5], $datos[6], $datos[7]);
            array_push($paseos, $paseo);
        }
        $conexion->cerrar();
        return $paseos;
    }
    
    public static function listarPorPropietario($idPropietario)
    {
        $conexion = new Conexion();
        $perroDAO = new PerroDAO("", "", "", "", "", $idPropietario);
        $conexion->abrir();
        $conexion->ejecutar($perroDAO->listarPorPropietario());
        
        $perros = [];
        while (($registro = $conexion->registro()) != null) {
            $perros[] = new Perro($registro[0], $registro[1], $registro[2], $registro[3], $registro[4], $registro[5]);
        }
        
        $conexion->cerrar();
        return $perros;
    }
    public static function obtenerPaseosPorPropietario($idPropietario) {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO();
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->consultarPaseosPorPropietario($idPropietario));
        
        $paseos = array();
        while (($datos = $conexion->registro()) != null) {
            // Crear objetos relacionados basados en los índices correctos
            // 0=idPaseo, 1=fecha, 2=hora_inicio, 3=hora_fin, 4=precio_total, 
            // 5=Paseador_idPaseador, 6=Perro_idPerro, 7=Estado_idEstado,
            // 8=nombre_paseador, 9=apellido_paseador, 10=tarifa,
            // 11=nombre_perro, 12=foto_perro, 13=obs_perro, 14=raza_perro, 15=estado_nombre
            
            $paseador = new Paseador($datos[5]);
            $paseador->setNombre($datos[8]);
            $paseador->setApellido($datos[9]);
            $paseador->setTarifa($datos[10]);
            
            $raza = new Raza();
            $raza->setNombre($datos[14]);
            
            $perro = new Perro($datos[6]);
            $perro->setNombre($datos[11]);
            $perro->setFoto($datos[12]);
            $perro->setObservaciones($datos[13]);
            $perro->setRaza($raza);
            
            $estado = new Estado($datos[7]);
            $estado->setNombre($datos[15]);
            
            // Crear paseo
            $paseo = new Paseo($datos[0], $datos[1], $datos[2], $datos[3], $datos[4], $paseador, $perro, $estado);
            array_push($paseos, $paseo);
        }
        $conexion->cerrar();
        return $paseos;
    }
    
    // Métodos auxiliares para el objeto
    public function getHora() {
        return $this->hora_inicio;
    }
    
    public function getDuracion() {
        $inicio = new DateTime($this->hora_inicio);
        $fin = new DateTime($this->hora_fin);
        $duracion = $inicio->diff($fin);
        return $duracion->h * 60 + $duracion->i;
    }
    
    public function getPrecio() {
        return $this->precio_total;
    }
    
    public function getPaseador() {
        return $this->Paseador_idPaseador;
    }
    
    public function getPerro() {
        return $this->Perro_idPerro;
    }
    
    public function getEstado() {
        return $this->Estado_idEstado;
    }
    
    public function getObservaciones() {
        // Por ahora retornar vacío ya que no tenemos este campo en la BD
        return "";
    }

    /**
     * Registra múltiples paseos para diferentes mascotas
     * @param string $fecha Fecha del paseo
     * @param string $horaInicio Hora de inicio
     * @param string $horaFin Hora de fin
     * @param float $precio Precio por mascota
     * @param int $idPaseador ID del paseador
     * @param array $mascotasIds Array con IDs de las mascotas
     * @return array Array con los paseos registrados
     */
    public static function registrarMultiples($fecha, $horaInicio, $horaFin, $precio, $idPaseador, $mascotasIds) {
        $paseosRegistrados = [];
        
        try {
            // Estado inicial: 1 (Pendiente o similar)
            $estadoInicial = 1;
            
            foreach ($mascotasIds as $idMascota) {
                // Crear un objeto Paseo individual y usar su método registrar individual
                $paseo = new Paseo("", $fecha, $horaInicio, $horaFin, $precio, $idPaseador, $idMascota, $estadoInicial);
                $paseo->registrar(); // Usar el método individual que maneja su propia conexión
                
                $paseosRegistrados[] = $paseo;
            }
            
            return $paseosRegistrados;
            
        } catch (Exception $e) {
            throw new Exception("Error al registrar paseos: " . $e->getMessage());
        }
    }

    /**
     * Registra un paseo individual
     */
    public function registrar() {
        $conexion = new Conexion();
        $paseoDAO = new PaseoDAO($this->id, $this->fecha, $this->hora_inicio, $this->hora_fin, $this->precio_total, $this->Paseador_idPaseador, $this->Perro_idPerro, $this->Estado_idEstado);
        $conexion->abrir();
        $conexion->ejecutar($paseoDAO->registrar());
        $conexion->cerrar();
    }
}
