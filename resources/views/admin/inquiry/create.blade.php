@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/video-record/css/video-js.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/video-record/css/videojs.record.min.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">

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
                                <input class="form-control" type="text" value="{{$treatment->ID_Number}}" name='ID_Number' id="ID_Number" readonly>
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
                        </div>
                        <div class="mt-3 row">
                            <div class="col-md-10 form-inline">
                                <label for="history_number" >&nbsp;&nbsp;&nbsp;标题&nbsp;&nbsp; </label>
                                <select class="select2 m-b-10" name="question_title" id="question_title" data-placeholder="请选择"data-parsley-required style="width:71.3%;">
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
                                <video id="myVideo" preload="auto" autoplay  class="video-js vjs-default-skin"></video>
                            </div>
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">主诉
                                <button type="button" title="添加主诉" onclick="appendAnnotation()" class="btn btn-circle btn-md btn-success p-0-0"><i class="fas fa-plus"></i></button>
                            </label>
                        </div>
                        <div id = "annotationSection">
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
                        <div class="mt-3" id="lizhengSection">
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">半表半里
                            </label>
                        </div>
                        <div class="mt-3" id="biaoliSection">
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">脉症
                            </label>
                        </div>
                        <div class="mt-3" id="maizhengSection">
                        </div>

                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">药方
                                <button type="button" class="btn btn-circle btn-warning p-0-0"  data-toggle="modal" data-target="#myModal" title="添加药材" ><i class="fas fa-plus"></i></button>
                            </label>
                        </div>

                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right"></label>
                            <div class="col-6">
                                <select class="m-b-10 form-control" style="width: 100%" data-placeholder="Choose" name="recipe" id="recipe">
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                            </div>
                        </div>

                        <div id="medicineSection" class="mt-3">
                        </div>
                        <hr>
                        <div class="form-group mt-3 row">
                            <label class="col-2 text-right">总价: </label>
                            <div class="col-3">
                                <span id="total_price_span" ></span>元
                            </div>
                            <input type="hidden" id="total_price" name="total_price" ></input>
                        </div>

                        <div class="form-group mt-3 row bg-gray">
                            <label for="example-text-input" class="col-2 col-form-label text-right" >其他症状</label>
                            <div class="col-8">
                                <textarea class="form-control" type="text" name='other_condition' id="other_condition" style="background-color:#ddd;" disabled></textarea>
                            </div>
                        </div>
                        <div class="form-group mt-3 row bg-gray">
                            <label for="example-text-input" class="col-2 col-form-label text-right" >禁忌</label>
                            <div class="col-8">
                                <textarea class="form-control" type="text" name='ban' id="ban" style="background-color:#ddd;"  disabled></textarea>
                            </div>
                        </div>
                        <div class="form-group mt-3 row bg-gray">
                            <label for="example-text-input" class="col-2 col-form-label text-right" >煎服方法</label>
                            <div class="col-8">
                                <textarea class="form-control" type="text" name='eating_method' id="eating_method" style="background-color:#ddd;"  disabled></textarea>
                            </div>
                        </div>
                        <div class="form-group mt-3 row bg-gray">
                            <label for="example-text-input" class="col-2 col-form-label text-right" >医生嘱托</label>
                            <div class="col-6">
                                <textarea class="form-control" type="text" name='doctor_question' id="doctor_question" style="background-color:#ddd;"></textarea>
                            </div>
                        </div>
                        <div class="form-group mt-3 row bg-gray">
                            <label for="example-text-input" class="col-2 col-form-label text-right" >病名</label>
                            <div class="col-6">
                                <input class="form-control" type="text" name='disease_name' id="disease_name" style="background-color:#ddd;">
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right" ></label>
                            <div class="col-8">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" class="custom-control-input" name="houfang" value="0">
                                    <input type="checkbox" class="custom-control-input" id="houfang" name="houfang" value="1" onclick="this.previousSibling.value=1-this.previousSibling.value">
                                    <label class="custom-control-label" for="houfang">是否合方</label>
                                </div>
                            </div>
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
                            <select class="form-control" name='medicine' id="medicine">
                                <option value="0">--请选择药材--</option>
                                @foreach ($medicines as $medicine)
                                    <option value="{{$medicine->id}}" data-min="{{$medicine->min_weight}}" data-max="{{$medicine->max_weight}}" data-price="{{$medicine->price}}">{{$medicine->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="addMedicineInModal()"><i class=" ti-plus"></i> 确认</button>
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

@endsection
