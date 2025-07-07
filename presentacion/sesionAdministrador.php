<?php
if($_SESSION["rol"] != "administrador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
?>
<body>
<?php 
include ("presentacion/encabezado.php");
include ("presentacion/menuAdministrador.php");
$administrador = new Administrador($_SESSION["id"]);
$administrador->consultar();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 text-center p-4 rounded shadow" style="border-radius: 20px;">
            <h2 class="fw-bold mb-3" style="color: #4b0082;">
                ¡Hola <?php echo $administrador->getNombre() . " " . $administrador->getApellido(); ?>!
            </h2>

            <p class="fs-5" style="color: #4b0082;">
                Bienvenid@ al panel de administración de <strong>PawTime</strong>. Aquí puedes gestionar paseadores, propietarios, mascotas, y supervisar el estado general de la plataforma.
            </p>

            <p class="fs-5 fw-semibold" style="color: #4b0082;">
                Selecciona una opción del menú para comenzar 🛠️🐾
            </p>
        </div>

        <div class="text-center mt-4">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player src="https://lottie.host/0d343a3a-ca65-4963-93fb-5ca775492199/nht5Ri9Lxh.lottie"
                              background="transparent" speed="1"
                              style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
        </div>
    </div>
</div>

<style>
    .btn-purple {
        background-color: #4b0082;
        color: white;
        border-radius: 12px;
    }

    .btn-purple:hover {
        background-color: #6a0dad;
    }
</style>
</body>
