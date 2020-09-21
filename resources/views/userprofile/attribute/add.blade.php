@extends('layouts.master')
@section('title')
    <title>{{ env('APP_NAME')}} | Attribute</title>
@endsection
@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Attribute</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/userprofile') }}">User Profile</a></li>
                        <li class="breadcrumb-item active">Tambah Attribute</li>
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
                    <h3 class="card-title">Tambah Attribute</h3>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <form action="{{ route('userprofile.attribute.store') }}" method="POST" >
                        <input type="hidden" name="groupname" value="{{ $userprofile->groupname }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <input type="checkbox" name="quotacheck"   id="quotacheck" value="quotaenabled"> <label for="">Quota Limit</label>
                                <select name="quota" id="quota" class="form-control @error('quota') is-invalid @enderror" disabled>
                                    <option value="">Pilih Attribute</option>
                                    <option value="Monthly-Bandwidth">Bandwidth Bulanan</option>
                                    <option value="Weekly-Bandwidth">Bandwidth Mingguan</option>
                                    <option value="Daily-Bandwidth">Bandwidth Harian</option>
                                    <option value="Total-Bandwidth">Bandwidth Total</option>
                                </select>
                                @error('quota')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <label for="quotavalue">Value</label>
                                <input type="number" id="quotavalue" name="quotavalue" class="form-control @error('quotavalue') is-invalid @enderror" autocomplete="off" placeholder="Value Dalam MB">
                                @error('quotavalue')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input type="checkbox" name="timecheck"  id="timecheck" value="timeenabled"> <label for="">Time Limit</label>
                                <select name="time" id="time" class="form-control" disabled>
                                    <option value="">Pilih Attribute</option>
                                    <option value="Max-Monthly-Session">Session Bulanan</option>
                                    <option value="Max-Weekly-Session">Session Mingguan</option>
                                    <option value="Max-Daily-Session">Session Harian</option>
                                    <option value="Max-All-Session">Session Total</option>
                                </select>
                                <label for="timevalue">Value</label>
                                <input type="number"  id="timevalue" name="timevalue" class="form-control" autocomplete="off" placeholder="Value Dalam Jam">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input type="checkbox" name="sharedcheck"  id="sharedcheck" value="sharedenabled"> <label for="">Shared User</label><br>
                                <label for="sharedvalue">Value</label>
                                <input type="number" id="sharedvalue" name="sharedvalue" class="form-control"  autocomplete="off" placeholder="Shared user, default 1">
                            </div>
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
@section('script')
    <script>
     $(function () {
         $('#quotavalue').hide()
         $('#timevalue').hide()
         $('#sharedvalue').hide()
         $('#quotacheck').on('click',function () {
            var state=this.checked
             state ? enableElem($('#quota')): disableElem($('#quota')) ; hideElem($('#quotavalue'))
         })
         $('#quota').on('change',function () {
             this.value==='' ? hideElem( $('#quotavalue')):showElem( $('#quotavalue'))
         })

         $('#timecheck').on('click',function () {
             var state=this.checked
             state ? enableElem($('#time')): disableElem($('#time')) ; hideElem($('#timevalue'))
         })
         $('#time').on('change',function () {
             this.value==='' ? hideElem($('#timevalue')):showElem($('#timevalue'))
         })

         $('#sharedcheck').on('click',function () {
             var state=this.checked
             state ? showElem($('#sharedvalue')): hideElem($('#sharedvalue'))
         })
     })

        function disableElem(elem) {
            elem.attr('disabled',true)
            elem.val('')
        }
        function enableElem(elem) {
            elem.attr('disabled',false)
        }
        function showElem(elem) {
            elem.show()
        }
        function hideElem(elem) {
            elem.val('')
            elem.hide()
        }
    </script>
@endsection