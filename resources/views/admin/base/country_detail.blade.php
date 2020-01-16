@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('static/plugin/dropify/dist/css/dropify.min.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">国家</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item"><a href="/admin/base/country">国家列表</a></li>
                <li class="breadcrumb-item active">{{$country->name_cn}}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-xlg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">基本信息</h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-success" onclick="updateCountry()"><i class="ti-save"></i> 保存</button>
                        </div>
                    </div>
                    <hr>
                    <form id="main-form" method="post" novalidate>
                        <div class="form-body">
                            <div class="form-group">
                                <input type="file" name="flag" class="dropify" data-height="180" data-default-file="{{!is_null($country->flag) ? url(config('asset.image_path') . $country->flag) : ''}}"/>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <h5> 中文名 : <span class="text-danger">*</span> </h5>
                                    <input type="text" class="form-control" name="name_cn" value="{{$country->name_cn}}" required data-validation-required-message="必填">
                                </div>
                                <div class="col-md-6">
                                    <h5> 英文名 : <span class="text-danger">*</span> </h5>
                                    <input type="text" class="form-control" name="name_en" required data-validation-required-message="必填" value="{{$country->name_en}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <h5> 电话代码 : <span class="text-danger">*</span> </h5>
                                    <input type="text" class="form-control" name="calling_code" required data-validation-required-message="必填" value="{{$country->calling_code}}">
                                </div>
                                <div class="col-md-6">
                                    <h5> 言语 : <span class="text-danger">*</span> </h5>
                                    <input type="text" class="form-control" name="language" required data-validation-required-message="必填" value="{{$country->language}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <h5> 货币 : <span class="text-danger">*</span> </h5>
                                    <input type="text" class="form-control" name="currency" required data-validation-required-message="必填" value="{{$country->currency}}">
                                </div>
                                <div class="col-md-6">
                                    <h5> 货币符号 : <span class="text-danger">*</span> </h5>
                                    <input type="text" class="form-control" name="symbol" required data-validation-required-message="必填" value="{{$country->symbol}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="controls">
                                    <label class="custom-control custom-checkbox m-b-0">
                                        <input type="checkbox" class="custom-control-input" id="loanable" name="loanable" {{$country->is_loanable ? 'checked' : ''}}>
                                        <span class="custom-control-label">可贷款</span>
                                    </label>
                                </div>
                            </div>
                            <input type="hidden" name="country_id" value="{{$country->id}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xlg-8 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">经济情况</h3>
                    <hr>
                    <form novalidate id="economy-form">
                        <h4 class="card-title">即时房价信息</h4>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="inflation_cpi" class="control-label">通货膨胀:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="inflation_cpi" name="inflation_cpi" value="{{$country->inflation_cpi}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="hpi" class="control-label">房价指数:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="hpi" name="hpi" value="{{$country->hpi}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="hpi_yoy" class="control-label">房价年变化:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="hpi_yoy" name="hpi_yoy" value="{{$country->hpi_yoy}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="hh_debt_gdp" class="control-label">家庭负债率:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="hh_debt_gdp" name="hh_debt_gdp" value="{{$country->hh_debt_gdp}}">
                                </div>
                            </div>
                        </div>
                        <h4 class="card-title">利率汇率</h4>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="interest_rate" class="control-label">央行利率:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="interest_rate" name="interest_rate" value="{{$country->interest_rate}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="mortgage_rate" class="control-label">私人贷款利率:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="mortgage_rate" name="mortgage_rate" value="{{$country->mortgage_rate}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="rmb_exchange" class="control-label">人民币计价汇率:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="rmb_exchange" name="rmb_exchange" value="{{$country->hpi_yoy}}">
                                </div>
                            </div>
                        </div>
                        <h4 class="card-title">其他信息</h4>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="consumer_confi" class="control-label">消费者信心:</label>
                                <input type="number" step="0.01" class="form-control" id="consumer_confi" name="consumer_confi" value="{{$country->consumer_confi}}">
                            </div>
                            <div class="col-md-4">
                                <label for="unemployment" class="control-label">失业率:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="unemployment" name="unemployment" value="{{$country->unemployment}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="gdp_growth" class="control-label">GDP增长率:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" id="gdp_growth" name="gdp_growth" value="{{$country->gdp_growth}}">
                                </div>
                            </div>
                        </div>
                    </form>
                    <h3 class="card-title">物业类型</h3>
                    <hr>
                    <form novalidate id="proptype-form">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="name" value="" required data-validation-required-message="必填">
                            </div>
                            <div class="col-md-6" style="display: flex">
                                <div class="controls m-t-10">
                                    <label class="custom-control custom-checkbox m-b-0">
                                        <input type="checkbox" class="custom-control-input" name="include_land_price">
                                        <span class="custom-control-label">包括土地价</span>
                                    </label>
                                </div>
                                <input type="hidden" name="country_id" value="{{$country->id}}">
                                <button type="submit" class="btn btn-primary m-l-15"><i class="ti-plus"></i> 新增</button>
                            </div>
                        </div>
                    </form>
                    <div id="proptypes">
                        @foreach($country->proptypes as $proptype)
                        <div class="form-group row" id="proptype-{{$proptype->id}}">
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="name-{{$proptype->id}}" value="{{$proptype->name}}">
                            </div>
                            <div class="col-md-6" style="display: flex">
                                <div class="controls m-t-10">
                                    <label class="custom-control custom-checkbox m-b-0">
                                        <input type="checkbox" class="custom-control-input" id="land-{{$proptype->id}}" {{$proptype->include_land_price ? 'checked' : ''}}>
                                        <span class="custom-control-label">包括土地价</span>
                                    </label>
                                </div>
                                <button type="button" class="btn btn-success m-l-15" onclick="updatePropertyType({{$proptype->id}})"><i class="ti-save"></i></button>
                                <button type="button" class="btn btn-danger m-l-10" onclick="deletePropertyType({{$proptype->id}})"><i class="ti-trash"></i></button>
                            </div>
                        </div>
                        @endforeach
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
    <script src="{{ asset('static/js/pages/validation.js') }}"></script>
    <script src="{{ asset('static/admin/country.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

    <script>
        $('.dropify').dropify({
            messages: {
                default: ''
            }
        });
    </script>
@endsection