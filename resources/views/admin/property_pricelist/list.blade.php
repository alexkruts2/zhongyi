@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">价格单列表</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">价格单列表</li>
            </ol>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-md-6">
            <button class="btn btn-rounded btn-info" onclick="createPrice()"><i class="ti-plus"></i> 新建价格单</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl_pricelist" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">Hash</th>
                                <th class="text-center">名称</th>
                                <th class="text-center">绑定的物业</th>
                                <th class="text-center">户型</th>
                                <th class="text-center">创建时间</th>
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

    <div id="price-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">价格单详情</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="price-form" novalidate>
                    <div class="modal-body">
                        <div class="form-group">
                            <label> 价格单名称 : <span class="text-danger">*</span> </label>
                            <div class="controls">
                                <input type="text" class="form-control" id="name" name="name" required data-validation-required-message="必填" value=""> </div>
                        </div>
                        <div class="form-group">
                            <label> 绑定的物业Hash : <span class="text-danger">*</span> </label>
                            <div class="controls">
                                <input type="text" class="form-control" id="property" name="property" required data-validation-required-message="必填" value=""> </div>
                        </div>
                        <input type="hidden" id="hash" name="hash">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> 保存</button>
                    </div>
                </form>
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
    <script src="{{ asset('static/admin/property_pricelist.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
@endsection
