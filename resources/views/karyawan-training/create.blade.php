@extends('layouts.app')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{__('Add Training Karyawan')}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('Home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('Add Training Karyawan')}}</li>
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
                        <form role="form" method="POST" class="needs-validation form-detail" novalidate id="form-karyawan" action="@empty($karyawan) {{ url('/training-karyawan/store') }} @else {{ url('/training-karyawan/update') }} @endempty">
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
                                @if (!empty($karyawan))
                                    <input type="hidden" name="id" value="{{$karyawan->id}}">
                                @endif
                                <div class="form-group validate">
                                    <label for="nama_karyawan">karyawan<span style="color:red;">*</span></label>
                                    <select name="karyawan" id="karyawan" class="form-control">
                                        <option value="">--Select karyawan--</option>
                                        @foreach ($karyawans as $karyawan)
                                            <option value="{{$karyawan->id}}" @if(!empty($pegawai) && $karyawan->id == $pegawai->id) selected @endif>{{$karyawan->nama_karyawan}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jenis <span style="color:red;">*</span></label>
                                    <div class="form-group validate">
                                        <div class="sepH_a">
                                            <a href="#" class="btn btn-link btn-xs str_select_all">Select All</a> |
                                            <a href="#" class="btn btn-link btn-xs str_deselect_all">Deselect All</a>
                                        </div>
                                        <div>
                                            <select multiple="multiple" id="jenis" name="trainings[]" class="multi-select multi-select-jenis jenis" data-label="Participant" required>
                                                @if (!empty($trainings))
                                                    @foreach ($trainings as $training)
                                                        <option value="{{ $training->id }}" @if(!empty($pegawais_selected) && in_array($training->id, $pegawais_selected)) selected @endif>{{ $training->jenis }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{url('training-karyawan')}}" class="btn btn-default btn-sx">Cancel</a>
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
    
    <script src="{{ asset('js/karyawan.js') }}"></script>

@endpush

