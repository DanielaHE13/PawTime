<?php
require ("logica/Persona.php");
require ("logica/Paseo.php");
require ("logica/Paseador.php");
$filtro = $_GET["filtro"];
$paseo = new Paseo();
$filtros = explode(" ", trim($filtro));
$paseos = $paseo -> buscarPaseador($filtros);
if(count($paseos) > 0){
    echo "<h5 class='card-title' style='color: #4b0082;'>Paseo</h5>";
    echo "<table class='table table-striped table-hover mt-3'>";
    echo "<tr><th>ID</th><th>Fecha</th><th>Hora inicio - Hora Fin</th><th>Precio total</th><th>Perro</th><th>Estado</th><th>Acciones</th></tr>";
    
    foreach ($paseos as $pac) {
        $idP = $pac->getId();
        $fecha = $pac->getFecha();
        $hora_inicio = $pac->getHora_inicio();
        $hora_fin = $pac->getHora_fin();
        $precio_total = $pac->getPrecio_total();
        $paseador = $pac->getPaseador_idPaseador()->getNombre();
        $perro = $pac->getPerro_idPerro();
        $estado = $pac->getEstado_idEstado();
        
        foreach ($filtros as $f) {
            $idP = str_ireplace($f, "<strong>" . substr($idP, stripos($idP, $f), strlen($f)) . "</strong>", $idP);
            $fecha = str_ireplace($f, "<strong>" . substr($fecha, stripos($fecha, $f), strlen($f)) . "</strong>", $fecha);
            $hora_inicio = str_ireplace($f, "<strong>" . substr($hora_inicio, stripos($hora_inicio, $f), strlen($f)) . "</strong>", $hora_inicio);
            $hora_fin = str_ireplace($f, "<strong>" . substr($hora_fin, stripos($hora_fin, $f), strlen($f)) . "</strong>", $hora_fin);
            $precio_total = str_ireplace($f, "<strong>" . substr($precio_total, stripos($precio_total, $f), strlen($f)) . "</strong>", $precio_total);
            $perro = str_ireplace($f, "<strong>" . substr($perro, stripos($perro, $f), strlen($f)) . "</strong>", $perro);
            $estado = str_ireplace($f, "<strong>" . substr($estado, stripos($estado, $f), strlen($f)) . "</strong>", $estado);
        }
        echo "<tr>";
        echo "<td>" . $idP . "</td>";
        echo "<td>" . $fecha . "</td>";
        echo "<td>" . $hora_inicio . "-" .$hora_fin. "</td>";
        echo "<td>$" . number_format($precio_total, 0, ',', '.') . "</td>";
        echo "<td>" . $perro . "</td>";
        $estadoClass = "";
        
        switch (strtolower($estado)) {
            case "aceptado":
                $estadoClass = "badge bg-warning text-dark";
                break;
            case "en curso":
                $estadoClass = "badge bg-primary";
                break;
            case "completado":
                $estadoClass = "badge bg-success";
                break;
            case "cancelado":
                $estadoClass = "badge bg-danger";
                break;
            default:
                $estadoClass = "badge bg-secondary";
                break;
        }
        echo "<td><span class='" . $estadoClass . "'>".$estado."</span></td>";
        echo "<td><a href='modalPaseo.php?idPaseo='" . $pac->getId() . "'&idPaseador=".$pac->getPaseador_idPaseador()->getId()." class='abrir-modal' data-id='" . $pac->getId() . "' style='color: #4b0082;'><span class='fas fa-eye' title='Ver más información'></span></a> ";
        echo "<a href='modalPerrito.php?idPerrito=" . $pac->getPerro_idPerro()->getId() . "' class='abrir-modal' data-id='" . $pac->getPerro_idPerro()->getId() . "' style='color: #4b0082;' title='Ver info del perrito'><i class='fa-solid fa-dog'></i></a>";
        
        echo "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
}else{
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}
?>