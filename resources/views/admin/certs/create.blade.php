@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Certs</h6>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Add Certs</h3>
                        </div>
                        <!--                        <div class="col-4 text-right">-->
                        <!--                            <a href="#!" class="btn btn-sm btn-primary">Settings</a>-->
                        <!--                        </div>-->
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
                    <form method="post" action="{{ route('certs.store') }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('POST') }}
                        <div class="pl-lg-4">

                            <!--<div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Card Id</label>
                                        <input type="text" name="CardId" class="form-control" value="{{ old('CardId') }}">
                                        @error('CardId')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                            </div>-->


                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Suffix</label>
                                        <input type="text" name="Suffix" class="form-control" value="{{ old('Suffix') }}">
                                        @error('Suffix')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">First Name</label>
                                        <input type="text" name="FirstName" class="form-control" value="{{ old('FirstName') }}">
                                        @error('FirstName')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Middle Name</label>
                                        <input type="text" name="MiddleName" class="form-control" value="{{ old('MiddleName') }}">
                                        @error('MiddleName')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Last Name</label>
                                        <input type="text" name="LastName" class="form-control" value="{{ old('LastName') }}">
                                        @error('LastName')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Dob</label>
                                        <input type="date" name="Dob" class="form-control" value="{{ old('Dob') }}">
                                        @error('Dob')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Gender</label>
                                        <input type="text" name="Zender" class="form-control" value="{{ old('Zender') }}">
                                        @error('Zender')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">HouseNo</label>
                                        <input type="text" name="HouseNo" class="form-control" value="{{ old('HouseNo') }}">
                                        @error('HouseNo')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Address</label>
                                        <input type="text" name="Address" class="form-control" value="{{ old('Address') }}">
                                        @error('Address')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">City</label>
                                        <input type="text" name="City" class="form-control" value="{{ old('City') }}">
                                        @error('City')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">State</label>
                                        <input type="text" name="State" class="form-control" value="{{ old('State') }}">
                                        @error('State')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Zipcode</label>
                                        <input type="text" name="Zipcode" class="form-control" value="{{ old('Zipcode') }}">
                                        @error('Zipcode')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Email</label>
                                        <input type="text" name="Email" class="form-control" value="{{ old('Email') }}">
                                        @error('Email')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Mobile</label>
                                        <input type="text" name="Mobile" class="form-control" value="{{ old('Mobile') }}">
                                        @error('Mobile')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Height</label>
                                        <input type="text" name="Height" class="form-control" value="{{ old('Height') }}">
                                        @error('Height')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Eye Color</label>
                                        <input type="text" name="EyeColor" class="form-control" value="{{ old('EyeColor') }}">
                                        @error('EyeColor')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Number Of Credit Hours Completed</label>
                                        <input type="text" name="NumberOfCreditHoursCompleted" class="form-control" value="{{ old('NumberOfCreditHoursCompleted') }}">
                                        @error('NumberOfCreditHoursCompleted')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">OSHA Card Type</label>
                                        <input type="text" name="OSHACardType" class="form-control" value="{{ old('OSHACardType') }}">
                                        @error('OSHACardType')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">OSHA CardNo</label>
                                        <input type="text" name="OSHACardNo" class="form-control" value="{{ old('OSHACardNo') }}">
                                        @error('OSHACardNo')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">OSHA Card Trainer Name</label>
                                        <input type="text" name="OSHACardTrainerName" class="form-control" value="{{ old('OSHACardTrainerName') }}">
                                        @error('OSHACardTrainerName')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">OSHA Card Course Issued Date</label>
                                        <input type="date" name="OSHACardCourseIssuedDate" class="form-control" value="{{ old('OSHACardCourseIssuedDate') }}">
                                        @error('OSHACardCourseIssuedDate')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="submit" class="btn btn-dark">Submit</button>
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
