@extends('admin.layout.admin')

@section('styles')
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
                <li class="breadcrumb-item active">收入记录</li>
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
                                <div class="col-md-5">
                                    <h4 class="card-title">收入金额趋势</h4>
                                </div>
                                <div class="col-md-7 text-right">
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="chart2" width="100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chart-container {
            position: relative;
            margin: auto;
            height: 80vh;
            width: calc(90vw - 240px);
        }

    </style>
@endsection

@section('scripts')
    <!--morris JavaScript -->
    <script src="{{ asset('static/plugin/Chart.js/Chart.js') }}"></script>
    <script src="{{ asset('static/admin/doctor_income.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection
