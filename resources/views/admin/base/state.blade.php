@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">省(州)</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">省(州)列表</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="selectpicker form-control" data-style="btn-info" id="country" onchange="searchState(this)">
                                <option value="0">--请选择国家--</option>
                                @foreach(\App\Country::orderBy('name_cn', 'asc')->get() as $country)
                                    <option value="{{$country->id}}">{{$country->name_cn}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" onclick="showStateModal(true, 0, null)"><i class="ti-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblState" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">国家</th>
                                <th class="text-center">中文名称</th>
                                <th class="text-center">英文名称</th>
                                <th class="text-center">英文缩写</th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="state-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">省(州)信息</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="state-form" novalidate>
                        <div class="modal-body">
                            <div class="form-group">
                                <select class="selectpicker form-control" data-style="btn-info" id="country_id" name="country_id" onchange="searchState(this)">
                                    @foreach(\App\Country::orderBy('name_cn', 'asc')->get() as $country)
                                        <option value="{{$country->id}}">{{$country->name_cn}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <div class="col-6">
                                    <h5> 中文名称 : <span class="text-danger">*</span> </h5>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="name_cn" name="name_cn" required data-validation-required-message="必填" value="" placeholder="请输入中文名称...">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5> 英文名称 : <span class="text-danger">*</span> </h5>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="name_en" name="name_en" required data-validation-required-message="必填" value="" placeholder="请输入英文名称...">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <h5> 英文缩写 </h5>
                                <div class="controls">
                                    <input type="text" class="form-control" id="name_en_abbr" name="name_en_abbr" value="" placeholder="请输入英文缩写...">
                                </div>
                            </div>
{{--                            <input id="country_id" name="country_id" type="hidden" value="">--}}
                            <input id="state_id" name="state_id" type="hidden" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-danger"><i class="ti-save"></i> 保存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{ asset('static/js/pages/validation.js') }}"></script>
    <script src="{{ asset('static/admin/state.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection
