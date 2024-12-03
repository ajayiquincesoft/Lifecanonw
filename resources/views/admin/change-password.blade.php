@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Admin Password</h6>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-lg-8 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Change Admin Password </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (Session::has('success'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{{ Session::get('success') }}</li>
                        </ul>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">

                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="post" action="{{ route('admin.password') }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Current Password</label>
                                    <input type="text" name="currentPassword" class="form-control" value="{{ old('currentPassword') }}">
                                    @error('currentPassword')<div class="text-danger">{{ $message }}*</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">New Password</label>
                                    <input type="text" name="newPassword" class="form-control" value="{{ old('newPassword') }}">
                                    @error('newPassword')<div class="text-danger">{{ $message }}*</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label">Confirm Password</label>
                                    <input type="text" name="confirmPassword" class="form-control" value="{{ old('confirmPassword') }}">
                                    @error('confirmPassword')<div class="text-danger">{{ $message }}*</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <button type="submit" class="btn btn-dark">Update</button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
