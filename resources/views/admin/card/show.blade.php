@extends('layouts.admin')

@section('content')

<div class="card-sec">
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
        @endif

        <div class="row">
            <div class="col-lg-9 col-md-9"> <h4></h4></div>
            <div class="col-lg-3 col-md-3 text-right"><a href="{{ route('card.edit',@$cardIdentity->id) }}" class="btn btn-primary" style="width;110%">Edit Detail</a></div>
        </div>

        <div class="row">
            <?php
            if(request()->get('Id')){
                $CardMeta = \App\Models\CardMeta::find(request()->get('Id'));
            }else{
                $CardMeta = \App\Models\CardMeta::whereCardId(@$cardIdentity->id)->first();
            }
            ?>
            <div class="col-lg-2"></div>
            <div class="col-lg-4 col-md-6">
                <div class="safety-card" data-aos="fade-up" data-aos-duration="2000">
                    <h4>{{ @$CardMeta->type->name }} Training Card <span>New York City</span></h4>
                    <img class="card-pic" src="{{ @$cardIdentity->image_path }}">
                    <div class="row">
                        <div class="col-lg-7 col-md-5">
                            <h5><label>ID :</label> <span>{{ @$cardIdentity->CardId }}</span></h5>
                            <h5><label>Name :</label>{{ @$cardIdentity->Suffix }} {{ @$cardIdentity->FirstName }} {{ @$cardIdentity->MiddleName }} {{ @$cardIdentity->LastName }}</h5>
                            <h5><label>Height :</label> <span>{{ @$cardIdentity->Height }}</span></h5>
                            <h5><label>Eye :</label> <span>{{ @$cardIdentity->EyeColor }}</span></h5>
                        </div><!-- col end here -->
                        <div class="col-lg-5 col-md-5">
                            <!-- <img src="{{ asset('assets/images/scan-code.png') }}">-->
                            @if(@$CardMeta->Url)
                            {!! QrCode::size(80)->generate(@$CardMeta->Url); !!}
                            @endif
                        </div>
                    </div><!-- row end here -->

                    <h3>{{ @$CardMeta->type->name }}</h3>
                    <div class="row">
                        <div class="col-lg-7 col-md-5">
                            <h6><label>Issued :</label> {{ (@$CardMeta->IssueDate)?Carbon\Carbon::parse(@$CardMeta->IssueDate)->format('m/d/Y'):'-' }}</h6>
                            <h6><label>Expired :</label> {{ (@$CardMeta->ExpiryDate)?Carbon\Carbon::parse(@$CardMeta->ExpiryDate)->format('m/d/Y'):'-' }}</h6>
                        </div><!-- col end here -->
                        <div class="col-lg-5 col-md-5"><img class="sft-logo" src="{{ asset('assets/images/logo.png') }}"></div>
                    </div><!-- row end here -->
                    <h6><span>DOB Course Provider ID No: {{ @$CardMeta->DOBCourseProviderId }}</span></h6>
                </div><!-- safety-card end here -->
            </div><!-- col end here -->

            <div class="col-lg-4 col-md-6">
                <div class="card-box" data-aos="fade-down" data-aos-duration="2000">
                    <h4>{{ @$CardMeta->type->name }}</h4>
                    <h5><span>Id</span> {{ @$cardIdentity->CardId }}</h5>
                    <h5><span>Name</span> {{ @$cardIdentity->Suffix }} {{ @$cardIdentity->FirstName }} {{ @$cardIdentity->MiddleName }} {{ @$cardIdentity->LastName }}</h5>
                    <h5><span>Email </span> {{ @$cardIdentity->Email }}</h5>
                    <h5><span>Mobile </span> {{ @$cardIdentity->Mobile }}</h5>
                    <h5><span>Dob </span> {{ @$cardIdentity->Dob }}</h5>
                    <h5><span>Gender </span> {{ @$cardIdentity->Zender }}</h5>
                    <h5><span>Address </span> {{ @$cardIdentity->HouseNo }} {{ @$cardIdentity->Address }}
                        {{ (@$cardIdentity->City)?@$cardIdentity->City.',':'' }}
                        {{ (@$cardIdentity->State)?@$cardIdentity->State.',':'' }}
                        {{ @$cardIdentity->Zipcode }}
                    </h5>
                    <h5><span>Credit Hours</span> {{ @$cardIdentity->NumberOfCreditHoursCompleted }}</h5>
<!--                    <h5><span>SST Id</span> {{ @$cardIdentity->TypeOfSstIDCard }}</h5>-->
                </div><!-- card-box end here -->
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- card-sec end here -->


