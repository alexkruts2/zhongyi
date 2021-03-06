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
                <li class="breadcrumb-item active">添加挂号</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="patient-form" novalidate method="post" style="display: none;" data-parsley-validate="">
                        <div class="row">
                            <div class="col-5">
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">科室</label>
                                    <div class="col-10">
                                        <select class="form-control" name='department' id="department" data-parsley-required>
                                            <option value="">--全部科室--</option>
                                            @foreach ($departments as $department)
                                                <option value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">医生</label>
                                    <div class="col-10">
                                        <select class="form-control" name="doctor_id" id="doctor_id" data-parsley-required>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mt-3 row">
                                    <label class="col-2 col-form-label text-right">从</label>
                                    <div class="input-group clockpicker col-4">
                                        <input type="text" class="form-control" value="" name='from' id="from" readonly="readonly">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                    <label class="col-2 col-form-label text-right">到</label>
                                    <div class="input-group clockpicker col-4">
                                        <input type="text" class="form-control" value="" name='to' id="to"  readonly="readonly">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">性别</label>
                                    <div class="col-10">
                                        <select class="form-control" name='sex' id="sex" data-parsley-required>
                                            <option value="">请选择</option>
                                            <option value="男">男</option>
                                            <option value="女">女</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">姓名</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" name='name' id="name" placeholder="姓名" data-parsley-required>
                                    </div>
                                </div>

                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">身份证号码</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" name='ID_number' id="ID_number"  readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">手机号码</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text" value="" name='phone_number' id="phone_number" placeholder="请输入手机号码" data-parsley-required data-parsley-maxlength="11" data-parsley-minlength="11">
                                    </div>
                                </div>
                                <div class="form-group mt-3 row">
                                    <label for="example-text-input" class="col-2 col-form-label text-right">挂号</label>
                                    <div class="col-10">
                                        <input class="form-control" type="text"  name='guahaoID' id="guahaoID" value="{{ $guahaoID }}" readonly="readonly"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-7">
                                <video onclick="snapshot(this);" width=600 height=400 id="video" controls autoplay></video>
                                    <canvas  id="myCanvas" width="600" height="400" style='display:none'></canvas>
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-success" onclick="snapshot();"><i class="ti-camera"></i> 快照</button>
                                </div>
                                <input class="form-control" type="hidden" name="photo" id="photo"/>
                                <div class="mt-3" id="snapshot"></div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-info"><i class="ti-save"></i> 保存</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    输入身份证号
                    <a href="/doctor/accept/guahao/view" type="button" class="close" >&times;</a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-10">
                            <input class="form-control" id="id_number" name="id_number" type="text" required="" placeholder="请输入身份证号">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="inputIDNumber()"><i class="ti-pencil"></i> 确认</button>
                </div>
            </div>
        </div>
    </div>
    <div id="payModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="font-weight-bold">请扫描。</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3 text-right col-form-label">付款方式</div>
                        <div class="col-8">
                            <select class="form-control" id="pay_type">
                                <option value="">－－－请选择付款方式－－－</option>
                                <option value="支付宝">支付宝</option>
                                <option value="微信支付">微信支付</option>
                                <option value="现金支付">现金支付</option>
                                <option value="POST机">POST机</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-5">
                            <h4 class="card-title">微信二维码：</h4>
                            <img src="{{$weixin_url}}" alt="微信二维码" class="w-100">
                        </div>
                        <div class="col-2"></div>
                        <div class="col-5">
                            <h4 class="card-title">支付宝二维码：</h4>
                            <img src="{{$zhifubao_url}}" alt="支付宝二维码" class="w-100">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="payAccept()"><i class="fab fa-amazon-pay"></i> 付款</button>
                    <button type="button" class="btn btn-danger" onclick="cancelAccept()"><i class="ti-close"></i> 取消</button>
                </div>
            </div>
        </div>
    </div>


    <div  id="printJS-form">
        <h1 style="text-align: center">修和中医问诊</h1>
        <h3  style="text-align: center">挂号单</h3>
        <table style="border: 1px solid #333; cellpadding:3px; width:100%;">
            <tbody>
                <tr>
                    <td style="font-size:9px;padding:5px;">患者编号</td>
                    <td style="font-size:9px;padding:5px;" id="print_guahao"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                </tr>
                <tr>
                    <td style="font-size:9px;padding:5px;">姓名</td>
                    <td style="font-size:9px;padding:5px;" id="print_patient_name"></td>
                    <td style="font-size:9px;padding:5px;">性别</td>
                    <td style="font-size:9px;padding:5px;" id="print_sex"></td>
                    <td style="font-size:9px;padding:5px;">年龄</td>
                    <td style="font-size:9px;padding:5px;" id="print_age"></td>
                </tr>
                <tr>
                    <td style="font-size:9px;padding:5px;">就诊时间</td>
                    <td style="font-size:9px;padding:5px;" id="print_time">２０２０／０５／０４</td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                </tr>
                <tr>
                    <td style="font-size:9px;padding:5px;">就诊科室</td>
                    <td style="font-size:9px;padding:5px;" id="print_department"></td>
                    <td style="font-size:9px;padding:5px;">就诊医生</td>
                    <td style="font-size:9px;padding:5px;" id="print_doctor"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                </tr>
                <tr>
                    <td style="font-size:9px;padding:5px;">问诊地址</td>
                    <td style="font-size:9px;padding:5px;" id="print_addres"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                </tr>
                <tr>
                    <td style="font-size:9px;padding:5px;">联系电话</td>
                    <td style="font-size:9px;padding:5px;" id="print_telephone"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                    <td style="font-size:9px;padding:5px;"></td>
                </tr>
            </tbody>
        </table>
        <h3  style="text-align: center;margin-top:10px;">挂号金额: <span id="print_price">２３</span>元</h3>
        <h3>&nbsp;</h3>
        <h3  style="text-align: center">前面候诊人数：<span id="man_number"></span></h3>
        <h3 style="text-align: center">请您在候诊等持候诊！</h3>
        <h3 style="text-align: center">此单就诊，单日有效，请妥善保存</h3>
    </div>
    <style>
        #printJS-form {
            width: 120mm;
            min-height: 140mm;
            padding: 20mm;
            margin: 10mm auto;
            border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        @media print {
            @page {
                size: 120mm 140mm; /* landscape */
                /* you can also specify margins here: */
                margin: 25mm;
                margin-right: 45mm; /* for compatibility with both A4 and Letter */
            }
            html, body {
                width: 120mm;
                height: 140mm;
            }
            #printJS-form {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
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
