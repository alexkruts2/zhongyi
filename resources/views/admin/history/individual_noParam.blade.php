@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/datatables/media/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">

@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">病历列表</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">个人病历</li>
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

                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    输入身份证号
                    <a href="/doctor/history/all" type="button" class="close" >&times;</a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <input class="form-control" id="id_number" name="id_number" type="text" required="" placeholder="请输入身份证号">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="inputIDNumber()"><i class="ti-pencil"></i> 输入</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!--morris JavaScript -->
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{ asset('static/admin/history.js') }}"></script>

@endsection
