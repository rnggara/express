<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic
Product Version: 8.2.0
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="id">
	<!--begin::Head-->
	<head><base href="../"/>
		<title>{{ \Config::get("constants.APP_LABEL") }}</title>
		<meta charset="utf-8" />
		{{-- <meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
		<meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" /> --}}
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		{{-- <meta property="og:locale" content="en_US" /> --}}
		{{-- <meta property="og:type" content="article" /> --}}
		{{-- <meta property="og:title" content="Metronic - Bootstrap Admin Template, HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template" /> --}}
		{{-- <meta property="og:url" content="https://keenthemes.com/metronic" /> --}}
		{{-- <meta property="og:site_name" content="Keenthemes | Metronic" /> --}}
		<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
		<link rel="shortcut icon" href="{{ asset(\Config::get("constants.APP_ICON")) }}" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="{{ asset("theme/assets/plugins/custom/datatables/datatables.bundle.css") }}" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ asset("theme/assets/plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset("theme/assets/css/style.bundle.css") }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset("theme/assets/css/variables.css") }}" type="text/css">
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed">
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
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid bg-white" style="padding-left: 0px" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" class="header bg-white min-h-75px h-auto" style="box-shadow: none; position: absolute">
                        <!--begin::Container-->
                        <div class="container-fluid d-flex flex-stack min-h-75px">
                            <!--begin::Brand-->
                            <div class="d-flex align-items-center me-5">
                                <!--begin::Aside toggle-->
                                <div class="d-lg-none btn btn-icon btn-active-color-white w-30px h-30px ms-n2 me-3" id="kt_aside_toggle">
                                    <i class="ki-duotone ki-abstract-14 fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                                <!--end::Aside  toggle-->
                                <!--begin::Logo-->
                                <a href="/">
                                    <img alt="Logo" src="{{ asset(\Config::get("constants.APP_ICON")) }}"
                                        class="h-25px h-lg-30px" />
                                    <h3 class="d-none d-md-inline">{{ env('APP_LABEL') }}</h3>
                                </a>
                                <!--end::Logo-->
                            </div>
                            <!--end::Brand-->
                        </div>
                    </div>

					<!--end::Header-->
					<!--begin::Content wrapper-->
					<div class="d-flex flex-column-fluid">
						<!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid" style="padding: 0 70px!important">
							<!--begin::Post-->
							<div class="content flex-column-fluid container mb-10" id="kt_content">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ asset("images/verified_employer.png") }}" class="w-400px mb-10" alt="">
                                    <span class="fs-1 fw-bold mb-5">Registrasi Sukses</span>
                                    <span class="text-center mb-10">Silahkan lengkapi profilmu semenarik mungkin untuk digunakan sebagai resume kamu,<br>ikuti tes untuk mendapat penilaian lebih dan segera dapatkan pekerjaan impianmu</span>
                                    <div class="d-flex">
                                        <a href="/" class="btn btn-primary me-10">
                                            Buat Job Ad
                                        </a>
                                        <a href="{{ route("account.setting") }}?v=company_profile" class="btn btn-outline btn-outline-primary text-primary">
                                            Complete Profile Perusahaan
                                        </a>
                                    </div>
                                </div>
							</div>
							<!--end::Post-->
						</div>
						<!--end::Container-->
					</div>
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->
		<!--end::Main-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Modals-->
		<!--end::Modals-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
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
        <script src="{{ asset("assets/jquery-number/jquery.number.min.js") }}"></script>
        <script>

        </script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
