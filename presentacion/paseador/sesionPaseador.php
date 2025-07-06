<?php
if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit;
}
$id = $_SESSION["id"];
$paseador = new Paseador($id);
$paseador->consultar();
include("presentacion/encabezado.php");
include("presentacion/menuPaseador.php");
?>

<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">
    <?php
    
    
    ?>

   

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 text-center p-4 rounded shadow" style="border-radius: 20px; background-color: rgba(255, 255, 255, 0.9);">
                <h2 class="fw-bold mb-3" style="color: #4b0082;">
                    ¡Hola <?php echo $paseador->getNombre() . " " . $paseador->getApellido(); ?>!
                </h2>

                <p class="fs-5" style="color: #6a0dad;">
                    Bienvenid@ a tu panel de <strong>PawTime</strong>. Desde aquí puedes gestionar tus paseos programados y mantener actualizado tu perfil profesional.
                </p>

                <p class="fs-5 fw-semibold" style="color: #4b0082;">
                    ¡Listo para hacer felices a las mascotas! 🐕‍🦺
                </p>

                <div class="d-flex justify-content-center">
                    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
                    <dotlottie-player src="https://lottie.host/0d343a3a-ca65-4963-93fb-5ca775492199/nht5Ri9Lxh.lottie" background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
                </div>
            </div>
        </div>

        <!-- Estadísticas del paseador -->
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card border-0 shadow" style="border-radius: 20px; background-color: rgba(255, 255, 255, 0.9);">
                    <div class="card-body">
                        <h4 class="text-center mb-4" style="color: #4b0082;">Tu información</h4>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="border-end">
                                    <h5 style="color: #8e44ad;">Tarifa</h5>
                                    <p class="fs-4 fw-bold" style="color: #4b0082;">$<?php echo number_format($paseador->getTarifa(), 0, ',', '.'); ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-end">
                                    <h5 style="color: #8e44ad;">Estado</h5>
                                    <p class="fs-5 fw-bold" style="color: #4b0082;">
                                        <?php 
                                        $estadoTexto = ($paseador->getEstado() == 1) ? "Activo" : "Inactivo";
                                        echo $estadoTexto; 
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 style="color: #8e44ad;">Contacto</h5>
                                <p class="fs-6" style="color: #4b0082;"><?php echo $paseador->getTelefono(); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-purple {
            background-color: #4b0082;
            color: white;
            border-radius: 12px;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn-purple:hover {
            background-color: #6a0dad;
            color: white;
            text-decoration: none;
        }

        .navbar-toggler {
            border: 1px solid rgba(255,255,255,0.3);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
</body>