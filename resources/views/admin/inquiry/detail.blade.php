@extends('admin.layout.admin')
<link href="{{ asset('static/plugin/horizontal-timeline/css/horizontal-timeline.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('static/plugin/horizontal-timeline/css/horizontal-timeline.css') }}" rel="stylesheet" type="text/css">

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
                                <video width="100%" height="auto" controls  >
                                    <source src="/uploads/videos/{{$historyData->record_video}}" type="video/webm">
                                </video>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-5 offset-1 col-form-label">主诉
                                <button data-toggle="collapse" class="btn btn-default" data-target="#annotationSection" aria-expanded="false">
                                    <i class="fas fa-angle-right"></i>
                                    <i class="fas fa-angle-down"></i>
                                </button>
                            </label>
                        </div>
                        <div id = "annotationSection" class="collapse">
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
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">表症 : <b>{{$historyData->biaozheng}}</b></label>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">里症 : <b>{{$historyData->lizheng}}</b></label>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">半表半里 : <b>{{$historyData->biaoli}}</b></label>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">脉症 : <b>{{$historyData->mai}}</b></label>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">医生嘱托 : <b>{{$historyData->doctor_question}}</b></label>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" {{$historyData->houfang=='1'?'checked':''}}>
                                    <label class="custom-control-label" for="houfang">是否合方</label>
                                </div>

                            </label>
                        </div>

                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">病名 : <b>{{$historyData->disease_name}}</b></label>
                        </div>

                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-5 offset-1 col-form-label">药方: <b>{{$historyData->recipe_name}}</b>
                                <button data-toggle="collapse" class="btn btn-default" data-target="#medicineSection" aria-expanded="false">
                                    <i class="fas fa-angle-right"></i>
                                    <i class="fas fa-angle-down"></i>
                                </button>

                            </label>
                        </div>
                        <div id="medicineSection" class="mt-3 collapse">
                            @php
                                $i = 0
                            @endphp
                            @while (isset($historyData->medicines[$i]))
                                <div class="row">
                                    <label class="col-2 col-form-label text-right">
                                        &nbsp;{{$historyData->medicines[$i]->medicine}}</label>
                                    <div class="col-3">
                                        <input class="form-control" type="text" value="{{$historyData->medicines[$i]->weight}}" readonly>
                                    </div>
                                    <div class="col-3 text-center">
                                        <label id="price_{{$historyData->medicines[$i]->medicine_id}}" style="line-height: 38px;">{{$historyData->medicines[$i]->price}} 元/10g</label>
                                    </div>
                                </div>
                                @php
                                    $i++
                                @endphp
                            @endwhile
                        </div>
                        <div class="form-group mt-3 row">
                            <label class="col-5 offset-1">总价: <b id="total_price_span" >{{$historyData->price}}</b>元</label>
                        </div>
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
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('static/plugin/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('static/admin/inquiry_detail.js') }}"></script>

@endsection
