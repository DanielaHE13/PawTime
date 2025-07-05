<?php
require("logica/administrador.php");
require("logica/paseador.php");
require_once("persistencia/conexion.php");
$idP = $_GET ['idPaseador'];
$paseador = new Paseador($idP);
$paseador -> consultar();
?>
<div class="modal-header" style='color: #4b0082;'>
	<h4 class="modal-title">Editar Paseador</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
	<form id="form" action="?pid=<?php echo base64_encode('presentacion/propietario/consultarPropietario.php'); ?>" method="post">
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Número de identificación*</label>
         <input type="text" class="form-control" name="idP" value="<?php echo $paseador ->getId(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Nombre*</label>
         <input type="text" class="form-control" name="nombre" value="<?php echo $paseador ->getNombre(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Apellido*</label>
         <input type="text" class="form-control" name="apellido" value="<?php echo $paseador ->getApellido(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Teléfono*</label>
         <input type="text" class="form-control" name="telefono" value="<?php echo $paseador ->getTelefono(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Correo*</label>
         <input type="email" class="form-control" name="correo" value="<?php echo $paseador ->getCorreo(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Tarifa*</label>
         <input type="text" class="form-control" name="tarifa" value="<?php echo $paseador ->getTarifa(); ?>" required>
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
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Estado*</label>
         <input type="text" class="form-control" name="tarifa" value="<?php echo $paseador ->getTarifa(); ?>" required>
      </div>
      <button type="submit" class="btn" name="crear" style="background-color: #4b0082; color: white; border-radius: 10px;">
        Guardar cambios
      </button>
  </form>
</div>
