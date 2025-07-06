<?php
if ($_SESSION["rol"] != "propietario") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

require_once(__DIR__ . "/../../logica/Perro.php");

$idPerro = $_GET["idPerro"];
$perro = new Perro($idPerro);
$perro->consultar();

// Verificar que el perro pertenece al propietario actual
if ($perro->getIdPropietario() != $_SESSION["id"]) {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}

$mensaje = "";
$tipoMensaje = "";

if(isset($_POST["editar"])){    
    $observaciones = trim($_POST["observaciones"]);
    
    $perro->setObservaciones($observaciones);
    $perro->editarObservaciones();
    
    $mensaje = "Observaciones actualizadas correctamente";
    $tipoMensaje = "success";
    
    // Recargar los datos del perro para mostrar los cambios
    $perro->consultar();
}

include ("presentacion/encabezado.php");
include ("presentacion/menuPropietario.php");
?>
<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">

<div class="container">
	<div class="row mt-5">
		<div class="col-12 col-md-8 col-lg-6 mx-auto">
			<div class="card" style="border-radius: 20px; border: 2px solid #E3CFF5; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
				<div class="card-header text-white text-center" style="background-color: #4b0082; border-top-left-radius: 18px; border-top-right-radius: 18px;">
					<h4><i class="fa-solid fa-edit me-2"></i>Editar Observaciones de <?php echo $perro->getNombre(); ?></h4>
				</div>
				<div class="card-body p-4">
					<!-- Mostrar foto de la mascota -->
					<div class="text-center mb-4">
						<img src="imagen/perros/<?php echo $perro->getFoto(); ?>" 
							alt="Foto de <?php echo $perro->getNombre(); ?>" 
							class="rounded-circle shadow"
							style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #E3CFF5;">
						<h5 class="mt-2" style="color: #4b0082;"><?php echo $perro->getNombre(); ?></h5>
						<p class="text-muted">Raza: <?php echo $perro->getRazaNombre(); ?></p>
					</div>

    				<?php 
    				if (!empty($mensaje)) { 
    				    echo "<div class='alert alert-$tipoMensaje' role='alert'><i class='fa-solid fa-check-circle me-2'></i>$mensaje</div>";
    				}    				    
    				?>
					<form method="post">
						<div class="mb-3">
							<label for="observaciones" class="form-label fw-bold" style="color: #4b0082;">
								<i class="fa-solid fa-comment me-2"></i>Observaciones
							</label>
							<textarea name="observaciones" id="observaciones" class="form-control" rows="4"
								style="border: 2px solid #E3CFF5; border-radius: 10px;"
								placeholder="Escribe las observaciones sobre <?php echo $perro->getNombre(); ?>..."><?php echo htmlspecialchars($perro->getObservaciones()); ?></textarea>
							<small class="text-muted">Información adicional sobre la mascota, comportamiento, gustos, etc.</small>
						</div>
						
						<div class="d-flex gap-3 justify-content-center">
							<button type="submit" name="editar" class="btn text-white fw-bold px-4 py-2"
								style="background-color: #4b0082; border-radius: 12px;">
								<i class="fa-solid fa-save me-2"></i>Guardar Cambios
							</button>
							
							<a href="?pid=<?php echo base64_encode('presentacion/propietario/misMascotas.php'); ?>" 
								class="btn fw-bold px-4 py-2"
								style="background-color: #E3CFF5; color: #4b0082; border-radius: 12px;">
								<i class="fa-solid fa-arrow-left me-2"></i>Cancelar
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// Auto-ocultar alertas después de 3 segundos
	setTimeout(function() {
		const alerts = document.querySelectorAll('.alert-success');
		alerts.forEach(alert => {
			alert.style.opacity = '0';
			setTimeout(() => alert.remove(), 500);
		});
	}, 3000);
</script>

</body>
