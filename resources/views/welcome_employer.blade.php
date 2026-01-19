<!DOCTYPE html>
<html lang="id">
<!--begin::Head-->

<head>
    <base href="../" />
    <title>{{ env('APP_LABEL') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset(\Config::get("constants.APP_ICON")) }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('theme/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('theme/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/assets/css/variables.css') }}" type="text/css">
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
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

<body id="kt_body" class="header-fixed">
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
        
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid bg-white" style="padding-left: 0px" id="kt_wrapper">
                <!--begin::Header-->
                @include('layouts.header', ['type' => 'applicant'])
                <!--end::Header-->
                <!--begin::Content wrapper-->
                <div class="d-flex flex-column-fluid">
                    @include('layouts.menu_aside')
                    <!--begin::Container-->
                    <div class="d-flex flex-column flex-column-fluid container-fluid">
                        <!--begin::Post-->
                        <div class="content flex-column-fluid px-md-20" id="kt_content">
                            <div class="card card-custom" style="background-color: #EAE3FB">
                                <div class="card-body">
                                    <div class="d-flex align-items-center flex-column flex-md-row justify-content-between p-md-10">
                                        <div class="d-flex flex-column mb-5 mb-md-0">
                                            <div class="d-flex align-items-center me-5">
                                                <!--begin::Logo-->
                                                <a href="/">
                                                    <img alt="Logo"
                                                        src="{{ asset('theme/assets/media/logos/icon-sm.png') }}"
                                                        class="h-25px h-lg-30px" />
                                                </a>
                                                <span class="fs-3 fw-bold text-dark">{{ env('APP_LABEL') }}</span>
                                                <!--end::Logo-->
                                            </div>
                                            <div class="mt-3">
                                                <span class="fs-3tx">Mencari kandidat terbaik, cepat dan tepat dengan
                                                    KerjaKu</span>
                                            </div>
                                            <span class="mt-3">Pasang iklan lowongan di Job Portal kami secara gratis,
                                                carilah kandidat yang paling cocok dengan kebutuhan perusahaan Anda</span>
                                            <div class="d-flex mt-5">
                                                <a href="{{ route('register') }}"
                                                    class="btn btn-primary me-5">Daftar</a>
                                                <a href="{{ empty($lp_employer) ? "#" : "https://wa.me/62".(substr($lp_employer["wa_no"], 1)) }}" target="_blank" class="btn btn-success">
                                                    <i
                                                        class="ki-duotone ki-whatsapp">
                                                        <i class="path1"></i>
                                                        <i class="path2"></i>
                                                    </i>
                                                    Whatsapp
                                                </a>
                                            </div>
                                        </div>
                                        <div class="bgi-no-repeat bgi-position-center bgi-size-cover flex-fill h-300px rounded w-100 w-md-50" style="background-image : url('{{ asset("images/image_landing2.jpg") }}')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--begin::Content card-->
                            <!--begin::Why-->
                            <div class="my-20">
                                <!--begin::Wrapper-->
                                <div class="mb-10">
                                    <!--begin::Top-->
                                    <div class="text-center mb-15">
                                        <!--begin::Title-->
                                        <h3 class="fs-5 text-primary mb-5">Membantu Tim {{ \Config::get("constants.APP_NAME") }} terus berkembang</h3>
                                        <h3 class="fs-2hx text-dark mb-5">Rekanan Kami</h3>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        {{-- <div class="fs-5 text-muted fw-semibold">Keunggulan apakah yang kami bawa kepada Anda?</div> --}}
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Top-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Description-->
                                <div class="row row-cols-1 row-cols-md-4">
                                    @if (empty($lp_employer))
                                        @for ($i = 1; $i <= 5; $i++)
                                            <div class="col">
                                                <div class="card card-dashed">
                                                    <div class="card-body">
                                                        <img src="{{ asset("images/logos/partner$i.png") }}"
                                                            style="object-fit: contain" class="h-100px w-100"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    @else
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if(!empty($lp_employer["partner$i"]))
                                            <div class="col mb-5 mb-md-0">
                                                <div class="card card-dashed">
                                                    <div class="card-body">
                                                        <img src="{{ asset($lp_employer["partner$i"]) }}"
                                                            style="object-fit: contain" class="h-100px w-100"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endfor
                                    @endif
                                </div>
                                {{-- <div class="row mt-5">
                                    <div class="col">
                                        <div class="card card-dashed">
                                            <div class="card-body">
                                                <div class="row p-10">
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fs-2tx fw-semibold">7 Juta+</span>
                                                            <span class="fs-5">Database Talenta
                                                                Profesional</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fs-2tx fw-semibold">6000+</span>
                                                            <span class="fs-5">Client Perusahaan</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fs-2tx fw-semibold">3000+</span>
                                                            <span class="fs-5">Applicant direkrut oleh
                                                                perusahaan</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <!--end::Description-->
                            </div>
                            <!--end::Why-->
                            <!--begin::Katalog-->
                            <div class="mb-18">
                                <!--begin::Wrapper-->
                                <div class="mb-10">
                                    <!--begin::Top-->
                                    <div class="text-center mb-15">
                                        <!--begin::Title-->
                                        {{-- <h3 class="fs-5 text-primary mb-5">Lorem Ipsum</h3> --}}
                                        <h3 class="fs-2hx text-dark mb-5">Fitur-Fitur Unggulan yang Dimiliki
                                            KerjaKu</h3>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Top-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Description-->
                                <div class="row">
                                    <div class="col">
                                        <div class="card card-bordered">
                                            <div class="card-body">
                                                <div class="align-items-center d-flex flex-column flex-md-row">
                                                    <div class="m-0 mb-5 m-md-20">
                                                        <div class="@empty($lp_employer) bg-light-primary @endempty rounded w-100 w-md-475px" >
                                                            @if (!empty($lp_employer))
                                                                <img src="{{ asset($lp_employer['hs_img']) }}"
                                                                    class="w-100" alt="">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column justify-content-between ms-0 ms-md-20 flex-fill">
                                                        @if (empty($lp_employer))
                                                            <div
                                                                class="d-flex align-items-center border-gray-300 mb-5">
                                                                <span
                                                                    class="fs-2tx text-primary me-3">01</span>
                                                                <div
                                                                    class="d-flex flex-column justify-content-between">
                                                                    <span class="fw-semibold">Mengatur Jadwal
                                                                        Interview</span>
                                                                    <span>Atur jadwal interview dengan mudah dan
                                                                        cepat berdasarkan jadwal yang sudah di
                                                                        buat</span>
                                                                </div>
                                                            </div>
                                                            <div class="separator separator-dashed mb-5"></div>
                                                            <div
                                                                class="d-flex align-items-center border-gray-300 mb-5">
                                                                <span
                                                                    class="fs-2tx text-primary me-3">02</span>
                                                                <div
                                                                    class="d-flex flex-column justify-content-between">
                                                                    <span class="fw-semibold">Seleksi melalui
                                                                        tes</span>
                                                                    <span>Fitur tes yang membantu memilih
                                                                        kandidat paling sesuai untuk posisi yang
                                                                        dicari.</span>
                                                                </div>
                                                            </div>
                                                            <div class="separator separator-dashed mb-5"></div>
                                                            <div
                                                                class="d-flex align-items-center border-gray-300 mb-5">
                                                                <span
                                                                    class="fs-2tx text-primary me-3">03</span>
                                                                <div
                                                                    class="d-flex flex-column justify-content-between">
                                                                    <span class="fw-semibold">Undangan
                                                                        Interview</span>
                                                                    <span>Memudahkan HR untuk mengundang
                                                                        Interview secara individu atau
                                                                        grup</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            @for ($i = 1; $i <= 3; $i++)
                                                                <div
                                                                    class="d-flex align-items-center border-gray-300 mb-5">
                                                                    <span
                                                                        class="fs-2tx text-primary me-3">{{ sprintf('%02d', $i) }}</span>
                                                                    <div
                                                                        class="d-flex flex-column justify-content-between">
                                                                        <span
                                                                            class="fw-semibold">{{ $lp_employer["hs_title$i"] }}</span>
                                                                        <span>{{ $lp_employer["hs_desc$i"] }}</span>
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        @endif
                                                        <div class="separator separator-dashed mb-5"></div>
                                                        <div>
                                                            <a href="{{ route("register") }}?role=employer"
                                                                class="btn btn-primary">Pasang Iklan
                                                                Lowongan</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Description-->
                            </div>
                            <!--end::Katalog-->
                            <!--end::Content card-->
                        </div>
                        <!--end::Post-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Content wrapper-->

                <div class="d-flex flex-column-fluid">
                    <!--begin::Container-->
                    <div class="d-flex flex-column flex-column-fluid container-fluid">
                        <!--begin::Post-->
                        <div class="content flex-column-fluid px-md-20" id="kt_content2">
                            <!--begin::Why-->
                            <div class="mb-18">
                                <!--begin::Wrapper-->
                                <div class="mb-10">
                                    <!--begin::Top-->
                                    <div class="text-center mb-15">
                                        <!--begin::Title-->
                                        <h3 class="fs-2hx text-dark mb-5">Langkah-Langkah Rekrutmen menggunakan
                                            KerjaKu</h3>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Top-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Description-->
                                <div class="row g-3">
                                    @if (empty($lp_employer))
                                        @for ($i = 1; $i <= 3; $i++)
                                            <div class="col col-md-4">
                                                <div class="card card-custom card-bordered card-stretch">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column">
                                                            <img src="{{ asset('images/blank.png') }}"
                                                                class="w-100px mb-5" alt="">
                                                            <h3>Lorem ipsum</h3>
                                                            <p>Dari cari lowongan sampai onboarding, sistem kami
                                                                dapat menangani seluruh tahap perjalanan kerja
                                                                anda.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    @else
                                        @for ($i = 1; $i <= 3; $i++)
                                            <div class="col col-md-4">
                                                <div class="card card-custom card-bordered card-stretch">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column">
                                                            <img src="{{ asset($lp_employer["wah_img$i"]) }}"
                                                                class="w-100px mb-5" alt="">
                                                            <h3>{{ $lp_employer["wah_title$i"] }}</h3>
                                                            <p>{{ $lp_employer["wah_content$i"] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                                <!--end::Description-->
                            </div>
                            <!--end::Why-->
                            <!--begin::Katalog-->
                            {{-- <div class="mb-18">
                                <!--begin::Wrapper-->
                                <div class="mb-10">
                                    <!--begin::Top-->
                                    <div class="text-center mb-15">
                                        <!--begin::Title-->
                                        <h3 class="fs-5 text-primary mb-5">USER STORIES</h3>
                                        <h3 class="fs-2hx text-dark mb-5">Ulasan pengguna kami</h3>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Top-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Description-->
                                <div class="row">
                                    <div class="col">
                                        <div class="card card-bordered">
                                            <div class="card-body">
                                                <div class="carousel carousel-custom slide" id="carousel-user"
                                                    data-bs-ride="carousel" data-bs-interval="8000">
                                                    @if (empty($lp_employer))
                                                        <div class="carousel-inner">
                                                            <div class="carousel-item active">
                                                                <div class="d-flex align-items-center p-10">
                                                                    <div class="h-300px w-50 rounded me-10"
                                                                        style="background-size: cover; background-repet: no-repeat; background-image : url('{{ asset('images/logos/user-stories.png') }}')">
                                                                    </div>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="mb-10">"Lorem ipsum dolor
                                                                            sit amet consectetur. Enim fusce
                                                                            risus vitae nunc lectus. Amet nisi
                                                                            lobortis id ut felis ut vivamus.
                                                                            Donec convallis tincidunt id iaculis
                                                                            sed viverra lectus tortor
                                                                            consectetur. Volutpat netus sit
                                                                            ullamcorper eget sem laoreet.
                                                                            Pretium urna sagittis mollis lacus.
                                                                            A integer ultricies consequat
                                                                            consequat et."</span>
                                                                        <span
                                                                            class="text-primary fw-bold">Anthony
                                                                            Luis</span>
                                                                        <span class="fw-semibold">Officer
                                                                            Recruitmen, PT Gumilang Abadi</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <div class="d-flex align-items-center p-10">
                                                                    <div class="h-300px w-50 rounded me-10"
                                                                        style="background-size: cover; background-repet: no-repeat; background-image : url('{{ asset('images/logos/user-stories.png') }}')">
                                                                    </div>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="mb-10">"Lorem ipsum dolor
                                                                            sit amet consectetur. Enim fusce
                                                                            risus vitae nunc lectus. Amet nisi
                                                                            lobortis id ut felis ut vivamus.
                                                                            Donec convallis tincidunt id iaculis
                                                                            sed viverra lectus tortor
                                                                            consectetur. Volutpat netus sit
                                                                            ullamcorper eget sem laoreet.
                                                                            Pretium urna sagittis mollis lacus.
                                                                            A integer ultricies consequat
                                                                            consequat et."</span>
                                                                        <span
                                                                            class="text-primary fw-bold">Anthony
                                                                            Luis</span>
                                                                        <span class="fw-semibold">Officer
                                                                            Recruitmen, PT Gumilang Abadi</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="carousel-item">
                                                                <div class="d-flex align-items-center p-10">
                                                                    <div class="h-300px w-50 rounded me-10"
                                                                        style="background-size: cover; background-repet: no-repeat; background-image : url('{{ asset('images/logos/user-stories.png') }}')">
                                                                    </div>
                                                                    <div class="d-flex flex-column">
                                                                        <span class="mb-10">"Lorem ipsum dolor
                                                                            sit amet consectetur. Enim fusce
                                                                            risus vitae nunc lectus. Amet nisi
                                                                            lobortis id ut felis ut vivamus.
                                                                            Donec convallis tincidunt id iaculis
                                                                            sed viverra lectus tortor
                                                                            consectetur. Volutpat netus sit
                                                                            ullamcorper eget sem laoreet.
                                                                            Pretium urna sagittis mollis lacus.
                                                                            A integer ultricies consequat
                                                                            consequat et."</span>
                                                                        <span
                                                                            class="text-primary fw-bold">Anthony
                                                                            Luis</span>
                                                                        <span class="fw-semibold">Officer
                                                                            Recruitmen, PT Gumilang Abadi</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <ol
                                                            class="p-0 m-0 carousel-indicators carousel-indicators-dots carousel-indicators-active-primary">
                                                            <li data-bs-target="#carousel-user"
                                                                data-bs-slide-to="0" class="ms-1 active"></li>
                                                            <li data-bs-target="#carousel-user"
                                                                data-bs-slide-to="1" class="ms-1"></li>
                                                            <li data-bs-target="#carousel-user"
                                                                data-bs-slide-to="2" class="ms-1"></li>
                                                        </ol>
                                                    @else
                                                        <div class="carousel-inner">
                                                            @foreach ($user_stories as $i => $item)
                                                                <div
                                                                    class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                                                    <div
                                                                        class="d-flex align-items-center p-10">
                                                                        <div
                                                                            class="h-300px w-50 rounded me-10 text-center">
                                                                            <img src="{{ asset($item->picture) }}"
                                                                                class="h-100 rounded"
                                                                                alt="">
                                                                        </div>
                                                                        <div class="d-flex flex-column">
                                                                            <span
                                                                                class="mb-10">"{{ strip_tags($item->notes) }}"</span>
                                                                            <span
                                                                                class="text-primary fw-bold">{{ $item->name }}</span>
                                                                            <span
                                                                                class="fw-semibold">{{ $item->specification }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <ol
                                                            class="p-0 m-0 carousel-indicators carousel-indicators-dots carousel-indicators-active-primary">
                                                            @foreach ($user_stories as $i => $item)
                                                                <li data-bs-target="#carousel-user"
                                                                    data-bs-slide-to="{{ $i }}"
                                                                    class="ms-1 {{ $i == 0 ? 'active' : '' }}">
                                                                </li>
                                                            @endforeach
                                                        </ol>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Description-->
                            </div> --}}
                            <!--end::Katalog-->
                            {{-- <div class="mb-18">
                                <!--begin::Description-->
                                <div class="card card-custom bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold fs-2tx mb-5">Pasang iklan Anda sekarang</span>
                                            <span class="mb-10">Pasang iklan lowongan kerja untuk perusahaanmu
                                                dan temukanlah kandidat tercocok</span>
                                            <div>
                                                <button type="button"
                                                    class="bg-hover-opacity-20 bg-white btn text-hover-white text-primary">Join
                                                    us now</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Description-->
                            </div> --}}
                        </div>
                        <!--end::Post-->
                    </div>
                    <!--end::Container-->
                </div>

                <div class="d-flex flex-column-fluid">
                    <div class="d-flex flex-column flex-column-fluid container-fluid">
                        @include('layouts.footer')
                    </div>
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
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('theme/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('theme/assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('theme/assets/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
    <script src="{{ asset('theme/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('theme/assets/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('theme/assets/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('theme/assets/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('theme/assets/js/custom/utilities/modals/create-app.js') }}"></script>
    <script src="{{ asset('theme/assets/js/custom/utilities/modals/users-search.js') }}"></script>
    @include('layouts._scripts')
    <script>
        // Format options
        var optionFormat = function(item) {
            if (!item.id) {
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

        console.log("hello")

        // Init Select2 --- more info: https://select2.org/
        $('#change_locale').select2({
            templateSelection: optionFormat,
            templateResult: optionFormat,
            minimumResultsForSearch: -1
        });
    </script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
