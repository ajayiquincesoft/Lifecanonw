@extends('layouts.app')

@section('content')
<div class="sign-inner"><img width="100%" src="{{asset('assets/front-assets/images/sign-img.jpg')}}"></div>

<div class="sign-sec">
 <div class="container">
  <div class="row">
   <div class="col-lg-12">
   <a class="back-btn" href="{{ url()->previous() }}"><i class="fa fa-chevron-left"></i> Back</a>
    <h2>Sign Up as</h2>
   </div><!-- col end here -->
   
   <div class="col-lg-3 col-md-2"></div>
   <div class="col-lg-3 col-md-4">
    <div class="sign-box">
     <a href="{{route('coach-signup')}}">
	 <!--<img src="{{asset('assets/front-assets/images/sign-icon.png')}}">--> 
	 <img src="{{asset('assets/front-assets/images/Coach.svg')}}" width="100" height="100">
     <h4>Coach</h4></a>
    </div><!-- sign-box end here -->
   </div><!-- col end here -->
   
   <div class="col-lg-3 col-md-4">
    <div class="sign-box">
     <a href="{{route('client-signup')}}">
	 <!--<img src="{{asset('assets/front-assets/images/sign-icon1.png')}}">!-->
	 <img src="{{asset('assets/front-assets/images/Customer.svg')}}" width="100" height="100">
     <h4>Client</h4></a>
    </div><!-- sign-box end here -->
   </div><!-- col end here -->
  </div><!-- row end here -->
 </div><!-- container end here -->
</div><!-- sign-sec end here -->


@endsection