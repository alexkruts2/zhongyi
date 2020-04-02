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
            <h4 class="text-white">药方管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">新药方</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="recipe-form" novalidate method="post" data-parsley-validate="">
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">科室</label>
                            <div class="col-10">
                                <select class="form-control" name='department' id="department_" data-parsley-required>
                                    <option value="">--请选择科室--</option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="prescription_name" class="col-2 col-form-label text-right">药方名</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="" name="prescription_name" id="prescription_name" data-parsley-required>
                            </div>
                        </div>

                        <div id="diseaseSection">
                            <div class="row">
                                <label class="col-2 col-form-label text-right">
                                    &nbsp;病症<span id="label_1">1</span></label>
                                <div class="col-10">
                                    <input class="form-control" type="text" value="" name="disease[]" id="disease_1" data-parsley-required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-circle btn-success p-0-0" data-toggle="tooltip" title="添加病症" onclick="addDisease()"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">其他病症</label>
                            <div class="col-10">
                                <textarea class="form-control" type="text" value="" name='other_condition' id="other_recipe" placeholder="按摩，抓药" ></textarea>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">煎服法</label>
                            <div class="col-10">
                                <textarea class="form-control" type="text" value="" name='eating_method' id="eating_method" placeholder="上五昧，㕮咀三味" ></textarea>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">禁忌</label>
                            <div class="col-10">
                                <textarea class="form-control" type="text" value="" name='ban' id="ban" placeholder="脉紧、无汗"></textarea>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-circle btn-warning p-0-0"  data-toggle="modal" data-target="#myModal" title="添加药材" ><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div id="medicineSection" class="mt-3">
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center m-t-30">
                                <button type="submit" class="btn btn-success"><i class="ti-save"></i> 保存</button>
                            </div>
                        </div>
                    </form>
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
                            <select class="select2 form-control" name='medicine' id="medicine">
                                <option value="0">--请选择药材--</option>
                                @foreach ($medicines as $medicine)
                                    <option value="{{$medicine->id}}" data-min="{{$medicine->min_weight}}" data-unit="{{$medicine->unit}}" data-max="{{$medicine->max_weight}}" data-price="{{$medicine->price}}">{{$medicine->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="addMedicine()"><i class=" ti-plus"></i> 确认</button>
                </div>
            </div>
        </div>
    </div>
<style>
    .select2{
        width:100% !important;
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
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{ asset('static/plugin/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{ asset('static/plugin/select2/dist/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('static/admin/recipe.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/plugin/clockpicker/dist/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.extra.js') }}"></script>

@endsection
