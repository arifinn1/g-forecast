@extends('layouts.login')

@section('content')
<div class="content-header row"></div>
<div class="content-body">
    <section class="flexbox-container">
    <div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1  box-shadow-2 p-0">
        <div class="card border-grey border-lighten-3 m-0">
            <div class="card-header no-border">
                <div class="card-title text-xs-center">
                    <div class="p-1"><img src="{{ asset('logo-ico/app-logo2.png') }}" alt="Genetic Forecast"></div>
                </div>
                <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>Login - Genetic Forecast</span></h6>
            </div>
            <div class="card-body collapse in">
                <div class="card-block">
                    <form class="form-horizontal form-simple"  method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <fieldset class="form-group position-relative has-icon-left mb-0">
                            <input type="text" class="form-control form-control-lg input-lg{{ $errors->has('nik') ? ' has-error' : '' }}" id="nik" name="nik" placeholder="NIK" value="{{ old('nik') }}" required autofocus>
                            <div class="form-control-position">
                                <i class="icon-head"></i>
                            </div>
                        </fieldset>
                        <fieldset class="form-group position-relative has-icon-left">
                            <input type="password" class="form-control form-control-lg input-lg{{ $errors->has('password') ? ' has-error' : '' }}" id="password" name="password" placeholder="Password"  value="{{ old('password') }}" required autofocus>
                            <div class="form-control-position">
                                <i class="icon-key3"></i>
                            </div>
                        </fieldset>
                        <!--<fieldset class="form-group row">
                            <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                                <fieldset>
                                    <input type="checkbox" id="remember" class="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember-me"> Remember Me</label>
                                </fieldset>
                            </div>
                            <div class="col-md-6 col-xs-12 text-xs-center text-md-right"><a href="{{ route('password.request') }}" class="card-link">Lupa Password?</a></div>
                        </fieldset>-->
                        <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icon-unlock2"></i> Login</button>
                    </form>
                </div>
            </div>
            <!--<div class="card-footer">
                <div class="">
                    <p class="float-sm-left text-xs-center m-0"><a href="{{ route('password.request') }}" class="card-link">Pulihkan Password</a></p>
                </div>
            </div>-->
        </div>
    </div>
    </section>
</div>
@endsection
