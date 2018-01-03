@extends('layouts.light')

@section('content')
<div class="row">
  <div class="col-xs-12">

    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Peramalan per Bulan</h4>
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
          <div class="row">
            <div class="col-xs-8">
              <progress id="progress-ramal" class="progress progress-striped progress-animated with-btn" value="0" max="100"></progress>
            </div>
            <div class="col-xs-4 del-left-pad">
              <div class="btn-group btn-group-justified">
                <a onclick="prosesRamal()" href="#" class="btn btn-primary btn-sm">Proses</a>
                <a href="#" class="btn btn-secondary btn-sm">Simpan</a>
              </div>
            </div>
            <div class="col-xs-12">
                <p id="info-ramal" class="card-text"><code><?php echo count($produk); ?> Produk</code> berhasil ditarik. Klik tombol <code>proses</code> dan tunggu sampai proses peramalan selesai. </p>
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
                <th scope="row"><?php echo ++$i; ?></th>
                <td id="stat-{{ $baris->kd_prod }}"></td>
                <td>{{ $baris->nm_db }}</td>
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
</div>

<script>
  var produk = [], proses_idx = -1, hasil = [], mulai;
  produk = JSON.parse(`<?php echo json_encode($produk); ?>`);
  var step = 100/produk.length;
  console.log(produk);

  $(document).ready(function() {
    setTableHeight();
  });

  $(window).resize(function() {
    setTableHeight();
  });

  function setTableHeight() {
    var w_height = $(window).height() - 280;
    if($('#div-tbl-prod').height() > w_height)
      $('#div-tbl-prod').css('height', w_height+'px');
    else
      $('#div-tbl-prod').css('height', $('#div-tbl-prod>table').height()+'px');
  }

  function prosesRamal() {
    if(proses_idx==-1) {
      for(var i=0;i<produk.length; i++){
        $('#stat-'+produk[i]['kd_prod']).html('');
        $('#gen-'+produk[i]['kd_prod']).html('-');
        $('#alp-'+produk[i]['kd_prod']).html('-');
        $('#gam-'+produk[i]['kd_prod']).html('-');
        $('#eva-'+produk[i]['kd_prod']).html('-');
      }
      mulai = performance.now();
      hasil = [];
      proses_idx++;
    }

    if(proses_idx<produk.length){        
      $('#info-ramal').html(`<code>`+(proses_idx+1)+`/`+produk.length+` Data</code> sedang diproses. Mohon tunggu sebentar.`);

      $('#div-tbl-prod').scrollTo($('#stat-'+produk[proses_idx]['kd_prod']), 800);
      $('#stat-'+produk[proses_idx]['kd_prod']).html(`<i class="text-warning icon-sun-o icon-spin"></i>`);
      $('#stat-'+produk[proses_idx]['kd_prod']).fadeIn("slow");
      jQuery.ajax({
        type: "POST",
        url: "/ramal/bulan/ambil_penjualan_min",
        data: { _token: "{{ csrf_token() }}", kd_prod: produk[proses_idx]['kd_prod'] },
        success: function(res)
        {
          hasil.push(res.split("||"));
          
          $('#progress-ramal').attr('value', step*(proses_idx+1));
          show_fade('#stat-'+produk[proses_idx]['kd_prod'], `<i class="text-success icon-check"></i>`);
          show_fade('#gen-'+produk[proses_idx]['kd_prod'], hasil[hasil.length-1][2]);
          show_fade('#alp-'+produk[proses_idx]['kd_prod'], parseFloat(hasil[hasil.length-1][3]).toFixed(8));
          show_fade('#gam-'+produk[proses_idx]['kd_prod'], parseFloat(hasil[hasil.length-1][4]).toFixed(8));
          show_fade('#eva-'+produk[proses_idx]['kd_prod'], parseFloat(hasil[hasil.length-1][5]).toFixed(8));
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          show_fade('#stat-'+produk[proses_idx]['kd_prod'], `<i class="text-danger icon-close2"></i>`);
        },
        complete: function()
        {
          proses_idx++;
          prosesRamal();
        }
      });
    }else{
      show_fade('#info-ramal', `<code>`+proses_idx+` Produk</code> berhasil diramal dalam `+msToTime(performance.now()-mulai)+`. Klik tombol <code>simpan</code> untuk menyimpan hasil peramalan.`);
      proses_idx = -1;
      console.log(hasil);
    }
  }
</script>
@endsection