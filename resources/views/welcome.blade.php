@extends('layouts.app')

@section('content')
<div class="main">
   <div class="container">
      <div class="row">
         <div class="col-lg-2"></div>
         <div class="col-lg-3 col-md-5" data-aos="fade-right"><img width="100%" src="{{asset('public/assets/front-assets/images/img.png')}}"></div>
         <div class="col-lg-5 col-md-7" data-aos="fade-up">
            <h1 style="font-size: 30px;">Welcome to LIFE CANON a Coaching App Designed by Life Coach Poul for All Coaches</h1>
            <p>
			<ul style="list-style: disc;padding-left: 29px;">
				<li>Set up clients on auto pay</li>
				<li>Set Client Goals</li>
				<li>Break down goals with new habit tasks & see in real time</li>
				<li>Set reminders & alerts</li>
				<li>Client Journals you can see in real time as a coach</li>
				<li>Coaches Notes for each client</li>
				<li>Appointment set up for new clients to choose</li>
				<li>And more...</li>
			</ul>
			</p>
            <a class="log-btn" href="#work-sec">Tell Me More</a> <a class="sign-btn" href="user-signup">Sign Me Up</a>
         </div><!-- col end here -->
      </div><!-- row end here -->
   </div><!-- container end here -->
</div><!-- main end here -->


<div class="fea-sec" id="fea-sec">
   <h2>Many Features</h2>
   <ul class="nav nav-tabs js-example" role="tablist">
      <li><a class="active" href="#tab1" role="tab" data-toggle="tab">For Coach</a></li>
      <li><a href="#tab2" role="tab" data-toggle="tab">For Client</a></li>
   </ul><!-- ul end here -->

   <div class="tab-content">
      <div id="tab1" class="tab-pane active">
         <ul class="nav fea-tab js-example" role="tablist" data-aos="fade-right">
            <li><a class="active" href="#tab3" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon.png')}}"> Client Progress</a></li>
            <li><a href="#tab4" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon1.png')}}"> Create client list</a></li>
            <li><a href="#tab5" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon2.png')}}"> Set Up Payments</a></li>
            <li><a href="#tab6" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon3.png')}}"> Session Notes</a></li>
            <li><a href="#tab7" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon4.png')}}"> Client Communications</a></li>
         </ul><!-- ul end here -->

         <div class="tab-content">
            <div id="tab3" class="tab-pane active">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Client Progress</h3>
                           <p>Track your client’s Habit List action items, Goals and journal entries in real time.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab3 end here -->

            <div id="tab4" class="tab-pane">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Create client list</h3>
                           <p>Create new Habit lists for your client with reminders to check off each day.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab4 end here -->

            <div id="tab5" class="tab-pane">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Set Up Payments</h3>
                           <p>Every client will be set up under an auto pay system using a Stripe account.  Extremely secure, with a one time, easy step by step set up.  All your clients have to do is enter their credit card information and agree to your fees.  Then Stripe will take payment from their credit card and deposit it right into your bank account automatically.  It couldn’t be easier.  Life Canon has got you covered.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab5 end here -->

            <div id="tab6" class="tab-pane">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Session Notes</h3>
                           <p>Here you can take session notes or even set up your own questions to ask ahead of time.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab6 end here -->

            <div id="tab7" class="tab-pane">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Client Communications</h3>
                           <p>Send clients a one way message of encouragement between sessions.  We chose a one way message so your client can’t start a chat conversation and use up your unpaid time.  One of our upgrades will include a VIP client message board where you can charge more for VIP clients and they will have more access to you with a 2 way message or text area.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab7 end here -->
         </div><!-- tab-content end here -->
      </div><!-- tab1 end here -->

      <div id="tab2" class="tab-pane">
         <ul class="nav fea-tab js-example" role="tablist">
            <li><a class="active" href="#tab8" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon5.png')}}"> Set Main Goals</a></li>
            <li><a href="#tab9" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon6.png')}}">Set New Habit List Items</a></li>
            <li><a href="#tab10" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon1.png')}}"> Journal</a></li>
           <li><a href="#tab11" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon2.png')}}"> Set Up Payments</a></li><li><a href="#tab12" role="tab" data-toggle="tab"><img src="{{asset('public/assets/front-assets/images/fea-icon4.png')}}">Coach Encouragements</a></li>
         </ul><!-- ul end here -->

         <div class="tab-content">
            <div id="tab8" class="tab-pane active">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Set Main Goals</h3>
                           <p>Set up personal goals with your coach.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab8 end here -->

            <div id="tab9" class="tab-pane">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Set New Habit List Items</h3>
                           <p>Work with your coach and set up habit tasks to do each week.  Tasks will have reminders for your convenience.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab9 end here -->

            <div id="tab10" class="tab-pane">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Journal</h3>
                           <p>Journal each day both good and bad so your coach can see how you are doing in real time and discuss any issues in your next sessions.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab10 end here -->
			<div id="tab11" class="tab-pane">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Set Up Payments</h3>
                           <p>Your coach will set you up on an easy auto pay plan so you both can focus on your goals.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab5 end here -->
            <div id="tab12" class="tab-pane">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-6 col-md-6"><img width="100%" src="{{asset('public/assets/front-assets/images/arrow_fea.jpg')}}"></div>
                     <div class="col-lg-6 col-md-6">
                        <div class="fea-box">
                           <h3>Coach Encouragements</h3>
                           <p>Allow your coach to follow your progress in real time and send you encouragement or helpful tips between sessions.</p>
                        </div><!-- fea-box end here -->
                     </div><!-- col end here -->
                  </div><!-- row end here -->
               </div><!-- container end here -->
            </div><!-- tab11 end here -->
         </div><!-- tab-content end here -->
      </div><!-- tab2 end here -->
   </div><!-- tab-content end here -->
