@extends('user.layout.app')
<style>
#tab3 ul li {
	margin-left: 18px;
}
#tab3 ul {
	list-style: disc;
}
#myModal-note ul {list-style: disc;padding-left: 30px;}
#myModal-note p {margin: 0;}
#hlist .greeninput input[type=checkbox][disabled][checked]{outline: 1px solid #ccc !important;
    height: 16px;
    width: 16px;
}
.coach_appointment {
	border: 1px solid #046404;
	color: #fff !important;
	background-color: var(--main-color);
	border-radius: 4px;
	display: inline-block;
	font-size: 15px;
	margin: 30px 0px 0px 17px;
	padding: 8px 32px;
}
.coach_appointment:hover{background: transparent!important;
	border: 1px solid #046404 !important;
	color: #000 !important;}
.custom_calender .bootstrap-datetimepicker-widget .collapse {
	padding: 5px;
	width: 100%;
}
.custom_calender .bootstrap-datetimepicker-widget table td.day {
	height: 35px;
	line-height: 22px;
	width: 35px;
}
.custom_calender .bootstrap-datetimepicker-widget .collapse tr {
	margin: 3px;
}
#habitcalender .calendar table {
	font-size: 10.5px;
	color: #fff;
}
#coachapp2 .calendar table {
	font-size: 12.5px;
	margin: 20px 0 0;
	width: 100%;
	color: #fff;
}
.send-btn:hover {
	background: transparent!important;
	border: 1px solid #046404 !important;
	color: #000 !important;
}
.habit-table strong{font-size: 20px;
font-family: SF-Pro-Semibold;
font-weight: normal;}
#AddappointmentModal1 .modal-dialog{max-width: 660px}
#AddappointmentModal1 .modal-content {height: 600px;}
#reschedulaappointmentModal .modal-dialog{max-width: 660px}
#reschedulaappointmentModal .modal-content {height: 600px;}

