<?php
if ($_SESSION["rol"] != "administrador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

$id = $_SESSION["id"];
$administrador = new Administrador($id);
$administrador->consultar();

// Procesar formulario cuando se envía
if ($_POST) {
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $claveActual = trim($_POST["claveActual"]);
    $claveNueva = trim($_POST["claveNueva"]);
    $confirmarClave = trim($_POST["confirmarClave"]);
    
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
    
    // Validaciones de contraseña (solo si se quiere cambiar)
    $cambiarClave = !empty($claveActual) || !empty($claveNueva) || !empty($confirmarClave);
    
    if ($cambiarClave) {
        if (empty($claveActual)) {
            $errores[] = "Debes ingresar tu contraseña actual para cambiarla";
        }
        
        if (empty($claveNueva)) {
            $errores[] = "Debes ingresar la nueva contraseña";
        } elseif (strlen($claveNueva) < 6) {
            $errores[] = "La nueva contraseña debe tener al menos 6 caracteres";
        }
        
        if ($claveNueva !== $confirmarClave) {
            $errores[] = "Las contraseñas nuevas no coinciden";
        }
        
        // Verificar contraseña actual
        if (!empty($claveActual)) {
            $adminTemp = new Administrador("", "", "", "", $administrador->getCorreo(), $claveActual);
            if (!$adminTemp->autenticar()) {
                $errores[] = "La contraseña actual es incorrecta";
            }
        }
    }
    
    // Si no hay errores, actualizar los datos
    if (empty($errores)) {
        $administradorActualizado = new Administrador(
            $id,
            $nombre,
            $apellido,
            $telefono,
            $correo,
            $cambiarClave ? $claveNueva : "" // Solo enviar la nueva clave si se va a cambiar
        );
        
        try {
            $administradorActualizado->editarPerfil();
            $mensaje = "Perfil actualizado exitosamente";
            if ($cambiarClave) {
                $mensaje .= " (incluyendo la contraseña)";
            }
            $tipoMensaje = "success";
            // Actualizar los datos en el objeto para mostrar los cambios
            $administrador = $administradorActualizado;
            // Recargar los datos desde la base de datos para asegurar consistencia
            $administrador->consultar();
        } catch (Exception $e) {
            $errores[] = "Error al actualizar el perfil: " . $e->getMessage();
        }
    }
}
include("presentacion/encabezado.php"); 
include("presentacion/menuAdministrador.php"); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - PawTime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                
                <!-- Botón de regreso -->
                <div class="mb-3">
                    <a href="?pid=<?php echo base64_encode('presentacion/sesionAdministrador.php'); ?>" class="btn" style="background-color: #4b0082; color: white; border-radius: 12px;">
                        <i class="fas fa-arrow-left me-2"></i>Regresar
                    </a>
                </div>

                <!-- Tarjeta del formulario -->
                <div class="card shadow-lg" style="border-radius: 20px; border: none; background: rgba(255, 255, 255, 0.95);">
                    <div class="card-header text-center py-4" style="background: linear-gradient(45deg, #4b0082, #6a0dad); border-radius: 20px 20px 0 0; border: none;">
                        <h2 class="fw-bold text-white mb-0">
                            <i class="fas fa-user-cog me-2"></i>Editar Mi Perfil
                        </h2>
                    </div>

                    <div class="card-body p-4">
                        
                        <!-- Mensajes -->
                        <?php if (isset($mensaje)): ?>
                            <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?php echo $mensaje; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

                        <!-- Formulario -->
                        <form method="post" novalidate>
                            
                            <!-- Nombres -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-user me-2"></i>Nombre *
                                    </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($administrador->getNombre()); ?>" 
                                           required style="border-radius: 12px; border: 2px solid #e9ecef;">
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-user me-2"></i>Apellido *
                                    </label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" 
                                           value="<?php echo htmlspecialchars($administrador->getApellido()); ?>" 
                                           required style="border-radius: 12px; border: 2px solid #e9ecef;">
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="mb-3">
                                <label for="telefono" class="form-label fw-bold" style="color: #4b0082;">
                                    <i class="fas fa-phone me-2"></i>Teléfono *
                                </label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" 
                                       value="<?php echo htmlspecialchars($administrador->getTelefono()); ?>" 
                                       required pattern="[0-9]{10}" maxlength="10"
                                       style="border-radius: 12px; border: 2px solid #e9ecef;"
                                       placeholder="Ej: 3001234567">
                                <div class="form-text">Ingresa 10 dígitos sin espacios ni guiones</div>
                            </div>

                            <!-- Correo -->
                            <div class="mb-3">
                                <label for="correo" class="form-label fw-bold" style="color: #4b0082;">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico *
                                </label>
                                <input type="email" class="form-control" id="correo" name="correo" 
                                       value="<?php echo htmlspecialchars($administrador->getCorreo()); ?>" 
                                       required style="border-radius: 12px; border: 2px solid #e9ecef;"
                                       placeholder="correo@ejemplo.com">
                            </div>

                            <!-- Separador para cambio de contraseña -->
                            <hr class="my-4" style="border-color: #4b0082;">
                            
                            <div class="mb-3">
                                <h5 style="color: #4b0082;">
                                    <i class="fas fa-key me-2"></i>Cambiar Contraseña
                                </h5>
                                <small class="text-muted">Deja estos campos vacíos si no deseas cambiar tu contraseña</small>
                            </div>

                            <!-- Contraseña actual -->
                            <div class="mb-3">
                                <label for="claveActual" class="form-label fw-bold" style="color: #4b0082;">
                                    <i class="fas fa-lock me-2"></i>Contraseña Actual
                                </label>
                                <input type="password" class="form-control" id="claveActual" name="claveActual" 
                                       style="border-radius: 12px; border: 2px solid #e9ecef;"
                                       placeholder="Ingresa tu contraseña actual">
                            </div>

                            <!-- Nueva contraseña -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="claveNueva" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-key me-2"></i>Nueva Contraseña
                                    </label>
                                    <input type="password" class="form-control" id="claveNueva" name="claveNueva" 
                                           minlength="6" style="border-radius: 12px; border: 2px solid #e9ecef;"
                                           placeholder="Mínimo 6 caracteres">
                                </div>
                                <div class="col-md-6">
                                    <label for="confirmarClave" class="form-label fw-bold" style="color: #4b0082;">
                                        <i class="fas fa-key me-2"></i>Confirmar Nueva Contraseña
                                    </label>
                                    <input type="password" class="form-control" id="confirmarClave" name="confirmarClave" 
                                           minlength="6" style="border-radius: 12px; border: 2px solid #e9ecef;"
                                           placeholder="Repite la nueva contraseña">
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="alert alert-info" role="alert" style="border-radius: 12px; background-color: rgba(75, 0, 130, 0.1); border-color: #4b0082;">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Recomendaciones para la contraseña:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Usa al menos 6 caracteres</li>
                                    <li>Combina letras, números y símbolos</li>
                                    <li>Evita usar información personal</li>
                                </ul>
                            </div>

                            <!-- Botones -->
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <button type="submit" class="btn w-100 fw-bold" 
                                            style="background-color: #4b0082; color: white; border-radius: 12px; padding: 12px;">
                                        <i class="fas fa-save me-2"></i>Guardar Cambios
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <a href="?pid=<?php echo base64_encode('presentacion/sesionAdministrador.php'); ?>" class="btn btn-secondary w-100 fw-bold" 
                                       style="border-radius: 12px; padding: 12px;">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                </div>
                            </div>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">* Campos obligatorios</small>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Validación del teléfono en tiempo real
        document.getElementById('telefono').addEventListener('input', function(e) {
            const value = e.target.value;
            e.target.value = value.replace(/[^0-9]/g, '').substring(0, 10);
        });

        // Validación de contraseñas en tiempo real
        const claveNueva = document.getElementById('claveNueva');
        const confirmarClave = document.getElementById('confirmarClave');
        
        function validarContraseñas() {
            const nueva = claveNueva.value;
            const confirmar = confirmarClave.value;
            
            // Validar longitud de la nueva contraseña
            if (nueva.length > 0 && nueva.length < 6) {
                claveNueva.style.borderColor = '#dc3545';
                claveNueva.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
            } else if (nueva.length >= 6) {
                claveNueva.style.borderColor = '#198754';
                claveNueva.style.boxShadow = '0 0 0 0.2rem rgba(25, 135, 84, 0.25)';
            } else {
                claveNueva.style.borderColor = '#e9ecef';
                claveNueva.style.boxShadow = 'none';
            }
            
            // Validar coincidencia de contraseñas
            if (confirmar.length > 0) {
                if (nueva === confirmar && nueva.length >= 6) {
                    confirmarClave.style.borderColor = '#198754';
                    confirmarClave.style.boxShadow = '0 0 0 0.2rem rgba(25, 135, 84, 0.25)';
                } else {
                    confirmarClave.style.borderColor = '#dc3545';
                    confirmarClave.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
                }
            } else {
                confirmarClave.style.borderColor = '#e9ecef';
                confirmarClave.style.boxShadow = 'none';
            }
        }
        
        claveNueva.addEventListener('input', validarContraseñas);
        confirmarClave.addEventListener('input', validarContraseñas);

        // Animación de focus en los inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                if (this.id !== 'claveNueva' && this.id !== 'confirmarClave') {
                    this.style.borderColor = '#4b0082';
                    this.style.boxShadow = '0 0 0 0.2rem rgba(75, 0, 130, 0.25)';
                }
            });
            
            input.addEventListener('blur', function() {
                if (this.value.trim() === '' && this.id !== 'claveNueva' && this.id !== 'confirmarClave') {
                    this.style.borderColor = '#e9ecef';
                    this.style.boxShadow = 'none';
                }
            });
        });

        // Auto-ocultar alertas después de 5 segundos
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('alert-success')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);

        // Validación del formulario antes de enviar
        document.querySelector('form').addEventListener('submit', function(e) {
            const claveActual = document.getElementById('claveActual').value;
            const claveNueva = document.getElementById('claveNueva').value;
            const confirmarClave = document.getElementById('confirmarClave').value;
            
            // Si se están llenando campos de contraseña, validar que estén completos
            if (claveActual || claveNueva || confirmarClave) {
                if (!claveActual) {
                    alert('Debes ingresar tu contraseña actual para cambiarla');
                    e.preventDefault();
                    return;
                }
                if (!claveNueva) {
                    alert('Debes ingresar la nueva contraseña');
                    e.preventDefault();
                    return;
                }
                if (claveNueva !== confirmarClave) {
                    alert('Las contraseñas nuevas no coinciden');
                    e.preventDefault();
                    return;
                }
                if (claveNueva.length < 6) {
                    alert('La nueva contraseña debe tener al menos 6 caracteres');
                    e.preventDefault();
                    return;
                }
            }
        });
    </script>

</body>
</html>
