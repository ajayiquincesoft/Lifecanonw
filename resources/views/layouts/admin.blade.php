<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Life Canon | Admin Dashboard</title>
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
    <!-- Page plugins -->
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/css/argon.css?v=1.2.1') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('admin_assets/css/style.css?v=1.2.1') }}" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

</head>

<body>

    <!-- Sidenav -->
    <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
        <div class="scrollbar-inner">
            <!-- Brand -->
            <div class="sidenav-header  align-items-center">
                <a class="navbar-brand" href="{{ route('user.index',['type'=>1]) }}">
                    <img src="{{ asset('admin_assets/img/brand/LC-Canon-Logo-01-inner.png') }}" class="navbar-brand-img" style="max-height: 100px;">
                </a>
            </div><br />
            <div class="navbar-inner">
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                    <!-- Nav items -->

                    <ul class="navbar-nav">
                        {{-- <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">
                        <i class="ni ni-palette"></i>
                        <span class="nav-link-text">Dashboard</span>
                        </a>
                        </li>
                        <!--<li class="nav-item">
                        <a class="nav-link" href="{{ route('card.index') }}">
                            <i class="fa fa-id-card"></i>
                            <span class="nav-link-text">Card</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cardtype.index') }}">
                            <i class="fa fa-address-card-o"></i>
                            <span class="nav-link-text">Card Type</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('course.index') }}">
                            <i class="fa fa-book"></i>
                            <span class="nav-link-text">Course</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('trainer.index') }}">
                            <i class="fa fa-bars"></i>
                            <span class="nav-link-text">Trainer</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('certs.index') }}">
                            <i class="fa fa-id-card"></i>
                            <span class="nav-link-text">Certs</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.smsLogs') }}">
                            <i class="fa fa-envelope-o"></i>
                            <span class="nav-link-text">SMS Logs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('banner.index') }}">
                            <i class="ni ni-ui-04 text-orange"></i>
                            <span class="nav-link-text">Banner</span>
                        </a>
				   </li>-->
                        <!--<li class="nav-item">
                        <a class="nav-link" href="{{ route('user.index',['type'=>1]) }}">
                            <i class="fa fa-group"></i>
                            <span class="nav-link-text">Users</span>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link" href="{{ route('user.index',['type'=>2]) }}">
                            <i class="fa fa-group"></i>
                            <span class="nav-link-text">Clients</span>
                        </a>
                    </li>--> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-group"></i>
                                <span class="nav-link-text">Users</span>
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.index',['type'=>1]) }}">Coach</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('user.index',['type'=>2]) }}">Client</a></li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('page.index') }}">
                                <i class="fas fa-pager"></i>
                                <span class="nav-link-text">Static Pages</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('feedback.index') }}">
                                <i class="fas fa-comments"></i>
                                <span class="nav-link-text">Feedbacks</span>
                            </a>
                        </li>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('template.index') }}">
                                <i class="fas fa-envelope-square"></i>
                                <span class="nav-link-text">Email Templates</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.profile') }}">
                                <i class="ni ni-single-02"></i>
                                <span class="nav-link-text">Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.password') }}">
                                <i class="ni ni-key-25"></i>
                                <span class="nav-link-text">Change Password</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.setting') }}">
                        <i class="ni ni-settings-gear-65"></i>
                        <span class="nav-link-text">Settings</span>
                        </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="nav-link-text">Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                        <!--<li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-planet text-orange"></i>
                            <span class="nav-link-text">Category</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-ui-04 text-orange"></i>
                            <span class="nav-link-text">Product</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-palette"></i>
                            <span class="nav-link-text">All Order</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-planet text-orange"></i>
                            <span class="nav-link-text">Order Return</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="" >
                            <i class="ni ni-palette"></i>
                            <span class="nav-link-text">Rating & Review</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-circle-08 text-primary"></i>
                            <span class="nav-link-text">Customer</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-bullet-list-67 text-default"></i>
                            <span class="nav-link-text">Coupon</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="" >
                            <i class="ni ni-ui-04"></i>
                            <span class="nav-link-text">Shipping Charge</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-circle-08 text-pink"></i>
                            <span class="nav-link-text">Slider</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-send text-dark"></i>
                            <span class="nav-link-text">Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-single-02 text-yellow"></i>
                            <span class="nav-link-text">Profile</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="">
                            <i class="ni ni-key-25 text-info"></i>
                            <span class="nav-link-text">Contact Info</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="" >
                            <i class="ni ni-palette"></i>
                            <span class="nav-link-text">Change Password</span>
                        </a>
                    </li>-->




                    </ul>
                    <!-- Divider -->
                    <hr class="my-3">
                    <!-- Heading -->
                    <!--                <h6 class="navbar-heading p-0 text-muted">-->
                    <!--                    <span class="docs-normal">Documentation</span>-->
                    <!--                </h6>-->
                    <!-- Navigation -->
                    <!--<ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/getting-started/overview.html" target="_blank">
                            <i class="ni ni-spaceship"></i>
                            <span class="nav-link-text">Getting started</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/foundation/colors.html" target="_blank">
                            <i class="ni ni-palette"></i>
                            <span class="nav-link-text">Foundation</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/components/alerts.html" target="_blank">
                            <i class="ni ni-ui-04"></i>
                            <span class="nav-link-text">Components</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/plugins/charts.html" target="_blank">
                            <i class="ni ni-chart-pie-35"></i>
                            <span class="nav-link-text">Plugins</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active active-pro" href="examples/upgrade.html">
                            <i class="ni ni-send text-dark"></i>
                            <span class="nav-link-text">Upgrade to PRO</span>
                        </a>
                    </li>
                </ul>-->
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="main-content" id="panel">
        <!-- Topnav -->
        <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Search form -->
                    <!--<form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                    <div class="form-group mb-0">
                        <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" placeholder="Search" type="text">
                        </div>
                    </div>
                    <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </form>-->
                    <!-- Navbar links -->
                    <ul class="navbar-nav align-items-center  ml-md-auto ">
                        <li class="nav-item d-xl-none">
                            <!-- Sidenav toggler -->
                            <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </div>
                        </li>


                    </ul>
                    <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
                        <li class="nav-item dropdown">
                            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="media align-items-center">
                                    <span class="avatar avatar-sm rounded-circle">
                                                        <!-- <img alt="Image placeholder" src="{{ Auth::User()->image }}"> -->
                                                        <img alt="Image placeholder" src="{{ asset('admin_assets/img/brand/profile.png') }}">
                                    </span>
                                    <div class="media-body  ml-2  d-none d-lg-block">
                                        <span class="mb-0 text-sm">{{ Auth::user()->name }}</span>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu  dropdown-menu-right ">
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome!</h6>
                                </div>
                                {{-- <a href="{{ route('home') }}" class="dropdown-item">
                                <i class="ni ni-support-16"></i>
                                <span>Dashboard</span>
                                </a> --}}
                                <a href="" class="dropdown-item">
                                    <i class="ni ni-single-02"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="" class="dropdown-item">
                                    <i class="ni ni-settings-gear-65"></i>
                                    <span>Setting</span>
                                </a>
                                <!-- <a href="#!" class="dropdown-item">
                                 <i class="ni ni-single-02"></i>
                                 <span>My profile</span>
                             </a>
                             <a href="#!" class="dropdown-item">
                                 <i class="ni ni-settings-gear-65"></i>
                                 <span>Settings</span>
                             </a>
                             <a href="#!" class="dropdown-item">
                                 <i class="ni ni-calendar-grid-58"></i>
                                 <span>Activity</span>
                             </a>
                             <a href="#!" class="dropdown-item">
                                 <i class="ni ni-support-16"></i>
                                 <span>Support</span>
                             </a>-->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="ni ni-user-run"></i>
                                    <span>Logout</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Header -->
        <!-- page content start-->
        @yield('content')
        <!-- page content end-->

    </div>
    <div id="snackbar"></div>
    <!-- Core -->
    <script src="{{ asset('admin_assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
    <!-- Optional JS -->
    <script src="{{ asset('admin_assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
    <!-- Argon JS -->
    <script src="{{ asset('admin_assets/js/argon.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        $(document).ready(function() {
            $('#example-1').DataTable();
        });
        $(document).ready(function() {
            $('.summernote').summernote({

                height: 400
            });
        });
    </script>

    @yield('scripts')
    <script>
        $('select').selectpicker();
    </script>

</body>

</html>