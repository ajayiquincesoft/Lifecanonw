@extends('user.layout.app')

@section('content')
<style>
.spinner {
  display: inline-block;
  position: relative;
	width: 50%;
}

.spinner::before {
	content: '';
	box-sizing: border-box;
	position: absolute;
	/* top: 0; */
	left: 0;
	width: 20px;
	height: 20px;
	border-radius: 50%;
	border: 2px solid #b5afaf;
	border-top-color: #ce1b1b;
	animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}


</style>
<div class="sign-sec new-clt">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a class="back-btn" href="{{ url()->previous() }}"><i class="fa fa-chevron-left"></i> Back</a>
                <h2>Add New Client</h2>
            </div><!-- col end here -->

            <div class="col-xl-3 col-lg-2 col-md-1"></div>
            <div class="col-xl-6 col-lg-8 col-md-10">
                <p>Add a Client for a monthly fee of $8.00 to start working with them in Life Canon. You will be charged for all clients on the first of every month.</p>
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
                <form action="{{route('coach.addclient')}}" method="post" data-cc-on-file="false" class="require-validation" id="add-client-form" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="client_form">
                        <div class="in-box">
                            <h5>Client Name</h5> <input class="box" type="text" name="client_name" placeholder="" required />
                        </div>
                        <div class="in-box">
                            <h5>Contact Email</h5> <input class="box" type="email" name="client_email" placeholder="" required />
                        </div>
                        <div class="in-box">
                            <h5>Phone Number</h5> <input class="box" type="text" name="phone" placeholder="" required />
                        </div>
                        <div class="in-box">
                            <h5>How will your client pay?</h5>
                            <select class="box" id="plan_name" autocomplete="off" name="plan_name" required>
                                <option value="">Select an Option</option>
                                <!--<option value="Monthly">Monthly</option>-->
                                <option value="Weekly">Weekly</option>
                                <option value="All At Once">All at once</option>
                            </select>
                        </div>
                        <div class="in-box" style="display: none;" id="cycle">
                            <h5>How many sessions will your client require?</h5> <input class="box" type="number" name="cycle" placeholder="" id="no_of_cycle" min="1" />
                        </div>


                        <div class="in-box">
                            <h5>Input either Total Contract or Weekly Charge?</h5> <input class="box" type="text" name="plan_amount" placeholder="" id="plan_amount" required />
                        </div>
                        <div class="in-box">
                            <h5>$ Per Additional Appointment Fee</h5> <input class="box" type="text" name="appointment_fee" placeholder="" required />
                        </div>

                        <hr>
                        <h6>Total Contract Amount : <span id="total_contract_amount">$0</span></h6>
                        <h6>Your Client will Pay : <span id="payble_amount">$0</span></h6>
                    </div>
                    <div class="card_form" style="display: none;">
                        <div class="in-box">
                            <h5>Card Number</h5> <input class="box card-number" type="number" name="card_number" placeholder="" required/>
                        </div>
                        <div class="in-box">
                            <h5>Expiry Month</h5> <input class="box card-expiry-month" type="number" name="exp_month" placeholder="" min="1" max="12" required/>
                        </div>
                        <div class="in-box">
                            <h5>Expiry Year</h5> <input class="box card-expiry-year" type="number" name="exp_year" placeholder="" min="2022" required />
                        </div>
                        <div class="in-box">
                            <h5>CVV</h5> <input class="box card-cvc" type="text" name="cvv" placeholder="" required/>
                        </div>
                    </div>
					
					<br>
					<div style="text-align:left;color:#721c24 ;font-weight: 600;">Note: Once submitted this contract can not be edited.</div>
					<br>
                    <hr>
                    <a href="#" class="log-btn" id="pay">Pay $8/Month to add this client</a>
                    <button class="log-btn" type="submit" value="submit" id="submit_button" style="display: none;">$8/Month to add this client</button>
                </form><!-- form end here -->
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- sign-sec end here -->


@endsection

@section('script')
<script type="text/javascript">
	/* $('#submit_button').on('click',function(){
		var $btn = $(this);
		// Add spinner class to the button
		$btn.addClass('spinner');
		
	}); */
    $('#plan_name,#no_of_cycle,#plan_amount').on("change", function() {
        var val = $('#plan_name').val();
        if (val != 'All At Once' && val != '') {
            $('#cycle').show();
            var amount = ($('#no_of_cycle').val() * $('#plan_amount').val()) ? $('#no_of_cycle').val() * $('#plan_amount').val() : 0;
            $('#total_contract_amount').text('$' + amount);
            $('#payble_amount').text('$' + $('#plan_amount').val() + '/' + val);
        } else {
            $('#cycle').hide();
            var amount = ($('#plan_amount').val()) ? $('#plan_amount').val() : 0;
            $('#total_contract_amount').text('$' + amount);
            $('#payble_amount').text('$' + $('#plan_amount').val());
        }
    });
    $("#pay").on('click', function() {

        if ($('input[name="client_name"]').val() != '' && $('input[name="client_email"]').val() != '' && $('input[name="phone"]').val() != '') {

            $('.client_form').hide();
            $('.card_form').show();
            $('#pay').hide();
            $('#submit_button').show();

        } else {
            alert('Please fill the form field');
        }
    });
</script>

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