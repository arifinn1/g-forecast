@extends('layouts.light')

@section('content')
<div class="row">
  <div class="col-xs-12">
    <div class="card" id="card-daftar">
      <div class="card-header">
        <h4 class="card-title">Laporan Peramalan per Minggu</h4>
        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li class="back"><a onclick="tutupRamal()"><i class="icon-arrow-left4"></i></a></li>
            <li class="print"><a onclick="cetakLap()" href="#"><i class="icon-print"></i></a></li>
            <li class="pdf"><a href="#"><i class="icon-file-pdf-o"></i></a></li>
            <li class="excel"><a href="#"><i class="icon-file-excel-o"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="card-body collapse in">
        <div class="card-block card-dashboard">
          <div class="row">
            <div id="div-mess" class="col-xs-12"></div>
          </div>
          <div class="row">
            <div class="col-sm-4 col-md-3">
              <select id="tanggal" name="interested" class="form-control">
                <option value="">Pilih Tanggal</option>
                @foreach( $tanggal as $baris )
                  <option value="{{ $baris->dibuat }}" {{ $stanggal==$baris->dibuat ? 'selected':''}}>{{ $baris->dibuat }}</option>
                @endforeach
              </select>
              <form id="form-tanggal" action="/lapor/minggu" method="GET"></form>
              <input type="hidden" id="kd-ramal">
            </div>
            <?php $pesan = (count($dramal)==0 ? 'Pilih <code class="bg-grey bg-lighten-4 grey darken-1">tanggal</code> untuk menampilkan hasil peramalan.'
              : 'Klik <code class="bg-grey bg-lighten-4 grey darken-1"><i class="icon-print"></i></code> / <code class="bg-info bg-lighten-2 grey lighten-4">
                <i class="icon-print"></i></code> untuk cetak hasil peramalan. Klik <code class="bg-grey bg-lighten-4 grey darken-1"><i class="icon-file-pdf-o"></i></code>
                 / <code class="bg-grey bg-lighten-4 grey darken-1"><i class="icon-file-excel-o"></i></code> untuk export laporan. 
                Klik <code class="bg-grey bg-lighten-4 grey darken-1"><i class="icon-area-chart"></i></code> untuk lihat hasil peramalan.');

            ?>
            <div class="col-sm-8 col-md-9"><p id="info-ramal" class="card-text"><code class="success">{{ count($dramal) }} Data</code> hasil peramalan. <?php echo $pesan; ?></p></div>
          </div>
        </div>

        <hr class="no-margin">

        <div class="card-block card-dashboard">
          <div id="alert-view"></div>
          <table id="tramal">
            <thead>
              <tr>
                <th>#</th>
                <th>Produk</th>
                <th>MAPE</th>
                <?php $r_col = [];
                  if(count($dramal)>0){
                  $fdata = json_decode($dramal[0]->actual);
                  $rleng = count($fdata[1]) - count($fdata[0]);
                  
                  for($i=count($fdata[1])-$rleng-1; $i<count($fdata[1]); $i++){
                    echo '<th>'.$fdata[1][$i].'</th>';
                    array_push($r_col, array('data'=>str_replace(' ', '_', $fdata[1][$i])));
                  }
                } ?>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; ?>
              @foreach( $dramal as $baris )
              <tr>
                <td><?php echo ++$i; ?></td>
                <td>{{ $baris->nm_db }}</td>
                <td>{{ round($baris->mape, 8) }}</td>
                <?php
                  $acdata = json_decode($baris->actual);
                  $rmdata = json_decode($baris->ramalan);
                  $ftdata = json_decode($baris->fitness);
                  echo '<td>'.round($acdata[0][count($acdata[0])-1], 2).'</td>';
                  
                  for($j=count($rmdata)-$rleng; $j<count($rmdata); $j++){
                    echo '<td>'.round($rmdata[$j], 2).'</td>';
                  }

                  $acdata = str_replace('"', "'", $baris->actual);
                ?>
                <td class="text-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="lihatRamal({{ $baris->kd }}, `{{ $i }}. {{ $baris->nm_db }}`, {{ $baris->alpha }}, {{ $baris->gamma }}, {{ $baris->mape }}, {{ $acdata }}, {{ $baris->ramalan }}, {{ $baris->fitness }})"><i class="icon-area-chart"></i></button>
                    <button type="button" class="btn btn-sm btn-info" onclick="cetakLap({{ $baris->kd }})"><i class="icon-print"></i></button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div id="div-stat">
    <div class="col-xs-6 col-md-3">
      <div class="card"><div class="card-body">
        <div class="card-block pad-less">
          <div class="media">
            <div class="media-body text-xs-left"><h3 class="deep-orange" id="div-gen">0</h3><span>Generasi</span></div>
            <div class="media-right media-middle"><i class="icon-share4 deep-orange font-large-2 float-xs-right"></i></div>
          </div>
        </div>
      </div></div>
    </div>

    <div class="col-xs-6 col-md-3">
      <div class="card"><div class="card-body">
        <div class="card-block pad-less">
          <div class="media">
            <div class="media-body text-xs-left"><h3 class="teal" id="div-alp">0</h3><span>Alpha</span></div>
            <div class="media-right media-middle"><i class="icon-moon3 teal font-large-2 float-xs-right"></i></div>
          </div>
        </div>
      </div></div>
    </div>

    <div class="col-xs-6 col-md-3">
      <div class="card"><div class="card-body">
        <div class="card-block pad-less">
          <div class="media">
            <div class="media-body text-xs-left"><h3 class="pink" id="div-gam">0</h3><span>Gamma</span></div>
            <div class="media-right media-middle"><i class="icon-star6 pink font-large-2 float-xs-right"></i></div>
          </div>
        </div>
      </div></div>
    </div>

    <div class="col-xs-6 col-md-3">
      <div class="card"><div class="card-body">
        <div class="card-block pad-less">
          <div class="media">
            <div class="media-body text-xs-left"><h3 class="cyan" id="div-mape">0</h3><span>MAPE</span></div>
            <div class="media-right media-middle"><i class="icon-target2 cyan font-large-2 float-xs-right"></i></div>
          </div>
        </div>
      </div></div>
    </div>
  </div>

  <div class="col-xs-12">
    <div class="card" id="card-ramal">
      <div class="card-body collapse in">
        <div class="card-block chartjs">
          <canvas id="fore-chart" height="500"></canvas>
        </div>
        <hr>
        <div class="card-block chartjs">
          <canvas id="error-chart" height="500"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var tramal, col;
  var f_lineChart, e_lineChart, f_data_labels, e_data_labels, data_penju, data_ramal, data_err;

  $('#div-stat').hide();
  $('#card-daftar .card-header .back').hide();

  <?php if(count($dramal)==0){
    echo "$('#card-daftar .card-header .print').hide();";
    echo "$('#card-daftar .card-header .pdf').hide();";
    echo "$('#card-daftar .card-header .excel').hide();";
  } ?>

  $(document).ready(function() {
    col = [
      { "data": "urut" },
      { "data": "nama" },
      { "data": "mape" },
    ];
    col = col.concat(JSON.parse(`<?php echo json_encode($r_col); ?>`), { "data": "tombol", "className": "text-center", "orderable": false });

    tramal = $('#tramal').DataTable({
      columns: col
    });
    
    BuatChart();
    $('#card-ramal').hide();
  });

  $('#tanggal').change(function() {
    if($(this).val()!==''){
      $('#form-tanggal').attr('action', '/lapor/minggu/'+$(this).val()+'/');
      $('#form-tanggal').submit();
    }
  });

  function lihatRamal(kd, title, alp, gam, mape, actual, ramalan, fitness){
    $('#kd-ramal').val(kd);
    $('#div-gen').html(fitness[1][(fitness[1]).length - 1]);
    $('#div-alp').html(parseFloat(alp).toFixed(8));
    $('#div-gam').html(parseFloat(gam).toFixed(8));
    $('#div-mape').html(parseFloat(mape).toFixed(8));

    f_data_labels = actual[1];
    e_data_labels = fitness[1];
    data_penju = actual[0];
    data_ramal = ramalan;
    data_err = fitness[0];
    UbahChart();

    $('#card-daftar').children('.card-body').collapse('hide');
    $('#card-daftar .card-title').html(title);
    $('#card-daftar .card-header .back').fadeIn('fast');
    $('#card-daftar .card-header .pdf').hide();
    $('#card-daftar .card-header .excel').hide();
    $('#div-stat').slideDown('fast', function(){
      $('#card-ramal').slideDown('fast');
    });
  }

  function tutupRamal(){
    $('#div-gen').html(0);
    $('#div-alp').html(0);
    $('#div-gam').html(0);
    $('#div-mape').html(0);

    $('#card-daftar').children('.card-body').collapse('show');
    $('#card-daftar .card-title').html('Peramalan per Minggu');
    $('#card-daftar .card-header .back').hide();
    $('#card-daftar .card-header .pdf').fadeIn('fast');
    $('#card-daftar .card-header .excel').fadeIn('fast');
    $('#div-stat').slideUp('fast');
    $('#card-ramal').slideUp('fast');
  }

  function cetakLap(kd=-1){
    if($('#card-daftar .card-header .back').is(":visible") || kd>-1){
      $('#form-tanggal').attr('action', '/lapor/cetak_dminggu/'+(kd==-1 ? $('#kd-ramal').val(): kd)+'/');
      $('#form-tanggal').submit();
    }else{
      $('#form-tanggal').attr('action', '/lapor/cetak_minggu/{{ $stanggal }}/');
      $('#form-tanggal').submit();
    }
  }

  function UbahChart(){
    if(f_lineChart.data.labels.length > 0){
      f_lineChart.data.labels.pop();
      f_lineChart.data.datasets[0].data = [];
      f_lineChart.data.datasets[1].data = [];
      f_lineChart.update();

      e_lineChart.data.labels.pop();
      e_lineChart.data.datasets[0].data = [];
      e_lineChart.update();
    }
    
    f_lineChart.data.labels = f_data_labels;
    f_lineChart.data.datasets[0].data = data_penju;
    f_lineChart.data.datasets[1].data = data_ramal;
    f_lineChart.update();

    e_lineChart.options.title.text = "Rata-rata MAPE pada generasi ke-" + e_data_labels[data_err.length - 1] + " - " + data_err[data_err.length - 1];
    e_lineChart.data.labels = e_data_labels;
    e_lineChart.data.datasets[0].data = data_err;
    e_lineChart.update();
  }

  function BuatChart(){
    //Get the context of the Chart canvas element we want to select
    var f_ctx = $("#fore-chart");
    var e_ctx = $("#error-chart");

    // Chart Options
    var f_chartOptions = {
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
        text: 'Ramalan Minggu'
      }
    };

    // Chart Options
    var e_chartOptions = {
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
            text: 'Last MAPE - '
        }
    };

    var f_data_set = [{
        label: "Nilai Penjualan",
        data: [],
        fill: false,
        borderColor: "#fdcf09",
        pointBorderColor: "#fdcf09",
        pointBackgroundColor: "#FFF",
        pointBorderWidth: 2,
        pointHoverBorderWidth: 2,
        pointRadius: 4,
      },{
        label: "Peramalan",
        data: [],
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
        data: [],
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
      labels: [],
      datasets: f_data_set
    };
    var e_chartData = {
      labels: [],
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