</div><!-- fea-sec end here -->


<div class="work-sec" id="work-sec">
   <div class="container">
      <div class="row">
         <div class="col-lg-2"></div>
         <div class="col-lg-8 col-md-12">
            <h2>How it Works</h2>
            <!-- <h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dictum eleifend amet consectetur massa odio varius sed. In mattis urna, tortor, elit. Morbi sed diam leo enim diam eget tortor turpis sapien. Arcu urna proin sed molestie commodo velit varius.</h6> -->
		<ul class="nav nav-tabs js-example" role="tablist">
		  <li><a class="active" href="#howitwork1" role="tab" data-toggle="tab">For Coach</a></li>
		  <li><a href="#howitwork2" role="tab" data-toggle="tab">For Client</a></li>
	   </ul><!-- ul end here -->
         </div><!-- col end here -->
      </div><!-- row end here -->
	<div class="tab-content">
      <div id="howitwork1" class="tab-pane active">
      <div class="row">
         <div class="col-lg-1"></div>
         <div class="col-lg-10 col-md-12" data-aos="fade-up">
            <div class="row">
               <div class="col-lg-6 col-md-6">
                <p><span>1</span> When you first download and open Life Canon, as a Coach, you will not see very much of the app.  It will look very simple until you set up your first client. That being said you will need to first set up your Stripe account. This will take 10-30 minutes, depending how techie you are. Don’t miss this step or you will not be able to use the app. Don’t worry, your clients will not have to set up a Stripe account. This is only for you to be able to take credit card payments and have those payments go directly into <strong style="text-decoration:underline; font-weight:normal">your personal bank account</strong>. This is a very powerful part of this app.  Think of this as your personal accounting team.  Once you set it up it will run all on it’s own in the background.  48 hours before each client’s session, Stripe will make the transfer to your bank account allowing you to focus on coaching.  How cool is that? </p>
               </div><!-- col end here -->

               <div class="col-lg-6 col-md-6">
               <p><span>2</span> Once you have entered the 6 digit code you receive from your coach just fill out your personal information and your credit card for auto pay for your sessions.  Your Coach will have set up the fees already for you to agree to.</p>
               </div><!-- col end here -->

               <div class="col-lg-6 col-md-6">
                <p><span>3</span> What I do with my new clients in session two or <a href="{{route('firstPaidSession')}}">First Paid Session</a>, I gather their info to add a new client and get a 6 digit code.  As the coach you will need to get your client on the phone and set up your client on Life Canon as a new client. Click Add New Client.  Enter their personal information and set up the auto pay parameters.  You have the option to have them pay you all at once for your sessions or they can auto pay you weekly.  Either way Life Canon will calculate what you enter and automatically process their credit card once they sign up on their end. Next you will get a 6 digit code for your client to use with his/her <strong style="color:#056405;font-weight:normal;">Life Canon app</strong>.  </p>
               </div><!-- col end here -->

               <div class="col-lg-6 col-md-6">
					<p><span>4</span> <strong>Using the app</strong>.There are many features, but not so many you get lost.  We designed it to help you and your coach be successful.  Your coach will guild you but here are some of the main features.</p>
					<ul style="margin-top: -20px;">
							<li style="text-align:left;"><strong>Journal</strong> – Jot down your progress, issues and struggles each day.</li>
							<li style="text-align:left;"><strong>Goals </strong>– List your over all goals and chose one or two to work on with your coach.</li>
							<li style="text-align:left;"><strong>Habit List </strong>– Create small habit changing tasks with your coach along with reminders to move your towards your goals.</li>
							<li style="text-align:left;"></li>
					</ul>
					<p>Changes will happen if you stick with your coach and follow their advise.</p>
               </div><!-- col end here -->

               <div class="col-lg-6 col-md-6">
                <p><span>5</span> Start by asking your clients what their over all goal is.  Make a note of this in your notes. Begin working with your client as you would normally.  When you decide on some action tasks for your client to do this next week, go to the Habit List and set them up with your client along with any reminders they may need for each task. As your client completes a habit they simply check off a square. The client will get an animated encouragement pop up to keep them motivated. You, the coach, will then see when they have checked off an item as well and track their progress.  If your client is not tech savvy you can set all these habits and reminders for them on your end.  As soon as you hit save they will have it.  Encourage your clients to use the Journal tab each day. This will give you both incites to review during your next session as well. </p>
               </div><!-- col end here -->

               <div class="col-lg-6 col-md-6">
                <p><span>6</span> Finally rap up your session and make any changes on your Habit List for next week. Transition from 2-4 clients to easily handle 20+ clients all in one location. Coaching doesn’t need to be complicated. The main thing is to stay on the same page with your client by listening to them, finding the why beneath the why, then supporting their goals with monitored habit changes. They don’t need to go fast as much as they need your support and encouragement.
					</p>
               </div><!-- col end here -->
            </div><!-- row end here -->
         </div><!-- col end here -->
      </div><!-- row end here -->
  </div>
  <div id="howitwork2" class="tab-pane">
      <div class="row">
         <div class="col-lg-1"></div>
         <div class="col-lg-10 col-md-12" data-aos="fade-up">
            <div class="row">
               <div class="col-lg-6 col-md-6">
                <p><span>1</span> Start by downloading the free version of Life Canon in your app store.<strong style="text-decoration:underline">IMPORTANT!  Make sure to <a href="user-signup">sign up </a> as a CLIENT</strong>. Life Canon is available in Android, iPhone and on your PC computer.  Download both on your smart phone and your computer.The two will link together once you enter your 6 digit code from your coach.
				</p>
               </div><!-- col end here -->

               <div class="col-lg-6 col-md-6">
               <p><span>2</span>fill out all your personal information and add a photo of yourself for you coach to see.At the top right tap the My Coach.  Now enter your 6 digit code your coach will give you.
				</p>
               </div><!-- col end here -->

               <div class="col-lg-6 col-md-6">
                <p><span>3</span> Now enter your credit card information and agree to the coaches charges for your sessions together.That's it!  You are now ready to start working with your coach.</p>
               </div><!-- col end here -->

               <div class="col-lg-6 col-md-6">
                <p><span>4</span> <strong>Using the app</strong>. There are many features, but not so many you get lost.  We designed it to help you and your coach be successful.  Your coach will guild you, but here are some of the main features.
				<ul style="margin-top: -20px;">
				<li style="text-align:left;"><strong>Journal</strong> – Jot down your progress, issues and struggles each day.</li>
				<li style="text-align:left;"><strong>Goals </strong>– List your over all goals and chose one or two to work on with your coach.</li>
				<li style="text-align:left;"><strong>Habit List </strong>– Create small habit changing tasks with your coach along with reminders to move towards your goals. Changes will happen if you stick with your coach and follow their advise.</li>
				</ul>
				</p>
               </div><!-- col end here -->

               
            </div><!-- row end here -->
         </div><!-- col end here -->
      </div><!-- row end here -->
  </div>
  </div>
   </div><!-- container end here -->
