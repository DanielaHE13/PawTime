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
if(isset($_POST["tarifa"])){
    $tarifa = $_POST["tarifa"];
}
if(isset($_POST["estado"])){
    $estado = $_POST["estado"];
}
if(isset($_POST["crear"])){
    $paseador = new Paseador($idPropietario, $nombre, $apellido, $telefono, $correo, $clave, "", $tarifa, $estado);
    $paseador->registrar();
    $mensaje = true;
}
?>
<body>
<?php 
include ('presentacion/encabezado.php');
include ('presentacion/menuAdministrador.php')
?>
<div class="container my-2">
  <form id="form" action="?pid=<?php echo base64_encode('presentacion/paseador/crearPaseador.php'); ?>" method="post">
    <div class="card shadow" style="background-color: #f8f0fc; border-radius: 20px; border: 1px solid #4b0082;">
      <div class="card-header text-center" style="background-color: #ffffff; border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <h4 class="fw-bold" style="color: #4b0082;">Crear Paseador</h4>
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
              <label class="fw-semibold" style="color: #4b0082;">Tarifa*</label>
              <input type="number" class="form-control" name="tarifa" min="0" required>
            </div>
            <div class="form-group mb-3">
              <label class="fw-semibold d-block mb-2" style="color: #4b0082;">Estado*</label>
              
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="estado" id="habilitado" value="1" checked>
                <label class="form-check-label" for="habilitado" style="color: #4b0082;">Habilitado</label>
              </div>
            
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="estado" id="inhabilitado" value="0">
                <label class="form-check-label" for="inhabilitado" style="color: #4b0082;">Inhabilitado</label>
              </div>
            </div>
            <button type="submit" class="btn" name="crear" style="background-color: #4b0082; color: white; border-radius: 10px;">
              Crear paseador
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