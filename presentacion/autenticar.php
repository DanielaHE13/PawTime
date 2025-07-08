<?php
require_once("logica/Administrador.php");
require_once("logica/Propietario.php");
require_once("logica/Paseador.php");
?>
<style>
  body {
    font-family: 'Mukta', sans-serif;
    height: 100vh;
    min-height: 700px;
    background: linear-gradient(to bottom, #E3CFF5, #CFA8F5);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    position: relative;
    overflow-y: auto;
    padding: 10px 0;
  }

  a {
    text-decoration: none;
    color: #4b0082;
  }

  .login-reg-panel {
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    text-align: center;
    width: 85%;
    max-width: 1000px;
    margin: 10px auto;
    height: auto;
    min-height: 550px;
    background-color: rgba(255, 255, 255, 0.7);
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    padding: 10px 0;
  }

  .white-panel {
    background-color: #fff;
    height: auto;
    min-height: 600px;
    position: absolute;
    top: 0;
    width: 50%;
    right: calc(50% - 50px);
    transition: 0.3s ease-in-out;
    z-index: 0;
    box-shadow: 0 4px 20px rgba(75, 0, 130, 0.1);
    border-radius: 20px;
  }

  .right-log {
    right: 50px !important;
  }

  .login-info-box,
  .register-info-box {
    width: 35%;
    padding: 20px 30px;
    top: 15%;
    position: absolute;
    text-align: left;
    color: #4b0082;
    font-weight: bold;
  }

  .login-info-box {
    left: 0;
  }

  .register-info-box {
    right: 0;
  }

  .login-reg-panel input[type="radio"] {
    display: none;
  }

  .login-reg-panel #label-login,
  .login-reg-panel #label-register {
    border: 2px solid #4b0082;
    background-color: #4b0082;
    color: white;
    padding: 8px 10px;
    width: 150px;
    display: block;
    text-align: center;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 16px;
    margin: 10px auto;
    transition: background-color 0.3s;
  }

  .login-reg-panel #label-login:hover,
  .login-reg-panel #label-register:hover {
    background-color: #6a0dad;
  }

  .login-show,
  .register-show {
    z-index: 1;
    display: none;
    opacity: 0;
    transition: 0.3s ease-in-out;
    color: #4b0082;
    text-align: left;
    padding: 40px 30px 30px 30px;
    font-size: 16px;
    max-height: 550px;
    overflow-y: auto;
    min-height: 500px;
  }

  .show-log-panel {
    display: block;
    opacity: 0.95;
  }

  /* Media queries para mejor responsividad */
  @media (max-width: 1200px) {
    .login-reg-panel {
      width: 90%;
      margin: 20px auto;
    }
    
    .login-info-box,
    .register-info-box {
      width: 40%;
      padding: 15px 20px;
    }
  }

  @media (max-width: 768px) {
    .login-reg-panel {
      width: 95%;
      margin: 10px auto;
      min-height: 500px;
    }
    
    .white-panel {
      width: 100%;
      right: 0;
      min-height: 450px;
    }
    
    .login-info-box,
    .register-info-box {
      width: 100%;
      position: static;
      text-align: center;
      padding: 10px;
    }
    
    .login-show,
    .register-show {
      padding: 20px;
      max-height: 400px;
    }
  }

  @media (min-height: 900px) {
    .login-reg-panel {
      margin: 60px auto;
    }
  }
</style>
<?php
if (isset($_GET["sesion"])) {
  if ($_GET["sesion"] == "false") {
    session_destroy();
  }
}
$error = false;
$errorRegistro = false;
$exitoRegistro = false;
$mensaje = false;
if (isset($_POST["autenticar"])) {
  $correo = $_POST["correo"];
  $clave = $_POST["clave"];
  $admin = new Administrador("", "", "", "", $correo, $clave);
  if ($admin->autenticar()) {
    $_SESSION["id"] = $admin->getId();
    $_SESSION["rol"] = "administrador";
    header("Location: ?pid=" . base64_encode("presentacion/sesionAdministrador.php"));
  } else {
    $propietario = new Propietario("", "", "", "", $correo, $clave);
    if ($propietario->autenticar()) {
      $_SESSION["id"] = $propietario->getId();
      $_SESSION["rol"] = "propietario";
      header("Location: ?pid=" . base64_encode("presentacion/sesionPropietario.php"));
    } else {
      $paseador = new Paseador("", "", "", "", $correo, $clave);
      if ($paseador->autenticar()) {
          $paseador ->consultarEstado($paseador->getId());
          if($paseador->getEstado()!="0"){
            $_SESSION["id"] = $paseador->getId();
            $_SESSION["rol"] = "paseador";
            header("Location: ?pid=" . base64_encode("presentacion/paseador/sesionPaseador.php"));
          }else{
              $mensaje = true;
          }
      } else {
        $error = true;
      }
    }
  }
}

