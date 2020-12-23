<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Black Dashboard') }}</title>
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('black') }}/img/apple-icon.png">
        <link rel="icon" type="image/png" href="{{ asset('black') }}/img/favicon.png">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <!-- Icons -->
        <link href="{{ asset('black') }}/css/nucleo-icons.css" rel="stylesheet" />
        <!-- CSS -->
        <link href="{{ asset('black') }}/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
        <link href="{{ asset('black') }}/select2/select2.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('black') }}/css/lib/datatable/dataTables.bootstrap.min.css">
        <link href="{{ asset('black') }}/css/theme.css" rel="stylesheet" />
        <style>
            .mblack:hover{
                background-color: transparent !important;
            }
            .tooltip { top: 0; }
            .loading-head {
                margin: 0;
                width: 100%;
                height: 100%;
                position: fixed;
                z-index: 9999;
                background-color: #e14eca;
                opacity: 0.8;
                overflow-Y: hidden; /*quitar el scroll*/
            }
            .loading-head > .loader{
                visibility: visible;
                display: block;
            }

            .loader,
            .loader:before,
            .loader:after {
            background: #ffffff;
            -webkit-animation: load1 1s infinite ease-in-out;
            animation: load1 1s infinite ease-in-out;
            width: 1em;
            height: 4em;
            }
            .loader {
                display: none;
                color: #ffffff;
                text-indent: -9999em;
                margin: 300px auto;
                font-size: 50px;
                -webkit-transform: translateZ(0);
                -ms-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-animation-delay: -0.16s;
                animation-delay: -0.16s;
                visibility: hidden;
            }
            .loader:before,
            .loader:after {
                position: fixed;
                top: 0;
                content: '';
            }
            .loader:before {
                left: -1.5em;
                -webkit-animation-delay: -0.32s;
                animation-delay: -0.32s;
            }
            .loader:after {
                left: 1.5em;
            }
            tr:hover {background-color: #e14eca;}
            @-webkit-keyframes load1 {
                0%,
                80%,
                100% {
                    box-shadow: 0 0;
                    height: 4em;
                }
                40% {
                    box-shadow: 0 -2em;
                    height: 5em;
                }
            }
            @keyframes load1 {
                0%,
                80%,
                100% {
                    box-shadow: 0 0;
                    height: 4em;
                }
                40% {
                    box-shadow: 0 -2em;
                    height: 5em;
                }
            }
            /* .page-link{
                border-radius: 30px !important;
            } */
            /* .select2-no-results{
                background-color: black !important;
            }
            .select2-drop{
                background-color: black !important;
            }
            .select2-input{
                background-color: black !important;
            }
            .select2-searching{
                background-color: black !important;
            }
            .select2-choice{
                background-image: linear-gradient(to top, #eee 0%, black 50%)
            }
            .select2-input{
                background-color: black !important;
            } */
        </style>
        @yield('css')
        {{-- <link href="{{ asset('black') }}/adminlte.min.css" rel="stylesheet" /> --}}
    </head>
    <body class="{{ $class ?? 'sidebar-mini' }}">
        <div id="loading-open">
            <div class="loader">Loading...</div>
        </div>
        @auth()
            <div class="wrapper">
                <div class="navbar-minimize-fixed" style="opacity: 1;">
                    <button class="minimize-sidebar btn btn-link btn-just-icon">
                      <i class="tim-icons icon-align-center visible-on-sidebar-regular text-muted"></i>
                      <i class="tim-icons icon-bullet-list-67 visible-on-sidebar-mini text-muted"></i>
                    </button>
                </div>
                    @include('layouts.navbars.sidebar')
                <div class="main-panel ps ps--active-y">
                    @include('layouts.navbars.navbar')

                    <div class="content">
                        @yield('content')
                    </div>

                    @include('layouts.footer')
                </div>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            @include('layouts.navbars.navbar')
            <div class="wrapper wrapper-full-page">
                <div class="full-page {{ $contentClass ?? '' }}">
                    <div class="content">
                        <div class="container">
                            @yield('content')
                        </div>
                    </div>
                    @include('layouts.footer')
                </div>
            </div>
        @endauth
        @if(@Auth::user())
            @if(@Auth::user()->hasPermissionTo('sales_permission'))
                <div class="fixed-plugin">
                    <div class="dropdown show-dropdown">
                        <a href="#" data-toggle="dropdown">
                            <i class="fa fa-beer fa-2x"> </i>
                        </a>
                        <ul class="dropdown-menu">
                            <a href="javascript:void(0)" class="switch-trigger background-color">
                                <form action="{{ route('sales.new') }}" method="post">
                                    @csrf
                                    <li class="header-title"> Nueva venta</li>
                                    <li class="adjustments-line">
                                        <div class="badge-colors text-center">
                                            <span class="badge filter badge-primary active" data-color="primary"></span>
                                            <span class="badge filter badge-info" data-color="blue"></span>
                                            <span class="badge filter badge-success" data-color="green"></span>
                                        </div>
                                        <div class="clearfix"></div>
                                    </li>
                                    <li class="button-container">
                                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                            <label>{{ __('Fecha de venta') }}</label>
                                            <input type="date" name="sale_date" class="form-control{{ $errors->has('sale_date') ? ' is-invalid' : '' }}" value="{{ old('sale_date', date('Y-m-d')) }}"><br>
                                            <label>{{ __('Nombre Mesa') }}</label>
                                            <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', '') }}" autocomplete="off">
                                            @include('alerts.feedback', ['field' => 'name'])
                                        </div>
                                    </li>
                                    <li class="header-title">Acceso rápido!</li>
                                    <li class="button-container text-center">
                                        <button class="btn btn-round btn-info"><i class="fa fa-save"></i> Crear</button>
                                    </li>
                                </form>
                            </a>
                        </ul>
                    </div>
                </div>
            @endif
        @endif

        <script src="{{ asset('black') }}/js/core/jquery.min.js"></script>
        <script src="{{ asset('black') }}/js/core/popper.min.js"></script>
        <script src="{{ asset('black') }}/js/core/bootstrap.min.js"></script>
        <script src="{{ asset('black') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <script src="{{ asset('black') }}/select2/select2.js"></script>
        <!--  Google Maps Plugin    -->
        <!-- Place this tag in your head or just before your close body tag. -->
        {{-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> --}}
        <!-- Chart JS -->
        {{-- <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script> --}}
        <!--  Notifications Plugin    -->
        <script src="{{ asset('black') }}/js/plugins/bootstrap-notify.js"></script>

        <script src="{{ asset('black') }}/js/black-dashboard.min.js?v=1.0.0"></script>
        <script src="{{ asset('black') }}/js/theme.js"></script>


        @stack('js')
        @yield('js')

        <script>
            $("#loading-open").addClass('loading-head');
            $(window).on('load', function () {
                $("#loading-open").removeClass('loading-head');
            });
            function numberFomat(number, format = true){
                let numero = parseInt(number);

                if(isNaN(numero)){
                    return `-`;
                }
                if(format){
                    return `$` + new Intl.NumberFormat('es-ES').format(numero)
                }else{
                    return numero;
                }
            }
            $(document).ready(function() {
                // $('[data-toggle="tooltip"]').tooltip('show')
                $().ready(function() {
                    $sidebar = $('.sidebar');
                    $navbar = $('.navbar');
                    $main_panel = $('.main-panel');

                    $full_page = $('.full-page');

                    $sidebar_responsive = $('body > .navbar-collapse');
                    sidebar_mini_active = true;
                    white_color = false;

                    window_width = $(window).width();

                    fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

                    $('.fixed-plugin a').click(function(event) {
                        if ($(this).hasClass('switch-trigger')) {
                            if (event.stopPropagation) {
                                event.stopPropagation();
                            } else if (window.event) {
                                window.event.cancelBubble = true;
                            }
                        }
                    });

                    $('.fixed-plugin .background-color span').click(function() {
                        $(this).siblings().removeClass('active');
                        $(this).addClass('active');

                        var new_color = $(this).data('color');

                        if ($sidebar.length != 0) {
                            $sidebar.attr('data', new_color);
                        }

                        if ($main_panel.length != 0) {
                            $main_panel.attr('data', new_color);
                        }

                        if ($full_page.length != 0) {
                            $full_page.attr('filter-color', new_color);
                        }

                        if ($sidebar_responsive.length != 0) {
                            $sidebar_responsive.attr('data', new_color);
                        }
                    });

                    $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
                        var $btn = $(this);

                        if (sidebar_mini_active == true) {
                            $('body').removeClass('sidebar-mini');
                            sidebar_mini_active = false;
                            blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
                        } else {
                            $('body').addClass('sidebar-mini');
                            sidebar_mini_active = true;
                            blackDashboard.showSidebarMessage('Sidebar mini activated...');
                        }

                        // we simulate the window Resize so the charts will get updated in realtime.
                        var simulateWindowResize = setInterval(function() {
                            window.dispatchEvent(new Event('resize'));
                        }, 180);

                        // we stop the simulation of Window Resize after the animations are completed
                        setTimeout(function() {
                            clearInterval(simulateWindowResize);
                        }, 1000);
                    });

                    $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
                            var $btn = $(this);

                            if (white_color == true) {
                                $('body').addClass('change-background');
                                setTimeout(function() {
                                    $('body').removeClass('change-background');
                                    $('body').removeClass('white-content');
                                }, 900);
                                white_color = false;
                            } else {
                                $('body').addClass('change-background');
                                setTimeout(function() {
                                    $('body').removeClass('change-background');
                                    $('body').addClass('white-content');
                                }, 900);

                                white_color = true;
                            }
                    });
                });
            });
        </script>
        @stack('js')
    </body>
</html>
