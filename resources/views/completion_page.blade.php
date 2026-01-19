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
		<script type="text/javascript">
			(function(c,l,a,r,i,t,y){
				c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
				t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
				y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
			})(window, document, "clarity", "script", "jfgpyovjyx");
		</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed">
		<script type="text/javascript">
			(function(window, document, dataLayerName, id) {
			window[dataLayerName]=window[dataLayerName]||[],window[dataLayerName].push({start:(new Date).getTime(),event:"stg.start"});var scripts=document.getElementsByTagName('script')[0],tags=document.createElement('script');
			function stgCreateCookie(a,b,c){var d="";if(c){var e=new Date;e.setTime(e.getTime()+24*c*60*60*1e3),d="; expires="+e.toUTCString();f="; SameSite=Strict"}document.cookie=a+"="+b+d+f+"; path=/"}
			var isStgDebug=(window.location.href.match("stg_debug")||document.cookie.match("stg_debug"))&&!window.location.href.match("stg_disable_debug");stgCreateCookie("stg_debug",isStgDebug?1:"",isStgDebug?14:-1);
			var qP=[];dataLayerName!=="dataLayer"&&qP.push("data_layer_name="+dataLayerName),isStgDebug&&qP.push("stg_debug");var qPString=qP.length>0?("?"+qP.join("&")):"";
			tags.async=!0,tags.src="https://kerjaku.containers.piwik.pro/"+id+".js"+qPString,scripts.parentNode.insertBefore(tags,scripts);
			!function(a,n,i){a[n]=a[n]||{};for(var c=0;c<i.length;c++)!function(i){a[n][i]=a[n][i]||{},a[n][i].api=a[n][i].api||function(){var a=[].slice.call(arguments,0);"string"==typeof a[0]&&window[dataLayerName].push({event:n+"."+i+":"+a[0],parameters:[].slice.call(arguments,1)})}}(i[c])}(window,"ppms",["tm","cm"]);
			})(window, document, 'dataLayer', '8506bf83-6fb4-4771-b088-d969e75f28b1');
			</script>
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
							<div class="content flex-column-fluid mb-10" id="kt_content">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ asset("images/registration-complete.png") }}" class="w-md-400px w-100 mb-10" alt="">
                                    <span class="fs-1 fw-bold mb-5">Registrasi Sukses</span>
                                    <span class="text-center mb-10">Silahkan lengkapi profil Anda semenarik mungkin untuk digunakan sebagai resume.<br>Ikuti tes untuk mendapat penilaian lebih dan segera dapatkan pekerjaan impian Anda</span>
                                    <a href="{{ route("complete.profile.page") }}" class="btn btn-primary mb-10">
                                        Lengkapi Profile
                                    </a>
                                    <a href="{{ route("complete.profile.skip") }}" class="btn btn-link text-primary">
                                        Lewati dulu
                                    </a>
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
