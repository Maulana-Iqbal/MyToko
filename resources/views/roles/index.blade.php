@extends('layouts.app')


@section('content')


<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Manajemen Role</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->can('role') or auth()->user()->can('role-create'))
                <a class="btn btn-primary mb-2" href="{{ route('roles.create') }}"><i class="mdi mdi-plus-circle me-2"></i> Tambah Role</a>
                @endif
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
                @endif

                @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p>{{ $message }}</p>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            @can('show-all')
                            <th>Toko Created</th>
                            @endcan
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $role->alias }}</td>
                            @can('show-all')
                            <td>{{website($role->website_id)->nama_website}}</td>
                            @endcan
                            <td>
                                <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
                                @if(auth()->user()->can('role') or auth()->user()->can('role-edit'))
                                <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                @endif
                                @if(auth()->user()->can('role') or auth()->user()->can('role-delete'))
                                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                {!! Form::close() !!}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                {!! $roles->render() !!}

            </div>
        </div>
    </div>
</div>
@endsection