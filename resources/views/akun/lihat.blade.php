@extends('layouts.light')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="card" id="card-takun">
            <div class="card-header">
                <h4 class="card-title">Data akun</h4>
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
                    <table id="takun">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="ClearInput('', '', '', true)"><i class="icon-plus3"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $akun as $baris )
                            <tr>
                                <td>{{ $baris->kd }}</td>
                                <td>{{ $baris->nik }}</td>
                                <td>{{ $baris->nama }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="ClearInput({{ $baris->kd }}, '{{ $baris->nik }}', '{{ $baris->nama }}', true)"><i class="icon-pencil3"></i></button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="Hapus({{ $baris->kd }}, '{{ $baris->nama }}')"><i class="icon-trash2"></i></button>
					                </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card" id="card-akun">
            <div class="card-header">
                <h4 class="card-title" id="basic-layout-form">Form Akun</h4>
                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div id="alert-input"></div>
                    <form id="form-akun" class="form" method="post" autocomplete="off" onsubmit="return Simpan()">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="nik">NIK</label>
                                        <input type="hidden" id="kd" name="kd">
                                        <input type="hidden" id="nik_l" name="nik_l">
                                        <input type="text" id="nik" class="form-control" placeholder="NIK" name="nik" value="{{ old('nik') ? old('nik'): '' }}" maxlength="11" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" id="nama" class="form-control" placeholder="Nama" name="nama" value="{{ old('nama') ? old('nama'): '' }}" minlength="3" maxlength="100" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" class="form-control" placeholder="Default 123456" name="password" value="{{ old('password') ? old('password'): '' }}" minlength="6" maxlength="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-warning mr-1" onclick="ClearInput('', '', '', false)">
                                <i class="icon-cross2"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-check2"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <pre>
                
            </pre>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        takun = $('#takun').DataTable({
            "columns": [
                { "data": "kd" },
                { "data": "nik" },
                { "data": "nama" },
                { "data": "tombol", "className": "text-center", "orderable": false }
            ]
        });

        $('#takun tbody').on( 'click', 'div.btn-group', function () {
            active_row = $(this).parents('tr');
        } );
    } );

    $('#card-akun').hide();
    var takun, active_row;

    function ClearInput(kd, nik, nama, show)
    {
        if(show){
            $('#card-akun').show('slow');
            $('#card-takun').hide('slow');
        }else{
            $('#card-akun').hide('slow');
            $('#card-takun').show('slow');
        }

        $('#kd').val(kd);
        $('#nik_l').val(nik);
        $('#nik').val(nik);
        $('#nama').val(nama);
        $('#password').val('');
        $('#password').prop('readonly', kd!='');
        $('#password').attr('placeholder', kd==''? 'Bawaan 123456' : 'password');
    }

    function Simpan()
    {
      jQuery.ajax({
        type: "POST",
        url: "/akun/simpan",
        data: $('#form-akun').serialize(),
        success: function(res)
        {
                  res = res.split("|");
                  res[1] = JSON.parse(res[1]);
                  
                  if(res[0]=="BARU"){
                      show_alert("#alert-input","<strong>Sukses!</strong> Data baru telah berhasil tersimpan.", "success");
          }else if(res[0]=="UBAH"){
                      show_alert("#alert-input","<strong>Sukses!</strong> Data telah berhasil dirubah.", "success");
          }else if(res[0]=="NIK"){ show_alert("#alert-input","<strong>Gagal!</strong> NIK telah terdaftar.", "warning"); }
          
                  if(res[0]=="BARU" || res[0]=="UBAH"){ 
                      ReloadTampil(res[1], res[0]);
                  }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          if(confirm("Simpan data gagal, coba lagi?")){ Simpan(); }
        }
      });
      return false;
    }

    function ReloadTampil(data, op){
        data['tombol'] = '<div class="btn-group">'+
            '<button type="button" class="btn btn-sm btn-info" onclick="ClearInput('+data['kd']+', \''+data['nik']+'\', \''+data['nama']+'\', true)"><i class="icon-pencil3"></i></button>'+
            '<button type="button" class="btn btn-sm btn-warning" onclick="Hapus('+data['kd']+', \''+data['nama']+'\')"><i class="icon-trash2"></i></button>'+
            '</div>';
        
        if(op=='UBAH'){ takun.row(active_row).remove().draw(); }
        takun.row.add(data).draw();
        active_row = null;

        setTimeout(function(){
            ClearInput('', '', '', false);
            $('#alert-input').html('');
        }, 1000);
    }

    function Hapus(kd, nama){
        if(confirm("Yakin ingin menghapus akun "+nama+" ?")){
            jQuery.ajax({
                type: "POST",
                url: "/akun/hapus",
                data: { _token: "{{ csrf_token() }}", kd: kd },
                success: function(res)
                {
                    res = parseInt(res);
                    if(res>0){
                        takun.row(active_row).remove().draw();
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