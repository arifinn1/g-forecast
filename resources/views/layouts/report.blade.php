<!DOCTYPE html>
<html lang="id" data-textdirection="ltr" class="loading">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Arifin">
    <title>{{ $title }}</title>
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('logo-ico/app-icon-60.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('logo-ico/app-icon-76.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('logo-ico/app-icon-120.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('logo-ico/app-icon-152.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo-ico/favicon.ico') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo-ico/favicon-32.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/bootstrap.min.css') }}">

    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/app.css') }}">
    <!-- END ROBUST CSS-->
    
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/fonts/icomoon.css') }}">
    <!-- END VENDOR CSS-->

    <!-- BEGIN JQuary-->
    <script src="{{ asset('robust-assets/js/core/libraries/jquery.min.js') }}" type="text/javascript"></script>
    <!-- END JQuery-->

    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <!-- END Custom CSS-->
</head>
<body>

  <div class="content-wrapper">
    @yield('content')
  </div>

  <script src="{{ asset('robust-assets/vendors/js/ui/tether.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('robust-assets/js/core/libraries/bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('robust-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
</body>
</html>