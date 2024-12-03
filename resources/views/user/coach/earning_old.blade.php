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
                            <h3 class="mb-0">Earnings</h3>
                        </div>
					@php
						$thisyear = date('Y').'-01-01';
					@endphp
					<div class="col-4">
						<form autocomplete="off" action="{{route('coach.earning')}}" method="post" id="filter" enctype="multipart/form-data">
							@csrf
                           <select name="filter_date" class="form-control" id="filterdata">
						   <option value="">Select an Option</option>
						   <option value="{{ date('Y-m-d') }}" {{ (date('Y-m-d')==$filter)?'selected':' ' }}>Today</option>
						   <option value="{{ date('Y-m-1') }}" {{ (date('Y-m-1')==$filter)?'selected':' ' }}>This Month</option> 
						   <option value="{{ $thisyear }}" {{ ( $thisyear==$filter)?'selected':' ' }}>This Year</option>
						   <option value="">All Earning To date</option>
						   </select>
						 </form>
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
                    @php
						
                    @endphp
				<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="row">
					<div class="col-md-6">
					<strong>Total Earning</strong>
					</div>
					<div class="col-md-6">
					<h5>${{ $amount }}</h5>
					</div>
					</div>
					<br/>
				<div class="row">
				@php
					//print_r($payments_group_by);
				@endphp
				<?php if(count(@$payments_group_by)>0){?>
				@foreach(@$payments_group_by as $user)
				<div class="col-md-6"><div class="row"><div class="col-md-6"><img src="{{url($user->user->profile_pic)}}" class="img-responsive" width="60" height="60" style="border-radius:50%;"></div><div class="col-md-6"><strong>{{ $user->user->name }}</strong><br><small>{{ date('m/d/Y', strtotime($user->payment_date)) }}</small></div></div></div>
				<div class="col-md-6"><h5 class="save-btn btn btn-dark">Paid to Date</h5><br>${{ $user->amount }}</div>
				@endforeach
				<?php }else{
					echo "There is no Report found.";
				}?>
				</div>
				</div> 
				</div>  
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

@endsection

@section('script')
<script>
$('#filterdata').on('change', function()
{
	//alert($('#filterdata').val());
	
     this.form.submit();
});
</script>
@stop