<?php
if ($_SESSION["rol"] != "propietario") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

// Verificar que vengan los datos de todos los pasos anteriores
if (!isset($_SESSION["paseo_mascotas"]) || empty($_SESSION["paseo_mascotas"]) ||
    !isset($_SESSION["paseo_fecha"]) || empty($_SESSION["paseo_fecha"]) ||
    !isset($_SESSION["paseo_hora"]) || empty($_SESSION["paseo_hora"]) ||
    !isset($_SESSION["paseo_duracion"]) || empty($_SESSION["paseo_duracion"]) ||
    !isset($_SESSION["paseo_paseador"]) || empty($_SESSION["paseo_paseador"]) ||
    !isset($_SESSION["paseo_direccion"]) || empty($_SESSION["paseo_direccion"])) {
    header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo.php"));
    exit;
}

require_once(__DIR__ . "/../../logica/Perro.php");
require_once(__DIR__ . "/../../logica/Paseador.php");
require_once(__DIR__ . "/../../logica/Paseo.php");
require_once(__DIR__ . "/../../logica/ServicioPaseo.php");

$mensaje = "";
$tipoMensaje = "";
$errores = array();
$paseoRegistrado = false;

// Obtener resumen del paseo usando la clase de servicio
$resumenPaseo = ServicioPaseo::obtenerResumenPaseo($_SESSION);
$mascotasSeleccionadas = $resumenPaseo['mascotas'];
$paseadorSeleccionado = $resumenPaseo['paseador'];
$fecha = $resumenPaseo['fecha'];
$hora = $resumenPaseo['hora'];
$duracion = $resumenPaseo['duracion'];
$direccion = $resumenPaseo['direccion'];
$observaciones = $resumenPaseo['observaciones'];

// Calcular hora de fin
$horaInicio = DateTime::createFromFormat('H:i', $hora);
$horaFin = clone $horaInicio;
$horaFin->add(new DateInterval('PT' . $duracion . 'M'));

// Calcular precio total
$precioPorMascota = $paseadorSeleccionado->getTarifa();
$precioTotal = $precioPorMascota * count($mascotasSeleccionadas);

// Procesar confirmación del paseo
if ($_POST && isset($_POST["confirmar_paseo"])) {
    // Preparar datos del paseo
    $datosPaseo = [
        'fecha' => $fecha,
        'hora' => $hora,
        'duracion' => $duracion,
        'direccion' => $direccion,
        'observaciones' => $observaciones
    ];
    
    // Obtener IDs de las mascotas
    $mascotasIds = array_map(function($mascota) {
        return $mascota->getId();
    }, $mascotasSeleccionadas);
    
    // Programar el paseo usando la clase de servicio
    $resultado = ServicioPaseo::programarPaseo($datosPaseo, $mascotasIds, $paseadorSeleccionado->getId());
    
    if ($resultado['exitoso']) {
        $paseoRegistrado = true;
        $mensaje = $resultado['mensaje'];
        $tipoMensaje = "success";
        
        // Limpiar datos de sesión del paseo
        ServicioPaseo::limpiarDatosSesion($_SESSION);
        
    } else {
        $errores = $resultado['errores'];
        $tipoMensaje = "error";
    }
}

// Si viene del botón "Atrás"
if ($_POST && isset($_POST["paso_anterior"])) {
    header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo_Paso4.php"));
    exit;
}