</div><!-- work-sec end here -->


<div class="spec-sec" id="spec-sec">
   <div class="container" data-aos="fade-down">
      <div class="row">
         <div class="col-lg-3 col-md-2"></div>
         <div class="col-lg-6 col-md-8">
            <h2>Special Features</h2>
            <!-- <h6>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dictum eleifend amet consectetur massa odio varius sed. In mattis urna, tortor, elit. Morbi sed diam leo enim diam eget tortor turpis sapien. Arcu urna proin sed molestie commodo velit varius.</h6> -->
         </div><!-- col end here -->
      </div><!-- row end here -->

      <div class="row">
         <div class="col-lg-1"></div>
         <div class="col-lg-10 col-md-12">
            <div class="row">
               <div class="col-lg-4 col-md-4">
                  <div class="spec-box"><img src="{{asset('public/assets/front-assets/images/spec-icon.png')}}"></div>
                  <p><span>ENGAGE</span> Keep clients engaged between sessions with custom homework exercises, activities, reflections and education.</p>
               </div><!-- col end here -->

               <div class="col-lg-4 col-md-4">
                  <div class="spec-box"><img src="{{asset('public/assets/front-assets/images/spec-icon1.png')}}"></div>
                  <p><span>EDUCATE</span> Teach your clients how to reach your goals is by making small habit changes.</p>
               </div><!-- col end here -->

               <div class="col-lg-4 col-md-4">
                  <div class="spec-box"><img src="{{asset('public/assets/front-assets/images/spec-icon2.png')}}"></div>
                  <p><span>ASSESS</span> Coach you have a powerful tool in that you see your client’s success or failures in real time.  This gives you the ability to assess and make notes before your next session.  You will love this!</p>
               </div><!-- col end here -->
            </div><!-- row end here -->

            <div class="row">
               <div class="col-lg-4 col-md-4">
                  <div class="spec-box-1"><img src="{{asset('public/assets/front-assets/images/spec-icon3.png')}}"></div>
                  <p><span>REAL TIME ACCESS</span> Seeing how your clients do in real time through out the week is so powerful.</p>
               </div><!-- col end here -->

               <div class="col-lg-4 col-md-4">
                  <div class="spec-box-1"><img src="{{asset('public/assets/front-assets/images/spec-icon4.png')}}"></div>
                  <p><span>MESSAGE</span> We designed a one way message you can encourage your client midweek or whenever you want.  Keep your clients motivated throughout the week and not just in your sessions.</p>
               </div><!-- col end here -->

               <div class="col-lg-4 col-md-4">
                  <div class="spec-box-1"><img src="{{asset('public/assets/front-assets/images/spec-icon5.png')}}"></div>
                  <p><span>A GROWING APP</span> We want to hear good feedback.  Tell us your ideas or issues you may come across.  It is our goal to make changes using your input.</p>
               </div><!-- col end here -->
            </div><!-- row end here -->
         </div><!-- col end here -->
      </div><!-- row end here -->
   </div><!-- container end here -->
