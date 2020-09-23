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
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <a href="{{ url('/userprofile/create') }}" title="Tambah User Profile" class="btn btn-flat btn-success"><i class="fa fa-plus"></i> Tambah User Profile</a>
                </div>
                <div class="card-body">
                    <table id="userprofile" class="table table-hover table-responsive">
                        <thead>
                        <tr>

                            <th>Profile Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($userprofile as $key=>$value)
                                <tr>

                                    <td><a href="{{ url('userprofile/attribute/').'/'.$value->groupname }}">{{ $value->groupname }}</a> </td>
                                    <td><a href="{{ url('userprofile/attribute/add').'/'.$value->groupname }}" title="Tambah Attribute"><i class="fa fa-plus"></i> Tambah Attribute </a> | <a href="#" id="{{ $value->groupname }}" onclick="event.preventDefault(); deleteProfile(this.id);" title="Delete"><i class="fa fa-trash text-danger"></i> Hapus </a> </td>
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
            $('#userprofile').DataTable({
                "responsive": true,
                "autoWidth": false,
            })
        })

        function deleteProfile(id) {
            Swal.fire({
                title: 'Apa anda yakin?',
                text: "User Profile akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.value===true) {
                $.ajax({
                    url:"userprofile/"+id,
                    method:'POST',
                    dataType:'json',
                    data:{
                        _token:'{{ csrf_token() }}',
                        id:id,
                        _method:'DELETE'
                    },success:function (s) {
                        if(s){
                            Swal.fire(
                                'Berhasil',
                                'User Profile dihapus',
                                'success'
                            ).then((result)=>{
                                location.reload()
                        })

                        }
                    }
                })
            }
        })
        }

    </script>
@endsection