@extends('user.layout.app')

@section('content')

<!-- Page content -->
<br />
<br />
<br />
<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Stripe </h3>
                        </div>
						 <div class="col-4">
                            <h3 class="mb-0"><a data-toggle="modal" data-target="#stripsetup" class="stripe_button" style="cursor:pointer;">Stripe Setup </a></h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
                    @php
                    $user=Auth::user();
                    @endphp
                    <form method="post" action="{{ route('coach.saveaddorupdatestripe') }}" enctype="multipart/form-data" id="updatestripe">
                        @csrf
                        <div class="pl-lg-4">

                            @if(!@$user->stripe->auth_code or strtotime('+5 minutes',strtotime(@$user->stripe->updated_at)) <= strtotime(date('Y-m-d H:i:s')) ) <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Secret Key</label>
                                        <input type="text" name="secret_key" class="form-control" value="<?php  echo $substr1 = substr(@$user->stripe->secret_key,0,4);
									  $substr2 = strlen(substr(@$user->stripe->secret_key,4));
									 for($i=0;$i<$substr2;$i++){
										echo '*'; 
									 } ?>">
                                        @error('secret_key')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Published Key</label>
								
                                    <input type="text" name="published_key" class="form-control" value="<?php echo $substr1 = substr(@$user->stripe->published_key,0,4);
									  $substr2 = strlen(substr(@$user->stripe->published_key,4));
									 for($i=0;$i<$substr2;$i++){
										echo '*'; 
									 } ?>">
									 
                                    @error('published_key')<div class="text-danger">{{ $message }}*</div>@enderror
                                </div>
                            </div>

                        </div>
                        @else
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" name="secret_key" class="form-control" value="{{ @$user->stripe->secret_key }}">
                                <input type="hidden" name="published_key" class="form-control" value="{{ @$user->stripe->published_key }}">
                                <div class="form-group">
                                    <label class="form-control-label">OTP</label>
                                    <input type="text" name="auth_code" class="form-control" value="">
                                    @error('auth_code')<div class="text-danger">{{ $message }}*</div>@enderror
                                </div>
                            </div>

                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-4">
                                <button type="submit" class="btn btn-dark save-btn">save</button>
                            </div>
                            @if(!@$user->stripe->verified and $user->stripe )
                            <div class="col-lg-4">
                                <a href="{{route('pay')}}?payment_for=6" class="btn btn-dark">Varify It</a>
                            </div>

                            @endif


                        </div>
                </div>


                </form>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade coach-popup" id="stripsetup">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Stripe Setup</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
                  <strong>Why do i need to setup a stripe account?</strong>
				  <br>
				  <br>
				  <p>It is required so that you can get paid from your clients directly to your bank account !</p>
				  <br>
				  <h3 class="setuptripe_text"><a data-toggle="modal" data-target="#stripsetup_step1" id="hide_first">Show me how to setup my stripe account.</a></h3>
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->
	<div class="modal fade coach-popup" id="stripsetup_step1">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2><a class="back-btn" id="back1" style="margin-right: 18px;position: relative;"><i class="fa fa-chevron-left"></i>Back</a>&nbsp;&nbsp;&nbsp; Stripe Setup</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
				
					
					<div>
					  <strong>Do you have a stripe account?</strong>
						<br>
						<br>
					  <div>
							<button data-toggle="modal" data-target="#stripsetup_step2" class="log-btn" id="no" style="margin-right: 12px;margin-top: 20px;">No</button>
							&nbsp;
							<button class="log-btn" id="yes" data-toggle="modal" data-target="#stripsetup_step3">Yes</button>
					  </div>
					</div>
				</div>
				<!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->
	<div class="modal fade coach-popup" id="stripsetup_step2">
        <div class="modal-dialog">
            <div class="modal-content">
               <h2>&nbsp;&nbsp;&nbsp;Stripe Setup</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body" style="padding:15px; padding-top: 35px; padding-bottom: 35px;">
					<div>
						<a class="new-btn" id="back2" style="margin-right: 18px;position: relative;">Back</a>
						<a  class="new-btn" id="logged_register">Click here to register</a>
					</div>
				</div>
				<!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->
<div class="modal fade coach-popup" id="stripsetup_step3">
	<div class="modal-dialog">
		<div class="modal-content">
			<h2>&nbsp;&nbsp;&nbsp;Stripe Setup</h2>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<div class="modal-body">
				<div>
					<a class="new-btn" id="back3" style="margin-right: 18px;position: relative;">Back</a>
					<a data-toggle="modal" data-target="#click_login"  href="https://dashboard.stripe.com/login" class="new-btn" target="_blank" id="clicklogin_here">Click here to login</a>
				</div>
			</div>
			<!-- modal-body end here -->
		</div><!-- modal-content end here -->
	</div><!-- modal-dialog end here -->
