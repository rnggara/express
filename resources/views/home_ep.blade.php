@extends('layouts.template', ["withoutFooter" => 1])

@section('content')
<div class="card card-custom bg-primary text-white" style="background-image: url({{ asset("images/dashboard-banner.png") }}); background-size: cover; background-blend-mode: screen;">
    <div class="card-body">
        <div class="d-flex align-items-md-center flex-column flex-md-row justify-content-md-between">
            <div class="d-flex flex-column w-md-75 mb-5 mb-md-0">
                <span class="fs-2tx">Halo! {{ Auth::user()->name }}</span>
                <span class="fw-semibold mb-5">Carilah kandidat yang sesuai keinginan dan lokasi Anda</span>
                <div class="d-flex flex-column flex-md-row">
                    <!--begin::Input group-->
                    <div class="input-group mb-3 mb-md-0 input-group-solid">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="Cari lowongan kerja disini" aria-label="Cari lowongan kerja disini" aria-describedby="basic-addon2"/>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="input-group mb-3 mb-md-0 input-group-solid ms-md-3 w-md-50">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fa fa-map-marked-alt"></i>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="Lokasi" aria-label="Lokasi" aria-describedby="basic-addon2"/>
                    </div>
                    <!--end::Input group-->
                    <div class="ms-md-3">
                        <button type="button" class="btn btn-danger w-100 w-md-auto">
                            Cari
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex ms-0 ms-md-5 justify-content-between">
                <div class="rounded position-relative w-md-225px w-100 h-175px">
                    <div class="gradient-linear w-100 h-100 border rounded p-3"></div>
                    <div class="w-100 h-100 position-absolute" style="top: 35px">
                        <div class="d-flex flex-column align-items-center px-3">
                            <i class="fa fa-users fs-1 mb-1 text-white"></i>
                            <p class="text-center fs-6">Apakah Anda sedang mencari kandidat baru hari ini?</p>
                            <a href="{{ route("job.add.view") }}" class="btn btn-light-primary btn-sm">Buat Job Ad</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-5 mt-5 row-cols-1 row-cols-md-4">
    {{-- <div class="cols">
        <div class="card card-stretch card-bordered mb-5">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center">
                    <a href="https://drive.google.com/file/d/1cycaQCQ6-sGUKhYClrmm1Q3q-yuRgyVK/view?usp=sharing" target="_blank">
                        <img src="{{ asset("images/playstore.png") }}" class="w-100" alt="">
                    </a>
                    <span class="text-center">Download APK for mobile attendance</span>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="cols">
        <div class="card card-stretch card-bordered mb-5 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-column align-items-center align-items-md-start">
                        <div class="d-flex align-items-center mb-10 fw-bold">
                            <span class="btn btn-icon btn-sm btn-warning me-5">
                                <i class="fa fa-briefcase"></i>
                            </span>
                            Job Ad
                        </div>
                        <span class="fs-2tx">{{ $job_vac->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cols">
        <div class="card card-stretch card-bordered mb-5">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-column align-items-center align-items-md-start">
                        <div class="d-flex align-items-center mb-10 fw-bold">
                            <span class="btn btn-icon btn-sm btn-danger me-5">
                                <i class="fa fa-briefcase"></i>
                            </span>
                            Job Ad Tayang
                        </div>
                        <span class="fs-2tx">{{ $job_vac->whereNotNull('confirm_at')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cols">
        <div class="card card-stretch card-bordered mb-5">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-column align-items-center align-items-md-start">
                        <div class="d-flex align-items-center mb-10 fw-bold">
                            <span class="btn btn-icon btn-sm btn-danger me-5">
                                <i class="fa fa-briefcase"></i>
                            </span>
                            Job Ad Nonaktif
                        </div>
                        <span class="fs-2tx">{{ $job_vac->whereNull('confirm_at')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cols">
        <div class="card card-stretch card-bordered mb-5">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-column align-items-center align-items-md-start">
                        <div class="d-flex align-items-center mb-10 fw-bold">
                            <span class="btn btn-icon btn-sm btn-danger me-5">
                                <i class="fa fa-users"></i>
                            </span>
                            Total Kandidat Melamar
                        </div>
                        <span class="fs-2tx">{{ $job_app->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-5 mt-5">
    <div class="col-lg-6">
        <div class="card card-stretch card-bordered mb-5">
            <div class="card-header">
                <div class="card-title">
                    <div class="d-flex align-items-center">
                        <span class="btn btn-danger btn-icon btn-sm">
                            <i class="fa fa-briefcase"></i>
                        </span>
                        <h3 class="ms-3">Aktifitas job ad</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route("job_report.index") }}?a=applicant" class="text-primary text-hover-danger">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                @if (count($activity) == 0)
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("theme/assets/media/icons/empty.png") }}" alt="">
                    <span class="mt-3 fw-semibold">Tidak ada riwayat</span>
                </div>
                @else
                    @foreach ($activity as $item)
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-40px symbol-circle me-5">
                                <img src="{{ asset($item['image']) }}" alt="">
                            </div>
                            <div class="d-flex flex-column flex-fill">
                                <span class="fw-bold">{{$item['name']}}</span>
                                <span>{{$item['activity']}} <span class="fw-bold">{{ $item['job'] }}</span> </span>
                            </div>
                            <span>@dateId($item['date'])</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card card-stretch card-bordered mb-5">
            <div class="card-header">
                <div class="card-title">
                    <div class="d-flex align-items-center">
                        <span class="btn btn-danger btn-icon btn-sm">
                            <i class="fa fa-calendar"></i>
                        </span>
                        <h3 class="ms-3">Kalendar</h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route("calendar.index") }}" class="text-primary text-hover-danger">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                @if (count($kalenderAct) == 0)
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("theme/assets/media/icons/empty.png") }}" alt="">
                    <span class="mt-3 fw-semibold">Tidak ada riwayat</span>
                </div>
                @else
                    @php
                        $kalCount = 1;
                    @endphp
                    @foreach ($kalenderAct as $item)
                        @php
                            $int = $item[0];
                        @endphp
                        @if ($kalCount <= 3)
                            @php
                                $kalCount++;
                            @endphp
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px symbol-circle me-5">
                                    <img src="{{ asset($int['image']) }}" alt="">
                                </div>
                                <div class="d-flex flex-column flex-fill">
                                    <span class="fw-bold">{{$int['name']}} - Interview {{ count($item) }}</span>
                                    <span>{{$int['activity']}} <span class="fw-bold">{{ $int['job'] }}</span> </span>
                                </div>
                                <span>@dateId($int['date'])</span>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="card card-custom bg-transparent">
        <div class="card-header border-bottom-0">
            <div class="card-title">
                <div class="d-flex align-items-center">
                    <span class="btn btn-danger btn-icon btn-sm">
                        <i class="fa fa-star"></i>
                    </span>
                    <h3 class="ms-3 flex-fill">Laporan Job Ad</h3>
                </div>
            </div>
            <div class="card-toolbar">
                <a href="{{ route("job_report.index") }}">Lihat Semua</a>
            </div>
        </div>
        <div class="card-body bg-white rounded">
            <div class="row">
                <div class="col-12">
                    <table class="table display">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Nama Job Ad</th>
                                <th class="text-nowrap text-md-center">Status Job Ad</th>
                                <th class="text-nowrap">Dibuat Oleh</th>
                                <th class="text-nowrap text-md-center">Tanggal Dibuat</th>
                                <th class="text-nowrap text-md-center">Tanggal Tayang</th>
                                <th class="text-nowrap text-md-center">Jumlah dilihat</th>
                                <th class="text-nowrap text-md-center">Jumlah pelamar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($job_vac as $item)
                            @php
                                $hasComp = false;
                                $activateText = "Aktifkan";
                                $status = 1;
                                if(!empty($item->company_id)){
                                    $hasComp = true;
                                    if(!empty($item->activate_at)){
                                        $status = 0;
                                        $activateText = "Non Aktifkan";
                                    }
                                }

                                $statusJobAd = "Non Aktif";
                                $titleTooltip = "";
                                if(!empty($item->activate_at)){
                                    $statusJobAd = "Aktif";

                                    if(!empty($item->confirm_at)){
                                        $statusJobAd = "Tayang";
                                    }

                                    if(!empty($item->rejected_at)){
                                        $statusJobAd = "Tolak";
                                        $titleTooltip = $item->rejected_notes;
                                    }

                                }
                            @endphp
                            <tr>
                                <td class="text-nowrap">
                                    <span class="fw-bold">{{$item->position}}</span>
                                </td>
                                <td class="text-center">
                                    <span data-bs-toggle="tooltip" title="{{ $titleTooltip }}">{{ $statusJobAd }}</span>
                                </td>
                                <td class="text-nowrap">
                                    <span class="fw-bold">{{ $user_name[$item->user_id] ?? "-" }}</span>
                                </td>
                                <td class="text-center">{{ date("d/m/Y", strtotime($item->created_at)) }}</td>
                                <td class="text-center">{{ empty($item->confirm_at) ? "-" : date("d/m/Y", strtotime($item->confirm_at)) }}</td>
                                <td class="text-center">{{ $job_views->where('job_id', $item->id)->count() }}</td>
                                <td class="text-center">{{ $job_app->where("job_id", $item->id)->count() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<script>
    function circle_chart() {
        var e = document.querySelectorAll(".circle-chart");
        [].slice.call(e).map((function (e) {
            var t = parseInt(KTUtil.css(e, "height"));
            if (e) {
                var a = e.getAttribute("data-kt-chart-color"),
                    v = e.getAttribute("data-kt-value"),
                    f = e.getAttribute("data-formatter"),
                    o = KTUtil.getCssVariableValue("--bs-" + a),
                    r = KTUtil.getCssVariableValue("--bs-" + a + "-light"),
                    s = KTUtil.getCssVariableValue("--bs-white");
                new ApexCharts(e, {
                    series: [v],
                    chart: {
                        fontFamily: "inherit",
                        height: 110,
                        type: "radialBar"
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: "50%"
                            },
                            dataLabels: {
                                showOn: "always",
                                name: {
                                    show: !1,
                                    fontWeight: "700"
                                },
                                value: {
                                    color: s,
                                    fontSize: "16px",
                                    fontWeight: "700",
                                    offsetY: 5,
                                    show: !0,
                                    formatter: function (e) {
                                        return e + f
                                    }
                                }
                            },
                            track: {
                                background: r,
                                strokeWidth: "100%"
                            }
                        }
                    },
                    colors: [o],
                    stroke: {
                        lineCap: "round"
                    },
                    labels: ["Progress"]
                }).render()
            }
        }))
    }

    $(document).ready(function(){
        circle_chart()
        $("table.display").DataTable({
            bInfo : false
        })
    })
</script>
@endsection
