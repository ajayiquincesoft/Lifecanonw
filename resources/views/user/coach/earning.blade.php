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
						<h5>
						<?php if(@$payments_group_by){
							$totalamountearned = 0;
							foreach(@$payments_group_by as $total_amount){
								$totalamountearned = @$totalamountearned + $total_amount['paidtodate'];
							}
							echo '$'.$totalamountearned;
						}?>
						</h5>
					</div>
					</div>
					<br/>
					<div class="row">
					 
						<?php if(count(@$payments_group_by)>0){
								$totalamountearned = 0;
									foreach(@$payments_group_by as $detail){?>
										<div class="col-md-6">
											<div class="row">
											<div class="col-md-6">
											<?php if($detail['users']->profile_pic){?>
												<img src="<?php echo url($detail['users']->profile_pic);?>" class="img-responsive" width="60" height="60" style="border-radius:50%;">
											<?php }else{?>
												<img src="{{asset('/profile.png')}}" class="img-responsive" width="60" height="60" style="border-radius:50%;">
											<?php } ?>
											</div><div class="col-md-6"><strong><?php echo $detail['users']->name ?></strong><br><small> <?php echo date('m/d/Y', strtotime($detail['payment_date']));?> </small>
											</div></div>
										</div>
										<div class="col-md-6"><h5 class="save-btn btn btn-dark">Paid to Date</h5><br>$ <?php echo $detail['paidtodate']; ?></div>
									
									 <?php  
									}	
							}else{
								echo "There is no Report found.";
							}
							?>
						
					
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