#habitmyModal2 .modal-dialog{max-width: 600px}
#habitmyModal2 .modal-content{max-height: 600px}
@media(min-width:768px){
	.weekdays_select label{
		width: 80px;
		text-align: left;
	}
}
</style>
@section('content')
<div class="dash-sec">
    <div class="container">
	<div class="row">
			<div class="col-lg-12 col-md-12">
				<a class="back-btn" href="/" style="position: relative;"><i class="fa fa-chevron-left" style=""></i> Home</a>
			</div>
		</div>
        <div class="row">
		
            <div class="col-lg-4 col-md-12">
			
                @include('user.coach.dashboardleftsidebar2')

                <input type="hidden" id="clientIdVal" value="{{ $client_data->client_id }}"/> 

                <input type="hidden" id="eventdate" value=""/> 
            </div><!-- col end here -->

            <div class="col-lg-8 col-md-12">
				<?php if($client_data->status !==1){ ?>
					<p style="color: #d00f0f;">This in an archived client. You will not be able to edit any data or work with your client until you add this client back in to a paid subscription.</p>
				<?php } ?>
                <div class="dash-coach clt-sec">
                    <h2 class="my-clt"><a href="{{route('user.dashboard')}}"><?php if($client_data->status ==1){ ?>My Client<?php } else{ echo "Archived Client";}?></a></h2>
                    <ul class="coach-pro">
                        <li class="active"><img src="{{asset('/')}}{{$client_data->client->profile_pic}}"> <span> {{$client_data->name}}</span></li>
                        <li> @if(session('success'))
                          <div class="alert alert-success">{{session('success')}}</div>
                         @endif
                         @if(session('errors'))
                          <div class="alert alert-danger">{{session('Errors')}}</div>
                         @endif
                         </li> 
                    </ul><!-- ul end here -->
					
                    <div class="view-pro">
                        <h2>{{$client_data->client->name}}<button data-toggle="modal" data-target="#viewmyModal_1" type="button">View Profile <img src="{{asset('assets/front-assets/images/arrow.png')}}"></button></h2>
                        <div>
                        
                        <!--<a href="#" data-toggle="modal" data-target="#appointment">Appointments</a>--> 
						<?php //print_r($client_data);?>
						
						@if($client_data->status==1)
                        <form autocomplete="off" action="{{route('coach.archiveAddedClients')}}" method="post" id="" enctype="multipart/form-data">
                         @csrf
                         <input class="box" type="hidden" name="added_client_id" value="{{$client_data->id}}"> 
						 <input class="box" type="hidden" name="subscription_id_for_client" value="{{$client_data->subscription_id_for_client}}">
                            <button class="arc-btn1" onclick='return confirm("Are you sure you want to archive it?")'>Archive Client</button>
                        </form>
						@elseif($client_data->status==2)
							<a href="{{route('coach.get_reinstateclient',['clientId'=>$client_data->client_id])}}" class="arc-btn1">Reinstate Client</a>
						@else
							<a href="#" class="arc-btn1">Pending Client</a>
						@endif

                        </div>
                    </div><!-- view-pro end here -->
					<div class="row">
					<div class="col-md-5">
						
							<!--<a href="{{route('coach.getNotifications',['clientId'=>$client_data->client_id])}}" class="send-btn" style="border: 0;padding: 8px 30px;">Send Message to {{$client_data->client->name}}</a>-->	
							<?php //print_r($client_data);?>
							<?php if($client_data->status==1){?>
							<a onclick="mymessage()" class="send-btn" style="border:1px solid #046404;padding: 8px 30px;color:#fff;cursor: pointer;">Send Message to {{$client_data->client->name}}</a>	
							<?php }else{ ?>
								<a  class="send-btn" style="border:1px solid #046404; padding: 8px 30px;color:#fff;cursor: pointer;" onclick='return confirm("You are unable to send message.Please reinstate your client account.")'>Send Message to {{$client_data->client->name}}</a>
							<?php }?>
				
					</div>
					<div class="col-md-6">
						<?php 
						
						
						
						if($client_data->status==1){ ?>
						@if($client_data->client->phone)
						<a class="call-btn" href="tel:{{ $client_data->client->phone }}" style="margin-top: 12px;padding: 8px 30px;">Call to {{ $client_data->client->name }}&nbsp; {{ $client_data->client->phone }}&nbsp;<img src="{{asset('assets/front-assets/images/arrow2.png')}}"></a>
						@else
						 <a class="call-btn" href="#" onclick="confirm('Phone number not available')" style="margin-top: 12px;padding: 8px 30px;">Call to {{ $client_data->client->name }} &nbsp;<img src="{{asset('assets/front-assets/images/arrow2.png')}}"></a>
						@endif
						<?php }else{?>
						<a class="call-btn" href="#" onclick="confirm('You are unable to call.Please reinstate your client account.')" style="margin-top: 12px;">Call to {{ $client_data->client->name }} &nbsp;<img src="{{asset('assets/front-assets/images/arrow2.png')}}"></a>
						
						<?php } ?>
					</div>
					</div>
                    <ul class="nav nav-tabs js-example" role="tablist">
                        <li><a class="{{ (session('activeTab') == '')?'active':'' }}" href="#tab1" role="tab" data-toggle="tab">Habit Lists</a></li>
                        <li><a href="#tab2" class="{{ (session('activeTab') == 'goals')?'active':'' }}" role="tab" data-toggle="tab">Goals</a></li>
                        <li><a href="#tab3" class="{{ (session('activeTab') == 'notes')?'active':'' }}" role="tab" data-toggle="tab">Coach's Notes</a></li>
                        <li><a href="#tab4" class="" role="tab" data-toggle="tab">Client's Journal</a></li>
                    </ul><!-- ul end here -->

                    <div class="tab-content">
                        <div id="tab1" class="tab-pane {{ (session('activeTab') == '')?'active':'' }}">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 dd-1">
                                    <div id="habitcalender" class="calendar-container">
                                    
                                    </div> 
                                    @if($client_data->status==1)
                                    <button data-toggle="modal" data-target="#habitmyModal2" class="new-btn" type="button">Add New Item</button>
									@endif
                                </div>

                                <div class="col-lg-8 col-md-8">
                                    
                                
                                    <div class="habit-table" style="overflow-x:auto;" id="hlist">
                                        
                                    </div><!-- habit-table end here -->
                                    
                                    
                                </div><!-- col end here -->
                            </div><!-- row end here -->
                        </div><!-- tab1 end here -->

                        <div id="tab2" class="tab-pane {{ (session('activeTab') == 'goals')?'active':'' }}">
                            <div class="goal-txt">
                                <img src="{{asset('assets/front-assets/images/LC-Canon-Logo-01-inner.png')}}" width="80">
                                <h5 id="goalper"></h5>
								@if($client_data->status==1)
                                <button data-toggle="modal" data-target="#myModal-4" class="new-btn" type="button">Add New Goal</button>
								@endif
                            </div><!-- goal-txt end here -->
                            <div class="habit-table" style="overflow-x:auto;">
                                <table>
                                    @foreach($client_data->goal as $goal)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{$goal->id}}" class="updategoalstatus greeninput" {{($goal->status==1)?'checked':''}}><strong>&nbsp;&nbsp; {{$goal->name}}</strong>
                                         <input id="client_id" class="box" type="hidden" name="client_id" value="{{$client_data->client_id}}">
                                        </td>
										@if($client_data->status==1)
                                        <td><a data-toggle="modal" data-target="#myModal-{{$goal->id}}" href="#"><i class="fa fa-pencil-square-o"></i></a></td>
										@endif
                                        <div class="modal fade coach-popup" id="myModal-{{$goal->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <h2>Edit Your Goal</h2>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <div class="modal-body">
                                                        <form autocomplete="off" action="{{route('coach.addupdategoal')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="in-box">
                                                                <h5>Title</h5> <input class="box" type="text" name="name" value="{{$goal->name}}" placeholder="">
                                                                <input class="box" type="hidden" name="client_id" value="{{$client_data->client_id}}">
                                                                <input class="box" type="hidden" name="id" value="{{$goal->id}}">
                                                            </div>
                                                            <a class="del-btn" href="{{route('coach.deletegoal',['id'=>$goal->id])}}">Delete</a> <button type="submit" class="log-btn">Save</button>
                                                        </form>                                
                                                        
                                                    </div><!-- modal-body end here -->
                                                </div><!-- modal-content end here -->
                                            </div><!-- modal-dialog end here -->
                                        </div><!-- modal end here -->
                                    </tr><!-- tr end here -->
                                    @endforeach
                                </table><!-- table end here -->
                            </div><!-- habit-table end here -->
                        </div><!-- tab2 end here -->

                        <div id="tab3" class="tab-pane {{ (session('activeTab') == 'notes')?'active':'' }}">
                            <div class="jour-txt">
                                <h6>*These notes will not be shared with the client</h6>
                                @if($client_data->status==1)<button data-toggle="modal" data-target="#myModal-note" class="new-btn" type="button">Add New Note</button>
								@endif
                                @foreach($client_data->note as $notes)
                                <div class="note-txt">
									@if($client_data->status==1)
										<button type="button" data-toggle="modal" data-target="#myModalnotes-{{$notes->id}}"><i class="fa fa-pencil-square-o"></i></button> 
										 <form autocomplete="off" action="{{route('coach.deletenote')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
										 @csrf
										 <input class="box" type="hidden" name="client_id" value="{{$client_data->client_id}}">
										   <input class="box" type="hidden" name="id" value="{{$notes->id}}">
											<input class="box" type="hidden" name="user_id" value="{{$notes->user_id}}">
										<button class="del-btn" type="submit" onclick='return confirm("Are you sure to delete?")'><i class="fa fa-trash-o"></i></button>
										</form>
                                     @endif
                                    
                                    <h5>{{date('D, F j, Y',strtotime($notes->date_time))}}</h5>
									<p><small><?php
										 echo date('h:i A', strtotime($notes->date_time));
										?>
										</small>
									</p>
                                    <p><?php echo html_entity_decode($notes->description);?><br><br><?php if($notes->images1){ ?><img src="{{asset('/')}}{{ $notes->images1 }}" width="50" height="50"><?php } ?></p>
                                </div><!-- note-txt end here -->
                                 <div class="modal fade jour-popup" id="myModalnotes-{{$notes->id}}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <form autocomplete="off" action="{{route('coach.updatenote')}}" method="post" id="" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="in-box">
                                                        <h5>Edit Coach's Notes</h5> 

                                                        <textarea class="box description" type="text" name="description" placeholder="" rows="12"><?php echo html_entity_decode($notes->description);?></textarea>

                                                        <input class="box" type="hidden" name="client_id" value="{{$client_data->client_id}}">
                                                        <input class="box" type="hidden" name="id" value="{{$notes->id}}">
                                                    </div>
                                                    <div class="jour-box">
                                                        <div class="jour-img">
                                                            <input type="file" name="images1">
															<?php if($notes->images1){ ?>
                                                            <img src="{{asset('/')}}{{ $notes->images1 }}" width="150" height="150">
															<?php } ?>
                                                        </div><!-- jour-img end here -->
                                                    </div><!-- jour-box end here -->
                                                        
                                                     <button type="submit" class="log-btn">Update</button>
                                                </div><!-- modal-body end here -->
                                            </form>
                                        </div><!-- modal-content end here -->
                                    </div><!-- modal-dialog end here -->
                                </div><!-- modal end here -->


                                @endforeach  
                            </div><!-- accordion end here -->
                        </div><!-- tab3 end here -->

                        <div id="tab4" class="tab-pane">
                            <div id="accordion" class="jour-txt">
                                @foreach($client_data->journals as $journal)
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{date('F j, Y',strtotime($journal->date_time))}}</h5> <a data-toggle="collapse" href="#collapse-{{$journal->id}}"><span>{{date('g:i a',strtotime($journal->date_time))}}</span> <?php echo html_entity_decode(substr($journal->description, 0, 30));?></a>
                                    </div>
                                    <div id="collapse-{{$journal->id}}" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            
											<?php echo html_entity_decode($journal->description);?>
                                        </div><!-- card-body here -->
                                    </div><!-- collapse-1 end here -->
                                </div><!-- card end here -->
                                @endforeach
                            </div><!-- accordion end here -->
                        </div><!-- tab4 end here -->
                    </div><!-- tab-content end here -->
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
                        <div class="in-box"><input class="box" type="text" name="" placeholder=""></div>
                        <a class="log-btn" href="#">Submit</a>
                    </div><!-- coach-cd end here -->

                    <div class="coach-dt">
                        <img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                        <div class="coac-txt">
                            <h5>John Cena <span>Coach Code: <em>1376</em></span></h5>
                            <a class="log-btn" href="#">Continue</a>
                        </div><!-- coac-txt end here -->
                    </div><!-- coach-dt end here -->

                    <div class="coach-dt-1">
                        <img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                        <div class="coac-txt">
                            <h5>John Cena</h5>
                            <p>Your Coach has requested you to pay for the full course up front <span>$2,000</span></p>
                            <a class="log-btn" href="payment.html">Continue</a>
                        </div><!-- coac-txt end here -->
                    </div><!-- coach-dt-1 end here -->
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->

    <div class="modal fade coach-popup dd" id="viewmyModal_1">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Client Profile</h2>
				@php
				//print_r($client_data);
				@endphp
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
                    <div class="pro-img"><img src="{{asset('/')}}{{ $client_data->client->profile_pic }}">
                        <h5>{{$client_data->client->name}}</h5>
                    </div>
                    <div class="pro-txt">
                        <p><span>Experience</span> {{$client_data->client->experience}}</p>
                        <p><span>Area of Expertise</span> {{$client_data->client->area_of_expertise}}</p>
                        <p><span>Email</span> {{$client_data->client->email}}</p>
                        <p><span>About</span>{{$client_data->client->description}}</p>
						<p><span>Phone</span>{{$client_data->client->phone}}</p>
                    </div><!-- pro-txt end here -->
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->

    <div class="modal fade coach-popup" id="habitmyModal2">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Add Habit Item</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
                <form autocomplete="off" action="{{route('coach.addhabits')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
                   @csrf
                   
                        <div class="row">
                            <div class="col-md-4">
                                <label>Title</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3"  name="habit_name" placeholder="Enter Habbit Name"/>
                                <input id="client_id" class="box" type="hidden" name="client_id" value="{{$client_data->client_id}}">
                           </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <label>Start Date</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" id="habit_item_start_date" name="start_date" placeholder="Enter start date" required/>
                           </div>
						   
                        </div>
						
                        <div class="row">
                            <div class="col-md-4">
                                <label>End Date</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" id="habit_item_end_date" name="end_date" placeholder="Enter the end date" required/>
                           </div>
                        </div>
						<div class="row">
                            <div class="col-md-4">
                                <label>Start Time</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" id="habit_item_start_time" name="start_time" placeholder="Enter start time" required/>
                           </div>
						   
                        </div>
					  <div class="row">
                            <div class="col-md-4">
                                <label>End Time</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" id="habit_item_end_time" name="end_time" placeholder="Enter the end time" required/>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>No. of Weekly Cycles</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control mb-3" class="cycle" name="number_of_session"> 
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                           </div>
                        </div>
                       <div class="row">
                            <div class="col-md-4">
                                <label>Alert</label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control mb-3" name="alert">
                                    <option value="5 Minutes Before">5 Minutes Before</option>
                                    <option value="10 Minutes Before">10 Minutes Before</option>
                                    <option value="15 Minutes Before">15 Minutes Before</option>
                                    <option value="30 Minutes Before">30 Minutes Before</option>
                                    <option value="1 hour Before">1 hour Before</option>
                                    <option value="2 hour Before">2 hour Before</option>
                                    <option value="1 day Before">1 day Before</option>
                                </select>
                           </div>
                        </div>
                         <div class="row">
                            <div class="col-md-4">
                                <label>Select Weekdays</label>
                            </div>
                            <div class="col-md-8 weekdays_select">
                            <input type="checkbox" id="week-add" name="week_days[]" value="Monday"> <label for="week-add">Monday</label>
                            <input type="checkbox" id="week-add" name="week_days[]" value="Tuesday"> <label for="week-add">Tuesday</label>
                            <input type="checkbox" id="week-add" name="week_days[]" value="Wednesday"> <label for="week-add">Wednesday</label>
                            <input type="checkbox" id="week-add" name="week_days[]" value="Thursday"> <label for="week-add">Thursday</label>
                            <input type="checkbox" id="week-add" name="week_days[]" value="Friday"> <label for="week-add">Friday</label>
                            <input type="checkbox" id="week-add" name="week_days[]" value="Saturday"> <label for="week-add">Saturday</label>
                            <input type="checkbox" id="week-add" name="week_days[]" value="Sunday"> <label for="week-add">Sunday</label>
                           </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-12">
                                <button class="log-btn" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->

    <div class="modal fade jour-popup" id="myModal-note">
        <div class="modal-dialog">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <form autocomplete="off" action="{{route('coach.addnotes')}}" method="post" id="" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="in-box">
                            <h5>Coach's Notes</h5> 

                            <textarea class="box description" type="text" name="description" placeholder="" rows="12"></textarea>
                            <input class="box" type="hidden" name="client_id" value="{{$client_data->client_id}}">
                        </div>
                        <div class="jour-box">
                            <div class="jour-img">
                                <input type="file" id="jour-img" name="images1">
                                <label for="jour-img"><img class="jour-pic" src="{{asset('assets/front-assets/images/jour-img.png')}}"></label>
                            </div><!-- jour-img end here -->
                        </div><!-- jour-box end here -->

                         <button type="submit" class="log-btn">Save</button>
                    </div><!-- modal-body end here -->
                </form>
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->

    <div class="modal fade coach-popup" id="myModal-4">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Add Goal</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
                    <form autocomplete="off" action="{{route('coach.addupdategoal')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
                        @csrf
                        <div class="in-box">
                            <h5>Title</h5> <input class="box" type="text" name="name" placeholder="">
                            <input class="box" type="hidden" name="client_id" value="{{$client_data->client_id}}">
                        </div>
                        <button type="submit" class="log-btn">Save</button>
                    </form>
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->

    <div class="modal fade coach-popup" id="goalupdate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h5><center>You're Making Great Progress !</center></h5>

                    <img src='{{asset("/")}}goaldone.gif' style="width:50%"><br><br>

                    <button type="button" class="btn btn-success" data-dismiss="modal">Done</button>
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
							<br>
							<div class="row">
							   <div class="col-md-3" style="text-align:left; padding:5px;">
								  <label>End Date</label>
							   </div>
							   <div class="col-md-9" style="padding: 0px;">
								  <input type="text" class="form-control mb-3" id="datetim3" name="end_date" placeholder="Enter the end date" required>
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
    
