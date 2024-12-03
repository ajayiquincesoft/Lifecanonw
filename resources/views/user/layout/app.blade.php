<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Life Canon</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('public/assets/front-assets/style.css')}}" rel="stylesheet">
	<link href="{{asset('public/assets/front-assets/chatmessage.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/front-assets/simple-calendar.css')}}" rel="stylesheet">
    <link href="{{asset('public/assets/front-assets/custom.css')}}" rel="stylesheet">
	<link href="{{asset('public/assets/front-assets/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css">
    <!--<link rel="icon" href="{{asset('public/assets/front-assets/images/fav.png')}}" />-->
	<link rel="icon" href="{{asset('public/assets/front-assets/images/new-lifecanon-logo1.png')}}" width="32" height="32"/>

    <!-- small header js start here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/classie/1.0.1/classie.js"></script>
    <script>
        function init() {
            window.addEventListener('scroll', function(e) {
                var distanceY = window.pageYOffset || document.documentElement.scrollTop,
                    shrinkOn = 100,
                    header = document.querySelector("header");
                if (distanceY > shrinkOn) {
                    classie.add(header, "smaller");
                } else {
                    if (classie.has(header, "smaller")) {
                        classie.remove(header, "smaller");
                    }
                }
            });
        }
        window.onload = init();
    </script>
    <!-- small header js start here -->
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-68VYGFQ7WL"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'G-68VYGFQ7WL');
	</script>
</head>


<body>

    <header class="dash-head">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4"><a href="{{route('user.dashboard')}}">
				<img class="logo" src="{{asset('public/assets/front-assets/images/LC-Canon-Logo-01-inner.png')}}" width="40">
				<!--<img class="logo" src="{{asset('public/assets/front-assets/images/LC-Canon-inner-logo.png')}}" width="40">-->
				 <span class="logo-1"><img src="{{asset('public/assets/front-assets/images/dash-logo1.png')}}" style="margin-top: 5px;"> </span></a></div>
                <div class="col-lg-9 col-md-8">
                    <ul class="dash-nav">
                        <li class="cal-box">
                            <a onclick="showHideCalendar(this);"><i class="fa fa-calendar"></i></a>
                            <div id="container" class="calendar-container dropdown-menu" style="display:none;"></div>
                        </li><!-- li end here -->
						@if(Auth::user()->user_type==1)
							<li class="first_paid_session"><a href="#" onclick="firstpaidsession()">First Paid Session</a></li>
						@endif
                        <li class="log-box note-box">
                            @php
                                $user = Auth::user();
                                if ($user) 
                                {
                                    $notification = $user->notifications()->whereStatus(1)->orderBy('id','DESC')->limit(100)->get(); 
                                }
                            @endphp

                            <a href="#" data-toggle="dropdown">
                                <!-- <i class="fa fa-bell"></i> -->
                                @php
                                    if(count($notification) > 0)
                                    {
                                @endphp
                                        <img src="{{asset('public/assets/front-assets/images/bell_animated3.gif')}}" width="25">
                                @php
                                    }
                                    else
                                    {
                                @endphp
                                        <img src="{{asset('public/assets/front-assets/images/bell.png')}}" width="18">
                                @php        
                                    }
                                @endphp
                                
                            </a>
                            <ul class="dropdown-menu">
                                <center><h5>Notifications</h5></center>
								
								@foreach($notification as $noti)
									@php
										
									   $currentdate = date('Y-m-d h:i:s');
										$date1 = new DateTime($noti->created_at);
										$date2 = new DateTime($currentdate);
										$days  = $date2->diff($date1)->format('%a');
										$adddclients = \App\Models\AddClient::where('user_id', $user->id)->where('status', '=',1)->get();
                                        $client_id = 0;
										foreach($adddclients as $client){
											 $client_id = $client->id;
										}
							
									@endphp
									<li>
										<a href="{{route('user.dashboard')}}" onclick="readnotification({{$noti->id}})">
												<p>{{ $noti->title }} </p>
                                                <div> <div style="color: #006700; font-size: 14px; font-family: SF-Pro-Medium;">{{ $noti->body }} </div>{{ $days }} Days Ago</div>
										 </a>
									</li>
								@endforeach
								
                                <!-- <li><a href="{{route('coach.unreadnotifications')}}" class="allnotification">View All Notifications</a></li> -->
                            </ul><!-- ul end here -->
                        </li><!-- li end here -->
                        <li class="log-box">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
							@php
							if(Auth::user()->profile_pic){
							@endphp
							<img src="{{asset('')}}/{{Auth::user()->profile_pic}}">
							@php
							}else{ @endphp
							<img src="{{asset('/profile.png')}}">
							@php
							 }
							@endphp
							</a>
                            <ul class="dropdown-menu">
							
                                <li><a href="{{route('user.viewprofile')}}"><span><img src="{{asset('')}}/{{Auth::user()->profile_pic}}"></span>
                                        <h6>{{Auth::user()->name}} <strong>My Profile</strong></h6>
                                    </a>
                                </li>
								<li><h4>Account Setting</h4></li>
								<li><a href="{{route('user.viewprofile')}}">My Profile</a></li>
								<li><a href="{{route('user.resetpassword')}}">Change Password</a></li>
								
                                @if(Auth::user()->user_type==1)
                                <li><a href="{{route('coach.getaddorupdatestripe')}}">Manage Stripe Account</a></li>
                                @endif
                               @if(Auth::user()->user_type==1 OR Auth::user()->user_type==2)
                                <li><a href="{{route('coach.mysubscription')}}">My Subscription</a></li>
                                @endif  
								@if(Auth::user()->user_type==1)
                                <li><a href="{{route('coach.earning')}}">My Earnings</a></li>
                                @endif
								@if(Auth::user()->user_type==1)
                                <li><a href="{{route('coach.getCoachAvailability')}}">My Availability</a></li>
                                @endif
								<li><h4>Life Canon Support</h4></li>
								<li><a href="{{url('how-to-use-the-app')}}"> How to use the app</a></li>
								<li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
								<li><a href="{{url('help')}}"> Help</a></li>
								@if(Auth::user()->user_type==1)
								<li><a href="{{url('stripe-setup')}}"> Stripe Setup</a></li>
								@endif
								<li><a href="{{url('terms-conditions')}}">Term & Conditions</a></li>
								<li><a href="{{url('disclaimer')}}">Disclaimer</a></li>
								@if(Auth::user())
								<li><a href="{{route('coach.Getfeedback')}}">Feedback</a></li>
								@endif
								 <li><a href="{{route('user-logout')}}"><i class="fa fa-sign-out"></i> Logout</a></li>
								
                            </ul><!-- ul end here -->
							
                        </li><!-- li end here -->
						<li style="padding-left: 16px;"><a href="{{route('user-logout')}}" style="background:transparent;"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul><!-- ul end here -->
                </div><!-- col end here -->
            </div><!-- row end here -->
        </div><!-- container end here -->
    </header><!-- header end here -->
	<!---------------------message model----------------->

