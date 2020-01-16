@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">城市</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">城市列表</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <select class="selectpicker form-control" data-style="btn-info" id="country">
                                <option value="-1">--请选择国家--</option>
                                @foreach(\App\Country::orderBy('name_cn', 'asc')->get() as $country)
                                    <option value="{{$country->id}}">{{$country->name_cn}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="selectpicker form-control" data-style="btn-info" id="state" disabled>
                                <option value="-1">--请选择省(州)--</option>
                            </select>
                        </div>
                        <div class="col-md-3">
{{--                            <button class="btn btn-success" onclick="searchCity()"><i class="ti-search"></i></button>--}}
                            <button class="btn btn-primary" onclick="showCityModal(true, 0, null)"><i class="ti-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">国家</th>
                                <th class="text-center">省(州)</th>
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
        <div id="city-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">城市信息</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="city-form" novalidate>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <select class="selectpicker form-control" data-style="btn-info" id="country_id" name="country_id" onchange="changeCountry()">
                                        @foreach(\App\Country::orderBy('name_cn', 'asc')->get() as $country)
                                            <option value="{{$country->id}}">{{$country->name_cn}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="selectpicker form-control" data-style="btn-info" id="state_id" name="state_id">
                                    </select>
                                </div>
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
                            <input id="city_id" name="city_id" type="hidden" value="">
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
    <script src="{{ asset('static/admin/city.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection
