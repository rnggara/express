@extends('layouts.template')

@section('css')

@endsection

@section('content')
<div class="d-flex flex-column">
    <div class="card card-custom gutter-b mb-5">
        <div class="card-header border-0 pt-5">
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
            <div class="card-toolbar d-none d-md-inline">
                <a href="{{ route('applicant.job.apply_page', $job->id) }}" class="btn btn-{{ !empty($applicant) ? "success" : "primary" }} me-3 {{ !empty($applicant) ? "disabled" : "" }}">{{ !empty($applicant) ? "Sudah Lamar" : "Lamar" }}</a>
                @auth
                <button type="button" class="btn btn-outline btn-outline-primary" id="btn-bookmark" data-id="{{ $job->id }}">{{ !empty($bookmark) ? "Bookmarked" : "Bookmark" }}</button>
                @endauth
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column">
                <span class="mb-3">
                    <i class="fa me-3 fa-clock"></i>
                    {{ $job->job_type_label ?? "Fulltime" }}
                </span>
                <span class="mb-3">
                    <i class="fa me-3 fa-suitcase"></i>
                    {{ $job->yoe }} Tahun
                </span>
                <span class="mb-3">
                    <span class="me-1 fw-semibold">Rp.</span>
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
            <div class="d-flex justify-content-between align-items-md-center flex-column flex-md-row">
                <div class="d-flex align-items-center">
                    <span class="text-info me-5">{{ $nlabel }}</span>
                    <span class="">
                        <i class="fa fa-users text-dark"></i>
                        {{ $total_applicant->count() }} Applicants
                    </span>
                </div>
                <div class="d-flex d-inline d-md-none justify-content-between mt-5">
                    <a href="{{ route('applicant.job.apply_page', $job->id) }}" class="btn w-50 btn-{{ !empty($applicant) ? "success" : "primary" }} me-3 {{ !empty($applicant) ? "disabled" : "" }}">{{ !empty($applicant) ? "Lamar" : "Sudah Lamar" }}</a>
                    @auth
                    <button type="button" class="btn w-50 btn-outline btn-outline-primary" id="btn-bookmark" data-id="{{ $job->id }}">{{ !empty($bookmark) ? "Bookmarked" : "Bookmark" }}</button>
                    @endauth
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-link text-danger w-auto" data-bs-toggle="modal" data-bs-target="#modal_report">
                        <i class="fa fa-info-circle text-danger"></i>
                        Laporkan Iklan Kerja
                    </button>
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
                            <span class="fs-5 fs-md-3 fw-bold">Manfaat dan lainnya</span>
                            <span>{{ $comp_industry->benefit ?? "-" }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column col-md-4 col-12">
                    <h3>Foto Perusahaan</h3>
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
    @if($job_ads->count() > 0)
        <div class="d-flex justify-content-between mb-5 my-10">
            <span class="fs-1 fw-bold">Lowongan di {{$company->company_name}}</span>
            <a href="{{ route("app.cs.detail", $company->id) }}?v=job_ad">Lihat Semua Lowongan</a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-2">
            @foreach ($job_ads->take(4) as $item)
                <div class="cols">
                    <div class="card card-stretch">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="symbol me-5">
                                    <img src="{{ asset($company->icon) }}" alt="" class="w-50px h-auto">
                                </div>
                                <div class="flex-fill d-flex flex-column">
                                    <span class="fw-bold mb-3">{{ $item->position }}</span>
                                    <div class="align-items-md-center d-flex flex-column flex-md-row">
                                        <span class="me-5">
                                            <i class="fa fa-clock text-dark me-3"></i>
                                            {{ $item->job_type == 1 ? "Fulltime" : ($item->job_type == 2 ? "Freelance" : "Contract") }}
                                        </span>
                                        <span class="me-5">
                                            <i class="fa fa-suitcase text-dark me-3"></i>
                                            {{ $item->yoe }} tahun
                                        </span>
                                        <span class="me-5">
                                            <span class="fw-semibold text-dark me-2">Rp.</span>
                                            @if ($item->show_salary == 1)
                                                {{ number_format($item->salary_min, 2, ",", ".") }}{{ !empty($item->salary_max) ? " - ".number_format($item->salary_max, 2, ",", ".") : '' }}
                                                /month
                                            @else
                                                Kompetitif Salary
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
</div>

<div class="modal fade" tabindex="-1" id="modal_report">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="modal-title">Laporkan Iklan Kerja</h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>
            <form action="{{ route('applicant.job.report') }}" method="post">
                <div class="modal-body">
                    <div class="fv-row">
                        <label for="" class="col-form-label required">Pelapor</label>
                        <input type="email" name="pelapor" id="pelapor" class="form-control" required placeholder="Input email">
                    </div>
                    <div class="fv-row">
                        <label for="" class="col-form-label required">Headline</label>
                        <input type="text" name="headline" id="headline" class="form-control" required placeholder="Input headline">
                    </div>
                    <div class="fv-row">
                        <label for="" class="col-form-label required">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" required placeholder="Input Deskripsi" cols="30" rows="10"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="id_job" value="{{ $job->id }}">
                    <button type="button" class="btn text-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (\Session::has("report"))
<div class="modal fade" tabindex="-1" id="modal_report_result">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <span class="fw-bold mb-3">Laporan Terkirim!</span>
                    <span class="mb-5">Terima kasih atas laporannya, tim kami akan segera menginvestigasi terkait laporan kamu.</span>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Oke</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('custom_script')
    <script src="{{ asset("theme/assets/plugins/custom/fslightbox/fslightbox.bundle.js") }}"></script>
    @include('job._bookmark')
    <script>
        $(document).ready(function(){
            $("#btn-bookmark").click(function(){
                var _id = $(this).data("id")
                bookmark(this, _id, function(resp){
                    if(resp.booked){
                        $("#btn-bookmark").html("Bookmarked")

                        show_toast("Ditambahkan ke bookmark")
                    } else {
                        $("#btn-bookmark").html("Bookmark")
                        show_toast("Dihapus dari bookmark")
                    }
                })
            })

            @if (\Session::has("report"))
                $("#modal_report_result").modal("show")
            @endif
        })
    </script>
@endsection
