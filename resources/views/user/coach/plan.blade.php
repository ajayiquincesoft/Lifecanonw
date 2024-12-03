@extends('user.layout.app')

@section('content')
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

<div class="sign-sec new-clt">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a class="back-btn" href="/"><i class="fa fa-chevron-left"></i> Back</a>
                <h2>Plans</h2>

                <form  action="{{route('pay')}}" method="get" id="user-registration-form" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-xl-3 col-lg-2 col-md-1"></div>
                        @foreach( $plans as $plan)
                        <div class="col-xl-3 col-lg-4 col-md-5">
                            <h3>{{$plan->name}} Plan</h3>
                            <div class="plan-box" id="plan-boxess{{$plan->id}}">
                                <input type="radio" id="plan-add{{$plan->id}}" name="plan" value="{{$plan->id}}" style="width:18px; height:18px;top: 10px;"required>
                                <input type="hidden" name="payment_for" value="1" required>
								<span class="checkmark"></span>
                                <label for="plan-add{{$plan->id}}">
                                    <h5>${{$plan->price}} / {{$plan->name}}</h5>
                                    <ul>
                                        <li>Keep all your clients in one easy-to-use app</li>
                                        <li>Track progress with checklists, journaling, private notes, & more</li>
                                        <li>Keep your schedule & get paid within the app</li>
                                        <li>Save {{$plan->save_amount}}/{{$plan->name}}</li>
                                    </ul><!-- ul end here -->
                                </label>
                            </div><!-- plan-box end here -->
                        </div><!-- col end here -->
                        @endforeach
                    </div><!-- row end here -->
                    <button class="log-btn" type="submit">Next</button>
                </form><!-- form end here -->
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- sign-sec end here -->


@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
	
    $('#plan-boxess1').click(function() {
        $('#plan-boxess1').css('border','3px solid#009C00');
		$('#plan-boxess1 li').css('color','#000000');
		$('#plan-boxess2 li').css('color','#818181');
		$('#plan-boxess2').removeAttr('style');
    }); 
	$('#plan-boxess2').click(function() { 
        $('#plan-boxess2').css('border','3px solid#009C00');
		$('#plan-boxess2 li').css('color','#000000');
		$('#plan-boxess1 li').css('color','#818181');
		$('#plan-boxess1').removeAttr('style');
         
    });
});
</script>
@stop