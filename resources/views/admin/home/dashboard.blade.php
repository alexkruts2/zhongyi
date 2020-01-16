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
            <h4 class="text-white">仪表板</h4>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">首页</a></li>
                <li class="breadcrumb-item active">仪表板</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <!-- Column -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Visit</small>
                            <h2><i class="ti-arrow-up text-success"></i> 1064</h2>
                            <div id="sparklinedash"></div>
                        </div>
                        <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Total Page Views</small>
                            <h2><i class="ti-arrow-up text-purple"></i> 5064</h2>
                            <div id="sparklinedash2"></div>
                        </div>
                        <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Unique Visitor</small>
                            <h2><i class="ti-arrow-up text-info"></i> 664</h2>
                            <div id="sparklinedash3"></div>
                        </div>
                        <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Bounce Rate</small>
                            <h2><i class="ti-arrow-down text-danger"></i> 50%</h2>
                            <div id="sparklinedash4"></div>
                        </div>
                    </div>
                    <ul class="list-inline font-12 text-center">
                        <li><i class="fa fa-circle text-cyan"></i> Site A</li>
                        <li><i class="fa fa-circle text-primary"></i> Site B</li>
                        <li><i class="fa fa-circle text-purple"></i> Site C</li>
                    </ul>
                    <div id="morris-area-chart" style="height: 340px;"></div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <!-- ============================================================== -->
    <!-- Campaign -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <!-- column -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">NEW CLIENTS</h5>
                            <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                                <span class="display-5 text-info"><i class="icon-people"></i></span>
                                <a href="javscript:void(0)" class="link display-5 ml-auto">23</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- column -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">NEW PROJECTS</h5>
                            <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                                <span class="display-5 text-purple"><i class="icon-folder"></i></span>
                                <a href="javscript:void(0)" class="link display-5 ml-auto">169</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- column -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">OPEN PROJECTS</h5>
                            <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                                <span class="display-5 text-primary"><i class="icon-folder-alt"></i></span>
                                <a href="javscript:void(0)" class="link display-5 ml-auto">311</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- column -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">NEW INVOICES</h5>
                            <div class="d-flex m-t-30 m-b-20 no-block align-items-center">
                                <span class="display-5 text-success"><i class="icon-wallet"></i></span>
                                <a href="javscript:void(0)" class="link display-5 ml-auto">117</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- column -->
            </div>
        </div>
        <div class="col-lg-6">
            <div class="news-slide m-b-15">
                <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                    <!-- Carousel items -->
                    <div class="carousel-inner">
                        <div class="active carousel-item">
                            <div class="overlaybg"><img src="../assets/images/news/slide1.jpg" class="img-fluid" /></div>
                            <div class="news-content carousel-caption"><span class="label label-danger label-rounded">Primary</span>
                                <h4>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</h4> <a href="javascript:void(0)">Read More</a></div>
                        </div>
                        <div class="carousel-item">
                            <div class="overlaybg"><img src="../assets/images/news/slide1.jpg" /></div>
                            <div class="news-content carousel-caption"><span class="label label-primary label-rounded">Primary</span>
                                <h4>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</h4> <a href="javascript:void(0)">Read More</a></div>
                        </div>
                        <div class="carousel-item">
                            <div class="overlaybg"><img src="../assets/images/news/slide1.jpg" /></div>
                            <div class="news-content carousel-caption"><span class="label label-success label-rounded">Primary</span>
                                <h4>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</h4> <a href="javascript:void(0)">Read More</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Campaign -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End Info box -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Today's Schedule and sales overview -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex m-b-40 align-items-center no-block">
                        <h5 class="card-title ">SALES DIFFERENCE</h5>
                        <div class="ml-auto">
                            <ul class="list-inline font-12">
                                <li><i class="fa fa-circle text-cyan"></i> SITE A</li>
                                <li><i class="fa fa-circle text-primary"></i> SITE B</li>
                            </ul>
                        </div>
                    </div>
                    <div id="morris-area-chart2" style="height: 340px;"></div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-lg-4 col-md-12">
            <div class="row">
                <!-- Column -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">SALES DIFFERENCE</h5>
                            <div class="row">
                                <div class="col-6  m-t-30">
                                    <h1 class="text-primary">$647</h1>
                                    <p class="text-muted">APRIL 2017</p>
                                    <b>(150 Sales)</b> </div>
                                <div class="col-6">
                                    <div id="sales1" class="text-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">VISIT STATASTICS</h5>
                            <div class="row">
                                <div class="col-6  m-t-30">
                                    <h1 class="text-info">$347</h1>
                                    <p class="light_op_text">APRIL 2017</p>
                                    <b class="">(150 Sales)</b> </div>
                                <div class="col-6">
                                    <div id="sales2" class="text-right"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
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
    <script src="{{asset('static/admin/dashboard.js')}}"></script>
    <script src="{{ asset('static/plugin/sweetalert2/sweetalert2.js') }}"></script>

@endsection