include("presentacion/encabezado.php");
include("presentacion/menuPropietario.php");
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                
                <!-- Botones de navegación (solo si no se ha registrado el paseo) -->
                <?php if (!$paseoRegistrado): ?>
                    <div class="mb-3">
                        <form method="post" class="d-inline">
                            <button type="submit" name="paso_anterior" class="btn" style="background-color: #4b0082; color: white; border-radius: 12px;">
                                <i class="fas fa-arrow-left me-2"></i>Atrás
                            </button>
                        </form>
                        <a href="?pid=<?php echo base64_encode('presentacion/sesionPropietario.php'); ?>" class="btn btn-outline-secondary ms-2" style="border-radius: 12px;">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Indicador de pasos -->
                <div class="card mb-4" style="border-radius: 15px; border: none; background: rgba(255, 255, 255, 0.9);">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="step-indicator completed">
                                    <div class="step-circle"><i class="fas fa-check"></i></div>
                                    <small class="fw-bold text-success">Mascotas</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="step-indicator completed">
                                    <div class="step-circle"><i class="fas fa-check"></i></div>
                                    <small class="fw-bold text-success">Fecha & Hora</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="step-indicator completed">
                                    <div class="step-circle"><i class="fas fa-check"></i></div>
                                    <small class="fw-bold text-success">Paseador</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="step-indicator <?php echo $paseoRegistrado ? 'completed' : 'active'; ?>">
                                    <div class="step-circle">
                                        <?php if ($paseoRegistrado): ?>
                                            <i class="fas fa-check"></i>
                                        <?php else: ?>
                                            4
                                        <?php endif; ?>
                                    </div>
                                    <small class="fw-bold <?php echo $paseoRegistrado ? 'text-success' : 'text-primary'; ?>">Confirmar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje de éxito o error -->
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipoMensaje == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <i class="fas fa-<?php echo $tipoMensaje == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                        <?php echo $mensaje; ?>
                        <?php if ($tipoMensaje != 'success'): ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Tarjeta principal -->
                <div class="card shadow-lg animate__animated animate__fadeInUp" style="border-radius: 20px; border: none; background: rgba(255, 255, 255, 0.95);">
                    
                    <?php if ($paseoRegistrado): ?>
                        <!-- Confirmación exitosa -->
                        <div class="card-header text-center py-4" style="background: linear-gradient(45deg, #28a745, #20c997); border-radius: 20px 20px 0 0; border: none;">
                            <h2 class="fw-bold text-white mb-0">
                                <i class="fas fa-check-circle me-2"></i>¡Paseo Programado Exitosamente!
                            </h2>
                            <p class="text-white-50 mb-0">Tu mascota está lista para la aventura 🎉</p>
                        </div>

                        <div class="card-body p-4 text-center">
                            <div>
                                <i class="fas fa-heart fa-4x text-success mb-4"></i>
                                <h4 class="text-success mb-3">¡Todo listo!</h4>
                                <p class="text-muted mb-4">
                                    El paseador se comunicará contigo 30 minutos antes del paseo para coordinar los detalles finales.
                                    Recibirás notificaciones y fotos durante el paseo.
                                </p>
                                
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="?pid=<?php echo base64_encode('presentacion/sesionPropietario.php'); ?>" class="btn text-white fw-bold btn-lg px-4 py-3" style="background-color: #4b0082; border-radius: 15px;">
                                        <i class="fas fa-home me-2"></i>Ir al Inicio
                                    </a>
                                    <a href="?pid=<?php echo base64_encode('presentacion/propietario/programarPaseo.php'); ?>" class="btn btn-outline-primary fw-bold btn-lg px-4 py-3" style="border-radius: 15px;">
                                        <i class="fas fa-plus me-2"></i>Programar Otro
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <!-- Confirmación de datos -->
                        <div class="card-header text-center py-4" style="background: linear-gradient(45deg, #4b0082, #6a0dad); border-radius: 20px 20px 0 0; border: none;">
                            <h2 class="fw-bold text-white mb-0">
                                <i class="fas fa-clipboard-check me-2"></i>Confirma los Detalles del Paseo
                            </h2>
                            <p class="text-white-50 mb-0">Revisa toda la información antes de confirmar 📋</p>
                        </div>

                        <div class="card-body p-4">
                            
                            <!-- Resumen completo -->
                            <div class="row">
                                
                                <!-- Mascotas -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100" style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                        <div class="card-body">
                                            <h5 class="fw-bold mb-3" style="color: #4b0082;">
                                                <i class="fas fa-dog me-2"></i>Mascotas (<?php echo count($mascotasSeleccionadas); ?>)
                                            </h5>
                                            <?php foreach ($mascotasSeleccionadas as $mascota): ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="imagen/perros/<?php echo $mascota->getFoto(); ?>" 
                                                         alt="<?php echo $mascota->getNombre(); ?>"
                                                         class="rounded-circle me-3"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                    <div>
                                                        <div class="fw-bold"><?php echo $mascota->getNombre(); ?></div>
                                                        <small class="text-muted"><?php echo $mascota->getRazaNombre(); ?></small>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fecha y Hora -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100" style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                        <div class="card-body">
                                            <h5 class="fw-bold mb-3" style="color: #4b0082;">
                                                <i class="fas fa-calendar-alt me-2"></i>Fecha & Hora
                                            </h5>
                                            <div class="mb-2">
                                                <i class="fas fa-calendar me-2" style="color: #4b0082;"></i>
                                                <span class="fw-bold"><?php echo date('d/m/Y', strtotime($fecha)); ?></span>
                                                <br>
                                                <small class="text-muted"><?php echo date('l', strtotime($fecha)); ?></small>
                                            </div>
                                            <div class="mb-2">
                                                <i class="fas fa-clock me-2" style="color: #4b0082;"></i>
                                                <span class="fw-bold">
                                                    <?php echo $horaInicio->format('g:i A'); ?> - <?php echo $horaFin->format('g:i A'); ?>
                                                </span>
                                                <br>
                                                <small class="text-muted"><?php echo $duracion; ?> minutos</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paseador -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100" style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                        <div class="card-body">
                                            <h5 class="fw-bold mb-3" style="color: #4b0082;">
                                                <i class="fas fa-user-tie me-2"></i>Paseador
                                            </h5>
                                            <div class="d-flex align-items-center">
                                                <img src="imagen/paseador.png" 
                                                     alt="<?php echo $paseadorSeleccionado->getNombre(); ?>"
                                                     class="rounded-circle me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold"><?php echo $paseadorSeleccionado->getNombre() . " " . $paseadorSeleccionado->getApellido(); ?></div>
                                                    <div class="text-success fw-bold">
                                                        $<?php echo number_format($paseadorSeleccionado->getTarifa(), 0, ',', '.'); ?>/hora
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dirección y Precio -->
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100" style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                        <div class="card-body">
                                            <h5 class="fw-bold mb-3" style="color: #4b0082;">
                                                <i class="fas fa-map-marker-alt me-2"></i>Dirección & Precio
                                            </h5>
                                            <div class="mb-3">
                                                <small class="text-muted d-block">Punto de recogida:</small>
                                                <div class="fw-bold"><?php echo substr($direccion, 0, 100) . (strlen($direccion) > 100 ? '...' : ''); ?></div>
                                            </div>
                                            <div class="text-center">
                                                <div class="h4 fw-bold text-success mb-0">
                                                    $<?php echo number_format($precioTotal, 0, ',', '.'); ?>
                                                </div>
                                                <small class="text-muted">Total del paseo</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Observaciones si existen -->
                            <?php if (!empty($observaciones)): ?>
                                <div class="card mb-4" style="border-radius: 15px; background: linear-gradient(135deg, #e3c5f7, #d1b3f0);">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2" style="color: #4b0082;">
                                            <i class="fas fa-comment me-2"></i>Observaciones adicionales
                                        </h6>
                                        <p class="mb-0 small"><?php echo $observaciones; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Botón de confirmación -->
                            <div class="text-center">
                                <form method="post">
                                    <button type="submit" name="confirmar_paseo" id="btn-confirmar" class="btn text-white fw-bold btn-lg px-5 py-3" 
                                            style="background-color: #4b0082; border-radius: 20px;">
                                        <i class="fas fa-heart me-2"></i>¡Confirmar Paseo!
                                    </button>
                                </form>
                            </div>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        .step-indicator {
            position: relative;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 8px;
            transition: all 0.3s ease;
        }
        
        .step-indicator.active .step-circle {
            background-color: #4b0082;
            color: white;
            transform: scale(1.1);
        }
        
        .step-indicator.completed .step-circle {
            background-color: #28a745;
            color: white;
        }
        
        .step-indicator.active small {
            color: #4b0082 !important;
            font-weight: bold;
        }
        
        .step-indicator.completed small {
            color: #28a745 !important;
            font-weight: bold;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Código JavaScript para efectos adicionales si es necesario
        });
    </script>

</body>
