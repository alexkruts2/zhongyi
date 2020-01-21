@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">药方管理</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">药方管理</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="recipe-form" novalidate method="post" data-parsley-validate="">
                        <input type="hidden" name="recipe_id" id="recipe_id" value="{{$recipe->id}}"/>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">科室</label>
                            <div class="col-10">
                                <select class="form-control" name='department' id="department" data-parsley-required>
                                    <option value="0">--请选择科室--</option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->id}}"  {{$department->id==$recipe->recipe_part_id?'selected':''}}>{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="disease_name" class="col-2 col-form-label text-right">病名</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="{{$recipe->disease_name}}" name="disease_name" id="disease_name" data-parsley-required>
                            </div>
                        </div>
                        <div id="diseaseSection">
                            @foreach ($conditions as $key=>$condition)
                                <div class="row">
                                    <label class="col-2 col-form-label text-right"><button type="button" class="btn btn-default" title="删除" data-index="{{$key+1}}" onclick="removeDisease(this);"><i class="fas fa-times"></i> </button>&nbsp;病症<span id="label_{{$key+1}}">{{$key+1}}</span></label>
                                    <div class="col-10"><input class="form-control" type="text" value="{{$condition}}" name="disease[]" id="disease_{{$key+1}}"/> </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-circle btn-success p-0-0" data-toggle="tooltip" title="添加病症" onclick="addDisease()"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">其他病症</label>
                            <div class="col-10">
                                <textarea class="form-control" type="text" value="" name='other_condition' id="other_recipe" placeholder="按摩，抓药" data-parsley-required>{{$recipe->other_condition}}</textarea>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-circle btn-warning p-0-0"  data-toggle="modal" data-target="#myModal" title="添加药材" ><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div id="medicineSection" class="mt-3">

                            @foreach ($recipe_medicines as $key=>$recipe_medicine)
                                <div class="row">
                                    <label class="col-2 col-form-label text-right">
                                        <button type="button" class="btn btn-default" title="删除" data-index="{{$recipe_medicine->medicine_id}}" onclick="removeMedicine(this)"><i class="fas fa-times"></i> </button>&nbsp;{{$recipe_medicine->medicine}}
                                        <input type="hidden" name="medicine_name[]" value="{{$recipe_medicine->medicine}}" />
                                    </label>
                                    <div class="col-3">
                                        <input class="form-control" type="text" value="{{$recipe_medicine->min_weight}}" name="min_weight[]" id="min_weight_{{$recipe_medicine->medicine_id}}"/>
                                    </div>
                                    <span style="paddint-top:8px">~</span>
                                    <div class="col-3">
                                        <input class="form-control" type="text" value="{{$recipe_medicine->max_weight}}" name="max_weight[]" id="max_weight_{{$recipe_medicine->medicine_id}}"/>
                                    </div>
                                    <div class="col-3 text-center">
                                        <label id="price_{{$recipe_medicine->medicine_id}}" style="line-height:38px">{{$recipe_medicine->price}} 元/10g</label>
                                        <input type="hidden" name="price[]" value="{{$recipe_medicine->price}}"/>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="prescription_name" class="col-2 col-form-label text-right">药方名</label>
                            <div class="col-10">
                                <input class="form-control" type="text" value="{{$recipe->prescription_name}}" name="prescription_name" id="prescription_name" data-parsley-required>
                            </div>
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
                            <select class="form-control" name='medicine' id="medicine">
                                <option value="0">--请选择药材--</option>
                                @foreach ($medicines as $medicine)
                                    <option value="{{$medicine->id}}" data-min="{{$medicine->min_weight}}" data-max="{{$medicine->max_weight}}" data-price="{{$medicine->price}}">{{$medicine->name}}</option>
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
    <script>
        currentDiseaseNumber = {{count($conditions)}};
        document.addEventListener("DOMContentLoaded", function(){
            @foreach ($recipe_medicines as $key=>$recipe_medicine)
                $('#medicine option[value="{{$recipe_medicine->medicine_id}}"]').attr("disabled",true);
            @endforeach
        })
    </script>
@endsection
@section('scripts')
    <!--Sky Icons JavaScript -->
    <script src="{{asset('static/plugin/skycons/skycons.js')}}"></script>
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('static/plugin/morrisjs/morris.min.js')}}"></script>
    <script src="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/prism/prism.js')}}"></script>
    <script src="{{ asset('static/plugin/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
    <script src="{{ asset('static/plugin/sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{ asset('static/admin/recipe.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.js') }}"></script>
    <script src="{{ asset('static/plugin/parsley/zh_cn.extra.js') }}"></script>

@endsection
