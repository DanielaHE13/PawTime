<?php
if ($_SESSION["rol"] != "propietario") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

// Verificar que vengan los datos de los pasos anteriores
if (!isset($_SESSION["paseo_mascotas"]) || empty($_SESSION["paseo_mascotas"]) ||
    !isset($_SESSION["paseo_fecha"]) || empty($_SESSION["paseo_fecha"]) ||
    !isset($_SESSION["paseo_hora"]) || empty($_SESSION["paseo_hora"]) ||
    !isset($_SESSION["paseo_duracion"]) || empty($_SESSION["paseo_duracion"])) {
    header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo.php"));
    exit;
}

require_once(__DIR__ . "/../../logica/Perro.php");
require_once(__DIR__ . "/../../logica/Paseador.php");
require_once(__DIR__ . "/../../logica/Paseo.php");

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

// Obtener todos los paseadores activos
$paseador = new Paseador();
$todosPaseadores = $paseador->consultarTodos();

// Filtrar solo paseadores activos (estado = 1)
$paseadoresActivos = array_filter($todosPaseadores, function($p) {
    return $p->getEstado() == 1;
});

// Filtros aplicados
$filtroTarifa = $_GET['tarifa'] ?? 'todos';
$filtroOrden = $_GET['orden'] ?? 'precio_asc';

// Aplicar filtro de tarifa
$paseadoresFiltrados = $paseadoresActivos;
if ($filtroTarifa != 'todos') {
    switch ($filtroTarifa) {
        case 'economico': // Menos de $15000
            $paseadoresFiltrados = array_filter($paseadoresFiltrados, function($p) {
                return $p->getTarifa() < 15000;
            });
            break;
        case 'medio': // Entre $15000 y $25000
            $paseadoresFiltrados = array_filter($paseadoresFiltrados, function($p) {
                return $p->getTarifa() >= 15000 && $p->getTarifa() <= 25000;
            });
            break;
        case 'premium': // Más de $25000
            $paseadoresFiltrados = array_filter($paseadoresFiltrados, function($p) {
                return $p->getTarifa() > 25000;
            });
            break;
    }
}

// Ordenar paseadores
switch ($filtroOrden) {
    case 'precio_asc':
        usort($paseadoresFiltrados, function($a, $b) {
            return $a->getTarifa() - $b->getTarifa();
        });
        break;
    case 'precio_desc':
        usort($paseadoresFiltrados, function($a, $b) {
            return $b->getTarifa() - $a->getTarifa();
        });
        break;
    case 'nombre':
        usort($paseadoresFiltrados, function($a, $b) {
            return strcmp($a->getNombre(), $b->getNombre());
        });
        break;
}

// Procesar selección de paseador
if ($_POST && isset($_POST["siguiente_paso"])) {
    $paseadorSeleccionado = trim($_POST["paseador"] ?? "");
    
    if (empty($paseadorSeleccionado)) {
        $errores[] = "Debe seleccionar un paseador para el paseo";
    } else {
        // Verificar que el paseador existe y está activo
        $paseadorValido = false;
        foreach ($paseadoresActivos as $p) {
            if ($p->getId() == $paseadorSeleccionado) {
                $paseadorValido = true;
                $_SESSION["paseo_paseador"] = $paseadorSeleccionado;
                break;
            }
        }
        
        if (!$paseadorValido) {
            $errores[] = "El paseador seleccionado no está disponible";
        }
    }
    
    if (empty($errores)) {
        header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo_Paso4.php"));
        exit;
    }
}

// Si viene del botón "Atrás"
if ($_POST && isset($_POST["paso_anterior"])) {
    header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo_Paso2.php"));
    exit;
}

