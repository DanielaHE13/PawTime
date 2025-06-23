<?php
require_once("logica/Propietario.php");
require_once("logica/Paseador.php");

$mensaje = "";

if (isset($_POST["registrar"])) {
  $nombre = trim($_POST["nombre"]);
  $apellido = trim($_POST["apellido"]);
  $telefono = trim($_POST["telefono"]);
  $correo = trim($_POST["correo"]);
  $clave = md5($_POST["clave"]);
  $rol = $_POST["rol"];

  if ($rol === "propietario") {
    $nuevo = new Propietario("", $nombre, $apellido, $telefono, $correo, $clave);
  } else {
    $nuevo = new Paseador("", $nombre, $apellido, $telefono, $correo, $clave);
  }

  if ($nuevo->registrar()) {
    $mensaje = "<div class='alert alert-success mt-3'>¡Registro exitoso! Ya puedes iniciar sesión.</div>";
  } else {
    $mensaje = "<div class='alert alert-danger mt-3'>Error: El correo ya está registrado.</div>";
  }
}
?>

<style>
  body {
    font-family: 'Mukta', sans-serif;
    background: linear-gradient(to bottom, #E3CFF5, #CFA8F5);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .form-container {
    background-color: #ffffffdd;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 600px;
  }

  .form-title {
    color: #4b0082;
    font-weight: bold;
    text-align: center;
    margin-bottom: 30px;
  }

  .form-control:focus {
    border-color: #4b0082;
    box-shadow: 0 0 0 0.2rem rgba(75, 0, 130, 0.25);
  }

  .btn-purple {
    background-color: #4b0082;
    color: white;
    border-radius: 12px;
    padding: 10px 20px;
  }

  .btn-purple:hover {
    background-color: #6a0dad;
  }

  label {
    color: #4b0082;
    font-weight: 500;
  }
</style>

<body>
  <div class="form-container">
    <h2 class="form-title">Registro de Usuario</h2>

    <form method="post" action="">

      <div class="mb-3">
        <label for="nombre">Nombre</label>
        <input type="text" class="form-control" name="nombre" id="nombre" required>
      </div>

      <div class="mb-3">
        <label for="apellido">Apellido</label>
        <input type="text" class="form-control" name="apellido" id="apellido" required>
      </div>

      <div class="mb-3">
        <label for="telefono">Teléfono</label>
        <input type="text" class="form-control" name="telefono" id="telefono" required>
      </div>

      <div class="mb-3">
        <label for="correo">Correo electrónico</label>
        <input type="email" class="form-control" name="correo" id="correo" required>
      </div>

      <div class="mb-3">
        <label for="clave">Contraseña</label>
        <input type="password" class="form-control" name="clave" id="clave" required>
      </div>

      <div class="mb-3">
        <label>Tipo de usuario</label>
        <select class="form-select" name="rol" required>
          <option value="propietario">Propietario</option>
          <option value="paseador">Paseador</option>
        </select>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" name="registrar" class="btn btn-purple">Registrarme</button>
      </div>

      <div class="text-center mt-3">
        <a href="index.php" class="text-decoration-none" style="color: #4b0082;">¿Ya tienes cuenta? Inicia sesión</a>
      </div>

      <?php if (!empty($mensaje)) echo $mensaje; ?>

    </form>
  </div>
</body>
