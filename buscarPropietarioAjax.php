<?php
require ("logica/Persona.php");
require ("logica/Propietario.php");

$filtro = $_GET["filtro"];
$propietario = new Propietario();
$filtros = explode(" ", trim($filtro));
$propietarios = $propietario -> buscar($filtros);
if(count($propietarios) > 0){
    echo "<h5 class='card-title' style='color: #4b0082;'>Propietarios</h5>";
    echo "<table class='table table-striped table-hover mt-3'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Accion</th></tr>";
    
    foreach ($propietarios as $pac) {
        $idP = $pac->getId();
        $nombre = $pac->getNombre();
        $apellido = $pac->getApellido();
        $correo = $pac->getCorreo();
        
        foreach ($filtros as $f) {
            $idP = str_ireplace($f, "<strong>" . substr($idP, stripos($idP, $f), strlen($f)) . "</strong>", $idP);
            $nombre = str_ireplace($f, "<strong>" . substr($nombre, stripos($nombre, $f), strlen($f)) . "</strong>", $nombre);
            $apellido = str_ireplace($f, "<strong>" . substr($apellido, stripos($apellido, $f), strlen($f)) . "</strong>", $apellido);
            $correo = str_ireplace($f, "<strong>" . substr($correo, stripos($correo, $f), strlen($f)) . "</strong>", $correo);
        }
        echo "<tr>";
        echo "<td>" . $idP . "</td>";
        echo "<td>" . $nombre . "</td>";
        echo "<td>" . $apellido . "</td>";
        echo "<td>" . $correo . "</td>";
        echo "<td><a href='modalPropietario.php?idPropietario=" . $pac->getId() . "' class='abrir-modal' data-id='" . $pac->getId() . "' style='color: #4b0082;'><span class='fas fa-eye' title='Ver más información'></span></a> ";
        echo "<a href='modalEditarPropietario.php?idPropietario=" . $pac->getId() . "' class='abrir-modal' data-id='" . $pac->getId(). "' style='color: #4b0082;'><span class='fa-solid fa-pen-to-square' title='Editar información'></span></a> ";
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
}else{
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}
?>