// Lógica para registro
if (isset($_POST["registrar"])) {
  $cedula = $_POST["cedula"];
  $nombre = $_POST["nombre"];
  $apellido = $_POST["apellido"];
  $telefono = $_POST["telefono"];
  $correo = $_POST["correo_registro"];
  $clave = $_POST["clave_registro"];
  $confirmarClave = $_POST["confirmar_clave"];
  $tipoUsuario = $_POST["tipo_usuario"];
  
  // Validar que las contraseñas coincidan
  if ($clave === $confirmarClave) {
    try {
      if ($tipoUsuario === "propietario") {
        $direccion = ""; // Dirección por defecto vacía, se puede actualizar después
        $propietario = new Propietario($cedula, $nombre, $apellido, $telefono, $correo, $clave, $direccion);
        try {
          $resultado = $propietario->registrar();
          if ($resultado !== false) {
            $exitoRegistro = true;
          } else {
            $errorRegistro = "Error al registrar el propietario";
          }
        } catch (Exception $e) {
          $errorRegistro = "Error al registrar propietario: " . $e->getMessage();
        }
      } elseif ($tipoUsuario === "paseador") {
        // Para paseador necesitamos valores por defecto para los campos requeridos
        $foto = "default.jpg"; // Foto por defecto
        $tarifa = 0; // Tarifa inicial
        $estado = 1; // 1 = activo, 0 = inactivo
        
        $paseador = new Paseador($cedula, $nombre, $apellido, $telefono, $correo, $clave, $foto, $tarifa, $estado);
        try {
          $resultado = $paseador->registrar();
          if ($resultado !== false) {
            $exitoRegistro = true;
          } else {
            $errorRegistro = "Error al registrar el paseador";
          }
        } catch (Exception $e) {
          $errorRegistro = "Error al registrar paseador: " . $e->getMessage();
        }
      }
    } catch (Exception $e) {
      $errorRegistro = "Error en el registro: " . $e->getMessage();
    }
  } else {
    $errorRegistro = "Las contraseñas no coinciden";
  }
}
?>