</div><!-- spec-sec end here -->


<div class="cost-sec" id="cost-sec">
   <div class="container">
      <div class="row">
         <div class="col-xl-3 col-lg-2 col-md-1"></div>
         <div class="col-xl-6 col-lg-8 col-md-10">
            <h2>How much does it cost?</h2>
			<p><strong>As a client you pay nothing!  Your coach will pick up the bill and you will receive a free 6 digit code to use as payment.</strong></p><br>
			
            <h6>Coaches pay a subscription per month of just $14.99 and an additional $8 for each client per month. The benefits far outweighs the cost as now you have a powerful bookkeeper along with a very helpful coaching app to support your growing business. We designed the costs to grow with your business and client base so you don’t have to front a lot of money before you build up clients. When you finish with a client simply turn that client code off  by “Archiving” that client and you stop paying for them.  You can reload clients back into the system as well later if you want to re-install theme again.</h6>
         </div><!-- col end here -->
      </div><!-- row end here -->

      <div class="row" data-aos="fade-down">
         <div class="col-xl-3 col-lg-3 col-md-2"></div>
		@php 
			$i=1;
		@endphp
		 @foreach( $plans as $plan)
         <div class="col-xl-3 col-lg-3 col-md-4">
		  @if($i==1)
            <div class="cost-box">
			@else
			 <div class="cost-box dd">
			@endif
			   <h3><span>{{$plan->name}}</span> <strong>${{$plan->price}}</strong> / {{$plan->name}}</h3>
               <ul>
				<li>Keep all your clients in one easy-to-use app</li>
				<li>Track progress with checklists, journaling, private notes, & more</li>
				<li>Keep your schedule & get paid within the app</li>
				<li>Save {{$plan->save_amount}}/{{$plan->name}}</li>
			   </ul><!-- ul end here -->
               <a class="sign-btn" href="user-login">Sign Me Up</a>
            </div><!-- cost-box end here -->
         </div><!-- col end here -->
		@php 
			$i=$i+1;
		@endphp
		@endforeach
         
      </div><!-- row end here -->
   </div><!-- container end here -->
