@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Card</h6>
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
                            <h3 class="mb-0">Add Card</h3>
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
                    <form method="post" action="{{ route('card.store') }}" enctype="multipart/form-data">
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">HeadShot</label>
                                        <input type="file" name="image" class="form-control image">
                                        @error('image')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div id="crop_preview" class="row"></div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">File</label>
                                        <input type="file" name="cardFiles[]" id="upload_file" class="form-control" onchange="preview_image();" multiple/>
                                    </div>
                                </div>
                            </div>

                            <div id="image_preview" class="row"></div>

                            <!--<div class="row repeat_box">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">File</label>
                                        <input type="file" name="cardFiles[]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Description</label>
                                        <input type="text" name="Filedescription[]" class="form-control">
                                    </div>
                                </div>
                              </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <a href="javascript:void(0)" class="btn btn-circle btn-primary add_button">
                                        <i class="fa fa-plus"></i> Add More Files </a>
                                </div>
                            </div><br/>-->
                            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel">Crop Image</h5>
<!--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                                                <span aria-hidden="true">×</span>-->
<!--                                            </button>-->
                                        </div>
                                        <div class="modal-body">
                                            <div class="img-container">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="preview"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
<!--                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>-->
                                            <button type="button" class="btn btn-primary" id="crop">Crop</button>
                                        </div>
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

@section('scripts')
<script type="text/javascript">

    function preview_image()
    {
        var total_file=document.getElementById("upload_file").files.length;
        $('#image_preview').empty();
        for(var i=0;i<total_file;i++)
        {
            $('#image_preview').append("<div class='col-lg-6'><div class='form-group'><label class='form-control-label'></label><img style='max-width: 200px; height: 100px' src='"+URL.createObjectURL(event.target.files[i])+"'></div></div><div class='col-lg-6'><div class='form-group'><label class='form-control-label'>Description</label><input type='text' name='Filedescription[]' value='"+event.target.files[i].name+"' class='form-control'></div></div>");
        }
    }

    $(document).ready(function() {

        var max_field = 20;
        var wrapper = $('.all_info');
        $i = 1;

        $('.add_button').click(function(){
            var numItems = $('.repeat_box').length;
            //Check maximum number of input fields
            if(wrapper.children().length < max_field){
                $('body').find('.repeat_box:last').after('<div class="row repeat_box"><div class="col-lg-5"><div class="form-group"><label class="form-control-label">File</label><input type="file" name="cardFiles[]" class="form-control"></div></div><div class="col-lg-5"><div class="form-group"><label class="form-control-label">Description</label><input type="text" name="Filedescription[]" class="form-control"></div></div><div class="col-lg-2"><label class="form-control-label">Delete</label><br/><a href="javascript:void(0)"class="btn btn-circle btn-danger remove_button"><i class="fa fa-trash-o"></i> </a></div></div>'); //Add field html
            }
        });
        //Once remove button is clicked
        $('body').on('click', '.remove_button', function(e){
            $(this).parents('.repeat_box').remove(); //Remove field html
        });
    });

</script>

<style type="text/css">
img {
display: block;
max-width: 100%;
}
.preview {
overflow: hidden;
width: 160px;
height: 160px;
margin: 10px;
border: 1px solid red;
}
modal-lg{
max-width: 1000px !important;
}
</style>

<script>
    var $modal = $('#modal');
    var image = document.getElementById('image');
    var cropper;
    $("body").on("change", ".image", function(e){
        var files = e.target.files;
        var done = function (url) {
            image.src = url;
            $modal.modal('show');
        };
        var reader;
        var file;
        var url;
        if (files && files.length > 0) {
            file = files[0];
            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });
    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });
    $("#crop").click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 160,
            height: 160,
        });
        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                console.log(base64data);
                $('#crop_preview').append('<input type="hidden" name="cropImage" value="'+base64data+'" class="form-control">'); //Add field html

                $modal.modal('hide');
                /*$.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "crop-image-upload",
                    data: {'_token': $('meta[name="_token"]').attr('content'), 'image': base64data},
                    success: function(data){
                        console.log(data);
                        $modal.modal('hide');
                        alert("Crop image successfully uploaded");
                    }
                });*/
            }
        });
    })
</script>
@stop
