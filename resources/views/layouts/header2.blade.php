<div id="kt_header" class="header flex-column header-fixed">
    <!--begin::Top-->
    <div class="header-top">
        <!--begin::Container-->
        <div class="container-fluid" style="background-color: {{Session::get('company_bgcolor')}}">
            <!--begin::Left-->
            <div class="d-none d-lg-flex align-items-center mr-3">
                <!--begin::Logo-->
                <a href="{{route('home')}}" class="">
                    @php
                        $img = "";
                        if(!empty($app_comp->p_logo_white)){
                            $img = str_replace("public", "public_html", asset("images/".$app_comp->p_logo_white));
                        } else {
                            if($accounting_mode == 1){
                                $img = asset('assets/images/logo_1.png');
                            } else {
                                $img = asset('assets/images/logo.png');
                            }
                        }
                    @endphp
                    @if($accounting_mode == 1)
                        <img alt="Logo" src="{{ $img }}" class="max-h-35px"  />
                    @else
                        <img alt="Logo" src="{{ $img }}" class="max-h-20px"  />
                    @endif
                </a>

                <!--end::Logo-->
                <!--begin::Tab Navs(for desktop mode)-->
                <ul class="header-tabs nav align-self-end font-size-lg ml-20" role="tablist">
                    <!--begin::Item-->
                    <li class="nav-item">
                        <a href="{{route('home')}}" class="nav-link py-4 px-6 active" data-toggle="tab" data-target="#kt_header_tab_1" role="tab">
                            {{ strtoupper(\Session::get('company_name_parent') ?? \Config::get("constants.APP_NAME")) }}
                        </a>
                    </li>
                    <!--end::Item-->
                </ul>
                <!--begin::Tab Navs-->
            </div>
            <!--end::Left-->
            <!--begin::Topbar-->
            <div class="topbar" style="background-color: {{Session::get('company_bgcolor')}}">
                <!--begin::Search-->
                <div class="dropdown">
                    <!--begin::Toggle-->
                    {{--<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                        <div class="btn btn-icon btn-hover-transparent-white btn-lg btn-dropdown mr-1">
								<span class="svg-icon svg-icon-xl">
									<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
									<i class="fa fa-search"></i>
                                    <!--end::Svg Icon-->
								</span>
                        </div>
                    </div>--}}
                    <!--end::Toggle-->
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                        <div class="quick-search quick-search-dropdown" id="kt_quick_search_dropdown">
                            <!--begin:Form-->
                            <form method="get" class="quick-search-form">
                                <div class="input-group">
                                    <div class="input-group-prepend">
											<span class="input-group-text">
												<span class="svg-icon svg-icon-lg">
													<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
													<i class="fa fa-search"></i>
                                                    <!--end::Svg Icon-->
												</span>
											</span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Search..." />
                                    <div class="input-group-append">
											<span class="input-group-text">
												<i class="quick-search-close ki ki-close icon-sm text-muted"></i>
											</span>
                                    </div>
                                </div>
                            </form>
                            <!--end::Form-->
                            <!--begin::Scroll-->
                            <div class="quick-search-wrapper scroll" data-scroll="true" data-height="325" data-mobile-height="200"></div>
                            <!--end::Scroll-->
                        </div>
                    </div>
                    <!--end::Dropdown-->
                </div>
                {{--<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                    <div class="btn btn-icon btn-hover-transparent-white btn-lg btn-dropdown mr-1">
								<span class="svg-icon svg-icon-xl">
									<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
									<i class="fa fa-arrow-left"></i>
                                    <!--end::Svg Icon-->
								</span>
                    </div>
                </div>--}}
                <!--end::Search-->
                <!--begin::Notifications-->
                @if (Auth::user()->hasVerifiedEmail() && empty(Auth::user()->delete_schedule))
                <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-offset="10px,0px">
                        <select name="change_locale" id="change-locale" class="form-control selectpicker bg-transparent" data-style="bg-transparent text-white font-weight-bolder">
                            <option value="en" {{ $app_locale == "en" ? "SELECTED" : "" }} class="font-weight-bolder" data-icon="flag-icon flag-icon-gb flag-icon-squared">English</option>
                            <option value="id" {{ $app_locale == "id" ? "SELECTED" : "" }} class="font-weight-bolder" data-icon="flag-icon flag-icon-id flag-icon-squared">Indonesia</option>
                        </select>
                    </div>
                    <!--end::Toggle-->
                </div>
                {{-- Notifications --}}
                <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" onclick="notifications_panel()">
                        <div class="btn btn-icon btn-hover-transparent-white btn-dropdown btn-lg mr-1 pulse pulse-white">
                            <span class="svg-icon svg-icon-xl">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                                <i class="fa fa-bell"></i>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="pulse-ring"></span>
                            <span class="label label-danger mb-8 ml-8 mr-2 position-absolute label-sm" id="notifications-count">{{ \Session::get("notifications_count") }}</span>
                        </div>
                    </div>
                    <!--end::Toggle-->
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-xl">
                        <form>
                            <!--begin::Content-->
                            <div class="tab-content">
                                <!--begin::Tabpane-->
                                <div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">
                                    <!--begin::Scroll-->
                                    <div class="scroll pr-7 mr-n7" data-scroll="true" data-height="500" data-mobile-height="200">
                                        <h5>Notifications</h5><br />
                                        <!--begin::Item-->
                                        <div class="d-flex justify-content-center w-100" id="notification-spinner">
                                            <div class="spinner spinner-primary spinner-track spinner-lg"></div>
                                        </div>
                                        <div id="notifications-panel" class="h-100">
                                        </div>
                                    <!--end::Item-->
                                    </div>
                                    <!--end::Scroll-->
                                    <!--begin::Action-->
                                    {{-- <div class="d-flex flex-center pt-7">
                                        <a href="#" class="btn btn-light-primary font-weight-bold text-center">See All</a>
                                    </div> --}}
                                    <!--end::Action-->
                                </div>
                                <!--end::Tabpane-->
                            </div>
                            <!--end::Content-->
                        </form>
                    </div>
                    <!--end::Dropdown-->
                </div>
                {{-- end Notifications --}}
                @endif
                <!--end::Notifications-->
                <!--begin::Quick panel-->
                @actionStart('preferences', 'access')
                <div class="topbar-item">
                    <div class="btn btn-icon btn-hover-transparent-white btn-lg mr-1" id="kt_server_config">
                        <a href='{{route('company.index')}}'>
							<span class="svg-icon svg-icon-xl">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
								<i class="fa fa-cog"></i>
                                <!--end::Svg Icon-->
							</span>
                        </a>
                    </div>
                </div>
                @actionEnd
                <!--end::Quick panel-->
                <!--begin::User-->
            <!-- <div class="topbar-item">
						<div class="btn btn-icon btn-hover-transparent-white w-auto d-flex align-items-center btn-lg px-2">
							<span class="symbol symbol-35">
								<span class="symbol-label font-size-h5 font-weight-bold text-white bg-white"><img src='' height='30px'/></span>
							</span>
						</div>
					</div> -->
                <!--begin::User-->
                <div class="topbar-item">
                    <div class="btn btn-icon btn-hover-transparent-white w-auto d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                        <div class="d-flex flex-column text-right pr-3">
                            <span class="text-white opacity-50 font-weight-bold font-size-sm d-none d-md-inline">{{Auth::user()->name}}</span>
                            <span class="text-white font-weight-bolder font-size-sm d-none d-md-inline">{{(Auth::user()->position == null)?'SYSTEM':Auth::user()->position}}</span>
                        </div>
                        @php
                            $str = Auth::user()->name;
                        @endphp
                        <span class="symbol symbol-35">
                                        <span class="symbol-label font-size-h5 font-weight-bold text-white bg-white-o-30">{{strtoupper($str[0])}}</span>
                                    </span>
                    </div>
                </div>
                <!--end::User-->
                <!--end::User-->
            </div>
            <!--end::Topbar-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Top-->
    <!--begin::Bottom-->
    @if(!\Session::get("is_mobile"))
    <div class="header-bottom">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Header Menu Wrapper-->
            <div class="header-navs header-navs-left" id="kt_header_navs">
                <!--begin::Tab Content-->
                <div class="tab-content">
                    <!--begin::Tab Pane-->
                    <div class="tab-pane py-5 p-lg-0 show active" id="kt_header_tab_1">
                        <!--begin::Menu-->
                        <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                            <!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item menu-item-submenu">
                                    <a href="{{ route("home") }}" class="menu-link">
                                        <span class="menu-text">Dashbard</span>
                                    </a>
                                </li>
                                @if (\Config::get("constants.IS_BP") == 0)
                                <li class="menu-item menu-item-submenu">
                                    <a href="{{ route("applicant.job.index") }}" class="menu-link">
                                        <span class="menu-text">Cari Pekerjaan</span>
                                    </a>
                                </li>
                                @endif
                                @actionStart('management', 'access')
                                <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Managements</span>
                                        <span class="menu-desc"></span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu menu-submenu-fixed">
                                        <div class="menu-subnav">
                                            <ul class="menu-content">
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text"></span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('accounts', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('company.user', base64_encode(Session::get('company_id')))}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Accounts</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('employee', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('employee.index', base64_encode(Session::get('company_id')))}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Employee</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('employee', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('emp.mt.index', base64_encode(Session::get('company_id')))}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Employee Attendance</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('storage', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('wh.index', base64_encode(Session::get('company_id')))}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Stroages</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('client', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('marketing.client.index', base64_encode(Session::get('company_id')))}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Clients</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('variables', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('employee_variables', Session::get("company_id"))}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Variables</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('activity_test', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('hrd.test.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Activity Test</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('job_vacancy', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu`~-toggle="hover" aria-haspopup="true">
                                                            <a href="{{URL::route('job.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Job Vacancy</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('translations', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu`~-toggle="hover" aria-haspopup="true">
                                                            <a href="{{ action('\Barryvdh\TranslationManager\Controller@getIndex') }}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Translations</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('master_data', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu`~-toggle="hover" aria-haspopup="true">
                                                            <a href="{{ route('master_data.index') }}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Master Data</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                @actionEnd
                            </ul>
                            <!--end::Nav-->
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--begin::Tab Pane-->
                </div>
                <!--end::Tab Content-->
            </div>
            <!--end::Header Menu Wrapper-->
        </div>
        <!--end::Container-->
    </div>
    @endif
    <!--end::Bottom-->
</div>
<!--end::Header
