<?php
require("logica/administrador.php");
require("logica/paseador.php");
require_once("persistencia/conexion.php");
$idP = $_GET ['idPaseador'];
$paseador = new Paseador($idP);
$paseador -> consultar();
?>
<div class="modal-header" style='color: #4b0082;'>
	<h4 class="modal-title">Editar Paseador</h4>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
	<form id="form" action="indexAjax.php?pid=<?php echo base64_encode('presentacion/paseador/editarPaseadorAjaxSimple.php'); ?>" method="post">
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Número de identificación*</label>
         <input type="text" class="form-control" name="idP" value="<?php echo $paseador ->getId(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Nombre*</label>
         <input type="text" class="form-control" name="nombre" value="<?php echo $paseador ->getNombre(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Apellido*</label>
         <input type="text" class="form-control" name="apellido" value="<?php echo $paseador ->getApellido(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Teléfono*</label>
         <input type="text" class="form-control" name="telefono" value="<?php echo $paseador ->getTelefono(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Correo*</label>
         <input type="email" class="form-control" name="correo" value="<?php echo $paseador ->getCorreo(); ?>" required>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold" style="color: #4b0082;">Tarifa*</label>
         <input type="number" step="0.01" min="0" class="form-control" name="tarifa" value="<?php echo $paseador ->getTarifa(); ?>" required>
         <small class="form-text text-muted">Ingrese la tarifa por hora en pesos colombianos</small>
      </div>
      <div class="form-group mb-3">
         <label class="fw-semibold d-block mb-2" style="color: #4b0082;">Estado*</label>
              
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="estado" id="habilitado" value="1" <?php echo ($paseador->getEstado() == 1) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="habilitado" style="color: #4b0082;">Habilitado</label>
          </div>
            
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="estado" id="inhabilitado" value="0" <?php echo ($paseador->getEstado() == 0) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="inhabilitado" style="color: #4b0082;">Inhabilitado</label>
          </div>
      </div>
      <button type="submit" class="btn" name="crear" style="background-color: #4b0082; color: white; border-radius: 10px;">
        <i class="fa-solid fa-save me-2"></i>Guardar cambios
      </button>
  </form>
</div>

<script>
$(document).ready(function() {
    $('#form').on('submit', function(e) {
        e.preventDefault();
        
        // Validaciones del lado cliente
        const nombre = $('input[name="nombre"]').val().trim();
        const apellido = $('input[name="apellido"]').val().trim();
        const telefono = $('input[name="telefono"]').val().trim();
        const correo = $('input[name="correo"]').val().trim();
        const tarifa = $('input[name="tarifa"]').val().trim();
        const estado = $('input[name="estado"]:checked').val();
        
        // Validar campos vacíos
        if (!nombre || !apellido || !telefono || !correo || !tarifa || !estado) {
            const alertError = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 400px;">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <strong>Atención:</strong> Todos los campos son requeridos.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('body').append(alertError);
            setTimeout(function() { $('.alert-warning').fadeOut(); }, 3000);
            return;
        }
        
        // Validar formato de correo
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(correo)) {
            const alertError = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 400px;">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <strong>Atención:</strong> El formato del correo electrónico no es válido.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('body').append(alertError);
            setTimeout(function() { $('.alert-warning').fadeOut(); }, 3000);
            return;
        }
        
        // Validar tarifa
        if (isNaN(tarifa) || parseFloat(tarifa) < 0) {
            const alertError = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 400px;">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    <strong>Atención:</strong> La tarifa debe ser un número válido mayor o igual a 0.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('body').append(alertError);
            setTimeout(function() { $('.alert-warning').fadeOut(); }, 3000);
            return;
        }
        
        // Mostrar indicador de carga
        const btnSubmit = $(this).find('button[type="submit"]');
        const originalText = btnSubmit.html();
        btnSubmit.html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Guardando...');
        btnSubmit.prop('disabled', true);
        
        // Enviar formulario
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize() + '&crear=1', // Agregar el parámetro crear manualmente
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                
                // Cerrar modal
                $('#modalPaseador').modal('hide');
                
                if(response.success) {
                    // Mostrar mensaje de éxito
                    const alertSuccess = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 400px;">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            <strong>¡Éxito!</strong> ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    $('body').append(alertSuccess);
                    
                    // Actualizar tabla de paseadores
                    $("#resultados").load("indexAjax.php?pid=<?php echo base64_encode('presentacion/paseador/actualizarPaseadorAjax.php'); ?>");
                } else {
                    // Mostrar mensaje de error
                    const alertError = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 400px;">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            <strong>Error:</strong> ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    $('body').append(alertError);
                }
                
                // Ocultar alerta después de 4 segundos
                setTimeout(function() {
                    $('.alert').fadeOut();
                }, 4000);
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                // Cerrar modal
                $('#modalPaseador').modal('hide');
                
                let errorMessage = 'No se pudo actualizar la información del paseador. Inténtalo de nuevo.';
                
                // Intentar obtener mensaje de error del servidor
                try {
                    const response = JSON.parse(xhr.responseText);
                    if(response && response.message) {
                        errorMessage = response.message;
                    }
                } catch(e) {
                    if(xhr.responseText) {
                        errorMessage = `Error del servidor (${xhr.status}): ${xhr.responseText.substring(0, 100)}...`;
                    }
                }
                
                // Mostrar mensaje de error
                const alertError = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 400px;">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        <strong>Error:</strong> ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $('body').append(alertError);
                
                // Ocultar alerta después de 6 segundos para errores
                setTimeout(function() {
                    $('.alert-danger').fadeOut();
                }, 6000);
            },
            complete: function() {
                // Restaurar botón
                btnSubmit.html(originalText);
                btnSubmit.prop('disabled', false);
            }
        });
    });
});
</script>
