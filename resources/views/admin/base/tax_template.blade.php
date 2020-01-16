@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">税率模板（{{$template->name}}）</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item"><a href="/admin/base/tax">税率模板</a></li>
                <li class="breadcrumb-item active">{{$template->name}}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="template-form" method="post" novalidate>
                        <div class="form-body">
                            <h3>基本信息</h3>
                            <hr>
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label> 模板名称：<span class="text-danger">*</span> </label>
                                        <div class="controls">
                                            <input type="text" class="form-control" id="name" name="name" required data-validation-required-message="必填" value="{{$template->name}}"> </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label> 货币：<span class="text-danger">*</span></label>
                                        <div class="controls">
                                            <select name="currency" id="currency" required class="selectpicker form-control" data-style="btn-info">
                                                <option value="美元" {{$template->currency == '美元' ? 'selected' : ''}}>美元</option>
                                                <option value="欧元" {{$template->currency == '欧元' ? 'selected' : ''}}>欧元</option>
                                                <option value="英镑" {{$template->currency == '英镑' ? 'selected' : ''}}>英镑</option>
                                                <option value="加币" {{$template->currency == '加币' ? 'selected' : ''}}>加币</option>
                                                <option value="澳元" {{$template->currency == '澳元' ? 'selected' : ''}}>澳元</option>
                                                <option value="日元" {{$template->currency == '日元' ? 'selected' : ''}}>日元</option>
                                                <option value="泰铢" {{$template->currency == '泰铢' ? 'selected' : ''}}>泰铢</option>
                                                <option value="人民币" {{$template->currency == '人民币' ? 'selected' : ''}}>人民币</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>适用国家：</label>
                                        <div class="controls">
                                            <select name="country_id" id="country_id" required class="selectpicker form-control" data-style="btn-primary">
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id}}" {{!is_null($template->bind) && $template->bind->first()->country_id == $country->id ? 'selected' : ''}}>{{$country->name_cn}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>适用省(州)：</label>
                                        <div class="controls">
                                            <select name="state_id" id="state_id" required class="selectpicker form-control" data-style="btn-success">
                                                {{--@foreach($countries as $country)--}}
                                                    {{--<option value="{{$country->id}}" {{$template->bind->country_id == $country->id ? 'selected' : ''}}>{{$country->name}}</option>--}}
                                                {{--@endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>适用物业类型：</label>
                                        <div class="controls">
                                            <select name="proptype_id" id="proptype_id" required class="selectpicker form-control" data-style="btn-warning">
                                                {{--@foreach($countries as $country)--}}
                                                    {{--<option value="{{$country->id}}" {{$template->bind->country_id == $country->id ? 'selected' : ''}}>{{$country->name}}</option>--}}
                                                {{--@endforeach--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input id="template_id" name="template_id" type="hidden" value="{{$template->id}}">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> 保存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">税费信息</h3>
                    <h6 class="card-subtitle">租金、现金流、贷款因为都是全世界通用，不体现在本表上。本表只体现不同税费组成。</h6>
                    <hr>
                    <div class="button-group m-b-20">
                        <button type="button" class="btn btn-warning" onclick="showTaxModal(true, '成交')">成交阶段的税费</button>
                        <button type="button" class="btn btn-dark" onclick="showTaxModal(true, '持有')">持有阶段的税费</button>
                    </div>

                    <h4 class="m-b-20">成交阶段的费用</h4>
                    <div class="table-responsive">
                        <table id="tbltax1" class="table color-table warning-table">
                            <thead>
                                <tr>
                                    <th width="150">名称</th>
                                    <th width="100" class="text-center">类型</th>
                                    <th width="150" class="text-center">公式</th>
                                    <th width="250" class="text-center">说明</th>
                                    <th width="100" class="text-center">可修改</th>
                                    <th width="100" class="text-center">更新日期</th>
                                    <th class="text-center" width="150">操作</th>
                                </tr>
                            </thead>
                            <tbody id="taxes1">
                            @foreach($template->transaction_taxes as $tax)
                            <tr id="tax-{{$tax->id}}">
                                <td id="name-{{ $tax->id }}">{{ $tax->name }}</td>
                                <td id="type-{{ $tax->id }}" class="text-center">
                                    @if($tax->type == '按比例')
                                        <span class="label label-success">{{ $tax->type }}</span>
                                    @elseif($tax->type == '固定值')
                                        <span class="label label-info">{{ $tax->type }}</span>
                                    @else
                                        <span class="label label-warning">{{ $tax->type }}</span>
                                    @endif
                                </td>
                                <td id="value-{{ $tax->id }}" class="text-center">{{ $tax->value }}</td>
                                <td id="comment-{{ $tax->id }}" class="text-center">{{ $tax->comment }}</td>
                                <td id="editable-{{ $tax->id }}" class="text-center">
                                    @if($tax->editable)
                                        可
                                    @else
                                        否
                                    @endif
                                </td>
                                <td id="updated-{{ $tax->id }}" class="text-center">{{ $tax->updated_at }}</td>
                                <td>
                                <div class="text-center">
                                    <button class="btn btn-info" type="button" onclick="showTaxModal(false, '成交', {{$tax->id}})"><i class="ti-pencil-alt"></i></button>
                                    <button class="btn btn-danger" type="button" onclick="deleteTax({{ $tax->id }})"><i class="ti-close"></i></button>
                                </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h4 class="m-b-20 m-t-20">持有阶段的 · 年化费用</h4>
                    <div class="table-responsive">
                        <table id="tbltax2" class="table color-table inverse-table">
                            <thead>
                            <tr>
                                <th width="150">名称</th>
                                <th width="100" class="text-center">类型</th>
                                <th width="150" class="text-center">公式</th>
                                <th width="250" class="text-center">说明</th>
                                <th width="100" class="text-center">可修改</th>
                                <th width="100" class="text-center">更新日期</th>
                                <th class="text-center" width="150">操作</th>
                            </tr>
                            </thead>
                            <tbody id="taxes2">
                            @foreach($template->holding_taxes as $tax)
                                <tr id="tax-{{$tax->id}}">
                                    <td id="name-{{ $tax->id }}">{{ $tax->name }}</td>
                                    <td id="type-{{ $tax->id }}" class="text-center">
                                        @if($tax->type == '按比例')
                                            <span class="label label-success">{{ $tax->type }}</span>
                                        @elseif($tax->type == '固定值')
                                            <span class="label label-info">{{ $tax->type }}</span>
                                        @else
                                            <span class="label label-warning">{{ $tax->type }}</span>
                                        @endif
                                    </td>
                                    <td id="value-{{ $tax->id }}" class="text-center">{{ $tax->value }}</td>
                                    <td id="comment-{{ $tax->id }}" class="text-center">{{ $tax->comment }}</td>
                                    <td id="editable-{{ $tax->id }}" class="text-center">
                                        @if($tax->editable)
                                            可
                                        @else
                                            否
                                        @endif
                                    </td>
                                    <td id="updated-{{ $tax->id }}" class="text-center">{{ $tax->updated_at }}</td>
                                    <td>
                                        <div class="text-center">
                                            <button class="btn btn-info" type="button" onclick="showTaxModal(false, '持有', {{$tax->id}})"><i class="ti-pencil-alt"></i></button>
                                            <button class="btn btn-sm btn-danger" type="button" onclick="deleteTax({{ $tax->id }})"><i class="ti-close"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="tax-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">税费信息</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <form id="tax-form" novalidate>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label> 名称 : <span class="text-danger">*</span> </>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="tax_name" name="name" required data-validation-required-message="必填" value="" placeholder="请输入模板名称。。。">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label> 类型 : <span class="text-danger">*</span> </label>
                                            <div class="controls">
                                                <select name="type" id="tax_type" required class="selectpicker form-control" data-style="btn-info">
                                                    <option value="按比例">按比例</option>
                                                    <option value="固定值">固定值</option>
                                                    <option value="特殊">特殊</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label> 公式 : <span class="text-danger">*</span> </label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="tax_value" name="value" required data-validation-required-message="必填" value="" placeholder="请输入公式。。。">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label> 说明</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="tax_comment" name="comment" value="" placeholder="请输入说明。。。">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox m-b-0">
                                                <input type="checkbox" id="editable" name="editable" class="custom-control-input" checked>
                                                <span class="custom-control-label">可修改</span>
                                            </label>
                                        </div>
                                    </div>

                                    <input id="tax_id" name="tax_id" type="hidden" value="">
                                    <input id="tax_output" name="output" type="hidden" value="">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                        <button type="submit" class="btn btn-danger">保存</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">特殊税费规则</h3>
                    <hr>

                    <div class="row">
                        @foreach($template->specials as $special)
                            <div id="special-{{$special->id}}" class="col-4">
                                <div class="form-group">
                                    <label> 名称：<span class="text-danger">*</span> </label>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="special_name_{{$special->id}}" name="name" required data-validation-required-message="必填" value="{{$special->name}}" placeholder="请输入名称。。。">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label> 规则：</label>
                                    <div class="controls">
                                        <textarea class="form-control" name="rule" id="special_rule_{{$special->id}}" rows="8" cols="60">{{$special->rule}}</textarea>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info waves-effect waves-light" onclick="updateSpecial({{$special->id}})"><i class="ti-save"></i> 保存</button>
                                <button type="button" class="btn btn-danger waves-effect waves-light" onclick="deleteSpecial({{$special->id}})"><i class="ti-close"></i> 删除</button>
                            </div>
                        @endforeach
                        <div id="special-new" class="col-4">
                            <form id="special-form" method="post" novalidate>
                                <div class="form-group">
                                    <label> 名称：<span class="text-danger">*</span> </label>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="special_name_new" name="name" required data-validation-required-message="必填" value="" placeholder="请输入名称。。。">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label> 规则：</label>
                                    <div class="controls">
                                        <textarea class="form-control" name="rule" id="special_rule_new" rows="8" cols="60"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="ti-plus"></i> 添加</button>
                            </form>
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
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{ asset('static/js/pages/validation.js') }}"></script>
    <script src="{{ asset('static/admin/tax_template.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection