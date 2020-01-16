@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('static/plugin/dropify/dist/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('static/plugin/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/summernote/dist/summernote-bs4.css') }}" rel="stylesheet">
    <link href="{{asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/owl.carousel/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/owl.carousel/owl.theme.default.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">{{isset($unit) ? '户型详情' : '新建户型'}}</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                @if(isset($unit))
                <li class="breadcrumb-item"><a href="/admin/property/unit">户型列表</a></li>
                <li class="breadcrumb-item active">户型详情</li>
                @else
                <li class="breadcrumb-item active">新建户型</li>
                @endif
            </ol>
        </div>
    </div>
    <form id="unit-form" novalidate>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" onclick="saveUnitInfo();" class="btn btn-success"><i class="ti-save"></i> 保存</button>
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
                        <div class="row m-t-20 col-md-12">
                            <div class="col-md-4">
                                <h5 style="color:#fb9678">绑定信息 </h5>
                                <div class="controls m-t-20 col-md-12 p-l-0">
                                    <label> 价格单名称 : </label>  <input readonly type="text" class="form-control" id="pricelist_name" name="pricelist_name" value="{{isset($unit) ? $unit->pricelist->name : ''}}"/>
                                </div>
                                <div class="controls m-t-20 col-md-12 p-l-0">
                                    <label> 价格单Hash : </label>  <input readonly type="text" class="form-control" id="pricelist_hash" name="pricelist_hash" value="{{isset($unit) ? $unit->pricelist->hash : ''}}"/>
                                </div>
                            </div>
                            <div class=" col-md-4 "  style="border-left:solid #eee 1px">
                                <h5 class="p-l-10" style="color:#fb9678"> 基本信息 : </h5>
                                <input type="text" class="form-control" id="pricelist_id" name="pricelist_id"  value="{{isset($price_id) ? $price_id : ''}}" hidden/>
                                <input type="text" class="form-control" id="is_from_property" name="is_from_property"  value="{{isset($is_from_property) ? $is_from_property : 0}}" hidden/>
                                <div class="controls col-md-12 m-t-20">
                                    <label> 户型号 : <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" id="unit_number" name="unit_number" value="{{isset($unit) ? $unit->unit_number : ''}}"/>
                                </div>
                                <div class="controls col-md-12 m-t-20">
                                    <label> 公寓楼层 : </label><input type="number" class="form-control" id="level" name="level" required value="{{isset($unit) ? $unit->level : ''}}">
                                </div>
                            </div>
                            <div class="col-md-4 p-l-20" style="border-left:solid #eee 1px">
                                <label> 户型图 : <span class="text-danger">*</span> </label>
                                <input type="file" id="floorplan" name="floorplan" class="dropify" data-height="180" data-default-file="{{isset($unit) && isset($unit->floorplan) && !is_null(\App\Image::findByName($unit->floorplan))? \App\Image::findByName($unit->floorplan)->base_url . '/' . $unit->floorplan : ''}}"/>
                            </div>
                        </div>
                        <hr>
                        <div class="row m-t-40 col-md-12">
                            <div class="col-md-4 p-l-0">
                                <h5 class="p-l-10" style="color:#fb9678"> 价格信息 : </h5>
                                <input type="text" hidden id="country_id" value="{{isset($unit) && !is_null($unit->pricelist->property) ? $unit->pricelist->property->country_id : '-1'}}"/>
                                <div class="controls m-l-0 m-t-20 col-md-12" >
                                    <label> 户型总价 : <span class="text-danger">*</span> </label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" id="total_price" name="total_price" value="{{isset($unit) ? $unit->total_price : ''}}"/>
                                        <div class="input-group-append">
                                            <span class="input-group-text price_mark">$</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 row">
                                    <div class="controls col-md-6" id="div_structure_price" >
                                        <label> 建筑价格 : </label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" id="structure_price" name="structure_price" value="{{isset($unit) ? $unit->structure_price : ''}}"/>
                                            <div class="input-group-append">
                                                <span class="input-group-text">$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="controls m-t-20 col-md-6" id="div_land_price" >
                                        <label> 土地价格 : </label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" id="land_price" name="land_price" value="{{isset($unit) ? $unit->land_price : ''}}"/>
                                            <div class="input-group-append">
                                                <span class="input-group-text">$</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4" style="border-left:solid #eee 1px">
                                <h5 class=" p-l-10" style="color:#fb9678"> 户型信息 : </h5>
                                <div class="d-flex col-md-12 flex-md-wrap">
                                    <div class="controls col-md-4 m-t-10 m-l-0 p-l-0" >
                                        <label> 房间数 : </label><input type="number" class="form-control" id="room" name="room" value="{{isset($unit) ? $unit->room : ''}}"/>
                                    </div>
                                    <div class="controls col-md-4  m-t-10  p-l-0">
                                        <label> 卫生间 : </label>  <input type="number" class="form-control" id="bath" name="bath" value="{{isset($unit) ? $unit->bath : ''}}"/>
                                    </div>
                                    <div class="controls m-t-10  col-md-4  p-l-0">
                                        <label> 车位 : </label>  <input type="number" class="form-control" id="garage" name="garage" value="{{isset($unit) ? $unit->garage : ''}}"/>
                                    </div>
                                    <div class="controls m-t-20  col-md-4  p-l-0">
                                        <label> 书房 : </label>  <input type="number" class="form-control" id="study" name="study" value="{{isset($unit) ? $unit->study : ''}}"/>
                                    </div>
                                    <div class="controls m-t-20  col-md-4  p-l-0">
                                        <label> 客厅 : </label>  <input type="number" class="form-control" id="living" name="living" value="{{isset($unit) ? $unit->living : ''}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"  style="border-left:solid #eee 1px">
                                <h5 class=" p-l-10" style="color:#fb9678"> 面积信息 : </h5>
                                <div class="d-flex col-md-12 flex-md-wrap ">
                                    <div class="col-md-4 m-t-10  m-l-0 p-l-0">
                                        <label> 面积单位 : </label>
                                        <select class="form-control" id="size_unit" name="size_unit" onchange="sizeUnitChang()">
                                            <option value="sqm" {{isset($unit) && $unit->size_unit == 'sqm' ? 'selected' : ''}}>平方米</option>
                                            <option value="sqft" {{isset($unit) && $unit->size_unit == 'sqft' ? 'selected' : ''}}>平方英尺</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 m-t-10  m-l-0 p-l-0">
                                        <label> 套内面积 : </label>
                                        <div class="input-group mb-3">
                                            <input type="number" step="0.01" class="form-control" id="internal_size" name="internal_size" required value="{{isset($unit) ? $unit->internal_size : ''}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text size_unit">m²</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 m-t-10  m-l-0 p-l-0">
                                        <label> 阳台面积 : </label>
                                        <div class="input-group mb-3">
                                            <input type="number" step="0.01" class="form-control" id="external_size" name="external_size" required value="{{isset($unit) ? $unit->external_size : ''}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text size_unit">m²</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="div_land_size" class="col-md-4 m-t-20  m-l-0 p-l-0">
                                        <label> 土地面积 : </label>
                                        <div class="input-group mb-3">
                                            <input type="number" step="0.01" class="form-control" id="land_size" name="land_size" required value="{{isset($unit) ? $unit->land_size : ''}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text size_unit">m²</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_structure_size"  class="col-md-4 m-t-20  m-l-0 p-l-0">
                                        <label> 建筑面积 : </label>
                                        <div class="input-group mb-3">
                                            <input type="number" step="0.01" class="form-control" id="structure_size" name="structure_size" required value="{{isset($unit) ? $unit->structure_size : ''}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text size_unit">m²</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 m-t-20  m-l-0 p-l-0" hidden>
                                        <label> 别墅楼层 : </label><input type="number" class="form-control" id="house_type" name="house_type" required value="{{isset($unit) ? $unit->house_type : ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row m-t-40 col-md-12">
                            <div class=" col-md-4 p-l-10 p-r-20">
                                <h5 style="color:#fb9678"> 其他信息 : </h5>
                                <div class="controls m-t-20" >
                                    <div class="form-group row ">
                                        <div class="col-md-12">
                                            <label> 投资 HASH : </label><input type="text" class="form-control" id="cal_hash" name="cal_hash" required value="{{isset($unit) ? $unit->cal_hash : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label> 朝向 : </label>
                                            <select name="aspect" id="aspect" class="form-control">
                                                <option value="N/A" {{isset($unit) && $unit->aspect == "N/A" ? 'selected' : ''}}>N/A</option>
                                                <option value="东" {{isset($unit) && $unit->aspect == "东" ? 'selected' : ''}}>东</option>
                                                <option value="西" {{isset($unit) && $unit->aspect == "西" ? 'selected' : ''}}>西</option>
                                                <option value="南" {{isset($unit) && $unit->aspect == "南" ? 'selected' : ''}}>南</option>
                                                <option value="北" {{isset($unit) && $unit->aspect == "北" ? 'selected' : ''}}>北</option>
                                                <option value="东南" {{isset($unit) && $unit->aspect == "东南" ? 'selected' : ''}}>东南</option>
                                                <option value="东北" {{isset($unit) && $unit->aspect == "东北" ? 'selected' : ''}}>东北</option>
                                                <option value="西南" {{isset($unit) && $unit->aspect == "西南" ? 'selected' : ''}}>西南</option>
                                                <option value="西北" {{isset($unit) && $unit->aspect == "西北" ? 'selected' : ''}}>西北</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label> 合同类型 : </label>
                                            <select name="contract_type" id="contract_type" class="form-control">
                                                <option value="1" {{isset($unit) && $unit->contract_type == "1" ? 'selected' : ''}}>一本合同</option>
                                                <option value="2" {{isset($unit) && $unit->contract_type == "2" ? 'selected' : ''}}>两本合同</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4  p-l-20 p-r-20" style="border-left:solid #eee 1px">
                                <h5 style="color:#fb9678"> 补充材料：</h5>
                                <input type="file" id="additional_file" name="additional_file" class="btn btn-sm btn-info m-t-10">
                            </div>
                            <div class="col-md-4 p-l-20"  style="border-left:solid #eee 1px">
                                <h5 style="color:#fb9678"> 销售权限配置 : </h5>
                                <div class="controls m-t-20  m-l-0 p-l-0" >
                                    <label> 销售状态 : </label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{isset($unit) && $unit->status == "1" ? 'selected' : ''}}>未挂牌</option>
                                        <option value="2" {{isset($unit) && $unit->status == "2" ? 'selected' : ''}}>可售</option>
                                        <option value="3" {{isset($unit) && $unit->status == "3" ? 'selected' : ''}}>已预订</option>
                                        <option value="4" {{isset($unit) && $unit->status == "4" ? 'selected' : ''}}>已支付定金</option>
                                        <option value="5" {{isset($unit) && $unit->status == "5" ? 'selected' : ''}}>合同发出</option>
                                        <option value="6" {{isset($unit) && $unit->status == "6" ? 'selected' : ''}}>合同交换</option>
                                        <option value="7" {{isset($unit) && $unit->status == "7" ? 'selected' : ''}}>完成交割</option>
                                    </select>
                                </div>
                                <div class="controls  m-t-20  m-l-0 p-l-0">
                                    <label> 分销团队 : </label>  <input type="number" class="form-control" id="sales_group" name="sales_group" value="{{isset($unit) ? $unit->sales_group : ''}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input hidden type="text" class="form-control" id="hash" name="hash" required value="{{isset($unit) ? $unit->hash : ''}}"/>
    </form>

    <div id="process-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">申请步骤</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label> 步骤名称 : <span class="text-danger">*</span> </label>
                        <div class="controls">
                            <input type="text" class="form-control" id="process_title" name="process_title" required data-validation-required-message="必填" > </div>
                    </div>
                    <div class="form-group">
                        <label> 排序 : <span class="text-danger">*</span> </label>
                        <div class="controls">
                            <input type="text" class="form-control" id="process_rank" name="process_rank" required data-validation-required-message="必填" > </div>
                    </div>
                    <div class="form-group">
                        <label> 描述 : <span class="text-danger">*</span> </label>
                        <div class="controls">
                            <input type="text" class="form-control" id="process_des" name="process_des" required data-validation-required-message="必填" > </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success" onclick="addProcess();"><i class="fas fa-save"></i> 添加</button>
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
    <script src="{{asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{asset('static/plugin/owl.carousel/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('static/js/pages/validation.js') }}"></script>
    <script src="{{ asset('static/admin/property_unit.js') }}"></script>
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
