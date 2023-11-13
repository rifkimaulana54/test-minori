@extends('layouts.app')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{__('Add Karyawan')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('Home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('Add Karyawan')}}</li>
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
                        <div class="card-header">
                            <h3 class="card-title">
                                Form Karyawan
                            </h3>
                        </div>
                        <form role="form" method="POST" class="needs-validation form-detail" novalidate id="form-training" action="@empty($training) {{ url('/training/store') }} @else {{ url('/training/update') }} @endempty">
                            <div class="card-body">
                                @if(Session::has('flash_error'))
                                    <div class="alert alert-danger text-center">{!! session('flash_error') !!}</div>
                                @endif
                                @if(Session::has('flash_success'))
                                    <div class="alert alert-success text-center">{!! session('flash_success') !!}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                {{ csrf_field() }}
                                @if (!empty($training))
                                    <input type="hidden" name="id" value="{{$training->id}}">
                                @endif
                                <div class="form-group validate">
                                    <label for="jenis">Jenis<span style="color:red;">*</span></label>
                                    <input type="text" name="jenis" class="form-control" id="jenis" placeholder="Enter jenis" @if(!empty($training)) value="{{$training->jenis}}" @endif>
                                </div>
                                <div class="form-group validate">
                                    <label for="tgl_sertifikat">Nama training<span style="color:red;">*</span></label>
                                    <input type="date" name="tgl_sertifikat" class="form-control" id="tgl_sertifikat" placeholder="Enter nama training" @if(!empty($training)) value="{{$training->tgl_ssertifikat}}" @endif>
                                </div>
                                <div class="form-group validate">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea name="keterangan" id="" class="form-control" cols="30" rows="10">@if(!empty($training->jabatan)) {{$training->jabatan}} @endif</textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{url('training')}}" class="btn btn-default btn-sx">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-sx btnSubmit">Submit</button>
                            </div>
                        </form>
                    </div>
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
    
    <script src="{{ asset('js/training.js') }}"></script>

@endpush

