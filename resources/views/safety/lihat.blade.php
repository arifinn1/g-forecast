@extends('layouts.light')

@section('content')
<div class="row">
  <div class="col-xs-12">
    <div class="card" id="card-tsafety">
      <div class="card-header">
        <h4 class="card-title">Data Parameter Safety Stock</h4>
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
          <table id="tsafety">
            <thead>
              <tr>
                <th>#</th>
                <th>Berlaku</th>
                <th>Lead Time (hari)</th>
                <th>Oleh</th>
                <th class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="ClearInput('', new Date(), '', '', '', true)"><i class="icon-plus3"></i></button></th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; ?>
              @foreach( $safety as $baris )
              <tr>
                <td>{{ $baris->kd }}</td>
                <td>{{ $baris->berlaku }}</td>
                <td>{{ $baris->lead_time }}</td>
                <td>{{ $baris->nama }}</td>
                <td>
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-info" onclick="ClearInput({{ $baris->kd }}, dateonly_sql_to_js('{{ $baris->berlaku }}'), {{ $baris->lead_time }}, '{{ $baris->serv_level }}', '{{ $baris->nama }}', true)"><i class="icon-pencil3"></i></button>
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

    <div class="card" id="card-safety">
      <div class="card-header">
        <h4 class="card-title" id="basic-layout-form">Form Safety</h4>
        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
      </div>
      <div class="card-body collapse in">
        <div class="card-block">
          <div id="alert-input"></div>
          <form id="form-safety" class="form" method="post" autocomplete="off" onsubmit="return Simpan()">
            {{ csrf_field() }}
            <div class="form-body overflow-none">
              <div class="row">
                <div class="col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="nama">Dibuat Oleh</label>
                    <input type="hidden" id="kd" name="kd">
                    <div class="text-info" id="dibuat_oleh_txt">{{ $nama }}</div>
                  </div>
                </div>

                <div class="col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="nama">Berlaku</label>
                    <div class="input-group date" id="berlaku">
                      <input type="text" class="form-control" name="berlaku" readonly/>
                      <span class="input-group-addon">
                        <span class="icon-calendar4"></span>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="nama">Lead Time</label>
                    <input type="number" id="lead_time" class="form-control" placeholder="Lead Time" name="lead_time" required>
                  </div>
                </div>
              </div>

              <div class="form-actions center">
                <button type="button" class="btn btn-warning mr-1" onclick="ClearInput('', '', '', '', '', false)">
                  <i class="icon-cross2"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                  <i class="icon-check2"></i> Simpan
                </button>
              </div>
              
              <div class="row">
                @foreach( $produk as $baris )
                <div class="col-sm-4 col-md-3">
                  <div class="form-group">
                    <label class="no-wrap"><small>{{ $baris->nm_db }}</small></label>
                    <div class="position-relative has-icon-right">
                      <input type="number" id="sl{{ $baris->kd_prod }}" class="form-control input-sm" placeholder="Service Level" value="{{ $baris->serv_level }}">
                      <div class="form-control-position">
                        <i class="icon-percent"></i>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var produk = [];
  produk = JSON.parse(`<?php echo json_encode($produk); ?>`);

  $(document).ready(function() {
    tsafety = $('#tsafety').DataTable({
      "columns": [
        { "data": "kd" },
        { "data": "berlaku" },
        { "data": "lead_time" },
        { "data": "nama" },
        { "data": "tombol", "className": "text-center", "orderable": false }
      ]
    });

    $('#berlaku').datetimepicker({
      viewMode: 'days',
      format: 'DD MMM YYYY',
      date: new Date(),
      ignoreReadonly: true,
      icons: {
        previous: "icon-chevron-left2",
        next: "icon-chevron-right2"
      }
    });

    $('#tsafety tbody').on( 'click', 'div.btn-group', function () {
      active_row = $(this).parents('tr');
    });
  });

  $('#card-safety').hide();
  var tsafety, active_row;

  function ClearInput(kd, berlaku, lead_time, serv_level, dibuat, show)
  {
    if(show){
      $('#card-safety').show('slow');
      $('#card-tsafety').hide('slow');
    }else{
      $('#card-safety').hide('slow');
      $('#card-tsafety').show('slow');
    }

    $('#kd').val(kd);
    $('#berlaku').data("DateTimePicker").date(berlaku);
    $('#lead_time').val(lead_time);
    $('#dibuat_oleh_txt').html(dibuat=='' ? '{{ $nama }}' :dibuat);

    set_serv_level(serv_level);
  }

  function set_serv_level(serv_level){
    if(serv_level!=''){
      serv_level = JSON.parse(serv_level);
      for(var i=0; i<produk.length; i++){
        $('#sl'+produk[i]['kd_prod']).val(90);
        produk[i]['serv_level'] = 90;
        for(var j=0; j<serv_level.length; j++){
          if(produk[i]['kd_prod']==serv_level[j][0]){
            $('#sl'+produk[i]['kd_prod']).val(serv_level[j][1]);
            produk[i]['serv_level'] = serv_level[j][1];
          }
        }
      }
    }else{
      for(var i=0; i<produk.length; i++){
        $('#sl'+produk[i]['kd_prod']).val(90);
        produk[i]['serv_level'] = 90;
      }
    }
  }

  function Simpan(){
    var sl = [];
    for(var i=0; i<produk.length; i++){
      sl.push([produk[i]['kd_prod'], parseInt($('#sl'+produk[i]['kd_prod']).val())]);
    }

    jQuery.ajax({
      type: "POST",
      url: "/safety/simpan",
      data: $('#form-safety').serialize()+"&sl="+JSON.stringify(sl),
      success: function(res)
      {
        res = res.split("|");
        res[1] = JSON.parse(res[1]);
        if(res[0]=="BARU"){
          show_alert("#alert-input","<strong>Sukses!</strong> Data baru telah berhasil tersimpan.", "success");
        }else if(res[0]=="UBAH"){
          show_alert("#alert-input","<strong>Sukses!</strong> Data telah berhasil dirubah.", "success");
        }
        
        ReloadTampil(res[1], res[0], res[2]);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        if(confirm("Simpan data gagal, coba lagi?")){ Simpan(); }
      }
    });
    return false;
  }

  function ReloadTampil(data, op, serv_level){
    data['tombol'] = '<div class="btn-group">'+
      '<button type="button" class="btn btn-sm btn-info" onclick="ClearInput('+data['kd']+', dateonly_sql_to_js(\''+data['berlaku']+'\'), '+data['lead_time']+', \''+serv_level+'\', \''+data['nama']+'\', true)"><i class="icon-pencil3"></i></button>'+
      '<button type="button" class="btn btn-sm btn-warning" onclick="Hapus('+data['kd']+')"><i class="icon-trash2"></i></button>'+
      '</div>';
    
    if(op=='UBAH'){ tsafety.row(active_row).remove().draw(); }
    tsafety.row.add(data).draw();
    active_row = null;

    setTimeout(function(){
      ClearInput('', '', '', '', '', false);
      $('#alert-input').html('');
    }, 1000);
  }

  function Hapus(kd){
    if(confirm("Yakin ingin menghapus parameter safety "+kd+" ?")){
      jQuery.ajax({
        type: "POST",
        url: "/safety/hapus",
        data: { _token: "{{ csrf_token() }}", kd: kd },
        success: function(res)
        {
          res = parseInt(res);
          if(res>0){
            tsafety.row(active_row).remove().draw();
            active_row = null;
            show_alert("#alert-view","<strong>Sukses!</strong> Data berhasil dihapus.", "success");
          }else{ show_alert("#alert-view","<strong>Gagal!</strong> Data gagal dihapus.", "warning"); }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          if(confirm("Simpan data gagal, coba lagi?")){ Hapus(); }
        }
      });
    }
  }
</script>
@endsection