<div class="modal fade jour-popup" id="firstpaidsession">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			 <h2 style="padding: 22px 6px;font-size: 16px;text-align: center;">First Paid Session</h2>
			<div class="modal-body">
				<p>Day 1 &nbsp;  Are you prepared?  You must be ready for this session.  It will make or break your client/coach relationship.
				Be prepared with a little extra just in case things move along faster than you planned. I’ll give you some suggestions at the end.
				</p>
				
					<h5>STEP 1</h5><br/>
					<p>
						<strong>Q: Ask your client how they are doing today?</strong>
					</p>
					<p>
						<strong>A:</strong> Listen and respond to their answer, but keep it short.  This may give you insight as to your next step.
					</p>
					<p>
						Example: What if your client just lost a pet?  You may want to go deeper and discuss how they are dealing with this.<br>
					</p>	
               <p> You must reassure your client you care about them and their wellbeing.   Without this you have nothing.
			   </p>
				<p>
					If you are ready to start, both of you should open up the Life Canon app.  Your client should have already received their 6 digit code and filled out their credit card information.  In fact if you completed your first free session, by now you should have received your first payment for this paid session.
				</p>
				<p>
				Take a few minutes to remind them how the application works.<br/>
				<ul>
					<li><strong>Goals:</strong>  Where your main goals are noted.</li>
					<li><strong>Habit List:</strong>  Where your weekly tasks are entered along with reminders.  </li>
					
					<li><strong>Client's Journal:</strong>  Have your client use this at least once a day.  Brief notes on how they are feeling, issues they are having, and things that worked for them.</li>
					<li><strong>Coaches’ Notes:</strong>  Where you plan your next session with notes and ideas.  List of topis, materials you want to send them, or concerns to address.
						<ul>
							<li>Once your client is all set up on the app you can call them from each client’s home page.</li>
							<li>Prioritized goals down to 1 or 2 to start with, but list the rest below those.</li>
						</ul>
					</li>	
				</ul>
				Help them choose an important but easy goal to start with.  They need to see success early on for this to work.
				</p>
				
				<h5>STEP 2</h5><br/>
				<strong>The Why beneath the Why</strong>
				<p>Discuss the Goal you want to work on.  Ask:  What motivates you to have this goal?  This may seem like you are going down a rabbit hole, but trust me.</p>
				
				<p>This is what you must do to find the Why beneath the Why.  Let me give you an example I had with a client.</p>
				<p>
				Client:  I binge eat and I need to stop.<br>
				Coach:  What emotion are you feeling when you binge eat?<br>
				Client:   Frustration and lack of control.<br>
				Coach:  Where do you think this lack of control and frustration is coming from?<br>
				Client:  I’m not sure but my father doesn’t have a job, so my brother and I have to work to pay all the bills.<br>
				Coach:  Oh, that must be very difficult for you.  Why is you father not able to work?<br>
				Client:  He lost his job and hasn’t found another one.<br>
				</p>
				<p>Coach:  Now that we understand what is causing your stress we need to work on your response to this. You and your brother be able to encourage your father and even help him with his resume or promoting his media pages but in the end you still must learn to control your response to this and any other stressor in your life.</p>
				<p>
				Client:  Ok, so how do I do that?<br>
				Coach:  Great question!  We first need to brainstorm some optional responses to stressors of losing control.  Let’s begin.<br>
				</p>
				
			<p>As you can imagine, if I tried to focus on the binge eating issue without going deeper, failure would have been inevitable.  Refocusing on the root of the issue will help eliminated the bad habit driver. </p>
			<p>
			<strong>NOTE:</strong>  You have now established the original goal, the root cause of this goal and the fuel feeding this unwanted behavior.  Knowledge the root or the, Why beneath the Why, is the first step.  Now redirect feelings to action steps.  When your client starts to feel frustrated and out of control you need to replace those feelings with control and patience.  This is where the real work begins.  You see my client really only has control over her own life.  She can help her father but in the end she must learn to work on her response to stressors.  Replacing bad habits with good habits is the most effective tool to change.  A good habit should have immediate reward and never be food, so it builds a desire in your client to turn to this new good habit instead of, in this case, binge eating.  
			</p>
			<p>
				Take your time in this step to practice the habit a little and discuss how it makes them feel.  Reinforce the benefits of changing this bad habit with good habits.  
			</p>
			<p>
			<h5>Step 3:</h5><br>
			If you still have some time left in your session, now is when you bring out a few extras.  There are 2 categories you can do here.  Just Education or education with a Habit task.
			</p>
			<p>
			For example.  If your client is dealing with anxiety you can provide some information about stress on the body and all the negative things stress can do.  Give them some statistics and remind them how it relates to their current issue.  Ask them if they want to try something new this week in addition to their other habit tasks.  Then give them a relaxation technique to try before they go to bed or whenever they are feeling anxious.  Practice it with them once or twice and add it to their Habit List in the Life Canon app.  Be sure to add a reminder as well.
			</p>
			<p>
			<h5>STEP 4:</h5>  Review all their action step for the next 7 days until your meet again.  Encourage them to journal every day and check off their action items as they do them.
			</p>
			<p>
			<h5>STEP 5:</h5>  Remind them of when your next appointment is and give them lots of encouragement.
			</p>
			
			<p>
			That is it!  You just completed your first paid session.  Next week you can review their progress and discuss any issues they had.  Provide them with new habits to try and maybe continue with some of the old ones.  Habits will not be formed in just one week.  They must repeat for at least 30 days.  Keep educating your clients with new, interesting information and reward them with loads of encouragement.
			</p>
			<p>Always support their success and let them talk when you sense a deep feeling they have.  People need us to listen and encourage them more than they need our advise.</p>
			
			<p>Remember your role as a coach is to:</p>
			<p>
			<ul>
			<li>Ask questions</li>
			<li>Listen for the why beneath the why</li>
			<li>Educate</li>
			<li>Redirect bad habits with good ones</li>
			<li>Look for the wins and celebrate them together!  </li>
			</ul>
			</p>
			<p>
				If you can master that, you will be successful.
			</p>
			</div>
		</div><!-- modal-content end here -->
	</div><!-- modal-dialog end here -->
