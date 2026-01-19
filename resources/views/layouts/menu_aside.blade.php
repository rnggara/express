<div id="menu_aside_template" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#menu_aside_toggle" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
    data-kt-drawer-direction="start" data-kt-drawer-overlay="true">
    {{-- being::nav --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <div class="ms-5 ms-md-10 d-flex flex-column align-items-baseline">
                    <!--begin::Toggle-->
                    @if (\Config::get('constants.IS_BP') == 0)
                        @if (empty(Auth::user()) || (!empty(Auth::user()) && empty(Auth::user()->access)))
                            <a href="@auth{{ route('applicant.job.index') }}@else {{ route('applicant.job_guest.index') }} @endauth"
                                class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                                <span class="d-inline d-md-inline">@auth {{ __('Cari Pekerjaan') }}
                                    @else
                                    {{ __('Pekerjaan') }} @endauth
                                </span>
                            </a>
                        @endif
                        @if (empty(Auth::user()) || (!empty(Auth::user()) && empty(Auth::user()->access)))
                            <a href="@auth{{ route('app.cs.index') }}@else{{ route('app.cs_guest.index') }} @endauth"
                                class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                                <span class="d-inline d-md-inline">@auth {{ __('Cari Perusahaan') }}
                                    @else
                                    {{ __('Perusahaan') }} @endauth
                                </span>
                            </a>
                        @endif
                        @if (\Config::get('constants.PORTAL_STATE') != 3 && \Config::get("constants.PORTAL_STATE") != 4)
                            @auth
                                <a href="{{ url()->to('/') }}"
                                    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                                    <span class="d-inline d-md-inline">{{ __('Dashboard Saya') }}</span>
                                </a>
                            @endauth
                        @endif
                        @if (empty(Auth::user()) || (!empty(Auth::user()) && empty(Auth::user()->access)))
                            @auth
                                <div>
                                    <button type="button"
                                        class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <span class="d-inline d-md-inline">{{ __('Pengembangan Diri') }}</span>
                                        <i class="ki-duotone ki-down fs-4 ms-2 ms-md-3 me-0"></i>
                                    </button>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('artikel.index') }}" class="menu-link px-3 d-flex">
                                                <div class="symbol symbol-40px me-2">
                                                    <div class="symbol-label fw-semibold bg-light-success">
                                                        <i class="far fa-newspaper fs-2 spaper text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span>Artikel</span>
                                                    <span class="text-muted">Bacalah artikel tips/inspirasi karir</span>
                                                </div>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('test.page') }}" class="menu-link px-3 d-flex">
                                                <div class="symbol symbol-40px me-2">
                                                    <div class="symbol-label fw-semibold bg-light-primary">
                                                        <i class="fa fa-pen fs-2 text-white"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span>Tes</span>
                                                    <span class="text-muted">Ujilah kemampuan profesional Anda</span>
                                                </div>
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </div>
                            @endauth
                        @endif
                        @auth
                            @if (Auth::user()->access == 'EP' && \Config::get('constants.PORTAL_STATE') != 3 && \Config::get("constants.PORTAL_STATE") != 4)
                                <a href="{{ route('search_talent.index') }}"
                                    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                                    <span class="d-inline d-md-inline">{{ __('Cari Kandidat') }}</span>
                                </a>
                                <a href="{{ route('job_report.index') }}"
                                    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                                    <span class="d-inline d-md-inline">{{ __('Laporan Job Ad') }}</span>
                                </a>
                                <a href="{{ route('calendar.index') }}"
                                    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                                    <span class="d-inline d-md-inline">{{ __('Kalender') }}</span>
                                </a>
                                {{-- <a href="javascript:;"
                                                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                                                            <span class="d-inline d-md-inline">{{ __('Pembelian') }}</span>
                                                        </a> --}}
                            @endif
                            @if (Auth::user()->access == 'EP' && (\Config::get('constants.PORTAL_STATE') == 3 || \Config::get("constants.PORTAL_STATE") == 4))
                                @if(isset($menuCrm) && !empty($menuCrm))
                                    @include("layouts.aside_$menuCrm")
                                @else
                                @endif
                            @endif
                        @endauth
                    @endif
                    @if (\Config::get('constants.IS_BP') == 1)
                        @actionStart('management', 'access')
                        <a href="{{ route('home') }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-inline d-md-inline">{{ __('Dashboard') }}</span>
                        </a>
                        <a href="{{ route('bp.employers.index') }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-inline d-md-inline">{{ __('Employers') }}</span>
                        </a>
                        <a href="{{ route('bp.applicants.index') }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-inline d-md-inline">{{ __('Applicant') }}</span>
                        </a>
                        <a href="{{ route('bp.job_ads.index') }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span class="d-inline d-md-inline">{{ __('Job Ads') }}</span>
                        </a>
                        <div>
                            <button type="button"
                                class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <span class="d-inline d-md-inline">{{ __('Masters') }}</span>
                                <i class="ki-duotone ki-down fs-4 ms-2 ms-md-3 me-0"></i>
                            </button>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
                                data-kt-menu="true">
                                @actionStart('translations', 'access')
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ action('\Barryvdh\TranslationManager\Controller@getIndex') }}"
                                        class="menu-link px-3 d-flex">
                                        <span class="menu-title">Translations</span>

                                    </a>
                                </div>
                                <!--end::Menu item-->
                                @actionEnd
                                @actionStart('master_data', 'access')
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ route('master_data.index') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Master Data</span>

                                    </a>
                                </div>
                                <!--end::Menu item-->
                                @actionEnd
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ route('hrd.test.index') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Test Management</span>

                                    </a>
                                </div>
                                <!--end::Menu item-->
                                @actionStart('master_data', 'access')
                                <!--begin::Menu item-->
                                <div class="menu-item px-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="right-start">
                                    <a href="#" class="menu-link px-3 d-flex">
                                        <span class="menu-title">CMS</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <!--begin::Menu sub-->
                                    <div class="menu-sub menu-sub-dropdown w-auto py-4">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('cms.applicant.index') }}" class="menu-link px-3">
                                                Applicant Landing Page
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('cms.employer.index') }}" class="menu-link px-3">
                                                Employer Landing Page
                                            </a>
                                        </div>
                                    </div>
                                    <!--end::Menu sub-->
                                </div>
                                <!--end::Menu item-->
                                @actionEnd
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ route('pivot.index') }}" class="menu-link px-3 d-flex">
                                        <span class="menu-title">Pivot Table</span>

                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </div>
                        @actionEnd
                    @endif
                    @guest
                        <a href="{{ \Config::get('constants.PORTAL_STATE') == 2 ? \Config::get('constants.EMPLOYER_HOST') : \Config::get('constants.APPLICANT_HOST') }}"
                            class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center  mb-5 justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
                            <span
                                class="d-inline d-md-inline">{{ \Config::get('constants.PORTAL_STATE') == 2 ? __('Untuk Pemberi Kerja') : __('Untuk Pencari Kerja') }}</span>
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    {{-- end::nav --}}
</div>
