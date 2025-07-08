<?php
$paseo = new Paseo();
$paseosMes = $paseo->cantidadPaseosUltimos6Meses($_SESSION['id']);
$totalPaseos = $paseo->totalPaseos($_SESSION['id']);
$totalGanado = $paseo->totalGanado($_SESSION['id']);
?>

<div class="container my-5">
  <!-- Indicadores -->
  <div class="row text-center mb-4">
    <div class="col-md-4">
      <div class="bg-white shadow rounded p-4">
        <h5 class="text-muted">Total de Paseos</h5>
        <h2 class="fw-bold text-purple"><?php echo $totalPaseos; ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="bg-white shadow rounded p-4">
        <h5 class="text-muted">Total Ganado</h5>
        <h2 class="fw-bold text-success">$<?php echo number_format($totalGanado, 0, ',', '.'); ?></h2>
      </div>
    </div>
  </div>

  <!-- Paseos por Mes (Gráfico de Línea) -->
  <div class="row">
    <div class="col">
      <div class="bg-white shadow rounded p-4">
        <h4 class="text-center mb-3 text-purple">Paseos por Mes (Últimos 6 meses)</h4>
        <div id="chart_paseos_mes" style="height: 400px;"></div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
google.charts.load("current", {packages:['corechart', 'gauge']});
google.charts.setOnLoadCallback(drawLineChartPaseosPorMes);


function drawLineChartPaseosPorMes() {
  var data = google.visualization.arrayToDataTable([
    ['Mes', 'Paseos'],
    <?php foreach ($paseosMes as $mes): ?>
      ['<?php echo $mes[0]; ?>', <?php echo $mes[1]; ?>],
    <?php endforeach; ?>
  ]);

  var options = {
    title: 'Paseos por Mes',
    curveType: 'function',
    legend: { position: 'bottom' },
    colors: ['#6a0dad'],
    backgroundColor: 'transparent'
  };

  var chart = new google.visualization.LineChart(document.getElementById('chart_paseos_mes'));
  chart.draw(data, options);
}
</script>
