<?php
require_once(__DIR__ . "/../../logica/Perro.php");
require_once(__DIR__ . "/../../logica/Raza.php");

include(__DIR__ . "/../encabezado.php");
include(__DIR__ . "/../menuPropietario.php");

$registrado = false;
$razas = Raza::listar();
$idPropietario = $_SESSION["id"];

if (isset($_POST["registrar"])) {
    // Subida de imagen
    $fotoNombre = $_FILES["foto"]["name"];
    $fotoTemp = $_FILES["foto"]["tmp_name"];
    $rutaDestino = __DIR__ . "/../../imagen/perros/" . $fotoNombre;
    move_uploaded_file($fotoTemp, $rutaDestino);

    // Crear objeto Perro y registrar
    $perro = new Perro(
        null,
        $_POST["nombre"],
        $_POST["observaciones"],
        $fotoNombre,
        $_POST["idRaza"],
        $idPropietario
    );
    $perro->registrar();
    $registrado = true;
}
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
  <div class="container mt-5 mb-5">
    <div class="col-md-8 offset-md-2">
      <div class="card shadow" style="border-radius: 15px; background-color: #CFA8F5;">
        <div class="card-header text-white fw-bold" style="background-color: #4b0082; border-radius: 15px 15px 0 0;">
          <h4 class="mb-0">Registrar Nueva Mascota 🐶</h4>
        </div>

        <div class="card-body">
          <?php if ($registrado) { ?>
            <div class="alert alert-success">Mascota registrada con éxito.</div>
          <?php } ?>

          <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Raza</label>
              <select name="idRaza" class="form-select" required>
                <option value="">Seleccione una raza</option>
                <?php foreach ($razas as $raza) { ?>
                  <option value="<?php echo $raza["id"]; ?>"><?php echo $raza["nombre"]; ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Observaciones</label>
              <textarea name="observaciones" class="form-control" rows="3" placeholder="Ej: Le gusta jugar con otros perros..."></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Foto</label>
              <input type="file" name="foto" class="form-control" accept="image/*" required>
            </div>

            <div class="text-end">
              <button type="submit" name="registrar" class="btn text-white fw-bold" style="background-color: #4b0082; border-radius: 12px;">
                <i class="fa-solid fa-plus me-2"></i>Registrar Mascota
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap y FontAwesome -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
