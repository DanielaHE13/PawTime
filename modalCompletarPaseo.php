<?php
require("logica/paseador.php");
require_once("persistencia/conexion.php");
$idPaseo = $_GET['idPaseo'];
?>

<div class="modal-header" style="background-color: #E3CFF5; color: #4b0082;">
    <h5 class="modal-title fw-bold">Completar Paseo</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>

<div class="modal-body text-center">
    <p class="fs-5 text-secondary">¿Estás seguro que deseas terminar este paseo?</p>
    <i class="fa-solid fa-dog fa-3x text-purple mb-3" style="color: #4b0082;"></i>
</div>

<div class="modal-footer justify-content-center">
    <a href="?pid=<?php echo base64_encode('presentacion/paseador/paseosPendientes.php'); ?>&idPaseo=<?php echo $idPaseo; ?>&estado=3" 
       class="btn btn-success">
        Sí, aceptar
    </a>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
</div>
