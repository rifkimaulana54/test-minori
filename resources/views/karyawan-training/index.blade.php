@extends('layouts.app')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{__('Training Karyawan')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('Home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('Training Karyawan')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header with-border">
                            <div class="d-flex justify-content-between w-100" style="margin-bottom: 10px;">
                                <div class="d-flex">
                                    <a href="{{ url('training-karyawan/create') }}" class="btn btn-primary float-right"><i class="fas fa-fw fa-plus"></i> {{__('Add Training Karyawan')}}</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if(Session::has('flash_error'))
                                <div class="alert alert-danger text-center">{!! session('flash_error') !!}</div>
                            @endif
                            @if(Session::has('flash_success'))
                                <div class="alert alert-success text-center">{!! session('flash_success') !!}</div>
                            @endif
                            <div class="table-responsive">
                                <table id="karyawanList" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis</th>
                                            <th>Tgl Sertifikat</th>
                                            <th>NIP</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>	          
                                    <tbody>
                                        <tr>
                                            <td colspan="7">
                                            <center class="image-loading"><img src="{{asset('assets/img/loading.gif')}}" style="width: 64px;" /></center>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
                
            </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@push('js')
    <script type="text/javascript">
        var base_url = {!! json_encode(url('/')) !!};
    </script>
    <script src="{{ asset('js/training-karyawan.js') }}"></script>

@endpush

