@extends('admin.layout.admin')
<link href="{{ asset('static/plugin/horizontal-timeline/css/horizontal-timeline.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('static/plugin/horizontal-timeline/css/horizontal-timeline.css') }}" rel="stylesheet" type="text/css">
<link href="{{asset('static/plugin/video-record/css/video-js.min.css')}}" rel="stylesheet">

@section('styles')

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
                    @foreach($histories as $historyData)
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-1 col-form-label text-right">问诊医生</label>
                            <div class="col-2">
                                <input class="form-control" type="text" value="{{$historyData->doctor_name}}" readonly>
                            </div>
                            <label for="example-text-input" class="col-1 col-form-label text-right">患者姓名</label>
                            <div class="col-2">
                                <input class="form-control" type="text" value="{{$historyData->patient_name}}" readonly>
                            </div>
                            <label for="example-text-input" class="col-1 col-form-label text-right">日期</label>
                            <div class="col-3">
                                <input class="form-control" type="text" value="{{$historyData->treat_start}}" readonly>
                            </div>
                        </div>
                            <div class="form-group mt-3 row">
                                <section class="cd-horizontal-timeline loaded">
                                    <div class="timeline">
                                        <div class="events-wrapper">
                                            <div class="events" style="width: 640px;">
                                                <ol>
                                                    <li><a href="#0" style="left: 10px;" class="{{$historyData->state==config('constant.treat_state.accept')?'selected':''}}">接受</a></li>
                                                    <li><a href="#0" style="left: 120px;" class="{{$historyData->state==config('constant.treat_state.waiting_treatment')?'selected':''}}">等待治疗</a></li>
                                                    <li><a href="#0" style="left: 240px;" class="{{$historyData->state==config('constant.treat_state.treating')?'selected':''}}">在治疗中</a></li>
                                                    <li><a href="#0" style="left: 360px;" class="{{$historyData->state==config('constant.treat_state.before_treating_pay')?'selected':''}}">结束治疗</a></li>
                                                    <li><a href="#0" style="left: 480px;" class="{{$historyData->state==config('constant.treat_state.after_treating_pay')?'selected':''}}">结束治疗付款</a></li>
                                                    <li><a href="#0" style="left: 600px;" class="{{$historyData->state==config('constant.treat_state.close')?'selected':''}}">结束</a></li>
                                                </ol>
                                            </div>
                                            <!-- .events -->
                                        </div>
                                        <!-- .events-wrapper -->
                                    </div>
                                </section>
                            </div>
                        <input type="hidden" name="medicines" id="medicines" />

                            <div class="form-group mt-3 row">
                            <div class="col-lg-6 question-area">
                                @php
                                    $i = 0
                                @endphp
                                @while (isset($historyData->questions[$i]))
                                        @if(isset($historyData->questions[$i]->selectIndex))
                                            <div class="timeline-panel">
                                                <div class="timeline-heading">
                                                    <p class="timeline-title font-weight-bold"><b>{{$historyData->questions[$i]->question}}</b></p>
                                                </div>
                                                <div class="timeline-body">
                                                    <p>{{$historyData->questions[$i]->answers[$historyData->questions[$i]->selectIndex]}}</p>
                                                </div>
                                            </div>
                                        @endif
                                     @php
                                        $i++
                                    @endphp
                                @endwhile
                            </div>
                            <div class="col-lg-6">
                                <video class="video-js" id="player" controls    preload="auto"　width="640" height="264"  >
                                    <source src="/uploads/videos/{{$historyData->record_video}}" type="video/webm">
                                </video>
                            </div>
                        </div>
                        <div class="row bg-gray mt-3">
                            <div style="font-size: 25px;" class="col-md-2 col-form-label text-left">
                                表症
                            </div>
                        </div>
                        <div id="biaozhengSection">
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


                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-5 offset-1 col-form-label">问诊
                                <button data-toggle="collapse" class="btn btn-default" data-target="#annotationSection" aria-expanded="true">
                                    <i class="fas fa-angle-right"></i>
                                    <i class="fas fa-angle-down"></i>
                                </button>
                            </label>
                        </div>
                        <div id = "annotationSection" class="collapse show">
                            @php
                                $i = 0
                            @endphp
                            @while (isset($historyData->annotations[$i]))
                                <div class="form-group mt-3 row">
                                    <div class="col-2 ">
                                    </div>
                                    <div class="col-3">
                                        <input class="form-control" type="text" readonly value="{{$historyData->annotations[$i]->key}}">
                                    </div>
                                    <div class="col-3">
                                        <input class="form-control" type="text" readonly value="{{$historyData->annotations[$i]->value}}">
                                    </div>
                                </div>
                                @php
                                    $i++
                                @endphp
                            @endwhile
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-5 offset-1 col-form-label">主诉
                                <button data-toggle="collapse" class="btn btn-default" data-target="#commentSection" aria-expanded="true">
                                    <i class="fas fa-angle-right"></i>
                                    <i class="fas fa-angle-down"></i>
                                </button>
                            </label>
                        </div>
                        <div id = "commentSection" class="collapse show">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <textarea rows="4" id="comment" name="comment" class="form-control" disabled>{{$historyData->comment1}}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">医生嘱托 : <b>{{$historyData->doctor_question}}</b></label>
                        </div>


                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-5 offset-1 col-form-label">药方:
                                <button data-toggle="collapse" class="btn btn-default" data-target="#medicineSection" aria-expanded="true">
                                    <i class="fas fa-angle-right"></i>
                                    <i class="fas fa-angle-down"></i>
                                </button>

                            </label>
                        </div>
                        <div id="medicineSection" class="mt-3 collapse show">
                        </div>
                        <div class="row mt-3"><div class="col-sm-1"></div>
                            <hr class="col-sm-10">

                            <div class="col-sm-1"></div>
                            <div class="col-sm-2 text-right p-r-0">总价：</div> <h4 class="text-bold col-sm-5"><b id="total_price_span"></b>元</h4>
                        </div>
                        @if(!empty($historyData->shippingType))
                        <div class="row mt-3">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-2 text-right p-r-0">发药方式：</div>
                            <h4 class="text-bold col-sm-5">{{$historyData->shippingType}}</h4>
                        </div>
                        @endif
                        @if($historyData->shippingType=='邮寄')
                            <div class="row mt-3">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2 text-right p-r-0">快递公司　：</div>
                                <h4 class="text-bold col-sm-5">{{$historyData->kuaidiCompany}}</h4>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-2 text-right p-r-0">快递单号　：</div>
                                <h4 class="text-bold col-sm-5">{{$historyData->kuaidiNumber}}</h4>
                            </div>
                        @endif
                    @endforeach
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
        .question-area{
            background-color:#eee;
            overflow: auto;
        }
        .cd-horizontal-timeline{
            margin:10px;
            width:100%;
        }
        .cd-horizontal-timeline .events a{
            padding-bottom: 5px;
        }
        button[aria-expanded="true"] i:first-child,button[aria-expanded="false"] i:nth-child(2){
            display:none;
        }
        button[aria-expanded="false"] i:first-child,button[aria-expanded="true"] i:nth-child(2){
            display:block;
        }
        .bg-gray{
            background-color:#eee;
        }

    </style>
@endsection

@section('scripts')
    <script src="{{ asset('static/plugin/video-record/js/video.js') }}"></script>

    <script src="{{ asset('static/plugin/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('static/admin/inquiry_detail.js') }}"></script>

    <script>
        var biaozhengList="{{rtrim(ltrim($biaozheng,'['),']')}}".replace(/&quot;/g,'').split(','),lizhengList = "{{rtrim(ltrim($lizheng,'['),']')}}".replace(/&quot;/g,'').split(','),
            biaoliList = "{{rtrim(ltrim($biaoli,'['),']')}}".replace(/&quot;/g,'').split(','), maizhengList = "{{rtrim(ltrim($maizheng,'['),']')}}".replace(/&quot;/g,'').split(',');
           var selBiaozheng = "{{$historyData->biaozheng}}".split(','),
            selLizheng = "{{$historyData->lizheng}}".split(','),
            selBiaoli = "{{$historyData->biaoli}}".split(','),
            selmaizheng = "{{$historyData->mai}}".split(',');
        var recipes = "{{json_encode($historyData->recipe)}}".replace(/&quot;/g,'"');
        recipes = recipes.substr(1,recipes.length-2);
    </script>
@endsection