include("presentacion/encabezado.php");
include("presentacion/menuPropietario.php");
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                
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
                                <div class="step-indicator active">
                                    <div class="step-circle">3</div>
                                    <small class="fw-bold text-primary">Paseador</small>
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
                            <i class="fas fa-user-tie me-2"></i>Paso 3: Elige tu Paseador Perfecto
                        </h2>
                        <p class="text-white-50 mb-0">Encuentra al compañero ideal para la aventura </p>
                    </div>

                    <div class="card-body p-4">
                        
                        <!-- Filtros -->
                        <div class="card mb-4" style="border-radius: 15px; background: linear-gradient(45deg, #f8f9fa, #e9ecef);">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-filter me-2" style="color: #4b0082;"></i>
                                            <label class="form-label mb-0 me-3 fw-bold">Filtrar por precio:</label>
                                            <select class="form-select form-select-sm" id="filtroTarifa" style="width: auto; border-radius: 10px;">
                                                <option value="todos" <?php echo ($filtroTarifa == 'todos') ? 'selected' : ''; ?>>Todos los precios</option>
                                                <option value="economico" <?php echo ($filtroTarifa == 'economico') ? 'selected' : ''; ?>>Económico (< $15.000)</option>
                                                <option value="medio" <?php echo ($filtroTarifa == 'medio') ? 'selected' : ''; ?>>Intermedio ($15.000 - $25.000)</option>
                                                <option value="premium" <?php echo ($filtroTarifa == 'premium') ? 'selected' : ''; ?>>Premium (> $25.000)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-sort me-2" style="color: #4b0082;"></i>
                                            <label class="form-label mb-0 me-3 fw-bold">Ordenar por:</label>
                                            <select class="form-select form-select-sm" id="filtroOrden" style="width: auto; border-radius: 10px;">
                                                <option value="precio_asc" <?php echo ($filtroOrden == 'precio_asc') ? 'selected' : ''; ?>>Precio menor a mayor</option>
                                                <option value="precio_desc" <?php echo ($filtroOrden == 'precio_desc') ? 'selected' : ''; ?>>Precio mayor a menor</option>
                                                <option value="nombre" <?php echo ($filtroOrden == 'nombre') ? 'selected' : ''; ?>>Nombre A-Z</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                            
                            <?php if (empty($paseadoresFiltrados)): ?>
                                <!-- Sin paseadores disponibles -->
                                <div class="text-center py-5">
                                    <div>
                                        <i class="fas fa-search fa-4x text-muted mb-4"></i>
                                        <h4 class="text-muted mb-3">No hay paseadores disponibles</h4>
                                        <p class="text-muted mb-4">Intenta ajustar los filtros o seleccionar otra fecha</p>
                                        <button type="button" class="btn btn-outline-primary" onclick="resetFiltros()">
                                            <i class="fas fa-redo me-2"></i>Limpiar Filtros
                                        </button>
                                    </div>
                                </div>
                            <?php else: ?>
                                
                                <!-- Información sobre resultados -->
                                <div class="alert alert-info mb-4" style="border-radius: 15px; background: linear-gradient(45deg, #f3e5f5, #e1bee7);">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle fa-2x me-3" style="color: #4b0082;"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1" style="color: #4b0082;">Paseadores disponibles</h6>
                                            <small style="color: #6a0dad;">Encontramos <?php echo count($paseadoresFiltrados); ?> paseador(es)  disponibles para tu fecha y hora</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lista de paseadores -->
                                <div class="row">
                                    <?php foreach ($paseadoresFiltrados as $index => $paseadorItem): ?>
                                        <div class="col-lg-6 mb-4">
                                            <div class="paseador-card">
                                                <input type="radio" class="paseador-radio d-none" 
                                                       name="paseador" value="<?php echo $paseadorItem->getId(); ?>" 
                                                       id="paseador<?php echo $paseadorItem->getId(); ?>">
                                                <label class="paseador-label" for="paseador<?php echo $paseadorItem->getId(); ?>">
                                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 20px; transition: all 0.3s ease; cursor: pointer;">
                                                        <div class="card-body p-4 text-center">
                                                            <div class="position-relative d-inline-block mb-3">
                                                                <img src="imagen/paseador.png" 
                                                                     alt="<?php echo $paseadorItem->getNombre(); ?>"
                                                                     class="rounded-circle shadow"
                                                                     style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #E3CFF5;">
                                                                <div class="check-overlay-paseador">
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                            </div>
                                                            <h5 class="fw-bold mb-3" style="color: #4b0082;">
                                                                <?php echo $paseadorItem->getNombre() . " " . $paseadorItem->getApellido(); ?>
                                                            </h5>
                                                            <div class="precio-badge">
                                                                <span class="h4 fw-bold text-success mb-0">
                                                                    $<?php echo number_format($paseadorItem->getTarifa(), 0, ',', '.'); ?>
                                                                </span>
                                                                <small class="text-muted d-block">por hora</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Botón siguiente -->
                                <div class="text-center mt-4">
                                    <button type="submit" name="siguiente_paso" id="btn-siguiente" class="btn text-white fw-bold btn-lg px-5 py-3" 
                                            style="background-color: #4b0082; border-radius: 20px; opacity: 0.5;" disabled>
                                        <i class="fas fa-map-marker-alt me-2"></i>¡Confirmar Dirección!
                                    </button>
                                </div>
                            <?php endif; ?>

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

        .paseador-label:hover .card {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(75, 0, 130, 0.2) !important;
        }
        
        .paseador-radio:checked + .paseador-label .card {
            border: 3px solid #4b0082 !important;
            background: linear-gradient(135deg, #f8f0fc, #e3cff5);
            transform: translateY(-3px);
        }
        
        .check-overlay-paseador {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 25px;
            height: 25px;
            background-color: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
        }
        
        .paseador-radio:checked + .paseador-label .check-overlay-paseador {
            opacity: 1;
            transform: scale(1);
        }

        #btn-siguiente.enabled {
            opacity: 1 !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.paseador-radio');
            const btnSiguiente = document.getElementById('btn-siguiente');
            const filtroTarifa = document.getElementById('filtroTarifa');
            const filtroOrden = document.getElementById('filtroOrden');
            
            function validarSeleccion() {
                const seleccionado = document.querySelector('.paseador-radio:checked');
                
                if (seleccionado) {
                    btnSiguiente.disabled = false;
                    btnSiguiente.style.opacity = '1';
                    btnSiguiente.classList.add('enabled');
                } else {
                    btnSiguiente.disabled = true;
                    btnSiguiente.style.opacity = '0.5';
                    btnSiguiente.classList.remove('enabled');
                }
            }
            
            radios.forEach(radio => {
                radio.addEventListener('change', validarSeleccion);
            });
            
            // Filtros en tiempo real
            function aplicarFiltros() {
                const tarifa = filtroTarifa.value;
                const orden = filtroOrden.value;
                const url = new URL(window.location);
                url.searchParams.set('tarifa', tarifa);
                url.searchParams.set('orden', orden);
                window.location.href = url.toString();
            }
            
            if (filtroTarifa) {
                filtroTarifa.addEventListener('change', aplicarFiltros);
            }
            if (filtroOrden) {
                filtroOrden.addEventListener('change', aplicarFiltros);
            }
            
            // Efectos hover en las cards
            document.querySelectorAll('.paseador-label').forEach(label => {
                label.addEventListener('mouseenter', function() {
                    this.querySelector('.card').style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                label.addEventListener('mouseleave', function() {
                    const radio = this.previousElementSibling;
                    if (radio.checked) {
                        this.querySelector('.card').style.transform = 'translateY(-3px) scale(1)';
                    } else {
                        this.querySelector('.card').style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
            
            // Validar selección inicial
            validarSeleccion();
        });
        
        function resetFiltros() {
            const url = new URL(window.location);
            url.searchParams.delete('tarifa');
            url.searchParams.delete('orden');
            window.location.href = url.toString();
        }
    </script>

</body>
