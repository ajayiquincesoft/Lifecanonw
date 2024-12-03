<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="admin login.">
    <meta name="author" content="Creative shivam">
    <title>Admin Login</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('admin_assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" type="text/css">
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/css/argon.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/custom.css')}}" type="text/css">
</head>

<body class="bg-default">

    <!-- Main content -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="change-txt">
                    @if(session('success'))
                    <img src="{{asset('assets/images/tick/tick.png')}}">
                    <h3>Password Changed!</h3>
                    <p>{{session('success')}}</p>

                    @endif
                    @if ($errors->any())
                    <img src="{{asset('assets/images/tick/cross.png')}}">
                    <h3>Password Not Changed!</h3>               

                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach

                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Argon Scripts -->

    <!-- Core -->
    <script src="{{ asset('admin_assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/js-cookie/js.cookie.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
    <!-- Argon JS -->
    <script src="{{ asset('admin_assets/js/argon.js') }}"></script>
</body>

</html>