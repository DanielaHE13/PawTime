<?php
require_once(__DIR__ . "/Paseo.php");
require_once(__DIR__ . "/Estado.php");
require_once(__DIR__ . "/Perro.php");
require_once(__DIR__ . "/Paseador.php");
require_once(__DIR__ . "/Raza.php");
require_once("persistencia/Conexion.php");

/**
 * Clase de servicio para manejar la lógica de negocio relacionada con los paseos
 */
class ServicioPaseo {
    
    /**
     * Programa un paseo para múltiples mascotas
     * @param array $datosPaseo Array con los datos del paseo (fecha, hora, duracion, etc.)
     * @param array $mascotasIds Array con los IDs de las mascotas
     * @param int $idPaseador ID del paseador seleccionado
     * @return array Resultado de la operación
     */
    public static function programarPaseo($datosPaseo, $mascotasIds, $idPaseador) {
        try {
            // Validar datos de entrada
            $errores = self::validarDatosPaseo($datosPaseo, $mascotasIds, $idPaseador);
            if (!empty($errores)) {
                return [
                    'exitoso' => false,
                    'errores' => $errores
                ];
            }
            
            // Obtener paseador para calcular precio
            $paseador = new Paseador($idPaseador);
            $paseador->consultar();
            $precioPorMascota = $paseador->getTarifa();
            
            // Calcular horas de inicio y fin
            $horaInicio = DateTime::createFromFormat('H:i', $datosPaseo['hora']);
            $horaFin = clone $horaInicio;
            $horaFin->add(new DateInterval('PT' . $datosPaseo['duracion'] . 'M'));
            
            // Registrar paseos usando el método estático
            $paseosRegistrados = Paseo::registrarMultiples(
                $datosPaseo['fecha'],
                $horaInicio->format('H:i:s'),
                $horaFin->format('H:i:s'),
                $precioPorMascota,
                $idPaseador,
                $mascotasIds
            );
            
            return [
                'exitoso' => true,
                'mensaje' => '¡Paseo programado exitosamente! Recibirás una confirmación y el paseador se comunicará contigo.',
                'paseos' => $paseosRegistrados,
                'precioTotal' => $precioPorMascota * count($mascotasIds)
            ];
            
        } catch (Exception $e) {
            return [
                'exitoso' => false,
                'errores' => ['Error al programar el paseo: ' . $e->getMessage()]
            ];
        }
    }
    
    /**
     * Valida los datos necesarios para programar un paseo
     */
    private static function validarDatosPaseo($datosPaseo, $mascotasIds, $idPaseador) {
        $errores = [];
        
        // Validar datos básicos
        if (empty($datosPaseo['fecha'])) {
            $errores[] = 'La fecha es requerida';
        }
        
        if (empty($datosPaseo['hora'])) {
            $errores[] = 'La hora es requerida';
        }
        
        if (empty($datosPaseo['duracion'])) {
            $errores[] = 'La duración es requerida';
        }
        
        if (empty($datosPaseo['direccion'])) {
            $errores[] = 'La dirección es requerida';
        }
        
        if (empty($mascotasIds)) {
            $errores[] = 'Debe seleccionar al menos una mascota';
        }
        
        if (empty($idPaseador)) {
            $errores[] = 'Debe seleccionar un paseador';
        }
        
        // Validar que la fecha no sea en el pasado
        $fechaPaseo = DateTime::createFromFormat('Y-m-d', $datosPaseo['fecha']);
        $hoy = new DateTime();
        $hoy->setTime(0, 0, 0);
        
        if ($fechaPaseo < $hoy) {
            $errores[] = 'No se pueden programar paseos en fechas pasadas';
        }
        
        // Validar conflictos de horarios para las mascotas
        if (!empty($datosPaseo['fecha']) && !empty($datosPaseo['hora']) && !empty($datosPaseo['duracion']) && !empty($mascotasIds)) {
            $conflictos = self::verificarConflictosHorarios($datosPaseo['fecha'], $datosPaseo['hora'], $datosPaseo['duracion'], $mascotasIds);
            if (!empty($conflictos)) {
                $errores = array_merge($errores, $conflictos);
            }
        }
        
        return $errores;
    }
    
