<?php
$paseo = new Paseo();
$paseosV = $paseo->cantidadCompletadosVsCancelados();

$data = "['Estado','Cantidad'],";
$total = 0;

if (is_array($paseosV)) {
    foreach ($paseosV as $p) {
        $data .= "['" . $p[0] . "', " . $p[1] . "],";
        $total += $p[1];
    }
}
?>

<div class="card">
	<div class="card-header">
		<h4 class="card-title">Paseos Completados vs Cancelados</h4>
	</div>
	<div class="card-body">
		<h5>Total de paseos registrados: <?php echo $total ?></h5>
		<div id="piePaseosCompletadosCancelados" style="height: 400px;" class="text-center">
			<img src="img/loading.gif">
		</div>
	</div>
</div>

<script type="text/javascript">
	google.charts.load("current", {"packages":["corechart"]});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			<?php echo $data ?>
		]);

		var options = {
			is3D: true,
			legend: { position: "right", alignment: "center" },
			colors: ['#28a745', '#dc3545'],
			backgroundColor: 'transparent'
		};

		var chart = new google.visualization.PieChart(document.getElementById("piePaseosCompletadosCancelados"));
		chart.draw(data, options);
	}
</script>
