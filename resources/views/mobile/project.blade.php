@extends('mobile.layout.mobile')

@section('styles')
    <link href="{{asset('static/plugin/datatables/media/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('static/css/pages/dashboard4.css')}}" rel="stylesheet">
    <link href="{{asset('static/plugin/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('static/plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('static/plugin/prism/prism.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.0/mapsjs-ui.css?dp-version=1533195059" />

@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Campaign -->
    <!-- ============================================================== -->
    <div class="row">
            <div class="news-slide m-b-15">
                <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-1z" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-1z" data-slide-to="1"></li>
                        <li data-target="#carousel-example-1z" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="active carousel-item">
                            <img class="d-block w-100" src="../static/images/app-logo/london.jpg"/>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../static/images/app-logo/singapore.jpg" />
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../static/images/app-logo/sydney.jpg" />
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 m-b-10">
            <h4 class="text-center font-weight-bold property-title">普吉Trichada Sky海景别墅</h4>
        </div>
        <div class="col-6 text-center location">泰国 · 普吉岛 · 邦湃</div>
        <div class="col-6 text-center price">111万人民币起，首付 10%</div>
        <div class="col-sm-6 text-center tags-wrapper m-t-20">
            <button type="button" class="btn mybtn waves-effect waves-light btn-warning btn-small">核心地段</button>
            <button type="button" class="btn mybtn waves-effect waves-light btn-warning btn-small">独特设计</button>
            <button type="button" class="btn mybtn waves-effect waves-light btn-warning btn-small">包租</button>
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
                    <div><i class="fas fa-clone fa-rotate-45"></i></div>
                    <a href="#huxing" class="nav-link p-0" data-toggle="tab" aria-expanded="true">户型分析</a>
                </div>
            </li>
            <li class="nav-item tab-wrapper">
                <div class="p-t-10 tab-shadow">
                    <div><i class=" fas fa-compass"></i> </div>
                    <a href="#surround" class="nav-link p-0" data-toggle="tab" id="map-tab" aria-expanded="true">周边配套</a>
                </div>
            </li>
        </ul>
        <div class="p-t-10 m-t-30 middle-space "></div>

        <div id="property-info" class="tab-pane row m-t-30 active">
            <div class="col-12 ">
                <h2 class="text-center project-description-title font-weight-bold">项目简介</h2>
                <div class="text-center project-description m-l-20 m-r-20 m-t-30">
                    <p>
                        距离邦涛海滩（Bangtao Beach）和拉彦海滩（Layan Beach）极近；毗邻普吉岛拉古娜度假村LAGUNA，是普吉最成熟的富人区；
                    </p>
                    <p>
                        靠近悦榕庄，五星酒店度假村，在2500W-3000W泰铢以上的别墅；周边配套国际学校 - UWC世界联合学院；
                    </p>
                    <p>
                        知名开发商操刀定制，巴厘岛式现代泳池风格；一期24栋已全部售罄，三房租金在14万泰铢一个月，二期全部售罄，三期出炉。
                    </p>
                </div>
            </div>
            <div class="col-12 text-center">
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

        <div id="huxing" class="tab-pane row m-t-30">
            <div id="carouselExampleControls" class="carousel slide p-b-10" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="https://mdbootstrap.com/img/Photos/Slides/img%20(45).jpg"
                             alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="https://mdbootstrap.com/img/Photos/Slides/img%20(46).jpg"
                             alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="https://mdbootstrap.com/img/Photos/Slides/img%20(47).jpg"
                             alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <div id="current-house" class="row p-t-10 p-b-10 m-t-40">
                <div class="col-8 p-l-30">
                    <div class="col-12"><h5 class="font-weight-bold">A.303</h5></div>
                    <div class="col-12 text-left">1房, 1卫, 1车位   35平米</div>
                </div>
                <div class="col-4 text-center p-t-10 letter-spacing-2">
                    <i class="fas fa-calculator primary-color"></i>&nbsp;&nbsp;
                    <span class="font-12">投资分析</span>
                </div>
            </div>
            <div class="col-12"><h5 class="text-center font-weight-bold m-t-40">其他推荐户型</h5> </div>
            <div class="row p-t-10 p-b-10 m-t-30 border-top border-bottom">
                <div class="col-8 p-l-30">
                    <div class="col-12"><h5 class="font-weight-bold">A.303</h5></div>
                    <div class="col-12 text-left">1房, 1卫, 1车位   35平米</div>
                </div>
                <div class="col-4 text-center p-t-10 letter-spacing-2">
                    <i class="fas fa-calculator primary-color"></i>&nbsp;&nbsp;
                    <span class="font-12">投资分析</span>
                </div>
            </div>

        </div>

        <div id="surround" class="tab-pane row m-t-30">
            <div class="col-12">
                <h2 class="text-center font-weight-bold letter-spacing-4">配套完全、交通便利</h2>
                <h6 class="text-center letter-spacing-2" id="yimin-sub-title">（点击地图、打开配套分析）</h6>
            </div>
            <div id="map-box" style="width: 100%; height: 200px;"></div>
            <div class="card">
                <ul class="nav nav-tabs customtab m-b-20" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#primaryTab" role="tab"> <h4 class="m-t-20">小学</h4>
                        </a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#highSchoolTab" role="tab"> <h4 class="m-t-20">中学</h4>
                        </a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#univercityTab" role="tab"> <h4 class="m-t-20">大学</h4>
                        </a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#restrauntTab" role="tab"><h4 class="m-t-20">餐饮</h4>
                        </a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#hospitalTab" role="tab"> <h4 class="m-t-20">医院</h4>
                        </a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#transportTab" role="tab"> <h4 class="m-t-20">公共交通</h4>
                        </a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#leisureTab" role="tab"> <h4 class="m-t-20">娱乐</h4>
                        </a> </li>
                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#shoppingTab" role="tab"> <h4 class="m-t-20">购物</h4>
                        </a> </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active" id="primaryTab" role="tabpanel">
                        <div class="row m-b-15">
                            <div class="col-md-8">如果有修改，请务必保存。否则将会丢失翻译的记录。</div>
                            <div class="col-md-4 text-right">展示：2,000米半径内的小学</div>
                        </div>
                        <div class="table-responsive table-editable">
                            <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>分类</th>
                                    <th>自动抓取结果</th>
                                    <th>前端显示内容</th>
                                    <th>距离</th>
                                    <th>坐标</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="primary-school">
                                @isset($nearby)
                                    @foreach($nearby->primarySchool as $place)
                                        <tr>
                                            <td><img src="{{asset('static/images/location/icmark-primary.png')}}" height="30px"></td>
                                            <td>{{$place->name}}</td>
                                            <td contenteditable="true">{{$place->chinese}}</td>
                                            <td contenteditable="true">{{$place->distance}}</td>
                                            <td>{{$place->location}}</td>
                                            <td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,'小学')"><i class="ti-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="highSchoolTab" role="tabpanel">
                        <div class="row m-b-15">
                            <div class="col-md-8">如果有修改，请务必保存。否则将会丢失翻译的记录。</div>
                            <div class="col-md-4 text-right">展示：3,000米半径内的中学</div>
                        </div>
                        <div class="table-responsive">
                            <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>分类</th>
                                    <th>自动抓取结果</th>
                                    <th>前端显示内容</th>
                                    <th>距离</th>
                                    <th>坐标</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="high-school">
                                @isset($nearby)
                                    @foreach($nearby->highSchool as $place)
                                        <tr>
                                            <td><img src="{{asset('static/images/location/icmark-high.png')}}" height="30px"></td>
                                            <td>{{$place->name}}</td>
                                            <td contenteditable="true">{{$place->chinese}}</td>
                                            <td contenteditable="true">{{$place->distance}}</td>
                                            <td>{{$place->location}}</td>
                                            <td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,'中学')"><i class="ti-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="univercityTab" role="tabpanel">
                        <div class="row m-b-15">
                            <div class="col-md-8">如果有修改，请务必保存。否则将会丢失翻译的记录。</div>
                            <div class="col-md-4 text-right"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>分类</th>
                                    <th>自动抓取结果</th>
                                    <th>前端显示内容</th>
                                    <th>距离</th>
                                    <th>坐标</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="university">
                                @isset($nearby)
                                    @foreach($nearby->university as $place)
                                        <tr>
                                            <td><img src="{{asset('static/images/location/icmark-university.png')}}" height="30px"></td>
                                            <td>{{$place->name}}</td>
                                            <td contenteditable="true">{{$place->chinese}}</td>
                                            <td contenteditable="true">{{$place->distance}}</td>
                                            <td>{{$place->location}}</td>
                                            <td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,'大学')"><i class="ti-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="restrauntTab" role="tabpanel">
                        <div class="row m-b-15">
                            <div class="col-md-8">如果有修改，请务必保存。否则将会丢失翻译的记录。</div>
                            <div class="col-md-4 text-right">展示：1,000米半径内的餐饮</div>
                        </div>
                        <div class="table-responsive">
                            <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>分类</th>
                                    <th>自动抓取结果</th>
                                    <th>前端显示内容</th>
                                    <th>距离</th>
                                    <th>坐标</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="restaurant">
                                @isset($nearby)
                                    @foreach($nearby->restaurant as $place)
                                        <tr>
                                            <td><img src="{{asset('static/images/location/icmark-eatdrink.png')}}" height="30px"></td>
                                            <td>{{$place->name}}</td>
                                            <td contenteditable="true">{{$place->chinese}}</td>
                                            <td contenteditable="true">{{$place->distance}}</td>
                                            <td>{{$place->location}}</td>
                                            <td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,'餐饮')"><i class="ti-trash"></i></button></td>

                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="hospitalTab" role="tabpanel">
                        <div class="row m-b-15">
                            <div class="col-md-8">如果有修改，请务必保存。否则将会丢失翻译的记录。</div>
                            <div class="col-md-4 text-right"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>分类</th>
                                    <th>自动抓取结果</th>
                                    <th>前端显示内容</th>
                                    <th>距离</th>
                                    <th>坐标</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="hospital">
                                @isset($nearby)
                                    @foreach($nearby->hospital as $place)
                                        <tr>
                                            <td><img src="{{asset('static/images/location/icmark-hospital.png')}}" height="30px"></td>
                                            <td>{{$place->name}}</td>
                                            <td contenteditable="true">{{$place->chinese}}</td>
                                            <td contenteditable="true">{{$place->distance}}</td>
                                            <td>{{$place->location}}</td>
                                            <td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,'医院')"><i class="ti-trash"></i></button></td>

                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="transportTab" role="tabpanel">
                        <div class="row m-b-15">
                            <div class="col-md-8">如果有修改，请务必保存。否则将会丢失翻译的记录。</div>
                            <div class="col-md-4 text-right">展示：1,000米半径内的公共交通</div>
                        </div>
                        <div class="table-responsive">
                            <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>分类</th>
                                    <th>自动抓取结果</th>
                                    <th>前端显示内容</th>
                                    <th>距离</th>
                                    <th>坐标</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="public-transport">
                                @isset($nearby)
                                    @foreach($nearby->transport as $place)
                                        <tr>
                                            <td><img src="{{asset('static/images/location/icmark-transport.png')}}" height="30px"></td>
                                            <td>{{$place->name}}</td>
                                            <td contenteditable="true">{{$place->chinese}}</td>
                                            <td contenteditable="true">{{$place->distance}}</td>
                                            <td>{{$place->location}}</td>
                                            <td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,'公共交通')"><i class="ti-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="leisureTab" role="tabpanel">
                        <div class="row m-b-15">
                            <div class="col-md-8">如果有修改，请务必保存。否则将会丢失翻译的记录。</div>
                            <div class="col-md-4 text-right">展示：2,000米半径内的娱乐</div>
                        </div>
                        <div class="table-responsive">
                            <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>分类</th>
                                    <th>自动抓取结果</th>
                                    <th>前端显示内容</th>
                                    <th>距离</th>
                                    <th>坐标</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="leisure">
                                @isset($nearby)
                                    @foreach($nearby->leisure as $place)
                                        <tr>
                                            <td><img src="{{asset('static/images/location/icmark-goingout.png')}}" height="30px"></td>
                                            <td>{{$place->name}}</td>
                                            <td contenteditable="true">{{$place->chinese}}</td>
                                            <td contenteditable="true">{{$place->distance}}</td>
                                            <td>{{$place->location}}</td>
                                            <td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,'娱乐')"><i class="ti-trash"></i></button></td>

                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="shoppingTab" role="tabpanel">
                        <div class="row m-b-15">
                            <div class="col-md-8">如果有修改，请务必保存。否则将会丢失翻译的记录。</div>
                            <div class="col-md-4 text-right">展示：5,000米半径内的购物</div>
                        </div>
                        <div class="table-responsive">
                            <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>分类</th>
                                    <th>自动抓取结果</th>
                                    <th>前端显示内容</th>
                                    <th>距离</th>
                                    <th>坐标</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody id="shopping">
                                @isset($nearby)
                                    @foreach($nearby->shopping as $place)
                                        <tr>
                                            <td><img src="{{asset('static/images/location/icmark-shopping.png')}}" height="30px"></td>
                                            <td>{{$place->name}}</td>
                                            <td contenteditable="true">{{$place->chinese}}</td>
                                            <td contenteditable="true">{{$place->distance}}</td>
                                            <td>{{$place->location}}</td>
                                            <td><button class="btn btn-sm btn-danger m-l-20" onclick="deleteItem(this,'购物')"><i class="ti-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <input type="hidden" id="lat" value="27.69039">
        <input type="hidden" id="lng" value="85.34139">

    </div>
@endsection

@section('scripts')
    <!--Sky Icons JavaScript -->
    <script src="{{asset('static/plugin/skycons/skycons.js')}}"></script>
    <!--morris JavaScript -->
    <script src="{{asset('static/plugin/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('static/plugin/morrisjs/morris.min.js')}}"></script>
    <script src="{{asset('static/plugin/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-mapevents.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.0/mapsjs-places.js"></script>

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
    <script src="{{asset('static/mobile/property.js')}}"></script>
    <script src="{{ asset('static/admin/map.js') }}"></script>

@endsection