<body>
  <div class="login-reg-panel">

    <div class="login-info-box">
      <h3  >¿Ya tienes una cuenta?</h3>
      <p>Inicia sesión para agendar paseos, gestionar tu perfil o continuar con tus servicios.</p>
      <label id="label-register" for="log-reg-show">Iniciar sesión</label>

      <input type="radio" name="active-log-panel" id="log-reg-show" checked="checked">
    </div>

    <div class="register-info-box">
      <h3>¿Aún no tienes cuenta?</h3>
      <p>Regístrate para encontrar paseadores confiables para tu perrito o para ofrecer tus servicios si eres paseador.</p>
      <label id="label-login" for="log-login-show">Registrarse</label>
      <input type="radio" name="active-log-panel" id="log-login-show">
    </div>

    <div class="white-panel">
      <div class="login-show">
        <div class="text-center" style="padding-top: 20px;">
          <h2 class="mt-1 mb-4 fw-bold" style="color: #4b0082">Iniciar sesión</h2>
        </div>

        <form action="?pid=<?php echo base64_encode('presentacion/autenticar.php') ?>" method="post">
          <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" name="correo" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="clave" class="form-label">Contraseña</label>
            <input type="password" id="clave" name="clave" class="form-control" required />
          </div>

          <div class="text-center mb-4">
            <button type="submit" class="btn shadow-lg px-4 py-2" style="background-color: #4b0082; color: #fff;" name="autenticar">Iniciar sesión</button>
            <div class="mt-3">
              <a class="text-muted text-decoration-none" href="?">Regresar</a>
            </div>
          </div>
        </form>

        <?php
        if ($error) {
          echo "<div class='alert alert-danger mt-3' role='alert'>Credenciales incorrectas</div>";
        }else if($mensaje){
            echo "<div class='alert alert-danger' role='alert' style='color: #721c24; font-weight: bold;'>
            🚫 Tu cuenta se encuentra <strong>inhabilitada</strong> actualmente.<br>
            Por favor, comunícate con el administrador del sistema para más información o para restablecer tu acceso. 🙁🔒
            </div>";            
        }
        ?>
      </div>
      <div class="register-show">
        <div class="text-center" style="padding-top: 20px;">
          <h2 class="mt-1 mb-4 fw-bold" style="color: #4b0082">Registrarse</h2>
        </div>

        <form action="?pid=<?php echo base64_encode('presentacion/autenticar.php') ?>" method="post">
          <div class="mb-3">
            <label for="tipo_usuario" class="form-label">Tipo de usuario</label>
            <select id="tipo_usuario" name="tipo_usuario" class="form-control" required>
              <option value="">Seleccione una opción</option>
              <option value="propietario">Propietario</option>
              <option value="paseador">Paseador</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="cedula" class="form-label">Cédula</label>
            <input type="text" id="cedula" name="cedula" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" id="apellido" name="apellido" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Ejemplo: 3001234567" required />
          </div>

          <div class="mb-3">
            <label for="correo_registro" class="form-label">Correo</label>
            <input type="email" id="correo_registro" name="correo_registro" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="clave_registro" class="form-label">Contraseña</label>
            <input type="password" id="clave_registro" name="clave_registro" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="confirmar_clave" class="form-label">Confirmar contraseña</label>
            <input type="password" id="confirmar_clave" name="confirmar_clave" class="form-control" required />
          </div>

          <div class="text-center mb-4">
            <button type="submit" class="btn shadow-lg px-4 py-2" style="background-color: #4b0082; color: #fff;" name="registrar">Registrarse</button>
            <div class="mt-3">
              <a class="text-muted text-decoration-none" href="?">Regresar</a>
            </div>
          </div>
        </form>

        <?php
        if ($errorRegistro) {
          echo "<div class='alert alert-danger mt-3' role='alert'>$errorRegistro</div>";
        }
        if ($exitoRegistro) {
          echo "<div class='alert alert-success mt-3' role='alert'>¡Registro exitoso! Ya puedes iniciar sesión.</div>";
        }
        ?>
      </div>
    </div>
  </div>
</body>
<script>
  $(document).ready(function() {
    // Verificar si viene de un enlace directo al registro
    if (window.location.hash === '#registro') {
      // Mostrar formulario de registro
      $('.register-info-box').fadeOut();
      $('.login-info-box').fadeIn();
      $('.white-panel').addClass('right-log');
      $('.register-show').addClass('show-log-panel');
      $('.login-show').removeClass('show-log-panel');
      $('#log-login-show').prop('checked', true);
    } else {
      // Comportamiento por defecto (mostrar login)
      $('.login-info-box').fadeOut();
      $('.login-show').addClass('show-log-panel');
    }
    
    // Validación de contraseñas en tiempo real
    $('#confirmar_clave').on('keyup', function() {
      var clave = $('#clave_registro').val();
      var confirmarClave = $(this).val();
      
      if (clave !== confirmarClave && confirmarClave !== '') {
        $(this).addClass('is-invalid');
        if (!$(this).next('.invalid-feedback').length) {
          $(this).after('<div class="invalid-feedback">Las contraseñas no coinciden</div>');
        }
      } else {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
      }
    });
  });

  $('.login-reg-panel input[type="radio"]').on('change', function() {
    if ($('#log-login-show').is(':checked')) {
      $('.register-info-box').fadeOut();
      $('.login-info-box').fadeIn();

      $('.white-panel').addClass('right-log');
      $('.register-show').addClass('show-log-panel');
      $('.login-show').removeClass('show-log-panel');

    } else if ($('#log-reg-show').is(':checked')) {
      $('.register-info-box').fadeIn();
      $('.login-info-box').fadeOut();

      $('.white-panel').removeClass('right-log');

      $('.login-show').addClass('show-log-panel');
      $('.register-show').removeClass('show-log-panel');
    }
  });
</script>

</html>
