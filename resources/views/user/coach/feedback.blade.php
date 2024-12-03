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
                            <h3 class="mb-0">Feedback</h3>
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
					<form autocomplete="off" action="{{route('coach.AddFeedback')}}" method="post" id="user-registration-form" enctype="multipart/form-data">
						@csrf
						<div class="in-box">
							 <textarea class="form-control" type="text" name="description" placeholder="Start typing...." rows="12"></textarea>
						</div>
						<br>
						<button type="submit" class="save-btn btn btn-dark">Submit</button>
					</form>
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
    $('#updatestripe').submit(function(event) {
        if (!confirm("are you sure you want to update key")) {
            event.preventDefault();
        }
    });
</script>
@stop