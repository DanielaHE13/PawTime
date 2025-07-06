<?php
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

$id = $_SESSION["id"];
$paseador = new Paseador($id);
$paseador->consultar();

// Procesar formulario cuando se envía
if ($_POST) {
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $tarifa = trim($_POST["tarifa"]);
    $estado = $_POST["estado"];
    
    // Validaciones básicas
    $errores = array();
    
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio";
    }
    
    if (empty($apellido)) {
        $errores[] = "El apellido es obligatorio";
    }
    
    if (empty($telefono)) {
        $errores[] = "El teléfono es obligatorio";
    } elseif (!preg_match("/^[0-9]{10}$/", $telefono)) {
        $errores[] = "El teléfono debe tener 10 dígitos";
    }
    
    if (empty($correo)) {
        $errores[] = "El correo es obligatorio";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo no es válido";
    }
    
    if (empty($tarifa)) {
        $errores[] = "La tarifa es obligatoria";
    } elseif (!is_numeric($tarifa) || $tarifa < 0) {
        $errores[] = "La tarifa debe ser un número válido mayor o igual a 0";
    }
    
    if (empty($estado)) {
        $errores[] = "El estado es obligatorio";
    }
    
    // Si no hay errores, actualizar los datos
    if (empty($errores)) {
        $paseadorActualizado = new Paseador(
            $id,
            $nombre,
            $apellido,
            $telefono,
            $correo,
            "", // No se actualiza la clave aquí
            $paseador->getFoto(), // Mantener la foto actual
            $tarifa,
            $estado
        );
        
        try {
            $paseadorActualizado->editarPerfil();
            $mensaje = "Perfil actualizado exitosamente";
            $tipoMensaje = "success";
            // Actualizar los datos en el objeto para mostrar los cambios
            $paseador = $paseadorActualizado;
            // Recargar los datos desde la base de datos para asegurar consistencia
            $paseador->consultar();
        } catch (Exception $e) {
            $errores[] = "Error al actualizar el perfil: " . $e->getMessage();
        }
    }
}

include("presentacion/encabezado.php"); 
include("presentacion/menuPaseador.php"); 
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                
                <!-- Botón de regreso -->
                <div class="mb-3">
                    <a href="?pid=<?php echo base64_encode('presentacion/paseador/sesionPaseador.php'); ?>" class="btn" style="background-color: #4b0082; color: white; border-radius: 12px;">
                        <i class="fas fa-arrow-left me-2"></i>Regresar
                    </a>
                </div>

                <!-- Tarjeta principal -->
                <div class="card shadow-lg" style="border-radius: 20px; border: 2px solid #E3CFF5;">
                    
                    <!-- Header de la tarjeta -->
                    <div class="card-header text-white text-center py-4" style="background-color: #4b0082; border-top-left-radius: 18px; border-top-right-radius: 18px;">
                        <h3 class="mb-0">
                            <i class="fas fa-user-edit me-3"></i>Editar Mi Perfil
                        </h3>
                        <p class="mb-0 mt-2 opacity-75">Actualiza tu información profesional</p>
                    </div>

                    <!-- Cuerpo de la tarjeta -->
                    <div class="card-body p-4" style="background-color: #fefefe;">
                        
                        <!-- Mostrar mensajes de éxito -->
                        <?php if (isset($mensaje)): ?>
                            <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?php echo $mensaje; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Mostrar errores -->
                        <?php if (!empty($errores)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Por favor corrige los siguientes errores:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errores as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario -->
                        <form method="post" class="needs-validation" novalidate>
                            
                            <!-- Nombre y Apellido -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-user me-2"></i>Nombre *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="<?php echo htmlspecialchars($paseador->getNombre()); ?>"
                                           style="border: 2px solid #E3CFF5; border-radius: 10px;"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-user me-2"></i>Apellido *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="apellido" 
                                           name="apellido" 
                                           value="<?php echo htmlspecialchars($paseador->getApellido()); ?>"
                                           style="border: 2px solid #E3CFF5; border-radius: 10px;"
                                           required>
                                </div>
                            </div>

                            <!-- Teléfono y Correo -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-phone me-2"></i>Teléfono *
                                    </label>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="telefono" 
                                           name="telefono" 
                                           value="<?php echo htmlspecialchars($paseador->getTelefono()); ?>"
                                           style="border: 2px solid #E3CFF5; border-radius: 10px;"
                                           placeholder="Ej: 3001234567"
                                           maxlength="10"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="correo" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-envelope me-2"></i>Correo Electrónico *
                                    </label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="correo" 
                                           name="correo" 
                                           value="<?php echo htmlspecialchars($paseador->getCorreo()); ?>"
                                           style="border: 2px solid #E3CFF5; border-radius: 10px;"
                                           required>
                                </div>
                            </div>

                            <!-- Tarifa y Estado -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="tarifa" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-dollar-sign me-2"></i>Tarifa por Paseo *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background-color: #E3CFF5; border: 2px solid #E3CFF5;">$</span>
                                        <input type="number" 
                                               class="form-control" 
                                               id="tarifa" 
                                               name="tarifa" 
                                               value="<?php echo htmlspecialchars($paseador->getTarifa()); ?>"
                                               style="border: 2px solid #E3CFF5; border-left: none; border-radius: 0 10px 10px 0;"
                                               min="0"
                                               step="1000"
                                               required>
                                    </div>
                                    <small class="text-muted">Ingresa tu tarifa en pesos colombianos</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="estado" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-toggle-on me-2"></i>Estado *
                                    </label>
                                    <select class="form-select" 
                                            id="estado" 
                                            name="estado" 
                                            style="border: 2px solid #E3CFF5; border-radius: 10px;"
                                            required>
                                        <option value="1" <?php echo ($paseador->getEstado() == 1) ? 'selected' : ''; ?>>Activo</option>
                                        <option value="0" <?php echo ($paseador->getEstado() == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                    </select>
                                    <small class="text-muted">Tu disponibilidad para recibir solicitudes</small>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="alert" style="border-radius: 15px; border: 2px solid #E3CFF5; background-color: #f8f0fc; color: #4b0082;">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Información importante:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Mantén tus datos actualizados para generar confianza en los propietarios</li>
                                    <li>Tu estado "Activo" significa que recibirás solicitudes de paseo</li>
                                    <li>La tarifa que establezcas será visible para los propietarios</li>
                                </ul>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex gap-3 justify-content-center mt-4">
                                <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: #4b0082; border-radius: 12px;">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                                <a href="?pid=<?php echo base64_encode('presentacion/paseador/sesionPaseador.php'); ?>" 
                                   class="btn fw-bold px-4 py-2"
                                   style="background-color: #E3CFF5; color: #4b0082; border-radius: 12px;">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para validación del formulario -->
    <script>
        // Validación del teléfono en tiempo real
        document.getElementById('telefono').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Solo números
            e.target.value = value;
            
            if (value.length !== 10) {
                e.target.setCustomValidity('El teléfono debe tener exactamente 10 dígitos');
            } else {
                e.target.setCustomValidity('');
            }
        });

        // Validación de tarifa
        document.getElementById('tarifa').addEventListener('input', function(e) {
            let value = parseFloat(e.target.value);
            
            if (isNaN(value) || value < 0) {
                e.target.setCustomValidity('La tarifa debe ser un número válido mayor o igual a 0');
            } else {
                e.target.setCustomValidity('');
            }
        });

        // Validación general del formulario
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
