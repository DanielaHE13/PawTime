<?php
require("logica/administrador.php");
require("logica/paseador.php");
require_once("persistencia/conexion.php");
$idP = $_GET ['idPaseador'];
$paseador = new Paseador($idP);
$paseador -> consultar();
?>
<div class="modal-header" style='color: #4b0082;'>
	<h4 class="modal-title">Paseador</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
	<table class="table table-striped table-hover">
		<tr>
			<th style='color: #4b0082;'>Identificacion</th>
			<td><?php echo $paseador -> getId() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Nombre</th>
			<td><?php echo $paseador -> getNombre() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Apellido</th>
			<td><?php echo $paseador -> getApellido() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Correo</th>
			<td><?php echo $paseador -> getCorreo() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Foto</th>
				<td><img class="rounded" src="<?php echo $paseador -> getFoto() ?>" height="300px" /></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Telefono</th>
			<td><?php echo $paseador -> getTelefono() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Tarifa</th>
			<td><?php echo $paseador -> getTarifa() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Estado</th>
			<td><?php echo $paseador -> getEstado() ?></td>
		</tr>
	</table>
</div>
