<!-- Creado: 13 1 26 -->

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Solicitudes por estado</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="chartEstado"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Solicitudes por tipo</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="chartTipo"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Solicitudes por día (Semana)</h4>
                    </div>
                    <div class="card-body">
                        <div style="height:320px;">
                            <canvas id="lineChart_2"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Últimos 30 días</h4>
                    </div>
                    <div class="card-body">
                        <div style="height:320px;">
                            <canvas id="barChart_2"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- ================== JS ================== -->
<script>
    const BASE_URL = "<?= base_url(); ?>";
    const URL_ESTADO = BASE_URL + "metricas/C_metricas/por_estado";
    const URL_TIPO = BASE_URL + "metricas/C_metricas/por_tipo";
    const URL_DIA = BASE_URL + "metricas/C_metricas/por_dia";
    const URL_LINEA = BASE_URL + "metricas/C_metricas/ultimos_30_dias";

    (function initMetricasAdmin() {
        if (!window.jQuery || !window.jQuery.fn || !window.Chart) {
            return setTimeout(initMetricasAdmin, 50);
        }
        var $ = window.jQuery;
        var charts = {};
        cargarDona(URL_ESTADO, 'chartEstado');
        cargarDona(URL_TIPO, 'chartTipo');
        cargarSemanaLineChart();
        cargarUltimos30BarChart(); 


        function palette(n) {
            var out = [];
            for (var i = 0; i < n; i++) {
                var hue = Math.round((360 / n) * i);
                out.push('hsl(' + hue + ', 70%, 55%)');
            }
            return out;
        }
        function cargarDona(url, canvasId) {
            $.getJSON(url, function(data) {
                var labels = data.map(function(x) {
                    return x.label;
                });
                var values = data.map(function(x) {
                    return Number(x.total);
                });
                var colors = palette(values.length);
                if (charts[canvasId]) charts[canvasId].destroy();
                charts[canvasId] = new Chart(document.getElementById(canvasId), {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: colors,
                            borderColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            });
        }
        function cargarSemanaLineChart() {
            $.getJSON(URL_DIA, function(data) {
                var dias = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                var mapDowToIndex = {
                    1: 0,
                    2: 1,
                    3: 2,
                    4: 3,
                    5: 4,
                    6: 5,
                    0: 6
                };
                var values = [0, 0, 0, 0, 0, 0, 0];
                data.forEach(function(row) {
                    var dow = Number(row.dia);
                    var idx = mapDowToIndex[dow];
                    values[idx] = Number(row.total);
                });
                var color = 'hsl(210, 70%, 55%)';
                if (charts['lineChart_2']) charts['lineChart_2'].destroy();
                charts['lineChart_2'] = new Chart(document.getElementById('lineChart_2'), {
                    type: 'line',
                    data: {
                        labels: dias,
                        datasets: [{
                            label: 'Solicitudes',
                            data: values,
                            borderColor: color,
                            backgroundColor: color,
                            fill: false,
                            tension: 0.35,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            });
        }
        function cargarUltimos30BarChart() {
            $.getJSON(URL_LINEA, function(data) {
                var labels = data.map(function(x) {
                    return x.fecha;
                });
                var values = data.map(function(x) {
                    return Number(x.total);
                });
                var canvas = document.getElementById('barChart_2');
                var ctx = canvas.getContext('2d');
                var gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(77, 46, 216, 0.9)');
                gradient.addColorStop(1, 'rgba(85, 58, 204, 0.2)');
                if (charts['barChart_2']) charts['barChart_2'].destroy();
                charts['barChart_2'] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Solicitudes',
                            data: values,
                            backgroundColor: gradient,
                            borderColor: 'rgba(72, 43, 202, 1)',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            });
        }
    })();
</script>