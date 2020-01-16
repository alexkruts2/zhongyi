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
                    <form id="question-form" novalidate method="post">
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">编号</label>
                            <div class="col-10">
                                <input class="form-control" type="text"  name='number' id="number" placeholder="请输入标题" value="{{$question->number}}" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">标题</label>
                            <div class="col-10">
                                <input class="form-control" type="text"  name='title' id="title" placeholder="请输入标题" value="{{$question->title}}">
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">科室</label>
                            <div class="col-4">
                                <select class="form-control" name='department' id="department">
                                    <option>--请选择科室--</option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->id}}" {{$department->id==$department_id?'selected':''}}>{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="example-text-input" class="col-2 col-form-label text-right">医生</label>
                            <div class="col-4">
                                <select class="form-control" name="doctor_id" id="doctor_id">
                                    @foreach ($doctors as $doctor)
                                        <option value="{{$doctor->id}}" {{$doctor->id==$question->doctor_id?'selected':''}}>{{$doctor->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">药方</label>
                            <div class="col-10">
                                <select class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple" data-placeholder="Choose" name="recipes[]" value='{{$question->recipes}}'>
                                    @foreach(\App\recipe::all() as $recipe)
                                        <option value="{{$recipe->id}}" {{in_array($recipe->id,json_decode($question->recipes))?'selected':''}}>{{$recipe->prescription_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">病名</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="{{$question->disease_name}}" data-role="tagsinput" name='disease_name' id="disease_name" placeholder="请输入病名">
                            </div>
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
    <style>
        .bootstrap-tagsinput{
            width:100%;
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

    <script>
        var strQueries = "{{$question->questions}}";
        strQueries = strQueries.replace(/&quot;/g,'"');
        queries = JSON.parse(strQueries);
        drawAnswerSection(queries);
    </script>

@endsection
