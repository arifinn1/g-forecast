@extends('layouts.report')

@section('content')
<div class="content-header row">
  <div class="content-header-left col-xs-6 mb-1">
    <!--<h2 class="content-header-title">{{ $dramal[0]->nm_db }}</h2>-->
    <img alt="company logo" style="height: 40px;" src="{{ asset('logo-ico/app-logo2-big.png') }}" class="">
  </div>
  <div class="content-header-right breadcrumbs-right breadcrumbs-top col-xs-6">
    <div class="text-right col-xs-12">
      <?php echo date("D, j M Y"); ?>
    </div>
  </div>
</div>

<div id="invoice-template" class="card-block">
  <?php
    $fdata = json_decode($dramal[0]->actual);
    $edata = json_decode($dramal[0]->fitness);
    $rdata = json_decode($dramal[0]->ramalan);
    $rleng = count($fdata[1]) - count($fdata[0]);
  ?>
  <!-- Invoice Company Details -->
  <div id="invoice-company-details" class="row">
    <div class="col-xs-6 text-xs-left">
      <h4 class="margin-b-15">{{ $dramal[0]->nm_db }}</h4>
      <dl class="row">
        <dt class="col-xs-4">Riwayat</dt>
        <dd class="col-xs-8">{{ count($fdata[0]) }} Minggu</dd>
        <dt class="col-xs-4">Diramalkan</dt>
        <dd class="col-xs-8">{{ $rleng }} Minggu</dd>
        <dt class="col-xs-4">Generasi</dt>
        <dd class="col-xs-8">{{ $edata[1][count($edata[1])-1] }}</dd>
        <dt class="col-xs-4">Alpha, Gamma</dt>
        <dd class="col-xs-8">{{ $dramal[0]->alpha.", ".$dramal[0]->gamma }}</dd>
        <dt class="col-xs-4">MAPE, MSE</dt>
        <dd class="col-xs-8">{{ $dramal[0]->mape.", ".$dramal[0]->mse }}</dd>
      </dl>
    </div>
    <div class="col-xs-6 text-xs-right">
      <h2>MINGGUAN</h2>
      <p class="pb-3">{{ $fdata[1][count($fdata[0])] }} &nbsp; <i class="icon-arrow-right4"></i> &nbsp; {{ $fdata[1][count($fdata[1])-1] }}</p>
      <ul class="px-0 list-unstyled">
        <li class="text-bold-800">Dibuat</li>
        <li>{{ date("D, j M Y", strtotime($dramal[0]->dibuat)) }}</li>
      </ul>
    </div>
  </div>
  <!--/ Invoice Company Details -->
  <hr class="no-margin">

  <!-- Invoice Items Details -->
  <div class="row">
    <div class="card-block chartjs">
      <canvas id="fore-chart" height="400" width="1000"></canvas>
    </div>
  </div>
  <hr>

  <div class="row">
    <div class="card-block chartjs">
      <canvas id="error-chart" height="300" width="1000"></canvas>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  BuatChart();
  setTimeout(function(){ window.print(); }, 1000);
});

function BuatChart(){
  //Get the context of the Chart canvas element we want to select
  var f_ctx = $("#fore-chart"),
    e_ctx = $("#error-chart"),
    fdata = JSON.parse(`<?php echo $dramal[0]->actual; ?>`),
    edata = JSON.parse(`<?php echo $dramal[0]->fitness; ?>`),
    rdata = JSON.parse(`<?php echo $dramal[0]->ramalan; ?>`);

  // Chart Options
  var f_chartOptions = {
    responsive: false,
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
          labelString: 'Periode'
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
          labelString: 'Penjualan'
        }
      }]
    },
    title: {
      display: true,
      text: 'Ramalan Mingguan'
    }
  };

  // Chart Options
  var e_chartOptions = {
      responsive: false,
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
                  labelString: 'Presentasi Error per Generasi'
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
                  labelString: 'MAPE'
              }
          }]
      },
      title: {
          display: true,
          text: "Rata-rata MAPE pada generasi ke-" + edata[1][edata.length - 1] + " - " + edata[0][edata.length - 1]
      }
  };

  var f_data_set = [{
      label: "Nilai Penjualan",
      data: fdata[0],
      fill: false,
      borderColor: "#fdcf09",
      pointBorderColor: "#fdcf09",
      pointBackgroundColor: "#FFF",
      pointBorderWidth: 2,
      pointHoverBorderWidth: 2,
      pointRadius: 4,
    },{
      label: "Peramalan",
      data: rdata,
      fill: false,
      borderDash: [5, 5],
      borderColor: "#db2727",
      pointBorderColor: "#db2727",
      pointBackgroundColor: "#FFF",
      pointBorderWidth: 2,
      pointHoverBorderWidth: 2,
      pointRadius: 4,
    }];
  
  var e_data_set = [{
      label: "MAPE",
      data: edata[0],
      backgroundColor: "rgba(201,187,174,.3)",
      borderColor: "transparent",
      pointBorderColor: "#C9BBAE",
      pointBackgroundColor: "#FFF",
      pointBorderWidth: 2,
      pointHoverBorderWidth: 2,
      pointRadius: 4,
    }];

  // Chart Data
  var f_chartData = {
    labels: fdata[1],
    datasets: f_data_set
  };
  var e_chartData = {
    labels: edata[1],
    datasets: e_data_set
  };

  var f_config = {
    type: 'line',
    options : f_chartOptions,
    data : f_chartData
  };
  var e_config = {
    type: 'line',
    options : e_chartOptions,
    data : e_chartData
  };

  // Create the chart
  f_lineChart= new Chart(f_ctx, f_config);
  e_lineChart= new Chart(e_ctx, e_config);
}
</script>
@endsection