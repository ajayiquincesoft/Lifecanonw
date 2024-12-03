@extends('layouts.admin')

@section('content')

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Certs Detail</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{ route('certs.create') }}" class="btn btn-neutral">Add New Certs</a>
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

                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Detail</th>
                            <th scope="col">Address</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @if(count($datas)> 0)
                        @foreach($datas as $key =>$data)
                        <tr>
                            <td class="budget">{{ $data->id }}</td>
                            <td>{{ $data->FirstName }} {{ $data->LastName }}</td>
                            <td>
                                {{ $data->Email }} <br/>
                                {{ $data->Mobile }} <br/>
                            </td>
                            <td>
                                Height -  {{ $data->Height }} <br/>
                                Eye - {{ $data->EyeColor }}
                            </td>
                            <td>{{ $data->Address }}</td>

                            <td>


                                <span> {!! Form::open([
                                          'method'=>'POST',
                                          'route' => ['certs.copy', $data->id],
                                          'style' => 'display:inline'
                                          ]) !!}
                                            {!! Form::button('<i class="fa fa-clone" aria-hidden="true"></i> Convert to Card', array(
                                          'type' => 'submit',
                                          'class' => 'btn btn-google-plus',
                                          'title' => 'Convert to card',
                                          'onclick'=>'return confirm("Are you sure convert to Cards?")'
                                          )) !!}
                                          {!! Form::close() !!}</span>

                                <a href="{{ route('certs.edit', $data->id) }}" class="text-white">
                                    <span class="mr-2 btn btn-google-plus p-2">Edit</span>
                                </a>

                                <span> {!! Form::open([
                                          'method'=>'DELETE',
                                          'route' => ['certs.destroy', $data->id],
                                          'style' => 'display:inline'
                                          ]) !!}
                                            {!! Form::button('<i class="fa fa-trash text-danger" aria-hidden="true"></i>', array(
                                          'type' => 'submit',
                                          'class' => 'btn btn-darker',
                                          'title' => 'Delete certs',
                                          'onclick'=>'return confirm("Are you sure about deleting certs?")'
                                          )) !!}
                                          {!! Form::close() !!}</span>
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
                    {{ $datas->links('vendor.pagination.admin') }}
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
