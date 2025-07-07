<?php
if($_SESSION["rol"] != "paseador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    $id=$_SESSION['id'];
}
if(isset($_GET["idPaseo"])){
    $idPaseo= $_GET["idPaseo"];
    if(isset($_GET["estado"])){
        $estado= $_GET["estado"];
    }
    $estadoPaseo = new Paseo($idPaseo);
    $estadoPaseo ->actualizarEstadoPaseo($estado);
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
$paseo =  new Paseo();
$paseos = $paseo ->consultarPorPaseadorPendientes($_SESSION["id"]);
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
 <div class="col-12 col-md-5 col-lg-3">
            <div class="card shadow-lg border-0 rounded-3 h-100">
                <div class="text-center pt-4">
                    <img src="<?php echo $foto_path; ?>" class="card-img-top border border-3 border-purple" alt="Foto de <?php echo $perro->getNombre(); ?>" style="width: 140px; height: 140px; object-fit: cover;">
             
                </div>
                <div class="card-body p-3">
                	<h6 class="text-purple fw-bold mb-1"><?php echo $perro->getNombre(); ?></h6>
                    <h6 class="fw-semibold text-purple mb-2">Paseo N° <?php echo $p->getId(); ?></h6>
                    <p class="mb-1 small"><i class="fa-regular fa-calendar-days text-purple me-2"></i><strong>Fecha:</strong> <?php echo $p->getFecha(); ?></p>
                    <p class="mb-1 small"><i class="fa-regular fa-clock text-purple me-2"></i><strong>Hora:</strong> <?php echo $p->getHora_inicio() . " - " . $p->getHora_fin(); ?></p>
                    <p class="mb-2 small"><i class="fa-solid fa-dollar-sign text-purple me-2"></i><strong>Precio:</strong> $<?php echo number_format($p->getPrecio_total(), 0, ',', '.'); ?></p>
        			<?php
                    $estado = $p->getEstado_idEstado();
                    $estadoClass = "";
                    
                    switch (strtolower($estado->getNombre())) {
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
                    
                    <p class="mb-3 small">
                        <i class="fa-solid fa-flag text-purple me-2"></i>
                        <strong>Estado:</strong>
                        <span class="<?php echo $estadoClass; ?>">
                            <?php echo $estado->getNombre(); ?>
                        </span>
                    </p>
        
                    <div class="d-flex justify-content-around align-items-center">
                        <a href="modalPaseo.php?idPaseo=<?php echo $p->getId(); ?>&idPaseador=<?php echo $id; ?>" 
                           class="btn btn-outline-purple abrir-modal" 
                           data-id="<?php echo $p->getId(); ?>" 
                           title="Ver más del paseo">
                           <i class="fas fa-eye"></i>
                        </a>
        
                        <a href="modalPerrito.php?idPerrito=<?php echo $p->getPerro_idPerro()->getId(); ?>" 
                           class="btn btn-outline-purple abrir-modal" 
                           data-id="<?php echo $p->getPerro_idPerro()->getId(); ?>" 
                           title="Ver info del perrito">
                           <i class="fa-solid fa-dog"></i>
                        </a>
                        <?php 
                        if(strtolower($estado->getNombre()) == "en curso"){
                        ?>
                        <a href="modalCompletarPaseo.php?idPaseo=<?php echo $p->getId(); ?>" 
                           class="btn btn-success abrir-modal" 
                           data-id="<?php echo $p->getId(); ?>" 
                           title="Completar paseo">
                           <i class="fa-solid fa-file-invoice"></i>
                        </a>
                        <?php }?>
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
