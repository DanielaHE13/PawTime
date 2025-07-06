<?php
if ($_SESSION["rol"] != "propietario") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

require_once(__DIR__ . "/../../logica/Perro.php");

$idPropietario = $_SESSION["id"];
$misPerros = Perro::listarPorPropietario($idPropietario);

$mensaje = "";
$tipoMensaje = "";
$errores = array();

// Procesar selección de mascotas
if ($_POST && isset($_POST["siguiente_paso"])) {
    if (!isset($_POST["perros"]) || empty($_POST["perros"])) {
        $errores[] = "Debe seleccionar al menos una mascota para el paseo";
    } else {
        $perrosSeleccionados = $_POST["perros"];
        if (count($perrosSeleccionados) > 2) {
            $errores[] = "Solo puede seleccionar máximo 2 mascotas por paseo";
        } else {
            // Guardar en sesión y pasar al siguiente paso
            $_SESSION["paseo_mascotas"] = $perrosSeleccionados;
            header("Location: ?pid=" . base64_encode("presentacion/propietario/programarPaseo_Paso2.php"));
            exit;
        }
    }
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
                    <a href="?pid=<?php echo base64_encode('presentacion/sesionPropietario.php'); ?>" class="btn" style="background-color: #4b0082; color: white; border-radius: 12px;">
                        <i class="fas fa-arrow-left me-2"></i>Cancelar
                    </a>
                </div>

                <!-- Indicador de pasos -->
                <div class="card mb-4" style="border-radius: 15px; border: none; background: rgba(255, 255, 255, 0.9);">
                    <div class="card-body py-3">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="step-indicator active">
                                    <div class="step-circle">1</div>
                                    <small class="fw-bold text-primary">Mascotas</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="step-indicator">
                                    <div class="step-circle">2</div>
                                    <small>Fecha & Hora</small>
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
                            <i class="fas fa-dog me-2"></i>Paso 1: Selecciona tus Mascotas
                        </h2>
                        <p class="text-white-50 mb-0">¿Quién va a disfrutar del paseo hoy? </p>
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
                            
                            <?php if (empty($misPerros)): ?>
                                <!-- Sin mascotas -->
                                <div class="text-center py-5">
                                    <div>
                                        <i class="fas fa-heart-broken fa-4x text-muted mb-4"></i>
                                        <h4 class="text-muted mb-3">¡Oops! No tienes mascotas registradas</h4>
                                        <p class="text-muted mb-4">Primero necesitas agregar una mascota para poder programar un paseo</p>
                                        <a href="?pid=<?php echo base64_encode('presentacion/propietario/agregarMascota.php'); ?>" class="btn text-white fw-bold btn-lg" style="background-color: #4b0082; border-radius: 15px;">
                                            <i class="fas fa-plus me-2"></i>¡Agregar mi Primera Mascota!
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Selección de mascotas -->
                                <div class="mb-4">
                                    <div class="alert alert-info" style="border-radius: 15px; background: #6a0dad;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle fa-2x me-3" style="color:rgb(255, 255, 255);"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1" style="color:rgb(255, 255, 255);">Puedes seleccionar maximo 2 mascotas para el mismo paseo</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <?php foreach ($misPerros as $index => $perro): ?>
                                        <div class="col-md-4 col-lg-3 mb-4">
                                            <div class="mascota-card">
                                                <input type="checkbox" class="perro-checkbox d-none" 
                                                       name="perros[]" value="<?php echo $perro->getId(); ?>" 
                                                       id="perro<?php echo $perro->getId(); ?>">
                                                <label class="mascota-label" for="perro<?php echo $perro->getId(); ?>">
                                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 20px; cursor: pointer; height: 200px;">
                                                        <div class="card-body text-center p-4 d-flex flex-column justify-content-center">
                                                            <div class="position-relative mb-3">
                                                                <img src="imagen/perros/<?php echo $perro->getFoto(); ?>" 
                                                                     alt="<?php echo $perro->getNombre(); ?>"
                                                                     class="rounded-circle shadow"
                                                                     style="width: 80px; height: 80px; object-fit: cover; border: 4px solid #E3CFF5;">
                                                                <div class="check-overlay">
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                            </div>
                                                            <h6 class="fw-bold mb-0" style="color: #4b0082; font-size: 14px;"><?php echo $perro->getNombre(); ?></h6>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Contador de seleccionados -->
                                <div class="text-center mb-4">
                                    <div id="contador-mascotas" class="badge bg-primary fs-6 py-2 px-3" style="border-radius: 20px;">
                                        <i class="fas fa-dog me-2"></i>0 mascotas seleccionadas
                                    </div>
                                </div>

                                <!-- Botón siguiente -->
                                <div class="text-center">
                                    <button type="submit" name="siguiente_paso" id="btn-siguiente" class="btn text-white fw-bold btn-lg px-5 py-3" 
                                            style="background-color: #4b0082; border-radius: 20px; display: none;" disabled>
                                        <i class="fas fa-arrow-right me-2"></i>¡Siguiente Paso!
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
        
        .step-indicator.active small {
            color: #4b0082 !important;
            font-weight: bold;
        }
        
        .mascota-label:hover .card {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(75, 0, 130, 0.2) !important;
        }
        
        .perro-checkbox:checked + .mascota-label .card {
            border: 3px solid #4b0082 !important;
            background: linear-gradient(135deg, #f8f0fc, #e3cff5);
            transform: translateY(-3px);
        }
        
        .check-overlay {
            position: absolute;
            top: -5px;
            right: 5px;
            width: 30px;
            height: 30px;
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
        
        .perro-checkbox:checked + .mascota-label .check-overlay {
            opacity: 1;
            transform: scale(1);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.perro-checkbox');
            const contador = document.getElementById('contador-mascotas');
            const btnSiguiente = document.getElementById('btn-siguiente');
            
            function actualizarContador() {
                const checkedBoxes = document.querySelectorAll('.perro-checkbox:checked');
                const count = checkedBoxes.length;
                
                // Actualizar contador
                if (count === 0) {
                    contador.textContent = '0 mascotas seleccionadas';
                    contador.className = 'badge bg-secondary fs-6 py-2 px-3';
                } else if (count === 1) {
                    contador.innerHTML = '<i class="fas fa-dog me-2"></i>1 mascota seleccionada ¡Perfecto!';
                    contador.className = 'badge bg-secondary fs-6 py-2 px-3';
                } else if (count === 2) {
                    contador.innerHTML = '<i class="fas fa-heart me-2"></i>2 mascotas ¡Será súper divertido!';
                    contador.className = 'badge bg-secondary fs-6 py-2 px-3';
                }
                
                // Mostrar/ocultar botón siguiente
                if (count > 0) {
                    btnSiguiente.style.display = 'inline-block';
                    btnSiguiente.disabled = false;
                } else {
                    btnSiguiente.style.display = 'none';
                    btnSiguiente.disabled = true;
                }
            }
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedBoxes = document.querySelectorAll('.perro-checkbox:checked');
                    
                    // Limitar a máximo 2 mascotas
                    if (checkedBoxes.length > 2) {
                        this.checked = false;
                        
                        // Mostrar mensaje
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-warning alert-dismissible fade show';
                        alert.style.position = 'fixed';
                        alert.style.top = '20px';
                        alert.style.right = '20px';
                        alert.style.zIndex = '9999';
                        alert.style.maxWidth = '350px';
                        alert.innerHTML = `
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ¡Ups! Solo puedes llevar máximo 2 amiguitos por paseo 🐕🐕
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.body.appendChild(alert);
                        
                        // Auto-remover después de 3 segundos
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 3000);
                    }
                    
                    actualizarContador();
                });
            });
            
            // Efecto de hover en las cards
            document.querySelectorAll('.mascota-label').forEach(label => {
                label.addEventListener('mouseenter', function() {
                    this.querySelector('.card').style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                label.addEventListener('mouseleave', function() {
                    const checkbox = this.previousElementSibling;
                    if (checkbox.checked) {
                        this.querySelector('.card').style.transform = 'translateY(-3px) scale(1)';
                    } else {
                        this.querySelector('.card').style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
        });
    </script>

</body>