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

$error = 0;

if(isset($_POST["editar"])){    
    $nombre = $_FILES["imagen"]["name"];
    $extension = pathinfo($nombre, PATHINFO_EXTENSION);
    $extensiones = array('jpg','png','jpeg','gif','jfif','avif');
    if(in_array($extension, $extensiones)){
        $tam = $_FILES["imagen"]["size"] / 1024;
        if($tam < 500){
            $rutaLocal = $_FILES["imagen"]["tmp_name"];
            $rutaServidor = "imagen/perros/";
            $nombreImagen = time() . "." . $extension;
            
            // Eliminar la imagen anterior si existe
            if($perro->getFoto() != "" && $perro->getFoto() != "default.jpg"){
                if(file_exists($rutaServidor . $perro->getFoto())){
                    unlink($rutaServidor . $perro->getFoto());
                }
            }
            
            // Subir la nueva imagen
            if(move_uploaded_file($rutaLocal, $rutaServidor . $nombreImagen)){
                $perro->setFoto($nombreImagen);
                $perro->editarFoto();
                header("Location: ?pid=" . base64_encode("presentacion/propietario/misMascotas.php"));
                exit;
            }else{
                $error = 3;
            }
            
        }else{
            $error = 2;
        }
    }else{
        $error = 1;
    }    
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
					<h4><i class="fa-solid fa-camera me-2"></i>Editar Foto de <?php echo $perro->getNombre(); ?></h4>
				</div>
				<div class="card-body p-4">
					<!-- Mostrar foto actual -->
					<div class="text-center mb-4">
						<img src="imagen/perros/<?php echo $perro->getFoto(); ?>" 
							alt="Foto actual" 
							class="rounded-circle shadow"
							style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #E3CFF5;">
						<p class="mt-2 text-muted">Foto actual de <?php echo $perro->getNombre(); ?></p>
					</div>

    				<?php 
    				if (isset($_POST["editar"])) { 
    				    if($error == 0){
    				        echo "<div class='alert alert-success' role='alert'><i class='fa-solid fa-check-circle me-2'></i>Foto editada correctamente</div>";
    				    }else if($error == 1){
    				        echo "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-exclamation-triangle me-2'></i>Tipo de imagen no permitido. Solo se permiten archivos JPG, PNG, JPEG, GIF, JFIF, AVIF</div>";
    				    }else if($error == 2){
    				        echo "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-exclamation-triangle me-2'></i>Tamaño de imagen muy grande. Máximo 500KB</div>";
    				    }else if($error == 3){
    				        echo "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-exclamation-triangle me-2'></i>Error al subir la imagen</div>";
    				    }
    				}    				    
    				?>
					<form method="post" enctype="multipart/form-data">
						<div class="mb-3">
							<label for="imagen" class="form-label fw-bold" style="color: #4b0082;">
								<i class="fa-solid fa-image me-2"></i>Seleccionar nueva foto
							</label>
							<input type="file" name="imagen" id="imagen" class="form-control" 
								style="border: 2px solid #E3CFF5; border-radius: 10px;"
								accept=".jpg,.jpeg,.png,.gif,.jfif,.avif" required>
							<small class="text-muted">Formatos permitidos: JPG, JPEG, PNG, GIF, JFIF, AVIF. Tamaño máximo: 500KB</small>
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
</body>
