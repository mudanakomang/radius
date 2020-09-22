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
                        <li class="breadcrumb-item active">RADIUS User</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-9 col-lg-9 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <a href="{{ url('user/create') }}" title="Tambah RADIUS User" class="btn btn-flat btn-success"><i class="fa fa-plus"></i> Tambah RADIUS User</a>
                </div>
                <div class="card-body">
                    <table id="usertable" class="table table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Password</th>
                            <th>User Group / Profile</th>
                            <th>Status</th>
                            <th>Shared User</th>
                            <th>Bandwidth Usage/Limit</th>
                            <th>Session Usage/Limit</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $key=>$value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->username }}</td>
                                <td>{{ $value->value }}</td>
                                <td>{{ $value->userGroup->groupname }}</td>
                                <td>{{ $accts[$value->username]['status']=='off' ? 'Offline':'Online' }} </td>
                                <td>{{ $accts[$value->username]['shared'] }} </td>
                                <td>{{ $accts[$value->username]['bwusage']==null ? "-":round($accts[$value->username]['bwusage']/1024,2,2)." MB" }} / {{$accts[$value->username]['bwlimit']==null ? "-":round($accts[$value->username]['bwlimit']/1024,2,2)." MB"}} </td>
                                <td>{{ $accts[$value->username]['sessionusage']==null ? "-":round($accts[$value->username]['sessionusage']/3600,2,2)." Jam" }} / {{$accts[$value->username]['sessionlimit']==null ? "-":round($accts[$value->username]['sessionlimit']/3600,2,2)." Jam"}} </td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#usertable').DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        })
        {{--function deleteNas(id) {--}}
            {{--var dataid=id.split('-')[1]--}}
            {{--Swal.fire({--}}
                {{--title: 'Apa anda yakin?',--}}
                {{--text: "Data NAS akan dihapus",--}}
                {{--icon: 'warning',--}}
                {{--showCancelButton: true,--}}
                {{--confirmButtonColor: '#3085d6',--}}
                {{--cancelButtonColor: '#d33',--}}
                {{--confirmButtonText: 'Ya, Hapus'--}}
            {{--}).then((result) => {--}}
                {{--if (result.value===true) {--}}
                {{--$.ajax({--}}
                    {{--url:'{{ route('nas.delete') }}',--}}
                    {{--method:'POST',--}}
                    {{--dataType:'json',--}}
                    {{--data:{--}}
                        {{--_token:'{{ csrf_token() }}',--}}
                        {{--id:dataid--}}
                    {{--},success:function (s) {--}}
                        {{--if(s){--}}
                            {{--Swal.fire(--}}
                                {{--'Berhasil',--}}
                                {{--'NAS telah dihapus',--}}
                                {{--'success'--}}
                            {{--).then((result)=>{--}}
                                {{--location.reload()--}}
                        {{--})--}}

                        {{--}--}}
                    {{--}--}}
                {{--})--}}
            {{--}--}}
        {{--})--}}
        {{--}--}}
    </script>
@endsection