<!-----------Appointment form--------------->

<div class="modal fade coach-popup" id="appointment">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Add Appointment</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
                   <div class="appoint-sec">
                <form autocomplete="off" action="{{route('coach.clientappointment')}}" method="post"  enctype="multipart/form-data">
                        @csrf
                      <h5>Choose Date</h5>
                      <input type="date" name="appointmentdate" value="" class="form-control" id="appointmenttdate">
                      <h5>Choose A Weekdays</h5>
                      <ul>
                       <li><input type="checkbox"> <label for="day-add">Monday</label></li>
                       <li><input type="checkbox"> <label for="day-add">Tuesday</label></li>
                       <li><input type="checkbox"> <label for="day-add">Wednesday</label></li>
                       <li><input type="checkbox"> <label for="day-add">Thursday</label></li>
                       <li><input type="checkbox"> <label for="day-add">Friday</label></li>
                       <li><input type="checkbox"> <label for="day-add">Saturday</label></li>
                       <li><input type="checkbox"> <label for="day-add">Sunday</label></li>
                      </ul><!-- ul end here -->
                      
                      <hr>
                      <h5>Choose from Available TimeSlots</h5>
                      <ul>
                       <li><input type="checkbox"> <label for="slot-add">8:00 AM - 9:00 AM</label></li>
                       <li><input type="checkbox"> <label for="slot-add">10:00 AM - 11:00 AM</label></li>
                       <li><input type="checkbox"> <label for="slot-add">11:00 AM - 12:00 PM</label></li>
                       <li><input type="checkbox"> <label for="slot-add">12:00 PM - 1:00 PM</label></li>
                      </ul><!-- ul end here -->
                      <hr>
                      <h5>Would you like to repeat?</h5>
                      <select name="repeat">
                       <option value="">Select an Option</option>
                       <option value="1">Yes</option>
                       <option value="0">No</option>
                      </select>
                      <button class="new-btn" type="submit">Add Appointment</button>
                      </form>
                     </div><!-- appoint-sec end here -->
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
</div><!-- modal end here -->

