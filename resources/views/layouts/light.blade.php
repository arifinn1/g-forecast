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
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/bootstrap.css') }}">
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/fonts/icomoon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/fonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/vendors/css/extensions/pace.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/vendors/css/ui/prism.min.css') }}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/colors.css') }}">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('robust-assets/css/core/menu/menu-types/vertical-overlay-menu.css') }}">
    <!-- END Page Level CSS-->
    <!-- BEGIN JQuary-->
    <script src="{{ asset('robust-assets/js/core/libraries/jquery.min.js') }}" type="text/javascript"></script>
    <!-- END JQuery-->

    <?php if(isset($datatables)){ ?>
    <!-- BEGIN DataTables-->
    <!--<link rel="stylesheet" type="text/css" href="{{ asset('datatables/datatables.min.css') }}"/>
    <script type="text/javascript" src="{{ asset('datatables/datatables.min.js') }}"></script>-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <!-- END DataTables-->
    <?php } ?>

    <?php if(isset($ex_pdf)){ ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"/>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"/>
    <?php } ?>

    <?php if(isset($datetimepicker)){ ?>
    <!-- BEGIN Datetimepicker-->
    <script src="{{ asset('moment/moment.min.js') }}"></script>
    <link href="{{ asset('datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- END Datetimepicker-->
    <?php } ?>

    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <!-- END Custom CSS-->
