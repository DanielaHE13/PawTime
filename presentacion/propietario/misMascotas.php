<?php
require_once(__DIR__ . "/../../logica/Perro.php");
require_once(__DIR__ . "/../../logica/Raza.php");

$rol = $_SESSION["rol"];
if ($rol != "propietario") {
  header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
  exit;
}

$mensaje = "";
$tipoMensaje = "";

// Procesar eliminación si se recibe el parámetro
if (isset($_POST["eliminar"]) && isset($_POST["idPerro"])) {
    $idPerro = $_POST["idPerro"];
    $perro = new Perro($idPerro);
    $perro->consultar();
    
    // Verificar que el perro pertenece al propietario actual
    if ($perro->getIdPropietario() == $_SESSION["id"]) {
        // Eliminar la foto del servidor si existe
        if ($perro->getFoto() != "" && file_exists("imagen/perros/" . $perro->getFoto())) {
            unlink("imagen/perros/" . $perro->getFoto());
        }
        
        // Eliminar el perro de la base de datos
        $perro->eliminar();
        $mensaje = "Mascota eliminada correctamente";
        $tipoMensaje = "success";
    } else {
        $mensaje = "No tienes permisos para eliminar esta mascota";
        $tipoMensaje = "danger";
    }
}

include("presentacion/encabezado.php");
include("presentacion/menuPropietario.php");

$id = $_SESSION["id"];
$misPerros = Perro::listarPorPropietario($id);
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
  <div class="container mt-4">
    <h2 class="text-center mb-4" style="color: #4b0082;">🐾 Mis Mascotas</h2>

    <!-- Mensajes de confirmación -->
    <?php if (!empty($mensaje)): ?>
      <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
        <i class="fas fa-<?php echo $tipoMensaje === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
        <?php echo $mensaje; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="row">
      <?php foreach ($misPerros as $perro) { ?>
        <div class="col-12 mb-4">
          <div class="card shadow d-flex flex-row align-items-center" style="border-radius: 30px; overflow: hidden; background-color: #E3CFF5;">
            <div class="p-3">
              <img src="imagen/perros/<?php echo $perro->getFoto(); ?>" style="height: 150px; width: 150px; object-fit: cover; border-radius: 50%; border: 5px solid #4b0082;">
            </div>
            <div class="card-body">
              <h5 class="card-title" style="color: #4b0082;"><strong><?php echo $perro->getNombre(); ?></strong></h5>
              <p><strong>Raza:</strong> <?php echo $perro->getRazaNombre(); ?></p>
              <p><strong>Observaciones:</strong> <?php echo $perro->getObservaciones(); ?></p>
              
              <!-- Botones de acción -->
              <div class="mt-3">
                <a href="?pid=<?php echo base64_encode('presentacion/propietario/editarFotoMascota.php'); ?>&idPerro=<?php echo $perro->getId(); ?>" 
                   class="btn btn-sm text-white fw-bold me-2" 
                   style="background-color: #4b0082; border-radius: 10px;">
                  <i class="fa-solid fa-camera me-1"></i>
                </a>
                <a href="?pid=<?php echo base64_encode('presentacion/propietario/editarObservacionesMascota.php'); ?>&idPerro=<?php echo $perro->getId(); ?>" 
                   class="btn btn-sm text-white fw-bold me-2" 
                   style="background-color: #6a0dad; border-radius: 10px;">
                  <i class="fa-solid fa-edit me-1"></i>
                </a>
                <button type="button" class="btn btn-danger btn-sm fw-bold" 
                        style="border-radius: 10px;"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalEliminar<?php echo $perro->getId(); ?>">
                  <i class="fa-solid fa-trash me-1"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal de confirmación para eliminar -->
        <div class="modal fade" id="modalEliminar<?php echo $perro->getId(); ?>" tabindex="-1" aria-labelledby="modalEliminarLabel<?php echo $perro->getId(); ?>" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; background-color: #f8f0fc;">
              <div class="modal-header text-white" style="background-color: #dc3545; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <h5 class="modal-title" id="modalEliminarLabel<?php echo $perro->getId(); ?>">
                  <i class="fa-solid fa-exclamation-triangle me-2"></i>¿Eliminar mascota?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>

              <div class="modal-body text-center">
                <div class="mb-3">
                  <img src="imagen/perros/<?php echo $perro->getFoto(); ?>" 
                       alt="<?php echo $perro->getNombre(); ?>" 
                       class="rounded-circle"
                       style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #dc3545;">
                </div>
                <h5 style="color: #4b0082;"><?php echo $perro->getNombre(); ?></h5>
                <p class="fs-6 mt-3" style="color: #4b0082;">
                  ¿Estás segur@ de que deseas eliminar a <strong><?php echo $perro->getNombre(); ?></strong>?
                </p>
                <p class="text-muted small">Esta acción no se puede deshacer.</p>
              </div>

              <div class="modal-footer justify-content-center">
                <form method="post" style="display: inline;">
                  <input type="hidden" name="idPerro" value="<?php echo $perro->getId(); ?>">
                  <button type="submit" name="eliminar" class="btn text-white fw-bold px-4" style="background-color: #dc3545; border-radius: 12px;">
                    <i class="fa-solid fa-trash me-2"></i>Sí, eliminar
                  </button>
                </form>
                <button type="button" class="btn text-white fw-bold px-4" style="background-color: #6c757d; border-radius: 12px;" data-bs-dismiss="modal">
                  <i class="fa-solid fa-times me-2"></i>Cancelar
                </button>
              </div>
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
  
  <script>
    // Auto-ocultar alertas de éxito después de 4 segundos
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert-success');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 4000);
  </script>
</body>
