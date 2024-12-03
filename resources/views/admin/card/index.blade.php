@extends('layouts.admin')

@section('content')

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Card Detail</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{ route('card.create') }}" class="btn btn-neutral">Add New Card</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow">
                <div class="card-header bg-transparent border-0">

                    @if(session('success'))
                    <div class="alert alert-success">{{session('success')}}</div>
                    @endif

                    <form method="get" action="{{ route('card.index') }}">
                        <div class="col-md-3" style="float: left">
                            <label class="text-white">Search</label>
                            <input type="text" name="search" value="{{ Request()->search }}" placeholder="search" class="form-control">
                        </div>

                        <div class="col-md-3" style="float: left">
                            <label class="text-white">Card Type</label>
                            <select name="card_type" class="form-control">
                                <option value="">Select Card type</option>
                                @foreach($cardType as $cardTypes)
                                    <option value="{{ $cardTypes->id }}" {{ (Request()->card_type == $cardTypes->id)?'selected':'' }}>{{ $cardTypes->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2" style="float: left">
                            <label class="text-white">Card To Date</label>
                            <input type="date" name="to_date" value="{{ Request()->to_date }}" placeholder="search" class="form-control">
                        </div>

                        <div class="col-md-2" style="float: left">
                            <label class="text-white">Card From Date</label>
                            <input type="date" name="from_date" value="{{ Request()->from_date }}" placeholder="search" class="form-control">
                        </div>


                        <div class="col-md-2" style="float: left">
                            <label class="text-white">Filter Card Date</label>
                            <select name="card_date" class="form-control">
                                <option value="">Select Card Date</option>
                                <option value="issue_date" {{ (Request()->card_date == 'issue_date')?'selected':'' }}>Issue Date</option>
                                <option value="expiry_date" {{ (Request()->card_date == 'expiry_date')?'selected':'' }}>Expiry Date</option>

                            </select>
                        </div>

                        <div class="col-md-1 mt-md-4" style="float: left;">
                            <input type="submit" value="Search" class="btn btn-instagram">
                        </div>
                        <div class="col-md-1 mt-md-4" style="float: left;">
                            <a href="{{ route('card.index') }}" class="btn btn-instagram" style="width;110%">Clear</a>
                        </div>
                        <div class="col-md-1 mt-md-4" style="float: left;">
                            <a href="{{ route('card.export',['search'=>Request()->search,'card_type'=>Request()->card_type,'from_date'=>Request()->from_date,'to_date'=>Request()->to_date,'card_date'=>Request()->card_date]) }}" class="btn btn-instagram" style="width;110%">Export</a>
                        </div>
                    </form>

                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Card Id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Detail</th>
                            <th scope="col">#Cards</th>
                            <th scope="col">#Course</th>
<!--                            <th scope="col">Card</th>-->
<!--                            <th scope="col">Status</th>-->
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @if(count($cardData)> 0)
                        @foreach($cardData as $key =>$data)
                        <tr>
                            <td class="budget">{{ $data->id }}</td>
                            <td>{{ $data->CardId }}</td>
                            <td>
                                {{ $data->Suffix }} {{ $data->FirstName }} {{ $data->MiddleName }} {{ $data->LastName }} <br/>
                                {{ $data->Email }} <br/>
                                {{ $data->Mobile }} <br/>
                            </td>
                            <td>
                               Height -  {{ $data->Height }} <br/>
                               Eye - {{ $data->EyeColor }}
                            </td>
                            <td>{{ $data->cardDetail->count() }}</td>
                            <td>{{ $data->cardCourse->count() }}</td>
<!--                            <td>-->
<!--                                Type-   {{ @$data->type->name }} <br/>-->
<!--                                Issue - {{ ($data->IssueDate)?Carbon\Carbon::parse($data->IssueDate)->format('d M Y h:i a'):'' }} <br/>-->
<!--                                Expire - {{ ($data->ExpiryDate)?Carbon\Carbon::parse($data->ExpiryDate)->format('d M Y h:i a'):'' }} <br/>-->
<!--                            </td>-->
<!--                            <td> <a href="#" class="btn {{ ($data->status == 'Active')?'btn-success':'btn-danger' }}">{{ $data->status }}</a></td>-->

                            <td>
                                <a href="{{ route('card.show', $data->id) }}" class="text-white">
                                    <span class="mr-2 btn btn-google-plus p-2">View Detail</span>
                                </a>

                                <a href="{{ route('card.edit', $data->id) }}" class="text-white">
                                    <span class="mr-2 btn btn-google-plus p-2">Edit</span>
                                </a>

                                <span> {!! Form::open([
                                          'method'=>'DELETE',
                                          'route' => ['card.destroy', $data->id],
                                          'style' => 'display:inline'
                                          ]) !!}
                                            {!! Form::button('<i class="fa fa-trash text-danger" aria-hidden="true"></i>', array(
                                          'type' => 'submit',
                                          'class' => 'btn btn-darker',
                                          'title' => 'Delete card',
                                          'onclick'=>'return confirm("Are you sure about deleting card?")'
                                          )) !!}
                                          {!! Form::close() !!}</span>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="7" class="text-center">No Record Found</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="card-footer py-4">
                    {{ $cardData->links('vendor.pagination.admin') }}
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
