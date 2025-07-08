<?php
$paseo = new Paseo();
$paseosMes = $paseo->cantidadDePaseosPorMes();

$data = "['Mes','Cantidad'],";
$total = 0;

if (is_array($paseosMes)) {
    foreach ($paseosMes as $mes) {
        $data .= "['" . $mes[0] . "', " . $mes[1] . "],";
        $total += $mes[1];
    }
}
?>
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Paseos por mes</h4>
	</div>
	<div class="card-body">
		<h5>Total de paseos registrados: <?php echo $total ?></h5>
		<div id="chart_paseos_por_mes" style="height: 400px;" class="text-center">
			<img src="img/loading.gif">
		</div>
	</div>
</div>

<script type="text/javascript">
	google.charts.load("current", { packages: ["corechart"] });
	google.charts.setOnLoadCallback(drawChartPaseosPorMes);

	function drawChartPaseosPorMes() {
		var data = google.visualization.arrayToDataTable([
			<?php echo $data ?>
		]);

		var options = {
			title: "Paseos por Mes",
			colors: ['#6a0dad'],
			legend: { position: "none" },
			backgroundColor: 'transparent',
			bar: { groupWidth: "75%" }
		};

		var chart = new google.visualization.ColumnChart(document.getElementById("chart_paseos_por_mes"));
		chart.draw(data, options);
	}
</script>
