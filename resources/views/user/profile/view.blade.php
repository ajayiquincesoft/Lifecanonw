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
                            <h3 class="mb-0">Profile </h3>
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
                    $user=Auth::user();
                    @endphp
                    <form method="post" action="{{ route('user.saveprofile') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                                        @error('name')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Years of Experience</label>
                                        <input type="text" name="experience" class="form-control" value="{{ $user->experience }}">
                                        @error('experience')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Area of Expertise</label>
                                        <input type="text" name="area_of_expertise" class="form-control" value="{{ $user->area_of_expertise }}">
                                        @error('area_of_expertise')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                            </div>
							 <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" disabled>
                                        @error('email')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                        @error('phone')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">About</label>
                                        <textarea id="summernote" name="description" class="form-control">{{ $user->description }}</textarea>

                                        @error('description')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

							  <div class="row">
                                <div class="col-lg-12">
                                <label class="form-control-label">Select Your Time Zone*</label> 
                                <select class="form-control" name="timezone" value="">
									<option value="">Select Your Time Zone</option>
									<option value="America/Denver" {{($user->timezone=='America/Denver')?'selected':''}}>Mountain time</option>
									<option value="America/Los_Angeles" {{($user->timezone=='America/Los_Angeles')?'selected':''}}>Pacific time</option>
									<option value="America/Chicago" {{($user->timezone=='America/Chicago')?'selected':''}}>Central time</option>
									<option value="America/New_York" {{($user->timezone=='America/New_York')?'selected':''}}>Eastern time</option>
								</select>
                            </div>
							</div>
							<br>
							

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Status</label>
                                        <select name="status" class="form-control" disabled>
                                            <option value="1" {{($user->status==1)?'selected':''}}>Active</option>
                                            <!-- <option value="0" {{($user->status==0)?'selected':''}}>InActive</option> -->
                                        </select>

                                        @error('status')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Update Profile Pic</label>
                                        <input type="file" name="profile_pic" id="add-pic">
                                        @error('profile_pic')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="submit" class="btn btn-dark save-btn">Save</button>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection