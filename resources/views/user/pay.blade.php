@extends('user.layout.app')

@section('content')
<div class="sign-sec new-clt">
    <div class="container">
		<div class="row">
			<div class="col-lg-12">
			 <a class="back-btn" href="{{route('coach.plan')}}"><i class="fa fa-chevron-left"></i> Back</a>
			</div>
		</div>	
	</div>
	<br/><br/>
	<div class="container">
        <div class="row">
		<div class="col-lg-1"></div>
            <div class="col-lg-5">
                <h2>Payment Details</h2>
                <form action="{{route('pay')}}" method="post" data-cc-on-file="false" class="require-validation" id="add-client-form" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        
                        <div class="col-lg-12 col-md-12">
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
                                <input type="hidden" name="plan_id" value="{{app('request')->input('plan')}}">
                                <input type="hidden" name="payment_for" value="{{app('request')->input('payment_for')}}">
                                <input type="hidden" name="code" value="{{app('request')->input('code')}}">
								
                                <h5>Name*</h5> <input class="box" size='4' type='text' required>
                            </div>
                            <div class="in-box">
                                <h5>Card Number*</h5> <input class="box card-number" size='20' type='text' required>
                            </div>
                            <div class="in-box">
                                <h5>CVC*</h5> <input class="box card-cvc" type='text' required>
                            </div>
                            <div class="in-box">
                                <h5>Expiration Month*</h5> <input class="box card-expiry-month" type='text' required>
                            </div>
                            <div class="in-box">
                                <h5>Expiration Year*</h5> <input class="box card-expiry-year" type='text' required>
                            </div>
                        </div>
                    </div>
                    <!-- <button class="log-btn" type="submit">Submit</button> -->
                    <button class="log-btn" type="submit">Pay Now</button>
                </form><!-- form end here -->
            </div><!-- col end here -->
			<div class="col-md-1"></div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-12">
					
						<h2>Billing Plan</h2>
						<div class="slected_plan">
						<?php if(isset($_GET['plan'])){
							$plan_id = $_GET['plan'];
							$plan_infos =App\Models\plan::where('status', '=', 1)->where('id',$plan_id)->get();
							foreach($plan_infos as $plan_info){?>
							 <h5><input type="radio" checked="true">&nbsp;<?php echo $plan_info->price;?>/<?php echo $plan_info->name;?></h5>
							 <ul>
							  <li>Keep all your clients in one easy-to-use app</li>
								<li>Track progress with checklists, journaling, private notes, & more</li>
								<li>Keep your schedule & get paid within the app</li>
								<li>Save <?php echo $plan_info->save_amount;?>/<?php echo $plan_info->name;?></li>
							 </ul>
							<?php }
						} ?>
						</div>
					</div>
				</div>
			</div>
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- sign-sec end here -->

@endsection
@section('script')
<script type="text/javascript">
    $(function() {
        var $form = $(".require-validation");
        $('form.require-validation').bind('submit', function(e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'
                ].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('hide');
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
            });
            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey("{{ config('app.stripe_publish_key') }}");
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
            }
        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                // $('.error')
                //     .removeClass('hide')
                //     .find('.alert')
                //     .text(response.error.message);
                alert(response.error.message);
            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    });
</script>

@stop