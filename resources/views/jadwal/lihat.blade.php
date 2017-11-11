@extends('layouts.light')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="card" id="card-tjadwal">
            <div class="card-header">
                <h4 class="card-title">Data Jadwal</h4>
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
                    <table id="tjadwal">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Berlaku</th>
                                <th>Jam</th>
                                <th>Oleh</th>
                                <th class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="ClearInput('', new Date(), new Date(), '', true)"><i class="icon-plus3"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=0; ?>
                            @foreach( $jadwal as $baris )
                            <tr>
                                <td>{{ $baris->kd }}</td>
                                <td>{{ $baris->berlaku }}</td>
                                <td>{{ $baris->jam }}</td>
                                <td>{{ $baris->nama }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info" onclick="ClearInput({{ $baris->kd }}, dateonly_sql_to_js('{{ $baris->berlaku }}'), timeonly_sql_to_js('{{ $baris->jam }}'), '{{ $baris->nama }}', true)"><i class="icon-pencil3"></i></button>
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

        <div class="card" id="card-jadwal">
            <div class="card-header">
                <h4 class="card-title" id="basic-layout-form">Form Jadwal</h4>
                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div id="alert-input"></div>
                    <form id="form-jadwal" class="form" method="post" autocomplete="off" onsubmit="return Simpan()">
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
                                        <label for="nama">Jam</label>
                                        <div class="input-group date" id="jam">
                                            <input type="text" class="form-control" name="jam" readonly/>
                                            <span class="input-group-addon">
                                                <span class="icon-calendar4"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-warning mr-1" onclick="ClearInput('', '', '', '', false)">
                                <i class="icon-cross2"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-check2"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        tjadwal = $('#tjadwal').DataTable({
            "columns": [
                { "data": "kd" },
                { "data": "berlaku" },
                { "data": "jam" },
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

        $('#jam').datetimepicker({
			format: 'HH:mm',
			date: new Date(),
			ignoreReadonly: true,
            icons: {
                previous: "icon-chevron-left2",
                next: "icon-chevron-right2",
                up: "icon-chevron-up2",
                down: "icon-chevron-down2"
            }
		});

        $('#tjadwal tbody').on( 'click', 'div.btn-group', function () {
            active_row = $(this).parents('tr');
        } );
    } );

    $('#card-jadwal').hide();
    var tjadwal, active_row;

    function ClearInput(kd, berlaku, jam, dibuat, show)
    {
        if(show){
            $('#card-jadwal').show('slow');
            $('#card-tjadwal').hide('slow');
        }else{
            $('#card-jadwal').hide('slow');
            $('#card-tjadwal').show('slow');
        }

        $('#kd').val(kd);
        $('#berlaku').data("DateTimePicker").date(berlaku);
        $('#jam').data("DateTimePicker").date(jam);
        $('#dibuat_oleh_txt').html(dibuat=='' ? '{{ $nama }}' :dibuat);
    }

    function Simpan()
    {
        jQuery.ajax({
            type: "POST",
			url: "/jadwal/simpan",
			data: $('#form-jadwal').serialize(),
			success: function(res)
			{
                res = res.split("|");
                res[1] = JSON.parse(res[1]);
                if(res[0]=="BARU"){
                    show_alert("#alert-input","<strong>Sukses!</strong> Data baru telah berhasil tersimpan.", "success");
				}else if(res[0]=="UBAH"){
                    show_alert("#alert-input","<strong>Sukses!</strong> Data telah berhasil dirubah.", "success");
				}
				
                ReloadTampil(res[1], res[0]);
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
            '<button type="button" class="btn btn-sm btn-info" onclick="ClearInput('+data['kd']+', dateonly_sql_to_js(\''+data['berlaku']+'\'), timeonly_sql_to_js(\''+data['jam']+'\'), \''+data['nama']+'\', true)"><i class="icon-pencil3"></i></button>'+
            '<button type="button" class="btn btn-sm btn-warning" onclick="Hapus('+data['kd']+')"><i class="icon-trash2"></i></button>'+
            '</div>';
        
        if(op=='UBAH'){ tjadwal.row(active_row).remove().draw(); }
        tjadwal.row.add(data).draw();
        active_row = null;

        setTimeout(function(){
            ClearInput('', '', '', '', false);
            $('#alert-input').html('');
        }, 1000);
    }

    function Hapus(kd){
        if(confirm("Yakin ingin menghapus jadwal "+kd+" ?")){
            jQuery.ajax({
                type: "POST",
                url: "/jadwal/hapus",
                data: { _token: "{{ csrf_token() }}", kd: kd },
                success: function(res)
                {
                    res = parseInt(res);
                    if(res>0){
                        tjadwal.row(active_row).remove().draw();
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