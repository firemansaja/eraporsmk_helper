
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Raport Helper || @yield('title')</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{asset('bootstrap/dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{repo('dist/css/AdminLTE.min.css')}}">
        <link rel="stylesheet" href="{{repo('dist/css/skins/_all-skins.min.css')}}">
        <style>
            .table {
                font-size: 10pt;
                font-family: Tahoma;
            }
            .selected {
                background-color: black;
                color: white;
            }
        </style>
        @yield("css")
    </head>
    <body class="hold-transition skin-blue fixed sidebar-mini">
        <div class="wrapper">

            <header class="main-header">
                <a href="{{ route('home')}}" class="logo">
                    <span class="logo-mini"><b>A</b>LT</span>
                    <span class="logo-lg"><b>Admin</b>LTE</span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="#">{{sesi('name')}}</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MAIN NAVIGATION</li>
                        <li class="@yield('home')">
                            <a href="{{ route('home')}}">
                                <i class="fa fa-home"></i> <span>Home</span>
                            </a>
                        </li>
                        <li class="@yield('sikap')">
                            <a href="{{ route('sikap')}}">
                                <i class="fa fa-book"></i> <span>Nilai Sikap</span>
                            </a>
                        </li>
                        <li class="@yield('ketidakhadiran')">
                            <a href="{{ route('ketidakhadiran') }}">
                                <i class="fa fa-clock-o"></i> <span>Ketidakhadiran</span>
                            </a>
                        </li>
                        <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
                        <li class="header">LABELS</li>
                        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
                    </ul>
                </section>
            </aside>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        Fixed Layout
                        <small>Blank example to the fixed layout</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#">Layout</a></li>
                        <li class="active">Fixed</li>
                    </ol>
                </section>
                <section class="content">
                    @yield("content")
                </section>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.4.13
                </div>
                <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights reserved.
            </footer>
        </div>

        <script src="{{repo('bower_components/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{asset('bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{repo('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
        <script src="{{repo('bower_components/fastclick/lib/fastclick.js')}}"></script>
        <script src="{{repo('dist/js/adminlte.min.js')}}"></script>
        <script src="{{repo('dist/js/demo.js')}}"></script>
        @yield("js")
    </body>
</html>
