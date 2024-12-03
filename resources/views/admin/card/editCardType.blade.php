@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Card Type</h6>
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
                            <h3 class="mb-0">Edit Card Type </h3>
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
                    <form method="post" action="{{ route('card.editCardType',['id'=>request()->route('id')]) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="pl-lg-4">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Type Of Sst ID Card</label>
                                        <select name="TypeOfSstIDCard" class="form-control">
                                            @foreach($carttype as $carttypes)
                                            <option value="{{ $carttypes->id }}" {{ ($carttypes->id == $data->TypeOfSstIDCard)?'selected':'' }}>{{ $carttypes->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('TypeOfSstIDCard')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Card Issuer ID</label>
                                        <input type="text" name="CardIssuerID" class="form-control" value="{{ $data->CardIssuerID }}">
                                        @error('CardIssuerID')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Trainer Admin</label>
                                        <select name="TrainerId" class="form-control">
                                            <option value="0">Select Trainer Admin</option>
                                            @foreach($trainertype as $trainertypes)
                                            <option value="{{ $trainertypes->id }}" {{ ($trainertypes->id == $data->TrainerId)?'selected':'' }}>{{ $trainertypes->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('TrainerId')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">DOB Course Provider Id</label>
                                        <input type="text" name="DOBCourseProviderId" class="form-control" value="{{ $data->DOBCourseProviderId }}">
                                        @error('DOBCourseProviderId')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Issue Date</label>
                                        <input type="date" name="IssueDate" class="form-control" value="{{ ($data->IssueDate)?$data->IssueDate->format('Y-m-d'):'' }}">
                                        @error('IssueDate')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Expiry Date</label>
                                        <input type="date" name="ExpiryDate" class="form-control" value="{{ ($data->ExpiryDate)?$data->ExpiryDate->format('Y-m-d'):'' }}">
                                        @error('ExpiryDate')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Url</label>
                                        <input type="text" class="form-control" id="url" value="{{ $data->Url }}">

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-info mt-4" onclick="myFunction()">Copy Url</button>
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

<script>
    function myFunction() {
        var copyText = document.getElementById("url");
        copyText.select();
        document.execCommand("copy");
    }
</script>