</div><!-- modal end here -->
    @yield('content')


    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="{{asset('assets/front-assets/jquery.simple-calendar.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
	 <script src="{{asset('assets/front-assets/bootstrap-datetimepicker.min.js')}}"></script>
	
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script type="text/javascript">
            $(function () {
                $('#dateti1').datetimepicker({format:'YY-MM-DD HH:mm'});
				$('#datetim2').datetimepicker({format:'YY-MM-DD HH:mm'});
                $('#datetim3').datetimepicker({format:'MM/DD/YYYY HH:mm'});
				$('#addappdatetim4').datetimepicker({format:'MM/DD/YYYY HH:mm'});
				$('#habit_item_start_date').datetimepicker({format:'MM/DD/YYYY'});
				$('#habit_item_start_time').datetimepicker({format:'hh:mm a'});
				$('#habit_item_end_date').datetimepicker({format:'MM/DD/YYYY'});
				$('#habit_item_end_time').datetimepicker({format:'hh:mm a'});
            });
		function firstpaidsession(){
			$('#firstpaidsession').modal('show'); 
		}
    </script>
    <script type="text/javascript">
        $("#jour-img").change(function() {
            readLogoURL(this);
        });

        function readLogoURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.jour-pic').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        onload();

        var $calendar;
        $(document).ready(function() {
            let container = $("#container, #container-1, #container-2").simpleCalendar({
                fixedStartDay: 0,
                disableEmptyDetails: true,
                events: [{
                        startDate: new Date(new Date().setHours(new Date().getHours() + 24)).toDateString(),
                        endDate: new Date(new Date().setHours(new Date().getHours() + 25)).toISOString(),
                        summary: 'Visit of the Eiffel Tower'
                    },

                    {
                        startDate: new Date(new Date().setHours(new Date().getHours() - new Date().getHours() - 12, 0)).toISOString(),
                        endDate: new Date(new Date().setHours(new Date().getHours() - new Date().getHours() - 11)).getTime(),
                        summary: 'Restaurant'
                    },

                    {
                        startDate: new Date(new Date().setHours(new Date().getHours() - 48)).toISOString(),
                        endDate: new Date(new Date().setHours(new Date().getHours() - 24)).getTime(),
                        summary: 'Visit of the Louvre'
                    }
                ],
                onDateSelect: function (date) 
                {
                    var date = new Date(date);

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

                    newDate = yyyy + '-' + mm + '-' + dd;
                    //alert(newDate);

                    $.ajax(
                    {
                        type: "POST",
                        url: "{{route('clientappointment.date')}}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'appointment_date': newDate,
                            'client_id':$('#clientIdVal').val(),

                        },
                        success: function(data) 
                        {
                            $("#showdlt").html(data);
                        }
                    });

                    $.ajax(
                    {
                        type: "POST",
                        url: "{{route('clientappointment.date')}}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'appointment_date': newDate,
                        },
                        success: function(data) 
                        {
                            //alert(data);
                            $("#showdlt2").html(data);
                        }
                    });

                    $.ajax(
                    {
                        type: "POST",
                        url: "{{route('clientappointment.date')}}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'appointment_date': newDate,
                        },
                        success: function(data) 
                        {
                            //alert(data);
                            $("#clientappointment").html(data);
                        }
                    });

                    $.ajax(
                    {
                        type: "POST",
                        url: "{{route('clientappointment.date')}}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'appointment_date': newDate,
                            'client_id':$('#coachIdVal').val(),
                        },
                        success: function(data) 
                        {
                            //alert(data);
                            $("#coachappointment").html(data);
                        }
                    });
                }
            });
        });


        function onload()
        {
            var date = new Date();

            var dd = date.getDate();
            var mm = date.getMonth() + 1;
            var yyyy = date.getFullYear();
            var fullmonth = date.toLocaleString('default', { month: 'long' });

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
                    'client_id':$('#clientIdVal').val(),

                },
                success: function(res) 
                {
                    $("#showdlt").html(res);

                    console.log(res.data.length);

                    $("#showdlt").html('');

                    if(res.data.length > 0)
                    {
                        $("#showdlt").append(newDate2);
                    }

                    for(var i = 0; i < res.data.length; i++)
                    {
                        var profile = res.data[i].profile;
                        var client_name = res.data[i].client_name;
                        var date = res.data[i].date;
                        var time = res.data[i].time;
                        var appoint_id = res.data[i].appoint_id;

                        $("#showdlt").append('<li data-toggle="modal" data-target="#appointmentdetails" onclick="appointmentdetails(\'' + appoint_id + '\',\'' + newDate + '\');"><a style="cursor:pointer;"><img src="{{asset("public/")}}/'+ profile +'"><p>'+client_name+'<span>'+time+'</span></p></a></li>');
                    }
                }
            });

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

                        $("#showdlt2").append('<li data-toggle="modal" data-target="#appointmentdetails" onclick="appointmentdetails(\'' + appoint_id + '\',\'' + newDate + '\');"><a style="cursor:pointer;"><img src="{{asset("public/")}}/'+ profile +'"><p>'+client_name+'<span>'+time+'</span></p></a></li>');
                    }
                }
            });

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

                        $("#clientappointment").append('<li data-toggle="modal" data-target="#appointmentdetails" onclick="appointmentdetails(\'' + appoint_id + '\',\'' + newDate + '\');"><a style="cursor:pointer;"><img src="{{asset("public/")}}/'+ profile +'"><p>'+client_name+'<span>'+time+'</span></p></a></li>');
                    }
                }
            });

            $.ajax(
            {
                type: "POST",
                url: "{{route('clientappointment.date')}}",
                data: {
                    _token: "{{ csrf_token() }}",
                    'appointment_date': newDate,
                    'client_id':$('#coachIdVal').val(),
                },
                success: function(res) 
                {
                    //alert(data);
                    //$("#coachappointment").html(data);

                    console.log(res.data.length);

                    $("#coachappointment").html('');

                    if(res.data.length > 0)
                    {
                        $("#coachappointment").append(newDate2);
                    }

                    for(var i = 0; i < res.data.length; i++)
                    {
                        var profile = res.data[i].profile;
                        var client_name = res.data[i].client_name;
                        var date = res.data[i].date;
                        var time = res.data[i].time;
                        var appoint_id = res.data[i].appoint_id;

                        $("#coachappointment").append('<li data-toggle="modal" data-target="#appointmentdetails" onclick="appointmentdetails(\'' + appoint_id + '\',\'' + newDate + '\');"><a style="cursor:pointer;"><img src="{{asset("public/")}}/'+ profile +'"><p>'+client_name+'<span>'+time+'</span></p></a></li>');
                    }
                }
            });
        }
    </script>
    <script type="text/javascript">
        function showHideCalendar(obj) {
            if ($(obj).hasClass('show')) {
                $(obj).removeClass('show');
                $('#container').hide();
            } else {
                $(obj).addClass('show');
                $('#container').show();
            }
        }
    </script>
	
	<script type="text/javascript">
        function readnotification(notification_id) {
          // alert(notification_id);
		     $.ajax(
            {
                type: "POST",
                url: "{{route('coach.readNotificationStatus')}}",
                data: {
                    _token: "{{ csrf_token() }}",
                    'id': notification_id,
                    'status':0,
                },
                success: function(data) 
                {
                    //alert(data);
                   // $("#coachappointment").html(data);
                }
            });
        }
    </script>
	
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-database.js"></script>

    <script>
        /*Here is the demo account details of chhavi*/

        var firebaseConfig = {
            apiKey: "AIzaSyDGacnXrLdnjj4ubqOo9D0khd4WsIYlfv4",
            authDomain: "laravelfirebase-e624d.firebaseapp.com",
            databaseURL: "https://laravelfirebase-e624d-default-rtdb.firebaseio.com",
            projectId: "laravelfirebase-e624d",
            storageBucket: "laravelfirebase-e624d.appspot.com",
            messagingSenderId: "354870234664",
            appId: "1:354870234664:web:2e743d6e318d7dee6eef1d",
            measurementId: "G-KLB43DZE8K"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
  
        
            messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) 
            {
                console.log(token);
   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
  
               /*  $.ajax(
                {
                    type: "POST",
                    url: "{{route('fcmTokensave')}}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        'fcm_token': token,
                    },
                    success: function(res)
                    {   
                        
                    }
                }); */
  
            }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
        
      
        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(noteTitle, noteOptions);
        });
    </script>
    @yield('script')
</body>

</html>