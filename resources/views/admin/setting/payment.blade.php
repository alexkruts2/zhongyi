@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/dropify/dist/css/dropify.min.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">系统设置</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">付费设置</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="setting-form" novalidate method="post">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="card-title">微信二维码：</h4>
                                <input type="file" id="weixin_image" name="weixin_image" class="dropify" data-default-file="{{$weixin_url}}"/>
                            </div>
                            <div class="col-6">
                                <h4 class="card-title">支付宝二维码：</h4>
                                <input type="file" id="zhifubao_image" name="zhifubao_image" class="dropify" data-default-file="{{$zhifubao_url}}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center m-t-20">
                                <button type="submit" class="btn btn-success"><i class="ti-save"></i> 保存</button>
                            </div>
                        </div>
                    </form>
                    <div class="form-group mt-3 row">
                        <label for="example-text-input" class="col-md-2 col-form-label text-right">挂号费</label>
                        <div class="col-md-6">
                            <input class="form-control" type="number" name='accept_price' id="accept_price" placeholder="接受费" value="{{$accept_price}}"/>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" onclick="saveAccept();"><i class="ti-save"></i> 保存</button>
                        </div>
                    </div>
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
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/plugin/calendar/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{ asset('static/plugin/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{ asset('static/plugin/popper/popper.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/plugin/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="{{ asset('static/admin/setting.js') }}"></script>

@endsection
