<div class="dash-sch">
	<div class="row">
		<div class="col-md-10">
		<h2>{{$client_data->client->name}} Appointments</h2>
		</div>
		<div class="col-md-2">
			<?php if($client_data->status==1){?>
			<img src="https://lifecanon.com/assets/images/plus.png" class="img-responsive" title="Are you sure you want to add a FREE appointment with your client?  To charge your client for this appointment please have your client set up the appointment on his phone." alt="Are you sure you want to add a FREE appointment with your client?  To charge your client for this appointment please have your client set up the appointment on his phone.  " data-toggle="modal" data-target="#AddappointmentModal1" width="25">
			<?php } ?>
		</div>
	</div>
    <div id="coachapp2" class="calendar-container"></div>
    <ul id="showdlt">
        
        <!-- <li><a href="#"><img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                <p>John Cena <span>3:00 PM - 4:00 PM</span></p>
            </a></li>
        <li><a href="#"><img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                <p>John Cena <span>3:00 PM - 4:00 PM</span></p>
            </a></li>
        <li><a href="#"><img src="{{asset('assets/front-assets/images/our-img.jpg')}}">
                <p>John Cena <span>3:00 PM - 4:00 PM</span></p>
            </a></li> -->
        
    </ul><!-- ul end here -->
</div><!-- dash-sch end here -->
