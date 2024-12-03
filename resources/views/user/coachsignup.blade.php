@extends('layouts.app')

@section('content')

<div class="sign-inner"><img width="100%" src="{{asset('assets/front-assets/images/sign-img.jpg')}}"></div>

<div class="sign-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a class="back-btn" href="{{route('user-signup')}}"><i class="fa fa-chevron-left"></i> Back</a>
                <h2>Create your Account</h2>
            </div><!-- col end here -->


            <div class="col-xl-3 col-lg-2 col-md-1"></div>
            <div class="col-xl-6 col-lg-8 col-md-10">
                <form autocomplete="off" action="{{route('user-register')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        @csrf

                        <div class="col-lg-4 col-md-4">
                            <div class="add-pic">
                                <input type="file" name="profile_pic" id="add-pic">
                                <label for="add-pic"><img class="pro-pic" src="{{asset('assets/front-assets/images/pro-pic.png')}}"></label>
                                <span>Add Profile Picture</span>
                            </div><!-- add-pic end here -->
                        </div><!-- col end here -->

                        <div class="col-lg-8 col-md-8">
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
                            <div class="in-box">
                                <h5>Name*</h5> <input class="box" type="text" name="name" value="{{old('name')}}" required />
                            </div>
                            <div class="in-box">
                                <h5>e-Mail Address*</h5> <input class="box" type="email" name="email" value="{{old('email')}}" required />
                            </div>
                            <div class="in-box">
                                <h5>Password*</h5> <input class="box" type="password" name="password" required />
                            </div>
                            <div class="in-box">
                                <h5>Confirm Password*</h5> <input class="box" type="password" name="confirm_password" placeholder="" required />
                                <input class="box" type="hidden" name="user_type" placeholder="" value="1" />
                            </div>
							
							 <div class="in-box">
                                <h5>Select Your Time Zone*</h5> 
                                <select class="box form-control" name="timezone">
									<option value="">Select Your Time Zone</option>
									
									<option value="America/Denver">Mountain time</option>
									<option value="America/Los_Angeles">Pacific time</option>
									<option value="America/Chicago">Central time</option>
									<option value="America/New_York">Eastern time</option>
								</select>
                            </div>
							<br>
                            <h6><input type="checkbox" name="termsandcondition" value="1" required> <span>I agree to the <a href="{{url('terms-conditions')}}">Terms & Conditions</a> and <a href="{{url('privacy-policy')}}">Privacy Policy</a></span></h6>
                            <button type="submit" class="log-btn" value="submit">Sign Up</button>
                        </div><!-- col end here -->
                    </div><!-- row end here -->
                </form><!-- form end here -->
            </div>
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- sign-sec end here -->

@endsection
@section('script')
<script type="text/javascript">
    $("#add-pic").change(function() {
        readLogoURL(this);
    });

    function readLogoURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.pro-pic').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@stop