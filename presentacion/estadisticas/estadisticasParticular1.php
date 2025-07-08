<?php
$paseo = new Paseo();
$estados = $paseo->distribucionEstadosPorPaseador($_SESSION["id"]);
$data = "['Estado','Cantidad'],";
foreach ($estados as $estado) {
    $data .= "['" . $estado[0] . "', " . $estado[1] . "],";
}
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Distribución de estados de paseos</h4>
    </div>
    <div class="card-body">
        <div id="chartEstadosPaseo" style="height: 400px;" class="text-center">
            <img src="img/loading.gif">
        </div>
    </div>
</div>

<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChartEstadosPaseo);

    function drawChartEstadosPaseo() {
        var data = google.visualization.arrayToDataTable([
            <?php echo $data ?>
        ]);

        var options = {
            title: "Distribución por Estado del Paseo",
            is3D: true,
            colors: ['#28a745', '#ffc107', '#dc3545'], // Verde, amarillo, rojo
            backgroundColor: "transparent"
        };

        var chart = new google.visualization.PieChart(document.getElementById('chartEstadosPaseo'));
        chart.draw(data, options);
    }
</script>
