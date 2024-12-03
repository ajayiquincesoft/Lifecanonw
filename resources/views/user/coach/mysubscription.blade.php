@extends('user.layout.app')

@section('content')

<!-- Page content -->

<br />
<br />
<br />

<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">My Subscriptions </h3>
                        </div>

                    </div>
                </div>
                <div class="card-body">
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
                    <?php if(Auth::user()->user_type==1){ ?>
					<div class="row">
					
					
					<?php 
					
					if(count($plans)>0){?>
					
					@foreach($plans as $my_plan)
					<?php if(@$my_plan->subscription_status ==1){?>
					<div class="col-md-2"></div>
					<div class="col-md-8"><h4 class="heading-title">Life Canon Plans </h4></div>
					<div class="col-md-2"></div>
					<div class="col-md-2"></div>
					<div class="col-md-6">
					<table class="table">
				
						<tr>
							<td>Plan name:</td>
							<td>{{ $my_plan->plan->name }}</td>
						</tr>
						<tr>
							<td>Plan Amount:</td>
							<td>${{ $my_plan->plan->price }}</td>
						</tr>
						<tr>
							<td>Duration:</td>
							<td>{{date('m/d/Y',strtotime($my_plan->start_date))}}  - {{ date('m/d/Y',strtotime($my_plan->end_date)) }}</td>
						</tr>
					
					
					</table>
					</div>
					<div class="col-md-2">
						<table>
						<tr>
						<td>Status:</td>
						<td>
						<?php 
						if($my_plan->end_date <  date('Y-m-d h:i:s')){
							echo 'In-Active';
						}else{
							echo 'Active';
						} ?>
						
						</td>
						</tr>
						<tr>
							<td>
							<?php 
							$iostransaction = \App\Models\Payment::where('subscription_id', '=', $my_plan->subscription_id)->first();
							if(!@$iostransaction->ios_original_transaction_id){
							?>
							<form autocomplete="off" action="{{route('coach.cancelsubscription')}}" method="post" id="" enctype="multipart/form-data">
							 @csrf
							 <input class="box" type="hidden" name="user_id" value="{{ $my_plan->user_id }}">
							 <input class="box" type="hidden" name="subscription_id" value="{{ $my_plan->subscription_id }}">
								<button class="arc-btn1 plan-cancel"onclick='return confirm("Are you sure.You want to cancel subscription?")'>Cancel</button>	
							</form>
							<?php } ?>
							</td>
						</tr>
						</table>
					</div>
					<div class="col-md-2"></div>
					<?php }?>
					@endforeach
					<div class="col-md-2"></div>
					<?php }else{ echo "<div class='col-md-3'></div><div class='col-md-6'><p style='text-align:center;color: red;'>There is no subscription available.</p></div>";} ?>
					</div>
				 
					<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8"><h4 class="heading-title">Added clients </h4></div>
					<div class="col-md-2"></div>
					</div>
					
					@php
						//print_r($addClient);
					@endphp
					<?php if(count($addClient)>0){?>
					<?php foreach($addClient as $my_client){?>
					<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-6">
					<table class="table">
					
						<tr>
							<td colspan="2"><strong>{{ $my_client->client_name }}</strong></td>
							
						</tr>
						<tr>
							<td>Session:</td>
							<td>{{ $my_client->cycle }}</td>
						</tr>
						<tr>
							<td>Plan Name:</td>
							<td>{{ $my_client->plan_name }}</td>
						</tr>
						<tr>
							<td>Plan Amount:</td>
							<td>${{ $my_client->plan_amount }}</td>
						</tr>
						<tr>
							<td>Total Contarct:</td>
							<td>${{ $my_client->plan_amount * $my_client->cycle }} </td>
						</tr>	
						<tr>
							<td>Paid to date:</td>
							@php
						
							$paidtodate=0;
							$star_and_end_date_weeks=0;
							
							if($my_client->plan_name=='Weekly'){
								$firstDate = new DateTime($my_client->start_date);
								$secondDate = new DateTime(date('Y/m/d'));
								$differenceInDays = $firstDate->diff($secondDate)->days;
								$differenceInWeeks = ($differenceInDays)/7;
								$star_and_end_date_weeks = floor($differenceInWeeks);
								if($star_and_end_date_weeks > $my_client->cycle){
									$star_and_end_date_weeks =$my_client->cycle-1;
								}
								$paidtodate = ($my_client->plan_amount)*($star_and_end_date_weeks+1);
									
							}
							if($my_client->plan_name=='Monthly'){
									$startd = new DateTime($my_client->start_date);
									$secondDate1 = new DateTime(date('Y/m/d'));
									$differenceInDays = $startd->diff($secondDate1)->days;
									$differenceInmonth = ($differenceInDays)/30;
									$diffmonths = floor($differenceInmonth);
									if($diffmonths>=$my_client->cycle){
										$diffmonths = $my_client->cycle-1;
								}
								
								$paidtodate = ($my_client->plan_amount)*($diffmonths);
								}
							@endphp
							<td>${{ $paidtodate }}</td>
						</tr>
					
					
					</table>
					</div>
					<div class="col-md-2">
						<table>
						<tr>
						<td>Status:</td>
						<td>
						
						{{ ($my_client->subscription_status_for_coach==1)?'Active':'In-Active' }}
						</td>
						</tr>
						<tr>
							<td>
							<form autocomplete="off" action="{{route('coach.cancelsubscription')}}" method="post" id="" enctype="multipart/form-data">
							 @csrf
							 <input class="box" type="hidden" name="user_id" value="{{ $my_plan->user_id }}">
							 <input class="box" type="hidden" name="plan_id" value="{{ $my_plan->plan_id }}">
								<!--<button class="arc-btn1 plan-cancel">In-active</button>-->
							</form>
							</td>
						</tr>
						</table>
					</div>
					<div class="col-md-2"></div>
					</div>
					<?php } ?>
					
					<?php }else{ echo "<div class='col-md-1'></div><div class='col-md-6'><p style='text-align:center;color: red;'>There is no client available.</p></div>";} ?>
				  
					<?php } else{ ?> 
					<div class="row">
						<div class="col-md-2"></div>
						@if(count($plans)>0)
							@foreach($plans as $my_plan)
								<div class="col-md-8">
									<table class="table">
										<tr>
											<td colspan="3"><strong>{{ $my_plan->client_name }}</strong></td>
										</tr>
										<tr>
											<td>Plan Name:</td>
											<td>{{ $my_plan->plan_name }}</td>
											<td>Active</td>
										</tr>
										<tr>
											<td>Plan Amount:</td>
											<td>${{ $my_plan->plan_amount }}</td>
											<td></td>
										</tr>
									</table>
								</div>
							@endforeach
						@else
							<div class="col-md-8">
									<div class="alert alert-danger">There is no subscription available.</div>
							</div>
						@endif
						<div class="col-md-2"></div>
					</div>  
					<?php }?> 
				 
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('script')
<script>
    $('#updatestripe').submit(function(event) {
        if (!confirm("are you sure you want to update key")) {
            event.preventDefault();
        }
    });
</script>
@stop