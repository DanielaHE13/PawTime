 <?php
$id = $_SESSION["id"];
$paseador = new Paseador($id);
$paseador->consultar();
?>

<div class="navbar d-flex justify-content-between align-items-center py-3 px-4 flex-wrap"
  style="margin: 10px auto; max-width: 98%; background-color: #E3CFF5; border-radius: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 0px;">

  <!-- Sección de perfil -->
  <div class="col-12 col-md-6 d-flex align-items-center mb-3 mb-md-0">

    <div class="text-center" style="margin-left: 30px; margin-right: 30px;">
      <img src="imagen/<?php echo !empty($paseador->getFoto()) ? $paseador->getFoto() : 'default.jpg'; ?>"
        alt="Foto Paseador"
        class="rounded-circle shadow"
        style="width: 100px; height: 100px; object-fit: cover;">
      
      <div class="mt-2">
        <a href="?pid=<?php echo base64_encode('presentacion/paseador/editarFoto.php'); ?>"
          class="btn btn-sm text-white fw-bold"
          style="background-color: #4b0082; border-radius: 10px; font-size: 12px;">
          <i class="fa-solid fa-camera me-1"></i>Editar foto
        </a>
      </div>
    </div>

    <div>
      <h3 class="mb-1">
        <strong style="color: #4b0082;">Hola <?php echo $paseador->getNombre() . " " . $paseador->getApellido(); ?></strong>
        <a href="?pid=<?php echo base64_encode('presentacion/paseador/editarPerfil.php'); ?>"
          class="btn btn-sm text-white fw-bold ms-2"
          style="background-color: #4b0082; border-radius: 10px; margin-right: 20px;">
          <i class="fa-solid fa-pen-to-square me-1"></i>
        </a>
        <br>
      </h3>

      <p class="mb-0 text-sm" style="color: #4b0082;">
        <i class="fa-solid fa-envelope me-2"></i><?php echo $paseador->getCorreo(); ?>
      </p>
      <p class="mb-0 text-sm" style="color: #4b0082;">
        <i class="fa-solid fa-phone me-2"></i><?php echo $paseador->getTelefono(); ?>
      </p>
      <p class="mb-0 text-sm" style="color: #4b0082;">
        <i class="fa-solid fa-dollar-sign me-2"></i>Tarifa: $<?php echo number_format($paseador->getTarifa(), 0, ',', '.'); ?>
      </p>
    </div>

  </div>
      <div class="col-12 col-md-6 d-flex flex-wrap justify-content-center justify-content-md-end gap-3">
        <div class="dropdown">
      <button class="btn fw-bold px-4 py-2 dropdown-toggle"
              type="button"
              id="dropdownSolicitudes"
              data-bs-toggle="dropdown"
              aria-expanded="false"
              style="color: #4b0082; border-radius: 12px;">
        <i class="fa-solid fa-list me-2 fa-lg"></i>Gestionar paseos
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownSolicitudes">
        <li>
          <a class="dropdown-item"
             href="?pid=<?php echo base64_encode('presentacion/paseador/solicitudesPaseo.php') ?>">
             <i class="fa-solid fa-inbox me-2 text-purple"></i>Solicitudes
          </a>
        </li>
        <li>
          <a class="dropdown-item"
             href="?pid=<?php echo base64_encode('presentacion/paseador/paseosPendientes.php') ?>">
             <i class="fa-solid fa-calendar-check me-2 text-purple"></i>Pendientes
          </a>
        </li>
        <li>
          <a class="dropdown-item"
             href="?pid=<?php echo base64_encode('presentacion/paseador/paseosCompletados.php') ?>">
             <i class="fa-solid fa-check me-2 text-purple"></i>Completados
          </a>
        </li>
      </ul>
    </div>

    <a href="?pid=<?php echo base64_encode('presentacion/paseador/editarPerfil.php') ?>"
      class="btn fw-bold px-4 py-2"
      style="color: #4b0082; border-radius: 12px;">
      <i class="fa-solid fa-user-edit me-2 fa-lg"></i>Mi Perfil
    </a>

    <a href="?pid=<?php echo base64_encode('presentacion/paseador/consultarPaseo.php') ?>"
      class="btn fw-bold px-4 py-2"
      style="color: #4b0082; border-radius: 12px;">
      <i class="fa-solid fa-history me-2 fa-lg"></i>Historial de paseos
    </a>
    
    <a href="?pid=<?php echo base64_encode('presentacion/estadisticas/estadisticasGeneral.php') ?>"
      class="btn fw-bold px-4 py-2"
      style="color: #4b0082; border-radius: 12px;">
      <i class="fa-solid fa-chart-line me-2 fa-lg"></i>Estadisticas general
    </a>
    
    <a href="?pid=<?php echo base64_encode('presentacion/estadisticas/estadisticasPropias.php') ?>"
      class="btn fw-bold px-4 py-2"
      style="color: #4b0082; border-radius: 12px;">
      <i class="fa-solid fa-chart-line me-2 fa-lg"></i>Mis estadisticas
    </a>

    <!-- Botón que abre el modal de cerrar sesión -->
    <a href="#" class="btn fw-bold px-4 py-2"
      style="color: #4b0082; border-radius: 12px;"
      data-bs-toggle="modal" data-bs-target="#modalCerrarSesion">
      <i class="fa-solid fa-right-from-bracket me-2 fa-lg"></i>Salir
    </a>

    <!-- Modal de Confirmación de Cierre de Sesión -->
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
            <a href="index.php?sesion=false" class="btn text-white fw-bold px-4" style="background-color: #4b0082; border-radius: 12px;">
              <i class="fa-solid fa-door-open me-2"></i> Sí, cerrar sesión
            </a>
            <a href="?pid=<?php echo base64_encode('presentacion/paseador/sesionPaseador.php'); ?>" class="btn text-white fw-bold px-4" style="background-color:rgb(173, 106, 221); border-radius: 12px;">
              Cancelar
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>