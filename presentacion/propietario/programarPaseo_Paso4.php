<?php
if ($_SESSION["rol"] != "propietario") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

// Verificar que vengan los datos de los pasos anteriores
if (!isset($_SESSION["paseo_mascotas"]) || empty($_SESSION["paseo_mascotas"]) ||
    !isset($_SESSION["paseo_fecha"]) || empty($_SESSION["paseo_fecha"]) ||
    !isset($_SESSION["paseo_hora"]) || empty($_SESSION["paseo_hora"]) ||
    !isset($_SESSION["paseo_duracion"]) || empty($_SESSION["paseo_duracion"]) ||
    !isset($_SESSION["paseo_paseador"]) || empty($_SESSION["paseo_paseador"])) {
    header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo.php"));
    exit;
}

require_once(__DIR__ . "/../../logica/Perro.php");
require_once(__DIR__ . "/../../logica/Paseador.php");
require_once(__DIR__ . "/../../logica/Propietario.php");

$mensaje = "";
$tipoMensaje = "";
$errores = array();

// Obtener datos de los pasos anteriores
$mascotasSeleccionadas = array();
foreach ($_SESSION["paseo_mascotas"] as $idPerro) {
    $perro = new Perro($idPerro);
    $perro->consultar();
    $mascotasSeleccionadas[] = $perro;
}

$paseadorSeleccionado = new Paseador($_SESSION["paseo_paseador"]);
$paseadorSeleccionado->consultar();

// Obtener datos del propietario para sugerir dirección
$propietario = new Propietario($_SESSION["id"]);
$propietario->consultar();

// Procesar formulario
if ($_POST && isset($_POST["siguiente_paso"])) {
    $direccion = trim($_POST["direccion"] ?? "");
    $observaciones = trim($_POST["observaciones"] ?? "");
    
    // Validaciones
    if (empty($direccion)) {
        $errores[] = "Debe ingresar la dirección de recogida";
    } else if (strlen($direccion) < 10) {
        $errores[] = "La dirección debe ser más específica (mínimo 10 caracteres)";
    }
    
    if (empty($errores)) {
        // Guardar en sesión y continuar
        $_SESSION["paseo_direccion"] = $direccion;
        $_SESSION["paseo_observaciones"] = $observaciones;
        header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo_Paso5.php"));
        exit;
    }
}

// Si viene del botón "Atrás"
if ($_POST && isset($_POST["paso_anterior"])) {
    header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo_Paso3.php"));
    exit;
}

