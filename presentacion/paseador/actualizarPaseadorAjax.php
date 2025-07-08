<?php 
require ("logica/Persona.php");
require ("logica/Paseador.php");
$paseador = new Paseador();
$paseadores = $paseador -> consultarTodos();
?>
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