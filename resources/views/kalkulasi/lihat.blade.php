@extends('layouts.light')

@section('content')
<div class="row">
  <div class="col-xs-12">
    <div class="card" id="card-kal">
      <div class="card-header">
        <h4 class="card-title">Transaksi to Timeseries</h4>
        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
            <li class="back"><a onclick="Kalkulasi()"><i class="icon-play4"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="card-body collapse in">
        <div class="card-block card-dashboard">
          <div id="div-stat">
            <div class="col-md-6">
              <div class="card"><div class="card-body">
                <div class="card-block pad-less">
                  <div class="media">
                    <div class="media-body text-xs-left"><h3 class="deep-orange" id="div-tgl">{{ $up_day }}</h3><span>Tarikan Terakhir</span></div>
                    <div class="media-right media-middle"><i class="icon-calendar4 deep-orange font-large-2 float-xs-right"></i></div>
                  </div>
                </div>
              </div></div>
            </div>
        
            <div class="col-md-2">
              <div class="card"><div class="card-body">
                <div class="card-block pad-less">
                  <div class="media">
                    <div class="media-body text-xs-left"><h3 class="teal" id="div-hari">{{ $hari }}</h3><span>Hari</span></div>
                    <div class="media-right media-middle"><i class="icon-sun-o teal font-large-2 float-xs-right"></i></div>
                  </div>
                </div>
              </div></div>
            </div>
        
            <div class="col-md-2">
              <div class="card"><div class="card-body">
                <div class="card-block pad-less">
                  <div class="media">
                    <div class="media-body text-xs-left"><h3 class="pink" id="div-ming">{{ $minggu }}</h3><span>Minggu</span></div>
                    <div class="media-right media-middle"><i class="icon-star-o pink font-large-2 float-xs-right"></i></div>
                  </div>
                </div>
              </div></div>
            </div>
        
            <div class="col-md-2">
              <div class="card"><div class="card-body">
                <div class="card-block pad-less">
                  <div class="media">
                    <div class="media-body text-xs-left"><h3 class="cyan" id="div-bulan">{{ $bulan }}</h3><span>Bulan</span></div>
                    <div class="media-right media-middle"><i class="icon-moon3 cyan font-large-2 float-xs-right"></i></div>
                  </div>
                </div>
              </div></div>
            </div>

            <div id="div-mess" class="col-xs-12"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function Kalkulasi() {
    block_card('#div-stat');

    jQuery.ajax({
      type: "POST",
      url: "/kalkulasi/import",
      data: { _token: "{{ csrf_token() }}" },
      success: function(res)
      {
        res = JSON.parse(res);
        $('#div-tgl').html(res['jumlah']['up_terakhir']);
        $('#div-hari').html(res['jumlah']['hari']);
        $('#div-ming').html(res['jumlah']['minggu']);
        $('#div-bulan').html(res['jumlah']['bulan']);

        if(res['jumlah']['up_terakhir']!=''){
          show_alert(`#div-mess`, `<strong>Sukses!</strong> Import data timeseries berhasil.`, `success`, 0);
        }else{
          show_alert(`#div-mess`, `<strong>Peringatan!</strong> Import data timeseries gagal, periksa koneksi dan refresh halaman.`, `warning`, 0);
        }
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        
      },
      complete: function()
      {
        unblock_card('#div-stat');
      }
    });
  }
</script>
@endsection