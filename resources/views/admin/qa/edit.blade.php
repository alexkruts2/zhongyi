@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />

@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">问答圈管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">修改答圈</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="question-form" method="post" data-parsley-validate="">
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">编号</label>
                            <div class="col-10">
                                <input class="form-control" type="text"  name='number' id="number" placeholder="请输入标题" value="{{$question->number}}" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">标题</label>
                            <div class="col-10">
                                <input class="form-control" type="text"  name='title' id="title" placeholder="请输入标题" value="{{$question->title}}" data-parsley-required>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">科室</label>
                            <div class="col-10">
                                <select class="form-control" name='department' id="department" data-parsley-required>
                                    <option value="">--全部科室--</option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->id}}" {{$department->id==$department_id?'selected':''}}>{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">药方</label>
                            <div class="col-10">
                                <select class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="Choose" id="recipes" name="recipes[]" value='{{$question->recipes}}' data-parsley-required>
                                    @foreach(\App\recipe::all() as $recipe)
                                        <option value="{{$recipe->id}}" {{in_array($recipe->id,json_decode($question->recipes))?'selected':''}}>{{$recipe->prescription_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="medicineSection">
                        </div>

                        <div class="row bg-gray mt-3">
                            <div style="font-size: 25px;" class="col-md-2 col-form-label text-left">
                                表症
                            </div>
                            <div class="col-md-1 text-right col-form-label">
                                <button type="button" class="btn btn-circle btn-info p-0-0" title="添加表症" onclick="appenItem('biaozheng')"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-3 col-form-label">
                                <input type="text" class="form-control" id="biaozhengInput" />
                            </div>
                            <div class="col-md-1 text-left col-form-label">
                                <button type="button" class="btn btn-circle btn-danger p-0-0" title="删除表症" onclick="removeItem('biaozheng')"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div id="biaozhengSection">
                        </div>
                        <div class="row bg-gray mt-3">
                            <div style="font-size: 25px;" class="col-md-2 col-form-label text-left">
                                里症
                            </div>
                            <div class="col-md-1 text-right col-form-label">
                                <button type="button" class="btn btn-circle btn-info p-0-0" title="添加里症" onclick="appenItem('lizheng')"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-3 col-form-label">
                                <input type="text" class="form-control" id="lizhengInput" />
                            </div>
                            <div class="col-md-1 text-left col-form-label">
                                <button type="button" class="btn btn-circle btn-danger p-0-0" title="删除表症" onclick="removeItem('lizheng')"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div id="lizhengSection">
                        </div>
                        <div class="row bg-gray mt-3">
                            <div style="font-size: 25px;" class="col-md-2 col-form-label text-left">
                                半表半里
                            </div>
                            <div class="col-md-1 text-right col-form-label">
                                <button type="button" class="btn btn-circle btn-info p-0-0" title="添加半表半里" onclick="appenItem('biaoli')"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-3 col-form-label">
                                <input type="text" class="form-control" id="biaoliInput" />
                            </div>
                            <div class="col-md-1 text-left col-form-label">
                                <button type="button" class="btn btn-circle btn-danger p-0-0" title="删除半表半里" onclick="removeItem('biaoli')"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div id="biaoliSection">
                        </div>

                        <div class="row bg-gray mt-3">
                            <div style="font-size: 25px;" class="col-md-2 col-form-label text-left">
                                脉症
                            </div>
                            <div class="col-md-1 text-right col-form-label">
                                <button type="button" class="btn btn-circle btn-info p-0-0" title="添加脉症" onclick="appenItem('maizheng')"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="col-md-3 col-form-label">
                                <input type="text" class="form-control" id="maizhengInput" />
                            </div>
                            <div class="col-md-1 text-left col-form-label">
                                <button type="button" class="btn btn-circle btn-danger p-0-0" title="删除脉症" onclick="removeItem('maizheng')"><i class="fas fa-minus"></i></button>
                            </div>

                        </div>
                        <div id="maizhengSection">
                        </div>

                        <div id="answerSection">
                        </div>
                        <input type="hidden" name="questions" id="questions"/>
                        <input type="hidden" name="question_id" id="question_id" value="{{$question->id}}"/>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-circle btn-success p-0-0"  title="添加问圈" onclick="openQAModal()"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <input type="hidden" name="biaozheng" id="biaozheng"/>
                        <input type="hidden" name="lizheng" id="lizheng"/>
                        <input type="hidden" name="biaoli" id="biaoli"/>
                        <input type="hidden" name="maizheng" id="maizheng"/>
                        <input type="hidden" name="medicines" id="medicines" />

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-success"><i class="ti-save"></i> 保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="QAModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    添加问圈
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group mt-3 row">
                        <label for="example-text-input" class="col-2 col-form-label text-right">质问</label>
                        <div class="col-10">
                            <input class="form-control" type="text" value="" name='question' id="question" placeholder="请输入质问&nbsp;&nbsp;">
                        </div>
                    </div>
                    <div id="answerItemSection">
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-circle btn-success p-0-0"  title="添加答项1" id="btnAnswer1" onclick="appendAnswerItem1()"><i class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-circle btn-danger p-0-0"  title="添加答项2" id="btnAnswer2" onclick="appendAnswerItem2()"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="addQueryItem()"><i class="ti-save"></i> 保存</button>
                </div>
            </div>
        </div>
    </div>

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
                            <select class="select2 form-control w-100" name='medicine' id="medicine" style="width:100%;">
                                <option value="0">--请选择药材--</option>
                                @foreach ($medicines as $medicine)
                                    <option value="{{$medicine->id}}" data-min="{{$medicine->min_weight}}" data-max="{{$medicine->max_weight}}" data-price="{{$medicine->price}}" data-unit="{{$medicine->unit}}">{{$medicine->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="addMedicineInModal(false)"><i class=" ti-plus"></i> 确认</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bootstrap-tagsinput{
            width:100%;
        }
        .bg-gray{
            background-color:#eee;
        }

    </style>

@endsection

@section('scripts')
    <!--Sky Icons JavaScript -->
    <script src="{{asset('static/plugin/skycons/skycons.js')}}"></script>
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('static/plugin/morrisjs/morris.min.js')}}"></script>
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/plugin/calendar/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{ asset('static/plugin/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{ asset('static/admin/qa.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/plugin/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('static/plugin/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}" ></script>
    <script src="{{ asset('static/plugin/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.extra.js') }}"></script>

    <script>
        var strQueries = "{{$question->questions}}";
        strQueries = strQueries.replace(/&quot;/g,'"');
        queries = JSON.parse(strQueries);
        drawAnswerSection(queries);
        var biaozhengList="{{rtrim(ltrim($biaozheng,'['),']')}}".replace(/&quot;/g,'').split(','),lizhengList = "{{rtrim(ltrim($lizheng,'['),']')}}".replace(/&quot;/g,'').split(','),
            biaoliList = "{{rtrim(ltrim($biaoli,'['),']')}}".replace(/&quot;/g,'').split(','), maizhengList = "{{rtrim(ltrim($maizheng,'['),']')}}".replace(/&quot;/g,'').split(',');

        var recipes = "{{json_encode($recipes)}}".replace(/&quot;/g,'"');
        recipes = recipes.substr(1,recipes.length-2);
    </script>

@endsection
