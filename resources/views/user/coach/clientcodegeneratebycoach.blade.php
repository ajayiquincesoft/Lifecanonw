@extends('user.layout.app')

@section('content')
<div class="sign-sec new-clt">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a class="back-btn" href="{{route('user.dashboard')}}"><i class="fa fa-chevron-left"></i> Back</a>
                <h2>Confirmation</h2>
            </div><!-- col end here -->

            <div class="col-xl-3 col-lg-2 col-md-1"></div>
            <div class="col-xl-6 col-lg-8 col-md-10">
                <p>Add a Client for a monthly fee of $8.00 to start working with them in Life Canon. You will be charged for all clients on the first of every month.</p>


                <div class="cr-code">
                    <h4><?php echo  Session::get('code'); ?></h4>
                </div>

                <hr>
                <h6>Amount Paid by Coach : <span>$<?php echo  Session::get('coachamountpaid'); ?></span></h6>
                <h6>Amount to be Paid by the Client : <span>$<?php echo  Session::get('clientamountpaid'); ?></span></h6>
                <hr>
                <a class="log-btn" href="{{route('user.dashboard')}}">Go Home</a>

            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- sign-sec end here -->



@endsection
@section('script')
<!-- <script type="text/javascript">
    function confirmWinClose(e) {
        e.preventDefault();
        var confirmClose = confirm('Close?');
        return confirmClose;
    }
    window.unload = confirmWinClose();
</script> -->
@stop