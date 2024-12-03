@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Page</h6>
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
                            <h3 class="mb-0">Add new Page </h3>
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
                    <form method="post" action="{{ route('page.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                        @error('title')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                               
                            </div>
							<div class="row">
							 <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Meta Data</label>
                                        <input type="text" name="meta_data" class="form-control" value="{{ old('meta_data') }}">
                                        @error('meta_data')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                               
                               
                            </div>
							<div class="row">
							  <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Meta Description</label>
                                        <input type="text" name="meta_description" class="form-control" value="{{ old('title') }}">
                                        @error('meta_description')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                             </div>
							<div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Status</label>
										<select name="status" class="form-control">
										<option value="1" >Active</option>
										<option value="0">InActive</option>
										</select>
                                        
                                        @error('status')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
								 <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Page content</label>
										<textarea id="summernote" name="content" class="summernote form-control" ></textarea>
                                        
                                        @error('content')<div class="text-danger">{{ $message }}*</div>@enderror
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
