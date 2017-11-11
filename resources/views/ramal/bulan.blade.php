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
                        <canvas id="line-chart" height="500"></canvas>
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
        <div class="loading text-center">
          <object type="image/svg+xml" data="{{ asset('loading/pinwheel.svg') }}">
            Loading...
          </object>
        </div>
        <table class="table table-hover table-selected mb-0">
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
      $("#cari_prod").keyup(function(event) {
          if (event.keyCode === 13) {
              Cari();
          }
      });
  });

  function Cari(){
    $('#modal-cari table').hide();
    $('#modal-cari .loading').show();
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
        
        $('#modal-cari table').show();
        $('#modal-cari .loading').hide();
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        if(confirm("Cari data gagal, coba lagi?")){ Cari(); }else{ $('#modal-cari').modal('hide'); }
      }
    });
    return false;
  }

  function PilihProduk(kd, nama, panjang){
    $('#modal-cari').modal('hide');
    $('#kd_prod').val(kd);
    $('#nm_prod_txt').html(nama);
    $('#panjang_txt').html(panjang);

    jQuery.ajax({
      type: "POST",
      url: "/ramal/bulan/ambil_penjualan",
      data: { _token: "{{ csrf_token() }}", kd_prod: kd },
      success: function(res)
      {
        data_penj = JSON.parse(res);
        data_ramal = [];
        BuatChart();
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        if(confirm("Tarik data penjualan gagal, coba lagi?")){ PilihProduk(kd, nama, panjang); }
      }
    });
  }

  function Proses(){
    return false;
  }

  var data_penj = [];
  var data_ramal = [];

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

    var data_labels = [];
    var data_penju = [];

    var st = [];
    var dt = [];
    var ftm = [];
    var alpha = parseFloat($('#alpha').val());
    var gamma = parseFloat($('#gamma').val());

    for(var i=0; i<data_penj.length; i++){
      data_labels[i] = month_by_int(data_penj[i]['bulan'])+" "+(data_penj[i]['tahun']).toString().substring(2);
      if((i+1) == data_penj.length){
        data_labels[i+1] = month_by_int(data_penj[i]['bulan']<12 ? data_penj[i]['bulan']+1 : 1)+" "+(data_penj[i]['bulan']<12 ? data_penj[i]['tahun'] : data_penj[i]['tahun']+1).toString().substring(2);
      }

      data_penju[i] = parseFloat(data_penj[i]['jumlah']);
      
      if(i==0){
        st[i] = null;
        dt[i] = null;
        ftm[i] = null;
      }else if(i<=1){
        st[i] = data_penju[0];
        dt[i] = data_penju[1]-data_penju[0];
        ftm[i] = data_penju[0];
      }else{
        st[i] = (alpha * data_penju[i])+((1-alpha) * (st[i-1]+dt[i-1]));
        dt[i] = (gamma * (st[i]-st[i-1]))+((1-gamma) * dt[i-1]);
        ftm[i] = st[i-1] + dt[i-1];
        if((i+1) == data_penj.length){
          ftm[i+1] = st[i] + dt[i];
        }
      }
    }

    data_ramal = ftm;

    var data_set = [{
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

    // Chart Data
    var chartData = {
      labels: data_labels,
      datasets: data_set
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