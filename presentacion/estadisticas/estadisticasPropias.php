<?php
if($_SESSION["rol"] != "paseador"){
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
}
$id=$_SESSION['id'];
?>
<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">

<?php 
include ('presentacion/encabezado.php');
include ('presentacion/menuPaseador.php');
?>
<div class="container">
  <div class="row">
    <div class="col">
      <div class="p-4 rounded shadow-sm" style="background-color: rgba(255, 255, 255, 0.9); border-left: 6px solid #4b0082;">
        <h4 class="mb-4 fw-bold" style="color: #4b0082;">Mis Estadisticas</h4>

        <?php	    include ("presentacion/estadisticas/estadisticasParticular.php");
				    echo "<br>";
				    include ("presentacion/estadisticas/estadisticasParticular1.php");		    
				?>
      </div>
    </div>
  </div>
</div>
