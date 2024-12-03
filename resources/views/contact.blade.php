@extends('layouts.app')

@section('content')
<div class="about-inner dd">
 <h1 data-aos="fade-right">Contact <span>Us</span></h1>
 <img width="100%" src="{{asset('assets/front-assets/images/contact-banner.jpg')}}">
</div><!-- about-inner end here -->


<div class="cont-sec">
 <div class="container">
  <div class="row">
   <div class="col-lg-4 col-md-5" data-aos="fade-right">
    <h2>How to find us?</h2>
    <p>If you have any questions, just fill in the contact form, and we will answer you shortly. If you are living nearby, come visit TaxExpert at one of our comfortable offices.</p>
    <h4>HEADQUARTERS</h4>
    <h6>8963 MILL ROAD, CAMBRIDGE, MG09 99HT.</h6>
    <ul>
     <!--<li><span>Telephone</span> <a href="tel:+1 800 603 6035">+1 805-714-0446</a></li>-->
     <li><span>E-mail</span> <a href="mailto:scott@contractorwebsiteservices.com">scott@contractorwebsiteservices.com</a></li>
    </ul><!-- ul end here -->
   </div><!-- col end here -->
   
   <div class="col-lg-1"></div>
   <div class="col-lg-7 col-md-7">
    <h2>Get in touch</h2>
  <div class="row">
		<div class="col-lg-1 col-md-1"></div>
		<div class="col-md-10"> @if(session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
                @endif
				@if ($errors->has('captcha'))
				  <span class="help-block">
					  <div class="alert alert-danger">{{ $errors->first('captcha') }}</div>
				  </span>
			  @endif
		</div></div>
      <div class="row">
         <div class="col-lg-1 col-md-1"></div>
         <div class="col-lg-10 col-md-10">
            <form data-aos="fade-up" action="{{route('contactform')}}" method="post" enctype="multipart/form-data" id="contact_form">
			@csrf
               <div class="row">
                  <div class="col-lg-4 col-md-4">
                     <div class="in-box"><h5>Name*</h5><input class="box" type="text" name="name" placeholder="Your Name" required /></div>
                  </div><!-- col end here -->

                  <div class="col-lg-4 col-md-4">
                     <div class="in-box"><h5>Email*</h5><input class="box" type="email" name="email" placeholder="Your e-Mail Address" required /></div>
                  </div><!-- col end here -->

                  <div class="col-lg-4 col-md-4">
                     <div class="in-box"><h5>Phone*</h5><input class="box" type="number" name="phone" placeholder="Your Phone Number" required /></div>
                  </div><!-- col end here -->
               </div><!-- row end here -->
               <div class="in-box"><h5>Message*</h5><textarea class="box" type="text" name="message" placeholder="Message" rows="4"></textarea></div>
				<!-----------Captcha code------------->
				<div class="in-box">
					<div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
						  <div class="captcha" style="padding: 0px 3px 15px 1px;text-align: left;">
						  <span>{!! captcha_img() !!}</span>
						  <button type="button" class="btn btn-success btn-refresh" id="refreshbtn"><i class="fa fa-refresh"></i></button>
						  </div>
						  <input id="captcha" type="text" class="box" placeholder="Enter Captcha" name="captcha">
						 
					 </div>
				</div>
				<!-----------End Captcha code----------->
			   
               <button class="log-btn" type="submit">Send Message</button>
            </form><!-- form end here -->
         </div><!-- col end here -->
      </div><!-- row end here -->
   </div><!-- col end here -->
  </div><!-- row end here -->
 </div><!-- container end here -->
</div><!-- cont-sec end here -->

@section('script')
	<script>
		$("#refreshbtn").click(function(){
			$.ajax({
				 type:'GET',
				 url:"{{route('refresh_captcha')}}",
				 success:function(data){
					 if(data.captcha){
						$(".captcha span").html(data.captcha);
					 }
				 }
			});
		});
	</script>
@stop

@endsection
