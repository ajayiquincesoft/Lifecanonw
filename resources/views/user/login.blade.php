@extends('layouts.app')

@section('content')
<div class="sign-inner"><img width="100%" src="{{asset('assets/front-assets/images/sign-img.jpg')}}"></div>

<div class="sign-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a class="back-btn" href="{{ url()->previous() }}"><i class="fa fa-chevron-left"></i> Back</a>
                <h2>Log In to your Account</h2>
            </div><!-- col end here -->

            <div class="col-lg-4 col-md-3"></div>
            <div class="col-lg-4 col-md-6">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form autocomplete="off" action="{{route('user-login')}}" method="post" id="user-login-form" enctype="multipart/form-data">
                    @csrf
                    <div class="in-box">
                        <h5>e-Mail Address*</h5> <input class="box" type="email" name="email" placeholder="" required />
                    </div>
                    <div class="in-box">
                        <h5>Password*</h5> <input class="box" type="password" name="password" placeholder="" required />
                    </div>
                    <button type="submit" class="log-btn" value="submit">Log In</button>
                </form><!-- form end here -->
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- sign-sec end here -->


@endsection