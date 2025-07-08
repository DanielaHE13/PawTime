<?php
if($_SESSION["rol"] != "paseador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
$id=$_SESSION['id'];
$mensaje = false;
$debugInfo = "";
if(isset($_GET["idPaseo"])){
    $idPaseo= $_GET["idPaseo"];
    if(isset($_GET["estado"])){
        $estado= $_GET["estado"];
    }
    $estadoPaseo = new Paseo($idPaseo);
    $estadoPaseo->consultar(); // Obtener los datos del paseo
    
    // DEBUG: Mostrar información del paseo
    require_once(__DIR__ . "/../../logica/ServicioPaseo.php");
    $paseosDebug = ServicioPaseo::debugContarPaseos(
        $_SESSION["id"], 
        $estadoPaseo->getFecha(), 
        $estadoPaseo->getHora_inicio(), 
        $estadoPaseo->getHora_fin()
    );
    
    $debugInfo = "<div class='alert alert-info'><strong>DEBUG:</strong><br>";
    $debugInfo .= "Paseo a aceptar: ID " . $idPaseo . ", Fecha: " . $estadoPaseo->getFecha() . ", Hora: " . $estadoPaseo->getHora_inicio() . " - " . $estadoPaseo->getHora_fin() . "<br>";
    $debugInfo .= "Paseos existentes del paseador " . $_SESSION["id"] . ":<br>";
    foreach ($paseosDebug as $paseo) {
        $debugInfo .= "- ID: " . $paseo['id'] . ", Estado: " . $paseo['estado_nombre'] . " (" . $paseo['estado_id'] . "), Hora: " . $paseo['hora_inicio'] . " - " . $paseo['hora_fin'] . "<br>";
    }
    $debugInfo .= "</div>";
    
    // Verificar si ya hay 2 paseos aceptados en el mismo horario
    $aceptados = $estadoPaseo->contarPaseosAceptadosSolapados(
        $_SESSION["id"], 
        $estadoPaseo->getFecha(), 
        $estadoPaseo->getHora_inicio(), 
        $estadoPaseo->getHora_fin()
    );
    
    $debugInfo .= "<div class='alert alert-warning'>Paseos solapados encontrados: " . $aceptados . "</div>";
    
    if($aceptados >= 2){
        $mensaje = true;
    }else{
        $estadoPaseo ->actualizarEstadoPaseo($estado);  
    }
}
?>
<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">

<?php 
include ('presentacion/encabezado.php');
include ('presentacion/menuPaseador.php');
?>
<div class="container mt-4">
    <div class="row g-4">
<?php 
// Mostrar información de debug si está disponible
if (!empty($debugInfo)) {
    echo $debugInfo;
}

$paseo =  new Paseo();
$paseos = $paseo ->consultarPorPaseadorProgramados($_SESSION["id"]);
if($mensaje){
    echo "<div class='alert alert-danger' role='alert' style='color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; font-weight: bold;'>
        <i class='fa-solid fa-exclamation-triangle me-2'></i>
        🚫 <strong>No puedes aceptar este paseo</strong><br>
        Ya tienes programados <strong>2 paseos simultáneos</strong> para el mismo horario en esta fecha. 
        <br>Por la seguridad y bienestar de los perritos, el límite máximo es de <strong>2 perros por paseo</strong>. 🐶💜
        <br><em>Intenta programar el paseo en un horario diferente.</em>
    </div>";
}
if(empty($paseos)){
    echo '
    <div class="container mt-5">
        <div class="alert alert-warning text-center shadow" role="alert" style="background-color: #FFF3CD; color: #856404;">
            <i class="fa-solid fa-dog fa-lg me-2"></i>
            ¡No tienes paseos programados por el momento!
        </div>
    </div>
    ';
}else{
foreach ($paseos as $p){
    $perro =  new Perro($p->getPerro_idPerro()->getId());
    $perro ->consultar();
    $foto_path = "imagen/perros/" . $perro->getFoto();
?>
 <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 h-100">
                <div class="text-center pt-4">
                    <img src="<?php echo $foto_path; ?>" class="card-img-top border border-3 border-purple" alt="Foto de <?php echo $perro->getNombre(); ?>" style="width: 140px; height: 140px; object-fit: cover;">
             
                </div>
                <div class="card-body px-4">
                	<h5 class="mt-3 text-purple fw-bold"><?php echo $perro->getNombre(); ?></h5>
                    <h5 class="card-title fw-bold text-purple mb-3">Paseo N° <?php echo $p->getId(); ?></h5>
                    <p class="mb-1"><i class="fa-regular fa-calendar-days text-purple me-2"></i><strong>Fecha:</strong> <?php echo $p->getFecha(); ?></p>
                    <p class="mb-1"><i class="fa-regular fa-clock text-purple me-2"></i><strong>Hora:</strong> <?php echo $p->getHora_inicio() . " - " . $p->getHora_fin(); ?></p>
                    <p class="mb-3"><i class="fa-solid fa-dollar-sign text-purple me-2"></i><strong>Precio:</strong> $<?php echo number_format($p->getPrecio_total(), 0, ',', '.'); ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="modalPaseo2.php?idPaseo=<?php echo $p->getId(); ?>&idPaseador=<?php echo $id; ?>" class="btn btn-outline-purple abrir-modal" data-id="<?php echo $p->getId(); ?>" title="Ver más del paseo">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="modalPerrito.php?idPerrito=<?php echo $p->getPerro_idPerro()->getId(); ?>" class="btn btn-outline-purple abrir-modal" data-id="<?php echo $p->getPerro_idPerro()->getId(); ?>" title="Ver info del perrito">
                            <i class="fa-solid fa-dog"></i>
                        </a>
                        <a href="modalAceptarPaseo.php?idPaseo=<?php echo $p->getId(); ?>"
                           class="btn btn-success abrir-modal"
                           data-id="<?php echo $p->getId(); ?>"
                           title="Aceptar paseo">
                           <i class="fa-solid fa-check"></i>
                        </a>
                        <a href="modalCancelarPaseo.php?idPaseo=<?php echo $p->getId(); ?>"
                           class="btn btn-danger abrir-modal"
                           data-id="<?php echo $p->getId(); ?>"
                           title="Cancelar paseo">
                           <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php } }?>
    </div>
</div>

<div class="modal fade" id="modalPaseo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modalContent">
        </div>
    </div>
</div>

<style>
    .text-purple {
        color: #4b0082;
    }
    .btn-outline-purple {
        border-color: #4b0082;
        color: #4b0082;
        border-radius: 10px;
    }
    .btn-outline-purple:hover {
        background-color: #4b0082;
        color: white;
    }
    .border-purple {
        border-color: #4b0082 !important;
    }
</style>
<script>
$(document).ready(function(){
 $('body').on('click', '.abrir-modal', function(e) {
    e.preventDefault();    
    const url = $(this).attr('href');
    $("#modalContent").load(url, function() {
      const modal = new bootstrap.Modal(document.getElementById('modalPaseo'));
      modal.show();
    });
  });
  document.getElementById('modalPaseo').addEventListener('hidden.bs.modal', function () {
  document.getElementById('modalContent').innerHTML = '';
  });
});
</script>
</body>
