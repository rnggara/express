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
        <style>
            .select2-results__option[aria-disabled=true]
            {
                display: none;
            }
        </style>
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
					@if (Auth::user()->complete_profile == 1)
                        @include('layouts.header')
                    @else
                        <!--begin::Header-->
                        <div id="kt_header" class="header bg-white min-h-75px h-auto" style="box-shadow: none; position: absolute">
                            <!--begin::Container-->
                            <div class="container-fluid d-flex flex-stack min-h-75px">
                                <!--begin::Brand-->
                                <div class="d-flex align-items-center me-5">
                                    <!--begin::Logo-->
                                    <a href="/">
                                        <img alt="Logo" src="{{ asset(\Config::get("constants.APP_ICON")) }}"
                                            class="h-25px h-lg-30px" />
                                        <h3 class="d-none d-md-inline">{{ \Config::get("constants.APP_LABEL") }}</h3>
                                    </a>
                                    <!--end::Logo-->
                                </div>
                                <!--end::Brand-->
                            </div>
                        </div>
                    @endif

					<!--end::Header-->
					<!--begin::Content wrapper-->
					<div class="d-flex flex-column-fluid">
                        @if (Auth::user()->complete_profile == 1)
                            @include('layouts.menu_aside')
                        @endif
						<!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid">
							<!--begin::Post-->
							<div class="content flex-column-fluid mb-10" id="kt_content">
                                <div class="d-flex flex-column">
                                    <div class="d-flex flex-column align-items-center mb-10">
                                        <span class="fw-bold" style="font-size: 36px">Lengkapi Profil Anda!</span>
                                        <span>Profil yang menarik akan meningkatkan peluang Anda untuk mendapatkan pekerjaan impian Anda</span>
                                    </div>
                                    {{-- personal data --}}
                                    <div class="accordion mb-5">
                                        <div class="d-flex flex-column border rounded">
                                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px symbol-circle me-5">
                                                        <div class="symbol-label fs-2 fw-semibold text-primary" style="background-color: #E1D7FA" id="step1">1</div>
                                                    </div>
                                                    <span class="fw-bold">Personal Data</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-0 align-items-center">
                                                    {{-- <button type="button" class="btn btn-lewati text-primary" onclick="next_step(this, 1)">Lewati</button> --}}
                                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                        <div class="accordion-header" aria-controls="collapse1" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true">
                                                            <span class="accordion-icon">
                                                                <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse1" class="border-top accordion-collapse collapse show" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_content">
                                                <form action="{{ route("complete.profile.step")."?a=personal_data" }}" id="form-personal-data" class="form">
                                                    @csrf
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Nama Lengkap</label>
                                                                    <input type="text" name="name" class="form-control" required value="{{ $profile->name ?? $user->name }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Email</label>
                                                                    <input type="email" name="email" class="form-control" required value="{{ $profile->email ?? $user->email }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Nomor Telepon</label>
                                                                    <input type="text" class="form-control phone" placeholder="Input Nomor Telepon" name="phone" required value="{{ $profile->phone ??$user->phone }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Jenis Kelamin</label>
                                                                    <select name="gender" class="form-control" data-control="select2" data-hide-search="true" required>
                                                                        <option value="">Pilih Jenis Kelamin</option>
                                                                        @foreach ($gender as $item)
                                                                            <option value="{{ $item->name }}" {{ !empty($profile) && $profile->gender == $item->name ? "selected" : "" }}>{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Tanggal Lahir</label>
                                                                    <div class="input-group " id="inputDates" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                                        <input id="inputDates_input" placeholder="Pilih Tanggal Lahir" required name="birth_date" value="{{ empty($profile->birth_date) ? "" : date('d-m-Y', strtotime($profile->birth_date)) }}" type="text" class="form-control tempusDominus" data-td-target="#inputDates"/>
                                                                        <span class="input-group-text" data-td-target="#inputDates" data-td-toggle="datetimepicker">
                                                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Status Pernikahan</label>
                                                                    <select name="marital_status" class="form-control" data-control="select2" data-hide-search="true" required>
                                                                        <option value="">Pilih</option>
                                                                        @foreach ($marital_status as $item)
                                                                            <option value="{{ $item->name }}" {{ !empty($profile) && $profile->marital_status == $item->name ? "selected" : "" }}>{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Agama</label>
                                                                    <select name="religion" class="form-control" data-control="select2" data-hide-search="true" required>
                                                                        <option value="">Pilih</option>
                                                                        @foreach ($religion as $item)
                                                                            <option value="{{ $item->name }}" {{ !empty($profile) && $profile->religion == $item->name ? "selected" : "" }}>{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Kota</label>
                                                                    <select name="city_id" class="form-control" data-control="select2" required>
                                                                        <option value="">Pilih</option>
                                                                        @foreach ($city as $item)
                                                                            <option value="{{ $item->id }}" data-prov="{{ $item->prov_id }}" {{ !empty($profile) && $profile->city_id == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12">
                                                                <div class="fv-row">
                                                                    <label for="" class="col-form-label required">Provinsi</label>
                                                                    <select name="prov_id" class="form-control" data-control="select2" required>
                                                                        <option value="">Pilih</option>
                                                                        @foreach ($province as $item)
                                                                            <option value="{{ $item->id }}" {{ !empty($profile) && $profile->prov_id == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="fv-row">
                                                            <label for="" class="col-form-label required">Alamat Lengkap</label>
                                                            <textarea name="address" class="form-control" required id="pdalamat" cols="30" rows="10">{!! $profile->address ?? "" !!}</textarea>
                                                        </div>
                                                        <div class="fv-row">
                                                            <label for="" class="col-form-label required">Gaji yang diharapkan</label>
                                                            <input type="text" class="form-control number" name="salary_expect" value="{{ number_format($profile->salary_expect ?? 0, 0, ".", "") }}">
                                                        </div>
                                                        <div class="d-flex mt-10 justify-content-end">
                                                            <button type="button" class="btn btn-secondary" onclick="validate_form(this)" data-target="1">Selanjutnya</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- pengalaman kerja --}}
                                    <div class="accordion mb-5">
                                        <div class="d-flex flex-column border rounded">
                                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px symbol-circle me-5">
                                                        <div class="symbol-label fs-2 fw-semibold text-primary" style="background-color: #E1D7FA" id="step2">2</div>
                                                    </div>
                                                    <span class="fw-bold">Pengalaman Kerja</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-0 align-items-center">
                                                    <button type="button" class="btn btn-lewati text-primary" style="display: none;" onclick="next_step(this, 2)">Lewati</button>
                                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                        <div class="accordion-header collapsed" aria-controls="collapse2" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true">
                                                            <span class="accordion-icon">
                                                                <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse2" class="accordion-collapse collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_content">
                                                <form action="{{ route("complete.profile.step")."?a=pengalaman_kerja" }}" id="form-pengalaman_kerja" class="form">
                                                    @csrf
                                                    <div class="accordion-body">
                                                        <div class="duplicate">
                                                            <div class="dup-sec">
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Perusahaan</label>
                                                                            <input type="text" name="company_name[]" placeholder="Input nama perusahaan" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Gaji Terakhir</label>
                                                                            <input type="text" name="salary[]" placeholder="Input Gaji" class="form-control number">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Posisi</label>
                                                                            <input type="text" name="position[]" placeholder="Input Posisi" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Jabatan</label>
                                                                            <select name="job_level[]" data-control="select2" data-hide-search="true" class="form-control">
                                                                                <option value="">Pilih Jabatan</option>
                                                                                @foreach ($jabatan as $i => $item)
                                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Tipe Pekerjaan</label>
                                                                            <select name="job_type[]" class="form-control" data-control="select2" data-hide-search="true">
                                                                                <option value="">Pilih tipe pekerjaan</option>
                                                                                @foreach ($job_type as $i => $item)
                                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Spesialisasi</label>
                                                                            <select name="specialization[]" class="form-control" data-control="select2" data-hide-search="true">
                                                                                <option value="">Pilih spesialisasi</option>
                                                                                @foreach ($specialization as $i => $item)
                                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Industri</label>
                                                                            <select name="industry[]" class="form-control" data-control="select2">
                                                                                <option value="">Pilih Industri</option>
                                                                                @foreach ($industri as $i => $item)
                                                                                    <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->industry == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Periode Kerja</label>
                                                                            <div class="row">
                                                                                <div class="col-md-2 col-6">
                                                                                    <select name="start_month[]" data-control="select2" data-hide-search="true" class="form-control">
                                                                                        <option value="">Bulan</option>
                                                                                        @foreach ($idFullMonth as $i => $item)
                                                                                            <option value="{{ sprintf("%02d", $i) }}">{{ $item }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-2 col-6">
                                                                                    <select name="start_year[]" data-control="select2" data-hide-search="true" class="form-control">
                                                                                        <option value="">Tahun</option>
                                                                                        @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                                                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                                                        @endfor
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-4 my-3 my-md-0 col-sm-12 text-md-center">
                                                                                    Hingga
                                                                                </div>
                                                                                <div class="col-md-2 col-6">
                                                                                    <select name="end_month[]" data-control="select2" data-hide-search="true" class="form-control">
                                                                                        <option value="">Bulan</option>
                                                                                        @foreach ($idFullMonth as $i => $item)
                                                                                            <option value="{{ sprintf("%02d", $i) }}">{{ $item }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-2 col-6">
                                                                                    <select name="end_year[]" data-control="select2" data-hide-search="true" class="form-control">
                                                                                        <option value="">Tahun</option>
                                                                                        @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                                                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                                                        @endfor
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="form-group">
                                                                            <div class="checkbox-inline col-form-label">
                                                                                <label class="checkbox checkbox-success">
                                                                                    <input type="checkbox" class="ck-still" name="still[]"/>
                                                                                    <span></span>
                                                                                    Masih bekerja di sini
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Lokasi</label>
                                                                            <input type="text" name="location[]" placeholder="Input lokasi" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Deskripsi</label>
                                                                            <textarea name="descriptions[]" placeholder="Masukkan deskripsi pekerjaan" class="form-control" id="" cols="30" rows="10"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Prestasi Kerja <span class="text-muted">(optional)</span></label>
                                                                            <textarea name="achievements[]" placeholder="Masukkan prestasi kerja" class="form-control" id="" cols="30" rows="10"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Referensi</label>
                                                                            <input type="text" name="reference[]" placeholder="Input nama referensi" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Nomor Telepon</label>
                                                                            <input type="text" name="phone[]" placeholder="Input nomor telepon referensi" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Posisi / Jabatan</label>
                                                                            <input type="text" name="ref_pos[]" placeholder="Input nama posisi / jabatan" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="" class="col-form-label">Alasan resign</label>
                                                                            <input type="text" name="resign_reason[]" placeholder="Input alasan resign" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-center mt-10">
                                                            <button type="button" class="btn btn-link text-primary" onclick="duplicate_form(this)">
                                                                <i class="fa fa-plus-circle text-primary"></i>
                                                                Tambah Pengalaman
                                                            </button>
                                                        </div>
                                                        <div class="d-flex mt-10 justify-content-end">
                                                            <button type="button" class="btn btn-link text-primary me-10" onclick="kembali(2)">Kembali</button>
                                                            <button type="button" class="btn btn-secondary" onclick="validate_form(this)" data-target="2">Selanjutnya</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- pendidikan --}}
                                    <div class="accordion mb-5">
                                        <div class="d-flex flex-column border rounded">
                                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px symbol-circle me-5">
                                                        <div class="symbol-label fs-2 fw-semibold text-primary" style="background-color: #E1D7FA" id="step3">3</div>
                                                    </div>
                                                    <span class="fw-bold">Pendidikan</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-0 align-items-center">
                                                    <button type="button" class="btn btn-lewati text-primary" style="display: none;" onclick="next_step(this, 3)">Lewati</button>
                                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                        <div class="accordion-header collapsed" aria-controls="collapse3" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true">
                                                            <span class="accordion-icon">
                                                                <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse3" class="collapse accordion-collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_content">
                                                <form action="{{ route("complete.profile.step")."?a=pendidikan" }}" id="form-pendidikan" class="form">
                                                    @csrf
                                                    <div class="accordion-body">
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold mb-5">Pendidikan Formal</span>
                                                            <div class="duplicate">
                                                                <div class="dup-sec">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label ">Tingkat Pendidikan</label>
                                                                                <select name="degree[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                    <option value="">Pilih</option>
                                                                                    @foreach ($ledu as $item)
                                                                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label ">Jurusan</label>
                                                                                <input type="text" name="field_of_study[]" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label">IPK</label>
                                                                                <input type="text" name="grade[]" class="form-control number ipk" >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label ">Nama Institusi</label>
                                                                                <input type="text" name="school_name[]" class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label ">Masa Belajar</label>
                                                                                <div class="row">
                                                                                    <div class="col-md-2 col-6">
                                                                                        <select name="fo_start_month[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                            <option value="">Bulan</option>
                                                                                            @foreach ($idFullMonth as $i => $item)
                                                                                                <option value="{{ sprintf("%02d", $i) }}" {{ !empty($_detail) && date("n", strtotime($_detail->start_date)) == $i ? "SELECTED" : "" }}>{{ $item }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-2 col-6">
                                                                                        <select name="fo_start_year[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                            <option value="">Tahun</option>
                                                                                            @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                                                                                                <option value="{{ $i }}" {{ !empty($_detail) && date("Y", strtotime($_detail->start_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 col-sm-12 text-md-center my-3 my-md-0">
                                                                                        Hingga
                                                                                    </div>
                                                                                    <div class="col-md-2 col-6">
                                                                                        <select name="fo_end_month[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                            <option value="">Bulan</option>
                                                                                            @foreach ($idFullMonth as $i => $item)
                                                                                                <option value="{{ sprintf("%02d", $i) }}" {{ !empty($_detail) && date("n", strtotime($_detail->end_date)) == $i ? "SELECTED" : "" }}>{{ $item }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-2 col-6">
                                                                                        <select name="fo_end_year[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                            <option value="">Tahun</option>
                                                                                            @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                                                                                                <option value="{{ $i }}" {{ !empty($_detail) && date("Y", strtotime($_detail->end_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="checkbox-inline col-form-label">
                                                                                    <label class="checkbox checkbox-success">
                                                                                        <input type="checkbox" class="ck-still" name="fo_still[]"/>
                                                                                        <span></span>
                                                                                        Saat ini saya sedang mengejar gelar ini
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label">Deskripsi <span class="text-muted">(optional)</span></label>
                                                                                <textarea name="fo_descriptions[]" class="form-control" id="" cols="30" rows="10"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label col-12">Upload Dokumen</label>
                                                                                <label for="file-upload-pendidikan-formal" class="btn btn-secondary btn-sm">
                                                                                    <i class="flaticon-attachment"></i>
                                                                                    Attachments
                                                                                </label>
                                                                                <span class="text-muted">format : JPG, PNG, PDF</span>
                                                                                <input id="file-upload-pendidikan-formal" style="display: none" name="fo_attachments[]" accept=".jpg, .png, .pdf" type="file"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-center mt-10">
                                                                <button type="button" class="btn btn-link text-primary" onclick="duplicate_form(this)">
                                                                    <i class="fa fa-plus-circle text-primary"></i>
                                                                    Tambah Pendidikan Formal
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="separator separator-solid my-10"></div>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold mb-5">Pendidikan Informal</span>
                                                            <div class="duplicate">
                                                                <div class="dup-sec">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label ">Nama Pelatihan</label>
                                                                                <input type="text" name="course_name[]" class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label ">Penyelenggara</label>
                                                                                <input type="text" name="vendor[]" class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label ">Masa Pelatihan</label>
                                                                                <div class="row">
                                                                                    <div class="col-md-2 col-6">
                                                                                        <select name="info_start_month[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                            <option value="">Bulan</option>
                                                                                            @foreach ($idFullMonth as $i => $item)
                                                                                                <option value="{{ sprintf("%02d", $i) }}" {{ !empty($_detail) && date("n", strtotime($_detail->start_date)) == $i ? "SELECTED" : "" }}>{{ $item }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-2 col-6">
                                                                                        <select name="info_start_year[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                            <option value="">Tahun</option>
                                                                                            @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                                                                                                <option value="{{ $i }}" {{ !empty($_detail) && date("Y", strtotime($_detail->start_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-4 col-sm-12 text-md-center my-3 my-md-0">
                                                                                        Hingga
                                                                                    </div>
                                                                                    <div class="col-md-2 col-6">
                                                                                        <select name="info_end_month[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                            <option value="">Bulan</option>
                                                                                            @foreach ($idFullMonth as $i => $item)
                                                                                                <option value="{{ sprintf("%02d", $i) }}" {{ !empty($_detail) && date("n", strtotime($_detail->end_date)) == $i ? "SELECTED" : "" }}>{{ $item }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-2 col-6">
                                                                                        <select name="info_end_year[]" class="form-control"  data-control="select2" data-hide-search="true">
                                                                                            <option value="">Tahun</option>
                                                                                            @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                                                                                                <option value="{{ $i }}" {{ !empty($_detail) && date("Y", strtotime($_detail->end_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-sm-12">
                                                                            <div class="form-group">
                                                                                <div class="checkbox-inline col-form-label">
                                                                                    <label class="checkbox checkbox-success">
                                                                                        <input type="checkbox" class="ck-still" name="info_still[]"/>
                                                                                        <span></span>
                                                                                        Saat ini saya sedang mengejar gelar ini
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label">Deskripsi <span class="text-muted">(optional)</span></label>
                                                                                <textarea name="info_descriptions[]" class="form-control" id="" cols="30" rows="10"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-sm-12">
                                                                            <div class="form-group">
                                                                                <label for="" class="col-form-label col-12">Upload Dokumen</label>
                                                                                <label for="file-upload-pendidikan" class="btn btn-secondary btn-sm">
                                                                                    <i class="flaticon-attachment"></i>
                                                                                    Attachments
                                                                                </label>
                                                                                <span class="text-muted">format : JPG, PNG, PDF</span>
                                                                                <input id="file-upload-pendidikan" style="display: none" name="info_attachments[]" accept=".jpg, .png, .pdf" type="file"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-center mt-10">
                                                                <button type="button" class="btn btn-link text-primary" onclick="duplicate_form(this)">
                                                                    <i class="fa fa-plus-circle text-primary"></i>
                                                                    Tambah Pendidikan Informal
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mt-10 justify-content-end">
                                                            <button type="button" class="btn btn-link text-primary me-10" onclick="kembali(3)">Kembali</button>
                                                            <button type="button" class="btn btn-secondary" onclick="validate_form(this)" data-target="3">Selanjutnya</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- kemampuan bahasa --}}
                                    <div class="accordion mb-5">
                                        <div class="d-flex flex-column border rounded">
                                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px symbol-circle me-5">
                                                        <div class="symbol-label fs-2 fw-semibold text-primary" style="background-color: #E1D7FA" id="step4">4</div>
                                                    </div>
                                                    <span class="fw-bold">Kemampuan Bahasa</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-0 align-items-center">
                                                    <button type="button" class="btn btn-lewati text-primary" style="display: none;" onclick="next_step(this, 4)">Lewati</button>
                                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                        <div class="accordion-header collapsed" aria-controls="collapse4" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true">
                                                            <span class="accordion-icon">
                                                                <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse4" class="accordion-collapse collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_content">
                                                <form action="{{ route("complete.profile.step")."?a=kemampuan_bahasa" }}" id="form-kemampuan_bahasa" class="form">
                                                    @csrf
                                                    <div class="accordion-body">
                                                        <div class="duplicate">
                                                            <div class="dup-sec">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="language" class="col-form-label">Bahasa</label>
                                                                            <select name="language[]" data-control="select2" class="form-control">
                                                                                <option value="">Pilih Bahasa</option>
                                                                                @foreach ($languages as $item)
                                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="writing" class="col-form-label">Kemampuan Menulis</label>
                                                                            <select name="writing[]" data-control="select2" data-hide-search="true" class="form-select">
                                                                                <option value="">Kemampuan</option>
                                                                                @for ($item = 1; $item <= 5; $item++)
                                                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="speaking" class="col-form-label">Kemampuan Berbicara</label>
                                                                            <select name="speaking[]" data-control="select2" data-hide-search="true" class="form-select">
                                                                                <option value="">Kemampuan</option>
                                                                                @for ($item = 1; $item <= 5; $item++)
                                                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="reading" class="col-form-label">Kemampuan Membaca</label>
                                                                            <select name="reading[]" data-control="select2" data-hide-search="true" class="form-select">
                                                                                <option value="">Kemampuan</option>
                                                                                @for ($item = 1; $item <= 5; $item++)
                                                                                    <option value="{{ $item }}">{{ $item }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-center mt-10">
                                                            <button type="button" class="btn btn-link text-primary" onclick="duplicate_form(this)">
                                                                <i class="fa fa-plus-circle text-primary"></i>
                                                                Tambah Kemampuan Bahasa
                                                            </button>
                                                        </div>
                                                        <div class="d-flex mt-10 justify-content-end">
                                                            <button type="button" class="btn btn-link text-primary me-10" onclick="kembali(4)">Kembali</button>
                                                            <button type="button" class="btn btn-secondary" onclick="validate_form(this)" data-target="4">Selanjutnya</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- kemampuan --}}
                                    <div class="accordion mb-5">
                                        <div class="d-flex flex-column border rounded">
                                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px symbol-circle me-5">
                                                        <div class="symbol-label fs-2 fw-semibold text-primary" style="background-color: #E1D7FA" id="step5">5</div>
                                                    </div>
                                                    <span class="fw-bold">Kemampuan Khusus</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-0 align-items-center">
                                                    <button type="button" class="btn btn-lewati text-primary" style="display: none;" onclick="next_step(this, 5)">Lewati</button>
                                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                        <div class="accordion-header collapsed" aria-controls="collapse5" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="true">
                                                            <span class="accordion-icon">
                                                                <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse5" class="accordion-collapse collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_content">
                                                <form action="{{ route("complete.profile.step")."?a=kemampuan" }}" id="form-kemampuan" class="form">
                                                    @csrf
                                                    <div class="accordion-body">
                                                        <div class="duplicate">
                                                            <div class="dup-sec">
                                                                <div class="row">
                                                                    <div class="col-md-8 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="skill" class="col-form-label">Kemampuan Khusus</label>
                                                                            <input type="text" class="form-control" placeholder="Masukkan Kemampuan Khusus" name="skill_name[]">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-12">
                                                                        <div class="form-group">
                                                                            <label for="proficiency" class="col-form-label">Keahlian</label>
                                                                            <select name="proficiency[]" data-control="select2" data-hide-search="true" class="form-select">
                                                                                <option value="">Keahlian</option>
                                                                                @foreach ($proficiency as $item)
                                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-center mt-10">
                                                            <button type="button" class="btn btn-link text-primary" onclick="duplicate_form(this)">
                                                                <i class="fa fa-plus-circle text-primary"></i>
                                                                Tambah Kemampuan Khusus
                                                            </button>
                                                        </div>
                                                        <div class="d-flex mt-10 justify-content-end">
                                                            <button type="button" class="btn btn-link text-primary me-10" onclick="kembali(5)">Kembali</button>
                                                            <button type="button" class="btn btn-secondary" onclick="validate_form(this)" data-target="5">Selanjutnya</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- portofolio --}}
                                    <div class="accordion mb-5">
                                        <div class="d-flex flex-column border rounded">
                                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px symbol-circle me-5">
                                                        <div class="symbol-label fs-2 fw-semibold text-primary" style="background-color: #E1D7FA" id="step6">6</div>
                                                    </div>
                                                    <span class="fw-bold">Portofolio</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-0 align-items-center">
                                                    <button type="button" class="btn btn-lewati text-primary" style="display: none;" onclick="next_step(this, 6)">Lewati</button>
                                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                        <div class="accordion-header collapsed" aria-controls="collapse6" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="true">
                                                            <span class="accordion-icon">
                                                                <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse6" class="accordion-collapse collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_content">
                                                <form action="{{ route("complete.profile.step")."?a=portofolio" }}" id="form-portofolio" class="form">
                                                    @csrf
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Website</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="website" class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Behance</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="behance" class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Dribble</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="dribble" class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Github</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="github" class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Aplikasi Mobile</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="mobile" class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Link lain</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="others" class="form-control" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label col-12">Upload Dokumen</label>
                                                                    <label for="file-upload-porto" class="btn btn-secondary btn-sm">
                                                                        <i class="flaticon-attachment"></i>
                                                                        Attachments
                                                                    </label>
                                                                    <span class="text-muted">format : JPG, PNG, PDF</span>
                                                                    <input id="file-upload-porto" style="display: none" name="attachments" accept=".jpg, .png, .pdf" type="file"/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex mt-10 justify-content-end">
                                                            <button type="button" class="btn btn-link text-primary me-10" onclick="kembali(6)">Kembali</button>
                                                            <button type="button" class="btn btn-secondary" onclick="validate_form(this)" data-target="6">Selanjutnya</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- social media --}}
                                    <div class="accordion mb-5">
                                        <div class="d-flex flex-column border rounded">
                                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px symbol-circle me-5">
                                                        <div class="symbol-label fs-2 fw-semibold text-primary" style="background-color: #E1D7FA" id="step7">7</div>
                                                    </div>
                                                    <span class="fw-bold">Media Sosial</span>
                                                </div>
                                                <div class="d-flex justify-content-between border-0 align-items-center">
                                                    {{-- <button type="button" class=" btn-lewatibtn text-primary" style="display: none;" onclick="next_step(this, 3)">Lewati</button> --}}
                                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                        <div class="accordion-header collapsed" aria-controls="collapse7" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="true">
                                                            <span class="accordion-icon">
                                                                <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse7" class="accordion-collapse collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_content">
                                                <form action="{{ route("complete.profile.step")."?a=medsos" }}" id="form-medsos" class="form">
                                                    @csrf
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Linkedin</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="linkedin" class="form-control"  value="{{ $medsos->linkedin ?? "" }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Facebook</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="facebook" class="form-control"  value="{{ $medsos->facebook ?? "" }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Instagram</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="instagram" class="form-control"  value="{{ $medsos->instagram ?? "" }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Twitter</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="twitter" class="form-control"  value="{{ $medsos->twitter ?? "" }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="" class="col-form-label">Tiktok</label>
                                                                    <input type="text" placeholder="Masukkan Link" name="tiktok" class="form-control"  value="{{ $medsos->tiktok ?? "" }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex mt-10 justify-content-end">
                                                            <button type="button" class="btn btn-link text-primary me-10" onclick="kembali(7)">Kembali</button>
                                                            <button type="button" class="btn btn-secondary" onclick="validate_form(this)" data-target="7">Selanjutnya</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- end --}}
                                    <form action="{{ route("complete.profile.post") }}" method="post">
                                        @csrf
                                        <div class="form-check mt-5 {{ Auth::user()->complete_profile == 1 ? "d-none" : "" }}">
                                            <input class="form-check-input" name="agreement" required type="checkbox" {{ Auth::user()->complete_profile == 1 ? "checked" : "" }} value="agree" id="flexCheckDefault" />
                                            <label class="form-check-label" for="flexCheckDefault">
                                                <span>Saya telah membaca dan menyetujui <a href="#" target="_blank">Syarat</a> dan <a href="#" target="_blank">Ketentuan</a> Portal {{ \Config::get("constants.APP_LABEL") }}</span>
                                            </label>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" id="btn-submit" disabled class="btn btn-primary px-20">{{ Auth::user()->complete_profile == 1 ? "Simpan" : "Kirim" }}</button>
                                        </div>
                                    </form>
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
            // Format options

            var canSubmit = {{ Auth::user()->complete_profile == 1 ? "true" : "false" }}
            @if(Auth::user()->complete_profile == 1)
            $("#btn-submit").prop("disabled", false)
            @endif

            $(".accordion-header").click(function(){
                var collapse = $(this).parents(".accordion")
                isShow(collapse)
            })

            function isShow(collapse){
                $(".btn-lewati").hide()
                var cl = $(collapse).find(".accordion-collapse")
                $(collapse).on("shown.bs.collapse", function(){
                    $(collapse).find(".btn-lewati").show()
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $(collapse).offset().top
                    }, 1000);
                })
            }

            $(".accordion-collapse").on("shown", function(){
                console.log("hello")
            })

            var optionFormat = function(item) {
                if ( !item.id ) {
                    return item.text;
                }

                var span = document.createElement('span');
                var imgUrl = item.element.getAttribute('data-kt-select2-country');
                console.log(imgUrl)
                var template = '';

                template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
                template += item.text;

                span.innerHTML = template;

                return $(span);
            }

            function next_step(me, index){
                $(`#collapse${index}`).collapse('hide')
                $(`#collapse${index + 1}`).collapse('show')
                $(`#step${index}`).removeClass("bg-light-primary text-primary")
                $(`#step${index}`).addClass("bg-primary text-white")
                isShow($(`#collapse${index + 1}`).parents('.accordion'))
            }

            function remove_form(btn){
                var form = $(btn).parents("div.dup-sec")
                form.remove()
            }

            function duplicate_form(btn){
                var _par = $(btn).parent()
                var form = $(_par).prev("div.duplicate")
                var div = $(form).find("div.dup-sec")
                form.find("select[data-control=select2]").select2("destroy")
                var clone = $(div[0]).clone()
                clone.find("input, textarea, select").val("").trigger("change")
                clone.addClass("mt-5")

                var _btnDelete = `<div class="d-flex justify-content-center mt-5">
                    <button type="button" class="btn btn-link text-danger" onclick="remove_form(this)">
                        <i class="fa fa-trash text-danger"></i>
                        Hapus
                    </button>
                </div>`
                clone.append(_btnDelete)

                $(clone).append(`<div class='separator separator-solid my-3'></div>`)
                if(div.length == 1){
                    $(clone).prepend(`<div class='separator separator-solid my-3'></div>`)
                }
                $(form).append(clone)
                form.find("select[data-control=select2]").select2()
                still()
            }

            function still(){
                $("input.ck-still").click(function(){
                    var form = $(this).parents("div.dup-sec")
                    var name = $(this).attr("name")
                    var _name = name.split("_")
                    var endMonth = "end_month[]"
                    var endYear = "end_year[]"
                    if(_name.length > 1){
                        endMonth = _name[0] + "_end_month[]"
                        endYear = _name[0] + "_end_year[]"
                    }
                    var isChekced = this.checked
                    if (isChekced) {
                        form.find(`select[name='${endMonth}']`).parent().prev().hide()
                        form.find(`select[name='${endMonth}']`).parent().hide()
                        form.find(`select[name='${endYear}']`).parent().hide()
                        form.find(`select[name='${endMonth}']`).prop("required", false)
                        form.find(`select[name='${endYear}']`).prop("required", false)
                    } else {
                        form.find(`select[name='${endMonth}']`).parent().prev().show()
                        form.find(`select[name='${endMonth}']`).parent().show()
                        form.find(`select[name='${endYear}']`).parent().show()
                        form.find(`select[name='${endMonth}']`).prop("required", true)
                        form.find(`select[name='${endYear}']`).prop("required", true)
                    }
                })
            }

            function validate_form(btn){
                var form = $(btn).parents("form")
                var f = document.getElementById($(form).attr("id"))
                var requireds = $(f).find(":required")
                var fields = {}
                requireds.each(function(){
                    var _name = $(this).attr("name")
                    var _row = $(this).parents("div.fv-row")
                    var _label = $(_row).find('label')
                    var attr = {
                        validators: {
                            notEmpty: {
                                message: `${_label.text()} harus diisi`
                            }
                        }
                    }
                    fields[_name] = attr
                })

                FormValidation.formValidation(f, {
                    fields : fields,
                    plugins : {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }).validate().then(function(resp){
                    if(resp == "Valid"){
                        var _t = $(btn).data("target")
                        $(`#collapse${_t}`).collapse('hide')
                        $(`#collapse${_t + 1}`).collapse('show')
                        $(`#step${_t}`).removeClass("bg-light-primary text-primary")
                        $(`#step${_t}`).addClass("bg-primary text-white")
                        $.ajax({
                            url : form.attr("action"),
                            type : "post",
                            dataType : "json",
                            data : new FormData(form[0]),
                            contentType: false,
                            processData: false,
                        }).then(function(resp){
                            canSubmit = true
                            $("#btn-submit").prop("disabled", false)
                            isShow($(`#collapse${_t + 1}`).parents(".accordion"))
                        })
                    }
                })
            }

            function kembali(step){
                $(`#collapse${step}`).collapse('hide')
                $(`#collapse${step - 1}`).collapse('show')
                $(`#step${step - 1}`).removeClass("bg-light-primary text-primary")
                $(`#step${step - 1}`).addClass("bg-primary text-white")
                isShow($(`#collapse${step - 1}`).parents('.accordion'))
            }

            // Init Select2 --- more info: https://select2.org/
            $('#change_locale').select2({
                templateSelection: optionFormat,
                templateResult: optionFormat,
                minimumResultsForSearch: -1
            });

            $(".ipk").on("keyup", function(){
                console.log($(this).val())
                if($(this).val() > 4){
                    $(this).val(4)
                }
            })

            $(".tempusDominus").each(function(){
                var _id = $(this).attr("id")
                var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                    display : {
                        viewMode: "calendar",
                        components: {
                            decades: true,
                            year: true,
                            month: true,
                            date: true,
                            hours: false,
                            minutes: false,
                            seconds: false
                        }
                    },
                    localization: {
                        locale: "id",
                        startOfTheWeek: 1,
                        format: "dd-MM-yyyy"
                    }
                });
            })

            Inputmask({
                "mask" : "99-99-9999"
            }).mask(".tempusDominus");

            $(document).ready(function(){
                still()

                $(".number").number(true, 2, ",", ".")

                $("#btn-submit").click(function(e){
                    var aggree = document.getElementById("flexCheckDefault").checked
                    var form = $(this).parents("form")
                    e.preventDefault()
                    if(!canSubmit){
                        Swal.fire("Profil belum lengkap", "Mohon lengkapi data", "error")
                    } else {
                        if(!aggree){
                            Swal.fire("Syarat & Ketentuan", "Syarat & Ketentuan harus dicentang", "error")
                        } else {
                            form.submit()
                        }
                    }
                })

                $("select[name=city_id]").change(function(){
                    var form = $(this).parents("form")
                    if($(this).val() != null){
                        var opt = $(this).find("option:selected")
                        var prid = $(opt).data("prov")
                        $(form).find("select[name=prov_id]").val(prid).trigger("change")
                    }
                })

                $("select[name=prov_id]").change(function(){
                    var form = $(this).parents("form")
                    if($(this).val() != ""){
                        var ct = $(form).find("select[name=city_id]")
                        ct.find("option").prop("disabled", true)
                        ct.find(`option[data-prov=${$(this).val()}]`).prop("disabled", false)

                        if($(ct).val() == null){
                            var ctprov = $(ct).find("option:selected")
                            if($(this).val() != $(ctprov).data("prov")){
                                $(ct).val("").trigger("change")
                            }
                        }
                        // var opt = $(this).find("option:selected")
                        // var prid = $(opt).data("prov")
                        // $(form).find("select[name=prov_id]").val(prid).trigger("change")
                    }
                })
            })
        </script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
