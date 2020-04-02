<div class="side-mini-panel">
    <ul class="mini-nav">
        <!-- .Dashboard -->
        <li class="selected">
            <div class="sidebarmenu">
                <!-- Left navbar-header -->
                <h3 class="menu-title">管理平台</h3>
                <ul class="sidebar-menu" style="overflow-y: auto;height: calc(100vh - 180px)">
                    @if(!empty(auth()->guard('admin')->id()))
                        <li class="menu {{(strpos(request()->path(),'admin/doctor')!==false ) ?'active':'' }}">
                            <a href="javascript:void(0)">医生管理<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'admin/doctor/department')!==false || request()->path()=='admin/doctor/create' || request()->path()=='admin/doctor/view' || strpos(request()->path(),'admin/doctor/edit')!==false|| strpos(request()->path(),'admin/doctor/detail')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == 'admin/doctor/create' ? 'active' : ''}}"><a class="{{request()->path() == 'admin/doctor/create' ? 'active' : ''}}" href="/admin/doctor/create">新医生</a></li>
                                <li class="{{request()->path() == 'admin/doctor/view' || strpos(request()->path(),'admin/doctor/edit')!==false || strpos(request()->path(),'admin/doctor/detail')!==false ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'admin/doctor/view')!==false || strpos(request()->path(),'admin/doctor/edit')!==false || strpos(request()->path(),'admin/doctor/detail')!==false  ? 'active' : ''}}" href="/admin/doctor/view">查看医生</a></li>
                                <li class="{{request()->path() == 'admin/doctor/department' ? 'active' : ''}}"><a class="{{request()->path() == 'admin/doctor/department' ? 'active' : ''}}" href="/admin/doctor/department">医院分科</a></li>
                            </ul>
                        </li>
                        <li class="menu {{(strpos(request()->path(),'admin/setting')!==false||strpos(request()->path(),'admin/hospital')!==false ) ?'active':'' }}">
                            <a href="javascript:void(0)">系统设置<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'admin/setting')!==false||strpos(request()->path(),'admin/hospital')!==false ) ?'block':'none' }}">
                                <li class="{{request()->path() == 'admin/setting' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'admin/setting')!==false ? 'active' : ''}}" href="/admin/setting">付费设置</a></li>
                                <li class="{{request()->path() == 'admin/hospital' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'admin/hospital')!==false ? 'active' : ''}}" href="/admin/hospital">医院管理</a></li>
                            </ul>
                        </li>
                        <li class="menu {{(strpos(request()->path(),'admin/authority')!==false ) ?'active':'' }}">
                            <a href="javascript:void(0)">权限平台<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'admin/authority')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == 'admin/authority/view' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'admin/authority/view')!==false ? 'active' : ''}}" href="/admin/authority/view">查看权限</a></li>
                            </ul>
                        </li>
                        <li class="menu {{(strpos(request()->path(),'admin/income/hospital')!==false||strpos(request()->path(),'admin/income/doctor')!==false ) ?'active':'' }}">
                            <a href="javascript:void(0)">收入记录<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'admin/income/hospital')!==false||strpos(request()->path(),'admin/income/doctor')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == 'admin/income/hospital' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'admin/income/hospital')!==false ? 'active' : ''}}" href="/admin/income/hospital">医院收入</a></li>
                                <li class="{{request()->path() == 'admin/income/doctor' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'admin/income/doctor')!==false ? 'active' : ''}}" href="/admin/income/doctor">医生收入</a></li>
                            </ul>
                        </li>

                    @endif
                    @if(!empty(auth()->guard('admin')->id()) || checkAuthority(auth()->guard('doctor')->id(),'/doctor/recipe'))
                        <li class="menu {{(strpos(request()->path(),'doctor/recipe')!==false ) ?'active':'' }}">
                            <a href="javascript:void(0)">药方管理<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'doctor/recipe')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == 'doctor/recipe/create' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'doctor/recipe/create')!==false ? 'active' : ''}}" href="/doctor/recipe/create">新药方</a></li>
                                <li class="{{request()->path() == 'doctor/recipe/view' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'doctor/recipe/view')!==false ? 'active' : ''}}" href="/doctor/recipe/view">查看药方</a></li>
                                <li class="{{request()->path() == 'doctor/recipe/give' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'doctor/recipe/give')!==false ? 'active' : ''}}" href="/doctor/recipe/give">发药</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(!empty(auth()->guard('admin')->id()) || checkAuthority(auth()->guard('doctor')->id(),'/doctor/qa'))
                        <li class="menu {{(strpos(request()->path(),'doctor/qa')!==false ) ?'active':'' }}">
                            <a href="javascript:void(0)">问诊单管理<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'doctor/qa')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == 'doctor/qa/create' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'doctor/qa/create')!==false ? 'active' : ''}}" href="/doctor/qa/create">新问诊单</a></li>
                                <li class="{{request()->path() == 'doctor/qa/view' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'doctor/qa/view')!==false ? 'active' : ''}}" href="/doctor/qa/view">查看问诊单</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(!empty(auth()->guard('admin')->id()) || checkAuthority(auth()->guard('doctor')->id(),'/doctor/accept'))
                        <li class="menu {{(strpos(request()->path(),'doctor/accept/patient/create')!==false ||strpos(request()->path(),'doctor/accept/guahao/view')!==false || strpos(request()->path(),'doctor/accept/guahao/edit')!==false ) ?'active':'' }}">
                            <a href="javascript:void(0)">挂号管理<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'doctor/accept/patient/create')!==false || request()->path()=='doctor/accept/guahao/view' || strpos(request()->path(),'doctor/accept/guahao/edit')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == '/doctor/accept/patient/create' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/accept/patient/create' ? 'active' : ''}}" href="/doctor/accept/patient/create">新挂号</a></li>
                                <li class="{{request()->path() == '/doctor/accept/guahao/view' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/accept/guahao/view' ? 'active' : ''}}" href="/doctor/accept/guahao/view">挂号列表</a></li>
                            </ul>
                        </li>
                        <li class="menu {{(strpos(request()->path(),'doctor/accept/payment/create')!==false ||strpos(request()->path(),'doctor/accept/payment/list')!==false  ) ?'active':'' }}">
                            <a href="javascript:void(0)">付费管理<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'doctor/accept/payment/create')!==false || request()->path()=='doctor/accept/payment/list') ?'block':'none' }}">
                                <li class="{{request()->path() == '/doctor/accept/payment/create' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/accept/payment/create' ? 'active' : ''}}" href="/doctor/accept/payment/create">新挂号</a></li>
                                <li class="{{request()->path() == '/doctor/accept/payment/list' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/accept/payment/list' ? 'active' : ''}}" href="/doctor/accept/payment/list">挂号列表</a></li>
                            </ul>
                        </li>
                    @endif
                    @if(!empty(auth()->guard('admin')->id()) || checkAuthority(auth()->guard('doctor')->id(),'/doctor/medicine'))
                        <li class="menu {{(strpos(request()->path(),'doctor/medicine/view')!==false ||strpos(request()->path(),'doctor/medicine/contrary/view')!==false||strpos(request()->path(),'doctor/medicine/contrary/editPrice')!==false||strpos(request()->path(),'doctor/medicine/yield')!==false  ) ?'active':'' }}">
                            <a href="javascript:void(0)">药材管理<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'doctor/medicine/view')!==false || request()->path()=='doctor/medicine/contrary/view'||request()->path()=='doctor/medicine/editPrice'|| strpos(request()->path(),'doctor/medicine/yield')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == '/doctor/medicine/view' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/medicine/view' ? 'active' : ''}}" href="/doctor/medicine/view">查看药材</a></li>
                                <li class="{{request()->path() == '/doctor/medicine/editPrice' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/medicine/editPrice' ? 'active' : ''}}" href="/doctor/medicine/editPrice">编辑药价</a></li>
                                <li class="{{request()->path() == 'doctor/medicine/contrary/view' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/medicine/contrary/view' ? 'active' : ''}}" href="/doctor/medicine/contrary/view">查看排斥药材</a></li>
{{--                                <li class="{{request()->path() == '/doctor/medicine/yield/view' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/medicine/yield/view' ? 'active' : ''}}" href="/doctor/medicine/yield/view">查看发药</a></li>--}}

                            </ul>
                        </li>
                    @endif
                    @if(!empty(auth()->guard('admin')->id()) || checkAuthority(auth()->guard('doctor')->id(),'/doctor/inquiry'))
                        <li class="menu {{(strpos(request()->path(),'doctor/inquiry/view')!==false ||strpos(request()->path(),'doctor/inquiry/create')!==false||strpos(request()->path(),'doctor/history/detail')!==false  ) ?'active':'' }}">
                            <a href="javascript:void(0)">问诊管理<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'doctor/inquiry/view')!==false || strpos(request()->path(),'doctor/inquiry/create')!==false|| strpos(request()->path(),'doctor/history/detail')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == '/doctor/inquiry/view' ? 'active' : ''}}"><a class="{{request()->path() == 'doctor/inquiry/view' ? 'active' : ''}}" href="/doctor/inquiry/view">查看问诊</a></li>
                            </ul>
                        </li>
                    @endif
                    <li class="menu {{(strpos(request()->path(),'doctor/history')!==false ) ?'active':'' }}">
                        <a href="javascript:void(0)">病历列表<i class="fa fa-angle-left float-right"></i></a>
                        <ul class="sub-menu" style="display:{{(strpos(request()->path(),'doctor/history')!==false) ?'block':'none' }}">
                            <li class="{{request()->path() == 'doctor/history/individual' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'doctor/history/individual')!==false ? 'active' : ''}}" href="/doctor/history/individual">个人病例</a></li>
                            <li class="{{request()->path() == 'doctor/history/all' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'doctor/history/all')!==false ? 'active' : ''}}" href="/doctor/history/all">全部病例</a></li>
                        </ul>
                    </li>

                    @if(empty(auth()->guard('admin')->id()))
                        <li class="menu {{(strpos(request()->path(),'doctor/setting')!==false ) ?'active':'' }}">
                            <a href="javascript:void(0)">设置<i class="fa fa-angle-left float-right"></i></a>
                            <ul class="sub-menu" style="display:{{(strpos(request()->path(),'doctor/setting')!==false) ?'block':'none' }}">
                                <li class="{{request()->path() == 'doctor/setting/change_password' ? 'active' : ''}}"><a class="{{strpos(request()->path() ,'doctor/setting/change_password')!==false ? 'active' : ''}}" href="/doctor/setting/change_password">更改密码</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
                <!-- Left navbar-header end -->
            </div>
        </li>
    </ul>
</div>
