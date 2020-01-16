@extends('mobile.layout.mobile')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Campaign -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="news-slide m-b-15">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                <div class="active carousel-item">
                    <img class="d-block w-100" src="../static/images/app-logo/london.jpg"/>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 m-b-10">
            <h4 class="text-center font-weight-bold letter-spacing-2 m-b-0">澳大利亚132商业天才类永居签证</h4>
        </div>
        <div class="col-12 text-center font-12">一步到位拿永居。移民政策稳定、投资风险低。 无英语要求，投资100万澳元起。</div>
        <div class="col-sm-6 text-center tags-wrapper m-t-20">
            <button type="button" class="btn mybtn waves-effect waves-light btn-warning btn-small">无移民监</button>
            <button type="button" class="btn mybtn waves-effect waves-light btn-warning btn-small">一步到位</button>
        </div>
    </div>
    <div class="row tab-container p-l-30 p-r-30 m-t-30"> </div>
    <div  class="tab-content br-n pn">
        <ul class="nav m-b-30" id="tab-container">
            <li class="nav-item tab-wrapper">
                <div class="p-t-10 tab-shadow active">
                    <div><i class="ti-info-alt"></i> </div>
                    <a href="#property-info" class="nav-link p-0" data-toggle="tab" aria-expanded="true">项目信息</a>
                </div>
            </li>
            <li class="nav-item tab-wrapper">
                <div class="p-t-10 tab-shadow">
                    <div><i class="mdi mdi-message"></i> </div>
                    <a href="#faq" class="nav-link p-0" data-toggle="tab" aria-expanded="true">FAQ</a>
                </div>
            </li>
            <li class="nav-item tab-wrapper">
                <div class="p-t-10 tab-shadow">
                    <div><i class="fas fa-angle-double-right"></i></div>
                    <a href="#shenqingbuzhou" class="nav-link p-0" data-toggle="tab" aria-expanded="true">申请步骤</a>
                </div>
            </li>
            <li class="nav-item tab-wrapper">
                <div class="p-t-10 tab-shadow">
                    <div><i class="fas fa-dollar-sign"></i> </div>
                    <a href="#cost" class="nav-link p-0" data-toggle="tab" aria-expanded="true">费用详情</a>
                </div>
            </li>
        </ul>
        <div class="p-t-10 m-t-30 middle-space "></div>
        <div id="property-info" class="tab-pane row m-t-30 active">
            <div class="col-12 ">
                <h2 class="text-center project-description-title font-weight-bold">项目简介</h2>
                <div class="text-center project-description m-l-20 m-r-20 m-t-30">
                    <p>
                        澳大利亚是一个稳定、民主的社会，拥有技术精湛的劳动力和具有竞争力的强大经济。 澳大利亚的多元文化社会包括土著民族和来自各界各地约200个国家的移民。
                    </p>
                    <p>
                        澳洲132商业移民项目又称“商业天才移民项目”，是直接一步到位获得永居，比较适合大中型企业主，通常要求投资人在澳洲运营一个生意。
                    </p>
                </div>
            </div>
            <div class="p-t-10 m-t-30 middle-space "></div>
            <div class="col-12 m-t-30">
                <h3 class="text-center project-description-title font-weight-bold">项目优势</h3>
                <div class="text-center project-description m-l-20 m-r-20 m-t-30">
                    <div role="tablist" class="minimal-faq m-t-30" aria-multiselectable="true">
                        <div class="card m-b-0 questin-title">
                            <div class="question-header p-l-10 p-t-20 p-b-10" role="tab" id="headingOne11">
                                <h5 class="mb-0 text-left">
                                    <a class="link collapsed" data-toggle="collapse" data-parent="#accordion2" href="#yimin1" aria-expanded="false" aria-controls="collapseOne11">
                                        <span class="step">1</span>
                                        享受当地居民福利待遇
                                    </a>
                                </h5>
                            </div>
                            <div id="yimin1" class="collapse p-l-10 text-left" role="tabpanel" aria-labelledby="headingOne11" style="">
                                <div class="card-body">
                                    <p>可直接享受当地居民福利待遇（如买二手房不受限制，小孩读大学可以享受当地学生待遇）
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-t-10 m-t-30 middle-space "></div>
            <div class="col-12 m-t-30">
                <h3 class="text-center project-description-title font-weight-bold">申请条件</h3>
                <div class="text-center project-description m-l-20 m-r-20 m-t-30">
                    <div id="shenqing" role="tablist" class="minimal-faq m-t-30" aria-multiselectable="true">
                        <div class="card m-b-0 questin-title">
                            <div class="question-header p-l-10 p-t-20 p-b-10" role="tab" id="headingOne11">
                                <h5 class="mb-0 text-left">
                                    <a class="link collapsed" data-toggle="collapse" data-parent="#shenqing" href="#shenqing1" aria-expanded="false" aria-controls="collapseOne11">
                                        <span class="step">1</span>
                                        取得“意向申请”邀请
                                    </a>
                                </h5>
                            </div>
                            <div id="shenqing1" class="collapse p-l-10 text-left" role="tabpanel" aria-labelledby="headingOne11" style="">
                                <div class="card-body">
                                    <p>可直接享受当地居民福利待遇（如买二手房不受限制，小孩读大学可以享受当地学生待遇）
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card m-b-0 questin-title">
                            <div class="question-header p-l-10 p-t-20 p-b-10" role="tab" id="headingOne11">
                                <h5 class="mb-0 text-left">
                                    <a class="link collapsed" data-toggle="collapse" data-parent="#shenqing" href="#shenqing2" aria-expanded="false" aria-controls="collapseOne11">
                                        <span class="step">2</span>
                                        55岁以下或州政府豁免年龄要求
                                    </a>
                                </h5>
                            </div>
                            <div id="shenqing2" class="collapse p-l-10 text-left" role="tabpanel" aria-labelledby="headingOne11" style="">
                                <div class="card-body">
                                    <p>可直接享受当地居民福利待遇（如买二手房不受限制，小孩读大学可以享受当地学生待遇）
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center m-t-40">
                <button type="button" class="btn btn-circle primary-back-color color-white" style="font-size:12px;"><i class="fas fa-plus"></i> </button>
                <hr style="margin-top:-19px;" >
            </div>
            <div class="row">
                <div class="col-3 text-right">
                    <img class="round align-self-center" src="{{asset('/static/images/users/6.jpg')}}">
                </div>
                <div class="col-4 p-l-0 p-r-0">
                    <div class="col-12"><h5 class="font-weight-bold">Chris</h5></div>
                    <div class="col-12 text-left">邦海外 - 投资顾问</div>
                </div>
                <div class="col-5">
                    <button type="button" class="btn btn-outline-secondary primary-color btn-rounded primary-border-color"><i class="mdi mdi-phone primary-color "></i> &nbsp;联系我 </button>
                </div>
            </div>
        </div>

        <div id="faq" class="tab-pane row m-t-30">
            <div class="col-12">
                <h2 class="text-center font-weight-bold letter-spacing-4">FAQ</h2>
                <h6 class="text-center letter-spacing-2" id="yimin-sub-title">本移民项目专家问答</h6>
            </div>
            <div class="col-12 p-l-20 p-r-20">
                <div id="accordion2" role="tablist" class="minimal-faq m-t-30" aria-multiselectable="true">
                    <div class="card m-b-0 questin-title">
                        <div class="question-header p-l-10 p-t-20 p-b-10" role="tab" id="headingOne11">
                            <h5 class="mb-0">
                                <a class="link collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne11" aria-expanded="false" aria-controls="collapseOne11">
                                    <i class=" fas fa-angle-down"></i>
                                    <i class=" fas fa-angle-up" style="display:none"></i>
                                    132获批后，申请人享有什么权利？
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne11" class="collapse p-l-10" role="tabpanel" aria-labelledby="headingOne11" style="">
                            <div class="card-body">
                                <p>132签证是一步到位的永久居留签证，签证获得者享有一切澳洲永久居民相同的权利，（与澳洲公民分区别体现在：</p>
                                <p class="m-b-0">1. 不持有澳洲护照;</p>
                                <p class="m-b-0">2. 没有选举与被选举权;</p>
                                <p class="m-b-0">3. 不能申请澳洲政府公务员性质的工作，例如：警察、海关、参军等）如果在澳洲生育子女，也可以直接申请到澳洲国籍。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="shenqingbuzhou" class="tab-pane row m-t-30">
            <div class="col-12 m-t-30">
                <h3 class="text-center project-description-title font-weight-bold letter-spacing-2">申请步骤</h3>
                <div class="col-12 text-center font-12">详解 澳大利亚132商业天才类永居签证 申请步骤</div>
                <div class="text-center project-description m-l-20 m-r-20 m-t-30">
                    <div id="shenqingbuzhouList" role="tablist" class="minimal-faq m-t-30" aria-multiselectable="true">
                        <div class="card m-b-0 questin-title">
                            <div class="question-header p-l-10 p-t-20 p-b-10" role="tab" id="headingOne11">
                                <h5 class="mb-0 text-left">
                                    <a class="link collapsed" data-toggle="collapse" data-parent="#shenqingbuzhouList" href="#shenqingbuzhouList1" aria-expanded="false" aria-controls="collapseOne11">
                                        <span class="step">1</span>
                                        取得“意向申请”邀请
                                    </a>
                                </h5>
                            </div>
                            <div id="shenqingbuzhouList1" class="collapse p-l-10 text-left" role="tabpanel" aria-labelledby="headingOne11" style="">
                                <div class="card-body">
                                    <p>可直接享受当地居民福利待遇（如买二手房不受限制，小孩读大学可以享受当地学生待遇）
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card m-b-0 questin-title">
                            <div class="question-header p-l-10 p-t-20 p-b-10" role="tab" id="headingOne11">
                                <h5 class="mb-0 text-left">
                                    <a class="link collapsed" data-toggle="collapse" data-parent="#shenqingbuzhouList" href="#shenqingbuzhouList2" aria-expanded="false" aria-controls="collapseOne11">
                                        <span class="step">2</span>
                                        55岁以下或州政府豁免年龄要求
                                    </a>
                                </h5>
                            </div>
                            <div id="shenqingbuzhouList2" class="collapse p-l-10 text-left" role="tabpanel" aria-labelledby="headingOne11" style="">
                                <div class="card-body">
                                    <p>可直接享受当地居民福利待遇（如买二手房不受限制，小孩读大学可以享受当地学生待遇）
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div id="cost" class="tab-pane row m-t-30">
            <div class="col-12 m-t-30">
                <h3 class="text-center project-description-title font-weight-bold letter-spacing-4">费用详情</h3>
                <div class="col-12 text-center font-12">（下表可左右滑动，查看更多信息）</div>
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
    <script src="{{ asset('static/plugin/sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{ asset('static/plugin/popper/popper.min.js')}}"></script>
    <script src="{{ asset('static/plugin/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('static/mobile/property.js')}}"></script>

@endsection