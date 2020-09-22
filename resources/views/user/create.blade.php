@extends('layouts.master')
@section('title')
    <title>{{ env('APP_NAME')}} | RADIUS User</title>
@endsection
@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">RADIUS User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/user') }}">RADIUS User</a></li>
                        <li class="breadcrumb-item active">Tambah RADIUS User</li>
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
                    <h3 class="card-title">Tambah RADIUS User</h3>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <form action="{{ route('user.store') }}" method="POST" >
                        @csrf
                        <div class="form-group ">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-control  @error('username') is-invalid @enderror" value="{{ old('username') }}"  placeholder="Username" >
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" id="password" name="password" class="form-control  @error('password') is-invalid @enderror" value="{{ old('password') }}"  placeholder="Password" >
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="profile">Profile</label>
                            <select name="profile" id="profile" class="form-control @error('profile') is-invalid @enderror">
                                <option value="">Pilih Profile</option>
                                @foreach(\App\RadUserGroup::get()->unique('groupname') as $group)
                                    <option value="{{ $group->groupname }}">{{ $group->groupname }}</option>
                                    @endforeach
                            </select>
                            @error('profile')
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