<!-----------Appointment form--------------->
<!---------------------message model----------------->

<div class="modal fade jour-popup" id="clientmessage">
	<div class="modal-dialog">
		<div class="modal-content" id="modal-message">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			
			<div id="chat"> 
			 <h2 style="float: right;padding: 22px 6px;font-size: 16px;">Send Message to {{$client_data->client->name}}</h2>
				<div id="messages"></div>
				
				<form id="message-form">
				<div class="ms-chat">
				 <!--<input id="message-input" class="form-control" type="" name="text" placeholder="Type your message..." required >-->
				 <textarea  id="message-input" class="form-control" name="text" minlength="2"placeholder="Type your message..." data-expandable required></textarea>
				 <button id="message-btn" type="submit"><i class="fa fa-paper-plane"></i></button>    
				</div><!-- ms-chat end here -->
				</form>
			</div>
			
		</div><!-- modal-content end here -->
	</div><!-- modal-dialog end here -->
</div><!-- modal end here -->


<!---------------------message model----------------->



<!-----------------Coach add appointment form ---------->
    <div class="modal fade coach-popup" id="AddappointmentModal1">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Add Appointment</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body" style="padding:30px;">
                    <form autocomplete="off" class="require-validation" action="{{route('coachaddappointment')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="alert alert-danger" style="display:none"></div>                    
                        <div class="alert alert-success" style="display:none"></div>

                            <div class="row">
                                <div class="col-md-3" style="text-align:left; padding:5px;">
                                   <label>Choose a Weekday</label>
                                </div>
                               
                                <div class="col-md-9" style="padding: 0px;">
                                    <ul class="donate-now">
                                        <li>
                                            <input type="radio" id="add1" name="app_days" value="Monday" onclick="AddAppointmentgettime();"/>
                                            <label for="add1">Monday</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="add2" name="app_days" value="Tuesday" onclick="AddAppointmentgettime();"/>
                                            <label for="add2">Tuesday</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="add3" name="app_days" value="Wednesday" onclick="AddAppointmentgettime();"/>
                                            <label for="add3">Wednesday</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="add4" name="app_days" value="Thursday" onclick="AddAppointmentgettime();"/>
                                            <label for="add4">Thursday</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="add5" name="app_days" value="Friday" onclick="AddAppointmentgettime();"/>
                                            <label for="add5">Friday</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="add6" name="app_days" value="Saturday" onclick="AddAppointmentgettime();"/>
                                            <label for="add6">Saturday</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="add7" name="app_days" value="Sunday" onclick="AddAppointmentgettime();"/>
                                            <label for="add7">Sunday</label>
                                        </li> 
                                    </ul>
                                </div>
                            </div>
                              
                            <div class="row">
                               <div class="col-md-3" style="text-align:left; padding:5px;">
                                  <div></div>Choose From Available Time</label>
                               </div>

                               <div class="col-md-9" style="padding: 0px;">
                                    <ul class="donate-now1" id="appointment_add">
                                        <p style="color:red">No Available Times</p>
                                    </ul>
                               </div>
                            </div>

                            <div class="row">
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

                            <div class="row">
                               <div class="col-md-3" style="text-align:left; padding:5px;">
                                  <label>End Date</label>
                               </div>

                               <div class="col-md-9" style="padding: 0px;">
                                    <input type="hidden" name="client_id" id="user_id" value="<?php echo $client_data->client_id; ?>">
                                  <input type="text" class="form-control mb-3" id="addappdatetim4" name="end_date" placeholder="Enter the end date" />
                               </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="log-btn" type="submit">Save</button>
                                </div>
                            </div> 
                        
                    </form>
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->
<!--------------End Coach add appointment form --------->

