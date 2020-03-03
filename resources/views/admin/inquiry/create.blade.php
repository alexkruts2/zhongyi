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
                        <div class="form-group mt-3 row">
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_1" name="biaozheng[]" value="发热">
                                    <label class="custom-control-label" for="biaozheng_1">发热</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_2" name="biaozheng[]" value="汗出">
                                    <label class="custom-control-label" for="biaozheng_2">汗出</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_3" name="biaozheng[]" value="恶风">
                                    <label class="custom-control-label" for="biaozheng_3">恶风</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_4" name="biaozheng[]" value="鼻鸣干呕">
                                    <label class="custom-control-label" for="biaozheng_4">鼻鸣干呕</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_5" name="biaozheng[]" value="头项强痛">
                                    <label class="custom-control-label" for="biaozheng_5">头项强痛</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_6" name="biaozheng[]" value="恶寒">
                                    <label class="custom-control-label" for="biaozheng_6">恶寒</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_7" name="biaozheng[]" value="项背强几几">
                                    <label class="custom-control-label" for="biaozheng_7">项背强几几</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_8" name="biaozheng[]" value="汗出恶风">
                                    <label class="custom-control-label" for="biaozheng_8">汗出恶风</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_9" name="biaozheng[]" value="汗遂漏不止">
                                    <label class="custom-control-label" for="biaozheng_9">汗遂漏不止</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_10" name="biaozheng[]" value="小便难">
                                    <label class="custom-control-label" for="biaozheng_10">小便难</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_11" name="biaozheng[]" value="四肢微急难以屈伸">
                                    <label class="custom-control-label" for="biaozheng_11">四肢微急难以屈伸</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_12" name="biaozheng[]" value="微寒">
                                    <label class="custom-control-label" for="biaozheng_12">微寒</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_13" name="biaozheng[]" value="如虐状">
                                    <label class="custom-control-label" for="biaozheng_13">如虐状</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_14" name="biaozheng[]" value="如虐状">
                                    <label class="custom-control-label" for="biaozheng_14">如虐状</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_15" name="biaozheng[]" value="热多寒少">
                                    <label class="custom-control-label" for="biaozheng_15">热多寒少</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_16" name="biaozheng[]" value="身痒">
                                    <label class="custom-control-label" for="biaozheng_16">身痒</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_17" name="biaozheng[]" value="面色有热色">
                                    <label class="custom-control-label" for="biaozheng_17">面色有热色</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_18" name="biaozheng[]" value="大汗出">
                                    <label class="custom-control-label" for="biaozheng_18">大汗出</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_19" name="biaozheng[]" value="形似疟">
                                    <label class="custom-control-label" for="biaozheng_19">形似疟</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_20" name="biaozheng[]" value="无汗">
                                    <label class="custom-control-label" for="biaozheng_20">无汗</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_21" name="biaozheng[]" value="自汗出">
                                    <label class="custom-control-label" for="biaozheng_21">自汗出</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaozheng_22" name="biaozheng[]" value="微恶寒">
                                    <label class="custom-control-label" for="biaozheng_22">微恶寒</label>
                                </div>
                            </div>
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">里症
                            </label>
                        </div>
                        <div class="form-group mt-3 row">
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_1" name="lizheng[]" value="下之后">
                                    <label class="custom-control-label" for="lizheng_1">下之后</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_2" name="lizheng[]" value="不呕">
                                    <label class="custom-control-label" for="lizheng_2">不呕</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_3" name="lizheng[]" value="大烦渴不解">
                                    <label class="custom-control-label" for="lizheng_3">大烦渴不解</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_4" name="lizheng[]" value="心下满微痛">
                                    <label class="custom-control-label" for="lizheng_4">心下满微痛</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_5" name="lizheng[]" value="吐逆">
                                    <label class="custom-control-label" for="lizheng_5">吐逆</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_6" name="lizheng[]" value="胃气不和谵语">
                                    <label class="custom-control-label" for="lizheng_6">胃气不和谵语</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_7" name="lizheng[]" value="自下利">
                                    <label class="custom-control-label" for="lizheng_7">自下利</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_8" name="lizheng[]" value="不下利但呕">
                                    <label class="custom-control-label" for="lizheng_8">不下利但呕</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_9" name="lizheng[]" value="利遂不止">
                                    <label class="custom-control-label" for="lizheng_9">利遂不止</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_10" name="lizheng[]" value="干呕">
                                    <label class="custom-control-label" for="lizheng_10">干呕</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_11" name="lizheng[]" value="渴">
                                    <label class="custom-control-label" for="lizheng_11">渴</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_12" name="lizheng[]" value="噎">
                                    <label class="custom-control-label" for="lizheng_12">噎</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_13" name="lizheng[]" value="发热不渴">
                                    <label class="custom-control-label" for="lizheng_13">发热不渴</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_14" name="lizheng[]" value="不渴">
                                    <label class="custom-control-label" for="lizheng_14">不渴</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="lizheng_15" name="lizheng[]" value="叉手自冒心">
                                    <label class="custom-control-label" for="lizheng_15">叉手自冒心</label>
                                </div>
                            </div>
                        </div>

                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">半表半里
                            </label>
                        </div>
                        <div class="form-group mt-3 row">
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_1" name="biaoli[]" value="胸满">
                                    <label class="custom-control-label" for="biaoli_1">胸满</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_2" name="biaoli[]" value="小便不利">
                                    <label class="custom-control-label" for="biaoli_2">小便不利</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_3" name="biaoli[]" value="小便难">
                                    <label class="custom-control-label" for="biaoli_3">小便难</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_4" name="biaoli[]" value="小便数">
                                    <label class="custom-control-label" for="biaoli_4">小便数</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_5" name="biaoli[]" value="心烦">
                                    <label class="custom-control-label" for="biaoli_5">心烦</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_6" name="biaoli[]" value="咽中干">
                                    <label class="custom-control-label" for="biaoli_6">咽中干</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_7" name="biaoli[]" value="烦躁">
                                    <label class="custom-control-label" for="biaoli_7">烦躁</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_8" name="biaoli[]" value="喘而胸满者">
                                    <label class="custom-control-label" for="biaoli_8">喘而胸满者</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_9" name="biaoli[]" value="嗜卧">
                                    <label class="custom-control-label" for="biaoli_9">嗜卧</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_10" name="biaoli[]" value="胸满胁痛">
                                    <label class="custom-control-label" for="biaoli_10">胸满胁痛</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_11" name="biaoli[]" value="不汗出而烦躁">
                                    <label class="custom-control-label" for="biaoli_11">不汗出而烦躁</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_12" name="biaoli[]" value="咳">
                                    <label class="custom-control-label" for="biaoli_12">咳</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_13" name="biaoli[]" value="少腹满">
                                    <label class="custom-control-label" for="biaoli_13">少腹满</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_14" name="biaoli[]" value="喘">
                                    <label class="custom-control-label" for="biaoli_14">喘</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_15" name="biaoli[]" value="咳而微喘">
                                    <label class="custom-control-label" for="biaoli_15">咳而微喘</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_16" name="biaoli[]" value="微喘">
                                    <label class="custom-control-label" for="biaoli_16">微喘</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_17" name="biaoli[]" value="昼日烦躁不得眠">
                                    <label class="custom-control-label" for="biaoli_17">昼日烦躁不得眠</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_18" name="biaoli[]" value="夜而安静">
                                    <label class="custom-control-label" for="biaoli_18">夜而安静</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="biaoli_19" name="biaoli[]" value="气上冲胸">
                                    <label class="custom-control-label" for="biaoli_19">气上冲胸</label>
                                </div>
                            </div>
                        </div>
                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">脉症
                            </label>
                        </div>
                        <div class="form-group mt-3 row">
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_1" name="mai[]" value="脉缓">
                                    <label class="custom-control-label" for="mai_1">脉缓</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_2" name="mai[]" value="脉浮缓">
                                    <label class="custom-control-label" for="mai_2">脉浮缓</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_3" name="mai[]" value="脉促">
                                    <label class="custom-control-label" for="mai_3">脉促</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_4" name="mai[]" value="脉微缓">
                                    <label class="custom-control-label" for="mai_4">脉微缓</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_5" name="mai[]" value="脉微">
                                    <label class="custom-control-label" for="mai_5">脉微</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_6" name="mai[]" value="脉洪大">
                                    <label class="custom-control-label" for="mai_6">脉洪大</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_7" name="mai[]" value="脉微弱">
                                    <label class="custom-control-label" for="mai_7">脉微弱</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_8" name="mai[]" value="脉浮">
                                    <label class="custom-control-label" for="mai_8">脉浮</label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_9" name="mai[]" value="脉沉">
                                    <label class="custom-control-label" for="mai_9">脉沉</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_10" name="mai[]" value="脉浮细">
                                    <label class="custom-control-label" for="mai_10">脉浮细</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_11" name="mai[]" value="脉沉微">
                                    <label class="custom-control-label" for="mai_11">脉沉微</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_12" name="mai[]" value="脉沉紧">
                                    <label class="custom-control-label" for="mai_12">脉沉紧</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_13" name="mai[]" value="脉浮数">
                                    <label class="custom-control-label" for="mai_13">脉浮数</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_14" name="mai[]" value="脉微而沉">
                                    <label class="custom-control-label" for="mai_14">脉微而沉</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_15" name="mai[]" value="脉沉结">
                                    <label class="custom-control-label" for="mai_15">脉沉结</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_16" name="mai[]" value="脉浮而动数">
                                    <label class="custom-control-label" for="mai_16">脉浮而动数</label>
                                </div>
                            </div>
                            <div class="col-sm-3">

                            <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_17" name="mai[]" value="脉沉而紧">
                                    <label class="custom-control-label" for="mai_17">脉沉而紧</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_18" name="mai[]" value="脉关上浮">
                                    <label class="custom-control-label" for="mai_18">脉关上浮</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_19" name="mai[]" value="寸脉微浮">
                                    <label class="custom-control-label" for="mai_19">寸脉微浮</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_20" name="mai[]" value="脉浮">
                                    <label class="custom-control-label" for="mai_20">脉浮</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_21" name="mai[]" value="脉浮虚而涩">
                                    <label class="custom-control-label" for="mai_21">脉浮虚而涩</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_22" name="mai[]" value="脉浮滑">
                                    <label class="custom-control-label" for="mai_22">脉浮滑</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_23" name="mai[]" value="脉结悸">
                                    <label class="custom-control-label" for="mai_23">脉结悸</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_24" name="mai[]" value="脉结代">
                                    <label class="custom-control-label" for="mai_24">脉结代</label>
                                </div>
                            </div>
                            <div class="col-sm-3">

                            <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_25" name="mai[]" value="脉迟">
                                    <label class="custom-control-label" for="mai_25">脉迟</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_26" name="mai[]" value="脉弦">
                                    <label class="custom-control-label" for="mai_26">脉弦</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_27" name="mai[]" value="脉涩">
                                    <label class="custom-control-label" for="mai_27">脉涩</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_28" name="mai[]" value="脉滑">
                                    <label class="custom-control-label" for="mai_28">脉滑</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="mai_29" name="mai[]" value="脉浮数">
                                    <label class="custom-control-label" for="mai_29">脉浮数</label>
                                </div>
                            </div>
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
