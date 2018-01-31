@extends('layouts.light')

@section('content')
<div class="row">
  <div class="col-xs-12">
    <div class="card" id="card-tpersetujuan">
      <div class="card-header">
        <h4 class="card-title">Persetujuan Firm Order</h4>
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
          <div id="alert-view">
            @if (session('pesan'))
              <div class="alert alert-success alert-dismissable" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{{ session('pesan') }}</div>
            @endif
          </div>
          <table id="tpersetujuan">
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
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; ?>
              @foreach( $renbeli as $baris )
              <?php $per_t = $baris->periode=='mingguan' ? 'ming' : 'bulan'; ?>
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
                    <button type="button" class="btn btn-sm btn-info" onclick="ClearInput({{ $baris->kd }}, '{{ $baris->ref_ramal }}', '{{ $baris->nama_peng }}', '{{ $baris->nama_acc }}', '{{ $baris->periode }}', '{{ $baris->tgl_periode }}', '{{ $baris->status }}', '{{ $baris->ket }}', true)"><i class="icon-pencil3"></i></button>
                    <?php if($baris->status=='menunggu'){ ?> <button type="button" class="btn btn-sm btn-warning" onclick="Hapus({{ $baris->kd }}, '{{ $baris->tgl_periode }}')"><i class="icon-trash2"></i></button><?php } ?>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card" id="card-persetujuan">
      <div class="card-header">
        <h4 class="card-title" id="basic-layout-form">Form Persetujuan</h4>
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
          <div id="alert-input"></div>
          <form id="form-persetujuan" class="form" method="post" autocomplete="off" action="/persetujuan/setujui">
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
              <button type="button" class="btn btn-warning mr-1" onclick="ClearInput('', '', '{{ $nama }}', '', 'bulanan', new Date(), 'menunggu', '', false)">
                <i class="icon-cross2"></i> Batal
              </button>
              <button id="btn-simpan" type="submit" class="btn btn-primary">
                <i class="icon-check2"></i> Setujui
              </button>
              <button id="btn-tolak" type="button" class="btn btn-primary" onclick="Tolak()">
                <i class="icon-ban"></i> Tolak
              </button>
            </div>
          </form>
        </div>

        <div class="card-block card-dashboard">
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
                  <td class="safety">{{ $baris->safety }}</td>
                  <td class="peramalan">{{ $baris->ramal[0] }}</td>
                  <td><span id="ren{{ $baris->kd_prod }}_txt"></td>
                  <td><input type="number" id="set{{ $baris->kd_prod }}" value=""></span></td>
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
  var produk = [], ref_ramal = [], produk_exist = [];
  produk = JSON.parse(`<?php echo json_encode($produk); ?>`);

  $(document).ready(function() {
    tpersetujuan = $('#tpersetujuan').DataTable({
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

    tproduk = $('#tproduk').DataTable({
      "scrollY": "500px",
      "scrollCollapse": true,
      "paging": false
    });

    $('#tpersetujuan tbody').on( 'click', 'div.btn-group', function () {
      active_row = $(this).parents('tr');
    } );

    $('#card-persetujuan').hide();
    ReloadRamal('bulanan');
  });

  $('#form-persetujuan').submit(function() {
    $('#periode').attr('disabled', false);
    if($('#periode').val()!=''){
      if($('#status_txt').html()=='Menunggu'){
        $('#r_data').val(JSON.stringify(produk));
        return true;
      }else{
        show_alert("#alert-input","<strong>Peringatan!</strong> Ubah data tidak diperbolehkan karna Firm Order telah disetujui atau ditolak.", "warning");
        return false;
      }
    }else{
      show_alert("#alert-input","<strong>Peringatan!</strong> Pilih periode ramalan dan isi jumlah rencana pembelian.", "warning");
      return false;
    }
  });

  function Tolak(){
    var periode = $('#tgl_periode_txt').html();
    if(confirm("Yakin ingin menghapus firm order untuk periode "+periode+" ?")){
      jQuery.ajax({
        type: "POST",
        url: "/persetujuan/tolak",
        data: { _token: "{{ csrf_token() }}", kd: $('#kd').val() },
        success: function(res)
        {
          res = parseInt(res);
          if(res>0){
            ClearInput('', '', '{{ $nama }}', '', 'bulanan', new Date(), 'menunggu', '', false);
            show_alert("#alert-view","<strong>Sukses!</strong> Status persetujuan berhasil disimpan.", "success");
            setTimeout(function(){ window.location.reload(); }, 3000);
          }else{ show_alert("#alert-input","<strong>Gagal!</strong> Status persetujuan gagal disimpan.", "warning"); }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          if(confirm("Perubahan status data gagal, coba lagi?")){ Hapus(kd, periode); }
        }
      });
    }
  }

  $("input[id^='set']").change(function() {
    var kd_prod = ($(this).attr('id')).substr(3);
    for(var i=0; i<produk.length; i++){
      if(produk[i]['kd_prod']==kd_prod){
        produk[i]['r_diset'] = parseInt($(this).val());
      }
    }
  });

  $('#periode').change(function() {
    if($('#kd').val()==''){
      if($(this).val()!=''){
        $('#ref_ramal').val(produk[0]['dibuat']);
        $('#ref_ramal_txt').html(produk[0]['dibuat']);
        $('#tgl_periode').val(produk[0]['r_awal']);
      }else{
        $('#ref_ramal').val('');
        $('#ref_ramal_txt').html('');
        $('#tgl_periode').val('');
      }
      TampilPeriode($(this).val());
      ReloadRamal($(this).val());
    }
  });

  var tpersetujuan, tproduk, active_row;

  function AmbilStok(periode, ref_ramal, kd){
    block_card('#btn-simpan');
    jQuery.ajax({
      type: "POST",
      url: "/persetujuan/ambil",
      data: { _token: "{{ csrf_token() }}", periode: periode ,ref_ramal: ref_ramal, kd: kd },
      success: function(res)
      {
        res = JSON.parse(res);
        produk = res;
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        alert(textStatus);
      },
      complete: function()
      {
        if(kd==''){
          var r_awal = produk[0]['r_awal'];
          $('#tgl_periode').val(r_awal);
        }
        TampilPeriode(periode);
        ReloadRamal(periode);
        
        unblock_card('#btn-simpan');
      }
    });
  }

  function ClearInput(kd, ref_r, dibuat_oleh, disetujui_oleh, periode, tgl_periode, status, ket, show){
    if(show){
      $('#card-persetujuan').show('slow');
      $('#card-tpersetujuan').hide('slow');
    }else{
      $('#card-persetujuan').hide('slow');
      $('#card-tpersetujuan').show('slow');
    }

    $('#kd').val(kd);
    $('#dibuat_oleh_txt').html(dibuat_oleh);
    $('#ref_ramal').val(ref_r==''? produk[0]['dibuat']: ref_r);
    $('#ref_ramal_txt').html(ref_r==''? produk[0]['dibuat']: ref_r);
    $('#disetujui_oleh_txt').html(disetujui_oleh);
    $('#periode').attr('disabled', false);
    $('#periode').val(periode);

    if(kd==''){
      AmbilStok(periode, '', '');
    }else{
      $('#tgl_periode').val(tgl_periode);
      AmbilStok(periode, ref_r, kd);
    }

    $('#status_txt').html(ucwords(status));
    $('#btn-simpan').attr('disabled', status!='menunggu');
    $('#btn-tolak').attr('disabled', status!='menunggu');
    $("#tproduk input[id^='set']").attr('disabled', status!='menunggu');
    $('#ket').html(ket);
    $('#periode').attr('disabled', kd!='');
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
        $('#tgl_periode_txt').html('Minggu ke-'+produk[0]['r_ming']+", "+getRangeWeek(produk[0]['r_ming'], r_awal.getFullYear()));
      }
    }else{
      $('#tgl_periode_txt').html('');
    }
  }

  function ReloadRamal(periode){
    var _kd = $('#kd').val(), _row, _tr;
    var editable = $('#status_txt').html()!='Ditolak';
    for(var i=0; i<produk.length; i++){
      if(produk[i]['ramal']){
        $('#tr'+produk[i]['kd_prod']+' .peramalan').html(parseFloat(produk[i]['ramal'][0]).toFixed(2));
        $('#tr'+produk[i]['kd_prod']+' .safety').html(produk[i]['safety']);
      }else{
        $('#tr'+produk[i]['kd_prod']+' .peramalan').html('-');
        $('#tr'+produk[i]['kd_prod']+' .safety').html('-');
      }

      _tr = $('#tr'+produk[i]['kd_prod']);
      _row = tproduk.row(_tr);
      if(!produk[i]['r_jml']){
        produk[i]['r_jml'] = 0;
        _tr.hide();
      }else{
        produk[i]['r_jml'] = produk[i]['r_jml'];
        _tr.show();
      }
      $('#ren'+produk[i]['kd_prod']+'_txt').html(produk[i]['r_jml']);
      
      if(!produk[i]['r_diset']){ produk[i]['r_diset'] = produk[i]['r_jml'];
      }else{ produk[i]['r_diset'] = produk[i]['r_diset']; }
      $('#set'+produk[i]['kd_prod']).val(produk[i]['r_diset']);

      $('#set'+produk[i]['kd_prod']).attr('readonly', !editable);
    }
  }
</script>
@endsection