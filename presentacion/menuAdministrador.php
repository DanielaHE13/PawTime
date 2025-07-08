<?php
$id = $_SESSION["id"];
$administrador = new Administrador($id);
$administrador->consultar();
?>

<div class="navbar navbar-expand-lg py-2 px-4 flex-wrap"
     style="margin: 10px auto; max-width: 98%; background-color: #E3CFF5; border-radius: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 0px;">


    <div class="d-flex align-items-center">
      <div>
        <h3 class="mb-1">
          <strong style="color: #4b0082;">Hola <?php echo $administrador->getNombre() . " " . $administrador->getApellido(); ?></strong>
          <a href="?pid=<?php echo base64_encode('presentacion/administrador/editarPerfil.php'); ?>"
             class="btn btn-sm text-white fw-bold ms-2"
             style="background-color: #4b0082; border-radius: 10px; margin-right: 20px;">
            <i class="fa-solid fa-pen-to-square me-1"></i>
          </a>
        </h3>
        <p class="mb-0 text-sm" style="color: #4b0082;">
          <i class="fa-solid fa-envelope me-2"></i><?php echo $administrador->getCorreo(); ?>
        </p>
      </div>
    </div>
    
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
  <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarSupportedContent">
    <ul class="navbar-nav ms-auto">
	
	<li class="nav-item me-4">
      <a class="nav-link fw-bold fs-5" 
         href="?pid=<?php echo base64_encode('presentacion/estadisticas/estadisticasGeneral.php'); ?>" 
         style="color: #4b0082; border-radius: 12px;">
        <i class="fa-solid fa-chart-line me-1"></i> Estadisticas
      </a>
    </li>

	
      <li class="nav-item dropdown me-4">
        <a class="nav-link dropdown-toggle fw-bold fs-5" href="#" role="button" data-bs-toggle="dropdown"
           aria-expanded="false" style="color: #4b0082; border-radius: 12px;">
          <i class="fa-solid fa-person-walking me-1"></i> Paseador
        </a>
        <ul class="dropdown-menu" style="background-color: #E3CFF5;">
          <li><a class="dropdown-item" style="color: #4b0082;" href="?pid=<?php echo base64_encode('presentacion/paseador/consultarPaseador.php'); ?>">Consultar</a></li>
          <li><a class="dropdown-item" style="color: #4b0082;" href="?pid=<?php echo base64_encode('presentacion/paseador/crearPaseador.php'); ?>">Crear</a></li>
        </ul>
      </li>
    
      <li class="nav-item dropdown me-4">
        <a class="nav-link dropdown-toggle fs-5 fw-bold" href="#" role="button" data-bs-toggle="dropdown"
           aria-expanded="false" style="color: #4b0082; border-radius: 12px;">
          <i class="fa-solid fa-user me-1"></i><i class="fa-solid fa-dog"></i> Propietario
        </a>
        <ul class="dropdown-menu" style="background-color: #E3CFF5;">
          <li><a class="dropdown-item" style="color: #4b0082;" href="?pid=<?php echo base64_encode('presentacion/propietario/consultarPropietario.php'); ?>">Consultar</a></li>
          <li><a class="dropdown-item" style="color: #4b0082;" href="?pid=<?php echo base64_encode('presentacion/propietario/crearPropietario.php'); ?>">Crear</a></li>
        </ul>
      </li>
    
      <li class="nav-item dropdown me-4">
        <a class="nav-link dropdown-toggle fw-bold fs-5" href="#" role="button" data-bs-toggle="dropdown"
           aria-expanded="false" style="color: #4b0082; border-radius: 12px;">
          <i class="fa-solid fa-person-walking me-1"></i><i class="fa-solid fa-dog"></i> Paseo
        </a>
        <ul class="dropdown-menu" style="background-color: #E3CFF5;">
          <li><a class="dropdown-item" style="color: #4b0082;" href="?pid=<?php echo base64_encode('presentacion/paseo/consultarPaseo.php'); ?>">Consultar</a></li>
        </ul>
      </li>
    
      <li class="nav-item d-flex align-items-center">
        <a href="#" class="btn text-white fw-bold px-4 py-2 ms-3"
           style="background-color: #4b0082; border-radius: 12px;"
           data-bs-toggle="modal" data-bs-target="#modalCerrarSesion">
          <i class="fa-solid fa-right-from-bracket me-2 fa-lg"></i>Salir
        </a>
      </li>
    
    </ul>

  </div>
</div>

    <div class="modal fade" id="modalCerrarSesion" tabindex="-1" aria-labelledby="modalCerrarSesionLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; background-color: #f8f0fc;">
          <div class="modal-header text-white" style="background-color: #4b0082; border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <h5 class="modal-title" id="modalCerrarSesionLabel">
              <i class="fa-solid fa-circle-exclamation me-2"></i>¿Cerrar sesión?
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <div class="modal-body text-center">

            <p class="fs-5 mt-3" style="color: #4b0082;">¿Estás segur@ de que deseas salir de PawTime?</p>
          </div>

          <div class="modal-footer justify-content-center">
            <a href="index.php?sesion=false" class="btn text-white fw-bold px-4"style="background-color: #4b0082;">
              <i class="fa-solid fa-door-open me-2" style="background-color: #4b0082; border-radius: 12px;"></i> Sí, cerrar sesión
            </a>
            <button type="button" class="btn text-white fw-bold px-4" style="background-color:rgb(173, 106, 221); border-radius: 12px;" data-bs-dismiss="modal">
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
