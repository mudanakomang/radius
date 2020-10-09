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
{{--                        CHECKBOX QUOTA--}}
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="cbquota" name="cbquota" class=" @error('cbquota') is-invalid @enderror" value="">
                                <label class="form-check-label" for="cbquota"><strong>Quota</strong></label>
                            </div>
                            @error('cbquota')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="quotadiv" style="display:none" class="form-group form-check form-check-inline">
                            <input type="number" id="quota" name="quota" class="form-check-input form-control  @error('quota') is-invalid @enderror" value="{{ old('quota') }}"  placeholder="Only Number" >
                            <select class="form-check-input form-control" name="sizetype" id="sizetype">
                                <option value="mb">MB</option>
                                <option value="gb">GB</option>
                            </select>
                            @error('quota')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                        </div>
{{--                        CHECKBOX TIME LIMIT--}}
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="cbtimelimit" name="cbtimelimit" class=" @error('cbtimelimit') is-invalid @enderror" value="">
                                <label class="form-check-label" for="cbtimelimit"><strong>Time Limit</strong></label>
                            </div>
                            @error('cbtimelimit')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="timelimitdiv" style="display:none" class="form-group form-check form-check-inline">
                            <input type="number" id="timelimit" name="timelimit" class="form-check-input form-control  @error('timelimit') is-invalid @enderror" value="{{ old('timelimit') }}"  placeholder="Only Number" >
                            <select class="form-check-input form-control" name="timetype" id="timetype">
                                <option value="jam">Jam</option>
                                <option value="menit">Menit</option>
                            </select>
                            @error('timelimit')
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
