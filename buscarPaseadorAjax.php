<?php
require ("logica/Persona.php");
require ("logica/Paseador.php");

$filtro = $_GET["filtro"];
$paseador = new Paseador();
$filtros = explode(" ", trim($filtro));
$paseadores = $paseador -> buscar($filtros);
if(count($paseadores) > 0){
    echo "<h5 class='card-title' style='color: #4b0082;'>Propietarios</h5>";
    echo "<table class='table table-striped table-hover mt-3'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Tarifa</th><th>Estado</th><th>Accion</th></tr>";
    
    foreach ($paseadores as $pac) {
        $idP = $pac->getId();
        $nombre = $pac->getNombre();
        $apellido = $pac->getApellido();
        $correo = $pac->getCorreo();
        $tarifa = $pac->getTarifa();
        $estado = $pac->getEstado();
        
        foreach ($filtros as $f) {
            $idP = str_ireplace($f, "<strong>" . substr($idP, stripos($idP, $f), strlen($f)) . "</strong>", $idP);
            $nombre = str_ireplace($f, "<strong>" . substr($nombre, stripos($nombre, $f), strlen($f)) . "</strong>", $nombre);
            $apellido = str_ireplace($f, "<strong>" . substr($apellido, stripos($apellido, $f), strlen($f)) . "</strong>", $apellido);
            $correo = str_ireplace($f, "<strong>" . substr($correo, stripos($correo, $f), strlen($f)) . "</strong>", $correo);
            $tarifa = str_ireplace($f, "<strong>" . substr($tarifa, stripos($tarifa, $f), strlen($f)) . "</strong>", $tarifa);
            $estado = str_ireplace($f, "<strong>" . substr($estado, stripos($estado, $f), strlen($f)) . "</strong>", $estado);
        }
        echo "<tr>";
        echo "<td>" . $idP . "</td>";
        echo "<td>" . $nombre . "</td>";
        echo "<td>" . $apellido . "</td>";
        echo "<td>" . $correo . "</td>";
        echo "<td>" . $tarifa . "</td>";
        echo "<td>" . $estado . "</td>";
        echo "<td><a href='modalPaseador.php?idPaseador=" . $pac->getId() . "' class='abrir-modal' data-id='" . $pac->getId() . "' style='color: #4b0082;'><span class='fas fa-eye' title='Ver más información'></span></a> ";
        echo "<a href='modalEditarPaseador.php?idPaseador=" . $pac->getId() . "' class='abrir-modal' data-id='" . $pac->getId(). "' style='color: #4b0082;'><span class='fa-solid fa-pen-to-square' title='Editar información'></span></a> ";
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
}else{
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}
?>