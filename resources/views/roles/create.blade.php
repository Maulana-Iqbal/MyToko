@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Tambah Role Baru</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif


                {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                    <strong>Permission:</strong>
                        <br />
                        <div class="row">
                            @foreach($permission as $value)
                            @if($value->induk_id==null or $value->induk_id==0)
                            <div class="col-lg-4 col-sm-6 col-xs-12">
                                <div class="card">
                                    <div class="card-header">
                                        <span class="font-16 text-info">{{ strtoupper($value->name) }}</span>
                                    </div>
                                    <div class="card-body">
                                        <label><b>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                                {{ $value->name }}</b> <small>{{$value->keterangan??'Semua Akses'}}<br> @if($value->name=='show-all') (<b class="text-danger">Fitur Super Admin</b>) @endif </small></label>
                                        <br>
                                        @foreach($value->sub as $v)
                                        <label style="margin-left: 10px;">{{ Form::checkbox('permission[]', $v->id, false, array('class' => 'name')) }}
                                            {{ $v->name }} <small>{{$v->keterangan??'Hanya Fitur Ini'}}</small></label>
                                        <br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                {!! Form::close() !!}


            </div>
        </div>
    </div>
</div>
@endsection