</div><!-- cost-sec end here -->


<div class="faq-sec">
   <div class="container">
      <div class="row">
      <div class="col-lg-2 col-lg-1"></div>
         <div class="col-lg-8 col-md-10">
            <h2>Frequently asked questions</h2>
         </div><!-- col end here -->
      </div><!-- row end here -->

      <div class="row">
         <div class="col-lg-2 col-lg-1"></div>
         <!--<div class="col-lg-5 col-md-6" data-aos="fade-right">
            <h4>Nnonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation.</h4>
            <p>Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius.</p>
            <p>Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</p>
         </div>--><!-- col end here -->

         <div class="col-lg-8 col-md-10">
            <div id="accordion">
			   
               <div class="card">
                  <div class="card-header"><a data-toggle="collapse" href="#collapse-1" aria-expanded="true">How come I have to pay for my app and my clients?</a></div>
                  <div id="collapse-1" class="collapse show" data-parent="#accordion">
                     <div class="card-body">
                        <p>You as a coach you are providing this application for your clients. It is easily set up and powerful to help you keep track of all your sessions and payments. This is a huge benefit for you the coach and is a tool for your business.  You will want to make sure all your clients are in one location and if you give your clients to option to use the app you will not have all your clients organized together.  When you provide this as part of your service/business then you can keep your entire client list, notes and payment plan automated and organized.</p>
                     </div><!-- card-body here -->
                  </div><!-- collapse-1 end here -->
               </div><!-- card end here -->

               <div class="card">
                  <div class="card-header"><a data-toggle="collapse" href="#collapse-2">How does this app save money?</a></div>
                  <div id="collapse-2" class="collapse" data-parent="#accordion">
                     <div class="card-body">
                        <p>Think of how much you would pay someone to handle all your bookkeeping. Would you pay them $15-$20 per hour? How many hours per week would you pay them to make sure all your clients are paid up on time and ready for their next session before your session begins. If you had someone work just 10 hours per week that would cost you hundreds of dollars per month.  Life Canon does all this and more for way less money.  Not to mention if you are doing all the accounting yourself then you will be saving yourself all of that time.  This frees you up to take on more clients and relieves you from needing to ask for payments all the time.</p>
                     </div><!-- card-body here -->
                  </div><!-- collapse-2 end here -->
               </div><!-- card end here -->
				 <div class="card">
                  <div class="card-header"><a data-toggle="collapse" href="#collapse-3">Can I change my plan at any time?</a></div>
                  <div id="collapse-3" class="collapse" data-parent="#accordion">
                     <div class="card-body">
                        <p>Yes you can. If you want to go back in and save money by paying for a yearly plan that is not a problem. Your new plan will begin the moment you make that change.</p>
                     </div><!-- card-body here -->
                  </div><!-- collapse-3 end here -->
               </div><!-- card end here -->
             
            </div><!-- accordion end here -->
         </div><!-- col end here -->
      </div><!-- row end here -->
   </div><!-- container end here -->
