@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">药材管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">药材管理</li>
            </ol>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-info" onclick="uploadMedicineExcel()"><i class=" fas fa-upload"></i></button>
        </div>
    </div>
    <form action="/doctor/medicine/uploadMedicine" method="POST" enctype="multipart/form-data" style="position:absolute;top:1000px;" id="excel-form">
        <input name="file_1" type="file"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbl_medicine" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">药材名称</th>
                                <th class="text-center">用处</th>
                                <th class="text-center">数量（g）</th>
                                <th class="text-center">价格（￥/10g）</th>
                                <th class="text-center">最少使用量（g)</th>
                                <th class="text-center">最多使用量(g)</th>
                                <th class="text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="medicine-form" novalidate method="post">

                <div class="modal-header">
                    修改药材
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                        <input type="hidden" id="medicine_id" name="medicine_id"/>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-4 col-form-label text-right">药材名称</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='medicine_name' id="medicine_name" value=""/>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-4 col-form-label text-right">用处</label>
                            <div class="col-8">
                                <textarea class="form-control" rows="4" cols="50"  name='usage' id="usage" >它的补气功效是大家都比较清楚的，参有着极大的药用价值。</textarea>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-4 col-form-label text-right">数量（g）</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='weight' id="weight" value=""/>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-4 col-form-label text-right">价格(￥/10g)</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='price' id="price" value=""/>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-4 col-form-label text-right">最少使用量（g)</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='min_weight' id="min_weight" value=""/>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-4 col-form-label text-right">最多使用量(g)</label>
                            <div class="col-8">
                                <input class="form-control" type="text"  name='max_weight' id="max_weight" value=""/>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><i class="ti-save"></i> 保存</button>
                </div>
                </form>

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
    <script src="{{ asset('static/plugin/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/admin/medicine.js') }}"></script>

@endsection
