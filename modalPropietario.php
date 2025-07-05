<?php
require("logica/administrador.php");
require("logica/propietario.php");
require_once("persistencia/conexion.php");
$idP = $_GET ['idPropietario'];
$propietario = new Propietario($idP);
$propietario -> consultar();
?>
<div class="modal-header" style='color: #4b0082;'>
	<h4 class="modal-title">Propietario</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
	<table class="table table-striped table-hover">
		<tr>
			<th style='color: #4b0082;'>Identificacion</th>
			<td><?php echo $propietario -> getId() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Nombre</th>
			<td><?php echo $propietario -> getNombre() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Apellido</th>
			<td><?php echo $propietario -> getApellido() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Correo</th>
			<td><?php echo $propietario -> getCorreo() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Foto</th>
				<td><img class="rounded" src="<?php echo $propietario -> getFoto() ?>" height="300px" /></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Telefono</th>
			<td><?php echo $propietario -> getTelefono() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Direccion</th>
			<td><?php echo $propietario -> getDireccion() ?></td>
		</tr>
	</table>
</div>