</div><!-- faq-sec end here -->


<div class="our-sec" id="cust-sec">
   <div class="container">
      <div class="row">
         <div class="col-xl-3 col-lg-2 col-md-2"></div>
         <div class="col-xl-6 col-lg-8 col-md-8">
            <h2>Our Customers love Life Canon</h2>
            <!--<h6>Beautiful and simple to use Life Canon will make your coaching business easier to run.</h6>-->
         </div><!-- col end here -->
      </div><!-- row end here -->

      <div class="row">
         <div class="col-lg-1"></div>
         <div class="col-lg-10 col-md-12" data-aos="fade-up">
            <div id="our-sec" class="owl-carousel">
				<?php 
						$users =App\Models\User::where('user_type', 1)->where('status',1)->get();
						if(count($users)>0){
							foreach($users as $user){
								 $feedbacks = App\Models\AppFeedback::where('user_id',$user->id)->latest()->get()->unique('user_id');
								 //print_r($feedback);
								 foreach($feedbacks as $feedback){ ?>
									  <div class="our-box">
										<?php if($user->profile_pic){?>
										  <img src="{{asset('public/')}}/{{$user->profile_pic}}">
											<?php }else{?>
											<img src="{{asset('public/assets/front-assets/images/our-img1.jpg')}}">
											<?php } ?>
										  <p>"<?php echo $feedback->description;?>"</p>
										  <h5><span><?php echo $user->name;?></span> Coach</h5>
									   </div>
								<?php }
							}
						}
				?>
               <!--<div class="our-box">
                  <img src="{{asset('public/assets/front-assets/images/our-img1.jpg')}}">
                  <p>"Life Canon is great for my coaching business. Set up takes a little time but so much now is automated I can just focus on my clients. I love it!"</p>
                  <h5><span>Alfredo Rosser</span> Consultant</h5>
               </div>

               <div class="our-box">
                  <img src="{{asset('public/assets/front-assets/images/our-img2.jpg')}}">
                  <p>"I love the Habit List. It’s awesome to be able to see my clients progress through out the week and them discuss it with them together. It gives more quality and content to our sessions and makes me look more professional"</p>
                  <h5><span>Marilyn Herwitz</span> Consultant</h5>
               </div>

               <div class="our-box">
                  <img src="{{asset('public/assets/front-assets/images/our-img.jpg')}}">
                  <p>"Beautifully designed pricing tables so you can setup pricing tiers for your app. "</p>
                  <h5><span>Jocelyn Calzoni</span> Consultant</h5>
               </div>

               <div class="our-box">
                  <img src="{{asset('public/assets/front-assets/images/our-img1.jpg')}}">
                  <p>"Life Canon is great for my coaching business. Set up takes a little time but so much now is automated I can just focus on my clients. I love it!"</p>
                  <h5><span>Alfredo Rosser</span> Consultant</h5>
               </div>

               <div class="our-box">
                  <img src="{{asset('public/assets/front-assets/images/our-img2.jpg')}}">
                  <p>"I love the Habit List. It’s awesome to be able to see my clients progress through out the week and them discuss it with them together. It gives more quality and content to our sessions and makes me look more professional"</p>
                  <h5><span>Marilyn Herwitz</span> Consultant</h5>
               </div>--><!-- our-box end here -->
			   
            </div><!-- our-sec end here -->
         </div><!-- col end here -->
      </div><!-- row end here -->
   </div><!-- container end here -->
