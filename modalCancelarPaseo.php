<?php
require("logica/paseador.php");
require_once("persistencia/conexion.php");
$idPaseo = $_GET['idPaseo'];
?>

<div class="modal-header" style="background-color: #E3CFF5;">
    <h5 class="modal-title text-danger fw-bold">
        <i class="fa-solid fa-xmark me-2"></i>Cancelar Paseo
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>

<div class="modal-body text-center">
    <p class="fs-5" style="color: #4b0082;">
        ¿Estás segur@ que deseas <strong>cancelar</strong> el paseo con ID <strong>#<?php echo $idPaseo; ?></strong>?
    </p>
    <p class="text-muted">
        Esta acción no se puede deshacer.
    </p>
</div>

<div class="modal-footer justify-content-center">
    <a href="?pid=<?php echo base64_encode('presentacion/paseador/solicitudesPaseo.php'); ?>&idPaseo=<?php echo $idPaseo; ?>&estado=4" 
       class="btn btn-danger">
        <i class="fa-solid fa-xmark me-1"></i>Cancelar paseo
    </a>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fa-solid fa-arrow-left me-1"></i>Volver
    </button>
</div>
