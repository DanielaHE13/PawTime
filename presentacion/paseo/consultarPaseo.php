<?php
if($_SESSION["rol"] != "administrador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
?>
<body>
<?php 
include ('presentacion/encabezado.php');
include ('presentacion/menuAdministrador.php');
$paseo = new Paseo();
$paseos = $paseo -> consultarTodos();
?>

<div class="container mt-1">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="input-group">
        <span class="input-group-text fw-bold" style="color: #4b0082; background-color: #E3CFF5; border-right: none;">
          <i class="fa-solid fa-magnifying-glass me-2"></i>Buscar paseo:
        </span>
        <input type="text" class="form-control" id="filtro" placeholder="Nombre, correo, etc." autocomplete="off"
               style="border-left: none;" />
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div id="resultados">
  	<h5 class="card-title" style="color: #4b0082;">Paseo</h5>
    	<table class="table class='table table-striped table-hover mt-3">
    		<tr>
    			<th scope="col">ID</th>
    			<th scope="col">Fecha</th>
    			<th scope="col">Hora inicio - Hora Fin</th>
    			<th scope="col">Precio total</th>
    			<th scope="col">Paseador</th>
    			<th scope="col">Perro</th>
    			<th scope="col">Estado</th>
    			<th scope="col">Acciones</th>
    		</tr>
    		<?php
    		foreach ($paseos as $p){
    		?>
            <tr>
               <th scope="row"><?php echo $p -> getId()?></th>
               <td><?php echo $p -> getFecha()?></td>
               <td><?php echo $p -> getHora_inicio() . "-" . $p -> getHora_fin()?></td>
               <td><?php echo $p -> getPrecio_total()?></td>
               <td><?php echo $p -> getPaseador_idPaseador()->getNombre()?></td>
               <td><?php echo $p -> getPerro_idPerro()->getNombre()?></td>
               <td><?php echo $p -> getEstado_idEstado()->getNombre()?></td>
               <td><a href="modalPaseo.php?idPaseo=<?php echo $p->getId(); ?>" class="abrir-modal" data-id="<?php echo $p->getId(); ?>" style="color: #4b0082;"><span class="fas fa-eye" title="Ver más información"></span></a>
             	   </td>
            </tr>
            <?php }?>
       </table>
  </div>
</div>
<div class="modal fade" id="modalPaseo" tabindex="-1" aria-hidden="true">
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
			var ruta = "buscarPaseoAjax.php?filtro=" + $("#filtro").val().replaceAll(" ", "%20");
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