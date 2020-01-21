@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/datatables/media/css/jquery.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">病历列表</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">病历列表</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-3 table-responsive">
                        <table id="tbl_yield" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">挂号</th>
                                <th class="text-center">患者名</th>
                                <th class="text-center">病名</th>
                                <th class="text-center">问诊日期</th>
                                <th class="text-center">处方</th>
                                <th class="text-center">状态</th>
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
    <script src="{{ asset('static/admin/yield.js') }}"></script>

@endsection