</div><!-- dash-sec end here -->
@endsection

@section('script')

 <link rel="stylesheet" href="https://lifecanon.com/public/assets/front-assets/summernote-summernote/dist/summernote-bs4.css">
  <script type="text/javascript" src="https://lifecanon.com/public/assets/front-assets/summernote-summernote/dist/summernote-bs4.js"></script>

  <script type="text/javascript">
   jQuery(document).ready(function (){
      jQuery('.description').summernote({
        height: 200,
      });
    });
  </script>

   <script>
    function AddAppointmentgettime() 
    {
		
        var app_days = $("input[name='app_days']:checked").val();
		
        var user_id = $("#user_id").val();

        $("#appointment_add").html('<img src="{{asset("/")}}loading2.gif" style="width:55px;">');
    
        $.ajax(
        {
            type: "POST",
            url: "{{route('get_availabletime')}}",
            data: {
                _token: "{{ csrf_token() }}",
                'app_days': app_days,
                'user_id' : <?php echo $client_data->user_id; ?>,
            },
            success: function(res)
            {   
                $("#appointment_add").html('');

                if(res.data.length > 0)
                {
                    for(var i = 0; i < res.data.length; i++)
                    {
                        $("#appointment_add").append('<li><input type="radio" id="add1'+i+'" name="app_time" value="'+res.data[i].gettime+'"><label for="add1'+i+'">'+res.data[i].gettime+'</label></li>');
                    }  
                }
                else
                {
                    $("#appointment_add").append('<p style="color:red">No Available Times</p>');
                }
            }
        }); 
    }
   
        onloadhabit();

        function deletehabit()
        {
            alert("Are you sure want to delete");
        }

        $(document).ready(function() {
            var container = $("#habitcalender").simpleCalendar({
                //fixedStartDay: 0,
                disableEmptyDetails: true,
                events: [
                    @php
                        echo $totaldt;
                    @endphp
                ],
                onDateSelect: function (date)
                {
                    //console.log(date);

                    var date = new Date(date);
                    var dd = date.getDate();
                    var mm = date.getMonth() + 1;
                    var yyyy = date.getFullYear();
                    var time = date.getTime();

                    var text = "a"+dd+""+date.getMonth()+"a";

                    //console.log(text);
                    $('#habitcalender table tr td div').removeClass("today");

                    $("#habitcalender #"+text).addClass("today");

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
                    //console.log(newDate);
                    $.ajax(
                    {
                        type: "POST",
                        url: "{{route('coach.listhabits')}}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'habit_date': newDate,
                            'client_id':$('#client_id').val(),
                        },
                        success: function(res)
                        {   
                            $("#hlist").html('');

                            if(res.data.length > 0)
                            {
                                $("#hlist").append(newDate2);
                            }

                            for(var i = 0; i < res.data.length; i++)
                            {
                                //alert(data.name);
                                //console.log(res.data[i].id);
                                var id = res.data[i].id;
                                //console.log(id);
                                var stardate = res.data[i].start_date;
                                var user_id = res.data[i].user_id;
                                var client_id = res.data[i].client_id;
                                var enddate = res.data[i].end_date;
                                var title = res.data[i].title;
                                var startime = res.data[i].startime;
                                var endtime = res.data[i].endtime;
                                var weekcycle = res.data[i].weekcycle;
                                var alert = res.data[i].alert;
                                var weekdays = res.data[i].weekdays;
                                var status = res.data[i].status;
                                var selectdate = res.data[i].selectdate;
                                var selectdate2 = res.data[i].selectdate2;
								//console.log(newDate);
								//console.log(selectdate2);
                                /* if(status==2 && newDate <= selectdate2)
                                {
                                    var chkbox ='<input type="checkbox" value="" checked="true" disabled>';
                                }
                                else
                                {
                                     if(newDate < selectdate2)
                                    {
                                        var chkbox ='<input type="checkbox" value='+ id +' checked="true" disabled>';
										
                                    }
                                    else
                                    {
                                        var chkbox ='<img src="https://lifecanon.com/public/assets/front-assets/images/dot_new.png"  width="22"/>';
                                    } 
									
									 //var chkbox ='<img src="https://lifecanon.com/public/assets/front-assets/images/dot_new.png"  width="22"/>';
                                } */
								
								if(status==1)
                                {
                                    var chkbox ='<input type="checkbox" value="" checked="true" disabled>';
                                }else{
									var chkbox ='<input type="checkbox" value="" disabled>';
								}
                                var opt1 = weekcycle==1 ? 'selected': ' ';
                                var opt2 = weekcycle==2 ? 'selected': ' ';
                                var opt3 = weekcycle==3 ? 'selected': ' ';
                                var opt4 = weekcycle==4 ? 'selected': ' ';
                                var opt5 = weekcycle==5 ? 'selected': ' ';
                                var alert1= alert=='5 Minutes Before' ? 'selected' : ' ';
                                var alert2= alert=='10 Minutes Before' ? 'selected' : ' ';
                                var alert3= alert=='15 Minutes Before' ? 'selected' : ' ';
                                var alert4= alert=='30 Minutes Before' ? 'selected' : ' ';
                                var alert5= alert=='1 hour Before' ? 'selected' : ' ';
                                var alert6= alert=='2 hour Before' ? 'selected' : ' ';
                                var alert7= alert=='1 day Before' ? 'selected' : ' ';
                                
                                var frm ='<form autocomplete="off" action="{{route('coach.updatehabit')}}" method="post" id="" enctype="multipart/form-data">@csrf<h3>'+ title +'@if($client_data->status==1)<button class="cl-btn" type="submit" style="float: right;margin-top: -7px;">Save</button>@endif</h3><ul class="wdays"><li><div class="row"><div class="col-md-4"><label>Title</label></div><div class="col-md-8"><input type="text" class="form-control mb-3"  name="habit_name" value="'+ title +'"/><input class="box" type="hidden" name="id" value="'+ id +'"><input class="box" type="hidden" name="client_id" value="'+ client_id +'"></div></div></li><li><div class="row"><div class="col-md-4"><label>Start</label></div><div class="col-md-8 custom_calender"><input type="text" class="form-control mb-3" id="startdatetimepicker'+ id +'" name="start_date" value="'+ stardate +'"  readonly/></div></div></li><li><div class="row"><div class="col-md-4"><label>End</label></div><div class="col-md-8 custom_calender"><input type="text" class="form-control mb-3" id="enddatetimepicker'+ id +'" name="end_date" value="'+ endtime +'"  readonly/></div></div></li><li><div class="row"><div class="col-md-4"><label>No.of Weekly Cycles</label></div><div class="col-md-8"><input type="text" name="number_of_session" value="'+weekcycle+'" class="form-control mb-3" readonly></div></div></li><li><div class="row"><div class="col-md-4"><label>Alert</label></div><div class="col-md-8"><select class="form-control mb-3" name="alert" style="width:100%;border: 1px solid #ccc;padding: 2px 9px;"><option value="5 Minutes Before" '+ alert1 +'>5 Minutes Before</option><option value="10 Minutes Before" '+ alert2 +'>10 Minutes Before</option><option value="15 Minutes Before" '+ alert3 +'>15 Minutes Before</option><option value="30 Minutes Before" '+ alert4 +'>30 Minutes Before</option><option value="1 hour Before" '+ alert5 +'>1 hour Before</option><option value="2 hour Before" '+ alert6 +'>2 hour Before</option><option value="1 day Before" '+ alert7 +' >1 day Before</option></select></div></li><li><div class="row"><div class="col-md-4"><label>Select Weekdays</label></div><div class="col-md-8  "><div class="row">'+res.data[i].weekdaysHtml+'</div></div></div></li><li>@if($client_data->status==1)<button class="cl-btn" type="submit">Save</button>@endif</form></li></ul><ul>@if($client_data->status==1)<li><form autocomplete="off" action="{{route('coach.deletehabit')}}" method="post" id="user-registration-form" enctype="multipart/form-data">@csrf<input class="box" type="hidden" name="client_id" value="'+ client_id +'"><input class="box" type="hidden" name="id" value="'+ id +'"><input class="box" type="hidden" name="user_id" value="'+ user_id +'"><button style="float: left;margin-top: 0px;" class="del-btn" type="submit" onclick="deletehabit();">Delete Habit List Item</button></form></li>@endif</ul>';
                                
                                $("#hlist").append('<table><tr><td style="width:60%" class="greeninput">'+ chkbox +' &nbsp;<strong> '+ title +'</strong></td><td>'+ startime +'<br>'+ endtime +'</td><td><a data-toggle="collapse" href="#collapse'+ id +'"><i class="fa fa-pencil-square-o"></i></a></td></tr> <tr id="collapse'+ id +'" class="collapse"><td>'+ frm +'</td></tr></table>');
                            }
                        }
                    });
                }
            });
        });


        function onloadhabit()
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
                url: "{{route('coach.listhabits')}}",
                data: {
                    _token: "{{ csrf_token() }}",
                    'habit_date': newDate,
                    'client_id':$('#client_id').val(),
                },
                success: function(res)
                {   
                    //console.log(res.data.length);

                    $("#hlist").html('');

                    if(res.data.length > 0)
                    {
                        $("#hlist").append(newDate2);
                    }

                    
                    for(var i = 0; i < res.data.length; i++)
                    {
                        //alert(data.name);
                        //console.log(res.data[i].id);
                        var id = res.data[i].id;
                        //console.log(id);
                        var stardate = res.data[i].start_date;
                        var user_id = res.data[i].user_id;
                        var client_id = res.data[i].client_id;
                        var enddate = res.data[i].end_date;
                        var title = res.data[i].title;
                        var startime = res.data[i].startime;
                        var endtime = res.data[i].endtime;
                        var weekcycle = res.data[i].weekcycle;
                        var alert = res.data[i].alert;
                        var weekdays = res.data[i].weekdays;
                        var status = res.data[i].status;
                        var selectdate = res.data[i].selectdate;
                        var selectdate2 = res.data[i].selectdate2;

                      /*   if(status==2 && newDate <= selectdate2)
                        {
                            var chkbox ='<input type="checkbox" value="" checked="true" disabled>';
                        }
                        else
                        {
                             if(newDate < selectdate2)
                            {
                                var chkbox ='<input type="checkbox" value='+ id +' checked="true" disabled>';
								//var chkbox ='<img src="https://lifecanon.com/public/assets/front-assets/images/dot_new.png"  width="22"/>';
                            }
                            else
                            {
                                var chkbox ='<img src="https://lifecanon.com/public/assets/front-assets/images/dot_new.png"  width="22"/>'; 
                            } 
							//var chkbox ='<img src="https://lifecanon.com/public/assets/front-assets/images/dot_new.png"  width="22"/>';
                        } */
						if(status==1)
						{
						  var chkbox ='<input type="checkbox" value="" checked="true">';
						}else{
						  var chkbox ='<input type="checkbox" value="">';	
						}
                        var opt1 = weekcycle==1 ? 'selected': ' ';
                        var opt2 = weekcycle==2 ? 'selected': ' ';
                        var opt3 = weekcycle==3 ? 'selected': ' ';
                        var opt4 = weekcycle==4 ? 'selected': ' ';
                        var opt5 = weekcycle==5 ? 'selected': ' ';
                        var alert1= alert=='5 Minutes Before' ? 'selected' : ' ';
                        var alert2= alert=='10 Minutes Before' ? 'selected' : ' ';
                        var alert3= alert=='15 Minutes Before' ? 'selected' : ' ';
                        var alert4= alert=='30 Minutes Before' ? 'selected' : ' ';
                        var alert5= alert=='1 hour Before' ? 'selected' : ' ';
                        var alert6= alert=='2 hour Before' ? 'selected' : ' ';
                        var alert7= alert=='1 day Before' ? 'selected' : ' ';
                 
                        var frm ='<form autocomplete="off" action="{{route('coach.updatehabit')}}" method="post" id="" enctype="multipart/form-data">@csrf<h3>'+ title +'@if($client_data->status==1)<button class="cl-btn" type="submit" style="float: right;margin-top: -7px;">Save</button>@endif</h3><ul class="wdays"><li><div class="row"><div class="col-md-4"><label>Title</label></div><div class="col-md-8"><input type="text" class="form-control mb-3"  name="habit_name" value="'+ title +'"/><input class="box" type="hidden" name="id" value="'+ id +'"><input class="box" type="hidden" name="client_id" value="'+ client_id +'"></div></div></li><li><div class="row"><div class="col-md-4"><label>Start</label></div><div class="col-md-8 custom_calender"><input type="text" class="form-control mb-3" id="startd'+ id +'" name="start_date" value="'+ stardate +'" placeholder="'+ stardate +'" readonly/></div></div></li><li><div class="row"><div class="col-md-4"><label>End</label></div><div class="col-md-8 custom_calender"><input type="text" class="form-control mb-3" id="endd'+ id +'" name="end_date" value="'+ endtime +'" readonly/></div></div></li><li><div class="row"><div class="col-md-4"><label>No.of Weekly Cycles</label></div><div class="col-md-8"><input type="text" value="'+weekcycle+'" name="number_of_session" class="form-control mb-3" readonly></div></div></li><li><div class="row"><div class="col-md-4"><label>Alert</label></div><div class="col-md-8"><select class="form-control mb-3" name="alert" style="width:100%;border: 1px solid #ccc;padding: 2px 9px;"><option value="5 Minutes Before" '+ alert1 +'>5 Minutes Before</option><option value="10 Minutes Before" '+ alert2 +'>10 Minutes Before</option><option value="15 Minutes Before" '+ alert3 +'>15 Minutes Before</option><option value="30 Minutes Before" '+ alert4 +'>30 Minutes Before</option><option value="1 hour Before" '+ alert5 +'>1 hour Before</option><option value="2 hour Before" '+ alert6 +'>2 hour Before</option><option value="1 day Before" '+ alert7 +' >1 day Before</option></select></div></li><li><div class="row"><div class="col-md-4"><label>Select Weekdays</label></div><div class="col-md-8  "><div class="row">'+res.data[i].weekdaysHtml+'</div></div></div></li><li>@if($client_data->status==1)<button class="cl-btn" type="submit">Save</button>@endif</form></li></ul><ul><li><form autocomplete="off" action="{{route('coach.deletehabit')}}" method="post" id="user-registration-form" enctype="multipart/form-data">@csrf<input class="box" type="hidden" name="client_id" value="'+ client_id +'"><input class="box" type="hidden" name="id" value="'+ id +'"><input class="box" type="hidden" name="user_id" value="'+ user_id +'">@if($client_data->status==1)<button style="float: left;margin-top: 0px;" class="del-btn" type="submit" onclick="deletehabit();">Delete Habit List Item</button>@endif</form></li></ul>';
                        
                        $("#hlist").append('<table><tr><td style="width:60%" class="greeninput">'+ chkbox +' <strong>&nbsp; '+ title +'</strong></td><td>'+ startime +'<br>'+ endtime +'</td><td><a data-toggle="collapse" href="#collapse'+ id +'"><i class="fa fa-pencil-square-o"></i></a></td></tr> <tr id="collapse'+ id +'" class="collapse"><td>'+ frm +'</td></tr></table>');
                    }
                }
            });
        }

        $(document).ready(function() 
        {
            let container = $("#coachapp2").simpleCalendar({
                fixedStartDay: 0,
                disableEmptyDetails: true,
                events: 
                    @php 
                        echo $totalapp2;
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
                    $('#coachapp2 table tr td div').removeClass("today");

                    $("#coachapp2 #"+text).addClass("today");

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

                            //console.log(res.data.length);

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

                                $("#showdlt").append('<li data-toggle="modal" data-target="#appointmentdetails" onclick="appointmentdetails(\'' + appoint_id + '\',\'' + newDate + '\');"><a style="cursor:pointer;"><img dasd src="{{asset("/")}}'+ profile +'"/><p>'+client_name+'<span>'+time+'</span></p></a></li>');
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
                    'client_id':$('#clientIdVal').val(),
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
						$("#getappdet").html('<table><tr><td><img src="{{asset("/")}}'+ profile +'" style="border-radius:50%; height:44px; width:44px;"></td><td><p style="color:#023a02; font-family:SF-Pro-Medium; font-size:14px; margin:0 0 0 28px; text-align:left; font-weight: 500;">'+client_name+'<br>$ '+appointment_fees+' / apt</p></td></tr></table><hr>'+upcomming+'<span style="float:left">'+get_appoint_date+'</span><br><span style="float:left">'+appointment_time+'</span><br><br><span style="float:left">Appointment with  '+client_name+'</span><br><br><div style="border-top:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding-top:10px; padding-bottom:33px;"><span style="float:left; font-size:18px; font-weight:600;">Total</span><span style="float:right; font-size:18px; font-weight:600; color:#024602;">$ '+ap_amount+'</span></div><br>@if($client_data->status==1)'+reappoint+'@endif');
					}else{
						$("#getappdet").html('<table><tr><td><img src="{{asset("/")}}'+ profile +'" style="border-radius:50%; height:44px; width:44px;"></td><td><p style="color:#023a02; font-family:SF-Pro-Medium; font-size:14px; margin:0 0 0 28px; text-align:left; font-weight: 500;">'+client_name+'<br>$ '+appointment_fees+' / apt</p></td></tr></table><hr>'+upcomming+'<span style="float:left">'+get_appoint_date+'</span><br><span style="float:left">'+appointment_time+'</span><br><br><span style="float:left">Payment will be made on '+payment_date+'</span><br><br><div style="border-top:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding-top:10px; padding-bottom:33px;"><span style="float:left; font-size:18px; font-weight:600;">Total</span><span style="float:right; font-size:18px; font-weight:600; color:#024602;">$ '+ap_amount+'</span></div><br>@if($client_data->status==1)'+reappoint+'@endif');
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
                    'user_id' :<?php echo $client_data->user_id; ?> ,
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
 
 
<script type="text/javascript">
$('.ms-chat').on('keydown input', 'textarea[data-expandable]', function() {
  //Auto-expanding textarea
  this.style.removeProperty('height');
  this.style.height = (this.scrollHeight+2) + 'px';
}).on('mousedown focus', 'textarea[data-expandable]', function() {
  //Do this on focus, to allow textarea to animate to height...
  this.style.removeProperty('height');
  this.style.height = (this.scrollHeight+2) + 'px';
});
/* function startmycal(id){
	$('#startdatetimepicker'+id+'').datetimepicker({format:'YY-MM-DD HH:mm'});
} */
/*
function enddatemycal(id){
	setTimeout(
	$('#enddatetimepicker'+id+'').datetimepicker({format:'YY-MM-DD HH:mm'}),1000);
} */ 

    $(function () {
        @foreach($client_data->Habit as $habit)
            $('#startdatetimepicker{{ $habit->id }}').datetimepicker({format:'YY-MM-DD HH:mm'});
            $('#enddatetimepicker{{ $habit->id }}').datetimepicker({format:'YY-MM-DD HH:mm'});
        @endforeach
    }); 
</script>

<script>

    $('.updategoalstatus').on('click', function() {
        $.ajax({
            type: "POST",
            url: "{{route('coach.updategoalstatus')}}",
            data: {
                _token: "{{ csrf_token() }}",
                id: $(this).val()
            },
            success: function(res) {
                
                if(res.success == true)
                {
                    $("#tab2").addClass("active");

                    getgoal();

                    if(res.goalstatus == 1)
                    {
                        $("#goalupdate").modal('show');
                    }
                }
                else
                {
                    
                }

            }
        });
    });
$(document).ready(function(){
        $.ajax(
        {
            type: "POST",
            url: "{{route('coach.gaolscore')}}",
            data: {
                _token: "{{ csrf_token() }}",
                'client_id':$('#client_id').val(),
            },
            success: function(res)
            {   
                $("#goalper").html(''+res.data+' % Completed'); 
            }
        });
});

    function getgoal()
    {
        $.ajax(
        {
            type: "POST",
            url: "{{route('coach.gaolscore')}}",
            data: {
                _token: "{{ csrf_token() }}",
                'client_id':$('#client_id').val(),
            },
            success: function(res)
            {   
                $("#goalper").html(''+res.data+' % Completed'); 
            }
        });
    }
	
</script>
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-database.js"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>

<script>
/* real time message */
@php
	$user = Auth::user();
	if($user){
	$user_id = $user->id;
	$client_id = $client_data->client_id;
	$chatroom_id='';
		//$chatroom = \App\Models\ChatRooms::whereCoachId($user_id)->whereClientId($client_id)->get();
		if (\App\Models\ChatRooms::whereCoachId($user->id)->whereClientId($client_id)->count() > 0){
					$chatroom = \App\Models\ChatRooms::whereCoachId($user_id)->whereClientId($client_id)->first();
					$chatroom_id = $chatroom->id;
				
				} else {
					$createchatroom = \App\Models\ChatRooms::make();
					$createchatroom->coach_id = $user_id;
					$createchatroom->client_id = $client_id;
					$createchatroom->save();
					$chatroom_id =$createchatroom->id;
			}
				
	}
	
@endphp
// Your web app's Firebase configuration
function mymessage(){
	$('#clientmessage').modal('show'); 
	setTimeout(() => {
		var height = $('#chat').height();
		
		$('#modal-message').scrollTop(height);
	}, 2000);

	
}
$(document).ready(function(){
const firebaseConfig = {

  apiKey: "AIzaSyCDUZZYqPaWx_F0w3lPWp4nFGRAtSpMzjw",

  authDomain: "life-canon.firebaseapp.com",

  databaseURL: "https://life-canon-default-rtdb.firebaseio.com",

  projectId: "life-canon",

  storageBucket: "life-canon.appspot.com",

  messagingSenderId: "56454234316",

  appId: "1:56454234316:web:7c8f180d62556a7ef44269",

  measurementId: "G-69QDKC62N7"

};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// initialize database
const db = firebase.database();

// get user's data
//const username = prompt("Please Tell Us Your Name");

// submit form
// listen for submit event on the form and call the postChat function
document.getElementById("message-form").addEventListener("submit", sendMessage);
@php $user_id = Auth::user()->id; @endphp;
 const user = {{ $user_id }};
  var room_id = {{ $chatroom_id }}; 
// send message to db
function sendMessage(e) {
  e.preventDefault();

  // get values to be submitted
  const timestamp = Date.now();
  const messageInput = document.getElementById("message-input");
  const text = messageInput.value;
  

  // clear the input box
  messageInput.value = "";

  //auto scroll to bottom
  document
    .getElementById('chat')
    .scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start'  });
	
	/* var objDiv = document.getElementById('chat');
	objDiv.scrollTop = objDiv.scrollHeight; */
  // create db collection and send in the data


//    var getts = db.ServerValue.TIMESTAMP;
	

 db.ref("messages").push({
    room_id,
    text,
	timestamp,
	user
  }); 
}

// display the messages
// reference the collection created earlier
const fetchChat = db.ref("messages/");

// check for new messages using the onChildAdded event listener
fetchChat.on("child_added", function (snapshot) {
  const messages = snapshot.val();
  /* var dtime = new Date(messages.timestamp);
  var dtime = dtime.toString();
  var dtime = dtime.split('GMT')[0]; */
   var dtime = new Date(messages.timestamp);
  var dtime2 = moment(messages.timestamp).format("ddd, MMMM D, YYYY");
  if((user === messages.user) && (messages.room_id==room_id) ){
	  
	  
  const message = `<div class=${
    user === messages.user ? "sent-txt send" : "receive"
  }><p>${messages.text}<span>${dtime2}</span></p></div>`;
  // append the message on the page
  document.getElementById("messages").innerHTML += message;
}
});
 
  
});
</script>
@stop