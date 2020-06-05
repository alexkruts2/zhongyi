@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/datatables/media/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
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
                <li class="breadcrumb-item active">付费管理</li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-info" id="btn_payment" onclick="location.href='/doctor/accept/payment/create'">交费</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3 table-responsive">
                        <table id="tbl_jiaofei" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">时间</th>
                                <th class="text-center">挂号</th>
                                <th class="text-center">姓名</th>
                                <th class="text-center">手机号</th>
                                <th class="text-center">费用（￥）</th>
                                <th class="text-center">科室</th>
                                <th class="text-center">医生</th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog" style="display:none" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    二维码
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3 text-right col-form-label">付款方式</div>
                        <div class="col-8">
                            <select class="form-control" id="pay_type">
                                <option value="">－－－请选择付款方式－－－</option>
                                <option value="支付宝">支付宝</option>
                                <option value="微信支付">微信支付</option>
                                <option value="现金支付">现金支付</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <h4 class="card-title">微信二维码:</h4>
                            <img src="{{$weixin_url}}" alt="微信二维码" class="w-100"/>
                        </div>
                        <div class="col-6">
                            <h4 class="card-title">支付宝二维码:</h4>
                            <img src="{{$zhifubao_url}}" alt="支付宝二维码" class="w-100"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="payTreatment()"><i class="fab fa-amazon-pay"></i> 付款 </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ti-pencil"></i> 取消</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="treat_id"/>
    <div id="random">
    </div>

@endsection
<form method="post" action="#" id="printJS-form" style="position:absolute">

</form>

@section('scripts')
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{ asset('static/admin/payment.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/plugin/print/print.min.js') }}"></script>

@endsection
