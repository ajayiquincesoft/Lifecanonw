@extends('layouts.admin')

@section('content')

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">SMS logs</h6>
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

                    <form method="get" action="{{ route('admin.smsLogs') }}">
                        <div class="col-md-4" style="float: left">
                            <label class="text-white">Search</label>
                            <input type="text" name="search" value="{{ Request()->search }}" placeholder="search" class="form-control">
                        </div>
                        <div class="col-md-1 mt-md-4" style="float: left;">
                            <input type="submit" value="Search" class="btn btn-instagram">
                        </div>
                        <div class="col-md-1 mt-md-4" style="float: left;">
                            <a href="{{ route('admin.smsLogs') }}" class="btn btn-instagram" style="width;110%">Clear</a>
                        </div>
                    </form>

                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Sr.</th>
                            <th scope="col">From</th>
                            <th scope="col">To</th>
                            <th scope="col">Message</th>
                            <th scope="col">Date</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @if(count($smslogs)> 0)
                        @foreach($smslogs as $key =>$data)
                        <tr>
                            <td class="budget">{{ ++$key }}</td>
                            <td class="budget">{{ $data->from }}</td>
                            <td>{{ $data->to }}</td>
                            <td>
                                {{ $data->body }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($data->dateSent)->format('d M Y h:i a')}}
                            </td>


                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="5" class="text-center">No Record Found</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="card-footer py-4">

                </div>

            </div>
        </div>
    </div>

</div>

@endsection
