@extends('user.layout.app')

@section('content')
<div class="dash-sec">
    <div class="container">
		<div class="row">
			<div class="col-md-12">
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
			</div>
		</div>
        <div class="row">
            <div class="col-lg-4 col-md-12">
                @include('user.dashboardleftsidebar')
                <!-- <div class="dash-sch">
                    <h2>My Schedule </h2>
                    <div id="container-1" class="calendar-container"></div>
                    <ul>
                        <li><a href="#"><img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                                <p>John Cena <span>3:00 PM - 4:00 PM</span></p>
                            </a></li>
                        <li><a href="#"><img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                                <p>John Cena <span>3:00 PM - 4:00 PM</span></p>
                            </a></li>
                        <li><a href="#"><img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                                <p>John Cena <span>3:00 PM - 4:00 PM</span></p>
                            </a></li>
                        <li><a href="#"><img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                                <p>John Cena <span>3:00 PM - 4:00 PM</span></p>
                            </a></li>
                    </ul>
                </div> -->
            </div><!-- col end here -->

            <div class="col-lg-8 col-md-12">
                <div class="dash-coach">
                    <h2>My Coaches</h2>
                    <button data-toggle="modal" data-target="#myModal" class="coach-btn" type="button">Add Coach</button>
                    <div class="clt-dt">

                        <div class="in-box">
                            <!-- <form method="get" action="{{route('user.dashboard')}}"><input class="box" type="text" name="s" placeholder="Search Clients"></form> -->
                        </div>
                        <br />
                        <ul>
                            @foreach($coachdata as $coach)
                            <li><a href="{{route('client.viewcoach',['coach_id'=>$coach->user_id])}}"><img src="{{url($coach->user->profile_pic)}}">
                                    <p>{{$coach->user->name}}</p>
                                </a></li>
                            @endforeach
                        </ul><!-- ul end here -->
                    </div><!-- clt-dt end here -->
                    <!-- <ul class="coach-pro">

                        @foreach($coachdata as $coach)
                        <li class="active"><img src="{{url($coach->user->profile_pic)}}"> <span>{{$coach->user->name}}</span></li>
                        @endforeach
                    </ul> -->
                </div><!-- dash-coach end here -->
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
    <div class="modal fade coach-popup" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Add Your Coach's Code</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
                    <div class="coach-cd">
                        <div class="in-box"><input class="box code" type="number" name="code" placeholder=""></div>
                        <a class="log-btn" href="#" onclick="addCach()">Submit</a>
                    </div><!-- coach-cd end here -->

                    <div class="coach-dt" style="display: none;">
                        <img class="profile_pic" src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                        <div class="coac-txt">
                            <h5 class="coach_name">John Cena</h5> <span>Coach Code: <em class="coach_code">1376</em></span>
                            <a class="log-btn" href="#" onclick="display_price()">Continue</a>
                        </div><!-- coac-txt end here -->
                    </div><!-- coach-dt end here -->

                    <div class="coach-dt-1" style="display: none;">
                        <img class="profile_pic" src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                        <div class="coac-txt">
                            <h5 class="coach_name">John Cena</h5>
                            <p>Your Coach has requested you to pay for the full course up front <span class="coach_price">$2,000</span></p>
                            <a class="log-btn payment_url" href="#">Continue</a>
                        </div><!-- coac-txt end here -->
                    </div><!-- coach-dt-1 end here -->
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->

</div><!-- dash-sec end here -->

<div class="modal fade coach-popup" id="appointmentdetails">
    <div class="modal-dialog">
        <div class="modal-content">
            <h2>Appointment Details</h2>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-body" style="padding:20px;">
               <div id="getappdet">
                    
               </div>
            </div><!-- modal-body end here -->
        </div><!-- modal-content end here -->
    </div><!-- modal-dialog end here -->
</div>


@endsection

@section('script')
<script>
    function addCach() {
        var code = $('.code').val();
        if (!code) {
            alert('code value required');
            return false;
        }
        $.ajax({
            type: "POST",
            url: "{{route('client.addcoach')}}",
            data: {
                _token: "{{ csrf_token() }}",
                code: code
            },
            success: function(response) {
                if (response.success) {
                    $('.coach-cd').hide();
                    $('.coach-dt').show();
                    $('.profile_pic').attr('src', "{{url('/')}}/" + response.data.user.profile_pic);
                    $('.coach_name').text(response.data.user.name);
                    $('.coach_code').text(response.data.code);
                    $('.coach_price').text("$" + response.data.plan_amount + "/" + response.data.plan_name);
                    $('.payment_url').attr('href', "{{route('pay')}}?payment_for=3&code=" + response.data.code);
                } else {
                    alert('Code is not validat.Please contact to Administrator.');
                }

            }
        });
    }

    function display_price() {
        $('.coach-dt').hide();
        $('.coach-dt-1').show();
    }
</script>
@if (session('plan_selected'))
<script>
    swal({
            text: "Payment has been success.",
            icon: "success",
        })
        .then((willDelete) => {
            //
        });
</script>
@endif

