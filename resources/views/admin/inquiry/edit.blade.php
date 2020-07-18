@extends('admin.layout.admin')
<link href="{{ asset('static/plugin/horizontal-timeline/css/horizontal-timeline.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('static/plugin/horizontal-timeline/css/horizontal-timeline.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">

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
                    <form id="question-form" method="post" data-parsley-validate="">


                    @foreach($histories as $historyData)

                            <div class="mt-3 row">
                                <div class="col-md-4 form-inline">
                                    <label for="guahao" >病历号&nbsp;&nbsp; </label>
                                    <input class="form-control w-75" type="text" value="{{$historyData->guahao}}" name='guahao' id="guahao" readonly>
                                </div>
                                <div class="col-md-4 form-inline">
                                    <label for="patient_name" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;姓名&nbsp;&nbsp; </label>
                                    <input class="form-control w-75" type="text" value="{{$historyData->patient_name}}" name='patient_name' id="patient_name" readonly>
                                </div>
                                <div class="col-md-4 form-inline">
                                    <label for="ID_Number" >身份证号&nbsp;&nbsp; </label>
                                    <input class="form-control" type="text" value="{{$historyData->patient->ID_Number}}" name='ID_Number' id="ID_Number" readonly>
                                </div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-md-4 form-inline">
                                    <label for="sex" >&nbsp;&nbsp;&nbsp;性别&nbsp;&nbsp; </label>
                                    <select class="form-control w-75" name='sex' id="sex" disabled>
                                        <option value="">请选择</option>
                                        <option value="男" {{$historyData->patient->sex=='男'?'selected':''}}>男</option>
                                        <option value="女" {{$historyData->patient->sex=='女'?'selected':''}}>女</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-inline">
                                    <label for="history_number" >历次(次)&nbsp;&nbsp; </label>
                                    <input class="form-control w-75" type="text" value="{{$history_number}}" name='history_number' id="history_number" readonly>
                                </div>
                            </div>

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
                                <video width="100%" height="auto" controls  >
                                    <source src="/uploads/videos/{{$historyData->record_video}}" type="video/mp4">
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
                            <label for="example-text-input" class="col-5 offset-1 col-form-label">主诉
                                <button type='button' data-toggle="collapse" class="btn btn-default" data-target="#annotationSection" aria-expanded="false">
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
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">医生嘱托 : <b>{{$historyData->doctor_question}}</b></label>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-8 offset-1 col-form-label">既往病史 : <b>{{$historyData->past_history}}</b></label>
                        </div>
                        <div class="form-group mt-3 row">
                            <div class="col-8 offset-1 col-form-label">
                                <textarea class="form-control" type="text" name='past_history' id="past_history"></textarea>
                            </div>
                        </div>


                        <div class="row bg-gray">
                            <label for="example-text-input" style="font-size: 27px;" class="col-2 col-form-label text-left">药方
                            </label>
                            <div class="col-sm-2"><button type="button" class="btn btn-info m-t-10" onclick="searchRecipes();"><i class="ti-search"></i> 出方</button></div>
                        </div>

                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right"></label>
                            <div class="col-6">
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
                    @endforeach
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
                        <div class="col-2 text-right" style="line-height: 38px;">其他病症</div>
                        <div class="col-10" id="modal_other_condition">
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

@endsection

@section('scripts')
    <script src="{{ asset('static/plugin/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('static/admin/editInquiry.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.extra.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

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
