@extends('user.layout.app')

@section('content')

<!-- Page content -->


<div class="sign-sec-availablity new-clt">
<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
                    <div class="row">
					   <div class="col-lg-12">
						<a class="back-btn" href="{{route('user.dashboard')}}"><i class="fa fa-chevron-left"></i> Back</a>
						<h2>My Availability</h2>
					   </div><!-- col end here -->

					</div>
               
             
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
					//print_r($data['times']);
					$weekdays = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
				@endphp
				<div class="row">
				<div class="col-md-2"></div>
					<div class="col-md-8">
					 <label>Choose Available Weekdays</label>
					</div>
				</div>
				<br>
				<form autocomplete="off" action="{{route('coach.availability')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
				@csrf
				<div class="row">
				
					<div class="col-md-2"></div>
					<div class="col-md-8">
					<div class="row">
				
					 @foreach(@$weekdays as $weekday)
						@if(in_array($weekday,@$data['days']))
						<div class="col-md-3">
						<label for="week-add"><input class="greeninput " type="checkbox" name="days[]" value="{{ $weekday }}" checked>&nbsp;{{ $weekday }}</label>
						</div>
						@else
						<div class="col-md-3">
							<label for="week-add"><input class=" " type="checkbox" name="days[]" value="{{ $weekday }}">&nbsp;{{ $weekday }}</label>
						</div>
						@endif	
					 @endforeach
					
					 </div>
					 </div>
					 <div class="col-md-2"></div>
				</div>
				<br>
				<br>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
					 <label>Choose Available Time Slot</label>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
					<div class="row" id="timearea">
					<?php if($data['times']){ ?>
					 @foreach($data['times'] as $time)
						<div class="col-md-3">
						<label for="time-slot"><input class="greeninput " type="checkbox" name="time[]" value="{{ $time }}" checked>&nbsp;{{ $time }}</label>
						</div>
							
					 @endforeach
					<?php }else{ echo "Available time is not Available.";}?>
					 </div>
					 <br>
					 <div class="row">
					 <div class="col-md-12"><h2>Add More Time Slots</h2>
					 <div class="error_mess"></div>
					 </div>
					  <div class="col-md-5">
						<label>From</label>
						<input class="form-control starttime" type="text" id="datetime" />
					  </div>
					  <div class="col-md-5">
					  <label>To</label>
						<input class="form-control endtime" type="text" id="datetime1" />
					  </div>
					  <div class="col-md-2">
					   <label></label>
					   <div id="myDiv" class="btn btn-dark save-btn" style="margin-top: 33px;">Add Time</div>

						
					  </div>
					 </div>
					 <br><br>
					 <div class="row">
					  <div class="col-md-12">
					  <button type="Submit" class="btn btn-dark save-btn">Save Availability</button>
					 </div>
					 </div>
					 <div class="col-md-2"></div>
				</div>
				</form>
  </div>
</div>
</div>
</div></div>

@endsection

@section('script')
<script>
$('#datetime').datetimepicker({
	format: 'hh:mm A'
});
$('#datetime1').datetimepicker({
	format: 'hh:mm A'
});
		
$('#myDiv').click(function(){	
var start = $('#datetime').val();
var endt = 	$('#datetime1').val();
if((start === undefined || start === null || start === '') && (endt === undefined || endt === null || endt === '')){
	$('.error_mess').html('<div class="alert alert-danger">Please fill the times</div>');
}else{
	$('.error_mess').remove();
	$('#timearea').append('<div class="col-md-3"><label for="time-slot"><input class="greeninput " type="checkbox" name="time[]" value="'+ start + ' - '+ endt +'" checked=""> '+ start + ' - '+ endt +'</label></div>');
	
}

});		

</script>
@stop