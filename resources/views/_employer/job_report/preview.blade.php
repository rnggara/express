@extends('layouts.template')

@section('css')

@endsection

@section('content')
<div class="d-flex flex-column">
    <div class="container-fluid">
        <a href="{{ route("job_report.index") }}" class="text-primary mb-5">
            <i class="fa fa-chevron-left text-primary"></i>
            Kembali
        </a>
        <div class="card card-custom gutter-b mb-5 mt-5">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="card-icon">
                        <div class="symbol me-3">
                            <img src="{{ asset($company->icon ?? "theme/assets/media/avatars/blank.png") }}" alt="" class="w-50px h-auto">
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="{{ route('applicant.job.detail', $job->id) }}" class="font-size-h3 fw-bold text-dark text-hover-primary">{{ $job->position }}</a>
                        <ol class="breadcrumb breadcrumb-dot fs-6 fw-semibold">
                            <li class="breadcrumb-item">
                                <span class="font-weight-bolder">{{ $company->company_name ?? "-" }}</span>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="font-size-h5">{{ ucwords(strtolower($comp_city->name ?? "-")) }}, {{ $comp_prov->name ?? "" }}</span>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="card-toolbar">
                    {{-- <a href="{{ route('applicant.job.apply_page', $job->id) }}" class="btn btn-primary me-3 {{ !empty($applicant) ? "disabled" : "" }}">Lamar</a>
                    @auth
                    <button type="button" class="btn btn-outline btn-outline-primary" id="btn-bookmark" data-id="{{ $job->id }}">{{ !empty($bookmark) ? "Bookmarked" : "Bookmark" }}</button>
                    @endauth --}}
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="mb-3">
                        <i class="fa text-dark me-3 fa-clock"></i>
                        {{ $jtype->name ?? "Fulltime" }}
                    </span>
                    <span class="mb-3">
                        <i class="fa text-dark me-3 fa-suitcase"></i>
                        {{ $job->yoe }} tahun
                    </span>
                    <span class="mb-3">
                        <span class="me-2 fw-semibold">Rp. </span>
                        {{ number_format($job->salary_min, 2, ",", ".") }}{{ !empty($job->salary_max) ? " - ".number_format($job->salary_max, 2, ",", ".") : "" }} /bulan
                    </span>
                </div>
            </div>
            <div class="card-footer border-0">
                @php
                    $d1 = date_create(date("Y-m-d"));
                    $d2 = date_create(date("Y-m-d", strtotime($job->created_at)));
                    $d = date_diff($d1, $d2);
                    $days = $d->format("%a");
                    $months = $d->format("%m");
                    $years = $d->format("%y");
                    $nlabel = "Today";
                    if($days > 0){
                        $nlabel = "$days hari yang lalu";
                    }
                    if($months > 0){
                        $nlabel = "$months bulan yang lalu";
                    }
                    if($years > 0){
                        $nlabel = "$years tahun yang lalu";
                    }
                @endphp
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="text-info me-5">{{ $nlabel }}</span>
                        <span class="">
                            <i class="fa fa-users text-dark"></i>
                            {{ $total_applicant->count() }} Applicants
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b mb-5">
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="card-label">Deskripsi Pekerjaan</h3>
                </div>
            </div>
            <div class="card-body">
                {!! $job->job_description !!}
            </div>
        </div>
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="row">
                    <div class="d-flex flex-column col-md-8 col-12">
                        <div class="d-flex align-items-center mb-10">
                            <div class="symbol symbol-circle me-3">
                                <img src="{{ asset($company->icon ?? "theme/assets/media/avatars/blank.png") }}" class="w-50px h-auto" alt="">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <span class="font-size-h3 fw-bold">{{ $company->company_name ?? "-" }}</span>
                                <span class="fw-semibold">{{ ucwords(strtolower($comp_city->name ?? "-")) }}, {{ $comp_prov->name ?? "" }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <span class="fs-5 fs-md-3 fw-bold">Tentang Perusahaan:</span>
                            <p>{!! $company->descriptions !!}</p>
                        </div>
                        <div class="row row-cols-1 row-cols-md-3">
                            <div class="col flex-column d-flex mb-5 mb-md-10">
                                <span class="fs-5 fs-md-3 fw-bold">Nomor Registrasi</span>
                                <span>{{$company->reg_num}}</span>
                            </div>
                            <div class="col flex-column d-flex mb-5 mb-md-10">
                                <span class="fs-5 fs-md-3 fw-bold">Jumlah Karyawan</span>
                                <span>{{$company->skala_usaha ?? "-"}}</span>
                            </div>
                            <div class="col flex-column d-flex mb-5 mb-md-10">
                                <span class="fs-5 fs-md-3 fw-bold">Lokasi</span>
                                <span>{{ ucwords(strtolower($comp_city->name ?? "-")) }}, {{ $comp_prov->name ?? "" }}</span>
                            </div>
                            <div class="col flex-column d-flex mb-5 mb-md-10">
                                <span class="fs-5 fs-md-3 fw-bold">Waktu Rata-rata Proses Lamaran</span>
                                <span>-</span>
                            </div>
                            <div class="col flex-column d-flex mb-5 mb-md-10">
                                <span class="fs-5 fs-md-3 fw-bold">Industri</span>
                                <span>{{ $comp_industry->name ?? "-" }}</span>
                            </div>
                            <div class="col flex-column d-flex mb-5 mb-md-10">
                                <span class="fs-5 fs-md-3 fw-bold">Manfaat dan Lainnya</span>
                                <span>-</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column col-md-4 col-12">
                        <h3>Company Photo</h3>
                        <a class="d-block overlay h-75" data-fslightbox="lightbox-basic" href="{{ asset($banner->file_address ?? "images/image_placeholder.png") }}">
                            <!--begin::Image-->
                            <div class="overlay-wrapper bgi-no-repeat h-100 bgi-position-center bgi-size-cover card-rounded min-h-175px"
                                style="background-image:url('{{ asset($banner->file_address ?? "images/image_placeholder.png") }}')">
                            </div>
                            <!--end::Image-->

                            <!--begin::Action-->
                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                <i class="bi bi-eye-fill text-white fs-3x"></i>
                            </div>
                            <!--end::Action-->
                        </a>
                        <div class="row row-cols-3 mt-5">
                            @for ($i = 2; $i<=4; $i++)
                                @php
                                    $_fComp = $comp_photos[$i - 1] ?? [];
                                @endphp
                                <div class="col">
                                    <a class="d-block overlay" data-fslightbox="lightbox-basic" href="{{ asset($_fComp->file_address ?? "images/image_placeholder.png") }}">
                                        <!--begin::Image-->
                                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-100px w-100"
                                            style="background-image:url('{{ asset($_fComp->file_address ?? "images/image_placeholder.png") }}')">
                                        </div>
                                        <!--end::Image-->

                                        <!--begin::Action-->
                                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                            <i class="bi bi-eye-fill text-white fs-3x"></i>
                                        </div>
                                        <!--end::Action-->
                                    </a>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_script')
    <script src="{{ asset("theme/assets/plugins/custom/fslightbox/fslightbox.bundle.js") }}"></script>
    <script>
        $(document).ready(function(){
        })
    </script>
@endsection
