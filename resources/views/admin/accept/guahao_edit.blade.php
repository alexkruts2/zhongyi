@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
    <link href="{{ asset('static/plugin/clockpicker/dist/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/plugin/print/print.min.css')}}">

@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">挂号管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">修改挂号</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="patient-edit-form" method="post" data-parsley-validate="">
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">科室</label>
                                    <div class="col-8">
                                        <select class="form-control" name='department' id="department" {{$editable?'':'disabled'}} data-parsley-required>
                                            <option value="">--全部科室--</option>
                                            @foreach ($departments as $department)
                                                <option value="{{$department->id}}" {{$department_id==$department->id?'selected':''}}>{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">医生</label>
                                    <div class="col-8">
                                        <select class="form-control" name="doctor_id" id="doctor_id" {{$editable?'':'disabled'}} data-parsley-required>
                                            @foreach ($doctors as $doctor)
                                                <option value="{{$doctor->id}}" data-from="{{$doctor->from}}" data-to="{{$doctor->to}}"{{$treatment->doctor_id==$doctor->id?'selected':''}}>{{$doctor->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mt-3 row">
                                    <label class="col-2 col-form-label text-right">从</label>
                                    <div class="input-group clockpicker col-3">
                                        <input type="text" class="form-control" value="{{$from}}" name='from' id="from" readonly="readonly">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                    <label class="col-2 col-form-label text-right">到</label>
                                    <div class="input-group clockpicker col-3">
                                        <input type="text" class="form-control" value="{{$to}}" name='to' id="to"  readonly="readonly">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">性别</label>
                                    <div class="col-8">
                                        <select class="form-control" name='sex' id="sex" {{$editable?'':'disabled'}} data-parsley-required>
                                            <option value="">请选择</option>
                                            <option value="男" {{$sex=='男'?'selected':''}}>男</option>
                                            <option value="女" {{$sex=='女'?'selected':''}}>女</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">姓名</label>
                                    <div class="col-8">
                                        <input class="form-control" type="text" value="{{$patient_name}}" name='name' id="name" placeholder="姓名" readonly="readonly">
                                    </div>
                                </div>

                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">身份证号码</label>
                                    <div class="col-8">
                                        <input class="form-control" type="text" value="{{$ID_Number}}" name='ID_number' id="ID_number"  readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">手机号码</label>
                                    <div class="col-8">
                                        <input class="form-control" type="text" value="{{$phone_number}}" name='phone_number' id="phone_number" placeholder="请输入手机号码" {{$editable?'':'disabled'}} data-parsley-required  data-parsley-maxlength="11" data-parsley-minlength="11">
                                    </div>
                                </div>
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">挂号</label>
                                    <div class="col-8">
                                        <input class="form-control" type="text"  name='guahaoID' id="guahaoID" value="{{ $treatment->guahao }}" readonly="readonly"/>
                                    </div>
                                </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-info" {{$editable?'':'disabled'}}><i class="ti-save"></i> 保存</button>
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
    <!-- Chart JS -->
    {{--    <script src="{{asset('static/js/dashboard4.js')}}"></script>--}}
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('static/plugin/calendar/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{ asset('static/plugin/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{ asset('static/admin/accept.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/plugin/clockpicker/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('static/plugin/print/print.min.js') }}"></script>
    <script src="{{ asset('static/plugin/barcode/JsBarcode.all.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.extra.js') }}"></script>

@endsection
