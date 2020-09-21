@extends('layouts.master')
@section('title')
    <title>{{ env('APP_NAME')}} | User Profile</title>
@endsection
@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">User Profile</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/userprofile') }}">User Profile</a></li>
                        <li class="breadcrumb-item active">Tambah User Profile</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4 col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah User Profile</h3>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <form action="{{ route('userprofile.store') }}" method="POST" >
                        @csrf
                        <div class="form-group ">
                            <label for="profilename">User Profile Name</label>
                            <input type="text" id="profilename" name="profilename" class="form-control  @error('profilename') is-invalid @enderror" value="{{ old('profilename') }}"  placeholder="User Profile Name" >
                            @error('profilename')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-flat btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>

    </script>
@endpush