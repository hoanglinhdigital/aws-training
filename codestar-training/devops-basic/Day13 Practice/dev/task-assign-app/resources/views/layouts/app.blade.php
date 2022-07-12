<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>




    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
    </script>

    <!-- Bootstrap select -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- gijgo plugin for treeview -->
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    

</head>

<body>
    <div class="overlay"></div>
    <div id="app">

        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                @auth
                    <nav id="sidebar">
                        <div id="dismiss">
                            <i class="fas fa-arrow-left"></i>
                        </div>
            
                        <div class="sidebar-header">
                            <h3>
                                <a class="navbar-brand" href="{{ url('/jobs') }}">{{ config('app.name', 'Laravel') }}</a>
                            </h3>
                        </div>

                        @if (Auth::user()->is_admin)
                            @include('components.sidebar-content', ['options' => [
                                ['link' => route('accounts.pending'), 'value' => 'Duyệt tài khoản mới'],
                                ['link' => route('staff_info.show', ['id' => Auth::user()->staff_id]), 'value' => 'Thông tin cá nhân'],
                                ['link' => route('jobs.create'), 'value' => 'Tạo công việc mới'],
                                ['link' => route('jobs.index', ['type' => 'pending']), 'value' => 'Nhận việc'],
                                ['link' => route('jobs.index', ['type' => 'handling']), 'value' => 'Công việc đang xử lý'],
                                ['link' => route('jobs.index', ['type' => 'assigner']), 'value' => 'Công việc đã giao xử lý'],
                                [
                                    'href' => 'search_categories',
                                    'parentText' => 'Tra cứu/Tìm kiếm',
                                    'children' => [
                                        ['link' => route('jobs.index'), 'value' => 'Tìm kiếm công việc'],
                                        ['link' => route('timesheet-statis.list'), 'value' => 'Thống kê timesheet'],
                                        ['link' => route('project-plan.list'), 'value' => 'Kế hoạch dự án'],
                                        ['link' => route('backup-manday.list'), 'value' => 'Manday dự phòng'],
                                        ['link' => route('free-time.list'), 'value' => 'Tìm kiếm thời gian rảnh']
                                    ]
                                ],
                                [
                                    'href' => 'system_categories',
                                    'parentText' => 'Danh mục hệ thống',
                                    'children' => [
                                        ['link' => route('project.list'), 'value' => 'Mã dự án'],
                                        ['link' => route('project-type.list'), 'value' => 'Loại công việc'],
                                        ['link' => route('priority.list', ['type' => 'handling']), 'value' => 'Độ ưu tiên'],
                                        ['link' => route('skill.list', ['type' => 'handling']), 'value' => 'Kỹ năng'],
                                        ['link' => route('process-method.list'), 'value' => 'Hình thức xử lý'],
                                        ['link' => route('config.list'), 'value' => 'Cấu hình'],

                                    ]
                                ]

                            ]])
                        @else
                            @include('components.sidebar-content', ['options' => [
                                ['link' => route('staff_info.show', ['id' => Auth::user()->staff_id]), 'value' => 'Thông tin cá nhân'],
                                ['link' => route('jobs.create'), 'value' => 'Tạo công việc mới'],
                                ['link' => route('jobs.index', ['type' => 'pending']), 'value' => 'Nhận việc'],
                                ['link' => route('jobs.index', ['type' => 'handling']), 'value' => 'Công việc đang xử lý'],
                                ['link' => route('jobs.index', ['type' => 'assigner']), 'value' => 'Công việc đã giao xử lý'],
                                [
                                    'href' => 'search_categories',
                                    'parentText' => 'Tra cứu/Tìm kiếm',
                                    'children' => [
                                        ['link' => route('jobs.index'), 'value' => 'Tìm kiếm công việc'],
                                        ['link' => route('timesheet-statis.list'), 'value' => 'Thống kê timesheet'],
                                        ['link' => route('project-plan.list'), 'value' => 'Kế hoạch dự án'],
                                        ['link' => route('backup-manday.list'), 'value' => 'Manday dự phòng'],
                                        ['link' => route('free-time.list'), 'value' => 'Tìm kiếm thời gian rảnh']
                                    ]
                                ],

                            ]])
                        @endif
            

            
            
                    </nav>
                    
                    <button class="navbar-toggler d-inline" id="sidebarCollapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                @endauth
                



                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Đăng nhập') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Đăng ký') }}</a>
                                </li>
                            @endif
                        @else


                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->staff->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                        {{ __('Đăng xuất') }}
                                    </a>


                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>


    </div>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#dismiss, .overlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });

            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').addClass('active');
                $('.overlay').addClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });

            //bootstrap-select init
            $.fn.selectpicker.Constructor.BootstrapVersion = '4';
            $('.selectpicker').selectpicker();

        });
    </script>

</body>

</html>