</head>
<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-menu 2-columns  fixed-navbar">

    <!-- navbar-fixed-top-->
    <nav class="header-navbar navbar navbar-with-menu navbar-fixed-top navbar-light navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
                <li class="nav-item"><a href="{{ route('home') }}" class="navbar-brand nav-link"><img alt="branding logo" src="{{ asset('logo-ico/app-logo2.png') }}" data-expand="{{ asset('logo-ico/app-logo2.png') }}" data-collapse="{{ asset('logo-ico/favicon-25.png') }}" class="brand-logo"></a></li>
                <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a></li>
            </ul>
        </div>
        <div class="navbar-container content container-fluid">
            <div id="navbar-mobile" class="collapse navbar-toggleable-sm">
                <ul class="nav navbar-nav">
                <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5">         </i></a></li>
                <li class="nav-item hidden-sm-down"><a href="#" class="nav-link nav-link-expand"><i class="ficon icon-expand2"></i></a></li>
                </ul>
                <ul class="nav navbar-nav float-xs-right">
                <li class="dropdown dropdown-language nav-item"><a id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link"><i class="flag-icon flag-icon-gb"></i><span class="selected-language">English</span></a>
                    <div aria-labelledby="dropdown-flag" class="dropdown-menu"><a href="#" class="dropdown-item"><i class="flag-icon flag-icon-gb"></i> English</a><a href="#" class="dropdown-item"><i class="flag-icon flag-icon-fr"></i> French</a><a href="#" class="dropdown-item"><i class="flag-icon flag-icon-cn"></i> Chinese</a><a href="#" class="dropdown-item"><i class="flag-icon flag-icon-de"></i> German</a></div>
                </li>
                <li class="dropdown dropdown-notification nav-item"><a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon icon-bell4"></i><span class="tag tag-pill tag-default tag-danger tag-default tag-up">5</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                    <li class="dropdown-menu-header">
                        <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span><span class="notification-tag tag tag-default tag-danger float-xs-right m-0">5 New</span></h6>
                    </li>
                    <li class="list-group scrollable-container"><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left valign-middle"><i class="icon-cart3 icon-bg-circle bg-cyan"></i></div>
                            <div class="media-body">
                            <h6 class="media-heading">You have new order!</h6>
                            <p class="notification-text font-small-3 text-muted">Lorem ipsum dolor sit amet, consectetuer elit.</p><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">30 minutes ago</time></small>
                            </div>
                        </div></a><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left valign-middle"><i class="icon-monitor3 icon-bg-circle bg-red bg-darken-1"></i></div>
                            <div class="media-body">
                            <h6 class="media-heading red darken-1">99% Server load</h6>
                            <p class="notification-text font-small-3 text-muted">Aliquam tincidunt mauris eu risus.</p><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Five hour ago</time></small>
                            </div>
                        </div></a><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left valign-middle"><i class="icon-server2 icon-bg-circle bg-yellow bg-darken-3"></i></div>
                            <div class="media-body">
                            <h6 class="media-heading yellow darken-3">Warning notifixation</h6>
                            <p class="notification-text font-small-3 text-muted">Vestibulum auctor dapibus neque.</p><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Today</time></small>
                            </div>
                        </div></a><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left valign-middle"><i class="icon-check2 icon-bg-circle bg-green bg-accent-3"></i></div>
                            <div class="media-body">
                            <h6 class="media-heading">Complete the task</h6><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Last week</time></small>
                            </div>
                        </div></a><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left valign-middle"><i class="icon-bar-graph-2 icon-bg-circle bg-teal"></i></div>
                            <div class="media-body">
                            <h6 class="media-heading">Generate monthly report</h6><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Last month</time></small>
                            </div>
                        </div></a></li>
                    <li class="dropdown-menu-footer"><a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all notifications</a></li>
                    </ul>
                </li>
                <li class="dropdown dropdown-notification nav-item"><a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon icon-mail6"></i><span class="tag tag-pill tag-default tag-info tag-default tag-up">8</span></a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                    <li class="dropdown-menu-header">
                        <h6 class="dropdown-header m-0"><span class="grey darken-2">Messages</span><span class="notification-tag tag tag-default tag-info float-xs-right m-0">4 New</span></h6>
                    </li>
                    <li class="list-group scrollable-container"><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left"><span class="avatar avatar-sm avatar-online rounded-circle"><img src="{{ asset('robust-assets/images/portrait/small/avatar-s-1.png') }}" alt="avatar"><i></i></span></div>
                            <div class="media-body">
                            <h6 class="media-heading">Margaret Govan</h6>
                            <p class="notification-text font-small-3 text-muted">I like your portfolio, let's start the project.</p><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Today</time></small>
                            </div>
                        </div></a><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left"><span class="avatar avatar-sm avatar-busy rounded-circle"><img src="{{ asset('robust-assets/images/portrait/small/avatar-s-2.png') }}" alt="avatar"><i></i></span></div>
                            <div class="media-body">
                            <h6 class="media-heading">Bret Lezama</h6>
                            <p class="notification-text font-small-3 text-muted">I have seen your work, there is</p><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Tuesday</time></small>
                            </div>
                        </div></a><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left"><span class="avatar avatar-sm avatar-online rounded-circle"><img src="{{ asset('robust-assets/images/portrait/small/avatar-s-3.png') }}" alt="avatar"><i></i></span></div>
                            <div class="media-body">
                            <h6 class="media-heading">Carie Berra</h6>
                            <p class="notification-text font-small-3 text-muted">Can we have call in this week ?</p><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">Friday</time></small>
                            </div>
                        </div></a><a href="javascript:void(0)" class="list-group-item">
                        <div class="media">
                            <div class="media-left"><span class="avatar avatar-sm avatar-away rounded-circle"><img src="{{ asset('robust-assets/images/portrait/small/avatar-s-6.png') }}" alt="avatar"><i></i></span></div>
                            <div class="media-body">
                            <h6 class="media-heading">Eric Alsobrook</h6>
                            <p class="notification-text font-small-3 text-muted">We have project party this saturday night.</p><small>
                                <time datetime="2015-06-11T18:29:20+08:00" class="media-meta text-muted">last month</time></small>
                            </div>
                        </div></a></li>
                    <li class="dropdown-menu-footer"><a href="javascript:void(0)" class="dropdown-item text-muted text-xs-center">Read all messages</a></li>
                    </ul>
                </li>
                <li class="dropdown dropdown-user nav-item">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link"><span class="avatar avatar-online"><img src="{{ asset('robust-assets/images/portrait/small/avatar-s-1.png') }}" alt="avatar"><i></i></span><span class="user-name">{{ $nama }}</span></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item"><i class="icon-head"></i> Edit Profile</a>
                        <a href="#" class="dropdown-item"><i class="icon-mail6"></i> My Inbox</a>
                        <a href="#" class="dropdown-item"><i class="icon-clipboard2"></i> Task</a>
                        <a href="#" class="dropdown-item"><i class="icon-calendar5"></i> Calender</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item"
                            onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                            <i class="icon-power3"></i> Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
                </ul>
            </div>
        </div>
    </div>
    </nav>

    <!-- main menu-->
    <div data-scroll-to-active="true" class="main-menu menu-fixed menu-light menu-accordion menu-shadow">

        <!-- main menu content-->
        <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class=" nav-item"><a href="index.html"><i class="icon-home3"></i><span class="menu-title">Dashboard</span><span class="tag tag tag-primary tag-pill float-xs-right mr-2">2</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::path() == 'home' || Request::path() == '/' ? 'active' : '' }}"><a href="{{ route('home') }}" class="menu-item">Home</a></li>
                        <li><a href="dashboard-2.html" class="menu-item">Dashboard</a></li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="icon-calculator4"></i><span class="menu-title">Kalkulasi</span></a>
                    <ul class="menu-content">
                        <li><a href="layout-boxed.html" class="menu-item">Harian</a></li>
                        <li><a href="layout-static.html" class="menu-item">Mingguan</a></li>
                        <li><a href="layout-light.html" class="menu-item">Bulanan</a></li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="icon-clock-o"></i><span class="menu-title">Jadwal dan Savety Stock</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::path() == 'jadwal' ? 'active' : '' }}"><a href="{{ route('jadwal') }}" class="menu-item">Jadwal Kalkulasi</a></li>
                        <li class="{{ Request::path() == 'savety' ? 'active' : '' }}"><a href="{{ route('savety') }}" class="menu-item">Savety Stock</a></li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="icon-sun-o"></i><span class="menu-title">Peramalan</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::path() == 'ramal/hari' ? 'active' : '' }}"><a href="{{ route('ramal_hari') }}" class="menu-item">Harian</a></li>
                        <li class="{{ Request::path() == 'ramal/minggu' ? 'active' : '' }}"><a href="{{ route('ramal_minggu') }}" class="menu-item">Mingguan</a></li>
                        <li class="{{ Request::path() == 'ramal/bulan' ? 'active' : '' }}"><a href="{{ route('ramal_bulan') }}" class="menu-item">Bulanan</a></li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="icon-file-text3"></i><span class="menu-title">Laporan</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::path() == 'lapor/hari' ? 'active' : '' }}"><a href="{{ route('lapor_hari') }}" class="menu-item">Harian</a></li>
                        <li class="{{ Request::path() == 'lapor/minggu' ? 'active' : '' }}"><a href="{{ route('lapor_minggu') }}" class="menu-item">Mingguan</a></li>
                        <li class="{{ Request::path() == 'lapor/bulan' ? 'active' : '' }}"><a href="{{ route('lapor_bulan') }}" class="menu-item">Bulanan</a></li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="#"><i class="icon-shopping-basket"></i><span class="menu-title">Rencana Pembelian</span></a>
                    <ul class="menu-content">
                        <li><a href="card-statistics.html" class="menu-item">Pengajuan</a></li>
                        <li><a href="card-charts.html" class="menu-item">Persetujuan</a></li>
                    </ul>
                </li>
                <li class="{{ Request::path() == 'akun/ganti_pass' ? 'active' : '' }}"><a href="{{ route('ganti_pass') }}"><i class="icon-key3"></i><span class="menu-title">Ubah Password</span></a></li>
                <li class="{{ Request::path() == 'akun' ? 'active' : '' }}"><a href="{{ route('lihat_akun') }}"><i class="icon-users3"></i><span class="menu-title">Pengguna</span></a></li>
            </ul>
        </div>
        <!-- /main menu content-->
    </div>
    <!-- / main menu-->

    <div class="app-content content container-fluid">
        <div class="content-wrapper">

        @yield('content')

        </div>
    </div>

    <footer class="footer footer-static footer-light navbar-border place-bottom">
        <p class="clearfix text-muted text-sm-center mb-0 px-2"><span class="float-md-left d-xs-block d-md-inline-block">Copyright  &copy; 2017 <a href="https://pelumas.geloragroup.com" target="_blank" class="text-bold-800 grey darken-2"><span class="hidden-xs-down">Gelora Putra Perkasa</span><span class="hidden-sm-up">GPP</span> </a>, All rights reserved.</span><span class="float-md-right d-xs-block d-md-inline-block hidden-sm-down"> Hand-crafted & Made with <i class="icon-heart5 pink"></i></span></p>
    </footer>

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('robust-assets/vendors/js/ui/tether.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('robust-assets/js/core/libraries/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('robust-assets/vendors/js/ui/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('robust-assets/vendors/js/ui/unison.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('robust-assets/vendors/js/ui/blockUI.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('robust-assets/vendors/js/ui/jquery.matchHeight-min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('robust-assets/vendors/js/ui/screenfull.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('robust-assets/vendors/js/extensions/pace.min.js') }}" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script type="text/javascript" src="{{ asset('robust-assets/vendors/js/ui/prism.min.js') }}"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN ROBUST JS-->
    <script src="{{ asset('robust-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
    <script src="{{ asset('robust-assets/js/core/app.js') }}" type="text/javascript"></script>
    <!-- END ROBUST JS-->

    <?php if(isset($scroll)){ ?>
    <!-- BEGIN scrollTo JS-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js"></script>
    <!-- END CHART JS-->
    <?php } ?>

    <?php if(isset($chart)){ ?>
    <!-- BEGIN CHART JS-->
    <script src="{{ asset('robust-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
    <!-- END CHART JS-->
    <?php } ?>

    <!-- BEGIN COSTUM JS-->
    <script src="{{ asset('js/script.js') }}" type="text/javascript"></script>
    <!-- END COSTUM JS-->
</body>
</html>