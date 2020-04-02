@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/datatables/media/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">

@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">问诊</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">问诊</li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-info" onclick="createInquiry()">添加</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <label for="example-text-input" class="col-2 col-form-label text-right">患者姓名</label>
                        <div class="col-3">
                            <input class="form-control" placeholder="患者姓名" id="patient_name" readonly>
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label text-right">身份证号</label>
                        <div class="col-3">
                            <input class="form-control" placeholder="身份证号" id="ID_Number" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4">
                            <table id="tbl_guahao"  class="display nowrap table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">挂号</th>
                                    <th class="text-center">姓名</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-8">
                            <table id="tbl_inquiry" class="display nowrap table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">患者名</th>
                                    <th class="text-center">问诊次数</th>
                                    <th class="text-center">问诊日期</th>
                                    <th class="text-center">处方</th>
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
    </div>

    <div id="random">

    </div>

@endsection

@section('scripts')
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.15/api/fnReloadAjax.js"></script>
    <script src="{{ asset('static/admin/inquiry.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection
