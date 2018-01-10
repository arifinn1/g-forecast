@extends('layouts.light')

@section('content')
<div class="row">
  <div class="col-xs-12">

    <div class="card" id="card-daftar">
      <div class="card-header">
        <h4 class="card-title">Peramalan per Bulan</h4>
        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li class="back"><a onclick="tutupRamal()"><i class="icon-arrow-left4"></i></a></li>
            <!--<li><a data-action="expand"><i class="icon-expand2"></i></a></li>-->
          </ul>
        </div>
      </div>
      <div class="card-body collapse in">
        <div class="card-block card-dashboard">
          <div class="row">
            <div id="div-mess" class="col-xs-12"></div>
          </div>
          <div class="row">
            <div id="div-progress" class="col-xs-6">
              <progress id="progress-ramal" class="progress progress-striped with-btn" value="0" max="100"></progress>
            </div>
            <div id="div-btn-ramal" class="col-xs-6 del-left-pad">
              <div class="btn-group btn-group-justified">
                <a id="btn-progres" onclick="prosesRamal()" href="#" class="btn btn-primary btn-sm">Proses</a>
                <a id="btn-ulang" onclick="prosesRamal(true)" href="#" class="btn btn-warning btn-sm">Ulang</a>
                <a id="btn-simpan" onclick="simpanConfirm()" href="#" class="btn btn-secondary btn-sm">Simpan</a>
              </div>
            </div>
            <div class="col-xs-12">
                <p id="info-ramal" class="card-text"><code class="success"><?php echo count($produk); ?> Produk</code> berhasil ditarik. Klik tombol <code class="primary">proses</code> dan tunggu sampai proses peramalan selesai. </p>
            </div>
          </div>
        </div>
        <div id="div-tbl-prod" class="table-responsive" style="overflow: auto;">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th></th>
                <th>Nama</th>
                <th class="text-center">Data</th>
                <th class="text-center">Generasi</th>
                <th class="text-center">Alpha</th>
                <th class="text-center">Gamma</th>
                <th class="text-center">Evaluasi</th>
              </tr>
            </thead>
            <tbody><!-- <i class="text-success icon-check | text-danger icon-close2 | text-warning icon-sun-o icon-spin | text-info icon-repeat"> -->
              <?php $i=0; ?>
              @foreach( $produk as $baris )
              <tr>
                <th scope="row"><?php echo $i+1; ?></th>
                <td id="stat-{{ $baris->kd_prod }}"></td>
                <td><a onclick="lihatRamal(<?php echo $i; $i++; ?>)">{{ $baris->nm_db }}</a></td>
                <td class="text-center">{{ $baris->panjang }}</td>
                <td class="text-center" id="gen-{{ $baris->kd_prod }}">-</td>
                <td class="text-center" id="alp-{{ $baris->kd_prod }}">-</td>
                <td class="text-center" id="gam-{{ $baris->kd_prod }}">-</td>
                <td class="text-center" id="eva-{{ $baris->kd_prod }}">-</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div id="div-stat">
    <div class="col-md-3">
      <div class="card"><div class="card-body">
        <div class="card-block pad-less">
          <div class="media">
            <div class="media-body text-xs-left"><h3 class="deep-orange" id="div-gen">0</h3><span>Generasi</span></div>
            <div class="media-right media-middle"><i class="icon-share4 deep-orange font-large-2 float-xs-right"></i></div>
          </div>
        </div>
      </div></div>
    </div>

    <div class="col-md-3">
      <div class="card"><div class="card-body">
        <div class="card-block pad-less">
          <div class="media">
            <div class="media-body text-xs-left"><h3 class="teal" id="div-alp">0</h3><span>Alpha</span></div>
            <div class="media-right media-middle"><i class="icon-moon3 teal font-large-2 float-xs-right"></i></div>
          </div>
        </div>
      </div></div>
    </div>

    <div class="col-md-3">
      <div class="card"><div class="card-body">
        <div class="card-block pad-less">
          <div class="media">
            <div class="media-body text-xs-left"><h3 class="pink" id="div-gam">0</h3><span>Gamma</span></div>
            <div class="media-right media-middle"><i class="icon-star6 pink font-large-2 float-xs-right"></i></div>
          </div>
        </div>
      </div></div>
    </div>

    <div class="col-md-3">
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
  var produk = [], proses_idx = -1, ramal_list = [], error_list, hasil = [];
  var max_ajax = 5, que_ajax, mulai, progres, error, success, all_err, all_suc, step;
  var f_lineChart, e_lineChart, f_data_labels, e_data_labels, data_penju, data_ramal, data_err;
  produk = JSON.parse(`<?php echo json_encode($produk); ?>`);

  $('#btn-ulang').hide();
  $('#btn-simpan').hide();
  $('#div-stat').hide();
  $('#card-daftar .card-header .back').hide();

  $(document).ready(function() {
    BuatChart();
    $('#card-ramal').hide();
    setTableHeight();
  });

  $(window).resize(function() {
    setTableHeight();
  });

  function setTableHeight() {
    var w_height = $(window).height(),
      b_nav = $('body>nav').height(),
      p_wrap = parseFloat(($('body .content-wrapper').css('padding-top')).replace('px','')),
      ch = $('#card-daftar .card-header').outerHeight(),
      cb = $('#card-daftar .card-block').outerHeight();
      ft = $('footer').outerHeight();
    var total = w_height - b_nav - (p_wrap*2) - ch - cb - ft;

    setTimeout(function(){
      if($('#btn-progres').outerHeight()>0){
        $('#progress-ramal').css('height', $('#btn-progres').outerHeight()+'px');
      }
    }, 1000);

    if($('#div-tbl-prod').height() > total)
      $('#div-tbl-prod').css('height', total+'px');
    else
      $('#div-tbl-prod').css('height', $('#div-tbl-prod>table').height()+'px');
  }

  function simpanConfirm() {
    if(all_err>0){
      if(confirm("Yakin ingin melanjutkan penyimpanan, terdapat "+all_err+" peramalan yang belum diperbaiki?")){
        simpanRamal();
      }
    }else{
      simpanRamal();
    }
  }

  function simpanRamal() {
    block_card('#btn-simpan');
    jQuery.ajax({
      type: "POST",
      url: "/ramal/bulan/simpan_ramal",
      data: { _token: "{{ csrf_token() }}", data: JSON.stringify(hasil) },
      success: function(res)
      {
        if(res=='SUCCESS'){
          show_alert(`#div-mess`, `<strong>Sukses!</strong> `+hasil.length+` Data peramalan berhasil disimpan.`, `success`, 0);
        }else{
          show_alert(`#div-mess`, `<strong>Peringatan!</strong> Penyimpanan peramalan gagal, periksa koneksi dan refresh halaman.`, `warning`, 0);
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        
      },
      complete: function()
      {
        unblock_card('#btn-simpan');
      }
    });
  }

  function prosesRamal(ulang=false) {
    ramal_list = [];
    if(!ulang){
      hasil = [];
      for(var i=0;i<produk.length; i++){
        ramal_list.push([produk[i]['kd_prod'], i]);
        hasil.push([]);
      }
      all_err = 0, all_suc = 0;
      $('#info-ramal').html(`Data sedang diproses <code class="success">0 Sukses</code> <code>0 Gagal</code>. Mohon tunggu sebentar.`);
    }else{
      ramal_list = error_list;
      $('#btn-ulang').hide();
      $('#info-ramal').html(`Data sedang diproses <code class="success">`+all_suc+` Sukses</code> <code>`+all_err+` Gagal</code>. Mohon tunggu sebentar.`);
    }
    step = 100/ramal_list.length;
    error_list = [];
    $('#progress-ramal').attr('value', 0);
    $('#div-tbl-prod').scrollTo($('#stat-'+ramal_list[0][0]), 800);

    for(var i=0;i<ramal_list.length; i++){
      $('#stat-'+ramal_list[i][0]).html('');
      $('#gen-'+ramal_list[i][0]).html('-');
      $('#alp-'+ramal_list[i][0]).html('-');
      $('#gam-'+ramal_list[i][0]).html('-');
      $('#eva-'+ramal_list[i][0]).html('-');
    }
    mulai = performance.now();
    $('#progress-ramal').addClass('progress-animated');
    $('#div-btn-ramal').hide();
    $('#div-progress').removeClass('col-xs-6').addClass('col-xs-12');
    
    error = 0;
    success = 0;
    progres = 0;
    que_ajax = 0;
    for(var i=0; i<(max_ajax>ramal_list.length ? ramal_list.length : max_ajax); i++){
      ramalBulan(i, ulang);
    }
  }

  function ramalBulan(index, ulang=false) {
    que_ajax++;

    $('#stat-'+ramal_list[index][0]).html(`<i class="text-warning icon-sun-o icon-spin"></i>`);
    $('#stat-'+ramal_list[index][0]).fadeIn("slow");
    jQuery.ajax({
      type: "POST",
      url: "/ramal/bulan/ambil_penjualan_min",
      data: { _token: "{{ csrf_token() }}", kd_prod: ramal_list[index][0] },
      success: function(res)
      {
        var data_temp = res.split("||");
        
        if(ulang){ 
          hasil[ramal_list[index][1]] = data_temp;
          all_err--;
        }else{
          hasil[index] = data_temp;
        }
        success++;
        all_suc++;

        show_fade('#stat-'+ramal_list[index][0], `<i class="text-success icon-check"></i>`);
        show_fade('#gen-'+ramal_list[index][0], data_temp[3]);
        show_fade('#alp-'+ramal_list[index][0], parseFloat(data_temp[4]).toFixed(8));
        show_fade('#gam-'+ramal_list[index][0], parseFloat(data_temp[5]).toFixed(8));
        show_fade('#eva-'+ramal_list[index][0], parseFloat(data_temp[6]).toFixed(8));
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        if(error==0){ error_list = []; }
        error_list.push([ramal_list[index][0], index]);
        
        show_fade('#stat-'+ramal_list[index][0], `<i class="text-danger icon-close2"></i>`);
        error++;
        if(!ulang){ all_err++; }
      },
      complete: function()
      {
        progres++;
        $('#progress-ramal').attr('value', step*(progres));
        $('#div-tbl-prod').scrollTo($('#stat-'+ramal_list[index][0]), 400);

        if(progres==ramal_list.length){
          $('#progress-ramal').attr('value', 100);
          show_fade('#info-ramal', `Hasil peramalan <code class="success">`+all_suc+` Sukses</code> <code>`+all_err+` Gagal</code>, diselesaikan dalam `+msToTime(performance.now()-mulai)+`. Klik <code class="grey darken-1 bg-grey bg-lighten-3">nama produk</code> untuk melihat hasil peramalan atau klik tombol <code class="grey darken-1 border-grey bg-grey bg-lighten-4">simpan</code> untuk menyimpan hasil peramalan.`);
          $('#progress-ramal').removeClass('progress-animated');
          $('#div-progress').addClass('col-xs-6').removeClass('col-xs-12');
          ramal_list = [];

          if(error>0){
            $('#btn-ulang').html(error+' Error');
            $('#btn-ulang').show();
          }
          $('#btn-simpan').show();
          $('#div-btn-ramal').show('slow');
        }else{
          $('#info-ramal').html(`Data sedang diproses <code class="success">`+all_suc+` Sukses</code> <code>`+all_err+` Gagal</code>. Mohon tunggu sebentar.`);
          
          que_ajax--;
          var progres_temp = progres+max_ajax > ramal_list.length ? ramal_list.length : progres+max_ajax;
          for(var i=progres+que_ajax; i<progres_temp; i++){
            ramalBulan(i, ulang);
          }
        }
      }
    });
  }

  function lihatRamal(index){
    if(ramal_list.length==0){
      if(all_suc>0){
        $('#div-gen').html(hasil[index][3]);
        $('#div-alp').html(parseFloat(hasil[index][4]).toFixed(8));
        $('#div-gam').html(parseFloat(hasil[index][5]).toFixed(8));
        $('#div-mape').html(parseFloat(hasil[index][6]).toFixed(8));

        var e_temp = JSON.parse(hasil[index][2]);
        var f_temp = JSON.parse(hasil[index][0]);
        f_data_labels = f_temp[1];
        e_data_labels = e_temp[1];
        data_penju = f_temp[0];
        data_ramal = JSON.parse(hasil[index][1]);
        data_err = e_temp[0];
        UbahChart();

        $('#card-daftar').children('.card-body').collapse('hide');
        $('#card-daftar .card-title').html((index+1)+". "+produk[index]['nm_db']);
        $('#card-daftar .card-header .back').fadeIn('fast');
        $('#div-stat').slideDown('fast', function(){
          $('#card-ramal').slideDown('fast');
        });
      }else{
        show_alert(`#div-mess`, `<strong>Peringatan!</strong> Lakukan Peramalan terlebih dahulu.`, `warning`, 3000);
      }
    }else{
      show_alert(`#div-mess`, `<strong>Peringatan!</strong> Tunggu sampai proses peramalan selesai.`, `warning`, 3000);
    }
  }

  function tutupRamal(){
    $('#div-gen').html(0);
    $('#div-alp').html(0);
    $('#div-gam').html(0);
    $('#div-mape').html(0);

    $('#card-daftar').children('.card-body').collapse('show');
    $('#card-daftar .card-title').html('Peramalan per Bulan');
    $('#card-daftar .card-header .back').hide();
    $('#div-stat').slideUp('fast');
    $('#card-ramal').slideUp('fast');
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