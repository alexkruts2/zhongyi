@extends('admin.layout.admin')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/datatables/media/css/jquery.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">病历列表</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">病历列表</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mt-3 row">
                        <div class="col-md-4 form-inline">
                            <label for="guahao" >挂号&nbsp;&nbsp; </label>
                            <input class="form-control w-75" type="text" value="{{$guahao}}" name='guahao' id="guahao" readonly>
                        </div>
                        <div class="col-md-4 form-inline">
                            <label for="patient_name" >姓名&nbsp;&nbsp; </label>
                            <input class="form-control w-75" type="text" value="{{$patient_name}}" name='patient_name' id="patient_name" readonly>
                        </div>
                        <div class="col-md-4 form-inline">
                            <label for="ID_Number" >身份证号&nbsp;&nbsp; </label>
                            <input class="form-control" type="text" value="{{$ID_Number}}" name='ID_Number' id="ID_Number" readonly>
                        </div>
                    </div>
                    <div class="mt-3 row">
                        <div class="col-md-4 form-inline">
                            <label for="guahao" >病名&nbsp;&nbsp; </label>
                            <input class="form-control w-75" type="text" value="{{$disease_name}}" name='disease_name' id="disease_name" readonly>
                        </div>
                        <div class="col-md-4 form-inline">
                            <label for="recipe_name" >药方&nbsp;&nbsp; </label>
                            <input class="form-control w-75" type="text" value="{{$recipe_name}}" name='recipe_name' id="recipe_name" readonly>
                        </div>
                        <div class="col-md-4 form-inline">
                            <label for="guahao" >状态&nbsp;&nbsp; </label>
                            <input class="form-control w-75" type="text" value="{{$state}}" name='disease_name' id="disease_name" readonly>
                        </div>
                    </div>
                    <div class="mt-3 row">
                        <table class="table table-bordered text-center">
                            <thead>
                            <tr>
                                <th>药材</th>
                                <th>数量</th>
                                <th>价格</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($recipes as $recipe)
                                    <tr>
                                        <td>
                                            {{$recipe->medicine}}
                                        </td>
                                        <td>
                                            {{$recipe->weight}}
                                        </td>
                                        <td>
                                            {{$recipe->price}}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>总价:{{$price}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-center m-t-30">
                            <button type="button" class="btn btn-success" onclick="payTreatment('{{$guahao}}')" {{$state==config('constant.treat_state.close')?'disabled':''}}><i class="fab fa-amazon-pay"></i> 付款</button>
                            <button type="button" class="btn btn-danger" onclick="cancelTreatment()"><i class="ti-close"></i> 取消</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="random">

    </div>

@endsection

@section('scripts')
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{ asset('static/admin/yield.js') }}"></script>

@endsection
