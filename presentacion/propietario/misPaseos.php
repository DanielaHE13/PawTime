<?php
// Verificar que el usuario esté autenticado como propietario
if ($_SESSION["rol"] != "propietario") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

$id_propietario = $_SESSION["id"];
$mensaje = "";
$tipoMensaje = "";

// Procesar cancelación de paseo
if ($_POST && isset($_POST["cancelar_paseo"]) && isset($_POST["idPaseo"])) {
    $idPaseo = $_POST["idPaseo"];
    
    try {
        // Verificar que el paseo pertenece al propietario y está programado
        $conexion = new Conexion();
        $conexion->abrir();
        
        $consulta = "SELECT p.idPaseo, e.nombre as estado, pr.idPropietario 
                    FROM paseo p 
                    JOIN perro pe ON p.Perro_idPerro = pe.idPerro
                    JOIN propietario pr ON pe.Propietario_idPropietario = pr.idPropietario
                    JOIN estado e ON p.Estado_idEstado = e.idEstado
                    WHERE p.idPaseo = $idPaseo";
        
        $conexion->ejecutar($consulta);
        $datos = $conexion->registro();
        
        if ($datos && $datos[2] == $id_propietario && $datos[1] == 'Programado') {
            // Obtener el ID del estado "Cancelado" (asumiendo que es 4)
            $consultaEstado = "SELECT idEstado FROM estado WHERE nombre = 'Cancelado'";
            $conexion->ejecutar($consultaEstado);
            $estadoCancelado = $conexion->registro();
            
            if ($estadoCancelado) {
                // Actualizar el estado del paseo
                $actualizarPaseo = "UPDATE paseo SET Estado_idEstado = " . $estadoCancelado[0] . " WHERE idPaseo = $idPaseo";
                $conexion->ejecutar($actualizarPaseo);
                
                $mensaje = "Paseo cancelado exitosamente";
                $tipoMensaje = "success";
            } else {
                $mensaje = "Error: No se pudo encontrar el estado 'Cancelado'";
                $tipoMensaje = "danger";
            }
        } else {
            $mensaje = "No tienes permisos para cancelar este paseo o el paseo no está programado";
            $tipoMensaje = "danger";
        }
        
        $conexion->cerrar();
        
    } catch (Exception $e) {
        $mensaje = "Error al cancelar el paseo: " . $e->getMessage();
        $tipoMensaje = "danger";
    }
}

// Obtener todos los paseos del propietario
require_once(__DIR__ . "/../../logica/Paseo.php");
require_once("persistencia/Conexion.php");
$paseos = Paseo::obtenerPaseosPorPropietario($id_propietario);
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    <?php
    include("presentacion/encabezado.php");
    include("presentacion/menuPropietario.php");
    ?>

    <div class="container mt-4">
        <!-- Título principal -->
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold" style="color: #4b0082;">
                    <i class="fa-solid fa-walking me-3"></i>Mis Paseos
                </h2>
                <p class="text-muted">Historial completo de paseos de tus mascotas</p>
            </div>
        </div>

        <!-- Mensajes de confirmación -->
        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?php echo $tipoMensaje === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card" style="border-radius: 15px; border: none; background-color: rgba(255,255,255,0.95); box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h5 class="mb-0" style="color: #4b0082;">
                                    <i class="fa-solid fa-filter me-2"></i>Filtrar por estado
                                </h5>
                            </div>
                            
                            <div class="col-md-4">
                                <select id="filtroEstado" class="form-select" style="border-radius: 10px; border-color: #4b0082;">
                                    <option value="">Todos los estados</option>
                                    <option value="Programado">Programados</option>
                                    <option value="En curso">En curso</option>
                                    <option value="Completado">Completados</option>
                                    <option value="Cancelado">Cancelados</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4 text-end">
                                <button id="limpiarFiltros" class="btn btn-outline-secondary me-3" style="border-radius: 10px;">
                                    <i class="fa-solid fa-eraser me-1"></i>Limpiar
                                </button>
                                <span style="color: #4b0082; font-weight: 600;">
                                    <span id="contadorPaseos"><?php echo count($paseos); ?></span> paseo(s)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de paseos -->
        <div class="row">
            <div class="col-12">
                <div id="listaPaseos">
                    <?php if (empty($paseos)): ?>
                        <div class="card text-center py-5" style="border-radius: 20px; border: none; background-color: rgba(255,255,255,0.9);">
                            <div class="card-body">
                                <i class="fa-solid fa-dog fa-4x mb-3" style="color: #4b0082; opacity: 0.5;"></i>
                                <h4 style="color: #4b0082;">¡Aún no tienes paseos!</h4>
                                <p class="text-muted mb-4">Programa tu primer paseo para que tus mascotas disfruten</p>
                                <a href="?pid=<?php echo base64_encode('presentacion/propietario/programarPaseo.php'); ?>" 
                                   class="btn btn-lg text-white fw-bold px-5 py-3" 
                                   style="background-color: #4b0082; border-radius: 15px;">
                                    <i class="fa-solid fa-calendar-plus me-2"></i>Programar Primer Paseo
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($paseos as $paseo): ?>
                            <div class="paseo-item card mb-3" 
                                 data-estado="<?php echo htmlspecialchars($paseo->getEstado()->getNombre()); ?>"
                                 style="border-radius: 15px; border: none; background-color: rgba(255,255,255,0.95); box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <!-- Información del perro -->
                                        <div class="col-md-2 text-center mb-3 mb-md-0">
                                            <img src="imagen/perros/<?php echo $paseo->getPerro()->getFoto(); ?>" 
                                                 alt="<?php echo $paseo->getPerro()->getNombre(); ?>"
                                                 class="rounded-circle shadow-sm"
                                                 style="width: 70px; height: 70px; object-fit: cover;">
                                            <h6 class="mt-2 mb-0 fw-bold" style="color: #4b0082;">
                                                <?php echo $paseo->getPerro()->getNombre(); ?>
                                            </h6>
                                            <small class="text-muted"><?php echo $paseo->getPerro()->getRaza()->getNombre(); ?></small>
                                        </div>

                                        <!-- Información del paseo -->
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-1">
                                                        <i class="fa-solid fa-calendar me-2" style="color: #4b0082;"></i>
                                                        <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($paseo->getFecha())); ?>
                                                    </p>
                                                    <p class="mb-1">
                                                        <i class="fa-solid fa-clock me-2" style="color: #4b0082;"></i>
                                                        <strong>Hora:</strong> <?php echo date('H:i', strtotime($paseo->getHora())); ?>
                                                    </p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1">
                                                        <i class="fa-solid fa-user me-2" style="color: #4b0082;"></i>
                                                        <strong>Paseador:</strong> <?php echo $paseo->getPaseador()->getNombre() . ' ' . $paseo->getPaseador()->getApellido(); ?>
                                                    </p>
                                                    <p class="mb-1">
                                                        <i class="fa-solid fa-hourglass me-2" style="color: #4b0082;"></i>
                                                        <strong>Duración:</strong> <?php echo $paseo->getDuracion(); ?> min
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Precio y estado -->
                                        <div class="col-md-2 text-center">
                                            <h4 class="mb-2 fw-bold" style="color: #4b0082;">
                                                $<?php echo number_format($paseo->getPrecio(), 0, ',', '.'); ?>
                                            </h4>
                                            <small class="text-muted">Precio total</small>
                                        </div>

                                        <!-- Estado -->
                                        <div class="col-md-2 text-center">
                                            <?php
                                            $estado = $paseo->getEstado()->getNombre();
                                            $badgeClass = '';
                                            $icon = '';
                                            
                                            switch ($estado) {
                                                case 'Programado':
                                                    $badgeClass = 'bg-warning text-dark';
                                                    $icon = 'fa-clock';
                                                    break;
                                                case 'En curso':
                                                    $badgeClass = 'bg-info text-white';
                                                    $icon = 'fa-play';
                                                    break;
                                                case 'Completado':
                                                    $badgeClass = 'bg-success text-white';
                                                    $icon = 'fa-check-circle';
                                                    break;
                                                case 'Cancelado':
                                                    $badgeClass = 'bg-danger text-white';
                                                    $icon = 'fa-times-circle';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-secondary text-white';
                                                    $icon = 'fa-question';
                                            }
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?> px-3 py-2 fw-bold" style="border-radius: 10px; font-size: 0.9em;">
                                                <i class="fa-solid <?php echo $icon; ?> me-1"></i>
                                                <?php echo $estado; ?>
                                            </span>
                                            
                                            <!-- Botón de cancelar solo para paseos programados -->
                                            <?php if ($estado === 'Programado'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger mt-2 fw-bold" 
                                                        style="border-radius: 8px; font-size: 0.8em;"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalCancelar<?php echo $paseo->getId(); ?>">
                                                    <i class="fa-solid fa-ban me-1"></i>Cancelar
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($paseo->getObservaciones())): ?>
                                                <p class="text-muted mt-2 mb-0" style="font-size: 0.85em;">
                                                    <i class="fa-solid fa-note-sticky me-1"></i>
                                                    <?php echo htmlspecialchars(substr($paseo->getObservaciones(), 0, 50)) . (strlen($paseo->getObservaciones()) > 50 ? '...' : ''); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal de confirmación para cancelar paseo -->
                            <?php if ($estado === 'Programado'): ?>
                                <div class="modal fade" id="modalCancelar<?php echo $paseo->getId(); ?>" tabindex="-1" aria-labelledby="modalCancelarLabel<?php echo $paseo->getId(); ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 20px; background-color: #f8f0fc;">
                                            <div class="modal-header text-white" style="background-color: #dc3545; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                                                <h5 class="modal-title" id="modalCancelarLabel<?php echo $paseo->getId(); ?>">
                                                    <i class="fa-solid fa-exclamation-triangle me-2"></i>¿Cancelar Paseo?
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>

                                            <div class="modal-body text-center p-4">
                                                <div class="mb-3">
                                                    <img src="imagen/perros/<?php echo $paseo->getPerro()->getFoto(); ?>" 
                                                         alt="<?php echo $paseo->getPerro()->getNombre(); ?>" 
                                                         class="rounded-circle"
                                                         style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #dc3545;">
                                                </div>
                                                <h5 style="color: #4b0082;">Paseo de <?php echo $paseo->getPerro()->getNombre(); ?></h5>
                                                <p class="fs-6 mt-3" style="color: #4b0082;">
                                                    <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($paseo->getFecha())); ?><br>
                                                    <strong>Hora:</strong> <?php echo date('H:i', strtotime($paseo->getHora())); ?><br>
                                                    <strong>Paseador:</strong> <?php echo $paseo->getPaseador()->getNombre() . ' ' . $paseo->getPaseador()->getApellido(); ?>
                                                </p>
                                                <p class="text-muted small">¿Estás segur@ de que deseas cancelar este paseo?</p>
                                                <p class="text-warning small"><strong>Esta acción no se puede deshacer.</strong></p>
                                            </div>

                                            <div class="modal-footer justify-content-center">
                                                <form method="post" style="display: inline;">
                                                    <input type="hidden" name="idPaseo" value="<?php echo $paseo->getId(); ?>">
                                                    <button type="submit" name="cancelar_paseo" class="btn text-white fw-bold px-4" style="background-color: #dc3545; border-radius: 12px;">
                                                        <i class="fa-solid fa-ban me-2"></i>Sí, Cancelar Paseo
                                                    </button>
                                                </form>
                                                <button type="button" class="btn text-white fw-bold px-4" style="background-color: #6c757d; border-radius: 12px;" data-bs-dismiss="modal">
                                                    <i class="fa-solid fa-times me-2"></i>No, Mantener
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Mensaje cuando no hay resultados filtrados -->
                <div id="sinResultados" class="card text-center py-5" style="border-radius: 20px; border: none; background-color: rgba(255,255,255,0.9); display: none;">
                    <div class="card-body">
                        <i class="fa-solid fa-search fa-4x mb-3" style="color: #4b0082; opacity: 0.5;"></i>
                        <h4 style="color: #4b0082;">No se encontraron paseos</h4>
                        <p class="text-muted">Intenta cambiar el filtro o limpiarlo para ver más resultados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón para programar nuevo paseo -->
        <?php if (!empty($paseos)): ?>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="?pid=<?php echo base64_encode('presentacion/propietario/programarPaseo.php'); ?>" 
                       class="btn btn-lg text-white fw-bold px-5 py-3" 
                       style="background-color: #4b0082; border-radius: 15px;">
                        <i class="fa-solid fa-calendar-plus me-2"></i>Programar Nuevo Paseo
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .card {
            transition: transform 0.2s ease-in-out;
        }
        
        .card:hover {
            transform: translateY(-3px);
        }
        
        .badge {
            font-size: 0.9em !important;
        }
        
        .form-select:focus {
            border-color: #4b0082;
            box-shadow: 0 0 0 0.2rem rgba(75, 0, 130, 0.25);
        }
        
        @media (max-width: 768px) {
            .card-body .row {
                text-align: center;
            }
            
            .card-body .col-md-6 .row .col-6 {
                margin-bottom: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filtroEstado = document.getElementById('filtroEstado');
            const limpiarFiltros = document.getElementById('limpiarFiltros');
            const contadorPaseos = document.getElementById('contadorPaseos');
            const sinResultados = document.getElementById('sinResultados');
            const paseoItems = document.querySelectorAll('.paseo-item');

            function aplicarFiltros() {
                const estadoSeleccionado = filtroEstado.value;
                let paseosMostrados = 0;

                paseoItems.forEach(function(item) {
                    const estadoPaseo = item.getAttribute('data-estado');
                    let mostrar = true;
                    
                    // Filtro por estado
                    if (estadoSeleccionado && estadoPaseo !== estadoSeleccionado) {
                        mostrar = false;
                    }
                    
                    if (mostrar) {
                        item.style.display = 'block';
                        paseosMostrados++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Actualizar contador
                contadorPaseos.textContent = paseosMostrados;
                
                // Mostrar/ocultar mensaje de sin resultados
                if (paseosMostrados === 0 && paseoItems.length > 0) {
                    sinResultados.style.display = 'block';
                } else {
                    sinResultados.style.display = 'none';
                }
            }

            function limpiarTodosFiltros() {
                filtroEstado.value = '';
                aplicarFiltros();
            }

            // Event listeners
            filtroEstado.addEventListener('change', aplicarFiltros);
            limpiarFiltros.addEventListener('click', limpiarTodosFiltros);

            // Auto-ocultar alertas de éxito después de 4 segundos
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-success');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 4000);
        });
    </script>
</body>
