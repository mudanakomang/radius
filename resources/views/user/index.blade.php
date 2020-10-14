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
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <a href="{{ url('user/create') }}" title="Generate User" class="btn btn-flat btn-success"><i class="fa fa-plus"></i> Generate User</a>
                </div>
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <table id="usertable" class="table table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Akses</th>
                            <th>User Group / Profile</th>
                            <th>Status</th>
                            <th>Shared User</th>
                            <th>First Login</th>
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
                                @if( $value->userGroup == null)
                                    <td>-</td>
                                @else
                                    <td>{{ $value->userGroup->groupname }}</td>
                                @endif
                                <td>@if(count($value->acct)==0)
                                        <span class="badge badge-success">Belum Digunakan</span>
                                        @else
                                        <span class="badge badge-danger">Sudah Digunakan</span>
                                    @endif
                                </td>
{{--                                <td>{{ $accts[$value->username]==$useracct[$value->username] ? 'Used':'Unused' }} </td>--}}
                                <td>{{ $accts[$value->username]['shared'] }} </td>
                                <td>{{ count($value->acct)>0 ? \Carbon\Carbon::parse($value->acct->sortByDesc('acctstarttime')->first()->acctstarttime)->format('Y-m-d H:i:s'):"" }}</td>
                                <td>{{ $accts[$value->username]['bwusage']==null ? "-":round($accts[$value->username]['bwusage']/1024/1024,2,2)." MB" }} / {{$accts[$value->username]['bwlimit']==null ? "-":round($accts[$value->username]['bwlimit']/1024/1024,2,2)." MB"}} {{$accts[$value->username]['bwtype']}}</td>
                                <td>{{ $accts[$value->username]['sessionusage']==null ? "-":round($accts[$value->username]['sessionusage']/3600,2,2)." Jam" }} / {{$accts[$value->username]['sessionlimit']==null ? "-":round($accts[$value->username]['sessionlimit']/3600,2,2)." Jam"}} {{$accts[$value->username]['sestype']}}</td>
                                <td><a href="{{ url('user').'/'.$value->id.'/edit' }}" title="Edit User"><i class="fa fa-edit"></i> Edit</a> | <a href="#" onclick="event.preventDefault();deleteUser(this.id)" id="{{ $value->id }}" title="Hapus User"><i class="fa fa-trash text-danger"></i> Hapus</a> </td>
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


           var table= $('#usertable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "dom": 'l<"toolbar">frtip',
            });
            $("div.toolbar").html('<input type="checkbox" id="used"> Digunakan <br> <input type="checkbox" id="unused"> Belum Digunakan');

            $('#used , #unused').on('change',function () {
               if($('#used').is(':checked') && $('#unused').is(':checked')){
                   $.fn.dataTable.ext.search.pop()
               }else if ($('#used').is(':checked')){
                   $.fn.dataTable.ext.search.push(
                       function( settings, data, dataIndex ) {
                           return data[3]=='Sudah Digunakan'
                       }
                   )
               }else if($('#unused').is(':checked')){
                   $.fn.dataTable.ext.search.push(
                       function( settings, data, dataIndex ) {
                           return data[3]=='Belum Digunakan'
                       }
                   )
               }else{
                   $.fn.dataTable.ext.search.pop()
               }


               table.draw()
            })

        })
        function deleteUser(id) {
            Swal.fire({
                title: 'Apa anda yakin?',
                text: "Username akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.value===true) {
                $.ajax({
                    url:'{{ route('user.delete') }}',
                    method:'POST',
                    dataType:'json',
                    data:{
                        _token:'{{ csrf_token() }}',
                        id:id
                    },success:function (s) {
                        if(s){
                            Swal.fire(
                                'Berhasil',
                                'Username dihapus',
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
