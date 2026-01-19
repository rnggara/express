<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../" />
    <title>{{ env('APP_NAME') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{asset(\Config::get("constants.APP_ICON"))}}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('theme/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/assets/css/variables.css') }}" type="text/css">
    @include('layouts.styles')
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>

    @yield('css')

</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="auth-bg">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root bg-white">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-md-10 order-2 order-lg-1 bg-white">
                <!--begin::Form-->
                @yield('content')
                <!--end::Form-->
            </div>
            <!--end::Body-->
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2 bg-white rside d-none d-md-inline"
                style="background-image: url({{ isset($img) ? $img : asset('images/bg.jpg') }}); background-size: {{ isset($img_size) ? $img_size : 'cover' }}; background-repeat: no-repeat;">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                    <!--begin::Footer-->
                    @if (!isset($offCarousel))
                    <div class="d-flex position-absolute px-10 top-0 w-lg-500px mt-10" style="right: 0">
                        <!--begin::Languages-->
                        {{-- <div class="me-20">
                            <button type="button"
                                class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                <span class="d-none d-md-inline text-nowrap text-white">{{ __('Pengembangan Diri') }}</span>
                                <span class="d-flex flex-center rotate-180">
                                    <i class="ki-duotone ki-down fs-5 text-white m-0"></i>
                                </span>
                            </button>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3 d-flex">
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
                                            <span>Test</span>
                                            <span class="text-muted">Ujilah kemampuan profesionalmu</span>
                                        </div>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </div> --}}
                        {{-- <div class="me-10">
                            <!--begin::Toggle-->
                            <button
                                class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base text-white"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
                                data-kt-menu-offset="0px, 0px">
                                <img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3"
                                    src="{{ asset('theme/assets/media/flags/' . ($app_locale == 'id' ? 'indonesia' : 'united-states') . '.svg') }}"
                                    alt="" />
                                <span data-kt-element="current-lang-name"
                                    class="me-1">{{ $app_locale == 'id' ? 'Indonesia' : 'English' }}</span>
                                <span class="d-flex flex-center rotate-180">
                                    <i class="ki-duotone ki-down fs-5 text-white m-0"></i>
                                </span>
                            </button>
                            <!--end::Toggle-->
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-4 fs-7"
                                data-kt-menu="true" id="kt_auth_lang_menu">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="javascript:;change_locale('en')" class="menu-link d-flex px-5"
                                        data-kt-lang="English">
                                        <span class="symbol symbol-20px me-4">
                                            <img data-kt-element="lang-flag" class="rounded-1"
                                                src="{{ asset('theme/assets/media/flags/united-states.svg') }}"
                                                alt="" />
                                        </span>
                                        <span data-kt-element="lang-name">English</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="javascript:;change_locale('id')" class="menu-link d-flex px-5"
                                        data-kt-lang="Indonesia">
                                        <span class="symbol symbol-20px me-4">
                                            <img data-kt-element="lang-flag" class="rounded-1"
                                                src="{{ asset('theme/assets/media/flags/indonesia.svg') }}"
                                                alt="" />
                                        </span>
                                        <span data-kt-element="lang-name">Indonesia</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </div> --}}
                        <!--end::Languages-->
                    </div>
                    @endif
                    <!--end::Footer-->
                    <!--begin::Logo-->
                    {{-- <a href="../../demo14/dist/index.html" class="mb-0 mb-lg-12">
							<img alt="Logo" src="assets/media/logos/custom-1.png" class="h-60px h-lg-75px" />
						</a> --}}
                    <!--end::Logo-->
                    <!--begin::Image-->
                    {{-- <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20"
                        src="assets/media/misc/auth-screens.png" alt="" /> --}}
                    <!--end::Image-->
                    @if (isset($page_title))
                        <div class="d-flex align-items-center mb-5">
                            <img src="{{ $page_title['image'] ?? "" }}" class="h-70px" alt="">
                            <span class="fs-2tx fw-bold text-white">{{ $page_title['title'] ?? "" }}</span>
                        </div>
                        <h1 class="d-none d-lg-block text-white fs-5 fw-semibold text-center mb-7">{{ $page_title['subtitle'] ?? "" }}</h1>
                    @endif
                    @if (!isset($offCarousel))
                        <!--begin::Title-->
                        {{-- <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Bingung cari kerja?
                        </h1>
                        <!--end::Title-->
                        <!--begin::Text-->
                        <div id="kt_carousel_1_carousel" class="carousel carousel-custom slide"
                            data-bs-ride="carousel" data-bs-interval="8000">
                            <!--begin::Heading-->
                            <div class="d-flex align-items-center justify-content-center flex-wrap">
                                <!--begin::Label-->
                                <!--end::Label-->

                                <!--begin::Carousel Indicators-->
                                <ol class="p-0 m-0 carousel-indicators carousel-indicators-dots">
                                    <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="0"
                                        class="ms-1 active"></li>
                                    <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="1" class="ms-1">
                                    </li>
                                    <li data-bs-target="#kt_carousel_1_carousel" data-bs-slide-to="2" class="ms-1">
                                    </li>
                                </ol>
                                <!--end::Carousel Indicators-->
                            </div>
                            <!--end::Heading-->

                            <!--begin::Carousel-->
                            <div class="carousel-inner pt-8">
                                <!--begin::Item-->
                                <div class="carousel-item active">
                                    <div class="d-none d-lg-block text-white fs-base text-center">
                                        Telusuri katalog lowongan kami untuk posisi yang cocok untukmu
                                    </div>
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="carousel-item">
                                    <div class="d-none d-lg-block text-white fs-base text-center">
                                        Lorem Ipsum
                                    </div>
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="carousel-item">
                                    <div class="d-none d-lg-block text-white fs-base text-center">
                                        Dolor amet
                                    </div>
                                </div>
                                <!--end::Item-->
                            </div> --}}
                            <!--end::Carousel-->
                        </div>
                        <!--end::Text-->
                    @endif
                </div>
                <!--end::Content-->
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--end::Main-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset("theme/assets/plugins/global/plugins.bundle.js") }}"></script>
    <script src="{{ asset("theme/assets/js/scripts.bundle.js") }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset("theme/assets/plugins/custom/fslightbox/fslightbox.bundle.js") }}"></script>
    <script src="{{ asset("theme/assets/plugins/custom/datatables/datatables.bundle.js") }}"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset("theme/assets/js/widgets.bundle.js") }}"></script>
    <script src="{{ asset("theme/assets/js/custom/widgets.js") }}"></script>
    <script src="{{ asset("theme/assets/js/custom/apps/chat/chat.js") }}"></script>
    <script src="{{ asset("theme/assets/js/custom/utilities/modals/create-app.js") }}"></script>
    <script src="{{ asset("theme/assets/js/custom/utilities/modals/users-search.js") }}"></script>
    <script src="{{ asset("theme/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js") }}"></script>
    <script>
        var HOST_URL = "{{ URL::to('/') }}";
    </script>
    @include('layouts._scripts')
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    @yield('custom_script')
    <script>
        $("input[type=radio]").click(function() {
            var id = $(this).attr("id")
            $("label.role").removeClass("btn-light-primary text-primary")
            $("label.role").addClass("btn-outline btn-outline-primary text-dark")
            $("label.role").find("i").addClass("text-dark")
            $(`label[for=${id}]`).removeClass("btn-outline btn-outline-primary text-dark")
            $(`label[for=${id}]`).addClass("btn-light-primary text-primary")
            $(`label[for=${id}]`).find("i").removeClass("text-dark")
            $("#kt_sign_up_form").hide()
            $("#kt_stepper_example_basic").hide()
            $("#lbl-emp").hide()
            $("#lbl-app").hide()
            if($(this).val() == "employer"){
                $("#kt_stepper_example_basic").show()
                $("#lbl-emp").show()
            } else {
                $("#kt_sign_up_form").show()
                $("#lbl-app").show()
            }
        })
        $(document).ready(function() {
            $("input.role").each(function() {
                var checked = this.checked
                if (checked) {
                    // console.log($(this).val())
                    var id = $(this).attr("id")
                    $("label.role").removeClass("btn-light-primary text-primary")
                    $("label.role").addClass("btn-outline btn-outline-primary text-dark")
                    $("label.role").find("i").addClass("text-dark")
                    $(`label[for=${id}]`).removeClass("btn-outline btn-outline-primary text-dark")
                    $(`label[for=${id}]`).addClass("btn-light-primary text-primary")
                    $(`label[for=${id}]`).find("i").removeClass("text-dark")
                }
            })

            var _locale = "{{ $locale ?? '' }}"
            $("#locale").val(_locale != "" ? _locale : window.navigator.languages[1])
        })

        function change_locale(val) {
            $.ajax({
                url: window.HOST_URL + "/locale-switch",
                type: "post",
                dataType: "json",
                data: {
                    locale: val
                },
                success: function(resp) {
                    location.reload()
                }
            })
        }
    </script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
