<!DOCTYPE html>
<html lang="id">
	<!--begin::Head-->
	<head><base href="../"/>
		<title>{{ \Config::get("constants.APP_LABEL") }}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
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
        <link rel="stylesheet" href="{{ asset("theme/assets/css/variables.css") }}?v={{ time() }}" type="text/css">
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
        @include('layouts.styles')
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
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid bg-white" style="padding-left: 0px" id="kt_wrapper">
					<!--begin::Header-->
					@include('layouts.header', ["type" => "applicant"])
					<!--end::Header-->
					<!--begin::Content wrapper-->
					<div class="d-flex flex-column-fluid">
                        @include('layouts.menu_aside')
						<!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid">
							<!--begin::Post-->
							<div class="content flex-column-fluid px-md-20" id="kt_content">
                                {{-- begin:Top --}}
                                <div class="align-items-center d-flex flex-column flex-md-row mb-18 mt-5 gap-20">
                                    <form method="post" action="{{ route('booking.cari') }}" class="w-75">
                                        @csrf
                                        <input type="hidden" name="add_token" value="{{ rand(100000, 999999) }}">
                                        <div class="d-flex flex-column gap-5">
                                            <h3 class="fs-2hx text-dark">{{ $lp_applicant->sec_title ?? "Temukan harga terbaik untuk pengiriman mu" }}</h3>
                                            <p>{{ $lp_applicant->sec_desc ?? "Harga terbaik kami hanya untuk Anda. Kami melayani pengiriman ke luar negeri dengan kurir express Internasional terbaik." }}</p>
                                            <div class="card shadow">
                                                <div class="card-body">
                                                    <div class="d-flex flex-column gap-5">
                                                        <div class="fv-row d-flex align-items-center gap-5">
                                                            <label class="col-form-label text-nowrap">Jenis produk</label>
                                                            <select name="produk_id" required class="form-select" data-control="select2">
                                                                @foreach ($produk as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Dari</label>
                                                                    <select name="dari" required class="form-select" data-control="select2">
                                                                        @foreach ($dari as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Negara Tujuan</label>
                                                                    <select name="tujuan" required class="form-select" data-control="select2" data-placeholder="- Negara Tujuan -">
                                                                        <option value=""></option>
                                                                        @foreach ($tujuan as $item)
                                                                            <option value="{{ $item->id }}">{{ strtoupper($item->nama) }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            @foreach ($produk as $item)
                                                                <div class="{{ $loop->iteration == 1 ? "" : "d-none" }} d-flex flex-column gap-5" id="tab_{{ $item->id }}" data-role="tab">
                                                                    <div class="fv-row w-100">
                                                                        <select onchange="this.value == '_new' ? $('#div-others-{{$item->id}}').removeClass('d-none') : $('#div-others-{{$item->id}}').addClass('d-none')" name="kategori[{{ $item->id }}]{{ $item->tipe_kategori == "w" ? "" : "[]" }}" {{ $item->tipe_kategori == "w" ? "" : "multiple" }} {{ $loop->iteration == 1 ? "required" : "" }} class="form-select w-100" data-control="select2" data-placeholder="- Kategori -">
                                                                            <option value=""></option>
                                                                            @foreach ($kategori->where("produk_id", $item->id) as $val)
                                                                                <option value="{{ $val->id }}">{{ $val->nama }}</option>
                                                                            @endforeach
                                                                            @if ($item->tipe_kategori == "w")
                                                                                <option value="_new">Others</option>
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <!--begin::Repeater-->
                                                                    @if ($item->tipe_kategori != "w")
                                                                        <div id="form_repeat_{{ $item->id }}" data-form="repeater">
                                                                            <!--begin::Form group-->
                                                                            <div class="form-group">
                                                                                <div data-repeater-list="data" class="d-flex flex-column gap-5">
                                                                                    <div data-repeater-item class="border-top">
                                                                                        <div class="form-group row pt-5">
                                                                                            <div class="col-md-6">
                                                                                                <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                                    <label class="form-label required">Jumlah Paket</label>
                                                                                                    <input type="number" {{ $loop->iteration == 1 ? "required" : "" }} data-input="total_paket" name="total_paket-{{ $item->id }}" value="1" min="1" class="form-control w-75px">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                                    <label class="form-label required">Berat (kg)</label>
                                                                                                    <input type="number" max="300" {{ $loop->iteration == 1 ? "required" : "" }} data-input="berat" name="berat-{{ $item->id }}" value="1.00" min="1" step=".01" class="form-control w-75px">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                                    <label class="form-label required">Panjang (cm)</label>
                                                                                                    <input type="number" max="300" {{ $loop->iteration == 1 ? "required" : "" }} data-input="panjang" name="panjang-{{ $item->id }}" value="1.00" min="1" step=".01" class="form-control w-75px">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                                    <label class="form-label required">Tinggi (cm)</label>
                                                                                                    <input type="number" max="160" {{ $loop->iteration == 1 ? "required" : "" }} data-input="tinggi" name="tinggi-{{ $item->id }}" value="1.00" min="1" step=".01" class="form-control w-75px">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                                    <label class="form-label required">Lebar (cm)</label>
                                                                                                    <input type="number"  max="200" {{ $loop->iteration == 1 ? "required" : "" }} data-input="lebar" name="lebar-{{ $item->id }}" value="1.00" min="1" step=".01" class="form-control w-75px">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <div class="d-flex flex-column gap-5">
                                                                                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger w-100">
                                                                                                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                                                                            Delete
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!--end::Form group-->

                                                                            <!--begin::Form group-->
                                                                            <div class="form-group mt-5 d-flex justify-content-end gap-5">
                                                                                <a href="javascript:;" data-repeater-create class="btn btn-info">
                                                                                    Tambah Barang
                                                                                </a>
                                                                                <button type="submit" class="btn btn-primary">
                                                                                    Cari Harga
                                                                                </button>
                                                                            </div>
                                                                            <!--end::Form group-->
                                                                        </div>
                                                                    @else
                                                                        <div class="fv-row w-100 d-none" id="div-others-{{$item->id}}">
                                                                            <input type="text" name="kategori_name" class="form-control" placeholder="Input Kategori Lainnya">
                                                                        </div>
                                                                        <div class="fv-row w-100">
                                                                            <select name="document_weight" class="form-select w-100" data-control="select2" data-placeholder="- Berat -">
                                                                                @foreach (Config::get('constants.document_weight') as $val)
                                                                                    <option value="{{ $val }}">{{ $val }} kg</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="d-flex justify-content-end">
                                                                            <button type="submit" class="btn btn-primary">
                                                                                Cari Harga
                                                                            </button>
                                                                        </div>
                                                                    @endif
                                                                    <!--end::Repeater-->
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="w-100 rounded bgi-no-repeat bgi-size-cover bgi-position-center" style="height: 623px; background-image: url('{{ asset($lp_applicant->sec_img ?? "images/blank.png") }}?v={{ time() }}')"></div>
                                    {{-- <img src="{{ asset($lp_applicant->sec_img ?? "images/blank.png") }}?v={{ time() }}" class="w-md-50 w-100" alt=""> --}}
                                </div>
                                {{-- end:Top --}}
								<!--begin::Why-->
                                <div class="mb-18" style="margin-top: 100px;">
                                    <!--begin::Wrapper-->
                                    <div class="mb-10">
                                        <!--begin::Top-->
                                        <div class="text-center mb-15">
                                            <!--begin::Title-->
                                            <h3 class="fs-2hx text-dark mb-5">{{ $lp_applicant->mk_title ?? "Langkah kirim mudah di ".\Config::get("constants.APP_LABEL")."?" }}</h3>
                                            <!--end::Title-->
                                            <!--begin::Text-->
                                            <div class="fs-5 fw-semibold">{{ $lp_applicant->mk_subtitle ?? "Hanya 3 langkah mudah!" }}</div>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Top-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Description-->
                                    <div class="row g-3">
                                        @if (!empty($lp_applicant))
                                            @for ($i = 1; $i <= 3; $i++)
                                                <div class="col-md-4 col">
                                                    <div class="card card-custom card-bordered card-stretch">
                                                        <div class="card-body">
                                                            <div class="d-flex flex-column">
                                                                <img src="{{ asset($lp_applicant["mk_img$i"]) }}" class="w-100px mb-5" alt="">
                                                                <h3>{{ $lp_applicant["mk_title$i"] }}</h3>
                                                                <p>{{ $lp_applicant["mk_content$i"] }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            <div class="col-4">
                                                <div class="card card-custom card-bordered">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column">
                                                            <img src="{{ asset("images/blank.png") }}" class="w-100px mb-5" alt="">
                                                            <h3>Lorem ipsum</h3>
                                                            <p>Dari cari lowongan sampai onboarding, sistem kami dapat menangani seluruh tahap perjalanan kerja anda.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="card card-custom card-bordered">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column">
                                                            <img src="{{ asset("images/blank.png") }}" class="w-100px mb-5" alt="">
                                                            <h3>Lorem ipsum</h3>
                                                            <p>Dari cari lowongan sampai onboarding, sistem kami dapat menangani seluruh tahap perjalanan kerja anda.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="card card-custom card-bordered">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column">
                                                            <img src="{{ asset("images/blank.png") }}" class="w-100px mb-5" alt="">
                                                            <h3>Lorem ipsum</h3>
                                                            <p>Dari cari lowongan sampai onboarding, sistem kami dapat menangani seluruh tahap perjalanan kerja anda.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Why-->
                                <!--begin::Katalog-->
                                <div class="mb-18" style="margin-top: 200px;" id="cek-resi">
                                    <!--begin::Wrapper-->
                                    <div class="mb-10">
                                        <!--begin::Top-->
                                        <div class="text-center mb-15">
                                            <!--begin::Title-->
                                            <h3 class="fs-2hx text-dark mb-5">Lacak Pengiriman</h3>
                                            <!--end::Title-->
                                            <!--begin::Text-->
                                            <div class="fs-5 fw-semibold">Masukkan kode tracking pengiriman Anda</div>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Top-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Description-->
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-5 px-20">
                                            <div class="d-flex align-items-center gap-5">
                                                <input type="text" class="form-control" id="inputCekResi" placeholder="Masukkan kode tracking">
                                                <button class="btn btn-primary" id="btnCekResi">Lacak</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Katalog-->
							</div>
							<!--end::Post-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Content wrapper-->
                    <div class="d-flex flex-column-fluid bg-light-primary mb-18 mt-20">
                        <!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid">
							<!--begin::Post-->
							<div class="content flex-column-fluid" id="kt_content1">
                                {{-- begin:Top --}}
                                <div class="d-flex flex-column m-10 align-items-center">
                                    <h3 class="text-dark fs-2hx mb-5">Partner Kami</h3>
                                    <div class="align-items-center d-flex flex-column flex-md-row">
                                        @if (!empty($lp_applicant))
                                            @for ($i = 1; $i <= 7; $i++)
                                                @if($lp_applicant["partner$i"] != null)
                                                <div class="mx-5">
                                                    <img src="{{ asset($lp_applicant["partner$i"]) }}" class="h-50px" alt="">
                                                </div>
                                                @endif
                                            @endfor
                                        @else
                                            <div class="col">
                                                <img src="{{ asset("images/logos/github.png") }}" class="min-w-50px w-100" alt="">
                                            </div>
                                            <div class="col">
                                                <img src="{{ asset("images/logos/github.png") }}" class="min-w-50px w-100" alt="">
                                            </div>
                                            <div class="col">
                                                <img src="{{ asset("images/logos/github.png") }}" class="min-w-50px w-100" alt="">
                                            </div>
                                            <div class="col">
                                                <img src="{{ asset("images/logos/github.png") }}" class="min-w-50px w-100" alt="">
                                            </div>
                                            <div class="col">
                                                <img src="{{ asset("images/logos/github.png") }}" class="min-w-50px w-100" alt="">
                                            </div>
                                            <div class="col">
                                                <img src="{{ asset("images/logos/github.png") }}" class="min-w-50px w-100" alt="">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                {{-- end:Top --}}
							</div>
							<!--end::Post-->
						</div>
						<!--end::Container-->
                    </div>

                    <div class="d-flex flex-column-fluid" style="margin-top: 80px">
                        <!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid align-items-center">
							<!--begin::Post-->
							<div class="content flex-column-fluid" id="kt_content2">
                                <!--begin::motivasi-->
                                <div class="mb-18 px-md-20">
                                    <!--begin::Wrapper-->
                                    <div class="mb-10">
                                        <!--begin::Top-->
                                        <div class="text-center mb-15">
                                            <!--begin::Title-->
                                            <h3 class="fs-2hx text-dark mb-5">Contact Us!</h3>
                                            <!--end::Title-->
                                            <!--begin::Text-->
                                            <div class="fs-5 fw-semibold">Kami Siap Menjadi Mitra Anda</div>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Top-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Description-->
                                    <div class="d-flex flex-column gap-2 align-items-center mw-500px">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fa fa-map-marker-alt fs-2 text-primary"></i>
                                            <span class="text-center">{{ env("ADDRESS") }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fab fa-whatsapp text-success fs-2"></i>
                                            <a href="{{ env("WA_LINK") }}" target="_blank">
                                                {{ env("WA_PHONE_NUMBER") }}
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa fa-phone text-success fs-2"></i>
                                            <span class="text-primary">{{ env("HOTLINE_NUMBER") }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa fa-envelope text-success fs-2"></i>
                                            <a href="mailto:{{ env("EMAIL_ADDRESS") }}" target="_blank">
                                                {{ env("EMAIL_ADDRESS") }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column-fluid" style="margin-top: 80px;">
						<!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid">
							<!--begin::Post-->
							<div class="content flex-column-fluid" id="kt_content2">
                                <!--begin::motivasi-->
                                <div class="mb-18 px-md-20">
                                    <!--begin::Wrapper-->
                                    <div class="mb-10">
                                        <!--begin::Top-->
                                        <div class="text-center mb-15">
                                            <!--begin::Title-->
                                            <h3 class="fs-2hx text-dark mb-5">Artikel Terbaru</h3>
                                            <!--end::Title-->
                                            <!--begin::Text-->
                                            <div class="fs-5 fw-semibold">Bacaan ringan untuk Anda</div>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Top-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Description-->
                                    <div class="row">
                                        <div class="col-12">
                                            @if (!empty($hot_artikel))
                                                <h3>Hot Artikel Minggu ini</h3>
                                                <div class="row mt-5 mb-18">
                                                    @if (!empty($hot_artikel))
                                                    <a href="{{ route("artikel.detail", $hot_artikel->id) }}" class="col-12 col-md-7 d-flex flex-column text-dark">
                                                        <img src="{{ asset($hot_artikel->thumbnail ?? "images/article.png") }}" class="mb-5 rounded w-100" alt="">
                                                        <span class="fw-bold fs-2 text-dark">{{ $hot_artikel->subject ?? "Lorem ipsum dolor sit amet consectetur. At enim amet eros tellus non scelerisque mollis." }}</span>
                                                        <span>{!! strlen($hot_artikel->description) > 100 ? substr($hot_artikel->description, 0, 100) : $hot_artikel->description !!}</span>
                                                        <div class="d-flex text-muted">
                                                            <p>{{$hot_artikel->created_by}}</p>
                                                            <p> - @dateId(date("Y-m-d", strtotime($hot_artikel->created_at ?? date("Y-m-d"))))</p>
                                                            <p></p>
                                                        </div>
                                                    </a>
                                                    @endif
                                                    <div class="col-12 col-md-5 d-flex flex-column">
                                                        @if ($artikel->count() > 0)
                                                            @foreach ($artikel as $i => $item)
                                                                <a href="{{ route("artikel.detail", $item->id) }}" class="d-flex flex-column flex-md-row text-dark">
                                                                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover rounded h-200px h-md-auto w-md-500px w-100 mb-3 mb-md-0 me-0 me-md-5" style="background-image: url({{ asset($item->thumbnail) }})"></div>
                                                                    <div class="d-flex flex-column w-md-500px">
                                                                        <span class="fw-bold fs-3 text-dark">{{ $item->subject }}</span>
                                                                        <span>{!! strlen($item->description) > 100 ? substr($item->description, 0, 100) : $item->description !!}</span>
                                                                        <div class="d-flex text-muted">
                                                                            <p>{{$item->created_by}}</p>
                                                                            <p>- @dateId(date("Y-m-d", strtotime($item->created_at)))</p>
                                                                            <p></p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                                <div class="separator separator-dotted my-5"></div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row d-none d-md-inline">
                                        <h3>Artikel Baru</h3>
                                        <div class="row row-cols-3 g-3">
                                            @if ($newArtikel->count() > 0)
                                                @foreach ($newArtikel as $item)
                                                    <div class="col">
                                                        <a href="{{route("artikel.detail", $item->id)}}" class="d-flex flex-column text-dark" style="width: 100%">
                                                            {{-- <img src="" class="mb-5 rounded w-100" alt=""> --}}
                                                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover h-300px mb-5 rounded w-100" style="background-image: url('{{ asset($item->thumbnail) }}')"></div>
                                                            <span class="fw-bold fs-2">{{ $item->subject }}</span>
                                                            <span>{{ substr(strip_tags($item->description), 0, 200) }}</span>
                                                            <div class="d-flex text-muted">
                                                                <p>{{$item->created_by}}</p>
                                                                <p> - @dateId(date("Y-m-d", strtotime($item->created_at)))</p>
                                                                {{-- <p>- 5 mins read</p> --}}
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @else
                                            <div class="col-12 text-center">
                                                Tidak ada artikel baru
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Katalog-->
							</div>
							<!--end::Post-->
						</div>
						<!--end::Container-->
					</div>

                    <div class="d-flex flex-column-fluid" style="margin-top: 80px">
                        <!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid">
							<!--begin::Post-->
							<div class="content flex-column-fluid" id="kt_content_review">
                                <!--begin::motivasi-->
                                <div class="mb-18 px-md-20">
                                    <!--begin::Wrapper-->
                                    <div class="mb-10">
                                        <!--begin::Top-->
                                        <div class="text-center mb-15">
                                            <!--begin::Title-->
                                            <h3 class="fs-2hx text-dark mb-5">Review</h3>
                                            <!--end::Title-->
                                            <!--begin::Text-->
                                            {{-- <div class="fs-5 fw-semibold">Bacaan ringan untuk Anda</div> --}}
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Top-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <div class="tns" style="direction: ltr">
                                        <div data-tns="true" data-tns-nav-position="bottom" data-tns-items="3" data-tns-mouse-drag="true" data-tns-controls="false" class="gap-5">
                                            <!--begin::Item-->
                                            @foreach (\App\Models\Express_review::get() as $item)
                                                <div class="card border border-secondary-subtle">
                                                    <div class="card-body">
                                                        <div class="d-flex flex-column align-items-center gap-2">
                                                            <div class="symbol symbol-100px symbol-circle mb-3">
                                                                @if (empty($item->avatars))
                                                                    <span class="symbol-label bg-light-primary text-primary fs-3x fw-bold">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                                                @else
                                                                    <img alt="Pic" src="{{ asset($item->avatars ?? "images/blank.png") }}">
                                                                @endif
                                                            </div>
                                                            <div class="fs-3 text-dark fw-bold">{{ $item->name }}</div>
                                                            <div class="fs-5 text-muted">{{ $item->occupation }}</div>
                                                            <div class="d-flex">
                                                                @for ($i = 0; $i < $item->rating; $i++)
                                                                    <i class="fa fa-star text-warning"></i>
                                                                @endfor
                                                            </div>
                                                            <div>
                                                                {!! $item->description !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        @component('layouts.components.wa_button')

        @endcomponent
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
        <link href="{{ asset('theme/jquery-ui/jquery-ui.css') }}" rel="Stylesheet">
        <script src="{{ asset('theme/jquery-ui/jquery-ui.js') }}"></script>
        <script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
        @include('layouts._scripts')
        <script>
            // Format options
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

            // Init Select2 --- more info: https://select2.org/
            $('#change_locale').select2({
                templateSelection: optionFormat,
                templateResult: optionFormat,
                minimumResultsForSearch: -1
            });

            $("div[data-role=tab]").each(function(){
                const tabTrigger = new bootstrap.Tab(this)
            })

            $("select[name=produk_id]").change(function(){
                const produkId = $(this).val()
                var tab = "#tab_" + produkId
                $("div[data-role=tab]").addClass("d-none")
                $("div[data-role=tab]").find("input, select").prop("required", false)
                $(tab).removeClass('d-none')
                $(tab).find("input, select").prop("required", true)
            })

            $("#btnCekResi").click(function(){
                var resi = $("#inputCekResi").val()

                window.location.href = "{{ route("cek.resi") }}/" + resi
            })

            $("#home-lokasi").autocomplete({
                source: encodeURI(
                    "@auth{{ route("applicant.job.index") }}@else{{ route("applicant.job_guest.index") }}@endauth?a=location&t=autocomplete"
                    ),
                minLength: 1,
                appendTo: "#autocomplete-div",
                response: function(event, ui) {
                    console.log(event)
                },
                select: function(event, ui) {
                    // $("#comp_id").val(ui.item.id)
                    // $("#btn-add-company").show()
                }
            });

            $("div[data-form=repeater]").each(function(){
                $(this).repeater({
                    initEmpty: false,

                    show: function () {
                        $(this).slideDown();
                        $(this).find("input").val(1)
                        // $(this).find("input[data-input]").each(function(){
                        //     var name = $(this).attr("name")
                        //     name += "["+$(this).data("input")+"]"
                        //     $(this).attr("name", name)
                        // })
                    },

                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    },
                    ready: function(setIndexes){
                        setTimeout(() => {

                        }, 1000);
                    },
                    isFirstItemUndeletable: true
                });
            })

            // $.ajax({
            //     url : encodeURI("@auth{{ route("applicant.job.index") }}@else{{ route("applicant.job_guest.index") }}@endauth?a=location"),
            //     type : "get",
            //     dataType : "json"
            // }).then(function(resp){
            //     // var tagify = new Tagify(document.querySelector("#home-lokasi"), {
            //     //     whitelist : resp.locations,
            //     //     enforceWhitelist: true,
            //     //     dropdown: {
            //     //         maxItems: 20,           // <- mixumum allowed rendered suggestions
            //     //         classname: "", // <- custom classname for this dropdown, so it could be targeted
            //     //         enabled: 0,             // <- show suggestions on focus
            //     //         closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
            //     //     },
            //     //     callbacks : {

            //     //     }
            //     // })

            //     // $(".tagify__input").css("margin-top", "-3px")
            // })
        </script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
        <!--Start of Tawk.to Script-->
        @include('layouts.tawkto')
        <!--End of Tawk.to Script-->
	</body>
	<!--end::Body-->
</html>
