@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Card Course</h6>
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
                            <h3 class="mb-0">Add new Completed Course </h3>
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
                    <form method="post" action="{{ route('card.addCourse',['id'=>request()->route('id')]) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Course</label>
                                        <select name="course[]" class="form-control selectpicker" multiple data-live-search="true">
                                            @foreach($course as $courses )
                                                 <option value="{{ $courses->id }}">{{ $courses->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('course')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Days Attended</label>
                                        <input type="number" name="DaysAttended" class="form-control" value="{{ old('DaysAttended') }}">
                                        @error('DaysAttended')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!--<div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Hours Attended</label>
                                        <input type="number" name="HourAttended" class="form-control" value="{{ old('HourAttended') }}">
                                        @error('HourAttended')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>-->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Date Completed</label>
                                        <input type="date" name="CompletionDays" class="form-control" value="{{ old('CompletionDays') }}">
                                        @error('CompletionDays')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <!--<div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Status</label>
                                        <input type="text" name="Status" class="form-control" value="{{ old('Status') }}">
                                        @error('Status')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>-->

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
