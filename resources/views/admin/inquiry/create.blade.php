@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/video-record/css/video-js.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/video-record/css/videojs.record.min.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet" type="text/css">

@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">问诊</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">问诊</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <form id="question-form" method="post" data-parsley-validate="">
                        <input type="hidden" id="video_url" name="video_url"/>
                        <input type="hidden" id="question_string" name="question_string"/>
                        <input type="hidden" id="medicines" name="medicines"/>
                        <input type="hidden" id="heFang" name="heFang"/>
                        <div class="mt-3 row">
                            <div class="col-md-4 form-inline">
                                <label for="guahao" >病历号&nbsp;&nbsp; </label>
                                <input class="form-control w-75" type="text" value="{{$treatment->guahao}}" name='guahao' id="guahao" readonly>
                            </div>
                            <div class="col-md-4 form-inline">
                                <label for="patient_name" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;姓名&nbsp;&nbsp; </label>
                                <input class="form-control w-75" type="text" value="{{$treatment->name}}" name='patient_name' id="patient_name" readonly>
                            </div>
                            <div class="col-md-4 form-inline">
                                <label for="ID_Number" >身份证号&nbsp;&nbsp; </label>
                                <input class="form-control w-75" type="text" value="{{$treatment->ID_Number}}" name='ID_Number' id="ID_Number" readonly>
                            </div>
                        </div>
                        <div class="mt-3 row">
                            <div class="col-md-4 form-inline">
                                <label for="sex" >&nbsp;&nbsp;&nbsp;性别&nbsp;&nbsp; </label>
                                <select class="form-control w-75" name='sex' id="sex" disabled>
                                    <option value="">请选择</option>
                                    <option value="男" {{$treatment->sex=='男'?'selected':''}}>男</option>
                                    <option value="女" {{$treatment->sex=='女'?'selected':''}}>女</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-inline">
                                <label for="history_number" >历次(次)&nbsp;&nbsp; </label>
                                <input class="form-control w-75" type="text" value="{{$history_number}}" name='history_number' id="history_number" readonly>
                            </div>
                            <div class="col-md-4 form-inline">
                                <label for="history_number" >查询病例&nbsp;&nbsp;</label>
                                <select class="select2 w-75" name='search_history' id="search_history"  style="width:100%;">
                                    <option value="">查询病例</option>
                                    @foreach ($histories as $history)
                                        <option value="{{$history->id}}" data-doctor="{{$history->doctor_name}}" data-treat_start="{{$history->treat_start}}" data-price="{{$history->price}}" >{{$history->treat_start}}(医生：{{$history->doctor_name}})</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="mt-3 row">
                            <div class="col-md-10 form-inline">
                                <label for="history_number" >&nbsp;&nbsp;&nbsp;问诊单模板&nbsp;&nbsp; </label>
                                <select class="select2 m-b-10" name="question_title" id="question_title" data-placeholder="请选择问诊单模板" style="width:71.3%;">
                                    <option></option>
                                    @foreach(\App\question::all() as $question)
                                        <option value="{{$question->id}}" >{{$question->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <div class="col-lg-6">
                                <div class="news-slide m-b-15">
                                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel" data-interval="false" data-wrap="false">
                                        <ol class="carousel-indicators">
                                        </ol>
                                        <!-- Carousel items -->
                                        <div class="carousel-inner">
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleSlidesOnly" role="button" data-slide="prev">
                                            <span class="text-black" aria-hidden="true"><i class=" fas fa-arrow-left"></i> </span>
                                            <span class="sr-only  text-dark">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleSlidesOnly" role="button" data-slide="next">
                                            <span class="text-black" aria-hidden="true"><i class=" fas fa-arrow-right"></i> </span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <video id="myVideo" preload="auto"  class="video-js vjs-default-skin" ></video>
                            </div>
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">问诊
                                <button type="button" title="添加问诊" onclick="appendAnnotation()" class="btn btn-circle btn-md btn-success p-0-0"><i class="fas fa-plus"></i></button>
                            </label>
                        </div>
                        <div id = "annotationSection">
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">主诉
                            </label>
                        </div>
                        <div id = "commentSection">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <textarea rows="4" id="comment" name="comment" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">表症
                            </label>
                        </div>
                        <div class="mt-3" id="biaozhengSection">
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">里症
                            </label>
                        </div>
                        <div class="mt-3" id="lizhengSection" >
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">半表半里
                            </label>
                        </div>
                        <div class="mt-3" id="biaoliSection" >
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">脉症
                            </label>
                        </div>
                        <div class="mt-3" id="maizhengSection">
                        </div>

                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">
                                医生嘱托
                            </label>
                        </div>
                        <div id = "annotationSection">
                            <div class="form-group mt-3 row">
                                <div class="col-12">
                                    <textarea class="form-control" type="text" name='doctor_question' id="doctor_question"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">
                                既往病史
                            </label>
                        </div>
                        <div class="form-group mt-3 row">
                            <div class="col-12">
                                <textarea class="form-control" type="text" name='past_history' id="past_history"></textarea>
                            </div>
                        </div>

                        <div class="form-group mt-3 row">
                            <div class="col-md-3"><input type="text" class="form-control" value="" id="total_biaozheng" data-role="tagsinput" placeholder="表症"/> </div>
                            <div class="col-md-3"><input type="text" class="form-control"  value="" id="total_lizheng" data-role="tagsinput" placeholder="里症"/> </div>
                            <div class="col-md-3"><input type="text" class="form-control"  value="" id="total_biaoli" data-role="tagsinput" placeholder="半表半里"/> </div>
                            <div class="col-md-3"><input type="text"  class="form-control" value="" id="total_maizheng" data-role="tagsinput" placeholder="脉症"/> </div>
                        </div>

                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">药方
                            </label>
                            <div class="col-sm-2"><button type="button" class="btn btn-info m-t-10" onclick="searchRecipes();"><i class="ti-search"></i> 出方</button></div>
                        </div>

                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right"></label>
                            <div class="col-4">
                                <select class="m-b-10 form-control select2 select2-multiple" style="width: 100%" data-placeholder="Choose" multiple="multiple" name="recipe[]" id="recipe">
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                            </div>
                        </div>

                        <div id="medicineSection" class="mt-3">
                        </div>

                        <div class="form-group mt-3 row">
                            <div class="col-3">
                                <button type="button" class="btn btn-info" onclick="getHefang()"><i class="fas fa-certificate"></i> 合方</button>
                            </div>
                        </div>
                        <div id="hefangSection" class="mt-3">
                        </div>

                        <hr>
                        <div class="form-group mt-3 row">
                            <label class="col-2 text-right">总价: </label>
                            <div class="col-3">
                                <span id="total_price_span" ></span>元
                            </div>
                            <input type="hidden" id="total_price" name="total_price" ></input>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-center m-t-30">
                                <button type="submit" class="btn btn-success"><i class="ti-save"></i> 保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .carousel-caption{
            top:30px;
            text-align:left;
            right:0%;
            left:7%;
        }
        .bg-gray{
            background-color:#eee;
        }
    </style>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">药材名称</div>
                        <div class="col-10">
                            <select class="select2 form-control" name='medicine' id="medicine"  style="width:100%;">
                                @foreach ($medicines as $medicine)
                                    <option value="{{$medicine->id}}" data-min="{{$medicine->min_weight}}" data-unit="{{$medicine->unit}}" data-max="{{$medicine->max_weight}}" data-price="{{$medicine->price}}">{{$medicine->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="addMedicineInModal(true)"><i class=" ti-plus"></i> 确认</button>
                </div>
            </div>
        </div>
    </div>

    <div id="tipModal" class="modal fade" role="dialog" >
        <div class="modal-dialog" style="margin-right:0px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2 text-right">药房名</div>
                        <div class="col-10" id="modal_recipe_name">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">方证</div>
                        <div class="col-10" id="modal_other_condition">
                        </div>
                    </div><hr>

                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">方药</div>
                        <div class="col-10" id="modal_medicines">
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">煎服法</div>
                        <div class="col-10" id="modal_eatting_method">
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">禁忌</div>
                        <div class="col-10" id="modal_ban">
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px; padding:0;">脉证、病症</div>
                        <div class="col-10" id="modal_maizheng">
                        </div>
                    </div><hr>

                    <div class="row">
                        <div class="col-2 text-right">价格</div>
                        <div class="col-10" id="modal_price">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="historyModal" class="modal fade" role="dialog" >
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">患者姓名:</div>
                        <div class="col-10" id="patient_name" style="line-height: 38px;">{{$treatment->name}}
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">问诊医生:</div>
                        <div class="col-10" id="doctor_name" style="line-height: 38px;">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">日期:</div>
                        <div class="col-10" id="treat_start" style="line-height: 38px;">
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-2 text-right" style="line-height: 38px;">总价:</div>
                        <div class="col-10" style="line-height: 38px;" id="total_price_modal">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('static/plugin/select2/dist/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

    <script src="{{ asset('static/plugin/video-record/js/video.js') }}"></script>
    <script src="{{ asset('static/plugin/video-record/js/RecordRTC.js') }}"></script>
    <script src="{{ asset('static/plugin/video-record/js/adapter.js') }}"></script>
    <script src="{{ asset('static/plugin/video-record/js/videojs.record.min.js') }}"></script>
    <script src="{{ asset('static/admin/inquiry.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.extra.js') }}"></script>
    <script src="{{ asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}" ></script>

@endsection
