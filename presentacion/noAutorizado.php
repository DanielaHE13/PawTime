<?php
// Redirigir si ya está autenticado
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>No autorizado - PawTime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <style>
        body {
            background: linear-gradient(to bottom, #E3CFF5, #CFA8F5);
            font-family: 'Mukta', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background-color: #ffffffdd;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 90%;
        }

        h1 {
            color: #4b0082;
            font-weight: bold;
        }

        p {
            font-size: 1.1rem;
            color: #4b0082;
        }

        .btn-purple {
            background-color: #4b0082;
            color: white;
            border-radius: 12px;
            font-weight: bold;
            padding: 10px 20px;
            text-decoration: none;
        }

        .btn-purple:hover {
            background-color: #6a0dad;
            color: white;
        }
    </style>
</head>

<body>
    <div class="card ">
        <div class="d-flex justify-content-center">
            <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <dotlottie-player src="https://lottie.host/3a48f122-58ee-4d24-bafc-63259afed4df/3Mnv6QJqob.lottie" background="transparent" speed="1" style="width: 300px; height: 300px" loop autoplay></dotlottie-player>
        </div>
        <h1>⚠️ Acceso no autorizado</h1>
        <p>Tu rol no tiene permiso para ingresar a esta sección del sitio.</p>

        <a href="?pid=<?php echo base64_encode('presentacion/autenticar.php'); ?>" class="btn btn-purple mt-3">
            Volver al inicio de sesión
        </a>
    </div>
</body>

</html>