<script>
    $(document).ready(function() 
    {
        let container = $("#clientapp").simpleCalendar({
            fixedStartDay: 0,
            disableEmptyDetails: true,
            events: 
                @php 
                    echo $totalapp;
                @endphp
            ,
            onDateSelect: function (date) 
            {
                var date = new Date(date);

                var dd = date.getDate();
                var mm = date.getMonth() + 1;
                var yyyy = date.getFullYear();
                var fullmonth = date.toLocaleString('default', { month: 'long' });

                var text = "a"+dd+""+date.getMonth()+"a";

                //console.log(text);
                $('#clientapp table tr td div').removeClass("today");

                $("#clientapp #"+text).addClass("today");

                if(mm < 10)
                {
                    mm = '0'+mm;
                }

                if(dd < 10)
                {
                    dd = '0'+dd;
                }

                newDate = yyyy + '-' + mm + '-' + dd;
                newDate2 = fullmonth + ' ' + dd + ', ' + yyyy;
                //alert(newDate);

                $.ajax(
                {
                    type: "POST",
                    url: "{{route('clientappointment.date')}}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        'appointment_date': newDate,
                    },
                    success: function(res) 
                    {
                        //alert(data);
                        //$("#clientappointment").html(data);

                        console.log(res.data.length);

                        $("#clientappointment").html('');

                        if(res.data.length > 0)
                        {
                            $("#clientappointment").append(newDate2);
                        }

                        for(var i = 0; i < res.data.length; i++)
                        {
                            var profile = res.data[i].profile;
                            var client_name = res.data[i].client_name;
                            var date = res.data[i].date;
                            var time = res.data[i].time;
                            var appoint_id = res.data[i].appoint_id;

                            $("#clientappointment").append('<li data-toggle="modal" data-target="#appointmentdetails" onclick="appointmentdetails(\'' + appoint_id + '\',\'' + newDate + '\');"><a style="cursor:pointer;"><img src="{{asset("/")}}'+ profile +'"><p>'+client_name+'<span>'+time+'</span></p></a></li>');
                        }
                    }
                });
            }
        });
    });

    function appointmentdetails(appoint_id,appoint_date)
    {
        var date = new Date();

        var dd = date.getDate();
        var mm = date.getMonth() + 1;
        var yyyy = date.getFullYear();
        if(mm < 10)
        {
            mm = '0'+mm;
        }

        if(dd < 10)
        {
            dd = '0'+dd;
        }


        var newDate = yyyy + '-' + mm + '-' + dd;

        var upcomming = "";
        //var reappoint = '<button class="new-btn" type="button" onclick="reappointment();">Reschedule Appointment</button>';

        var reappoint = "";

        //alert(newDate+'>'+ appoint_date);
        if(newDate < appoint_date)
        {
            upcomming = '<p style="float:left; color:#014e01; font-size:16px; font-weight:600;">Upcommig Appointment</p><br><br>';

            reappoint = "";
        }

        $.ajax(
        {
            type: "POST",
            url: "{{route('getappointmentdetails')}}",
            data: {
                _token: "{{ csrf_token() }}",
                'appoint_id': appoint_id,
                'appoint_date' : appoint_date,
            },
            success: function(res)
            {   
                //alert(res.data.client.id);

                var client_name = res.data.coach_name;
                var profile = res.data.coach_profile;
                var appointment_fees = res.data.appointment_fee;
                var appointment_time = res.data.appointment_time;
                var get_appoint_date = res.data.get_appoint_date;
                var ap_amount = res.data.appointment_amount; 
                var payment_date = res.data.payment_date;
                var coach_id = res.data.coach_id;
				var schedule_by	= res.data.schedule_by;
                $("#user_id").val(coach_id);
                if(schedule_by=='freeByCoach'){
					$("#getappdet").html('<table><tr><td><img src="{{asset("/")}}'+ profile +'" style="border-radius:50%; height:44px; width:44px;"></td><td><p style="color:#023a02; font-family:SF-Pro-Medium; font-size:14px; margin:0 0 0 28px; text-align:left; font-weight: 500;">'+client_name+'<br>$ '+appointment_fees+' / apt</p></td></tr></table><hr>'+upcomming+'<span style="float:left">'+get_appoint_date+'</span><br><span style="float:left">'+appointment_time+'</span><br><br><span style="float:left">Appointment with  '+client_name+'</span><br><br><div style="border-top:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding-top:10px; padding-bottom:33px;"><span style="float:left; font-size:18px; font-weight:600;">Total</span><span style="float:right; font-size:18px; font-weight:600; color:#024602;">$ '+ap_amount+'</span></div><br>'+reappoint+'');
				}else{
					$("#getappdet").html('<table><tr><td><img src="{{asset("/")}}'+ profile +'" style="border-radius:50%; height:44px; width:44px;"></td><td><p style="color:#023a02; font-family:SF-Pro-Medium; font-size:14px; margin:0 0 0 28px; text-align:left; font-weight: 500;">'+client_name+'<br>$ '+appointment_fees+' / apt</p></td></tr></table><hr>'+upcomming+'<span style="float:left">'+get_appoint_date+'</span><br><span style="float:left">'+appointment_time+'</span><br><br><span style="float:left">Payment will be made on '+payment_date+'</span><br><br><div style="border-top:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding-top:10px; padding-bottom:33px;"><span style="float:left; font-size:18px; font-weight:600;">Total</span><span style="float:right; font-size:18px; font-weight:600; color:#024602;">$ '+ap_amount+'</span></div><br>'+reappoint+'');
					
				}
            }
        });
    }
</script>
@stop