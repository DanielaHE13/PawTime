<?php
if($_SESSION["rol"] != "administrador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
if(isset($_POST["idP"])){
    $idPaseador = $_POST["idP"];
}
if(isset($_POST["nombre"])){
    $nombre = $_POST["nombre"];
}
if(isset($_POST["apellido"])){
    $apellido = $_POST["apellido"];
}
if(isset($_POST["telefono"])){
    $telefono = $_POST["telefono"];
}
if(isset($_POST["correo"])){
    $correo = $_POST["correo"];
}
if(isset($_POST["tarifa"])){
    $tarifa = $_POST["tarifa"];
}
if (isset($_POST["estado"])) {
    $estado = $_POST["estado"];
}
if(isset($_POST["crear"])){
    $paseador = new Paseador($idPaseador, $nombre, $apellido, $telefono, $correo, "", "", $tarifa, $estado);
    $paseador->editarPerfil();
}
?>
<body>
<?php 
include ('presentacion/encabezado.php');
include ('presentacion/menuAdministrador.php');
$paseador = new Paseador();
$paseadores = $paseador -> consultarTodos();
?>

<div class="container mt-1">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="input-group">
        <span class="input-group-text fw-bold" style="color: #4b0082; background-color: #E3CFF5; border-right: none;">
          <i class="fa-solid fa-magnifying-glass me-2"></i>Buscar paseador:
        </span>
        <input type="text" class="form-control" id="filtro" placeholder="Nombre, correo, etc." autocomplete="off"
               style="border-left: none;" />
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div id="resultados">
  	<h5 class="card-title" style="color: #4b0082;">Paseador</h5>
    	<table class="table class='table table-striped table-hover mt-3">
    		<tr>
    			<th scope="col">ID</th>
    			<th scope="col">Nombre</th>
    			<th scope="col">Apellido</th>
    			<th scope="col">Correo</th>
    			<th scope="col">Tarifa</th>
    			<th scope="col">Estado</th>
    			<th scope="col">Acciones</th>
    		</tr>
    		<?php
    		foreach ($paseadores as $p){
    		?>
            <tr>
               <th scope="row"><?php echo $p -> getId()?></th>
               <td><?php echo $p -> getNombre()?></td>
               <td><?php echo $p -> getApellido()?></td>
               <td><?php echo $p -> getCorreo()?></td>
               <td><?php echo $p -> getTarifa()?></td>
               <?php
                $estadoClass = "";
                
                if($p -> getEstado()==1) {
                        $estadoClass = "badge bg-success";
                        $estado = "Habilitado";
                }else{
                        $estadoClass = "badge bg-danger";
                        $estado = "Inhabilitado";
                }
                ?>
                
                <td>
                	<div id="estado<?php echo $p->getId(); ?>">
                	<span class="<?php echo $estadoClass; ?>"><?php echo $estado; ?></span>  
                	</div>              
                </td>
               <td>
               <div class="d-flex align-items-center gap-2">
               <a href="modalPaseador.php?idPaseador=<?php echo $p->getId(); ?>" class="abrir-modal" data-id="<?php echo $p->getId(); ?>" style="color: #4b0082;"><span class="fas fa-eye" title="Ver más información"></span></a>
               <a href="modalEditarPaseador.php?idPaseador=<?php echo $p->getId(); ?>" class="abrir-modal" data-id="<?php echo $p->getId(); ?>" style="color: #4b0082;"><span class="fa-solid fa-pen-to-square" title="Editar información"></span></a>
             	  <div class="d-flex gap-2">
                        <button type="button" id="1<?php echo $p->getId(); ?>" class="btn btn-success btn-sm rounded-circle btn-estado" data-id="<?php echo $p->getId(); ?>" data-estado="1" title="Habilitar paseador"<?php echo ($p -> getEstado() == 1) ? 'disabled' : ''; ?>>
                            <i class="fa-solid fa-user-check"></i>
                        </button>
                        <button type="button" id="0<?php echo $p->getId(); ?>" class="btn btn-danger btn-sm rounded-circle btn-estado" data-id="<?php echo $p -> getId()?>" data-estado="0" title="Inhabilitar paseador"<?php echo ($p -> getEstado() == 0) ? 'disabled' : ''; ?>>
                            <i class="fa-solid fa-user-slash"></i>
                        </button>
                    </div>
             	  </div>
             	</td>
            </tr>
            <?php }?>
       </table>
  </div>
</div>
<div class="modal fade" id="modalPaseador" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="modalContent">
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
	let tablaOriginal = $("#resultados").html();
	$("#filtro").keyup(function(){
		if($("#filtro").val().length > 2){
			var ruta = "indexAjax.php?pid=<?php echo base64_encode('presentacion/paseador/buscarPaseadorAjax.php'); ?>&filtro=" + $("#filtro").val().replaceAll(" ", "%20");
			console.log(ruta);
			$("#resultados").load(ruta);
		}else{
			$("#resultados").load("indexAjax.php?pid=<?php echo base64_encode('presentacion/paseador/actualizarPaseadorAjax.php'); ?>");
		}
	});
 $('body').on('click', '.abrir-modal', function(e) {
    e.preventDefault();    
    const url = $(this).attr('href');
    $("#modalContent").load(url, function() {
      const modal = new bootstrap.Modal(document.getElementById('modalPaseador'));
      modal.show();
    });
  });
  document.getElementById('modalPaseador').addEventListener('hidden.bs.modal', function () {
  document.getElementById('modalContent').innerHTML = '';
  });
   $('body').on('click', '.btn-estado', function (e) {
    const idPaseador = $(this).data('id');
    const nuevoEstado = $(this).data('estado');
    const ruta = "indexAjax.php?pid=<?php echo base64_encode('presentacion/paseador/actualizarEstadoAjax.php'); ?>&estado=" + nuevoEstado + "&idP=" + idPaseador;
    console.log(ruta);
    $("#estado" + idPaseador).load(ruta, function () {
        if (nuevoEstado == 1) {
            $("#1" + idPaseador).prop("disabled", true);
            $("#0" + idPaseador).prop("disabled", false);
        } else {
            $("#1" + idPaseador).prop("disabled", false);
            $("#0" + idPaseador).prop("disabled", true);
        }
    	});
	});
});
</script>
</body>