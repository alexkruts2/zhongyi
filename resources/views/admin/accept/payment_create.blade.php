@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/clockpicker/dist/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/plugin/print/print.min.css')}}">

@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">付费管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">付费</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div id="payment-form" novalidate method="post" style="display: none;">
                        <input type="hidden" id="treat_id" name="treat_id" />
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">挂号</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='guahaoID' id="guahaoID"  readonly="readonly"/>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">姓名</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='patient_name' id="patient_name" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">处方</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='recipe' id="recipe" readonly="readonly"/>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">药费</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='price' id="price" readonly="readonly"/>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-6">
                                <h4 class="card-title">微信二维码：</h4>
                                <img src="{{$weixin_url}}" alt="微信二维码" class="w-100">
                            </div>
                            <div class="col-6">
                                <h4 class="card-title">支付宝二维码：</h4>
                                <img src="{{$zhifubao_url}}" alt="支付宝二维码" class="w-100">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-info" onclick="payTreatment()"><i class="fab fa-amazon-pay"></i> 付款</button>
                                <button type="button" class="btn btn-danger" onclick="location.href='/doctor/accept/payment/list'"><i class="ti-close"></i> 取消</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    输入挂号
                    <a href="/doctor/accept/payment/list" type="button" class="close" >&times;</a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <input class="form-control" id="guahao" name="guahao" type="text" required="" placeholder="请输入挂号">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="inputGuahao()"><i class="ti-pencil"></i> 确认</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!--Sky Icons JavaScript -->
    <!-- Chart JS -->
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/admin/payment.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection
