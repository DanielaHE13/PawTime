<?php
if($_SESSION["rol"] != "administrador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
if(isset($_POST["idP"])){
    $idPropietario = $_POST["idP"];
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
if(isset($_POST["direccion"])){
    $direccion = $_POST["direccion"];
}
if (isset($_POST["foto"])) {
    $foto = $_POST["foto"];
}
if(isset($_POST["crear"])){
    $propietario = new Propietario($idPropietario, $nombre, $apellido, $telefono, $correo, "", $direccion, $foto);
    $propietario->editarPerfil();
}
?>
<body>
<?php 
include ('presentacion/encabezado.php');
include ('presentacion/menuAdministrador.php');
$propietario = new Propietario();
$propietarios = $propietario -> consultarTodos();
?>

<div class="container mt-1">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="input-group">
        <span class="input-group-text fw-bold" style="color: #4b0082; background-color: #E3CFF5; border-right: none;">
          <i class="fa-solid fa-magnifying-glass me-2"></i>Buscar propietario:
        </span>
        <input type="text" class="form-control" id="filtro" placeholder="Nombre, correo, etc." autocomplete="off"
               style="border-left: none;" />
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div id="resultados">
  	<h5 class="card-title" style="color: #4b0082;">Propietarios</h5>
    	<table class="table class='table table-striped table-hover mt-3">
    		<tr>
    			<th scope="col">ID</th>
    			<th scope="col">Nombre</th>
    			<th scope="col">Apellido</th>
    			<th scope="col">Correo</th>
    			<th scope="col">Acciones</th>
    		</tr>
    		<?php
    		foreach ($propietarios as $p){
    		?>
            <tr>
               <th scope="row"><?php echo $p -> getId()?></th>
               <td><?php echo $p -> getNombre()?></td>
               <td><?php echo $p -> getApellido()?></td>
               <td><?php echo $p -> getCorreo()?></td>
               <td><a href="modalPropietario.php?idPropietario=<?php echo $p->getId(); ?>" class="abrir-modal" data-id="<?php echo $p->getId(); ?>" style="color: #4b0082;"><span class="fas fa-eye" title="Ver más información"></span></a>
             	   <a href="modalEditarPropietario.php?idPropietario=<?php echo $p->getId(); ?>" class="abrir-modal" data-id="<?php echo $p->getId(); ?>" style="color: #4b0082;"><span class="fa-solid fa-pen-to-square" title="Editar información"></span></a>
             	   </td>
            </tr>
            <?php }?>
       </table>
  </div>
</div>
<div class="modal fade" id="modalPropietario" tabindex="-1" aria-hidden="true">
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
			var ruta = "buscarPropietarioAjax.php?filtro=" + $("#filtro").val().replaceAll(" ", "%20");
			console.log(ruta);
			$("#resultados").load(ruta);
		}else{
			$("#resultados").html(tablaOriginal);
		}
	});
 $('body').on('click', '.abrir-modal', function(e) {
    e.preventDefault();    
    const url = $(this).attr('href');
    $("#modalContent").load(url, function() {
      const modal = new bootstrap.Modal(document.getElementById('modalPropietario'));
      modal.show();
    });
  });
  document.getElementById('modalPropietario').addEventListener('hidden.bs.modal', function () {
  document.getElementById('modalContent').innerHTML = '';
  });
});
</script>
</body>