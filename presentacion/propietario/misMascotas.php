<?php
require_once(__DIR__ . "/../../logica/Perro.php");
require_once(__DIR__ . "/../../logica/Raza.php");

$rol = $_SESSION["rol"];
if ($rol != "propietario") {
  header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
  exit;
}

include("presentacion/encabezado.php");
include("presentacion/menuPropietario.php");

$id = $_SESSION["id"];
$misPerros = Perro::listarPorPropietario($id);
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
  <div class="container mt-4">
    <h2 class="text-center mb-4" style="color: #4b0082;">🐾 Mis Mascotas</h2>

    <div class="row">
      <?php foreach ($misPerros as $perro) { ?>
        <div class="col-12 mb-4">
          <div class="card shadow d-flex flex-row align-items-center" style="border-radius: 30px; overflow: hidden; background-color: #E3CFF5;">
            <div class="p-3">
              <img src="../imagen/perros/<?php echo $perro->getFoto(); ?>" style="height: 150px; width: 150px; object-fit: cover; border-radius: 50%; border: 5px solid #4b0082;">
            </div>
            <div class="card-body">
              <h5 class="card-title" style="color: #4b0082;"><strong><?php echo $perro->getNombre(); ?></strong></h5>
              <p><strong>Raza:</strong> <?php echo $perro->getRazaNombre(); ?></p>
              <p><strong>Observaciones:</strong> <?php echo $perro->getObservaciones(); ?></p>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>

    <!-- Botón para ir al formulario -->
    <div class="text-center">
      <a class="btn text-white fw-bold my-4" style="background-color: #4b0082; border-radius: 12px;"
         href="?pid=<?php echo base64_encode('presentacion/propietario/agregarMascota.php'); ?>">
        <i class="fa-solid fa-plus me-2"></i>Agregar Mascota
      </a>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
