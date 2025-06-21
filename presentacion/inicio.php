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
          <li class="nav-item"><a class="nav-link" href="#" style="color: #4b0082">Registrarse</a></li>
          <li class="nav-item">
            <a class="btn nav-link px-3 text-light rounded me-2" style="background-color: #4b0082;" href="?pid=<?php echo base64_encode('presentacion/autenticar.php') ?>">Iniciar sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div id="inicio" class="container-fluid" style="background: linear-gradient(to bottom, #E3CFF5 0%, #CFA8F5 30%, #9A5DFF 40%, #CFA8F5 90%, #E3CFF5 100%); padding: 80px 0; position: relative; overflow: hidden;">
  <div style="position: absolute; top: 20%; left: 10%; width: 400px; height: 400px; background: #b48aff; border-radius: 50%; z-index: 0;"></div>
  <div style="position: absolute; top: 0.5%; left: 45%; width: 350px; height: 350px; background: #d4bbf9; border-radius: 50%; opacity: 0.7; z-index: 0;"></div>
  <div style="position: absolute; top: 52%; left: 73%; transform: translate(-50%, -50%); width: 400px; height: 400px; background: #4b0082; border-radius: 50%; z-index: 0;"></div>
  <div class="container">
    <div class="row align-items-center text-center position-relative" style="z-index: 1;">
      <div class="col-md-3 d-flex justify-content-center" style="margin-top: 30px; margin-right: 5px;">
        <img src="imagen/perroFeliz.png" alt="Perro feliz" style="width: 300px; height: auto;">
      </div>
      <div class="col-md-4 position-relative" style="z-index: 2; margin-bottom: 100px; background-color: rgba(255, 255, 255, 0.4); padding: 30px; border-radius: 50px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
      ">
        <h2 class="fw-bold" style="font-size: 2rem; margin-bottom: 20px;">¡Haz feliz a tu mascota con los mejores paseos!</h2>
        <p style="font-size: 1.2rem;">Nuestros cuidadores garantizan seguridad, diversión y amor en cada aventura. 🐾</p>
        <a href="#" class="btn text-light mt-3 px-4 py-2" style="font-size: 1rem; background-color: #4b0082; border-radius: 12px;">Solicitar paseo</a>
      </div>
      <div class="col-md-4 d-flex justify-content-center" style="margin-top: 50px;">
        <img src="imagen/paseador.png" alt="Paseador" style="width: 600px; height: auto;">
      </div>
    </div>
  </div>
</div>
<div id="servicios" class="container-fluid py-5" style="background-color: #f8f0fc;">
  <div class="container">
    <h3 class="fw-bold mb-5 text-center" style="color: #4b0082;">¿Qué ofrecemos?</h3>
    <div class="row justify-content-center">
       <div class="col-md-4 col-sm-6 mb-4">
        <div class="card h-100 shadow-lg border-0" style="border-radius: 15px;">
          <img src="imagen/paseo.jpg" class="card-img-top" alt="Paseo individual" style="height: 200px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
          <div class="card-body text-center">
            <h5 class="card-title fw-semibold" style="color: #4b0082;">Horas de paseo</h5>
            <p class="card-text">El tiempo maximo de cada paseo es de 2 horas.</p>
          </div>
        </div>
        </div>
       <div class="col-md-4 col-sm-6 mb-4">
        <div class="card h-100 shadow-lg border-0" style="border-radius: 15px;">
          <img src="imagen/individual.jpg" class="card-img-top" alt="Paseo individual" style="height: 200px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
          <div class="card-body text-center">
            <h5 class="card-title fw-semibold" style="color: #4b0082;">Paseos individuales</h5>
            <p class="card-text">Tu perrito disfrutará de un paseo personalizado, con toda la atención y cuidado de nuestros expertos.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card h-100 shadow-lg border-0" style="border-radius: 15px;">
          <img src="imagen/dos_perritos.jpg" class="card-img-top" alt="Paseo grupal" style="height: 200px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
          <div class="card-body text-center">
            <h5 class="card-title fw-semibold" style="color: #4b0082;">Paseos grupales (dos perritos)</h5>
            <p class="card-text">Diversión en manada 🐶🐾. Ideal para perros sociables que aman hacer nuevos amigos mientras caminan.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="beneficios" class="container-fluid py-5" style="background-color: #E3CFF5; position: relative; overflow: hidden;">
  <div style="position: absolute; width: 350px; height: 350px; background-color: #4b0082; border-radius: 50%; top: -100px; left: -100px; z-index: 0;"></div>
  <div style="position: absolute; width: 500px; height: 500px; background-color: #4b0082; border-radius: 50%; bottom: -150px; right: -150px; opacity: 0.2; z-index: 0;"></div>
  <div class="container" style="position: relative; z-index: 1;">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <img src="imagen/beneficios.jpg" alt="Beneficios PawTime" width="50%" height="50%" style="margin-left: 50%;" class="rounded shadow">
      </div>
      <div class="col-lg-6">
        <h3 class="fw-bold mb-4" style="color: #4b0082; font-size: 2rem;">¿Por qué elegirnos?</h3>
        <div class="d-flex align-items-start mb-3">
          <img src="imagen/paseador_certificado.png" alt="Verificado" width="40" class="me-3">
          <p class="mb-0 fs-5">Paseadores verificados y capacitados</p>
        </div>
        <div class="d-flex align-items-start mb-3">
          <img src="imagen/seguridad.png" alt="Seguro" width="40" class="me-3">
          <p class="mb-0 fs-5">Seguro para tu mascota</p>
        </div>
        <div class="d-flex align-items-start">
          <img src="imagen/horario.png" alt="Horarios flexibles" width="40" class="me-3">
          <p class="mb-0 fs-5">Horarios flexibles</p>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="contacto" class="container-fluid py-5" style="background: linear-gradient(to bottom, #f8f0fc 0%, #f8f0fc 20%, #E3CFF5 80%, #f8f0fc 90%, #f8f0fc 100%)">
  <div class="container">
    <h2 class="fw-bold mb-3 text-center" style="color: #4b0082;">¿Listo para apoyar la vida saludable de tu perrito?</h2>
    <p class="mb-5 fs-5 text-muted text-center">Contáctanos para resolver tus dudas 🐶💜</p>

    <div class="d-flex justify-content-center">
      <div class="px-4 py-3" style="background-color: #ffffffdd; border-radius: 15px; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <h5 class="fw-semibold mb-3 text-center" style="color: #4b0082;">Información de contacto</h5>
        <ul style="list-style: none; padding-left: 0;">
          <li><strong>📍 Dirección:</strong> Calle 123, Ciudad Mascota</li>
          <li><strong>📞 Teléfono:</strong> +57 300 123 4567</li>
          <li><strong>📧 Email:</strong> contacto@pawtime.com</li>
          <li><strong>🕒 Horario:</strong> Lunes a Sábado, 8:00am – 6:00pm</li>
        </ul>
      </div>
    </div>
  </div>
</div>
<footer class="text-white text-center py-3 mt-auto" style="background-color: #4b0082;">
    <div class="container">
      <small>&copy; 2025 PawTime™</small>
    </div>
  </footer>
</body>
