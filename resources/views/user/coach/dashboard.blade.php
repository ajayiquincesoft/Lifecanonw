@extends('user.layout.app')

@section('content')
<style>
.pending_class p{color: #006700;}
.client_code p {
	text-align: center;
	padding: 8px;
	font-size: 20px;
	font-weight: 600;
	color: #006700;
	border: 1px solid #006700;
	border-radius: 10px;
}
.pending_client_contact a::after{content:none;}
.clt_detail a::after{content:none;}
.clt_detail a{display: initial;text-align: center;}
.clt_detail a:hover{color:#000;}
</style>
<div class="dash-sec">
    <div class="container">
	<div class="row">
			<div class="col-lg-12 col-md-12">
				<a class="back-btn" href="/" style="position: relative;"><i class="fa fa-chevron-left" style=""></i> Home</a>
			</div>
		</div>
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
                @include('user.coach.dashboardleftsidebar')

            </div><!-- col end here -->

            <div class="col-lg-8 col-md-12">
                <div class="dash-coach">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<?php if (empty(auth()->user()->timezone)){?>
								<p style="color:red">Please update your time zone from the View profile!.</p>
						     <?php } ?>
						</div>
					</div>
                    <h2>My Clients</h2>
					<?php if($stripekeys->secret_key){?>
						<a class="coach-btn"  href="{{route('coach.addclient')}}">Add Client</a>
					<?php }else{?>
						<a class="coach-btn"  onclick='stripepopup()'>Add Client</a>
					<?php  } ?>
                    <div class="clt-dt">

                        <div class="in-box">
                            <form method="get" action="{{route('user.dashboard')}}"><input class="box" type="text" name="s" placeholder="Search Clients"></form>
                        </div>
						
                        <ul>
						<?php 
						
						$i=1;
						//print_r($addedclients);
						foreach($addedclients as $addedclient){ ?>	
						<?php if(@$addedclient->status==0){ ?>
							<?php if($i==1){?><h4>Pending Clients</h4><?php } ?>
							<li>
							 <a data-toggle="modal" data-target="#myModal{{$addedclient->id}}" style="cursor:pointer;"><img src="{{asset('/profile.png')}}">
                                    <p>{{$addedclient->client_name}}</p>
                                </a>
							</li>
							<div id="myModal{{$addedclient->id}}" class="modal fade" role="dialog">
							  <div class="modal-dialog">
								<!-- Modal content-->
								<div class="modal-content">
								  <div class="modal-header">
									
									<h4 style="text-align: center;width: 100%;">Confirmation</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								  </div>
								  <div class="modal-body pending_client_contact">
									<div class="row">
										<div class="col-md-12">
											<p class="mb-4">Please give your client this code to start working with them in life Canon.</p>
											<div class="client_code"><p>{{$addedclient->code}}</p></div>
										</div>
										<div class="col-md-8">
										<p>Amount To Paid By client:</p>
										<p>Total Sessions:</p>
										<p>Payment Plan:</p>
										<p>Each Payment:</p>
										<p>Total Cost :</p>
										</div>
										<div class="col-md-4 pending_class">
											<p>${{$addedclient->plan_amount}}/{{$addedclient->plan_name}}</p>
											<p>{{$addedclient->cycle}}</p>
											<p>{{$addedclient->plan_name}}</p>
											<p>${{$addedclient->plan_amount}}</p>
											<p>${{$addedclient->plan_amount * $addedclient->cycle}}</p>
										</div>
											<div class="col-md-12">
												<h4 class="modal-header" style="text-align: center;width: 100%;">Contact Client</h4>
											</div>
											<hr>
											
											<div class="col-md-12" style="display: ruby;">
												<i class="fa fa-phone"></i><a href="tel:{{$addedclient->phone}}">{{$addedclient->phone}}</a><br>
												
											</div>
											
											<div class="col-md-12" style="display: ruby;"><i class="fa fa-envelope"></i><a href="mailto:{{$addedclient->client_email}}">{{$addedclient->client_email}}</a></div>
											
										
									</div>
								  </div>
								  <div class="modal-footer clt_detail">
									<a href="{{route('coach.deleteaddedclient',['id'=>$addedclient->id])}}" class="del-btn" onclick='return confirm("Are you sure you want to delete this client? ")'>Delete</a>
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								  </div>
								</div>

							  </div>
							</div>
							
                        <?php $i++; }  } ?>   
                        </ul><!-- ul end here -->
						<hr>
						
						 <h4 style="color:#006700;">My Active Clients</h4>
                        <ul>
						<?php foreach($addedclients as $addedclient){ ?>	
							<?php if(@$addedclient->status==1){ ?>
							 <li>
							
							 <a href="{{route('coach.viewaddedclient',['client_id'=>$addedclient->id])}}"><img src="{{asset('/')}}{{$addedclient->client->profile_pic}}">
                                    <p>{{$addedclient->client->name}}</p>
                                </a>
							</li>
                        <?php } } ?>   
                        </ul><!-- ul end here -->
						<hr>
						<h4 style="color: #d00f0f;">My Archive Clients</h4>
                        <ul>
						<?php foreach(@$addedclients as $addedclient){ ?>	
							<?php if(@$addedclient->status ==2){ ?>
							 <li>
							
							 <a href="{{route('coach.viewaddedclient',['client_id'=>$addedclient->id])}}">
							 <?php if(@$addedclient->client->profile_pic){ ?>
									<img src="{{asset('/')}}{{@$addedclient->client->profile_pic}}">
							 <?php } ?>
                                    <p>{{@$addedclient->client->name}}</p>
                                </a>
							</li>
								
						
                        <?php } } ?>   
                        </ul><!-- ul end here -->
                    </div><!-- clt-dt end here -->
                </div><!-- dash-coach end here -->
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
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

<div class="modal fade coach-popup" id="reschedulaappointmentModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <h2>Add Appointment</h2>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="modal-body" style="padding:20px;">
                <form autocomplete="off" class="require-validation" action="{{route('reschedule_appointment')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
                    @csrf

                    <div class="alert alert-danger" style="display:none">
                        
                    </div>
                
                    <div class="alert alert-success" style="display:none">
                        
                    </div>

                    <div id="appintmentdetails">
                        <div class="row">
                            <div class="col-md-3" style="text-align:left; padding:5px;">
                               <label>Choose a Weekday</label>
                            </div>
                           
                            <div class="col-md-9" style="padding: 0px;">
                                <ul class="donate-now">
                                    <li>
                                        <input type="radio" id="html" name="app_days" value="Monday" onclick="gettime();"/>
                                        <label for="html">Monday</label>
                                    </li>

                                    <li>
                                        <input type="radio" id="html1" name="app_days" value="Tuesday" onclick="gettime();"/>
                                        <label for="html1">Tuesday</label>
                                    </li>

                                    <li>
                                        <input type="radio" id="html2" name="app_days" value="Wednesday" onclick="gettime();"/>
                                        <label for="html2">Wednesday</label>
                                    </li>

                                    <li>
                                        <input type="radio" id="html3" name="app_days" value="Thursday" onclick="gettime();"/>
                                        <label for="html3">Thursday</label>
                                    </li>

                                    <li>
                                        <input type="radio" id="html4" name="app_days" value="Friday" onclick="gettime();"/>
                                        <label for="html4">Friday</label>
                                    </li>

                                    <li>
                                        <input type="radio" id="html5" name="app_days" value="Saturday" onclick="gettime();"/>
                                        <label for="html5">Saturday</label>
                                    </li>

                                    <li>
                                        <input type="radio" id="html6" name="app_days" value="Sunday" onclick="gettime();"/>
                                        <label for="html6">Sunday</label>
                                    </li> 
                                </ul>
                            </div>
                        </div>
                          
                        <div class="row">
                           <div class="col-md-3" style="text-align:left; padding:5px;">
                              <div></div>Choose From Available Time</label>
                           </div>

                           <div class="col-md-9" style="padding: 0px;">
                                <ul class="donate-now1" id="displayapp_time">
                                    <p style="color:red">No Available Times</p>
                                </ul>
                           </div>
                        </div>

                        <!-- <div class="row">
                           <div class="col-md-3" style="text-align:left; padding:5px;">
                              <label>Whould you like to repeat ?</label>
                           </div>
                           <div class="col-md-9" style="padding: 0px;">
                              <select id="repeat" name="repeat" class="form-control mb-3" onchange="getrepeat();">
                                 <option value="">Select an option</option>
                                 <option value="0">Never</option>
                                 <option value="1">Weekly</option>
                              </select>
                           </div>
                        </div>

                        <div class="row" id="repeatdiv" style="display:none;">
                           <div class="col-md-3">
                              <label>End Date</label>
                           </div>

                           <div class="col-md-9" style="padding: 0px;">
                                
                              <input type="text" class="form-control mb-3" id="datetim3" name="end_date" placeholder="Enter the end date" />
                           </div>
                        </div> -->
                        
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="user_id" id="user_id" value="">
                                <input type="hidden" name="appointment_id" id="appointment_id" value="">
                                <button class="log-btn" type="submit">Save</button>
                            </div>
                        </div> 
                    </div>
                </form>
            </div><!-- modal-body end here -->
        </div><!-- modal-content end here -->
    </div><!-- modal-dialog end here -->
</div><!-- modal end here -->
<!--------------------------stripe key check ------------------------>
<div id="stripekeycheck" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p> In order to add your first client you will need to set up your Stripe Account first.
		  @if(Auth::user()->user_type==1)<strong><a href="{{route('coach.getaddorupdatestripe')}}">Click Here</a></strong>@endif
		</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!------------------------end of code--------------->


@endsection
@section('script')
<script>
function stripepopup(){
	$('#stripekeycheck').modal('show');
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
        let container = $("#coachapp").simpleCalendar({
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
                $('#coachapp table tr td div').removeClass("today");

                $("#coachapp #"+text).addClass("today");


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
                    url: "{{route('coachappointment.date')}}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        'appointment_date': newDate,
                    },
                    success: function(res) 
                    {
                        //alert(data);
                        console.log(res.data.length);

                        $("#showdlt2").html('');

                        if(res.data.length > 0)
                        {
                            $("#showdlt2").append(newDate2);
                        }

                        for(var i = 0; i < res.data.length; i++)
                        {
                            var profile = res.data[i].profile;
                            var client_name = res.data[i].client_name;
                            var date = res.data[i].date;
                            var time = res.data[i].time;
                            var appoint_id = res.data[i].appoint_id;

                            $("#showdlt2").append('<li data-toggle="modal" data-target="#appointmentdetails" onclick="appointmentdetails(\'' + appoint_id + '\',\'' + newDate + '\');"><a style="cursor:pointer;"><img src="{{asset("/")}}'+ profile +'"><p>'+client_name+'<span>'+time+'</span></p></a></li>');
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
        var reappoint = '<button class="new-btn" type="button" onclick="reappointment();">Reschedule Appointment</button>';

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

                var client_name = res.data.client_name;
                var profile = res.data.client_profile;
                var appointment_fees = res.data.appointment_fee;
                var appointment_time = res.data.appointment_time;
                var get_appoint_date = res.data.get_appoint_date;
                var ap_amount = res.data.appointment_amount; 
                var payment_date = res.data.payment_date;
                var client_id = res.data.client_id;
                var appointment_id = res.data.appointment_id;
				var schedule_by	= res.data.schedule_by;
                $("#user_id").val(client_id);
                $("#appointment_id").val(appointment_id);
                if(schedule_by=='freeByCoach'){
                $("#getappdet").html('<table><tr><td><img src="{{asset("/")}}'+ profile +'" style="border-radius:50%; height:44px; width:44px;"></td><td><p style="color:#023a02; font-family:SF-Pro-Medium; font-size:14px; margin:0 0 0 28px; text-align:left; font-weight: 500;">'+client_name+'<br>$ '+appointment_fees+' / apt</p></td></tr></table><hr>'+upcomming+'<span style="float:left">'+get_appoint_date+'</span><br><span style="float:left">'+appointment_time+'</span><br><br><span style="float:left">Appointment with  '+client_name+'</span><br><br><div style="border-top:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding-top:10px; padding-bottom:33px;"><span style="float:left; font-size:18px; font-weight:600;">Total</span><span style="float:right; font-size:18px; font-weight:600; color:#024602;">$ '+ap_amount+'</span></div><br>'+reappoint+'');
				}else{
					$("#getappdet").html('<table><tr><td><img src="{{asset("/")}}'+ profile +'" style="border-radius:50%; height:44px; width:44px;"></td><td><p style="color:#023a02; font-family:SF-Pro-Medium; font-size:14px; margin:0 0 0 28px; text-align:left; font-weight: 500;">'+client_name+'<br>$ '+appointment_fees+' / apt</p></td></tr></table><hr>'+upcomming+'<span style="float:left">'+get_appoint_date+'</span><br><span style="float:left">'+appointment_time+'</span><br><br><span style="float:left">Payment will be made on '+payment_date+'</span><br><br><div style="border-top:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding-top:10px; padding-bottom:33px;"><span style="float:left; font-size:18px; font-weight:600;">Total</span><span style="float:right; font-size:18px; font-weight:600; color:#024602;">$ '+ap_amount+'</span></div><br>'+reappoint+'');
				}
            }
        });
    }

    function reappointment()
    {
        $("#appointmentdetails").modal('hide');
        $("#reschedulaappointmentModal").modal('show');
    }

    function gettime() 
    {
        var app_days = $("input[name='app_days']:checked").val();
        var user_id = $("#user_id").val();

        $("#displayapp_time").html('<img src="{{asset("/")}}loading2.gif" style="width:55px;">');
    
        $.ajax(
        {
            type: "POST",
            url: "{{route('get_availabletime')}}",
            data: {
                _token: "{{ csrf_token() }}",
                'app_days': app_days,
                'user_id' : user_id,
            },
            success: function(res)
            {   
                $("#displayapp_time").html('');

                if(res.data.length > 0)
                {
                    for(var i = 0; i < res.data.length; i++)
                    {
                        $("#displayapp_time").append('<li><input type="radio" id="html1'+i+'" name="app_time" value="'+res.data[i].gettime+'"><label for="html1'+i+'">'+res.data[i].gettime+'</label></li>');
                    }  
                }
                else
                {
                    $("#displayapp_time").append('<p style="color:red">No Available Times</p>');
                }
            }
        });
    }

    function getrepeat()
    {
      var repeatval = $("#repeat").val();

      if(repeatval == "" || repeatval == "0")
      {
         $("#repeatdiv").hide();
      }
      else
      {
         $("#repeatdiv").show();
      }
    }
</script>
@stop