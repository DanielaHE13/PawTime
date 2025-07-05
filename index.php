<?php
require("logica/Administrador.php");
require("logica/Paseador.php");
require("logica/Paseo.php");
require("logica/Estado.php");
require("logica/Perro.php");
require("logica/Propietario.php");
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PawTime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.7.2/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"></script>
</head>
<body>

<?php

$paginas_sin_autenticacion = array(
    "presentacion/inicio.php",
    "presentacion/autenticar.php",
    "presentacion/noAutorizado.php",
    "presentacion/registrarse.php",

);

$paginas_con_autenticacion = array(
    "presentacion/sesionAdministrador.php",
    "presentacion/sesionPaseador.php",
    "presentacion/sesionPropietario.php",
    "presentacion/menuPropietario.php",
    "presentacion/propietario/editarFoto.php",
    "presentacion/propietario/misMascotas.php",
    "presentacion/propietario/agregarMascota.php",
    "presentacion/propietario/consultarPropietario.php",
    "presentacion/paseador/crearPaseador.php",
    "presentacion/paseador/consultarPaseador.php",
    "presentacion/propietario/crearPropietario.php",
    "presentacion/paseo/consultarPaseo.php",
    "presentacion/paseo/crearPaseo.php",
);


if (!isset($_GET["pid"])) {
    include("presentacion/inicio.php");
} else {

    $pid = base64_decode($_GET["pid"]);
    if (in_array($pid, $paginas_sin_autenticacion)) {
        include $pid;
    } else if (in_array($pid, $paginas_con_autenticacion)) {
        if (!isset($_SESSION["id"])) {
            include "presentacion/autenticar.php";
        } else {
            include $pid;
        }
    } else {
        echo "error 404";
    }
}

?>
</body>
</html>