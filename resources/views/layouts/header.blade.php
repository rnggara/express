<!--begin::View component-->
<!--end::View component-->
<div id="kt_header"
    class="header min-h-75px h-auto {{ $clHead ?? "bg-white" }} flex-column @if (isset($style) && !empty($style)) {{ $style['border'] }} @endif "
    @if (isset($style) && !empty($style)) style="box-shadow: {{ $style['box-shadow'] }}" @endif>
    <!--begin::Container-->
    <div class="container-fluid d-flex flex-stack min-h-75px">
        <!--begin::Brand-->
        <div class="d-flex align-items-center me-5">
            @auth
                @if (Auth::user()->access == 'EP' && (\Config::get('constants.PORTAL_STATE') == 3 || \Config::get('constants.PORTAL_STATE') == 4))
                    <a href="javascript:;" id="kt_drawer_right_menu_button" class="btn p-0">
                        <i class="fi fi-rr-grid fs-2 text-gray-500"></i>
                    </a>
                @endif
            @endauth
            @php
                $broker = \Config::get('constants.SSO_BROKER_NAME');
            @endphp
            <!--begin::Logo-->
            <a href="{{ \Session::get("lp") ?? route("lp") }}" class="d-flex align-items-center">
                <img alt="Logo" src="{{ asset(\Config::get('constants.APP_LOGO')) }}" class="h-25px h-lg-50px" />
                {{-- <div class="position-relative">
                    <span class="fs-2 fw-bold text-warning">{{ \Config::get('constants.APP_LABEL') }}</span>
                    @if (isset($subTitle) && !empty($subTitle))
                        <span class="end-0 fs-6 position-absolute text-primary top-75" style="font-style: italic">{{ $subTitle ?? "CRM" }}</span>
                    @endif
                </div> --}}
            </a>
            <!--end::Logo-->
            {{-- being::nav --}}
            @if(!($noMenu ?? false))
            <div class="d-flex align-items-center d-none d-md-inline">
                <div class="ms-5 ms-md-10 d-flex">
                    <!--begin::Toggle-->
                    @if (\Config::get("constants.PORTAL_STATE") == 3)
                        <a href="{{ route('home') }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-none d-md-inline">{{ __('Beranda') }}</span>
                        </a>
                        <a href="{{ route('be.orders') }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-none d-md-inline">{{ __('Order') }}</span>
                        </a>
                        <a href="{{ route('be.refund') }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-none d-md-inline">{{ __('Refund Request') }}</span>
                        </a>
                        <div>
                            <button type="button"
                                class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                <span class="d-none d-md-inline">{{ __('CMS') }}</span>
                                <i class="fi fi-rr-caret-down fs-4 ms-2 ms-md-3 me-0"></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="{{ route('cms.employer.index') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Branding</span>

                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('cms.applicant.index') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Landing Page</span>

                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('cms.pages.index') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Pages</span>

                                    </a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="button"
                                class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                <span class="d-none d-md-inline">{{ __('Database') }}</span>
                                <i class="fi fi-rr-caret-down fs-4 ms-2 ms-md-3 me-0"></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
                                data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <a href="{{ route('be.vendors') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Vendor</span>

                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('be.clients') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Users</span>

                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('be.address') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Address</span>

                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('be.settings') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Country - Zone</span>

                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('be.zone_pricing') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Zone Pricing</span>

                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('be.zone_multiplier') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Zone Multiplier</span>

                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('be.promo') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Promo</span>

                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                    <a href="@auth {{ route('home') }} @else {{ route('lp') }} @endauth"
                        class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                        <span class="d-none d-md-inline">{{ __('Beranda') }}</span>
                    </a>
                    @auth
                        <a href="{{ route("booking.index") }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-none d-md-inline">{{ __('Booking') }}</span>
                        </a>
                        <a href="{{ route("deposit.index") }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-none d-md-inline">{{ __('Deposit') }}</span>
                        </a>
                        {{-- <a href="{{ route("invoice.index") }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-none d-md-inline">{{ __('Invoice') }}</span>
                        </a> --}}
                    @endauth
                    <a href="{{ route('cek.resi') }}"
                        class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                        <span class="d-none d-md-inline">{{ __('Cek Resi') }}</span>
                    </a>
                    {{-- <a href="#"
                        class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                        <span class="d-none d-md-inline">{{ __('Hubungi Kami') }}</span>
                    </a>
                    <a href="#"
                        class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                        <span class="d-none d-md-inline">{{ __('Tentang Kami') }}</span>
                    </a> --}}
                    @endif
                </div>
            </div>
            @endif
            {{-- end::nav --}}
        </div>
        <!--end::Brand-->
        <!--begin::Topbar-->
        <div class="d-flex align-items-center flex-shrink-0">
            @auth
                @if (\Config::get("constants.PORTAL_STATE") == 3)
                <div class="d-none">
                    <button type="button" class="btn btn-icon btn-sm" data-bs-toggle="tooltip" title="Company Switcher" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px me-5">
                                <div class="symbol-label" style="background-image: url('{{ asset(stripos(\Session::get("company_app_logo"), "attachment") !== false ? \Session::get("company_app_logo") : "images/".(Session::get("company_app_logo") == "" ? "image_placeholder.png" : Session::get("company_app_logo"))) }}')"></div>
                            </div>
                        </div>
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                        @foreach ($comp->whereIn("id", \Session::get("comp_user")) as $item)
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="{{ route("company.switch") }}?id={{ $item->id }}" data-kt-drawer-show="true" class="menu-link px-3">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-5">
                                            <div class="symbol-label" style="background-image: url('{{ asset(stripos($item->app_logo, "attachment") !== false ? $item->app_logo : "images/".($item->app_logo == "" ? "image_placeholder.png" : $item->app_logo)) }}')"></div>
                                        </div>
                                        <span>{{ $item->company_name }}</span>
                                    </div>
                                </a>
                            </div>
                            <!--end::Menu item-->
                        @endforeach
                        {{-- <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('name',{{$item->id}})" class="menu-link px-3">
                                Edit Data
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.machine_type.delete', ['type' => "name", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->machine_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
                                Delete Data
                            </a>
                        </div>
                        <!--end::Menu item--> --}}
                    </div>
                </div>
                {{-- <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                        <div class="btn btn-icon btn-hover-transparent-white btn-dropdown btn-lg mr-1 pulse pulse-white">
                            <!-- <span class="svg-icon svg-icon-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                                        <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                                    </g>
                                </svg>
                            </span> -->
                            <span class="symbol symbol-35">
                                <span class="symbol-label font-size-h5 font-weight-bold text-white bg-white">
                                    <img src='{{str_replace("public", "public_html", asset('images/'.Session::get('company_app_logo')))}}' height='30px' alt="Company Logo"/>
                                </span>

                            </span>
                            <span class="pulse-ring"></span>
                        </div>
                    </div>
                    <!--end::Toggle-->
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                        <form>
                            <!--begin::Content-->
                            <div class="tab-content">
                                <!--begin::Tabpane-->
                                <div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">
                                    <!--begin::Scroll-->
                                    <div class="scroll pr-7 mr-n7" data-scroll="true" data-height="300" data-mobile-height="200">
                                        <h5>Company Selector</h5><br />
                                        <!--begin::Item-->
                                        @if(Auth::user()->username != 'cypher')<!--c4only-->
                                            @foreach(Session::get('comp_user') as $k => $valK)
                                                @foreach($comp as $value)
                                                    @if($value->id == $valK)
                                                        <div class="d-flex align-items-center mb-6">
                                                            <div class="symbol symbol-40 symbol-light-primary mr-5">
                                                                <span class="symbol-label">
                                                                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                                                                        <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' height='30px' alt="Company Logo"/>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                            <div class="d-flex flex-column font-weight-bold">
                                                                <a href="{{($value->id == 27 ? 'http://danuarthaelectrindosinergi.com/' : URL::route('company.switch')."?id=".$value->id)}}" class="text-dark text-hover-primary mb-1 font-size-lg">
                                                                    {{$value->company_name}}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @else
                                            @foreach($comp as $value)
                                                <div class="d-flex align-items-center mb-6">
                                                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                                                                <span class="symbol-label">
                                                                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                                                                        <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' height='30px' alt="Company Logo"/>
                                                                    </span>
                                                                </span>
                                                    </div>
                                                    <div class="d-flex flex-column font-weight-bold">
                                                        <a href="{{($value->id == 27 ? 'http://danuarthaelectrindosinergi.com/' : URL::route('company.switch')."?id=".$value->id)}}" class="text-dark text-hover-primary mb-1 font-size-lg">
                                                            {{$value->company_name}}
                                                        </a>
                                                    </div>
                                                </div>

                                            @endforeach
                                        @endif

                                    <!--end::Item-->
                                    </div>
                                    <!--end::Scroll-->
                                    <!--begin::Action-->
                                    <div class="d-flex flex-center pt-7">
                                        <a href="#" class="btn btn-light-primary font-weight-bold text-center">See All</a>
                                    </div>
                                    <!--end::Action-->
                                </div>
                                <!--end::Tabpane-->
                            </div>
                            <!--end::Content-->
                        </form>
                    </div>
                </div> --}}
                @endif
            @endauth
            <!--begin::Theme mode-->
            @if (\Config::get('constants.PORTAL_STATE') != 3)
                {{-- <div class="d-flex align-items-center ms-1">
                    <!--begin::Menu toggle-->
                    <select name="change_locale" id="change_locale" class="form-select form-select-transparent">
                        <option value="en" {{ $app_locale == 'en' ? 'SELECTED' : '' }}
                            data-kt-select2-country="{{ asset('theme/assets/media/flags/united-kingdom.svg') }}">
                            English
                        </option>
                        <option value="id" {{ $app_locale == 'id' ? 'SELECTED' : '' }}
                            data-kt-select2-country="{{ asset('theme/assets/media/flags/indonesia.svg') }}">Indonesia
                        </option>
                    </select>
                    <!--end::Menu-->
                </div> --}}
            @endif
            @auth
                <div class="mx-md-10 d-none">
                    <button type="button" class="btn text-hover-primary position-relative" data-kt-menu-trigger="click" onclick="getNotifications()"
                        data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                        <i class="bi bi-bell fs-md-3 fs-2tx text-primary"></i>
                        <span class="badge badge-circle badge-danger badge-sm ms-n3 position-absolute top-0" style="display: none" id="notification-count"></span>
                    </button>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Notifications</div>
                        </div>
                        <!--end::Menu item-->

                        <!--begin::Menu separator-->
                        <div class="separator mb-3 opacity-75"></div>
                        <!--end::Menu separator-->

                        <div class="notif-content">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <div class="menu-content fs-6 text-dark px-3 py-4">No Notifications</div>
                            </div>
                            <!--end::Menu item-->
                        </div>
                    </div>
                </div>
            @endauth
            <!--end::Theme mode-->
            @actionStart('preferences', 'access')
            @if (\Config::get("constants.IS_BP") == 1)
            @endif
            @actionEnd
            @auth
                <!--begin::User-->
                <div class="d-flex align-items-center ms-1" id="kt_header_user_menu_toggle">
                    <!--begin::User info-->
                    <div class="btn btn-flex align-items-center bg-hover-white bg-hover-opacity-10 py-2 px-2 px-md-3"
                        data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <!--begin::Name-->
                        <div class="d-none d-md-flex flex-column align-items-end justify-content-center me-2 me-md-4">
                            <span class="fw-bold fs-8 lh-1 mb-1">{{ Auth::user()->name }}</span>
                            <span class="text-muted fs-8 fw-bold lh-1">{{ Auth::user()->email }}</span>
                        </div>
                        <!--end::Name-->
                        <!--begin::Symbol-->
                        <div class="symbol symbol-30px symbol-md-40px">
                            <div class="symbol-label" style="background-image:url('{{ asset(Auth::user()->user_img ?? 'theme/assets/media/avatars/blank.png') }}')"></div>
                        </div>
                        <!--end::Symbol-->
                    </div>
                    <!--end::User info-->
                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px me-5">
                                    <div class="symbol-label" style="background-image:url('{{ asset(Auth::user()->user_img ?? 'theme/assets/media/avatars/blank.png') }}')"></div>
                                </div>
                                <!--end::Avatar-->
                                <!--begin::Username-->
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}</div>
                                    {{-- <a href="#"
                                        class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->role->name }}</a> --}}
                                </div>
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                        <!--end::Menu item-->
                        @if (Auth::user()->hasVerifiedEmail())
                            @if (\Config::get('constants.IS_BP') == 0)
                                @if(\Config::get("constants.PORTAL_STATE") != 3 && \Config::get("constants.PORTAL_STATE") != 4)
                                    @if(Session::get("login_state") == "applicant")
                                        <!--begin::Menu item-->
                                        {{-- <div class="menu-item px-5">
                                            <a href="{{ route('account.info') }}" class="menu-link px-5">
                                                <span class="menu-title">Lihat Profile</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-5">
                                            <a href="{{ route('account.my_applicant') }}" class="menu-link px-5">
                                                <span class="menu-title">Lamaran Saya</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                        </div> --}}
                                    @endif
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    {{-- <div class="menu-item px-5">
                                        <a href="{{ Session::get("login_state") == "applicant" ? route('account.my_bookmark') : route("search_talent.bookmark") }}" class="menu-link px-5">
                                            <span class="menu-title">Bookmark</span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                    </div> --}}
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    @if(\Config::get("constants.IS_TRIAL") == 0)
                                        <div class="menu-item px-5">
                                            <a href="{{ route('account.setting') }}" class="menu-link px-5">
                                                <span class="menu-title">Pengaturan</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                        </div>
                                    @endif
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    {{-- <div class="menu-item px-5">
                                        <a href="https://helpdesk.kerjaku.cloud/help-center" class="menu-link px-5">
                                            <span class="menu-title">FAQ</span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                    </div> --}}
                                    <!--end::Menu item-->
                                @endif
                                @if(\Config::get("constants.PORTAL_STATE") == 3 || \Config::get("constants.PORTAL_STATE") == 4)
                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="{{ str_replace("ess", "hris", route('account.setting')) }}" class="menu-link px-5">
                                        <span class="menu-title">Pengaturan</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                @endif
                                <!--begin::Menu item-->
                                {{-- <div class="menu-item px-5">
                                    <a href="{{ route("term.page") }}" class="menu-link px-5">
                                        <span class="menu-title">Syarat & Ketentuan</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                </div> --}}
                                <!--end::Menu item-->
                                <!--begin::Menu separator-->
                                @if(\Config::get("constants.IS_TRIAL") == 0)
                                <div class="separator my-2"></div>
                                @endif
                                <!--end::Menu separator-->
                            @endif
                        @endif
                        <!--begin::Menu item-->
                        @if(\Config::get("constants.IS_TRIAL") == 0)
                        <div class="menu-item px-5">
                            <form action="{{ route('logout') }}" class="px-5 menu-link" method="POST">
                                @csrf
                                @if (\Illuminate\Support\Facades\Session::get('login_dashboard'))
                                    <input type="hidden" name="dashboard"
                                        value="{{ strtolower(\Session::get('login_dashboard')) }}">
                                @endif
                                @if (\Illuminate\Support\Facades\Session::get('is_mobile'))
                                    <input type="hidden" name="ismobile" value="1">
                                @else
                                    <input type="hidden" name="ismobile" value="0">
                                @endif
                                <button type="submit" class="btn btn-link menu-link menu-center text-danger">
                                    Keluar
                                </button>
                            </form>
                        </div>
                        @endif
                        <!--end::Menu item-->
                    </div>
                    <!--end::User account menu-->
                </div>
                <!--end::User -->
            @endauth
            @guest
                <div class="d-flex align-items-center flex-shrink-0">
                    <div class="d-flex align-items-center ms-1">
                        {{-- begin:Login --}}
                        <a href="{{ \Config::get("constants.PORTAL_HOST") . '/login' }}"
                            class="btn btn-outline btn-outline-primary btn-sm me-1">
                            {{ __('Masuk') }}
                        </a>
                        {{-- end:Login --}}
                        {{-- begin:Register --}}
                        <a href="{{ route("register") }}" class="btn btn-primary btn-sm">
                            {{ __('Daftar') }}
                        </a>
                        {{-- end:Register --}}
                    </div>
                </div>
            @endguest
            <!--begin::Aside toggle-->
            <div class="d-lg-none btn btn-icon btn-active-color-white w-30px h-30px ms-5 me-3" id="menu_aside_toggle">
                <i class="fa fa-bars text-dark fs-2tx">
                </i>
            </div>
            <!--end::Aside  toggle-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
    @if (!empty($sub_head))
        <!--begin::Container-->
        <div class="container-fluid w-100 d-flex flex-md-stack flex-column mb-5 mb-md-0">
            @if ($sub_head == 'job')
                {{-- being::nav --}}
                <div class="row w-100 mb-5 mb-md-0">
                    <div class="col-md-7 col-sm-12">
                        <!--begin::Input group-->
                        <div class="input-group border rounded mb-5">
                            <span class="input-group-text border-0 bg-transparent" id="basic-addon1">
                                <i class="fi fi-rr-search"></i>
                            </span>
                            <input type="text" class="form-control border-0" id="search-job-input"
                                placeholder="Cari lowongan kerja disini" aria-label="Cari lowongan kerja disini"
                                aria-describedby="basic-addon1" />
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <!--begin::Input group-->
                        <div class="input-group border rounded mb-5 pe-2">
                            <span class="input-group-text border-0 bg-transparent" id="basic-addon2">
                                <i class="fi fi-rr-map-marker-home"></i>
                            </span>
                            <input type="text" class="form-control border-0" id="search-location-input"
                                placeholder="Lokasi" aria-label="Lokasi" aria-describedby="basic-addon2" />
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="col-md-1 col-sm-12">
                        <button type="button" id="search-button" class="btn btn-primary text-nowrap w-100 w-md-100">
                            <i class="fi fi-rr-search"></i>
                            Cari
                        </button>
                    </div>
                </div>
                {{-- end::nav --}}
                @if (isset($menuFilter))
                    @if ($menuFilter == "job")
                    <div class="d-inline d-md-none filter-mobile">
                        <div class="d-flex scroll hover-scroll align-items-center">
                            <!--begin::Menu-->
                            <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <!--begin::Menu link-->
                                    <a href="javascript:;" class="menu-link py-3">
                                        <span class="menu-title text-nowrap fs-6">Lokasi</span>
                                        <span class="fa fa-chevron-down fs-9 ms-3"></span>
                                    </a>
                                    <!--end::Menu link-->

                                    <!--begin::Menu sub-->
                                    <div class="menu-sub menu-sub-dropdown p-3 w-200px mh-300px scroll">
                                        <!--end::Menu item-->
                                        @foreach ($dataFilter['flokasi'] as $id => $item)
                                            <!--begin::Menu item-->
                                            <div class="menu-item">
                                                <div class="menu-content px-0">
                                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" {{ in_array($id, $locSelected) ? "checked" : "" }}  value="{{ ucwords($item) }}" name="filter_lokasi[]" onchange="search_job(true)" id="mobileck{{ str_replace(" ", "_", $item) }}" />
                                                        <label class="cursor-pointer form-check-label" for="mobileck{{ str_replace(" ", "_", $item) }}">
                                                            {{ ucwords($item) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Menu item-->
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!--begin::Menu-->
                            <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <!--begin::Menu link-->
                                    <a href="javascript:;" class="menu-link py-3">
                                        <span class="menu-title text-nowrap fs-6">Spesialisasi</span>
                                        <span class="fa fa-chevron-down fs-9 ms-3"></span>
                                    </a>
                                    <!--end::Menu link-->

                                    <!--begin::Menu sub-->
                                    <div class="menu-sub menu-sub-dropdown p-3 w-200px mh-300px scroll">
                                        <!--end::Menu item-->
                                        @foreach ($dataFilter['fspec'] as $id=> $item)
                                            <!--begin::Menu item-->
                                            <div class="menu-item">
                                                <div class="menu-content px-0">
                                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ $id }}" {{ in_array($id, $specSelected) ? "checked" : "" }} name="filter_spec[]" onchange="search_job(true)" id="mobileck{{ str_replace(" ", "_", $item) }}" />
                                                        <label class="cursor-pointer form-check-label" for="mobileck{{ str_replace(" ", "_", $item) }}">
                                                            {{ ucwords($item) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Menu item-->
                                        @endforeach
                                        <!--end::Menu item-->
                                    </div>
                                </div>
                            </div>
                            <!--begin::Menu-->
                            <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <!--begin::Menu link-->
                                    <a href="javascript:;" class="menu-link py-3">
                                        <span class="menu-title text-nowrap fs-6">Tipe Kepegawaian</span>
                                        <span class="fa fa-chevron-down fs-9 ms-3"></span>
                                    </a>
                                    <!--end::Menu link-->

                                    <!--begin::Menu sub-->
                                    <div class="menu-sub menu-sub-dropdown p-3 w-200px">
                                        <!--begin::Menu item-->
                                        @foreach ($dataFilter['ftype'] as $item)
                                            <!--begin::Menu item-->
                                            <div class="menu-item">
                                                <div class="menu-content px-0">
                                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ ucwords($item) }}" name="filter_type[]" onchange="search_job(true)" id="mobileck{{ str_replace(" ", "_", $item) }}" />
                                                        <label class="cursor-pointer form-check-label" for="mobileck{{ str_replace(" ", "_", $item) }}">
                                                            {{ ucwords($item) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Menu item-->
                                        @endforeach
                                        <!--end::Menu item-->
                                    </div>
                                </div>
                            </div>
                            <!--begin::Menu-->
                            <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <!--begin::Menu link-->
                                    <a href="javascript:;" class="menu-link py-3">
                                        <span class="menu-title text-nowrap fs-6">Gaji</span>
                                        <span class="fa fa-chevron-down fs-9 ms-3"></span>
                                    </a>
                                    <!--end::Menu link-->

                                    <!--begin::Menu sub-->
                                    <div class="menu-sub menu-sub-dropdown p-3 w-200px">
                                        <!--begin::Menu item-->
                                        <div class="menu-item">
                                            <div class="menu-content px-0">
                                                <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                    <input class="cursor-pointer form-check-input ck-filter" name="filter_salary[]" onchange="search_job(true)" type="checkbox" value="1.000.000 - 5.000.000" id="ck1000" />
                                                    <label class="cursor-pointer form-check-label" for="ck1000">
                                                        1.000.000 - 5.000.000
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item">
                                            <div class="menu-content px-0">
                                                <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                    <input class="cursor-pointer form-check-input ck-filter" name="filter_salary[]" onchange="search_job(true)" type="checkbox" value="5.000.000 - 10.000.000" id="ck5000" />
                                                    <label class="cursor-pointer form-check-label" for="ck5000">
                                                        5.000.000 - 10.000.000
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item">
                                            <div class="menu-content px-0">
                                                <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                    <input class="cursor-pointer form-check-input ck-filter" name="filter_salary[]" onchange="search_job(true)" type="checkbox" value="> 10.000.000" id="ckMore" />
                                                    <label class="cursor-pointer form-check-label" for="ckMore">
                                                        > 10.000.000
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                </div>
                            </div>
                            <!--begin::Menu-->
                            <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <!--begin::Menu link-->
                                    <a href="javascript:;" class="menu-link py-3">
                                        <span class="menu-title text-nowrap fs-6">Edukasi</span>
                                        <span class="fa fa-chevron-down fs-9 ms-3"></span>
                                    </a>
                                    <!--end::Menu link-->

                                    <!--begin::Menu sub-->
                                    <div class="menu-sub menu-sub-dropdown p-3 w-200px">
                                        @foreach ($dataFilter['fedu'] as $item)
                                            <!--begin::Menu item-->
                                            <div class="menu-item">
                                                <div class="menu-content px-0">
                                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ ucwords($item) }}" name="filter_edu[]" onchange="search_job(true)" id="mobileck{{ str_replace(" ", "_", $item) }}" />
                                                        <label class="cursor-pointer form-check-label" for="mobileck{{ str_replace(" ", "_", $item) }}">
                                                            {{ ucwords($item) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end::Menu item-->
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="d-flex align-items-baseline d-none mt-5">
                        <span class="fw-semibold me-3">Filter :</span>
                        <div id="menu-filter-mobile"></div>
                    </div>
                @endif
            @endif
        </div>
        <!--end::Container-->
    @endif
</div>
