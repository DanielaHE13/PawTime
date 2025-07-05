<?php
require("logica/administrador.php");
require("logica/paseo.php");
require_once("persistencia/conexion.php");
$idP = $_GET ['idPaseo'];
$paseo = new Paseo($idP);
$paseo -> consultar();
?>
<div class="modal-header" style='color: #4b0082;'>
	<h4 class="modal-title">Paseo</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
	<table class="table table-striped table-hover">
		<tr>
			<th style='color: #4b0082;'>ID</th>
			<td><?php echo $paseo -> getId() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Fecha</th>
			<td><?php echo $paseo -> getNombre() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Hora inicio - Hora Fin</th>
			<td><?php echo $paseo -> getApellido() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Precio total</th>
			<td><?php echo $paseo -> getCorreo() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Paseador</th>
				<td><img class="rounded" src="<?php echo $paseo -> getFoto() ?>" height="300px" /></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Perro/s</th>
			<td><?php echo $paseo -> getTelefono() ?></td>
		</tr>
		<tr>
			<th style='color: #4b0082;'>Estado</th>
			<td><?php echo $paseo -> getTarifa() ?></td>
		</tr>
	</table>
</div>
