@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/clockpicker/dist/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">药材管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">编辑药价</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <label class="col-md-5 text-right col-form-label"></label>

                    <form id="price-form" method="post" data-parsley-validate="" enctype="multipart/form-data">
                        @if(!empty($medicines))
                        @foreach($medicines as $medicine)
                            <div class="row mt-3">
                                <label class="col-md-5 text-right col-form-label">{{$medicine->name}}</label>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="price_{{$medicine->id}}" id="price_{{$medicine->id}}" value="{{$medicine->price}}" data-parsley-type="digits" min="0"/>
                                </div>
                                <label class="col-md-3 col-form-label">{{$medicine->unit=='两'?'￥/两':'￥/10g'}}</label>
                            </div>
                        @endforeach
                        @endif
                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success"><i class="ti-save"></i> 保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!--Sky Icons JavaScript -->
    <script src="{{asset('static/plugin/skycons/skycons.js')}}"></script>
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('static/plugin/morrisjs/morris.min.js')}}"></script>
    <script src="{{asset('static/plugin/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <!-- Chart JS -->
    {{--    <script src="{{asset('static/js/dashboard4.js')}}"></script>--}}
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/plugin/calendar/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{ asset('static/plugin/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{ asset('static/plugin/popper/popper.min.js')}}"></script>
    <script src="{{ asset('static/admin/medicine_price.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/plugin/clockpicker/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.extra.js') }}"></script>

@endsection
