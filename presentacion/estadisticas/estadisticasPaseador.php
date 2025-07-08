<?php
$paseo = new Paseo();
$paseosC = $paseo->cantidadDePaseosPorPaseador();

// Generar los datos para Google Charts
$data = "['Paseador', 'Cantidad', { role: 'style' }],";
$total = 0;

foreach ($paseosC as $p) {
    $nombre = $p[1] . ' ' . $p[2];
    $cantidad = $p[3];
    $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    $data .= "['$nombre', $cantidad, '$color'],";
    $total += $cantidad;
}
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Cantidad de Paseos por Paseador</h4>
    </div>
    <div class="card-body">
        <h5>Total de paseos registrados: <?php echo $total; ?></h5>
        <div id="grafico_paseador1" style="height: 400px;" class="text-center">
            <img src="img/loading.gif" alt="Cargando...">
        </div>
    </div>
</div>

<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawGraficoPaseador1);

    function drawGraficoPaseador1() {
        var data = google.visualization.arrayToDataTable([
            <?php echo $data; ?>
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([
            0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"
            },
            2
        ]);

        var options = {
            title: "Cantidad de Paseos por Paseador",
            bar: {groupWidth: "80%"},
            legend: {position: "none"},
            backgroundColor: 'transparent'
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('grafico_paseador1'));
        chart.draw(view, options);
    }
</script>
