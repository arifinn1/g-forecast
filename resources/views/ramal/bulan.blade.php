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
                                        <input type="number" step="0.001" id="alpha" class="form-control" placeholder="Alpha" name="alpha" value="0.400" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="gamma">Gamma</label>
                                        <input type="number" step="0.001" id="gamma" class="form-control" placeholder="Gamma" name="gamma" value="0.500" required>
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
                        <canvas id="fore-chart" height="500"></canvas>
                    </div>
                    <hr>
                    <div class="chartjs">
                        <canvas id="error-chart" height="500"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade text-xs-left" id="modal-cari" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Basic Modal</h4>
      </div>
      <div class="modal-body no-padding">
        <div class="loading text-center" id="cari-loading">
          <img src="{{ asset('loading/pinwheel.svg') }}">
        </div>
        <table class="table table-hover table-selected mb-0" id="cari-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Panjang</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    BuatChart();

    $("#cari_prod").keyup(function(event) {
      if (event.keyCode === 13) {
        Cari();
      }
    });
  });

  function Cari(){
    $('#cari-table').hide();
    $('#cari-loading').show();
    $('#modal-cari').modal('show');

    jQuery.ajax({
      type: "POST",
      url: "/ramal/bulan/cari_produk",
      data: { _token: "{{ csrf_token() }}", keyword: $('#cari_prod').val() },
      success: function(res)
      {
        var data = JSON.parse(res);
        var row = '', nama = '';
        for (var i = 0, len = data.length; i < len; i++) {
          nama = data[i]['nm_lain']=='' ? data[i]['nm_db'] : data[i]['nm_lain'];
          row += `<tr
            onclick="PilihProduk(${data[i]['kd_prod']},'${nama}',${data[i]['panjang']})"
            ><td>${i+1}</td><td>${nama}</td><td>${data[i]['panjang']}</td></tr>`;
        }
        $('#modal-cari tbody').html(row);
        $('#modal-cari .modal-title').html("Pencarian keyword '"+$('#cari_prod').val()+"', "+(data.length)+" data ditemukan");
        
        $('#cari-table').show();
        $('#cari-loading').hide();
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        if(confirm("Cari data gagal, coba lagi?")){ Cari(); }else{ $('#modal-cari').modal('hide'); }
      }
    });
    return false;
  }

  function PilihProduk(kd, nama, panjang){
    $('#cari-table').hide();
    $('#cari-loading').show();
    $('#modal-cari .modal-title').html("Mohon tunggu sebentar, peramalan sedang dilakukan");
    
    $('#kd_prod').val(kd);
    $('#nm_prod_txt').html(nama);
    $('#panjang_txt').html(panjang);

    jQuery.ajax({
      type: "POST",
      url: "/ramal/bulan/ambil_penjualan",
      data: { _token: "{{ csrf_token() }}", kd_prod: kd },
      success: function(res)
      {
        res = res.split("||");
        for(var i=0; i<res.length; i++){ res[i] = JSON.parse(res[i]); }
        console.log(res);

        data_ramal = res[1];
        SetDataChart(res[0], res[2]);
        UbahChart();

        $('#modal-cari').modal('hide');
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        if(confirm("Tarik data penjualan gagal, coba lagi?")){ PilihProduk(kd, nama, panjang); }
      }
    });
  }

  function SetDataChart(data, err){
    f_data_labels = [];
    data_penju = [];
    for(var i=0; i<data.length; i++){
      f_data_labels[i] = month_by_int(data[i]['bulan'])+" "+(data[i]['tahun']).toString().substring(2);
      if((i+1) == data.length){
        f_data_labels[i+1] = month_by_int(data[i]['bulan']<12 ? data[i]['bulan']+1 : 1)+" "+(data[i]['bulan']<12 ? data[i]['tahun'] : data[i]['tahun']+1).toString().substring(2);
      }

      data_penju[i] = parseFloat(data[i]['jumlah']);
    }

    e_data_labels = [];
    data_err = [];
    var len_err = err.length;
    var max_err = (err.length > 20 ? 20 : len_err);
    var incre = err.length/20, curr_gen = -1;
    e_lineChart.options.title.text = "Last MAPE - " + err[len_err - 1];
    for(var i=0; i<max_err; i++){
      if(len_err > 20){
        curr_gen = Math.floor(i*incre) + 1;
        if(i==0){
          data_err[i] = err[0];
          e_data_labels[i] = 1;
        }else{
          data_err[i] = err[curr_gen];
          e_data_labels[i] = curr_gen;
        }
        
        if(len_err==1000 && i==max_err-1){
          data_err[20] = err[999];
          e_data_labels[20] = 1000;
        }
      }else{ 
        data_err[i] = err[i];
        e_data_labels[i] = i;
      }
    }
  }

  function Proses(){
    return false;
  }

  var f_data_labels = [], e_data_labels = [];
  var data_penju = [], data_ramal = [], data_err = [];

  var f_lineChart, e_lineChart;

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
        text: 'Ramalan Bulan'
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
                    labelString: 'Error Rate per Generation'
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
        data: data_penju,
        fill: false,
        borderColor: "#fdcf09",
        pointBorderColor: "#fdcf09",
        pointBackgroundColor: "#FFF",
        pointBorderWidth: 2,
        pointHoverBorderWidth: 2,
        pointRadius: 4,
      },{
        label: "Peramalan",
        data: data_ramal,
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
      labels: f_data_labels,
      datasets: f_data_set
    };
    var e_chartData = {
      labels: e_data_labels,
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