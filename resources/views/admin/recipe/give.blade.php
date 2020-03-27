@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">药方管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">发药</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mt-3 row">
                        <label for="example-text-input" class="col-md-2 col-form-label text-right">挂号</label>
                        <div class="col-md-6">
                            <input class="form-control" type="number" name='guahao' id="guahao" placeholder="挂号" />
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success" onclick="getGuahaoData();"><i class="ti-check-box"></i> 查验</button>
                            <button type="button" class="btn btn-info" onclick="giveMedicine();" disabled id="giveMedicine"><i class="ti-wand"></i> 发药</button>

                        </div>
                    </div>
                    <div id="medicineSection" class="mt-3">
                    </div>
                    <div class="row mt-3"><div class="col-sm-1"></div>
                        <hr class="col-sm-10"> <div class="col-sm-2"></div> <h4 class="text-bold col-sm-10">总价：<span id="total_price_span"></span>元</h4>
                    </div>

                    <input type="hidden" id="medicines"/>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="{{asset('static/plugin/skycons/skycons.js')}}"></script>
    <script src="{{asset('static/plugin/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('static/plugin/morrisjs/morris.min.js')}}"></script>
    <script src="{{asset('static/plugin/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/plugin/calendar/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{ asset('static/plugin/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{ asset('static/plugin/popper/popper.min.js')}}"></script>
    <script src="{{ asset('static/plugin/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('static/admin/give.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection
