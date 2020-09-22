@extends('layouts.master')
@section('title')
    <title>{{ env('APP_NAME')}} | Attribute List</title>
@endsection
@section('content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Attribute List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/userprofile') }}">User Profile</a></li>
                        <li class="breadcrumb-item active">Attribute List</li>
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
                </div>
                <div class="card-body">
                    <table id="attribute" class="table table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Attribute</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($attrs['check'] as $key=>$value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->attribute }}</td>
                                <td>@if(in_array($value->attribute,['Monthly-Bandwidth','Weekly-Bandwidth','Daily-Bandwidth','Total-Bandwidth']))
                                        {{ round($value->value/1024,2,2) }} MB
                                    @elseif(in_array($value->attribute,['Max-Monthly-Session','Max-Weekly-Session','Max-Daily-Session','Max-All-Session','Session-Timeout']))
                                        {{ round($value->value/3600,2,2) }} Jam
                                        @else
                                        {{ $value->value }}
                                    @endif
                                </td>
                                <td><a href="#" onclick="event.preventDefault();deleteAttr(this.id)" id="check-{{ $value->id }}" title="Hapus Attribute" ><i class="fa fa-trash text-danger"></i> Hapus </a></td>
                            </tr>
                        @endforeach
                        @foreach($attrs['reply'] as $key=>$value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->attribute }}</td>
                                <td>@if(in_array($value->attribute,['Monthly-Bandwidth','Weekly-Bandwidth','Daily-Bandwidth','Total-Bandwidth']))
                                        {{ round($value->value/1024,2,2) }} MB
                                    @elseif(in_array($value->attribute,['Max-Monthly-Session','Max-Weekly-Session','Max-Daily-Session','Max-All-Session','Session-Timeout']))
                                        {{ round($value->value/3600,2,2) }} Jam
                                    @else
                                        {{ $value->value }}
                                    @endif
                                </td>
                                <td><a href="#" onclick="event.preventDefault();deleteAttr(this.id)" id="reply-{{ $value->id }}" title="Hapus Attribute" ><i class="fa fa-trash text-danger"></i> Hapus </a></td>
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
            $('#attribute').DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        })
        function deleteAttr(id) {
            var type=id.split('-')[0]
            var id=id.split('-')[1]
            Swal.fire({
                title: 'Apa anda yakin?',
                text: "Attribute akan dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.value===true) {
                $.ajax({
                    url:'{{ route('userprofile.attribute.delete') }}',
                    method:'POST',
                    dataType:'json',
                    data:{
                        _token:'{{ csrf_token() }}',
                        id:id,
                        type:type
                    },success:function (s) {
                        if(s){
                            Swal.fire(
                                'Berhasil',
                                'Attribute telah dihapus',
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