@extends('admin.layout.admin')
@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('static/plugin/dropify/dist/css/dropify.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('static/plugin/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{asset('static/plugin/summernote/dist/summernote-bs4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/owl.carousel/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/owl.carousel/owl.theme.default.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.0/mapsjs-ui.css?dp-version=1533195059" />

@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">{{isset($property) ? '物业详情' : '新建物业'}}</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                @if(isset($property))
                    <li class="breadcrumb-item"><a href="/admin/property">物业列表</a></li>
                    <li class="breadcrumb-item active">物业详情</li>
                @else
                    <li class="breadcrumb-item active">新建物业</li>
                @endif
            </ol>
        </div>
    </div>
    <form id="property-form" novalidate>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <button type="button" onclick="saveProperty()" class="btn btn-success"><i class="ti-save"></i> 保存</button>
                            </div>
                            @if(isset($property))
                                <div class="col-md-4 row">
                                    <div class="col-md-4 text-right m-t-10">
                                        <input type="checkbox" {{isset($property) && $property->is_hot ? 'checked' : ''}} class="js-switch" name="is_hot" data-color="red" data-size="small" onchange="setHot()" />
                                        <label class="m-r-20">热销</label>
                                    </div>
                                    <div class="col-md-4 text-right m-t-10">
                                        <input type="checkbox" {{isset($property) && $property->is_recommended ? 'checked' : ''}} class="js-switch" name="is_recommended" data-color="orange" data-size="small" onchange="setRecommended()" />
                                        <label class="m-r-20">推荐</label>
                                    </div>
                                    <div class="col-md-4 text-right m-t-10">
                                        <input type="checkbox" {{isset($property) && $property->is_published ? 'checked' : ''}} class="js-switch" name="is_published" data-color="#009c75" data-size="small" onchange="publish()" />
                                        <label class="m-r-20">发布</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">项目名称</h3>
                        <small>请填写下列项目的基本概况。如果中文名填写，则前端展示都会使用中文名。否则前端使用英文名做展示。</small>
                        <hr>
                        <div class="form-body">
                            <div class="form-group">
                                <label for="name_en">英文名：<span class="text-danger">*</span> (必填)</label>
                                <div class="controls">
                                    <input type="text" class="form-control" id="name_en" name="name_en" required data-validation-required-message="必填" value="{{isset($property) ? $property->name_en : ''}}"> </div>
                            </div>
                            <div class="form-group">
                                <label for="name_cn">中文名： </label>
                                <div class="controls">
                                    <input type="text" class="form-control" id="name_cn" name="name_cn" value="{{isset($property) ? $property->name_cn : ''}}"> </div>
                            </div>
                            <div class="form-group">
                                <label for="developer_name_en">开发商英文名：<span class="text-danger">*</span> (必填)</label>
                                <div class="controls">
                                    <input type="text" class="form-control" id="developer_name_en" name="developer_name_en" value="{{isset($property) ? $property->developer_name_en : ''}}"> </div>
                            </div>
                            <div class="form-group">
                                <label for="developer_name_cn">开发商中文名：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" id="developer_name_cn" name="developer_name_cn" value="{{isset($property) ? $property->developer_name_cn : ''}}"> </div>
                            </div>
                        </div>
                        <input type="hidden" id="hash" name="hash" value="{{isset($property) ? $property->hash : ''}}">
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">国家和地区</h3>
                        <small>请尽可能地规范本项目所在的区域信息。国家是必填的，其他内容为选填。请尽可能填至城市级别。</small> <a class="get-code" data-toggle="collapse" href="#official" aria-expanded="true"><i class="fa fa-code"></i>  <small class="card-subtitle">展开更多解释</small></a><br>
                        <div class="collapse m-t-10" id="official" aria-expanded="true">
                            <small>州政府级别 - 比如加拿大的：不列颠哥伦比亚 - B.C.</small><br>
                            <small>城市级别 - 比如：悉尼 - Sydney。注意，这个城市往往为地理名词，而非像City of Ryde这样，Council一样的行政单位。</small><br>
                            <small>小区级别 - 小区为Suburb，最小的地理单位，请区别于Council</small>
                        </div>
                        <hr>
                        <div class="form-body">
                            <div class="form-group">
                                <label for="country_id"> 国家：<span class="text-danger">*</span> (必填)</label>
                                <div class="controls">
                                    <select name="country_id" id="country_id" class="form-control" onchange="changeCountry()">
                                        @foreach(\App\Country::all() as $country)
                                            <option value="{{$country->id}}" {{isset($property) && $property->country_id == $country->id ? 'selected' : ''}}>{{$country->name_cn}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="state_id"> 州/省/地区：</label>

                                <div class="controls m-t-10">
                                    <select name="state_id" id="state_id" class="form-control" onchange="changeState()">
                                        <option value="0">--请选择州/省/地区--</option>
                                        @if(isset($property) && !is_null($property->country))
                                            @foreach($property->country->states as $state)
                                                <option value="{{$state->id}}" {{$property->state_id == $state->id ? 'selected' : ''}}>{{$state->name_cn}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city_id">所属的地理城市：</label>
                                <div class="controls m-t-10">
                                    <select name="city_id" id="city_id" class="form-control" onchange="changeCity()">
                                        <option value="0">--请选择城市--</option>
                                        @if(isset($property) && !is_null($property->state))
                                            @foreach($property->state->cities as $city)
                                                <option value="{{$city->id}}" {{$property->city_id == $city->id ? 'selected' : ''}}>{{$city->name_cn}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="suburb_id">小区：</label>

                                <div class="controls m-t-10">
                                    <select name="suburb_id" id="suburb_id" class="form-control">
                                        <option value="0">--请选择小区--</option>
                                        @if(isset($property) && !is_null($property->city))
                                            @foreach($property->city->suburbs as $suburb)
                                                <option value="{{$suburb->id}}" {{$property->suburb_id == $suburb->id ? 'selected' : ''}}>{{$suburb->name_cn}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">物业类型</h3>
                        <hr>
                        <div class="form-body">
                            <div class="form-group">
                                <label for="prop_type">物业类型：<span class="text-danger">*</span> </label>
                                <div class="controls">
                                    <select name="prop_type" id="prop_type" class="form-control" onchange="changePropType();" required data-validation-required-message="必填">
                                        @if (isset($property))
                                        @foreach($property->country->proptypes as $proptype)
                                            <option value="{{$proptype->id}}" {{$property->prop_type == $proptype->id ? 'selected' : ''}}>{{$proptype->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="prop_title">产权类型：</label>
                                <div class="controls">
                                    <input type="text" class="form-control" id="prop_title" name="prop_title" value="{{isset($property) ? $property->prop_title : ''}}"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#mainTab" role="tab"><span class="hidden-sm-up"><i class="ti-desktop"></i></span> <span class="hidden-xs-down">项目基本信息</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#assetTab" role="tab"><span class="hidden-sm-up"><i class="ti-gallery"></i></span> <span class="hidden-xs-down">媒体信息</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#commissionTab" role="tab"><span class="hidden-sm-up"><i class="ti-wallet"></i></span> <span class="hidden-xs-down">佣金</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#fileTab" role="tab"><span class="hidden-sm-up"><i class="ti-write"></i></span> <span class="hidden-xs-down">文件管理</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#educationTab" role="tab"><span class="hidden-sm-up"><i class="ti-notepad"></i></span> <span class="hidden-xs-down">培训材料</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#serviceTab" role="tab"><span class="hidden-sm-up"><i class="ti-agenda"></i></span> <span class="hidden-xs-down">附加服务</span></a> </li>
                    </ul>
                    <!-- Tab panes -->
                    @if(isset($property))
                    <div class="tab-content">
                        <div class="tab-pane active" id="mainTab" role="tabpanel">
                            <div class="card-body">
                                <div class="form-body">
                                    <h4 class="card-title">价格信息</h4>
                                    <div class="form-group row m-t-20">
                                        <div class="col-md-6 p-r-10">
                                            <label for="price_min">项目总价范围：<span class="text-danger">*</span> </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="currency_mark input-group-text">
                                                        {{!is_null($property->country) ? $property->country->symbol : '$'}}
                                                    </span>
                                                </div>
                                                <input type="number" class="form-control" step="0.01" id="price_min" name="price_min" value="{{isset($property) ? $property->price_min : ''}}" required data-validation-required-message="必填">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">万</span>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="m-t-40" style="margin-left: -7px; margin-right: -7px">至</small>
                                        <div class="col-md-6 p-l-10">
                                            <label for="price_max"><span class="text-danger">*</span> </label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="currency_mark input-group-text">
                                                        {{!is_null($property->country) ? $property->country->symbol : '$'}}
                                                    </span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="price_max" name="price_max" value="{{isset($property) ? $property->price_max : ''}}" required data-validation-required-message="必填">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">万</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="initial_deposit">首付比例：<span class="text-danger">*</span> </label>
                                            <div class="input-group mb-3">
                                                <input type="number" step="0.01" class="form-control" id="initial_deposit" name="initial_deposit" value="{{isset($property) ? $property->initial_deposit : ''}}" required data-validation-required-message="必填">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rental_yield">租金：<span class="text-danger">*</span> </label>
                                            <div class="input-group mb-3">
                                                <input type="number" step="0.01" class="form-control" id="rental_yield" name="rental_yield" value="{{isset($property) ? $property->rental_yield : ''}}" required data-validation-required-message="必填">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="price_max">手动预设单价：</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="currency_mark input-group-text">
                                                        {{!is_null($property->country) ? $property->country->symbol : '$'}}
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="unit_price_override" name="unit_price_override" value="{{isset($property) ? $property->unit_price_overide : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="card-title">贷款：</h4>
                                    <div class="form-group row m-t-20">
                                        <div class="col-md-6">
                                            <label for="price_max">是否贷款：</label>
                                            <div class="controls">
                                                <select class="form-control" id="mortgage" name="mortgage">
                                                    <option value="1" {{isset($property) && $property->mortgage == 1 ? 'selected' : ''}}>可贷款</option>
                                                    <option value="0" {{isset($property) && $property->mortgage == 0 ? 'selected' : ''}}>不贷款</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="price_max">贷款比例：</label>
                                            <div class="input-group mb-3">
                                                <input type="number" class="form-control" step="o.o1" id="ltv_ratio" name="ltv_ratio" value="{{isset($property) ? $property->ltv_ratio : ''}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="card-title">交付状态</h4>
                                    <div class="form-group row m-t-20">
                                        <div class="col-md-3">
                                            <label for="volume"> 体量：</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="volume" name="volume" value="{{isset($property) ? $property->volume : ''}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="status"> 状态：</label>
                                            <div class="controls">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="期房" {{isset($property) && $property->status == '期房' ? 'selected' : ''}}>期房</option>
                                                    <option value="现房" {{isset($property) && $property->status == '期房' ? 'selected' : ''}}>现房</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="settle_date">预期交房：</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="settle_date" name="settle_date" value="{{isset($property) ? $property->settle_date : ''}}"> </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="construct_date">预期开工：</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="construct_date" name="construct_date" value="{{isset($property) ? $property->construct_date : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="card-title">描述</h4>
                                    <div class="form-group m-t-20">
                                        <label for="tags">标签：<span class="text-danger">*</span> </label>
                                        <div class="controls">
                                            <input id="tags" name="tags" type="text" class="form-control" value="{{isset($property) ? $property->tags : ''}}" data-role="tagsinput" placeholder="输入标签..." required data-validation-required-message="必填"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">描述：</label>
                                        <div class="controls">
                                            <textarea class="form-control" id="description" name="description" rows="8">{{isset($property) ? $property->description : ''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="developer_comment">开发商描述：</label>
                                        <div class="controls">
                                            <textarea class="form-control" id="developer_comment" name="developer_comment" rows="8">{{isset($property) ? $property->developer_comment : ''}}</textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="card-title">详细地址(GPS)</h4>
                                    <div class="form-group row m-t-20">
                                        <div class="col-md-7">
                                            <div class="controls">
                                                <input type="text" class="form-control" id="address" name="address" placeholder="地址" required data-validation-required-message="必填" value="{{isset($property) ? $property->address : ''}}"> </div>
                                        </div>
                                        <div class="col-md-5">
                                            <button type="button" class="btn btn-warning" onclick="geocode();"> <i class="fa fa-map"></i> 搜索</button>
                                            <button type="button" class="btn btn-cyan" onclick="gotoLocation();"> <i class="ti-location-pin"></i> 生成地段分析</button>
                                        </div>
                                        <input type="hidden" id="lat" name="lat" value="{{isset($property) ? $property->lat : ''}}">
                                        <input type="hidden" id="lng" name="lng" value="{{isset($property) ? $property->lng : ''}}">
                                    </div>
                                    <div id="map-box" style="width: 100%; height: 300px;"></div>
                                    <div class="m-t-10">（ 输入项目位置后，可以通过移动地图上的标签来标记准确位置，请记得调整位置后按保存 ）</div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="assetTab" role="tabpanel">
                            <div class="card-body">
                                <div class="form-body">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <h4 class="card-title">项目图片</h4>
                                            <small >请记得点选大拇指，选择一张图片作为本项目的封面图片</small>
                                            <div id="property-images" class="owl-carousel owl-theme m-t-10">
                                                @if(isset($property) && !is_null($property->images) && $property->images != "")
                                                    @foreach(json_decode($property->images) as $image)
                                                        <div class="item {{$image->is_primary == 1 ? 'highlight_border' : ''}}">
                                                            <img src="{{asset_url($image->url, 'image')}}" height="150">
                                                            <div class="row" style="position:absolute; right:20px; bottom:10px">
                                                                <button type="button" class="btn btn-sm btn-cyan" onclick="setImagePrimary(this,'{{$image->url}}')"><i class="{{$image->is_primary == 1 ? ' ti-thumb-down' : 'ti-thumb-up' }}"></i></button>
                                                                <button type="button" class="btn btn-sm btn-danger m-l-10" onclick="deleteAsset(this, '{{$image->url}}', 'image')"><i class="ti-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12 m-t-10 d-flex">
                                            <input type="file" id="images" name="images[]" class="btn btn-sm btn-info" multiple>
                                            <button type="button" onclick="uploadAsset('image')" class="btn btn-success m-l-10"><i class="ti-upload"></i> 上传</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <h4 class="card-title">视频</h4>
                                            <video id="video-player" controls style="width: 100%;height:300px;">
                                                <source src="{{isset($property) && !is_null($property->video) ? asset_url($property->video, 'video') : ''}}" type="video/mp4">
                                            </video>
                                            <input type="file" id="video" name="video" class="btn btn-sm btn-info" accept="video/mp4">
                                            <button type="button" onclick="uploadAsset('video')" class="btn btn-success m-l-10"><i class="ti-upload"></i> 上传</button>
                                            @if(isset($property) && !is_null($property->video))
                                                <button type="button" id="btn_del_video"  class="btn btn-danger  m-l-10" onclick="deleteAsset(this, '{{$property->video}}', 'video')"><i class="ti-trash"></i> 删除</button>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="card-title">语音</h4>
                                                <audio id="audio-player" controls>
                                                    <source src="{{isset($property) && !is_null($property->audio) ? asset_url($property->audio, 'audio') : ''}}" type="audio/mpeg">
                                                </audio>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="file" id="audio" name="audio" class="btn btn-sm btn-info" accept="audio/mpeg">
                                                <button type="button" onclick="uploadAsset('audio')" class="btn btn-success m-l-10"><i class="ti-upload"></i> 上传</button>
                                                @if(isset($property) && !is_null($property->audio))
                                                    <button id="btn_del_audio" type="button" class="btn btn-danger  m-l-10" onclick="deleteAsset(this, '{{$property->audio}}', 'audio')"><i class="ti-trash"></i> 删除</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="commissionTab" role="tabpanel">
                            <div class="card-body">
                                <div class="form-body">
                                    <h4 class="card-title">佣金信息</h4>
                                    <div class="form-group row m-t-20">
                                        <div class="col-md-6">
                                            <label for="commission_type">佣金类型：</label>
                                            <div class="controls">
                                                <select name="commission_type" id="commission_type" class="form-control" onchange="changeCommissionType()">
                                                    <option value="百分比" {{isset($property) && $property->commission_type == '百分比' ? 'selected' : ''}}>百分比</option>
                                                    <option value="数字" {{isset($property) && $property->commission_type == '数字' ? 'selected' : ''}}>数字</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="commission">佣金：</label>
                                            <div class="input-group mb-3">
                                                <input type="number" step="0.01" class="form-control" id="commission" name="commission" value="{{isset($property) ? $property->commission : ''}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text commssion_tag">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="div_commission_construct">
                                        <div class="col-md-6">
                                            <label for="commission_land">土地佣金：</label>
                                            <div class="input-group mb-3">
                                                <input type="number" step="0.01" class="form-control" id="commission_land" name="commission_land" value="{{isset($property) ? $property->commission_land : ''}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text commssion_tag">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="commission_construct">建筑佣金：</label>
                                            <div class="input-group mb-3">
                                                <input type="number" step="0.01" class="form-control" id="commission_construct" name="commission_construct" value="{{isset($property) ? $property->commission_construct : ''}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text commssion_tag">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="card-title m-t-30">佣金备注</h4>
                                    <div class="form-group">
                                        <div class="controls">
                                            <textarea class="form-control" id="commission_comment" name="commission_comment" rows="8">{{isset($property) ? $property->commission_commet : ''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="fileTab" role="tabpanel">
                            <div class="card-body">
                                <div class="form-body">
                                    <div class="form-group">
                                        <div style="display: flex;">
                                            <div>
                                                <h4 class="card-title">文件打包</h4>
                                                <small class="card-subtitle">在此处管理完整资料的下载链接（最多3种，每种方式最多一个链接）</small>
                                            </div>
                                            <button type="button" class="btn btn-info m-t-10" onclick="showPackageModal(0, false)" style="margin-left: auto;height: 35px;" id="btnAddData"><i class="fa fa-plus"></i> 新增</button>
                                        </div>
                                        <div class="table-responsive m-t-20">
                                            <table id="tblpackage" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>类型</th>
                                                    <th>链接</th>
                                                    <th>备注（比如密码）</th>
                                                    <th class="text-center">操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="text" class="form-control" id="packages" name="packages" value="{{isset($property) ? $property->packages : ''}}" hidden/>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <div style="display: flex;">
                                            <div>
                                                <h4 class="card-title">小文件</h4>
                                                <small class="card-subtitle">在此处管理最常见的小文件。</small>
                                            </div>
                                            <button  type="button" class="btn btn-info m-t-10" onclick="showFileModal(0, '' ,false)" style="margin-left: auto;height: 35px;"><i class="fa fa-plus"></i> 新增</button>
                                        </div>
                                        <div class="table-responsive m-t-20">
                                            <table id="tblfile" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>类型</th>
                                                    <th>文件名称</th>
                                                    <th>更新时间</th>
                                                    <th class="text-center">操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($property) && !is_null($property->files))
                                                    @foreach(json_decode($property->files) as $file)
                                                        <tr>
                                                            <td>
                                                                {{$file->type}}
                                                            </td>
                                                            <td>
                                                                <a href="{{trim(asset_url($file->name,'file'))}}">{{$file->display_name}}</a>
                                                            </td>
                                                            <td>
                                                                {{date('Y-m-d', strtotime($file->updated_at))}}
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-success" onclick="showFileModal('{{$file->id}}','{{asset_url($file->name,"file")}}',true,this)"><i class="ti-pencil-alt"></i></button>
                                                                <button type="button" class="btn btn-sm btn-danger m-l-10" onclick="deleteSmallFile('{{$file->id}}',this)"><i class="ti-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="educationTab" role="tabpanel">
                            <div class="card-body">
                                <div class="form-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label> 项目负责人：</label><br>
                                            <small class="card-subtitle">分销状态下，本项目的默认联系人（仅可填入HASH ID）</small>
                                            <input type="text" class=" m-t-10 form-control" id="internal_manager_id" name="internal_manager_id" value="{{!is_null($property->internal_manager) ? $property->internal_manager->hash : ''}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label> 培训人：</label><br>
                                            <small class="card-subtitle">分销状态下，培训视频前端展示的培训人名称，请手动输入。</small>
                                            <input type="text" class=" m-t-10 form-control" id="trainer" name="trainer" value="{{$property->trainer}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="card-title">培训视频：</label>
                                                <video id="training-video-player" controls style="width: 100%;height:300px;">
                                                    <source src="{{isset($property) && !is_null($property->training_video) ? asset_url($property->training_video, 'video') : ''}}" type="video/mp4">
                                                </video>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="file" id="training_video" name="training_video" class="btn btn-sm btn-info" accept="video/mp4">
                                                <button type="button" onclick="uploadAsset('training_video')" class="btn btn-success m-l-10"><i class="ti-upload"></i> 上传</button>
                                                @if(isset($property) && !is_null($property->training_video))
                                                    <button type="button" id="btn_del_training_video"  class="btn btn-danger  m-l-10" onclick="deleteAsset(this, '{{$property->training_video}}', 'training_video')"><i class="ti-trash"></i> 删除</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group m-t-20">
                                        <label > 培训文章： </label>
                                        <div class="row col-md-12 ">

                                            <div class="table-responsive">
                                                <table id="tbl_training" class="display nowrap table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">文章Hash</th>
                                                        <th class="text-center">标题</th>
                                                        <th class="text-center">文章分类</th>
                                                        <th class="text-center">操作</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(isset($property) && !is_null($property->training_articles) && $property->training_articles != "")
                                                        @foreach(json_decode($property->training_articles) as $article_id)
                                                            <tr>
                                                                <td>
                                                                    {{\App\Article::where("id",$article_id)->first()->hash}}
                                                                </td>
                                                                <td>
                                                                    <a href="/admin/article/{{\App\Article::where("id",$article_id)->first()->hash}}">{{\App\Article::where("id",$article_id)->first()->name}}</a>
                                                                </td>
                                                                <td>
                                                                    {{!is_null(\App\Article::where("id",$article_id)->first()->category) ? \App\Article::where("id",$article_id)->first()->category->name : ''}}
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteArticle('{{\App\Article::where("id",$article_id)->first()->hash}}',this)"><i class="ti-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row col-md-12 m-t-20 m-b-20 justify-content-center" >
                                                <button class="btn btn-rounded btn-info" type="button" onclick="createArticle();return false;"><i class="ti-plus"></i> 添加文章</button>
                                            </div>
                                            <input type="text" class="form-control" id="training_articles" name="training_articles" value="{{isset($property) ? $property->training_articles : ''}}" hidden/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="serviceTab" role="tabpanel">
                            <div class="card-body">
                                <div class="form-body">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="location_id"> 地段分析：</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="location_id" name="location_id" value="{{isset($property) && !is_null($property->location) ? $property->location->hash : ''}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="unit_price_override">小区分析：</label>
                                            <div class="controls">
                                                <input type="text" class="form-control" id="unit_price_override" name="unit_price_override" value="{{isset($property) ? $property->unit_price_override : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group m-t-30">
                                        <div class="col-md-12">
                                            <div style="display: flex;align-items: center;">
                                                <div>
                                                    <h4>户型/价格单</h4>
                                                </div>
                                                <button  type="button" class="btn btn-info " onclick="showPriceModal(0, false)" style="margin-left: auto;height: 35px;"><i class="fa fa-plus"></i> 新增</button>
                                            </div>
                                            <div class="controls  m-t-20">
                                                <table id="tbl_price" class="table table-hover table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>价格单Hash</th>
                                                        <th>价格单名称</th>
                                                        <th>户型</th>
                                                        <th class="text-center">操作</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($property->price_lists as $pricelist)
                                                        <tr>
                                                            <td>{{$pricelist->hash}}</td>
                                                            <td>{{$pricelist->name}}</td>
                                                            <td>
                                                                @foreach($pricelist->units as $unit)
                                                                    <a href="/admin/property/unit/{{$unit->hash}}">{{$unit->unit_number}}</a>&nbsp&nbsp&nbsp
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-cyan" onclick="gotoAddUnit('{{$pricelist->hash}}')"><i class="ti-link"></i></button>
                                                                <button type="button" class="btn btn-sm btn-success m-l-5" onclick="changePrice('{{$pricelist->hash}}','{{$pricelist->name}}',this)"><i class="ti-pencil-alt"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group m-t-30">
                                        <div class="col-md-12">
                                            <div style="display: flex;align-items: center;">
                                                <div>
                                                    <h4>FAQ列表</h4>
                                                </div>
                                                <button  type="button" class="btn btn-info " onclick="showFaqModal(0, false)" style="margin-left: auto;height: 35px;"><i class="fa fa-plus"></i> 新增</button>
                                            </div>
                                            <div class="controls  m-t-20">
                                                <table id="tbl_faq" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Faq名称</th>
                                                        <th>Hash</th>
                                                        <th class="text-center">操作</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(isset($property) && !is_null($property->faq_ids))
                                                        @foreach(json_decode($property->faq_ids) as $faq_id)
                                                            <tr>
                                                                <td>
                                                                    {{\App\Faq::where("id",$faq_id)->first()->name}}
                                                                </td>
                                                                <td>
                                                                    {{\App\Faq::where("id",$faq_id)->first()->hash}}
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteFaq('{{\App\Faq::where("id",$faq_id)->first()->hash}}',this)"><i class="ti-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card-body">
                        <h4>上传基本信息之后才能看到。</h4>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <div id="package-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">文件打包</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="package-form" novalidate>
                    <div class="modal-body">
                        <div class="form-group">
                            <label> 类型：<span class="text-danger">*</span> </label>
                            <div class="controls">
                                <select name="type" id="package-type" required class="form-control">
                                    <option value="百度">百度</option>
                                    <option value="微云">微云</option>
                                    <option value="Google">Google</option>
                                    <option value="Dropbox">Dropbox</option>
                                    <option value="BOX">BOX</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label> 链接：<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="package-link" name="link" required data-validation-required-message="必填" value="" placeholder="请输入链接。。。">
                        </div>
                        <div class="form-group">
                            <label> 备注：</label>
                            <input type="text" class="form-control" id="package-comment" name="comment" value="" placeholder="请输入备注。。。">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-success" onclick="addPackage()"><i class="ti-save"></i> 保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="file-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">小文件管理</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="file-form" novalidate>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="file" id="small_file" name="file" class="dropify" data-height="210"/>
                        </div>
                        <div class="form-group">
                            <label> 前端显示名称：<span class="text-danger">*</span> </label>
                            <input type="text" id="display_name" name="display_name"  class="form-control" value="" placeholder="请输入显示名。。。"/>
                        </div>
                        <div class="form-group">
                            <label> 类型：<span class="text-danger">*</span> </label>
                            <div class="controls">
                                <select name="type" id="file_type" required class="form-control">
                                    <option value="价格单">价格单</option>
                                    <option value="楼书">楼书</option>
                                    <option value="Factsheet">Factsheet</option>
                                    <option value="户型图/楼面图">户型图/楼面图</option>
                                    <option value="购房合同">购房合同</option>
                                    <option value="其他类型">其他类型</option>
                                </select>
                            </div>
                        </div>
                        <input id="file_id" name="id" type="text" hidden value=""/>
                        <input type="hidden" id="property_hash" name="property_hash" value="{{isset($property) ? $property->hash : ''}}"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="price-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">价格单管理</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="package-form" novalidate>
                    <div class="modal-body">
                        <div class="form-group">
                            <label> 价格单Hash：<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="price_hash" name="price_hash" required data-validation-required-message="必填" value="" placeholder="请输入价格单Hash。。。">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-success" onclick="addPrice()"><i class="ti-save"></i> 保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="price-change-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">价格单管理</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="package-form" novalidate>
                    <div class="modal-body">
                        <div class="form-group">
                            <label> 价格单名称：<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="price_name" name="price_name" required data-validation-required-message="必填" value="" placeholder="请输入价格单名称。。。">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-success" onclick="changePriceName()"><i class="ti-save"></i> 保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="article-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">培训文章详情</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label> 文章Hash：<span class="text-danger">*</span> </label>
                        <div class="controls">
                            <input type="text" class="form-control" id="article_hash" name="article_hash" required data-validation-required-message="必填" > </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-success" onclick="addArticle();"><i class="ti-save"></i> 添加</button>
                </div>
            </div>
        </div>
    </div>

    <div id="faq-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">FAQ管理</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="package-form" novalidate>
                    <div class="modal-body">
                        <div class="form-group">
                            <label> FAQ Hash：<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" id="faq_hash" name="faq_hash" required data-validation-required-message="必填" value="" placeholder="请输入价格单Hash。。。">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-success" onclick="addFaq()"><i class="ti-save"></i> 保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-places.js"></script>

    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/plugin/dropify/dist/js/dropify.min.js')}}"></script>
    <script src="{{asset('static/plugin/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('static/plugin/owl.carousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{ asset('static/plugin/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('static/js/pages/validation.js') }}"></script>
    <script src="{{ asset('static/admin/map.js') }}"></script>
    <script src="{{ asset('static/admin/property.js') }}"></script>
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

        $('.owl-carousel').owlCarousel({
            loop:false,
            margin:10,
            nav:false,
            responsive:{
                0:{
                    items:1
                },
                1200:{
                    items:3
                },
                1900:{
                    items:5
                }
            }
        });

        if ($('#hash').val() == '')
            changeCountry();

        initializeMap();
    </script>
@endsection
