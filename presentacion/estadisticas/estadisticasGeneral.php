<?php
$rol=$_SESSION['rol'];
?>
<body style="background: linear-gradient(to bottom, #E3CFF5, #CFA8F5); min-height: 100vh; font-family: 'Mukta', sans-serif;">

<?php
include ('presentacion/encabezado.php');
include ("presentacion/menu" . ucfirst($rol) . ".php");
?>
<div class="container">
  <div class="row">
    <div class="col">
      <div class="p-4 rounded shadow-sm" style="background-color: rgba(255, 255, 255, 0.9); border-left: 6px solid #4b0082;">
        <h4 class="mb-4 fw-bold" style="color: #4b0082;">Estadísticas Generales</h4>

        <?php
        include ("presentacion/estadisticas/estadisticasPaseador.php");
        echo "<br>";
        include ("presentacion/estadisticas/estadisticasPaseador1.php");
        echo "<br>";
        include ("presentacion/estadisticas/estadisticasPaseador2.php");
        ?>
      </div>
    </div>
  </div>
</div>