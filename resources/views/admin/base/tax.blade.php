@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">税率模板</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">税率模板</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary" onclick="showTemplateModal()"><i class="ti-plus"></i> 添加模板</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbltemplate" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>模板名称</th>
                                <th>适用城市</th>
                                <th>适用物业类型</th>
                                <th>货币</th>
                                <th>最后编辑日期</th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody id="templates">
                            @foreach($templates as $template)
                                <tr>
                                    <td>
                                        <a href="/admin/base/tax/template/{{$template->id}}">{{$template->name}}</a>
                                    </td>
                                    <td>
                                        @if(!is_null($template->bind))
                                            {{$template->bind->country->name_cn}}的
                                            @if($template->bind->state_id == 0)
                                                全部城市
                                            @else
                                                {{$template->bind->state->name_cn}}
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if(!is_null($template->bind))
                                            @if($template->bind->proptype_id == 0)
                                                全部
                                            @else
                                                {{$template->bind->proptype->name}}
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{$template->currency}}</td>
                                    <td>{{$template->updated_at}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" type="button" onclick="deleteTemplate(this, {{$template->id}})"><i class="ti-close"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="template-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">添加模板</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <form id="template-form" novalidate>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <h5> 模板名称 : <span class="text-danger">*</span> </h5>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="template_name" name="name" required data-validation-required-message="必填" value="" placeholder="请输入模板名称。。。">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">取消</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light">保存</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{ asset('static/js/pages/validation.js') }}"></script>
    <script src="{{ asset('static/admin/tax.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection