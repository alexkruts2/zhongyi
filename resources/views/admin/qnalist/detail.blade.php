@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('static/plugin/dropify/dist/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('static/plugin/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/summernote/dist/summernote-bs4.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">{{isset($qnaList) ? 'QNA详情' : '新建QNA'}}</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                @if(isset($qnaList))
                <li class="breadcrumb-item"><a href="/admin/qnalist">QNA列表</a></li>
                <li class="breadcrumb-item active">QNA详情</li>
                @else
                <li class="breadcrumb-item active">新建QNA</li>
                @endif
            </ol>
        </div>
    </div>
    <form id="qnaList-form" novalidate>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success"><i class="ti-save"></i> 保存</button>
{{--                            <button type="button" class="btn btn-info" onclick="showPreviewModal()"><i class="ti-check"></i> 预览</button>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-body">
                        <div class="row m-t-20">
                            <div class="form-group col-md-6">
                                <label> FAQ Hash : <span class="text-danger">*</span> </label>
                                <div class="controls">
                                    <input type="text" class="form-control" id="faq_hash" name="faq_hash" required data-validation-required-message="必填" value="{{isset($qnaList) ? $qnaList->faq_hash : (request()->get('hash') != '' ? request()->get('hash') : '')}}"> </div>
                            </div>
{{--                            <div class="form-group col-md-6">--}}
{{--                                <label> QNA名称 : <span class="text-danger">*</span> </label>--}}
{{--                                <div class="controls">--}}
{{--                                    <input type="text" class="form-control" id="name" name="name" required data-validation-required-message="必填" value="{{isset($qnaList) ? $qnaList->name : ''}}"> </div>--}}
{{--                            </div>--}}
                        </div>
                        <hr>
                        <div class="row col-md-12 m-t-20 m-l-0 p-l-0 p-r-0">
                            <div class="form-group col-md-6 p-l-0">
                                <label > 问题列表 : </label>
                                <div class="row col-md-12 m-l-0 p-l-0 p-r-0">
                                    <div class="table-responsive">
                                        <table id="tbl_questions" class="display nowrap table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="text-center">设为前端首要展示</th>
                                                <th class="text-center">问题的内容</th>
                                                <th class="text-center">操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row col-md-12 m-t-20 m-b-20 justify-content-center" >
                                        <button class="btn btn-rounded btn-info" type="button" onclick="createQuery()"><i class="ti-plus"></i> 添加问题</button>
                                    </div>
                                    <input type="text" class="form-control" id="questions" name="questions" value="{{isset($qnaList) ? $qnaList->questions : ''}}" hidden/>
                                </div>
                            </div>

                            <div class="form-group col-md-6 p-r-0">
                                <label> 回答 : <span class="text-danger">*</span> </label>
                                <div class="controls" style="margin-top:5px">
                                    <textarea type="text" class="form-control" id="answer" name="answer" rows="6">{{isset($qnaList) ? $qnaList->answer : ''}}</textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div hidden class="form-group">
                            <label> 是否有上下文语境 : <span class="text-danger">*</span> </label>
                            <div class="controls">
                                <input type="checkbox"  class="js-switch form-check-input" data-color="#009efb" id="isContextOnly" name="isContextOnly" data-size="small" {{isset($qnaList) ? ($qnaList->isContextOnly == 1 ? 'checked' : '') : 'checked'}}/>
                            </div>
                        </div>
                        <div hidden class="form-group">
                            <label> 是否有弹出互交框 : <span class="text-danger">*</span> </label>
                            <div class="controls">
                                <input type="text"  checked class="form-control" id="PromptDTO" name="PromptDTO" data-size="small" value="{{isset($qnaList) ? $qnaList->PromptDTO : ''}}"/>
                            </div>
                        </div>
                        <div class="form-group m-t-20 ">
                            <label > 标签 : </label><br>
                            <small> 此处的 Meta Data，用于未来的智能客服系统升级。若您不知道该如何填写，可以留空。不会影响到前端具体应用的使用。</small>
                            <div class="col-md-12 m-t-5 m-l-0 p-l-0 p-r-0">
                                <div class="table-responsive">
                                    <table id="tbl_tags" class="display nowrap table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="text-center">类型（Meta Name）</th>
                                            <th class="text-center">值（Meta Value）</th>
                                            <th class="text-center">来源</th>
                                            <th class="text-center">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row col-md-12 m-t-20 m-b-20 justify-content-center" >
                                    <button class="btn btn-rounded btn-info" onclick="createTag();return false;"><i class="ti-plus"></i> 添加标签</button>
                                </div>
                                <input type="text" class="form-control" id="MetadataDTO" name="MetadataDTO" value="{{isset($qnaList) ? $qnaList->MetadataDTO : ''}}" hidden/>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id" value="{{isset($qnaList) ? $qnaList->id : ''}}">
                </div>
            </div>
        </div>
    </div>
    </form>

    <div id="tag-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">标签 </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <input type="text" hidden id="isUpdate"/>
                    <div class="form-group">
                        <label> 类型（Meta Name） : <span class="text-danger">*</span> </label>
                        <div class="controls">
                            <input type="text" class="form-control" id="meta_name" name="meta_name" required data-validation-required-message="必填" /> </div>
                    </div>
                    <div class="form-group">
                        <label> 值（Meta Value） : <span class="text-danger">*</span> </label>
                        <div class="controls">
                            <input type="text" class="form-control" id="meta_value" name="meta_value" required data-validation-required-message="必填" /> </div>
                    </div>
                    <div class="form-group">
                        <label> 来源 : <span class="text-danger">*</span> </label>
                        <div class="controls">
                            <input type="text" class="form-control" id="source" name="source" required data-validation-required-message="必填" value="Custom Editorial" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success" onclick="addTag();"><i class="fas fa-save"></i> 添加</button>
                </div>
            </div>
        </div>
    </div>

    <div id="query-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">问题 </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label> 设为前端首要展示 : </label>
                        <div class="controls">
                            <input type="checkbox"  class="js-switch form-check-input" data-color="#009efb" id="query_is_primary" name="query_is_primary" data-size="small"/>
                    </div>
                    <div class="form-group m-t-30">
                        <label> 问题的内容 : <span class="text-danger">*</span> </label>
                        <div class="controls">
                            <input type="text" class="form-control" id="query_content" name="query_content" required data-validation-required-message="必填" > </div>
                    </div>
                        <input type="text" class="form-control" id="query_is_update" name="query_is_update" hidden>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success" onclick="addQuery();"><i class="fas fa-save"></i> 添加</button>
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
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/plugin/dropify/dist/js/dropify.min.js')}}"></script>
    <script src="{{asset('static/plugin/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{ asset('static/plugin/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('static/js/pages/validation.js') }}"></script>
    <script src="{{ asset('static/admin/qnalist.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script>
        $('.dropify').dropify({
            messages: {
                default: ''
            }
        });
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());
        });
        $('#content').summernote({
            height: 900, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false // set focus to editable area after initializing summernote
        });
    </script>
@endsection
