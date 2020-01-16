@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">海潮仪表板</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">首页</a></li>
                <li class="breadcrumb-item active">仪表板</li>
            </ol>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-2 text-right">
            <select id="choosedays" class="selectpicker form-control" onchange="selectDays">
                <option  value="7">7天</option>
                <option value="30">30天</option>
                <option value="all" selected>全部</option>
            </select>
        </div>
    </div>

    <div class="row">
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-8"><h2 id="appUser">{{$app_users}}</h2>
                            <h6>APP 用户数</h6></div>
                        <div class="col-4 align-self-center text-right  p-l-0">
                            <div id="appChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-8"><h2 id="assistUser" class="">{{$wechat_users}}</h2>
                            <h6>小程序用户数</h6></div>
                        <div class="col-4 align-self-center text-right p-l-0">
                            <div id="wechatChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-8"><h2 id="project">{{$projects}}</h2>
                            <h6>项目数</h6></div>
                        <div class="col-4 align-self-center text-right p-l-0">
                            <div id="projectChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-8"><h2 id="article">{{$articles}}</h2>
                            <h6>文章数</h6></div>
                        <div class="col-4 align-self-center text-right p-l-0">
                            <div id="articleChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div>
                            <h4 class="card-title">用户分析</h4>
                        </div>
                        <div class="ml-auto">
                            <ul class="list-inline">
                                <li>
                                    <h6 class="text-muted text-success"><i class="fa fa-circle font-10 m-r-10 "></i>小程序用户</h6> </li>
                                <li>
                                    <h6 class="text-muted  text-info"><i class="fa fa-circle font-10 m-r-10"></i>App 用户</h6> </li>

                            </ul>
                        </div>
                    </div>
                    <div id="monthly_users" style="height: 405px;"></div>

                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-5">
            <!-- Column -->
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-actions">
                        <a class="" data-action="collapse"><i class="ti-minus"></i></a>
                        <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                        <a class="btn-close" data-action="close"><i class="ti-close"></i></a>
                    </div>
                    <h4 class="card-title m-b-0">分享统计</h4>
                </div>
                <div class="card-body collapse show">
                    <div id="shares-chart" class="ecomm-donute" style="height: 317px;"></div>
                    <ul class="list-inline m-t-20 text-center">
                        <li >
                            <h6 class="text-muted"><i class="fa fa-circle text-info"></i> 计算分享</h6>
                            <h4 class="m-b-0" id="share_calc">0</h4>
                        </li>
                        <li>
                            <h6 class="text-muted"><i class="fa fa-circle text-danger"></i> 护照分享</h6>
                            <h4 class="m-b-0" id="share_pass">0</h4>
                        </li>
                        <li>
                            <h6 class="text-muted"> <i class="fa fa-circle text-success"></i> 文章分享</h6>
                            <h4 class="m-b-0" id="share_article">0</h4>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('static/plugin/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('static/plugin/morrisjs/morris.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/admin/dashboard.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
@endsection
