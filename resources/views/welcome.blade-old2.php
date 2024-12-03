@extends('layouts.app')

@section('content')


<div class="banner"></div>
<div class="main">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">

            @if(count($bannerData) > 0)
                @foreach($bannerData as $bannerDatas)
                <div class="carousel-item {{ ($loop->first)?'active':'' }}">
                    <img src="{{ $bannerDatas->image }}" width="100%">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row" data-aos="fade-right" data-aos-duration="2000">
                                <div class="col-lg-7 col-md-12">
                                    <h3>{{ $bannerDatas->title }}</h3>
                                    <h1>{{ $bannerDatas->description }}</h1>
                                    @if($bannerDatas->url)
                                        <a class="view-btn" href="{{ $bannerDatas->url }}">View More</a>
                                    @endif
                                </div><!-- col end here -->
                            </div><!-- row end here -->
                        </div><!-- container end here -->
                    </div><!-- carousel-caption end here -->
                </div><!-- item end here -->
                @endforeach
            @else
                <div class="carousel-item active">
                    <img src="{{ asset('assets/images/banner.jpg') }}" width="100%">
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row" data-aos="fade-right" data-aos-duration="2000">
                                <div class="col-lg-7 col-md-12">
                                    <h3>Lorem Ipsum is simply</h3>
                                    <h1>Lorem Ipsum is simply dummy text of the printing and typesetting</h1>
                                    <a class="view-btn" href="#">View More</a>
                                </div><!-- col end here -->
                            </div><!-- row end here -->
                        </div><!-- container end here -->
                    </div><!-- carousel-caption end here -->
                </div><!-- item end here -->
            @endif

        </div><!-- carousel-inner end here -->
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></a>
    </div>

    <div class="form-sec">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7 col-md-2"></div>
                <div class="col-xl-4 col-lg-5 col-md-8">
                    <form method="post" action="{{ route('welcome') }}" data-aos="fade-up" data-aos-duration="2000">
                        {!! csrf_field() !!}

                        @if($message == 'false')
                            <div class="alert alert-danger">No Record found</div>
                        @endif

                        <h3>Verification Form</h3>
                        <h5>Card Number</h5>
                        <input class="form-control box" type="text" value="{{ \Request()->search }}" name="search" placeholder="Card Number">
                        @error('search')<div class="text-danger">{{ $message }}*</div>@enderror
                        <!--<h5>Captcha</h5>
                        <img src="{{ asset('assets/images/captcha.png') }}">-->
                        @if(config('services.recaptcha.key'))
                        <div class="g-recaptcha"
                             data-sitekey="{{config('services.recaptcha.key')}}">
                        </div>
                        @error('g-recaptcha-response')<div class="text-danger">{{ $message }}*</div>@enderror
                        @endif
                        <br>
                        <!--<input id="captcha" type="text" class="form-control box" placeholder="Enter Captcha" name="captcha">-->
                        <button type="submit" class="verify-button">Verify</button>
                        <!--<a href="#">Verify</a>-->
                        <a class="clr-btn" href="{{ route('welcome') }}">Clear</a>
                    </form><!-- form end here -->
                </div><!-- col end here -->
            </div><!-- row end here -->
        </div><!-- container end here -->
    </div><!-- form-sec end here -->
</div><!-- main end here -->


@if($message == 'true')
<div class="card-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 col-md-6">
                <?php
                if(request()->get('Id')){
                    $CardMeta = \App\Models\CardMeta::find(request()->get('Id'));
                }else{
                    $CardMeta = \App\Models\CardMeta::whereCardId(@$cardIdentity->id)->first();
                }
                ?>
                <div class="safety-card" data-aos="fade-up" data-aos-duration="2000">
                    <h4>{{ @$CardMeta->type->name }} Training Card <span>New York City</span></h4>
                    <img class="card-pic" src="{{ @$cardIdentity->image_path }}">
                    <div class="row">
                        <div class="col-lg-7 col-md-5">
                            <h5><label>ID :</label> <span>{{ @$cardIdentity->CardId }}</span></h5>
                            <h5><label>Name :</label>{{ @$cardIdentity->Suffix }} {{ @$cardIdentity->FirstName }}  {{ @$cardIdentity->MiddleName }}  {{ @$cardIdentity->LastName }}</h5>
                            <h5><label>Height :</label> <span>{{ @$cardIdentity->Height }}</span></h5>
                            <h5><label>Eye :</label> <span>{{ @$cardIdentity->EyeColor }}</span></h5>
                        </div><!-- col end here -->
                        <div class="col-lg-5 col-md-5">
<!--                           <img src="{{ asset('assets/images/scan-code.png') }}">-->
                            @if(@$CardMeta->Url)
                                {!! QrCode::size(80)->generate(@$CardMeta->Url); !!}
                            @endif
                        </div>
                    </div><!-- row end here -->

                    <h3>{{ @$CardMeta->type->name }}</h3>
                    <div class="row">
                        <div class="col-lg-7 col-md-7">

                            <h6><label>Issued :</label> {{ (@$CardMeta->IssueDate)?Carbon\Carbon::parse(@$CardMeta->IssueDate)->format('m/d/Y'):'-' }}</h6>
                            <h6><label>Expired :</label> {{ (@$CardMeta->ExpiryDate)?Carbon\Carbon::parse(@$CardMeta->ExpiryDate)->format('m/d/Y'):'-' }}</h6>
                        </div><!-- col end here -->
                        <div class="col-lg-5 col-md-5"><img class="sft-logo" src="{{ asset('assets/images/logo.png') }}"></div>
                    </div><!-- row end here -->
                    <h6><span>DOB Course Provider ID No: {{ @$CardMeta->DOBCourseProviderId }}</span></h6>
                </div><!-- safety-card end here -->
            </div><!-- col end here -->

            <!--<div class="col-lg-4 col-md-6">
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
                   <h5><span>SST Id</span> {{ @$cardIdentity->TypeOfSstIDCard }}</h5>--
                </div>card-box end here --
            </div> col end here -->
        </div><!-- row end here -->
    </div><!-- container end here -->
</div><!-- card-sec end here -->


<div class="tab-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card-tab">
                    <h4>Issued Safety Cards</h4>
                    @if(@count($cardData) > 0)
                        @foreach($cardData as $cardDatas)
                            <ul class="card-tab">
                                <li><label>Card Type</label> {{ @$cardDatas->type->name }}</li>
                                <li><label>Trainer</label> {{ @$cardDatas->trainer->name }}</li>
                                <li><label>Card Status</label> {{ @$cardDatas->status }}</li>
                                <li><label>Card Issuer Id</label> <span>{{ @$cardDatas->CardIssuerID }}</span></li>
                                <li><label>Issued Date</label> {{ (@$cardDatas->IssueDate)?Carbon\Carbon::parse(@$cardDatas->IssueDate)->format('m/d/Y h:i a'):'-' }}</li>
                                <li><label>Expiration Date</label> {{ (@$cardDatas->ExpiryDate)?Carbon\Carbon::parse(@$cardDatas->ExpiryDate)->format('m/d/Y h:i a'):'-' }}</li>
                            </ul><!-- ul end here -->
                        @endforeach
                    @endif



                    <h4>Completed Courses</h4>
                    @if(@count($cardClasses) > 0)
                        @foreach($cardClasses as $cardClass)
                            <ul class="card-tab">
                                <li><label>Name</label> {{ @$cardClass->courses->name }}</li>
<!--                                <li><label>Status</label> {{ @$cardClass->Status }}</li>-->
                                <li><label>Days Attended</label> <span>{{ @$cardClass->DaysAttended }}</span></li>
                                <li><label>Hours Attended</label> {{ @$cardClass->courses->hour }}</li>
                                <li><label>Completion Date</label> {{ Carbon\Carbon::parse(@$cardClass->CompletionDays)->format('m/d/Y') }}</li>
                            </ul><!-- ul end here -->
                        @endforeach
                    @endif



                </div><!-- card-tab end here -->

                <div class="card-tab-1" data-aos="fade-down" data-aos-duration="2000">
                    <h4>Issued Safety Cards</h4>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th>Card Type</th>
                            <th>Trainer</th>
                            <th>Card Status</th>
                            <th>Card Issuer Id</th>
                            <th>Issued Date</th>
                            <th>Expiration Date</th>
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
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table><!-- table end here -->
                </div><!-- card-tab-1 end here -->

                <div class="card-tab-1" data-aos="fade-down" data-aos-duration="2000">
                    <h4>Completed Courses</h4>
                    <table id="example-1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
<!--                            <th>Status</th>-->
                            <th>Days Attended</th>
                            <th>Hours Attended</th>
                            <th>Completion Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(@count($cardClasses) > 0)
                            @foreach($cardClasses as $cardClass)
                                <tr>
                                    <td>{{ @$cardClass->courses->name }}</td>
<!--                                    <td>{{ @$cardClass->Status }}</td>-->
                                    <td>{{ @$cardClass->DaysAttended }}</td>
                                    <td>{{ @$cardClass->courses->hour }}</td>
                                    <td>{{ Carbon\Carbon::parse(@$cardClass->CompletionDays)->format('m/d/Y') }}</td>
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
@else
<div class="card-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>We provide Safety Training from NYC's leading Safety Compliance Expert, Patrick Mills
                </h3>
                Structure Compliance is an all-encompassing site safety and training company located in Long Island City. We specialize in teaching Department of Buildings approved courses for the Construction Site Safety of New York City, Training crane operators around The United States to operate different cranes and pass their NCCCO practical exam, as well as provide crane and rigging classes with our master rigger. Structure Compliance is a licensed consultant, we frequently conduct the industry code 59 assessment for many companies around New York City.
                Our passion and dedication to site safety and consulting is why we are consistent leaders in the market.
            </div>
        </div>
    </div>
</div>
@endif

@endsection