</div><!-- modal end here -->
<div class="modal fade coach-popup" id="click_login">
	<div class="modal-dialog">
		<div class="modal-content">
			<h2>&nbsp;&nbsp;&nbsp;Stripe Setup</h2>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<div class="modal-body">
				<p>Ok, I have logged in to my stripe account.</p>
				<br>
				<div>
					<a class="new-btn" id="back4" style="margin-right: 18px;position: relative;">Back</a>
					<a class="new-btn" id="loggedin">Next</a>
				</div>
			</div>
			<!-- modal-body end here -->
		</div><!-- modal-content end here -->
	</div><!-- modal-dialog end here -->
</div><!-- modal end here -->
<div class="modal fade coach-popup" id="next_login">
	<div class="modal-dialog">
		<div class="modal-content" style="max-height:600px;">
			<h2>&nbsp;&nbsp;&nbsp;Stripe Setup</h2>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<div class="modal-body" style="padding:12px;">
				<p style="font-size: 20px;color: #525252;">Click on "Developers" tab on top right.</p>
				<small>Note: Please make sure to enable <span style="color:red">Live mode.</span></small>
				<br>
				<br>
				<div><img src="{{asset('assets/front-assets/images/login_stripe2.jpg')}}" class="img-responsive" width="50%"></div>
				<br>
				<br>
				<div>
					<a class="new-btn" id="back5" style="margin-right: 18px;position: relative;">Back</a>

					<a data-toggle="modal" data-target="#next_loggged_key" class="new-btn" id="getloggedkey">Next</a>
				</div>
			</div>
			<!-- modal-body end here -->
		</div><!-- modal-content end here -->
	</div><!-- modal-dialog end here -->
</div><!-- modal end here -->

<div class="modal fade coach-popup" id="next_loggged_key">
	<div class="modal-dialog">
		<div class="modal-content">
			<h2>&nbsp;&nbsp;&nbsp;Stripe Setup</h2>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<div class="modal-body">
				<p style="font-size: 20px;color: #525252;">Copy the publishable key and the secret key</p>
				<br>
				<div><img src="{{asset('assets/front-assets/images/logged2.jpg')}}" class="img-responsive" width="100%"></div>
				<br>
				<br>
				<div>
					<a class="new-btn" id="back6" style="margin-right: 18px;position: relative;">Back</a>
					<a href="{{route('coach.getaddorupdatestripe')}}" class="new-btn" class="">Next</a>
				</div>
			</div>
			<!-- modal-body end here -->
		</div><!-- modal-content end here -->
	</div><!-- modal-dialog end here -->
</div><!-- modal end here -->
@endsection

@section('script')
<script>
    $('#updatestripe').submit(function(event) {
        if (!confirm("are you sure you want to update key")) {
            event.preventDefault();
        }
    });
$("#hide_first").click(function(){
  $('#stripsetup').modal('hide');
});

$("#no").click(function(){
  $('#stripsetup_step1').modal('hide');
});
$("#yes").click(function(){
  $('#stripsetup_step1').modal('hide');
});
$("#back1").click(function(){
	$('#stripsetup_step1').modal('hide');
  $('#stripsetup').modal('show');
  
});
$("#back2").click(function(){
	$('#stripsetup_step2').modal('hide');
  $('#stripsetup_step1').modal('show');
  
});
$("#back3").click(function(){
	$('#stripsetup_step3').modal('hide');
  $('#stripsetup_step2').modal('show');
  
});

$("#back4").click(function(){
	$('#click_login').modal('hide');
  $('#stripsetup_step3').modal('show');
  
});

$("#back5").click(function(){
	$('#next_login').modal('hide');
  $('#click_login').modal('show');
  
});

$("#back6").click(function(){
	$('#next_loggged_key').modal('hide');
  $('#next_login').modal('show');
  
});
$("#clicklogin_here").click(function(){
	$('#stripsetup_step3').modal('hide');
  $('#click_login').modal('show');

  window.open('https://dashboard.stripe.com/login','_blank');
  
});
$("#loggedin").click(function(){
	$('#click_login').modal('hide');
  $('#next_login').modal('show');
});
$("#getloggedkey").click(function(){
	$('#next_login').modal('hide');
  $('#next_loggged_key').modal('show');
  
});
$("#logged_register").click(function(){
	$('#stripsetup_step2').modal('hide');
  $('#click_login').modal('show');

  window.open('https://dashboard.stripe.com/login','_blank');
  
});
</script>
@stop