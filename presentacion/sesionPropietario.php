<?php
if ($_SESSION["rol"] != "propietario") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}
$id = $_SESSION["id"];
$propietario = new Propietario($id);
$propietario->consultar();
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    <?php
    include("presentacion/encabezado.php");
    include("presentacion/menuPropietario.php");
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 text-center  p-4 rounded shadow" style="border-radius: 20px;">
                <h2 class="fw-bold mb-3" style="color: #4b0082;">
                    ¡Hola <?php echo $propietario->getNombre() . " " . $propietario->getApellido(); ?>!
                </h2>

                <p class="fs-5" style="color: #4b0082;">
                    Bienvenid@ a <strong>PawTime</strong>. Desde aquí puedes gestionar a tus mascotas, agendar paseos y explorar tus paseadores favoritos.
                </p>

                <p class="fs-5 fw-semibold" style="color: #4b0082;">
                    Da click a una opción para comenzar 🐾
                </p>


            </div>
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player src="https://lottie.host/0d343a3a-ca65-4963-93fb-5ca775492199/nht5Ri9Lxh.lottie" background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
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

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>