@extends('layouts.app')

@section('content')
<div class="about-inner dd">
 <h1 data-aos="fade-right" class="aos-init aos-animate">{!! $content->page_title !!}</h1>
 <img src="{{asset('assets/front-assets/images/contact-banner.jpg')}}" width="100%">
</div><!-- about-inner end here -->

<div class="page-sec">
 <div class="container">
  <div class="row">
   <div class="col-lg-12 col-md-12">
    {!! $content->page_content !!}
   </div><!-- col end here -->
  </div><!-- row end here -->
 </div><!-- container end here -->
</div><!-- page-sec end here -->
@endsection