<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PawTime</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v6.7.2/css/all.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Mukta', sans-serif;
      background: linear-gradient(to bottom, #E3CFF5, #CFA8F5);
      overflow-x: hidden;
    }
    .navbar-brand {
      font-weight: bold;
    }
    .btn-purple {
      background-color: #4b0082;
      color: white;
      border-radius: 12px;
    }
    .btn-purple:hover {
      background-color: #6a0dad;
    }
    .section-bg {
      background: linear-gradient(to bottom, #E3CFF5 0%, #CFA8F5 30%, #9A5DFF 40%, #CFA8F5 90%, #E3CFF5 100%);
    }
    .hero-panel {
      background-color: rgba(255, 255, 255, 0.4);
      padding: 30px;
      border-radius: 50px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg" style="background-color: #E3CFF5;">
    <div class="container-fluid">
      <a class="navbar-brand" style="color: #4b0082" href="#">
        <img src="imagen/logo.png" alt="Logo" width="65" height="64" class="d-inline-block align-text-top">
        PawTime
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link active" href="#inicio" style="color: #4b0082">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="#servicios" style="color: #4b0082">Servicios</a></li>
          <li class="nav-item"><a class="nav-link" href="#beneficios" style="color: #4b0082">Beneficios</a></li>
          <li class="nav-item"><a class="nav-link" href="#contacto" style="color: #4b0082">Contacto</a></li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" style="color: #4b0082" href="?pid=<?php echo base64_encode('presentacion/registrarse.php'); ?>">Registrarse</a>
          </li>
          <li class="nav-item">
            <a href="?pid=<?php echo base64_encode('presentacion/autenticar.php'); ?>" class="btn btn-purple fw-bold px-3 py-2 ms-2">Iniciar sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <section id="inicio" class="container-fluid section-bg py-5">
    <div class="container">
      <div class="row align-items-center text-center">
        <div class="col-md-3 mb-4">
          <img src="imagen/perroFeliz.png" class="img-fluid" alt="Perro feliz">
        </div>
        <div class="col-md-6 mb-4 hero-panel">
          <h2 class="fw-bold" style="color:#4b0082">¡Haz feliz a tu mascota con los mejores paseos!</h2>
          <p class="fs-5">Nuestros cuidadores garantizan seguridad, diversión y amor en cada aventura. 🐾</p>
          <a href="?pid=<?php echo base64_encode('presentacion/autenticar.php'); ?>" class="btn btn-purple mt-3 px-4 py-2">Solicitar paseo</a>
        </div>
        <div class="col-md-3 mb-4">
          <img src="imagen/paseador.png" class="img-fluid" alt="Paseador">
        </div>
      </div>
    </div>
  </section>

  <section id="servicios" class="py-5" style="background-color: #f8f0fc;">
    <div class="container">
      <h3 class="fw-bold text-center mb-5" style="color: #4b0082;">¿Qué ofrecemos?</h3>
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
          <div class="card h-100 shadow border-0 rounded-4">
            <img src="imagen/paseo.jpg" class="card-img-top rounded-top-4" alt="Horas de paseo">
            <div class="card-body text-center">
              <h5 class="fw-semibold" style="color: #4b0082;">Horas de paseo</h5>
              <p>El tiempo máximo de cada paseo es de 2 horas.</p>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100 shadow border-0 rounded-4">
            <img src="imagen/individual.jpg" class="card-img-top rounded-top-4" alt="Paseos individuales">
            <div class="card-body text-center">
              <h5 class="fw-semibold" style="color: #4b0082;">Paseos individuales</h5>
              <p>Tu perrito disfrutará de un paseo personalizado con toda la atención de nuestros expertos.</p>
            </div>
          </div>
        </div>
        <div class="col">
          <div class="card h-100 shadow border-0 rounded-4">
            <img src="imagen/dos_perritos.jpg" class="card-img-top rounded-top-4" alt="Paseos grupales">
            <div class="card-body text-center">
              <h5 class="fw-semibold" style="color: #4b0082;">Paseos grupales (dos perritos)</h5>
              <p>Diversión en manada 🐶🐾. Ideal para perros sociables que aman hacer nuevos amigos.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="beneficios" class="py-5" style="background-color: #E3CFF5;">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <img src="imagen/beneficios.jpg" alt="Beneficios PawTime" class="img-fluid rounded shadow">
        </div>
        <div class="col-lg-6">
          <h3 class="fw-bold mb-4" style="color: #4b0082;">¿Por qué elegirnos?</h3>
          <ul class="list-unstyled fs-5">
            <li class="mb-3"><i class="fas fa-check-circle me-2 text-purple"></i>Paseadores verificados y capacitados</li>
            <li class="mb-3"><i class="fas fa-check-circle me-2 text-purple"></i>Seguro para tu mascota</li>
            <li><i class="fas fa-check-circle me-2 text-purple"></i>Horarios flexibles</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section id="contacto" class="py-5" style="background-color: #f8f0fc;">
    <div class="container">
      <h2 class="fw-bold text-center mb-3" style="color: #4b0082;">¿Listo para apoyar la vida saludable de tu perrito?</h2>
      <p class="text-muted text-center mb-5">Contáctanos para resolver tus dudas 🐶💜</p>
      <div class="d-flex justify-content-center">
        <div class="bg-white p-4 rounded-4 shadow" style="max-width: 500px;">
          <ul class="list-unstyled">
            <li><strong>📍 Dirección:</strong> Calle 123, Ciudad Mascota</li>
            <li><strong>📞 Teléfono:</strong> +57 300 123 4567</li>
            <li><strong>📧 Email:</strong> contacto@pawtime.com</li>
            <li><strong>🕒 Horario:</strong> Lunes a Sábado, 8:00am – 6:00pm</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <footer class="text-white text-center py-3" style="background-color: #4b0082;">
    <div class="container">
      <small>&copy; 2025 PawTime™</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>