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
                        <li class="breadcrumb-item active">NAS</li>
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
                <a href="{{ url('/nas/create') }}" title="Tambah NAS" class="btn btn-flat btn-success"><i class="fa fa-plus"></i> Tambah NAS</a>
            </div>
            <div class="card-body">
                <table id="tablenas" class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NAS Name / IP</th>
                            <th>NAS Short Name</th>
                            <th>NAS type</th>
                            <th>Secret</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nas as $key=>$value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->nasname }}</td>
                                <td>{{ $value->shortname }}</td>
                                <td>{{ $value->type }}</td>
                                <td>{{ $value->secret }}</td>
                                <td>{{ $value->description }}</td>
                                <td><a href="{{url('/nas/edit').'/'.$value->id }}" title="Edit"><i class="fa fa-edit"></i> Edit</a> | <a href="#" onclick="event.preventDefault();deleteNas(this.id)" id="nas-{{$value->id}}" title="Delete"><i class="fa fa-trash text-danger"></i> Delete</a> </td>
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
            $('#tablenas').DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        })
        function deleteNas(id) {
            var dataid=id.split('-')[1]
            Swal.fire({
                title: 'Apa anda yakin?',
                text: "Data NAS akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.value===true) {
                    $.ajax({
                        url:'{{ route('nas.delete') }}',
                        method:'POST',
                        dataType:'json',
                        data:{
                            _token:'{{ csrf_token() }}',
                            id:dataid
                        },success:function (s) {
                            if(s){
                                Swal.fire(
                                    'Berhasil',
                                    'NAS telah dihapus',
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