@extends('admin.layout.admin')

@section('styles')
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    <div class="row page-titles">
        <div class="col-md-12">
            <h4 class="text-white">设置</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                <li class="breadcrumb-item active">更改密码</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="password-form" novalidate method="post">
                        @csrf

                        @foreach ($errors->all() as $error)
                            <p class="text-danger">{{ $error }}</p>
                        @endforeach

                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">原密码</label>
                            <div class="col-10">
                                <input class="form-control" type="password" name='original_password' id="original_password" >
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">新密码</label>
                            <div class="col-10">
                                <input class="form-control" type="password" name='new_password' id="new_password" >
                            </div>
                        </div>
                        <div class="form-group mt-3 row">
                            <label for="example-text-input" class="col-2 col-form-label text-right">重复密码</label>
                            <div class="col-10">
                                <input class="form-control" type="password" name='repeat_password' id="repeat_password" >
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 text-center m-t-30">
                                <button type="submit" class="btn btn-success"><i class="ti-save"></i> 更新</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script src="{{ asset('static/plugin/sweetalert/jquery.sweet-alert.custom.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

    <script src="{{ asset('static/admin/setting.js') }}"></script>

@endsection
