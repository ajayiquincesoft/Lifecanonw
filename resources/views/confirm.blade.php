@extends('layouts.app')

@section('content')
<style>
.em-main {
	background-color: green;
	height: 70vh;
	padding: 60px 0 0;
}
.em-txt {
	background-color: #fff;
	color: #000;
	font-size: 20px;
	padding: 55px 0 0;
	text-align: center;
	width: 100%;
	height: 100vh;
	width: 50%;
	margin: 0 auto;
}
.em-txt h3 {
	color: #000;
	font-size: 48px;
	font-weight: 600;
	margin: 0 0 40px;
}
.em-txt i {
	color: #333;
	display: block;
	font-size: 100px;
	padding: 0 0 25px;
}

@media(min-width:320px) and (max-width:767px) {
.em-main {
	padding: 18px 18px 0;
}	
.em-txt {
	width: 100%;
}
.about-inner img {
	height: 150px;
	object-fit: cover;
}	
}
</style>
<div class="about-inner dd" style="min-height:150px;background:green;">
 <h1 data-aos="fade-right"><span></span></h1>
 <!--<img width="100%" src="{{asset('assets/front-assets/images/contact-banner.jpg')}}">-->
</div><!-- about-inner end here -->
<div class="em-main"><div class="em-txt"><h3>Welcome!</h3> <i class="fa fa-envelope-open-o"></i> {{$msg}}</div></div>

@endsection
