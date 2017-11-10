@extends('layouts.light')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="card" id="card-pbulan">
            <div class="card-header">
                <h4 class="card-title">Peramalan Bulanan</h4>
                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                        <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body collapse in">
                <div class="card-block card-dashboard">
                    <form id="form-ramal" class="form" method="post" autocomplete="off" onsubmit="return Proses()">
                        {{ csrf_field() }}
                        <div class="form-body overflow-none">
                            <div class="row">
                                <div class="col-sm-12 col-md-4">
                                    <div class="form-group">
                                        <label for="cari_prod">Cari Produk</label>
                                        <input type="hidden" id="kd_prod">
                                        <input type="text" id="cari_prod" class="form-control" placeholder="Ketik nama produk > klik Enter" maxlength="100" required>
                                    </div>
                                </div>
                                <div class="col-sm-8 col-md-5">
                                    <div class="form-group">
                                        <label for="nama">Nama Produk</label>
                                        <div class="text-info" id="nm_prod_txt">...</div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-3">
                                    <div class="form-group">
                                        <label for="nama">Panjang data</label>
                                        <div class="text-info" id="panjang_txt">...</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="alpha">Alpha</label>
                                        <input type="number" id="alpha" class="form-control" placeholder="Alpha" name="alpha" value="2" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="gamma">Gamma</label>
                                        <input type="number" id="gamma" class="form-control" placeholder="Gamma" name="gamma" value="4" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="error_txt">Error</label>
                                        <div class="text-info" id="error_txt">...</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-check2"></i> Proses
                            </button>
                        </div>
                    </form>
                    <div class="chartjs">
                        <canvas id="line-chart" height="500"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#cari_prod").keyup(function(event) {
            if (event.keyCode === 13) {
                Cari();
            }
        });
    });

    function Cari(){
        alert('masuk cari');
        
        return false;
    }

    function Proses(){
        return false;
    }

    function BuatChart(){
        //Get the context of the Chart canvas element we want to select
        var ctx = $("#line-chart");

        // Chart Options
        var chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
            },
            hover: {
                mode: 'label'
            },
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        color: "#f3f3f3",
                        drawTicks: false,
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        color: "#f3f3f3",
                        drawTicks: false,
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }]
            },
            title: {
                display: true,
                text: 'Ramalan Bulan'
            }
        };

        // Chart Data
        var chartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
                label: "My First dataset",
                data: [65, 59, 80, 81, 56, 55, 40],
                fill: false,
                borderDash: [5, 5],
                borderColor: "#673AB7",
                pointBorderColor: "#673AB7",
                pointBackgroundColor: "#FFF",
                pointBorderWidth: 2,
                pointHoverBorderWidth: 2,
                pointRadius: 4,
            }, {
                label: "My Second dataset",
                data: [28, 48, 40, 19, 86, 27, 90],
                fill: false,
                borderDash: [5, 5],
                borderColor: "#00BCD4",
                pointBorderColor: "#00BCD4",
                pointBackgroundColor: "#FFF",
                pointBorderWidth: 2,
                pointHoverBorderWidth: 2,
                pointRadius: 4,
            }, {
                label: "My Third dataset - No bezier",
                data: [45, 25, 16, 36, 67, 18, 76],
                lineTension: 0,
                fill: false,
                borderColor: "#FF5722",
                pointBorderColor: "#FF5722",
                pointBackgroundColor: "#FFF",
                pointBorderWidth: 2,
                pointHoverBorderWidth: 2,
                pointRadius: 4,
            }]
        };

        var config = {
            type: 'line',

            // Chart Options
            options : chartOptions,

            data : chartData
        };

        // Create the chart
        var lineChart = new Chart(ctx, config);
    }
</script>
@endsection