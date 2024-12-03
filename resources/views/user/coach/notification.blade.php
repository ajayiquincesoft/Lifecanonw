@extends('user.layout.app')

@section('content')

<!-- Page content -->


<div class="sign-sec-availablity new-clt">
<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
                    <div class="row">
					   <div class="col-lg-12">
						<a class="back-btn" href="{{route('user.dashboard')}}"><i class="fa fa-chevron-left"></i> Back</a>
						<h2>Notifications</h2>
					   </div><!-- col end here -->

					</div>
               
             
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
				@php
		
				@endphp
				<div class="row notifi_alerts">
					<div class="col-md-12">
						<ul>
							@php
								$user = Auth::user();
								if ($user) {
									$notification = $user->notifications()->whereStatus(1)->limit(10)->get();
									//print_r($notification);
									
								}
								@endphp
								@foreach($notification as $noti)
									@php
										   $currentdate = date('Y-m-d h:i:s');
											$date1 = new DateTime($noti->created_at);
											$date2 = new DateTime($currentdate);
											$days  = $date2->diff($date1)->format('%a');
											$adddclients = \App\Models\AddClient::where('user_id', $user->id)->where('status', '=',1)->get();
											$client_id = 0;
											foreach($adddclients as $client){
												 $client->id;
											}
									@endphp
							<li>
							 <a href="{{route('user.dashboard')}}" onclick="readnotification({{$noti->id}})">
									<h4><strong>{{ $noti->title }}</strong> {{ $days }} Days Ago</h4>
									<p>{{ $noti->body }}</p>
							 </a>
							 @endforeach
							</li>
						</ul>
					</div>
				</div>
				
  </div>
</div>
</div>
</div></div>

@endsection

@section('script')

@stop