</div><!-- our-sec end here -->


<div class="cust-sec">
   <div class="container">
      <div class="row">
         <div class="col-lg-3 col-md-2"></div>
         <div class="col-lg-6 col-md-8" data-aos="fade-right">
            <h2>Our Customers love Life Canon</h2>
            <!--<h6>Beautifully designed pricing tables so you can setup pricing tiers for your app. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat.</h6>-->
			<div class="row">
				<div class="col-md-6">
					<span>iPhone users<span>
					<a href="https://apps.apple.com/in/app/lifecanon/id1628105468" target="_blank"><img src="{{asset('public/assets/front-assets/images/cust-icon.png')}}" > <span><em>DOWNLOAD</em> From App Store</span></a> 
				</div>
				<div class="col-md-6">
				<span>Android users<span>
			<a href="https://play.google.com/store/apps/details?id=com.life_canon" target="_blank"><img src="{{asset('public/assets/front-assets/images/cust-icon1.png')}}"> <span><em>DOWNLOAD</em> From Play Store</span></a>
			</div>
			</div>
         </div><!-- col end here -->
      </div><!-- row end here -->
   </div><!-- container end here -->
</div><!-- cust-sec end here -->


<div class="ques-sec" id="ques-sec">
   <h2>Have any questions?</h2>
   <div class="container">
   <div class="row"><div class="col-lg-1 col-md-1"></div><div class="col-md-10"> @if(session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
                @endif</div></div>
      <div class="row">
         <div class="col-lg-1 col-md-1"></div>
         <div class="col-lg-10 col-md-10">
            <form data-aos="fade-up" action="{{route('contactform')}}" method="post" enctype="multipart/form-data" id="contact_form">
			@csrf
               <div class="row">
                  <div class="col-lg-4 col-md-4">
                     <div class="in-box"><input class="box" type="text" name="name" placeholder="Your Name" required /></div>
                  </div><!-- col end here -->

                  <div class="col-lg-4 col-md-4">
                     <div class="in-box"><input class="box" type="email" name="email" placeholder="Your e-Mail Address" required /></div>
                  </div><!-- col end here -->

                  <div class="col-lg-4 col-md-4">
                     <div class="in-box"><input class="box" type="number" name="phone" placeholder="Your Phone Number" required /></div>
                  </div><!-- col end here -->
               </div><!-- row end here -->
               <div class="in-box"><textarea class="box" type="text" name="message" placeholder="Message" rows="4"></textarea></div>
			   
			   <!-----------Captcha code------------->
					<div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                          <div class="captcha" style="padding: 0px 3px 15px 1px;">
                          <span>{!! captcha_img() !!}</span>
                          <button type="button" class="btn btn-success btn-refresh" id="refreshbtn"><i class="fa fa-refresh"></i></button>
                          </div>
                          <input id="captcha" type="text" class="box" placeholder="Enter Captcha" name="captcha">
                          @if ($errors->has('captcha'))
                              <span class="help-block">
                                  <div class="alert alert-danger">{{ $errors->first('captcha') }}</div>
                              </span>
                          @endif
                     </div>
			   <!-----------End Captcha code----------->
			   
               <button class="log-btn" type="submit">Send Message</button>
            </form><!-- form end here -->
         </div><!-- col end here -->
      </div><!-- row end here -->
   </div><!-- container end here -->
</div><!-- ques-sec end here -->
@section('script')
	 @if(session('contact_success'))
		 <script>
				$('html, body').animate({
			scrollTop: $("#contact_form").offset().top
		}, 2000);
		</script>		
	  @endif
	  
	 @if($errors->has('captcha'))
		<script>
			$('html, body').animate({
			scrollTop: $("#captcha").offset().top
			}, 2000);
		</script>
	@endif 
	
	<script>
		$("#refreshbtn").click(function(){
			$.ajax({
				 type:'GET',
				 url:"{{route('refresh_captcha')}}",
				 success:function(data){
					 if(data.captcha){
						$(".captcha span").html(data.captcha);
					 }
				 }
			});
		});
	</script>
	
@stop
@endsection