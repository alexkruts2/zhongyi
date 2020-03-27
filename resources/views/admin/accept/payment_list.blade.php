@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/datatables/media/css/jquery.dataTables.min.css')}}" rel="stylesheet">
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
            <button type="button" class="btn btn-info" id="btn_payment" onclick="location.href='/doctor/accept/payment/create'">添加</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3 table-responsive">
                        <table id="tbl_payment" class="display nowrap table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">付费时间</th>
                                    <th class="text-center">姓名</th>
                                    <th class="text-center">处方</th>
                                    <th class="text-center">费用（￥）</th>
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

    <div id="random">

    </div>

@endsection

@section('scripts')
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{ asset('static/admin/payment.js') }}"></script>

@endsection
