@extends('layouts.light')

@section('content')
<div class="row">
  <div class="col-xs-12">
    <div class="card" id="card-tpengajuan">
      <div class="card-header">
        <h4 class="card-title">Pengajuan Rencana Pembelian Barang</h4>
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
          <div id="alert-view"></div>
          <table id="tpengajuan">
            <thead>
              <tr>
                <th>#</th>
                <th>Periode</th>
                <th>Tgl Periode</th>
                <th>Ref Ramal</th>
                <th>Dibuat Oleh</th>
                <th>Disetujui Oleh</th>
                <th>Status</th>
                <th>Tgl Update Status</th>
                <th>Keterangan</th>
                <th class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="ClearInput('', '{{ $reframal['bulanan']['dibuat'] }}', '{{ $nama }}', '', 'bulanan', '', 'menunggu', true)"><i class="icon-plus3"></i></button></th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; ?>
              @foreach( $renbeli as $baris )
              <tr>
                <td>{{ $baris->kd }}</td>
                <td>{{ ucwords($baris->periode) }}</td>
                <td>{{ $baris->tgl_periode }}</td>
                <td>{{ $baris->ref_ramal }}</td>
                <td>{{ $baris->nama_peng }}</td>
                <td>{{ $baris->nama_acc }}</td>
                <td>{{ ucwords($baris->status) }}</td>
                <td>{{ $baris->tgl_status }}</td>
                <td>{{ $baris->ket }}</td>
                <td>
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-info" onclick="ClearInput()"><i class="icon-pencil3"></i></button>
                    <button type="button" class="btn btn-sm btn-warning" onclick="Hapus({{ $baris->kd }})"><i class="icon-trash2"></i></button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card" id="card-pengajuan">
      <div class="card-header">
        <h4 class="card-title" id="basic-layout-form">Form Pengajuan</h4>
        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
      </div>
      <div class="card-body collapse in">
        <div class="card-block">
          <div id="alert-input"></div>
          <form id="form-pengajuan" class="form" method="post" autocomplete="off" action="/pengajuan/simpan">
            {{ csrf_field() }}
            <div class="form-body overflow-none">
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="nama">Dibuat Oleh</label>
                    <input type="hidden" id="kd" name="kd" value="">
                    <input type="hidden" id="r_data" name="r_data" value="">
                    <div class="text-info" id="dibuat_oleh_txt">{{ $nama }}</div>
                  </div>
                </div>
              
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="ref_ramal">Referensi Peramalan</label>
                    <input type="hidden" id="ref_ramal" name="ref_ramal">
                    <div class="text-info" id="ref_ramal_txt"></div>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="status">Status</label>
                    <!--<select id="status" name="status" class="form-control">
                      <option value="disetujui">Disetujui</option>
                      <option value="ditolak">Ditolak</option>
                      <option value="menunggu">Menunggu</option>
                    </select>-->
                    <div class="text-info" id="status_txt"></div>
                  </div>
                </div>

                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="disetujui_oleh">Disetujui Oleh</label>
                    <div class="text-info" id="disetujui_oleh_txt"></div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="periode">Periode</label>
                    <select id="periode" name="periode" class="form-control" required>
                      <option value="">Pilih</option>
                      <option value="mingguan">Mingguan</option>
                      <option value="bulanan">Bulanan</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="tgl_periode">Tanggal Periode</label>
                    <div class="text-info" id="tgl_periode_txt"></div>
                    <input type="hidden" id="tgl_periode" name="tgl_periode">
                    <!--<div class="input-group date" id="tgl_periode">
                      <input type="text" class="form-control" name="tgl_periode" readonly/>
                      <span class="input-group-addon">
                        <span class="icon-calendar4"></span>
                      </span>
                    </div>-->
                  </div>
                </div>

                <div class="col-sm-8">
                  <div class="form-group">
                    <label for="ket">Keterangan</label>
                    <textarea id="ket" rows="6" class="form-control" name="ket" placeholder="Keterangan"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-actions center">
              <button type="button" class="btn btn-warning mr-1" onclick="ClearInput('', '{{ $reframal['bulanan']['dibuat'] }}', '{{ $nama }}', '', 'bulanan', new Date(), 'menunggu', false)">
                <i class="icon-cross2"></i> Batal
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="icon-check2"></i> Simpan
              </button>
            </div>
          </form>
        </div>

        <div class="card-block card-dashboard">
            <div id="alert-view"></div>
            <table id="tproduk">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Produk</th>
                  <th>Satuan</th>
                  <th>Persediaan</th>
                  <th>Safety Stock</th>
                  <th>Peramalan</th>
                  <th>Rencana Beli</th>
                  <th>Setujui</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=0; ?>
                @foreach( $produk as $baris )
                <tr id="tr{{ $baris->kd_prod }}">
                  <input type="hidden" id="pers{{ $baris->kd_prod }}" value="{{ $baris->jumlah }}">
                  <input type="hidden" id="ss{{ $baris->kd_prod }}" value="">
                  <input type="hidden" id="pera{{ $baris->kd_prod }}" value="">

                  <td>{{ ++$i }}</td>
                  <td>{{ $baris->nm_db }}</td>
                  <td>{{ $baris->satuan }}</td>
                  <td class="persediaan">
                    {{ $baris->jumlah }}
                  </td>
                  <td class="safety">{{ $baris->s_bulan }}</td>
                  <td class="peramalan">{{ $baris->r_bulan[0] }}</td>
                  <td><input type="number" id="ren{{ $baris->kd_prod }}" value=""></td>
                  <td><!--<input type="number" id="set{{ $baris->kd_prod }}" value="">--><span id="set{{ $baris->kd_prod }}_txt"></span></td>
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
  var produk = [], ref_ramal = [];
  produk = JSON.parse(`<?php echo json_encode($produk); ?>`);
  ref_ramal = JSON.parse(`<?php echo json_encode($reframal); ?>`);
  console.log(produk);
  console.log(ref_ramal);

  $(document).ready(function() {
    tpengajuan = $('#tpengajuan').DataTable({
      "columns": [
        { "data": "kd" },
        { "data": "periode" },
        { "data": "tgl_periode" },
        { "data": "ref_ramal" },
        { "data": "dibuat_oleh" },
        { "data": "disetujui_oleh" },
        { "data": "status" },
        { "data": "tgl_status" },
        { "data": "ket" },
        { "data": "tombol", "className": "text-center", "orderable": false }
      ]
    });

    tproduk = $('#tproduk').DataTable();

    /*$('#tgl_periode').datetimepicker({
      viewMode: 'days',
      format: 'DD MMM YYYY',
      date: new Date(),
      ignoreReadonly: true,
      icons: {
        previous: "icon-chevron-left2",
        next: "icon-chevron-right2"
      }
    });*/

    $('#card-pengajuan').hide();
    ReloadRamal('bulanan');
  });

  $('#form-pengajuan').submit(function() {
    if($('periode').val()!=''){
      $('#r_data').val(JSON.stringify(produk));
      return true;
    }else{
      show_alert("#alert-input","<strong>Peringatan!</strong> Pilih periode ramalan dan isi jumlah rencana pembelian.", "warning");
      return false;
    }
  });

  $("input[id^='ren']").change(function() {
    var kd_prod = ($(this).attr('id')).substr(3);
    for(var i=0; i<produk.length; i++){
      if(produk[i]['kd_prod']==kd_prod){
        produk[i]['rencana'] = parseInt($(this).val());
      }
    }
  });

  $('#periode').change(function() {
    if($('#kd').val()==''){
      if($(this).val()!=''){
        $('#ref_ramal').val(ref_ramal[$(this).val()]['dibuat']);
        $('#ref_ramal_txt').html(ref_ramal[$(this).val()]['dibuat']);
        $('#tgl_periode').val(ref_ramal[$(this).val()]['r_awal']);
      }else{
        $('#ref_ramal').val('');
        $('#ref_ramal_txt').html('');
        $('#tgl_periode').val('');
      }
      TampilPeriode($(this).val());
    }
    ReloadRamal($(this).val());
  });

  var tpengajuan, tproduk, active_row;

  function ClearInput(kd, ref_r, dibuat_oleh, disetujui_oleh, periode, tgl_periode, status, show){
    if(show){
      $('#card-pengajuan').show('slow');
      $('#card-tpengajuan').hide('slow');
    }else{
      $('#card-pengajuan').hide('slow');
      $('#card-tpengajuan').show('slow');
    }

    $('#kd').val(kd);
    $('#dibuat_oleh_txt').html(dibuat_oleh);
    $('#ref_ramal').val(ref_r);
    $('#ref_ramal_txt').html(ref_r);
    $('#disetujui_oleh_txt').html(disetujui_oleh);
    $('#periode').val(periode);

    var r_awal = ref_ramal[periode]['r_awal'];
    $('#tgl_periode').val(r_awal);

    TampilPeriode(periode);
    $('#status_txt').html(ucwords(status));
    $('#ket').html(ket);
  }

  function TampilPeriode(periode){
    if(periode!=''){
      var r_awal = $('#tgl_periode').val();
      if(periode=='bulanan'){
        r_awal = dateonly_sql_to_js(r_awal);
        r_awal = month_by_int(r_awal.getMonth()+1)+" "+r_awal.getFullYear()
        $('#tgl_periode_txt').html(r_awal);
      }else{
        r_awal = dateonly_sql_to_js(r_awal);
        $('#tgl_periode_txt').html('Minggu ke-'+ref_ramal[periode]['r_ming']+", "+getRangeWeek(ref_ramal[periode]['r_ming'], r_awal.getFullYear()));
      }
    }else{
      $('#tgl_periode_txt').html('');
    }
  }

  function ReloadRamal(periode){
    for(var i=0; i<produk.length; i++){
      $('#tr'+produk[i]['kd_prod']+' #ren'+produk[i]['kd_prod']).val(0);
      produk[i]['jumlah'] = parseFloat(produk[i]['jumlah']);
      if(periode=='bulanan' && produk[i]['s_bulan'] != null){
        $('#tr'+produk[i]['kd_prod']+' .safety').html(produk[i]['s_bulan']);
        $('#tr'+produk[i]['kd_prod']+' .peramalan').html(parseFloat(produk[i]['r_bulan'][0]).toFixed(2));
        if(parseFloat(produk[i]['jumlah'])<produk[i]['r_bulan'][0]){
          $('#ren'+produk[i]['kd_prod']).val(Math.floor(produk[i]['r_bulan'][0] - produk[i]['jumlah']));
        }
      }else if(periode=='mingguan' && produk[i]['s_ming']!=null){
        $('#tr'+produk[i]['kd_prod']+' .safety').html(produk[i]['s_ming']);
        $('#tr'+produk[i]['kd_prod']+' .peramalan').html(parseFloat(produk[i]['r_ming'][0]).toFixed(2));
        if(parseFloat(produk[i]['jumlah'])<produk[i]['r_ming'][0]){
          $('#ren'+produk[i]['kd_prod']).val(Math.floor(produk[i]['r_ming'][0] - produk[i]['jumlah']));
        }
      }else{
        $('#tr'+produk[i]['kd_prod']+' .safety').html('-');
        $('#tr'+produk[i]['kd_prod']+' .peramalan').html('-');
      }
      produk[i]['rencana'] = parseInt($('#ren'+produk[i]['kd_prod']).val());
    }
  }
</script>
@endsection