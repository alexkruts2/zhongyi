@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/clockpicker/dist/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">医生管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">医生管理</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="doctor-form" method="post" data-parsley-validate="">
                        <input type="hidden" id="id" name="id" value="{{$doctor[0]->id}}"/>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">医院名称</label>
                            <div class="col-10">
                                <select class="form-control" name='hospital_name' id="hospital_name" data-parsley-required>
                                    <option value="">--请选择医院--</option>
                                    @foreach ($hospitals as $hospital)
                                        <option value="{{$hospital->id}}" {{$hospital->id==$doctor[0]->hospital_id?'selected':''}}>{{$hospital->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">姓名</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="{{$doctor[0]->name}}" name='name' id="name" placeholder="姓名" data-parsley-trigger="change" required="">
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">手机号</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="{{$doctor[0]->phone}}" name='phone' id="phone" placeholder="手机号" data-parsley-trigger="change"  data-parsley-maxlength="11" data-parsley-minlength="11" data-parsley-required>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">科室</label>
                            <div class="col-10">
                                <select class="form-control" name='department' id="department" data-parsley-required>
                                    <option>--请选择科室--</option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->id}}" @if($doctor[0]->department_id==$department->id) selected @endif >{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">简介</label>
                            <div class="col-10">
                                <textarea class="form-control" type="text" value="{{$doctor[0]->introduction}}" name='introduction' id="introduction" placeholder="按摩，抓药" data-parsley-required>{{$doctor[0]->introduction}}</textarea>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label class="col-2 col-form-label text-right">从</label>
                            <div class="input-group clockpicker col-10">
                                <input type="text" class="form-control" value="{{$doctor[0]->from}}" name='from' id="from" data-parsley-required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label class="col-2 col-form-label text-right">到</label>
                            <div class="input-group clockpicker col-10">
                                <input type="text" class="form-control" value="{{$doctor[0]->to}}" name='to' id="to" data-parsley-required>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">出诊地点</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="{{$doctor[0]->visiting_place}}" name='visiting_place' id="visiting_place" placeholder="出诊地点" data-parsley-required/>
                            </div>
                        </div>

                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">分成比例</label>
                            <div class="col-10">
                                <div class="slidecontainer">
                                    <input type="range" min="0" max="100" value="{{$doctor[0]->doctor_ratio}}" class="slider" id="myRange">
                                    <p>医生: <span id="doctor_value"></span>&nbsp;医院:<span id="hospital_value"></span></p>
                                </div>
                                <input type="hidden" value="" name='doctor_ratio' id="doctor_ratio" value="{{$doctor[0]->doctor_ratio}}"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success"><i class="ti-save"></i> 保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!--Sky Icons JavaScript -->
    <script src="{{asset('static/plugin/skycons/skycons.js')}}"></script>
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('static/plugin/morrisjs/morris.min.js')}}"></script>
    <script src="{{asset('static/plugin/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <!-- Chart JS -->
    {{--    <script src="{{asset('static/js/dashboard4.js')}}"></script>--}}
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/plugin/calendar/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{ asset('static/plugin/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{ asset('static/plugin/popper/popper.min.js')}}"></script>
    <script src="{{ asset('static/admin/doctor.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/plugin/clockpicker/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.extra.js') }}"></script>

@endsection