    /**
     * Obtiene el resumen de un paseo programado
     */
    public static function obtenerResumenPaseo($sessionData) {
        $resumen = [];
        
        // Obtener mascotas seleccionadas
        if (isset($sessionData['paseo_mascotas'])) {
            $resumen['mascotas'] = [];
            foreach ($sessionData['paseo_mascotas'] as $idPerro) {
                $perro = new Perro($idPerro);
                $perro->consultar();
                $resumen['mascotas'][] = $perro;
            }
        }
        
        // Obtener paseador seleccionado
        if (isset($sessionData['paseo_paseador'])) {
            $paseador = new Paseador($sessionData['paseo_paseador']);
            $paseador->consultar();
            $resumen['paseador'] = $paseador;
        }
        
        // Obtener otros datos
        $resumen['fecha'] = $sessionData['paseo_fecha'] ?? '';
        $resumen['hora'] = $sessionData['paseo_hora'] ?? '';
        $resumen['duracion'] = $sessionData['paseo_duracion'] ?? '';
        $resumen['direccion'] = $sessionData['paseo_direccion'] ?? '';
        $resumen['observaciones'] = $sessionData['paseo_observaciones'] ?? '';
        
        return $resumen;
    }
    
    /**
     * Limpia los datos de sesión del paseo
     */
    public static function limpiarDatosSesion(&$session) {
        unset($session["paseo_mascotas"]);
        unset($session["paseo_fecha"]);
        unset($session["paseo_hora"]);
        unset($session["paseo_duracion"]);
        unset($session["paseo_paseador"]);
        unset($session["paseo_direccion"]);
        unset($session["paseo_observaciones"]);
    }

    /**
     * Verifica conflictos de horarios para las mascotas seleccionadas
     * @param string $fecha Fecha del paseo
     * @param string $hora Hora del paseo
     * @param int $duracion Duración en minutos
     * @param array $mascotasIds Array de IDs de mascotas
     * @return array Array de errores de conflictos
     */
    private static function verificarConflictosHorarios($fecha, $hora, $duracion, $mascotasIds) {
        $errores = [];
        
        // Calcular hora de inicio y fin del nuevo paseo
        $horaInicio = DateTime::createFromFormat('H:i', $hora);
        $horaFin = clone $horaInicio;
        $horaFin->add(new DateInterval('PT' . $duracion . 'M'));
        
        $horaInicioStr = $horaInicio->format('H:i:s');
        $horaFinStr = $horaFin->format('H:i:s');
        
        foreach ($mascotasIds as $idMascota) {
            // Obtener la mascota para mostrar su nombre en el error
            $mascota = new Perro($idMascota);
            $mascota->consultar();
            
            // Buscar paseos existentes para esta mascota en la misma fecha
            $conexion = new Conexion();
            $conexion->abrir();
            
            $consulta = "SELECT p.hora_inicio, p.hora_fin, e.nombre as estado
                        FROM paseo p 
                        JOIN estado e ON p.Estado_idEstado = e.idEstado
                        WHERE p.Perro_idPerro = $idMascota 
                        AND p.fecha = '$fecha' 
                        AND e.nombre != 'Cancelado'
                        AND e.nombre != 'Finalizado'";
                        
            $conexion->ejecutar($consulta);
            
            while (($registro = $conexion->registro()) != null) {
                $paseoInicioExistente = DateTime::createFromFormat('H:i:s', $registro[0]);
                $paseoFinExistente = DateTime::createFromFormat('H:i:s', $registro[1]);
                
                // Verificar si hay solapamiento de horarios
                if (self::hayConflictoHorario($horaInicio, $horaFin, $paseoInicioExistente, $paseoFinExistente)) {
                    $errores[] = "La mascota " . $mascota->getNombre() . " ya tiene un paseo programado de " . 
                                $paseoInicioExistente->format('g:i A') . " a " . $paseoFinExistente->format('g:i A') . 
                                " el " . date('d/m/Y', strtotime($fecha));
                    break; // Solo mostrar un error por mascota
                }
            }
            
            $conexion->cerrar();
        }
        
        return $errores;
    }

    /**
     * Verifica si dos rangos de tiempo se solapan
     * @param DateTime $inicio1 Hora de inicio del primer rango
     * @param DateTime $fin1 Hora de fin del primer rango
     * @param DateTime $inicio2 Hora de inicio del segundo rango
     * @param DateTime $fin2 Hora de fin del segundo rango
     * @return bool True si hay conflicto, False si no
     */
    private static function hayConflictoHorario($inicio1, $fin1, $inicio2, $fin2) {
        // Dos rangos de tiempo se solapan si:
        // - El inicio del primero es antes del fin del segundo Y
        // - El fin del primero es después del inicio del segundo
        return ($inicio1 < $fin2) && ($fin1 > $inicio2);
    }
}
?>
