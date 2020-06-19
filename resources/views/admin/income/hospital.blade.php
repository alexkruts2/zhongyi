@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/datatables/media/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">收入记录</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">医院收入</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mt-3 form-group">
                                <div class="col-md-3">
                                    <select class="form-control" name='hospital' id="hospital" data-parsley-required>
                                        <option value="">--全部医院--</option>
                                        @foreach ($hospitals as $hospital)
                                            <option value="{{$hospital->id}}">{{$hospital->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name='department' data-parsley-required>
                                        <option value="">--全部科室--</option>
                                        @foreach ($departments as $department)
                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="from" class="form-control" placeholder="年 - 月 - 日  时 : 分（从）">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="to" class="form-control" placeholder="年 - 月 - 日  时 : 分（到）">
                                </div>
                                <div class="col-md-1">
                                    <button class="btn-md btn btn-success" type="button" onclick="drawHospitalTable();">提交</button>
                                </div>
                        </div>
                            <div class="row mt-3 ">
                                <div class="table-responsive">
                                    <table id="tbl_hospital" class="display nowrap table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">序号</th>
                                                <th class="text-center">医院分科</th>
                                                <th class="text-center">医生</th>
                                                <th class="text-center">患者姓名</th>
                                                <th class="text-center">医院收入</th>
                                                <th class="text-center">挂号金额</th>
                                                <th class="text-center">药品金额</th>
                                                <th class="text-center">日期</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-3">
                                总计:<b id="totalSum">0</b> ¥
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .footer {
            margin-left:0;
        }
    </style>
@endsection

@section('scripts')
    <!--morris JavaScript -->
    <script src="{{ asset('static/plugin/moment/moment.js') }}"></script>
    <script src="{{ asset('static/plugin/moment/moment-with-locales.js') }}"></script>
    <script src="{{ asset('static/plugin/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
        <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>

        <script src="{{ asset('static/admin/income.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection
