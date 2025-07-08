<?php
require ("logica/Paseador.php");
$idP= $_GET["idP"];
$estado =  $_GET["estado"];
$paseador = new Paseador($idP,"", "", "", "", "", "", "", $estado);
$paseador -> actualizarEstado();
if ($estado == 1) {
    echo '<span class="badge bg-success">Habilitado</span>';
} else {
    echo '<span class="badge bg-danger">Inhabilitado</span>';
}