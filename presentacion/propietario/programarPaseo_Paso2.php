<?php
if ($_SESSION["rol"] != "propietario") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

// Verificar que vengan las mascotas del paso anterior
if (!isset($_SESSION["paseo_mascotas"]) || empty($_SESSION["paseo_mascotas"])) {
    header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo.php"));
    exit;
}

require_once(__DIR__ . "/../../logica/Perro.php");

$mensaje = "";
$tipoMensaje = "";
$errores = array();

// Obtener datos de las mascotas seleccionadas
$mascotasSeleccionadas = array();
foreach ($_SESSION["paseo_mascotas"] as $idPerro) {
    $perro = new Perro($idPerro);
    $perro->consultar();
    $mascotasSeleccionadas[] = $perro;
}

// Procesar formulario
if ($_POST && isset($_POST["siguiente_paso"])) {
    $fecha = trim($_POST["fecha"] ?? "");
    $hora = trim($_POST["hora"] ?? "");
    $duracion = trim($_POST["duracion"] ?? "");

    // Validaciones
    if (empty($fecha)) {
        $errores[] = "Debe seleccionar una fecha para el paseo";
    } else {
        $fechaSeleccionada = new DateTime($fecha);
        $hoy = new DateTime();
        $hoy->setTime(0, 0, 0);

        if ($fechaSeleccionada < $hoy) {
            $errores[] = "No puedes programar un paseo en el pasado";
        }

        if ($fechaSeleccionada > (new DateTime())->add(new DateInterval('P30D'))) {
            $errores[] = "Solo puedes programar paseos con máximo 30 días de anticipación";
        }
    }

    if (empty($hora)) {
        $errores[] = "Debe seleccionar una hora para el paseo";
    } else {
        // Validar horario de servicio (6 AM a 8 PM)
        $horaObj = DateTime::createFromFormat('H:i', $hora);
        $inicio = DateTime::createFromFormat('H:i', '06:00');
        $fin = DateTime::createFromFormat('H:i', '20:00');

        if ($horaObj < $inicio || $horaObj > $fin) {
            $errores[] = "El horario de servicio es de 6:00 AM a 8:00 PM";
        }
    }

    if (empty($duracion)) {
        $errores[] = "Debe seleccionar la duración del paseo";
    }

    if (empty($errores)) {
        // Guardar en sesión y continuar
        $_SESSION["paseo_fecha"] = $fecha;
        $_SESSION["paseo_hora"] = $hora;
        $_SESSION["paseo_duracion"] = $duracion;
        header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo_Paso3.php"));
        exit;
    }
}

// Si viene del botón "Atrás"
if ($_POST && isset($_POST["paso_anterior"])) {
    header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo.php"));
    exit;
}

