@extends('layouts.light')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="card" id="card-akun">
            <div class="card-header">
                <h4 class="card-title" id="basic-layout-form">Ganti Password</h4>
                <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <div id="alert-input"></div>
                    <form id="form-ubah_pass" class="form" method="post" autocomplete="off" onsubmit="return Simpan()">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="pass_lama">Password Lama</label>
                                        <input type="password" id="pass_lama" class="form-control" placeholder="Password Lama" name="pass_lama" value="{{ old('pass_lama') ? old('pass_lama'): '' }}" minlength="6" maxlength="100" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="pass_baru1">Password Baru</label>
                                        <input type="password" id="pass_baru1" class="form-control" placeholder="Password Baru" name="pass_baru1" value="{{ old('pass_baru1') ? old('pass_baru1'): '' }}" minlength="6" maxlength="100" required>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="pass_baru2">Password</label>
                                        <input type="password" id="pass_baru2" class="form-control" placeholder="Ulangi Password Baru" name="pass_baru2" value="{{ old('pass_baru2') ? old('pass_baru2'): '' }}" minlength="6" maxlength="100" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
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
    function Simpan(){
        return false;
    }
</script>
@endsection