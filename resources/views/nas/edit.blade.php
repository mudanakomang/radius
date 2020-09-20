@extends('layouts.master')
@section('title')
    <title>{{ env('APP_NAME')}} | NAS</title>
@endsection
@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">NAS</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/nas') }}">NAS</a></li>
                        <li class="breadcrumb-item active">Edit NAS</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-8 col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit NAS</h3>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    {!! Form::model($nas,['route'=>['nas.update','id'=>$nas->id],'method'=>'POST'],['class'=>'form-horizontal']) !!}
                    <div class="form-group ">
                        {!! Form::label('nasname') !!}
                        <input type="text" id="nasname" name="nasname" class="form-control  @error('nasname') is-invalid @enderror" value="{{ $nas->nasname }}" placeholder="NAS Name" >
                        @error('nasname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('shortname') !!}
                        <input type="text" id="shortname" name="shortname" class="form-control  @error('shortname') is-invalid @enderror" value="{{ $nas->shortname }}" placeholder="NAS Shortname" >
                        @error('shortname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('type') !!}
                        <input type="text" id="type" name="type" class="form-control  @error('type') is-invalid @enderror" value="{{ $nas->type }}" placeholder="NAS Type" >
                        @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('secret') !!}
                        <input type="text" id="secret" name="secret" class="form-control  @error('secret') is-invalid @enderror" value="{{ $nas->secret }}" placeholder="NAS Secret" >
                        @error('secret')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('description') !!}
                        <input type="text" name="description" id="description" class="form-control  @error('description') is-invalid @enderror" value="{{ $nas->description }}" placeholder="Short description" >
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    {!! Form::submit('Simpan',['class'=>'btn btn-flat btn-success']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>

    </script>
@endpush