<div class="tab-sec" style="background: #ffffff">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">

                <div class="card-tab-1" data-aos="fade-down" data-aos-duration="2000">

                    <div class="row">
                        <div class="col-lg-9 col-md-9"> <h4>Issued Safety Cards</h4></div>
                        <div class="col-lg-3 col-md-3 text-right"><a href="{{ route('card.addCardType',['id'=>@$cardIdentity->id]) }}" class="btn btn-primary" style="width;110%">Add New Card Type</a></div>
                    </div>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th>Card Type</th>
                            <th>Trainer</th>
                            <th>Status</th>
                            <th>Card Issuer Id</th>
                            <th>Issued Date</th>
                            <th>Expiration Date</th>
                            <th>DOB Course Provider Id</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(@count($cardData) > 0)
                        @foreach($cardData as $cardDatas)
                        <tr>
                            <td>{{ @$cardDatas->type->name }}</td>
                            <td>{{ @$cardDatas->trainer->name }}</td>
                            <td>{{ @$cardDatas->status }}</td>
                            <td>{{ @$cardDatas->CardIssuerID }}</td>
                            <td>{{ (@$cardDatas->IssueDate)?Carbon\Carbon::parse(@$cardDatas->IssueDate)->format('m/d/Y h:i a'):'-' }}</td>
                            <td>{{ (@$cardDatas->ExpiryDate)?Carbon\Carbon::parse(@$cardDatas->ExpiryDate)->format('m/d/Y h:i a'):'-' }}</td>
                            <td>{{ @$cardDatas->DOBCourseProviderId }}
                                <button class="btn btn-info" onclick="myFunction('{{ @$cardDatas->Url }}')">Copy Url</button>
                            </td>
                            <td>
                                <a href="{{ route('card.editCardType', $cardDatas->id) }}" class="text-gray-dark">
                                    <span class="mr-2"><i class="fa fa-edit" title="Edit Card type"></i></span>
                                </a>
                                <span> {!! Form::open([
                                          'method'=>'DELETE',
                                          'route' => ['card.deleteCardType', $cardDatas->id],
                                          'style' => 'display:inline'
                                          ]) !!}
                                            {!! Form::button('<i class="fa fa-trash text-danger" aria-hidden="true"></i>', array(
                                          'type' => 'submit',
                                          'class' => 'btn',
                                          'title' => 'Delete Card Type',
                                          'onclick'=>'return confirm("Are you sure about deleting card type?")'
                                          )) !!}
                                          {!! Form::close() !!}</span>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table><!-- table end here -->
                </div><!-- card-tab-1 end here -->

                <div class="card-tab-1" data-aos="fade-down" data-aos-duration="2000">
                    <div class="row">
                    <div class="col-lg-10 col-md-10"> <h4>Completed Courses </h4></div>
                    <div class="col-lg-2 col-md-2"><a href="{{ route('card.addCourse',['id'=>@$cardIdentity->id]) }}" class="btn btn-primary" style="width;110%">Add New Course</a></div>
                    </div>

                    <table id="example-1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
<!--                            <th>Status</th>-->
                            <th>Days Attended</th>
                            <th>Hours Attended</th>
                            <th>Completion Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(@count($cardClasses) > 0)
                        @foreach($cardClasses as $cardClass)
                        <tr>
                            <td>{{ @$cardClass->courses->name }}</td>
<!--                            <td>{{ @$cardClass->Status }}</td>-->
                            <td>{{ @$cardClass->DaysAttended }}</td>
                            <td>{{ @$cardClass->courses->hour }}</td>
                            <td>{{ Carbon\Carbon::parse(@$cardClass->CompletionDays)->format('m/d/Y') }}</td>
                            <td>
                                <a href="{{ route('card.editCourse', $cardClass->id) }}" class="text-gray-dark">
                                    <span class="mr-2"><i class="fa fa-edit" title="Edit Course"></i></span>
                                </a>
                                <span> {!! Form::open([
                                          'method'=>'DELETE',
                                          'route' => ['card.deleteCourse', $cardClass->id],
                                          'style' => 'display:inline'
                                          ]) !!}
                                            {!! Form::button('<i class="fa fa-trash text-danger" aria-hidden="true"></i>', array(
                                          'type' => 'submit',
                                          'class' => 'btn',
                                          'title' => 'Delete Course',
                                          'onclick'=>'return confirm("Are you sure about deleting Course?")'
                                          )) !!}
                                          {!! Form::close() !!}</span>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table><!-- table end here -->
                </div><!-- card-tab-1 end here -->
            </div><!-- col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- tab-sec end here -->
@endsection
<script>
    function myFunction(value) {
        var tempInput = document.createElement("input");
        tempInput.value = value;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
    }
</script>
