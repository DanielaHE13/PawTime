<?php
if($_SESSION["rol"] != "administrador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
$mensaje = false;
if(isset($_POST["idP"])){
    $idPropietario = $_POST["idP"];
}
if(isset($_POST["nombre"])){
    $nombre = $_POST["nombre"];
}
if(isset($_POST["apellido"])){
    $apellido = $_POST["apellido"];
}
if(isset($_POST["telefono"])){
    $telefono = $_POST["telefono"];
}
if(isset($_POST["correo"])){
    $correo = $_POST["correo"];
}
if(isset($_POST["clave"])){
    $clave = $_POST["clave"];
}
if(isset($_POST["direccion"])){
    $direccion = $_POST["direccion"];
}
if(isset($_POST["crear"])){
    $propietario = new Propietario($idPropietario, $nombre, $apellido, $telefono, $correo, $clave, $direccion);
    $propietario->registrar();
    $mensaje = true;
}
?>
<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
<?php 
include ('presentacion/encabezado.php');
include ('presentacion/menuAdministrador.php')
?>
<div class="container my-2">
  <form id="form" action="?pid=<?php echo base64_encode('presentacion/propietario/crearPropietario.php'); ?>" method="post">
    <div class="card shadow" style="background-color: #f8f0fc; border-radius: 20px; border: 1px solid #4b0082;">
      <div class="card-header text-center" style="background-color: #ffffff; border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <h4 class="fw-bold" style="color: #4b0082;">Crear Propietario</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-3">
              <label class="fw-semibold" style="color: #4b0082;">Nombre*</label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="form-group mb-3">
              <label class="fw-semibold" style="color: #4b0082;">Apellido*</label>
              <input type="text" class="form-control" name="apellido" required>
            </div>
            <div class="form-group mb-3">
              <label class="fw-semibold" style="color: #4b0082;">Número de identificación*</label>
              <input type="text" class="form-control" name="idP" required>
            </div>
            <div class="form-group mb-3">
              <label class="fw-semibold" style="color: #4b0082;">Teléfono*</label>
              <input type="text" class="form-control" name="telefono" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group mb-3">
              <label class="fw-semibold" style="color: #4b0082;">Correo*</label>
              <input type="email" class="form-control" name="correo" required>
            </div>
            <div class="form-group mb-3">
              <label class="fw-semibold" style="color: #4b0082;">Clave*</label>
              <input type="password" class="form-control" name="clave" required>
            </div>
            <div class="form-group mb-3">
              <label class="fw-semibold" style="color: #4b0082;">Dirección*</label>
              <input type="text" class="form-control" name="direccion" required>
            </div>
            <button type="submit" class="btn" name="crear" style="background-color: #4b0082; color: white; border-radius: 10px;">
              Crear propietario
            </button>
            <?php 
            if ($mensaje) {
                echo "<div class='alert alert-success mt-3'>¡Registro exitoso!</div>";
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
</body>