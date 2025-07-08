<?php
require("logica/Administrador.php");
require("logica/Paseo.php");
require("logica/Paseador.php");
require("logica/Perro.php");
require("logica/Estado.php");
require("logica/Raza.php");
require("logica/Propietario.php");
require_once("persistencia/conexion.php");
if(isset($_GET ['idPaseo'])){
$idP = $_GET ['idPaseo'];
}
if(isset($_GET['idPaseador'])){
$idPaseador = $_GET['idPaseador'];
}
$paseo = new Paseo($idP);
$paseo -> consultar();
$paseador =  new Paseador($paseo -> getPaseador_idPaseador());
$paseador -> consultar();
$perro = new Perro();
$perritos = $perro -> perritosPorPaseo($paseo ->getFecha(), $paseo ->getHora_inicio(), $idPaseador);
$estado = new Estado($paseo->getEstado_idEstado());
$estado ->consultar();
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
        <h4 class="modal-title text-2xl font-weight-bold">Detalles del Paseo</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-hover">
            <tbody>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>ID</th>
                    <td class="py-2 px-4"><?php echo $paseo -> getId() ?></td>
                </tr>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>Fecha</th>
                    <td class="py-2 px-4"><?php echo $paseo -> getFecha() ?></td>
                </tr>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>Hora inicio - Hora Fin</th>
                    <td class="py-2 px-4"><?php echo $paseo -> getHora_inicio() . " - " . $paseo -> getHora_fin() ?></td>
                </tr>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>Precio total</th>
                    <td class="py-2 px-4"><?php echo $paseo -> getPrecio_total() ?></td>
                </tr>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold text-start" style="color: #4b0082;">Paseador</th>
                    <td class="py-2 px-4 text-start">
                        <?php
                        $paseador_foto_path = "imagen/" . $paseador->getFoto();
                        $fallback_paseador_image = "https://placehold.co/80x80/4b0082/E3CFF5?text=👤";
                        ?>
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle"
                                 src="<?php echo $paseador_foto_path; ?>"
                                 alt="Foto de <?php echo $paseador->getNombre(); ?>"
                                 onerror="this.onerror=null;this.src='<?php echo $fallback_paseador_image; ?>';"
                                 style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px;">
                            <span class="fw-semibold"><?php echo $paseador->getNombre() . " " . $paseador->getApellido(); ?></span>
                        </div>
                    </td>
                </tr>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>Perro/s</th>
                    <td class="py-2 px-4">
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <?php
                            foreach ($perritos as $p) {
                                $foto_path = "imagen/perros/" . $p->getFoto();
                                ?>
                                <div class="d-flex flex-column align-items-center p-2 rounded" style="background-color: #E3CFF5;">
                                    <img class="rounded-circle mb-2 img-fluid" src="<?php echo $foto_path; ?>" alt="Foto de <?php echo $p->getNombre(); ?>" style="width: 120px; height: 120px; object-fit: cover;">
                                    <span class="mt-2 small font-weight-medium text-purple-900"><?php echo $p->getNombre(); ?></span>
                                    <span class="small text-purple-700"><?php echo $p->getIdRaza()->getNombre(); ?></span>
                                    <span class="small text-purple-600">Propietario: <?php echo $p->getIdPropietario()->getNombre() . " " . $p->getIdPropietario()->getApellido(); ?></span>
                                </div>
                                <?php
                                }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr class="table-row-hover">
                    <th class="py-2 px-4 font-weight-semibold" style='color: #4b0082;'>Estado</th>
                    <?php
                    $estadoClass = "";
                    
                    switch (strtolower($estado-> getNombre())) {
                        case "aceptado":
                            $estadoClass = "badge bg-warning text-dark";
                            break;
                        case "en curso":
                            $estadoClass = "badge bg-primary";
                            break;
                        case "completado":
                            $estadoClass = "badge bg-success";
                            break;
                        case "cancelado":
                            $estadoClass = "badge bg-danger";
                            break;
                        default:
                            $estadoClass = "badge bg-secondary";
                            break;
                    }
                    ?>
                    
                    <td>
                        <span class="<?php echo $estadoClass; ?>">
                            <?php echo $estado-> getNombre(); ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pie de página del modal -->
    <div class="modal-footer">
        <button type="button" class="btn btn-custom-purple" data-bs-dismiss="modal">Cerrar</button>
    </div>
</div>

