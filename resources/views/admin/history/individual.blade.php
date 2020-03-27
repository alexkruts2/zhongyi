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
                <li class="breadcrumb-item active">个人病历</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mt-3 row">
                        <label for="example-text-input" class="col-2 col-form-label text-right">病历号</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="{{$guahao}}" readonly>
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label text-right">身份证号</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="{{$ID_Number}}" readonly>
                        </div>
                    </div>
                    <div class="form-group mt-3 row">
                        <label for="example-text-input" class="col-2 col-form-label text-right">姓名</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="{{$patient_name}}" readonly>
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label text-right">性别</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="{{$sex}}" readonly>
                        </div>

                    </div>

                    <div class="row mt-3 table-responsive">
                        <table id="tbl_history_individual" class="display nowrap table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">看诊时间</th>
                                <th class="text-center">药方</th>
                                <th class="text-center">医生</th>
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

<script>
    treat_id = "{{$treatment->id}}";
</script>

@endsection

@section('scripts')
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/datatables/datatables.min.js')}}"></script>
    <script src="{{ asset('static/admin/history.js') }}"></script>

@endsection