include("presentacion/encabezado.php");
include("presentacion/menuPropietario.php");
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                
                <!-- Botones de navegación -->
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
                                <div class="step-indicator active">
                                    <div class="step-circle">4</div>
                                    <small class="fw-bold text-primary">Dirección</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen de selección previa -->
                <div class="card mb-4 shadow-sm animate__animated animate__fadeInDown" style="border-radius: 15px; border: none; background: rgba(255, 255, 255, 0.95);">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <i class="fas fa-dog me-1" style="color: #4b0082;"></i>
                                <span class="fw-bold"><?php echo count($mascotasSeleccionadas); ?> mascota(s)</span>
                            </div>
                            <div class="col-md-3">
                                <i class="fas fa-calendar me-1" style="color: #4b0082;"></i>
                                <span class="fw-bold"><?php echo date('d/m/Y', strtotime($_SESSION["paseo_fecha"])); ?></span>
                            </div>
                            <div class="col-md-3">
                                <i class="fas fa-clock me-1" style="color: #4b0082;"></i>
                                <span class="fw-bold"><?php echo date('g:i A', strtotime($_SESSION["paseo_hora"])); ?></span>
                            </div>
                            <div class="col-md-3">
                                <i class="fas fa-user-tie me-1" style="color: #4b0082;"></i>
                                <span class="fw-bold"><?php echo $paseadorSeleccionado->getNombre() . " " . $paseadorSeleccionado->getApellido(); ?></span>
                                <br>
                                <small class="text-success">$<?php echo number_format($paseadorSeleccionado->getTarifa(), 0, ',', '.'); ?>/hora</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta principal -->
                <div class="card shadow-lg animate__animated animate__fadeInUp" style="border-radius: 20px; border: none; background: rgba(255, 255, 255, 0.95);">
                    <div class="card-header text-center py-4" style="background: linear-gradient(45deg, #4b0082, #6a0dad); border-radius: 20px 20px 0 0; border: none;">
                        <h2 class="fw-bold text-white mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Paso 4: ¿Dónde te recogemos?
                        </h2>
                        <p class="text-white-50 mb-0">Ingresa la dirección exacta para el punto de encuentro 📍</p>
                    </div>

                    <div class="card-body p-4">
                        
                        <!-- Mensajes de error -->
                        <?php if (!empty($errores)): ?>
                            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    <?php foreach ($errores as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Información útil -->
                        <div class="alert alert-info mb-4" style="border-radius: 15px; background: linear-gradient(45deg, #f3e5f5, #e1bee7);">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-lightbulb fa-2x me-3 mt-1" style="color: #4b0082;"></i>
                                <div>
                                    <h6 class="fw-bold mb-2" style="color: #4b0082;">Tips para una mejor experiencia</h6>
                                    <ul class="mb-0 small" style="color: #6a0dad;">
                                        <li>Incluye referencias claras (edificios, parques, tiendas cercanas)</li>
                                        <li>Especifica el número de apartamento o casa si aplica</li>
                                        <li>Agrega instrucciones especiales en las observaciones</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form method="post" novalidate>
                            
                            <!-- Dirección principal -->
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-3" style="color: #4b0082;">
                                    <i class="fas fa-home me-2"></i>Dirección de recogida
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control form-control-lg" 
                                          name="direccion" 
                                          id="direccion"
                                          rows="3"
                                          placeholder="Ej: Calle 123 #45-67, Apartamento 8B, Barrio Los Rosales. Al frente del supermercado Metro."
                                          style="border-radius: 15px; border: 2px solid #E3CFF5; resize: none;"><?php 
                                          echo $_POST['direccion'] ?? (!empty($propietario->getDireccion()) ? htmlspecialchars($propietario->getDireccion()) : ''); 
                                          ?></textarea>
                            </div>

                            <!-- Observaciones adicionales -->
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-3" style="color: #4b0082;">
                                    <i class="fas fa-comment me-2"></i>Observaciones adicionales
                                    <small class="text-muted fw-normal">(Opcional)</small>
                                </label>
                                <textarea class="form-control form-control-lg" 
                                          name="observaciones" 
                                          id="observaciones"
                                          rows="3"
                                          placeholder="Ej: Portón azul, timbre del apartamento 8B, horarios de portería, instrucciones especiales para las mascotas..."
                                          style="border-radius: 15px; border: 2px solid #E3CFF5; resize: none;"><?php echo $_POST['observaciones'] ?? ''; ?></textarea>
                            </div>

                            <!-- Botón siguiente -->
                            <div class="text-center mt-4">
                                <button type="submit" name="siguiente_paso" id="btn-siguiente" class="btn text-white fw-bold btn-lg px-5 py-3" 
                                        style="background-color: #4b0082; border-radius: 20px; opacity: 0.5;" disabled>
                                    <i class="fas fa-check-circle me-2"></i>¡Confirmar Paseo!
                                </button>
                            </div>

                        </form>
                    </div>
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

        .form-control:focus {
            border-color: #4b0082;
            box-shadow: 0 0 0 0.2rem rgba(75, 0, 130, 0.25);
        }

        #btn-siguiente.enabled {
            opacity: 1 !important;
        }

        .btn-outline-primary {
            border-color: #4b0082;
            color: #4b0082;
        }

        .btn-outline-primary:hover {
            background-color: #4b0082;
            border-color: #4b0082;
            color: white;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const direccion = document.getElementById('direccion');
            const btnSiguiente = document.getElementById('btn-siguiente');
            
            function validarFormulario() {
                const direccionValida = direccion.value.trim().length >= 10;
                
                if (direccionValida) {
                    btnSiguiente.disabled = false;
                    btnSiguiente.style.opacity = '1';
                    btnSiguiente.classList.add('enabled');
                } else {
                    btnSiguiente.disabled = true;
                    btnSiguiente.style.opacity = '0.5';
                    btnSiguiente.classList.remove('enabled');
                }
            }
            
            direccion.addEventListener('input', validarFormulario);
            
            // Contador de caracteres visual
            direccion.addEventListener('input', function() {
                const length = this.value.trim().length;
                if (length < 10) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#28a745';
                }
            });
            
            // Validar formulario inicial
            validarFormulario();
        });
    </script>

</body>
