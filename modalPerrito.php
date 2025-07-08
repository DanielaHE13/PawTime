<?php
require("logica/Administrador.php");
require("logica/Paseo.php");
require("logica/Paseador.php");
require("logica/Perro.php");
require("logica/Estado.php");
require("logica/Raza.php");
require("logica/Propietario.php");
require_once("persistencia/conexion.php");
$idP = $_GET ['idPerrito'];
$perro = new Perro($idP);
$perro -> consultar();
$propietario = new Propietario($perro ->getIdPropietario());
$propietario ->consultar();
$raza =  new Raza($perro ->getIdRaza());
$raza ->consultar();
$foto_path = "imagen/perros/" . $perro->getFoto();
?>
<style>
  .text-dark-purple {
    color: #4b0082;
  }
  .bg-light-lavender {
    background-color: #E3CFF5;
  }
  .border-dark-purple {
    border-color: #4b0082;
  }
  .text-purple-900 {
    color: #4b0082;
  }
  .text-purple-700 {
    color: #6a0dad;
  }
  .text-purple-600 {
    color: #8a2be2;
  }
  .table-row-hover:hover {
    background-color: #E3CFF5 !important;
    transition: background-color 0.3s ease;
  }
  .btn-custom-purple {
    background-color: #4b0082;
    color: white;
    border: none;
    transition: background-color 0.3s ease;
  }
  .btn-custom-purple:hover {
    background-color: #3a006a;
    color: white;
  }
</style>

<div class="modal-content">
    <div class="modal-header" style='color: #4b0082;'>
        <h4 class="modal-title text-2xl font-weight-bold">Información del Perrito</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <div class="modal-body">
        <div class="text-center mb-4">
            <img class="rounded-circle mb-3 img-fluid border border-3 border-dark-purple" src="<?php echo $foto_path; ?>" alt="Foto de <?php echo $perro->getNombre(); ?>" style="width: 150px; height: 150px; object-fit: cover;">
        </div>
        <table class="table table-striped table-hover">
            <tbody>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>Nombre</th>
                    <td class="py-2 px-4"><?php echo $perro->getNombre() ?></td>
                </tr>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>Raza</th>
                    <td class="py-2 px-4"><?php echo $raza->getNombre() ?></td>
                </tr>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>Observaciones</th>
                    <td class="py-2 px-4"><?php echo !empty($perro->getObservaciones()) ? $perro->getObservaciones() : 'Sin observaciones especiales'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-custom-purple" data-bs-dismiss="modal">Cerrar</button>
    </div>
</div>

