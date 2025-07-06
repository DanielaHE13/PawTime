<?php
require_once(__DIR__ . "/../../logica/Perro.php");
require_once(__DIR__ . "/../../logica/Raza.php");

include(__DIR__ . "/../encabezado.php");
include(__DIR__ . "/../menuPropietario.php");

$registrado = false;
$error = "";
$razas = Raza::listar();
$idPropietario = $_SESSION["id"];

if (isset($_POST["registrar"])) {
    // Validar que se haya subido un archivo
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        // Subida de imagen
        $fotoNombre = $_FILES["foto"]["name"];
        $fotoTemp = $_FILES["foto"]["tmp_name"];
        $rutaDestino = __DIR__ . "/../../imagen/perros/" . $fotoNombre;
        
        // Validar tipo de archivo
        $tiposPermitidos = array("jpg", "jpeg", "png", "gif", "jfif", "avif");
        $extension = strtolower(pathinfo($fotoNombre, PATHINFO_EXTENSION));
        
        if (in_array($extension, $tiposPermitidos)) {
            if (move_uploaded_file($fotoTemp, $rutaDestino)) {
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
            } else {
                $error = "Error al subir la imagen.";
            }
        } else {
            $error = "Tipo de archivo no permitido. Use: jpg, jpeg, png, gif, jfif, avif";
        }
    } else {
        $error = "Debe seleccionar una imagen.";
    }
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
          
          <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
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
              <a href="?pid=<?php echo base64_encode('presentacion/propietario/misMascotas.php'); ?>" 
                 class="btn text-white fw-bold me-2" 
                 style="background-color: #6c757d; border-radius: 12px;">
                <i class="fa-solid fa-times me-2"></i>Cancelar
              </a>
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
