<?php
require("logica/administrador.php");
require("logica/propietario.php");
require_once("persistencia/conexion.php");
$idP = $_GET ['idPropietario'];
$propietario = new Propietario($idP);
$propietario -> consultar();
?>
<div class="modal-header" style='color: #4b0082;'>
	<h4 class="modal-title">Editar Propietario</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
	<form id="form" action="?pid=<?php echo base64_encode('presentacion/propietario/consultarPropietario.php'); ?>" method="post">
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Número de identificación*</label>
         <input type="text" class="form-control" name="idP" value="<?php echo $propietario ->getId(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Nombre*</label>
         <input type="text" class="form-control" name="nombre" value="<?php echo $propietario ->getNombre(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Apellido*</label>
         <input type="text" class="form-control" name="apellido" value="<?php echo $propietario ->getApellido(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Teléfono*</label>
         <input type="text" class="form-control" name="telefono" value="<?php echo $propietario ->getTelefono(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Correo*</label>
         <input type="email" class="form-control" name="correo" value="<?php echo $propietario ->getCorreo(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Dirección*</label>
         <input type="text" class="form-control" name="direccion" value="<?php echo $propietario ->getDireccion(); ?>" required>
      </div>
      <input type="hidden" name="foto" value="<?php echo $propietario->getFoto(); ?>">
      <button type="submit" class="btn" name="crear" style="background-color: #4b0082; color: white; border-radius: 10px;">
        Guardar cambios
      </button>
  </form>
</div>
