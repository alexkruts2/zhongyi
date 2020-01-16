@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">国家</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">国家列表</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-primary" onclick="showCountryModal(true)"><i class="ti-plus"></i> 新增</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblcountry" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">国家<br><small class="text-grey">Country</small></th>
                                <th class="text-center">货币<br><small class="text-grey">Symbol</small></th>
                                <th class="text-center">央行利率<br><small class="text-grey">Interest Rate</small></th>
                                <th class="text-center">通货膨胀<br><small class="text-grey">Inflation</small></th>
                                <th class="text-center">房价指数<br><small class="text-grey">HPI</small></th>
                                <th class="text-center">房价年度变化<br><small class="text-grey">HPI YoY Change</small></th>
                                <th class="text-center">家庭负债率<br><small class="text-grey">H.Debt to GDP</small></th>
                                <th class="text-center">人民币汇率<br><small class="text-grey">RMB Exchange</small></th>
                                <th class="text-center">私人贷款利率<br><small class="text-grey">Mortgage Rate</small></th>
                                <th class="text-center">消费者信心<br><small class="text-grey">C.Confidence</small></th>
                                <th class="text-center">失业率<br><small class="text-grey">Unemployment</small></th>
                                <th class="text-center">GDP增长率<br><small class="text-grey">GDP Rate</small></th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Country::all() as $country)
                            <tr>
                                <td class="text-center"><a href="/admin/base/country/{{$country->id}}">{{$country->name_cn}}</a></td>
                                <td class="text-center">{{$country->symbol}}</td>
                                <td class="text-center">{{$country->interest_rate}}</td>
                                <td class="text-center">{{$country->inflation_cpi}}</td>
                                <td class="text-center">{{$country->hpi}}</td>
                                <td class="text-center">{{$country->hpi_yoy}}</td>
                                <td class="text-center">{{$country->hh_debt_gdp}}</td>
                                <td class="text-center">{{$country->rmb_exchange}}</td>
                                <td class="text-center">{{$country->mortgage_rate}}</td>
                                <td class="text-center">{{$country->consumer_confi}}</td>
                                <td class="text-center">{{$country->unemployment}}</td>
                                <td class="text-center">{{$country->gdp_growth}}</td>
                                <td class="text-center"><button class="btn btn-sm btn-danger" onclick="deleteCountry(this, {{$country->id}})"><i class="ti-trash"></i></button></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="country-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">添加国家</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form id="country-add-form" novalidate>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-6">
                                    <h5> 中文名称 : <span class="text-danger">*</span> </h5>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="country_name_cn" name="name_cn" required data-validation-required-message="必填" value="" placeholder="请输入中文名称...">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5> 英文名称 : <span class="text-danger">*</span> </h5>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="country_name_en" name="name_en" required data-validation-required-message="必填" value="" placeholder="请输入英文名称...">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-6">
                                    <h5> 电话代码 : <span class="text-danger">*</span> </h5>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="calling_code" name="calling_code" required data-validation-required-message="必填" value="" placeholder="请输入电话代码...">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5> 语言 : <span class="text-danger">*</span> </h5>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="language" name="language" required data-validation-required-message="必填" value="" placeholder="请输入语言...">
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <h5> 货币 : <span class="text-danger">*</span> </h5>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="currency" name="currency" required data-validation-required-message="必填" value="" placeholder="请输入货币...">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5> SYMBOL : <span class="text-danger">*</span> </h5>
                                    <div class="controls">
                                        <input type="text" class="form-control" id="symbol" name="symbol" required data-validation-required-message="必填" value="" placeholder="请输入SYMBOL...">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <label class="custom-control custom-checkbox m-b-0">
                                        <input type="checkbox" class="custom-control-input" name="loanable" id="loanable" checked>
                                        <span class="custom-control-label">可贷款</span>
                                    </label>
                                </div>
                            </div>
                            <input id="country_id" name="country_id" type="hidden" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-danger"><i class="ti-save"></i> 保存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="property-type-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title type-title">物业类型</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>

                    <div class="modal-body">
                        <div id="type-new" class="row">
                            <div class="col-md-5">
                                <input type="text" id="type-new-name" class="form-control" value="" placeholder="请输入物业类型。。。">
                            </div>
                            <div class="col-md-4 m-t-5">
                                <label class="custom-control custom-checkbox m-b-0">
                                    <input type="checkbox" id="include_land_price" name="include_land_price" class="custom-control-input">
                                    <span class="custom-control-label">包括土地价</span>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success waves-effect" onclick="addPropertyType()"><i class="ti-plus"></i> 添加</button>
                            </div>
                        </div>

                        <input id="country_id_type" name="country_id" type="hidden" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">取消</button>
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
    <script src="{{ asset('static/admin/country.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection