<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('static/images/hc-logo.png')}}">
    <title>邦海外 · 分销助手</title>
    <!-- This page CSS -->
    <link href="{{asset('static/plugin/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/style.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/mobile.css')}}" rel="stylesheet">

@yield('styles')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="skin-blue fixed-layout rmv-right-panel">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">帮海外</p>
    </div>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
        @yield('content')
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    @include('mobile.layout.footer')
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{asset('static/plugin/jquery/jquery-3.2.1.min.js')}}"></script>
<!-- Bootstrap popper Core JavaScript -->
<script src="{{asset('static/plugin/popper/popper.min.js')}}"></script>
<script src="{{asset('static/plugin/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('static/plugin/jquery-loading-overlay/dist/loadingoverlay.min.js') }}"></script>
<script src="{{ asset('static/plugin/toast-master/js/jquery.toast.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('static/js/perfect-scrollbar.jquery.min.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('static/js/waves.js')}}"></script>
<!--Menu sidebar -->
{{--<script src="{{asset('static/js/sidebarmenu.js')}}"></script>--}}
<!--Custom JavaScript -->
<script src="{{asset('static/js/custom.js')}}"></script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
@yield('scripts')
</body>

</html>