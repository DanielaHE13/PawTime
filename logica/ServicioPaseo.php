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
            $tarifaPorHora = $paseador->getTarifa();
            
            // Calcular precio usando la función centralizada
            $precioTotal = self::calcularPrecioTotal($tarifaPorHora, $datosPaseo['duracion'], count($mascotasIds));
            $precioPorMascota = $precioTotal / count($mascotasIds);
            
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
                'precioTotal' => $precioTotal
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
        
        // Validar límite de paseos simultáneos para el paseador
        if (!empty($datosPaseo['fecha']) && !empty($datosPaseo['hora']) && !empty($datosPaseo['duracion']) && !empty($idPaseador)) {
            $conflictoPaseador = self::verificarLimitePaseador($idPaseador, $datosPaseo['fecha'], $datosPaseo['hora'], $datosPaseo['duracion']);
            if (!empty($conflictoPaseador)) {
                $errores[] = $conflictoPaseador;
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
                        AND e.nombre IN ('Aceptado', 'En curso')";
                        
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
    
    /**
     * Verifica si un paseador ya tiene el límite máximo de paseos simultáneos (2)
     * @param int $idPaseador ID del paseador
     * @param string $fecha Fecha del nuevo paseo (Y-m-d)
     * @param string $hora Hora de inicio del nuevo paseo (H:i)
     * @param int $duracion Duración en minutos
     * @return string Mensaje de error si hay conflicto, string vacío si no hay problema
     */
    private static function verificarLimitePaseador($idPaseador, $fecha, $hora, $duracion) {
        // Calcular hora de inicio y fin del nuevo paseo
        $horaInicio = DateTime::createFromFormat('H:i', $hora);
        $horaFin = clone $horaInicio;
        $horaFin->add(new DateInterval('PT' . $duracion . 'M'));
        
        $horaInicioStr = $horaInicio->format('H:i:s');
        $horaFinStr = $horaFin->format('H:i:s');
        
        // Usar la función existente para contar paseos solapados
        $paseo = new Paseo();
        $paseosSimultaneos = $paseo->contarPaseosAceptadosSolapados($idPaseador, $fecha, $horaInicioStr, $horaFinStr);
        
        if ($paseosSimultaneos >= 2) {
            return "El paseador seleccionado ya tiene programados 2 paseos simultáneos para ese horario. Por la seguridad y bienestar de los perritos, cada paseador puede manejar máximo 2 perros al mismo tiempo.";
        }
        
        return "";
    }
    
    /**
     * Obtiene los paseadores disponibles para una fecha y horario específicos
     * @param string $fecha Fecha del paseo (Y-m-d)
     * @param string $hora Hora de inicio (H:i)
     * @param int $duracion Duración en minutos
     * @return array Array de paseadores disponibles
     */
    public static function obtenerPaseadoresDisponibles($fecha, $hora, $duracion) {
        // Obtener todos los paseadores activos
        $paseador = new Paseador();
        $todosPaseadores = $paseador->consultarTodos();
        
        // Filtrar solo paseadores activos
        $paseadoresActivos = array_filter($todosPaseadores, function($p) {
            return $p->getEstado() == 1;
        });
        
        // Filtrar paseadores que no tengan conflictos de horario
        $paseadoresDisponibles = array();
        
        foreach ($paseadoresActivos as $paseadorItem) {
            $conflicto = self::verificarLimitePaseador($paseadorItem->getId(), $fecha, $hora, $duracion);
            if (empty($conflicto)) {
                $paseadoresDisponibles[] = $paseadorItem;
            }
        }
        
        return $paseadoresDisponibles;
    }

    /**
     * Validación completa del sistema de límite de paseadores
     * Método de utilidad para verificar que todas las validaciones funcionen correctamente
     */
    public static function validarSistemaLimitePaseadores() {
        $resultados = [];
        
        // Obtener todos los paseadores activos
        $paseador = new Paseador();
        $paseadores = $paseador->consultarTodos();
        
        foreach ($paseadores as $p) {
            if ($p->getEstado() == 1) { // Solo paseadores activos
                // Verificar cuántos paseos tiene programados para hoy
                $paseo = new Paseo();
                $fechaHoy = date('Y-m-d');
                $horaActual = date('H:i:s');
                
                $paseosSimultaneos = $paseo->contarPaseosAceptadosSolapados(
                    $p->getId(), 
                    $fechaHoy, 
                    $horaActual, 
                    '23:59:59'
                );
                
                $resultados[] = [
                    'paseador' => $p->getNombre() . ' ' . $p->getApellido(),
                    'id' => $p->getId(),
                    'paseos_activos' => $paseosSimultaneos,
                    'disponible' => $paseosSimultaneos < 2
                ];
            }
        }
        
        return $resultados;
    }

    /**
     * Función de debug para verificar qué paseos está contando
     * REMOVER DESPUÉS DE SOLUCIONAR EL PROBLEMA
     */
    public static function debugContarPaseos($idPaseador, $fecha, $horaInicio, $horaFin) {
        $conexion = new Conexion();
        $conexion->abrir();
        
        $consulta = "SELECT p.idPaseo, p.fecha, p.hora_inicio, p.hora_fin, e.idEstado, e.nombre as estado
                    FROM paseo p 
                    JOIN estado e ON p.Estado_idEstado = e.idEstado
                    WHERE p.Paseador_idPaseador = '$idPaseador'
                      AND p.fecha = '$fecha'";
                      
        $conexion->ejecutar($consulta);
        
        $paseos = [];
        while (($registro = $conexion->registro()) != null) {
            $paseos[] = [
                'id' => $registro[0],
                'fecha' => $registro[1],
                'hora_inicio' => $registro[2], 
                'hora_fin' => $registro[3],
                'estado_id' => $registro[4],
                'estado_nombre' => $registro[5]
            ];
        }
        
        $conexion->cerrar();
        return $paseos;
    }

    /**
     * Calcula el precio total de un paseo
     * Fórmula: Tarifa por hora × Duración en horas × Cantidad de perros
     * @param float $tarifaPorHora Tarifa del paseador por hora
     * @param int $duracionMinutos Duración del paseo en minutos
     * @param int $cantidadPerros Cantidad de perros en el paseo
     * @return float Precio total del paseo
     */
    public static function calcularPrecioTotal($tarifaPorHora, $duracionMinutos, $cantidadPerros) {
        $duracionEnHoras = $duracionMinutos / 60;
        $precioPorPerro = $tarifaPorHora * $duracionEnHoras;
        $precioTotal = $precioPorPerro * $cantidadPerros;
        
        return round($precioTotal, 2); // Redondear a 2 decimales
    }
    
    /**
     * Función de utilidad para validar cálculos de precio
     * @param int $idPaseador ID del paseador
     * @param int $duracionMinutos Duración en minutos
     * @param int $cantidadPerros Cantidad de perros
     * @return array Información detallada del cálculo
     */
    public static function validarCalculoPrecio($idPaseador, $duracionMinutos, $cantidadPerros) {
        $paseador = new Paseador($idPaseador);
        $paseador->consultar();
        
        $tarifaPorHora = $paseador->getTarifa();
        $duracionEnHoras = $duracionMinutos / 60;
        $precioPorPerro = $tarifaPorHora * $duracionEnHoras;
        $precioTotal = $precioPorPerro * $cantidadPerros;
        
        return [
            'paseador' => $paseador->getNombre() . ' ' . $paseador->getApellido(),
            'tarifa_por_hora' => $tarifaPorHora,
            'duracion_minutos' => $duracionMinutos,
            'duracion_horas' => $duracionEnHoras,
            'cantidad_perros' => $cantidadPerros,
            'precio_por_perro' => $precioPorPerro,
            'precio_total' => $precioTotal,
            'formula' => "$tarifaPorHora × $duracionEnHoras × $cantidadPerros = $precioTotal"
        ];
    }
}

/* ✅ **CÁLCULO CORREGIDO DEL PRECIO TOTAL

### Fórmula implementada:
**Precio Total = Tarifa por hora × Duración en horas × Cantidad de perros**

### Ejemplo práctico:
- **Paseador**: Juan Pérez, Tarifa: $20,000/hora
- **Duración**: 90 minutos (1.5 horas) 
- **Perros**: 2 perros (Max y Luna)

**Cálculo:**
- Duración en horas: 90 ÷ 60 = 1.5 horas
- Precio total: $20,000 × 1.5 × 2 = $60,000

### Lugares donde se aplica:
1. ✅ `ServicioPaseo::programarPaseo()` - Al crear el paseo
2. ✅ `programarPaseo_Paso5.php` - En el resumen antes de confirmar
3. ✅ `ServicioPaseo::calcularPrecioTotal()` - Función centralizada
4. ✅ `factura.php` - Usa el precio guardado en BD (ya corregido)

### Validación:
Usa `ServicioPaseo::validarCalculoPrecio($idPaseador, $duracionMinutos, $cantidadPerros)` 
para verificar cálculos específicos.
*/
