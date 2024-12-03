@extends('layouts.app')

@section('content')
<div class="sign-inner"><img width="100%" src="{{asset('assets/front-assets/images/sign-img.jpg')}}"></div>

<div class="sign-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
               
                <h2>Delete your Account</h2>
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
				@if(session('success'))
					<div class="alert alert-success">{{session('success')}}</div>
                @endif
                <form autocomplete="off" action="{{route('account_delete')}}" method="post" id="user-login-form" enctype="multipart/form-data">
                    @csrf
                    <div class="in-box">
                        <h5>EMail Address*</h5> <input class="box" type="email" name="email" placeholder="" required />
                    </div>
           
                    <button type="submit" class="log-btn" value="submit" onclick='return confirm("Are you sure you want to delete this account ")'>Submit</button>
                </form><!-- form end here -->
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- sign-sec end here -->


@endsection