include("presentacion/encabezado.php");
include("presentacion/menuPropietario.php");
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">

                <!-- Botón de regreso -->
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
                                    <div class="step-circle">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <small class="fw-bold text-success">Mascotas</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="step-indicator active">
                                    <div class="step-circle">2</div>
                                    <small class="fw-bold text-primary">Fecha & Hora</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="step-indicator">
                                    <div class="step-circle">3</div>
                                    <small>Paseador</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="step-indicator">
                                    <div class="step-circle">4</div>
                                    <small>Confirmar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta principal -->
                <div class="card shadow-lg" style="border-radius: 20px; border: none; background: rgba(255, 255, 255, 0.95);">
                    <div class="card-header text-center py-4" style="background: linear-gradient(45deg, #4b0082, #6a0dad); border-radius: 20px 20px 0 0; border: none;">
                        <h2 class="fw-bold text-white mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>Paso 2: ¿Cuándo será la aventura?
                        </h2>
                        <p class="text-white-50 mb-0">Elige el día y hora perfectos para el paseo 🕐</p>
                    </div>

                    <div class="card-body p-4">

                        <!-- Mensajes de error -->
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

                        <form method="post" novalidate>

                            <!-- Información útil -->
                            <div class="alert alert-info mb-4" style="border-radius: 15px; background: linear-gradient(45deg, #f3e5f5, #e1bee7);">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-lightbulb fa-2x me-3" style="color: #4b0082;"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #4b0082;">Horario de servicio</h6>
                                        <small style="color: #6a0dad;">Disponible de 6:00 AM a 8:00 PM • Máximo 30 días de anticipación</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Selección de fecha -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-3" style="color: #4b0082;">
                                            <i class="fas fa-calendar me-2"></i>¿Qué día?
                                        </label>
                                        <input type="date"
                                            class="form-control form-control-lg"
                                            name="fecha"
                                            id="fecha"
                                            min="<?php echo date('Y-m-d'); ?>"
                                            max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"
                                            value="<?php echo $_POST['fecha'] ?? ''; ?>"
                                            style="border-radius: 15px; border: 2px solid #E3CFF5; padding: 15px;">
                                    </div>
                                </div>

                                <!-- Selección de hora -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-3" style="color: #4b0082;">
                                            <i class="fas fa-clock me-2"></i>¿A qué hora?
                                        </label>
                                        <select class="form-select form-select-lg"
                                            name="hora"
                                            id="hora"
                                            style="border-radius: 15px; border: 2px solid #E3CFF5; padding: 15px;">
                                            <option value="">Selecciona una hora</option>
                                            <?php for ($h = 6; $h <= 20; $h++): ?>
                                                <?php for ($m = 0; $m < 60; $m += 30): ?>
                                                    <?php
                                                    $hora_valor = sprintf('%02d:%02d', $h, $m);
                                                    $hora_texto = date('g:i A', strtotime($hora_valor));
                                                    $selected = (($_POST['hora'] ?? '') == $hora_valor) ? 'selected' : '';
                                                    ?>
                                                    <option value="<?php echo $hora_valor; ?>" <?php echo $selected; ?>>
                                                        <?php echo $hora_texto; ?>
                                                    </option>
                                                <?php endfor; ?>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Duración del paseo -->
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-3" style="color: #4b0082;">
                                    <i class="fas fa-stopwatch me-2"></i>¿Cuánto tiempo de diversión?
                                </label>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <input type="radio" class="btn-check" name="duracion" value="60" id="duracion60" <?php echo (($_POST['duracion'] ?? '') == '60') ? 'checked' : ''; ?>>
                                        <label class="btn btn-outline-primary w-100 py-3" for="duracion60" style="border-radius: 15px; border-width: 2px;">
                                            <i class="fas fa-walking me-2"></i>
                                            <div class="fw-bold">1 hora</div>

                                        </label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <input type="radio" class="btn-check" name="duracion" value="120" id="duracion120" <?php echo (($_POST['duracion'] ?? '') == '120') ? 'checked' : ''; ?>>
                                        <label class="btn btn-outline-primary w-100 py-3" for="duracion120" style="border-radius: 15px; border-width: 2px;">
                                            <i class="fas fa-running me-2"></i>
                                            <div class="fw-bold">2 horas</div>

                                        </label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <input type="radio" class="btn-check" name="duracion" value="180" id="duracion180" <?php echo (($_POST['duracion'] ?? '') == '180') ? 'checked' : ''; ?>>
                                        <label class="btn btn-outline-primary w-100 py-3" for="duracion180" style="border-radius: 15px; border-width: 2px;">
                                            <i class="fas fa-heart me-2"></i>
                                            <div class="fw-bold">3 horas</div>

                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de navegación -->
                            <div class="text-center mt-4">
                                <button type="submit" name="siguiente_paso" id="btn-siguiente" class="btn text-white fw-bold btn-lg px-5 py-3"
                                    style="background-color: #4b0082; border-radius: 20px; opacity: 0.5;" disabled>
                                    <i class="fas fa-arrow-right me-2"></i>¡Buscar Paseadores!
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

        .form-control:focus,
        .form-select:focus {
            border-color: #4b0082;
            box-shadow: 0 0 0 0.2rem rgba(75, 0, 130, 0.25);
        }

        .btn-check:checked+.btn-outline-primary {
            background-color: #4b0082;
            border-color: #4b0082;
            color: white;
            transform: scale(1.05);
        }

        .btn-outline-primary {
            border-color: #E3CFF5;
            color: #4b0082;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #f8f0fc;
            border-color: #4b0082;
            color: #4b0082;
            transform: translateY(-2px);
        }

        #btn-siguiente.enabled {
            opacity: 1 !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fecha = document.getElementById('fecha');
            const hora = document.getElementById('hora');
            const duracionInputs = document.querySelectorAll('input[name="duracion"]');
            const btnSiguiente = document.getElementById('btn-siguiente');

            function validarFormulario() {
                const fechaValida = fecha.value !== '';
                const horaValida = hora.value !== '';
                const duracionValida = document.querySelector('input[name="duracion"]:checked') !== null;

                if (fechaValida && horaValida && duracionValida) {
                    btnSiguiente.disabled = false;
                    btnSiguiente.style.opacity = '1';
                    btnSiguiente.classList.add('enabled');
                } else {
                    btnSiguiente.disabled = true;
                    btnSiguiente.style.opacity = '0.5';
                    btnSiguiente.classList.remove('enabled');
                }
            }

            fecha.addEventListener('change', validarFormulario);
            hora.addEventListener('change', validarFormulario);
            duracionInputs.forEach(input => {
                input.addEventListener('change', validarFormulario);
            });

            // Validar fecha mínima en tiempo real
            fecha.addEventListener('change', function() {
                const fechaSeleccionada = new Date(this.value);
                const hoy = new Date();
                hoy.setHours(0, 0, 0, 0);

                if (fechaSeleccionada < hoy) {
                    this.setCustomValidity('No puedes seleccionar una fecha en el pasado');
                    this.reportValidity();
                } else {
                    this.setCustomValidity('');
                }
            });

            // Llamar validación inicial
            validarFormulario();
        });
    </script>

</body>