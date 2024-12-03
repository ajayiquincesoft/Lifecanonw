@extends('user.layout.app')
<style>
#clienthabitcal .calendar{background: #009C00 !important;}
#clienthabitcal .calendar table {
	font-size: 10.5px;
	color: #fff;
}
#clientapp2 .calendar table {
	font-size: 12.5px;
	margin: 20px 0 0;
	width: 100%;
	color: #fff;
}
.bootstrap-datetimepicker-widget .collapse {
	padding: 5px;
	width: 100%;
}
#habitmyModal2 .modal-dialog{max-width: 600px}
#habitmyModal2 .modal-content{max-height: 600px}
#AddappointmentModal .modal-dialog {max-width: 660px;}
#AddappointmentModal .modal-content {height: 600px;}
.greeninput .disabled-checkbox{
  opacity:1;
  pointer-events: none;
  color:#006700;
}
.habit-table strong {
	font-size: 20px;
	font-family: SF-Pro-Semibold;
	font-weight: normal;
}
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
				<a class="back-btn" href="/"><i class="fa fa-chevron-left"></i> Back</a>
			</div>
		</div>
        <div class="row">
            <div class="col-lg-4 col-md-12 ss">
                @include('user.dashboardleftsidebar2')

                <input type="hidden" id="coachIdVal" value="{{ $coachdata->id }}"/> 
            </div><!-- col end here -->

            <div class="col-lg-8 col-md-12">
                <div class="dash-coach">
                     <ul class="coach-pro">                       
                        <li class="active"><img src="{{url($coachdata->profile_pic)}}"> <span>{{$coachdata->name}}</span></li>  

                        <li> @if(session('success'))
                          <div class="alert alert-success">{{session('success')}}</div>
                         @endif
                         @if(session('errors'))
                          <div class="alert alert-danger">{{session('Errors')}}</div>
                         @endif
                         </li>                     
                    </ul>
                    <div class="view-pro">
                        <h2>{{$coachdata->name}} <button data-toggle="modal" data-target="#myModal-1" type="button">View Profile <img src="{{asset('assets/front-assets/images/arrow.png')}}"></button></h2>
                        <a href="#" data-toggle="modal" data-target="#AddappointmentModal" onclick='return confirm("Are you sure you want to add an appointment?")'>Schedule Appointment</a>
                    </div><!-- view-pro end here -->

                    <ul class="nav nav-tabs js-example" role="tablist">
                        <li><a class="{{ (session('activeTab') == '')?'active':'' }}" href="#tab1" role="tab" data-toggle="tab">Habit Lists</a></li>
                        <li><a href="#tab2" class="{{ (session('activeTab') == 'journal')?'active':'' }}" role="tab" data-toggle="tab">Journal</a></li>
                        <li><a href="#tab3" class="{{ (session('activeTab') == 'goals')?'active':'' }}" role="tab" data-toggle="tab">Goals</a></li>
                        <li><a href="#tab4" class="{{ (session('activeTab') == 'message')?'active':'' }}" role="tab" data-toggle="tab" id="client_message">Messages</a></li>
                       
                    </ul><!-- ul end here -->

                    <div class="tab-content">
                        <div id="tab1" class="tab-pane {{ (session('activeTab') == '')?'active':'' }}">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 dd-1">
                                    <div id="clienthabitcal" class="calendar-container">
                                        
                                    </div> 
									<?php if($coachdata->status==1){?>
                                    <button data-toggle="modal" data-target="#habitmyModal2" class="new-btn" type="button">Add New Item</button>
									<?php } ?>
                                </div>
                                <div class="col-lg-8 col-md-8">
                                    <div class="habit-table" style="overflow-x:auto;" id="clienthlist">
                                        <!-- table end here -->
                                    </div><!-- habit-table end here -->
                                </div><!-- col end here -->
                            </div><!-- row end here -->
                        </div><!-- tab1 end here -->

                        <div id="tab2" class="tab-pane {{ (session('activeTab') == 'journal')?'active':'' }}">
                            <div id="accordion" class="jour-txt">
							<?php if($coachdata->status==1){?>
                                <button data-toggle="modal" data-target="#journalModal" class="new-btn" type="button">Add Journal</button>
							<?php } ?>
                                @foreach($journal as $journaldata)
                                   <div class="card">
                                       <div class="card-header">
                                           <h5>{{date('F j, Y',strtotime($journaldata->date_time))}}</h5> <a data-toggle="collapse" href="#collapse-4{{ $journaldata->id }}"><?php if($journaldata->images){?><img src="{{asset('/')}}{{ $journaldata->images }}" width="50" height="50" style="margin-right:10px;"><?php }?><?php echo html_entity_decode(substr($journaldata->description, 0, 50));?></a>
                                       </div>
                                       <div id="collapse-4{{ $journaldata->id }}" class="collapse" data-parent="#accordion">
                                           <div class="card-body">
                                               <p><?php echo html_entity_decode($journaldata->description);?></p>
                                           </div><!-- card-body here -->
                                       </div><!-- collapse-1 end here -->
                                   </div><!-- card end here -->
                                @endforeach 
                            </div><!-- accordion end here -->
                        </div><!-- tab2 end here -->

                        <div id="tab3" class="tab-pane {{ (session('activeTab') == 'goals')?'active':'' }}">
                            <div class="goal-txt">
                                <img src="{{asset('assets/front-assets/images/LC-Canon-Logo-01-inner.png')}}" width="80">
                                <h5 id="goalper"></h5>
								<?php if($coachdata->status==1){?>
                                <button data-toggle="modal" data-target="#myModal-4" class="new-btn" type="button">Add New Goal</button>
								<?php }?>
                            </div><!-- goal-txt end here -->

                            <div class="habit-table" style="overflow-x:auto;">
                                <table>
                                    @foreach($goals as $goal)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{$goal->id}}" class="updategoalstatus greeninput" {{($goal->status==1)?'checked':''}}>&nbsp;&nbsp; {{$goal->name}}
                                         <input id="client_id" class="box" type="hidden" name="client_id" value="{{ $coachdata->id }}">
                                        </td>
                                       <?php if($coachdata->status==1){?> <td><a data-toggle="modal" data-target="#myModal-{{$goal->id}}" href="#"><i class="fa fa-pencil-square-o"></i></a></td><?php } ?>
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
                                                                <input class="box" type="hidden" name="client_id" value="{{ $coachdata->id }}">
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
                                </table>
                            </div><!-- habit-table end here -->
                        </div><!-- tab3 end here -->

                        <div id="tab4" class="tab-pane {{ (session('activeTab') == 'remainders')?'message':'' }}">
							<div id="chat" class="clt-chat"> 
							 <h2 style="padding: 22px 6px;font-size: 15px;color:#006700;">Message From
							 {{$coachdata->name}}</h2>
								<div id="messages"></div>
							</div>
                        </div><!-- tab4 end here -->
                    </div><!-- tab-content end here -->
                </div>
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->

    <div class="modal fade coach-popup dd" id="myModal-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Coach Profile</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body">
                    <div class="pro-img"><img src="{{url($coachdata->profile_pic)}}">
                        <h5>{{$coachdata->name}}</h5>
                    </div>
                    <div class="pro-txt">
                        <p><span>Experience</span> {{$coachdata->experience}}</p>
                        <p><span>Area of Expertise</span>{{$coachdata->area_of_expertise}}</p>
                        <p><span>Email</span> {{$coachdata->email }}</p>
                        <p><span>About</span> {{$coachdata->description}}</p>
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
                                <input id="client_id" class="box" type="hidden" name="client_id" value="{{ $coachdata->id }}">
                           </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>Start</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" id="habit_item_start_date" name="start_date" placeholder="Enter start date"/>
                           </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label>End</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control mb-3" id="habit_item_end_date" name="end_date" placeholder="Enter the end date" />
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

    <div class="modal fade coach-popup" id="AddappointmentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <h2>Add Appointment</h2>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-body" style="padding:20px;">
                    <form autocomplete="off" class="require-validation" action="{{route('client.addappointment')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
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
                                   <!-- <ul class="donate-now">
                                        @php
                                           for($i = 0; $i < count($availabdata['days']); $i++)
                                           {
                                        @endphp
                                                <li>
                                                    <input type="radio" id="html{{$i}}" name="app_days" value="{{ $availabdata['days'][$i] }}" onclick="gettime();"/>
                                                    <label for="html{{$i}}">{{ $availabdata['days'][$i] }}</label>
                                                </li>
                                         @php   
                                            }
                                        @endphp
                                    </ul> -->

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

                            <div class="row" id="repeatdiv" style="display:none;">
                               <div class="col-md-3">
                                  <label>End Date</label>
                               </div>

                               <div class="col-md-9" style="padding: 0px;">
                                    <input type="hidden" name="user_id" id="user_id" value="{{ $availabdata['user_id'] }}">
                                  <input type="text" class="form-control mb-3" id="datetim3" name="end_date" placeholder="Enter the end date" />
                               </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="log-btn" type="button" onclick="getpayment();">Save</button>
                                </div>
                            </div> 
                        </div>

                        <div id="paymentdetails" style="display:none">
                            <div class="row">
                                <div class="col-md-4" style="text-align:left; padding:5px;">
                                   <label>Name*</label>
                                </div>
                               
                                <div class="col-md-8" style="padding: 0px;">
                                   <input class="form-control" type='text' name="customer-name" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4" style="text-align:left; padding:5px;">
                                   <label>Card Number*</label>
                                </div>
                               
                                <div class="col-md-8" style="padding: 0px;">
                                   <input class="form-control" id="card-number" name="card-number" type='text' required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4" style="text-align:left; padding:5px;">
                                   <label>CVC*</label>
                                </div>
                               
                                <div class="col-md-8" style="padding: 0px;">
                                   <input class="form-control" id="card-cvc" name="card-cvc" type='text' required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4" style="text-align:left; padding:5px;">
                                   <label>Expiration Month*</label>
                                </div>
                               
                                <div class="col-md-8" style="padding: 0px;">
                                   <input class="form-control" id="card-expiry-month" name="card-expiry-month" type='text' required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4" style="text-align:left; padding:5px;">
                                   <label>Expiration Year*</label>
                                </div>
                               
                                <div class="col-md-8" style="padding: 0px;">
                                    <input type='hidden' class="form-control" id="payment_for" name="payment_for" value="4">
                                    <input class="form-control" id="card-expiry-year" name="card-expiry-year" type='text' required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" style="padding: 0px;">
                                   <button class="log-btn" type="submit">Pay</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div><!-- modal end here -->

    <div class="modal fade jour-popup" id="journalModal">
        <div class="modal-dialog">
            <div class="modal-content">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <form autocomplete="off" action="{{route('client.journal')}}" method="post" id="" enctype="multipart/form-data">
                 @csrf
                 <div class="modal-body">
                     <div class="in-box">
                         <h5>New Journal</h5> 

                         <textarea class="box description" type="text" name="description" placeholder="" rows="12"></textarea>
                         <input class="box" type="hidden" name="user_id" value="{{ $coachdata->id }}">
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
                            <input class="box" type="hidden" name="client_id" value="{{ $coachdata->id }}">
                        </div>
                        <button type="submit" class="log-btn">Save</button>
                    </form>
                </div><!-- modal-body end here -->
            </div><!-- modal-content end here -->
        </div><!-- modal-dialog end here -->
    </div>

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
</div><!-- dash-sec end here -->
<div class="modal fade coach-popup" id="updatehabititem">
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
function complete_habit(id,habityr,habitm,habitdte){
	//console.log(habityr);
	//console.log(habitm);
	//console.log(habitdte);
	//console.log(id);
	if ($('input:checkbox[name=habititem]').is(':checked')) {
		$.ajax(
			{
				type: "POST",
				url: "{{route('UpdateHabitItemStatus')}}",
				data: {
					_token: "{{ csrf_token() }}",
					'id':id,
					'year':habityr,
					'month':habitm,
					'days':habitdte,
					
				},
				success: function(res)
				{   
					if(res){
						$("#updatehabititem").modal('show');
					}
				}
			});
	}else{
		$.ajax(
			{
				type: "POST",
				url: "{{route('UpdateHabitItemStatusOne')}}",
				data: {
					_token: "{{ csrf_token() }}",
					'id':id,
					'year':habityr,
					'month':habitm,
					'days':habitdte
				},
				success: function(res)
				{   
					if(res){
						$("#updatehabititem").modal('show');
					}
				}
			});
	} 
/* 	 $('input:checkbox[name=habititem]:checked').each(function() 
	{
	$.ajax(
        {
            type: "POST",
            url: "{{route('UpdateHabitItemStatus')}}",
            data: {
                _token: "{{ csrf_token() }}",
                'id':id,
            },
            success: function(res)
            {   
                if(res){
					$("#updatehabititem").modal('show');
				}
            }
        });
	});  */
}
    onloadclienthabit();

    function deletehabit()
    {
        alert("Are you sure want to delete");
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

    function getpayment()
    {
        var app_time = $("input[name='app_time']:checked").val();
        var repeat = $("#repeat").val();
        var datetim3 = $("#datetim3").val();

        if(app_time == "" || app_time == null)
        {
            $(".alert-danger").show();
            $(".alert-danger").html('<ul><li>The Appointment Time field is required</li></ul>');
        }
        else if(repeat == "" || repeat == null)
        {
            $(".alert-danger").show();
            $(".alert-danger").html('<ul><li>The Repeat field is required</li></ul>');
        }
        else if(repeat == "1")
        {
            if(datetim3 == "" || datetim3 == null)
            {
                $(".alert-danger").show();
                $(".alert-danger").html('<ul><li>The End Date field is required</li></ul>');
            }

            else
            {
                $(".alert-danger").hide();
                $("#appintmentdetails").hide();
                $("#paymentdetails").show();
            }
        }
        else
        {
            $(".alert-danger").hide();
            $("#appintmentdetails").hide();
            $("#paymentdetails").show();
        }
    }

    $(document).ready(function() 
    {
        let container = $("#clientapp2").simpleCalendar({
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
                $('#clientapp2 table tr td div').removeClass("today");

                $("#clientapp2 #"+text).addClass("today");

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

                            $("#coachappointment").append('<li data-toggle="modal" data-target="#appointmentdetails" onclick="appointmentdetails(\'' + appoint_id + '\',\'' + newDate + '\');"><a style="cursor:pointer;"><img src="{{asset("/")}}'+ profile +'"><p>'+client_name+'<span>'+time+'</span></p></a></li>');
                        }
                    }
                });
            }
        });
    });

    $(document).ready(function() 
    {
        var container = $("#clienthabitcal").simpleCalendar({
            //fixedStartDay: 0,
            disableEmptyDetails: true,
            events: [
                @php
                    echo $totaldt2;
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
                $('#clienthabitcal table tr td div').removeClass("today");

                $("#clienthabitcal #"+text).addClass("today");

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
				var habityr = yyyy;
				var habitm = mm;
				var habitdte = dd;
				//console.log('hello');
                //console.log(newDate);
                $.ajax(
                {
                    type: "POST",
                    url: "{{route('client.listhabits')}}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        'habit_date': newDate,
                        'client_id':$('#coachIdVal').val(),
                    },
                    success: function(res)
                    {   
                        $("#clienthlist").html('');

                        if(res.data.length > 0)
                        {
                            $("#clienthlist").append(newDate2);
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
							//console.log('hellodsfdf');
							//console.log('Select Date-'+newDate);
							
							//console.log('select date -'+selectdate2);
                            /* if(status==2 && newDate == selectdate2)
                            {
                                var chkbox ='<input onclick="complete_habit('+id+')" type="checkbox" value="" checked>';
                            }
                            else
                            {
                                if(newDate < selectdate2)
                                {
                                    var chkbox ='<input type="checkbox" value='+ id +' checked disabled>';
                                }
                                else if(newDate == selectdate2)
                                {
                                    var chkbox ='<input type="checkbox" value='+ id +' onclick="complete_habit('+id+')" name="habititem">';
                                }else{
									var chkbox ='<input type="checkbox" value='+ id +'  name="habititem" disabled>';
								}
                            } */
							
							if(status==1)
							{
								var chkbox ='<input onclick="complete_habit('+id+','+habityr+','+habitm+','+habitdte+')" type="checkbox" value="" checked >';
							}
							else
							{
								var chkbox ='<input type="checkbox" value='+ id +' onclick="complete_habit('+id+','+habityr+','+habitm+','+habitdte+')" name="habititem">';
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
                            
                            var frm ='<form autocomplete="off" action="{{route('coach.updatehabit')}}" method="post" id="" enctype="multipart/form-data">@csrf<h3>'+ title +'<button class="cl-btn" type="submit" style="float: right;margin-top: -7px;">Save</button></h3><ul class="wdays"><li><div class="row"><div class="col-md-4"><label>Title</label></div><div class="col-md-8"><input type="text" class="form-control mb-3"  name="habit_name" value="'+ title +'"/><input class="box" type="hidden" name="id" value="'+ id +'"><input class="box" type="hidden" name="client_id" value="'+ user_id +'"></div></div></li><li><div class="row"><div class="col-md-4"><label>Start</label></div><div class="col-md-8"><input type="text" class="form-control mb-3" name="start_date" value="'+ stardate +'" readonly/></div></div></li><li><div class="row"><div class="col-md-4"><label>End</label></div><div class="col-md-8"><input type="text" class="form-control mb-3"  name="end_date" value="'+ endtime +'" readonly/></div></div></li><li><div class="row"><div class="col-md-4"><label>No.of Weekly Cycles</label></div><div class="col-md-8"><input type="text" value="'+weekcycle+'" name="number_of_session" class="form-control mb-3" readonly></div></div></li><li><div class="row"><div class="col-md-4"><label>Alert</label></div><div class="col-md-8"><select class="form-control mb-3" name="alert" style="width:100%;border: 1px solid #ccc;padding: 2px 9px;"><option value="5 Minutes Before" '+ alert1 +'>5 Minutes Before</option><option value="10 Minutes Before" '+ alert2 +'>10 Minutes Before</option><option value="15 Minutes Before" '+ alert3 +'>15 Minutes Before</option><option value="30 Minutes Before" '+ alert4 +'>30 Minutes Before</option><option value="1 hour Before" '+ alert5 +'>1 hour Before</option><option value="2 hour Before" '+ alert6 +'>2 hour Before</option><option value="1 day Before" '+ alert7 +' >1 day Before</option></select></div></li><li><div class="row"><div class="col-md-4"><label>Select Weekdays</label></div><div class="col-md-8  "><div class="row">'+res.data[i].weekdaysHtml+'</div></div></div></li><li><button class="cl-btn" type="submit">Save</button></form></li></ul><ul><li><form autocomplete="off" action="{{route('coach.deletehabit')}}" method="post" id="user-registration-form" enctype="multipart/form-data">@csrf<input class="box" type="hidden" name="client_id" value="'+ client_id +'"><input class="box" type="hidden" name="id" value="'+ id +'"><input class="box" type="hidden" name="user_id" value="'+ user_id +'"><button style="float: left;margin-top: 0px;" class="del-btn" type="submit" onclick="deletehabit();">Delete Item</button></form></li></ul>';
                            
                            $("#clienthlist").append('<table><tr><td style="width:60%" class="greeninput">'+ chkbox +' &nbsp;<strong> '+ title +'</strong></td><td>'+ startime +'<br>'+ endtime +'</td><td><a data-toggle="collapse" href="#collapse'+ id +'"><i class="fa fa-pencil-square-o"></i></a></td></tr> <tr id="collapse'+ id +'" class="collapse"><td>'+ frm +'</td></tr></table>');
                        }
                    }
                });
            }
        });
    });

    function onloadclienthabit()
    {
        var date = new Date();

        var dd = date.getDate();
        var mm = date.getMonth() + 1;
        var yyyy = date.getFullYear();
        var fullmonth = date.toLocaleString('default', { month: 'long' });
		var habityr = yyyy;
		var habitm = mm;
		var habitdte = dd;

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
        console.log(newDate);
		console.log(newDate2);
        //alert(newDate);
        $.ajax(
        {
            type: "POST",
            url: "{{route('client.listhabits')}}",
            data: {
                _token: "{{ csrf_token() }}",
                'habit_date': newDate,
                'client_id':$('#coachIdVal').val(),
            },
            success: function(res)
            {   
                //console.log(res.data.length);

                $("#clienthlist").html('');

                if(res.data.length > 0)
                {
                    $("#clienthlist").append(newDate2);
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

                 /* if(status==2 && newDate == selectdate2)
                    {
                        var chkbox ='<input onclick="complete_habit('+id+')" type="checkbox" value="" checked>';
                    }
                    else
                    {
						if(newDate < selectdate2)
                          {
                              var chkbox ='<input type="checkbox" value='+ id +' checked disabled>';
                           }
                           else if(newDate == selectdate2)
                            {
                              var chkbox ='<input type="checkbox" value='+ id +' onclick="complete_habit('+id+')" name="habititem">';
                            }else{
									var chkbox ='<input type="checkbox" value='+ id +'  name="habititem" disabled>';
							}
                    } */
					
					if(status==1)
					{
						var chkbox ='<input onclick="complete_habit('+id+','+habityr+','+habitm+','+habitdte+')" type="checkbox" value="" checked>';
					}
					else
					{
						var chkbox ='<input type="checkbox" value='+ id +' onclick="complete_habit('+id+','+habityr+','+habitm+','+habitdte+')" name="habititem">';
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
                    
                    var frm ='<form autocomplete="off" action="{{route('coach.updatehabit')}}" method="post" id="" enctype="multipart/form-data">@csrf<h3>'+ title +'<button class="cl-btn" type="submit" style="float: right;margin-top: -7px;">Save</button></h3><ul class="wdays"><li><div class="row"><div class="col-md-4"><label>Title</label></div><div class="col-md-8"><input type="text" class="form-control mb-3"  name="habit_name" value="'+ title +'"/><input class="box" type="hidden" name="id" value="'+ id +'"><input class="box" type="hidden" name="client_id" value="'+ user_id +'"></div></div></li><li><div class="row"><div class="col-md-4"><label>Start</label></div><div class="col-md-8"><input type="text" class="form-control mb-3" name="start_date" value="'+ stardate +'" readonly/></div></div></li><li><div class="row"><div class="col-md-4"><label>End</label></div><div class="col-md-8"><input type="text" class="form-control mb-3" id="enddatetimepicker'+ id +'" name="end_date" value="'+ endtime +'" readonly/></div></div></li><li><div class="row"><div class="col-md-4"><label>No.of Weekly Cycles</label></div><div class="col-md-8"><input type="text" value="'+weekcycle+'" name="number_of_session" class="form-control mb-3" readonly></div></div></li><li><div class="row"><div class="col-md-4"><label>Alert</label></div><div class="col-md-8"><select class="form-control mb-3" name="alert" style="width:100%;border: 1px solid #ccc;padding: 2px 9px;"><option value="5 Minutes Before" '+ alert1 +'>5 Minutes Before</option><option value="10 Minutes Before" '+ alert2 +'>10 Minutes Before</option><option value="15 Minutes Before" '+ alert3 +'>15 Minutes Before</option><option value="30 Minutes Before" '+ alert4 +'>30 Minutes Before</option><option value="1 hour Before" '+ alert5 +'>1 hour Before</option><option value="2 hour Before" '+ alert6 +'>2 hour Before</option><option value="1 day Before" '+ alert7 +' >1 day Before</option></select></div></li><li><div class="row"><div class="col-md-4"><label>Select Weekdays</label></div><div class="col-md-8  "><div class="row">'+res.data[i].weekdaysHtml+'</div></div></div></li><li><button class="cl-btn" type="submit">Save</button></form></li></ul><ul><li><form autocomplete="off" action="{{route('coach.deletehabit')}}" method="post" id="user-registration-form" enctype="multipart/form-data">@csrf<input class="box" type="hidden" name="client_id" value="'+ client_id +'"><input class="box" type="hidden" name="id" value="'+ id +'"><input class="box" type="hidden" name="user_id" value="'+ user_id +'"><button style="float: left;margin-top: 0px;" class="del-btn" type="submit" onclick="deletehabit();">Delete Item</button></form></li></ul>';
                    
                    $("#clienthlist").append('<table><tr><td style="width:60%" class="greeninput">'+ chkbox +' &nbsp;<strong> '+ title +'</strong></td><td>'+ startime +'<br>'+ endtime +'</td><td><?php if($coachdata->status==1){?><a data-toggle="collapse" href="#collapse'+ id +'"><i class="fa fa-pencil-square-o"></i></a><?php } ?></td></tr> <tr id="collapse'+ id +'" class="collapse"><td>'+ frm +'</td></tr></table>');
                }
            }
        });
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
                    number: $('#card-number').val(),
                    cvc: $('#card-cvc').val(),
                    exp_month: $('#card-expiry-month').val(),
                    exp_year: $('#card-expiry-year').val()
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
                $form.append("<input type='hidden' name='stripe_token' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    });

    $('.updategoalstatus').on('click', function() 
    {
        $.ajax(
        {
            type: "POST",
            url: "{{route('coach.updategoalstatus')}}",
            data: 
            {
                _token: "{{ csrf_token() }}",
                id: $(this).val()
            },
            success: function(res) 
            {
                if(res.success == true)
                {
                    $("#tab3").addClass("active");

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

    $(document).ready(function()
    {
        $.ajax(
        {
            type: "POST",
            url: "{{route('coach.gaolscore')}}",
            data: 
            {
                _token: "{{ csrf_token() }}",
                'client_id':$('#coachIdVal').val(),
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
            data: 
            {
                _token: "{{ csrf_token() }}",
                'client_id':$('#coachIdVal').val(),
            },
            success: function(res)
            {   
                $("#goalper").html(''+res.data+' % Completed'); 
            }
        });
    }

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

        var reappoint = '';

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
                'client_id':$('#coachIdVal').val(),
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
                var schedule_by	= res.data.schedule_by;
				if(schedule_by=='freeByCoach'){
                $("#getappdet").html('<table><tr><td><img src="{{asset("/")}}'+ profile +'" style="border-radius:50%; height:44px; width:44px;"></td><td><p style="color:#023a02; font-family:SF-Pro-Medium; font-size:14px; margin:0 0 0 28px; text-align:left; font-weight: 500;">'+client_name+'<br>$ '+appointment_fees+' / apt</p></td></tr></table><hr>'+upcomming+'<span style="float:left">'+get_appoint_date+'</span><br><span style="float:left">'+appointment_time+'</span><br><br><span style="float:left">Appointment with  '+client_name+'</span><br><br><div style="border-top:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding-top:10px; padding-bottom:33px;"><span style="float:left; font-size:18px; font-weight:600;">Total</span><span style="float:right; font-size:18px; font-weight:600; color:#024602;">$ '+ap_amount+'</span></div><br>'+reappoint+'');
				}else{
					 $("#getappdet").html('<table><tr><td><img src="{{asset("/")}}'+ profile +'" style="border-radius:50%; height:44px; width:44px;"></td><td><p style="color:#023a02; font-family:SF-Pro-Medium; font-size:14px; margin:0 0 0 28px; text-align:left; font-weight: 500;">'+client_name+'<br>$ '+appointment_fees+' / apt</p></td></tr></table><hr>'+upcomming+'<span style="float:left">'+get_appoint_date+'</span><br><span style="float:left">'+appointment_time+'</span><br><br><span style="float:left">Payment will be made on '+payment_date+'</span><br><br><div style="border-top:1px solid #e5e5e5; border-bottom:1px solid #e5e5e5; padding-top:10px; padding-bottom:33px;"><span style="float:left; font-size:18px; font-weight:600;">Total</span><span style="float:right; font-size:18px; font-weight:600; color:#024602;">$ '+ap_amount+'</span></div><br>'+reappoint+'');
					
				}
            }
        });
    }
	
	
</script>

<!-------message function-------->
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-database.js"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<!--real time message--->
@php
	$user = Auth::user();
	if($user){
	$client_id = $user->id;
	$coach_id = $coachdata->id;
		//$chatroom = \App\Models\ChatRooms::whereCoachId($coach_id)->whereClientId($client_id)->get();
		if (\App\Models\ChatRooms::whereCoachId($coach_id)->whereClientId($client_id)->count() > 0){
					$chatroom = \App\Models\ChatRooms::whereCoachId($coach_id)->whereClientId($client_id)->first();
					$chatroom_id = $chatroom->id;
				} else {
					$createchatroom = \App\Models\ChatRooms::make();
					$createchatroom->coach_id = $coach_id;
					$createchatroom->client_id = $client_id;
					$createchatroom->save();
					$chatroom_id =$createchatroom->id;
			}		
	}
@endphp
<script>

// Your web app's Firebase configuration

$(document).ready(function(){
$('#client_message').click(function(){
     $("html, body").animate({ scrollTop: $(document).height() }, 1000);
});	
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
// listen for submit event on the form and call the postChat function
 const user = {{ $coach_id }};
  var room_id = {{ $chatroom_id }}; 
// send message to db
// display the messages
const fetchChat = db.ref("messages/").limitToLast(100);

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

<!----------End message functions ---->	
@stop