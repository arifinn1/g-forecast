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
                <th class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="ClearInput('', '{{ $reframal['bulanan'] }}', '{{ $nama }}', '', 'bulanan', new Date(), 'menunggu', true)"><i class="icon-plus3"></i></button></th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; ?>
              @foreach( $renbeli as $baris )
              <tr>
                <td>{{ $baris->kd }}</td>
                <td>{{ $baris->periode }}</td>
                <td>{{ $baris->tgl_periode }}</td>
                <td>{{ $baris->ref_ramal }}</td>
                <td>{{ $baris->dibuat_oleh }}</td>
                <td>{{ $baris->disetujui_oleh }}</td>
                <td>{{ $baris->status }}</td>
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
          <form id="form-pengajuan" class="form" method="post" autocomplete="off" onsubmit="return Simpan()">
            {{ csrf_field() }}
            <div class="form-body overflow-none">
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="nama">Dibuat Oleh</label>
                    <input type="hidden" id="kd" name="kd" value="">
                    <div class="text-info" id="dibuat_oleh_txt">{{ $nama }}</div>
                  </div>
                </div>
              
                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="ref_ramal">Referensi Peramalan</label>
                    <input type="hidden" id="ref_ramal" name="ref_ramal">
                    <div class="text-info" id="ref_ramal_txt"></div>
                  </div>
                </div>

                <div class="col-sm-4">
                  <div class="form-group">
                    <label for="disetujui_oleh">Disetujui Oleh</label>
                    <div class="text-info" id="disetujui_oleh_txt"></div>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="periode">Periode</label>
                    <select id="periode" name="periode" class="form-control" required>
                      <option value="">Pilih</option>
                      <option value="harian">Harian</option>
                      <option value="mingguan">Mingguan</option>
                      <option value="bulanan">Bulanan</option>
                    </select>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="tgl_periode">Tanggal Periode</label>
                    <div class="input-group date" id="tgl_periode">
                      <input type="text" class="form-control" name="tgl_periode" readonly/>
                      <span class="input-group-addon">
                        <span class="icon-calendar4"></span>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                      <option value="disetujui">Disetujui</option>
                      <option value="ditolak">Ditolak</option>
                      <option value="menunggu">Menunggu</option>
                    </select>
                    <div class="text-info" id="status_txt"></div>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="ket">Keterangan</label>
                    <textarea id="ket" rows="5" class="form-control" name="ket" placeholder="Keterangan"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-actions center">
              <button type="button" class="btn btn-warning mr-1" onclick="ClearInput('', '{{ $reframal['bulanan'] }}', '{{ $nama }}', '', 'bulanan', new Date(), 'menunggu', false)">
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
                <tr id="tr{{ $baris->id }}">
                  <input type="hidden" id="pers{{ $baris->id }}" name="pers{{ $baris->id }}" value="{{ $baris->jumlah }}">
                  <input type="hidden" id="ss{{ $baris->id }}" name="ss{{ $baris->id }}" value="">
                  <input type="hidden" id="pera{{ $baris->id }}" name="pera{{ $baris->id }}" value="">

                  <td>{{ ++$i }}</td>
                  <td>{{ $baris->nama }}</td>
                  <td>{{ $baris->dus }}</td>
                  <td class="persediaan">
                    {{ $baris->dus > 1 ? intval($baris->jumlah - fmod($baris->jumlah, 1)).' ds'.(fmod($baris->jumlah, 1) > 0.01 ? ', '.round(fmod($baris->jumlah, 1) * $baris->dus).' b':'') : intval($baris->jumlah).' dr' }}
                  </td>
                  <td class="safety"></td>
                  <td class="peramalan"></td>
                  <td><input type="number" id="ren{{ $baris->id }}" name="ren{{ $baris->id }}" value=""></td>
                  <td><input type="number" id="set{{ $baris->id }}" name="set{{ $baris->id }}" value=""></td>
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

    $('#tgl_periode').datetimepicker({
      viewMode: 'days',
      format: 'DD MMM YYYY',
      date: new Date(),
      ignoreReadonly: true,
      icons: {
        previous: "icon-chevron-left2",
        next: "icon-chevron-right2"
      }
    });

    $('#card-pengajuan').hide();
  });

  $('#periode').change(function() {
    if($('#kd').val()==''){
      if($(this).val()!=''){
        $('#ref_ramal').val(ref_ramal[$(this).val()]);
        $('#ref_ramal_txt').html(ref_ramal[$(this).val()]);
      }else{
        $('#ref_ramal').val('');
        $('#ref_ramal_txt').html('');
      }
    }
  });

  var tpengajuan, tproduk, active_row;

  function ClearInput(kd, ref_ramal, dibuat_oleh, disetujui_oleh, periode, tgl_periode, status, show){
    if(show){
      $('#card-pengajuan').show('slow');
      $('#card-tpengajuan').hide('slow');
    }else{
      $('#card-pengajuan').hide('slow');
      $('#card-tpengajuan').show('slow');
    }

    $('#kd').val(kd);
    $('#dibuat_oleh_txt').html(dibuat_oleh);
    $('#ref_ramal').val(ref_ramal);
    $('#ref_ramal_txt').html(ref_ramal);
    $('#disetujui_oleh_txt').html(disetujui_oleh);

    $('#periode').val(periode);
    $('#tgl_periode').data("DateTimePicker").date(tgl_periode);

    $('#status').val(status);
    $('#status_txt').html(ucwords(status));
    $('#ket').html(ket);
  }
</script>
@endsection