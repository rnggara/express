@extends('layouts.template')

@section('css')

@endsection

@section('content')
<div class="d-flex">
    <div class="container-fluid">
        @if(empty($job->confirm_at) && empty($job->rejected_at))
        <div class="d-flex mb-8 justify-content-between align-items-center">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-1">Review Job Ads</span>
                <span>Silahkan meninjau job ad dengan  baik dan seksama, klik approve untuk mempublish job ads</span>
            </div>
            <form action="{{ route("bp.job_ads.review_post") }}" method="post">
                <div class="d-flex">
                    @csrf
                    <input type="hidden" name="id" value="{{$job->id}}">
                    <button type="submit" name="submit" value="1" class="btn btn-primary me-5">Approve</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalTolak" class="btn btn-outline btn-outline-primary">Tolak</button>
                </div>
            </form>
        </div>
        @endif
        <div class="card card-custom gutter-b mb-5">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="card-icon">
                        <div class="symbol symbol-circle me-3">
                            <img src="{{ asset($company->icon ?? "theme/assets/media/avatars/blank.png") }}" alt="">
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="{{ route('applicant.job.detail', $job->id) }}" class="font-size-h3 fw-bold text-dark text-hover-primary">{{ $job->position }}</a>
                        <ol class="breadcrumb breadcrumb-dot fs-6 fw-semibold">
                            <li class="breadcrumb-item">
                                <span class="font-weight-bolder">{{ $company->company_name ?? "-" }}</span>
                            </li>
                            <li class="breadcrumb-item">
                                <span class="font-size-h5">{{ ucwords(strtolower($comp_city->name ?? "")) }}, {{ $comp_prov->name ?? "" }}</span>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="mb-3">
                        <i class="text-dark fa fa-clock"></i>
                        {{ $jtype->name ?? "Fulltime" }}
                    </span>
                    <span class="mb-3">
                        <i class="text-dark fa fa-suitcase"></i>
                        {{ $job->yoe }} years
                    </span>
                    <span class="mb-3">
                        <span class="me-1">Rp. </span>
                        {{ number_format($job->salary_min, 2, ",", ".") }}{{ !empty($job->salary_max) ? " - ".number_format($job->salary_max, 2, ",", ".") : "" }} /month
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
                        $nlabel = "$days days ago";
                    }
                    if($months > 0){
                        $nlabel = "$months months ago";
                    }
                    if($years > 0){
                        $nlabel = "$years years ago";
                    }
                @endphp
                <div class="d-flex justify-content-between">
                    <span class="text-info">Posted {{ $nlabel }}</span>
                    <button type="button" class="btn btn-link text-danger" data-bs-toggle="modal" data-bs-target="#modal_report">
                        <i class="fa fa-info-circle text-danger"></i>
                        Report job ads
                    </button>
                </div>
            </div>
        </div>
        <div class="card card-custom gutter-b mb-5">
            <div class="card-header border-0">
                <div class="card-title">
                    <h3 class="card-label">Job Description</h3>
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
                                <span>{{$company->reg_num ?? "-"}}</span>
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
        <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1>Lamar</h1>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <form action="{{ route('applicant.job.apply') }}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    @if (empty(Session::get('app_profile')))
                                        <div class="alert alert-warning alert-custom">
                                            <span class="alert-label">Anda belum melengkapi data personal. Silahkan untuk melengkapi data personal di Profile Saya > Personal Data</span>
                                        </div>
                                    @else
                                        @php
                                            $profile = Session::get("app_profile");
                                            $tgl = $profile->birth_date;
                                        @endphp
                                        <div class="border rounded p-3">
                                            <h3>Profile Saya</h3>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Nama Lengkap</label>
                                                        <span>{{ $profile->name }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Jenis Kelamin</label>
                                                        <span>{{ $profile->gender }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Tanggal Lahir</label>
                                                        <span>@dateId($tgl)</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Nomor Telepon</label>
                                                        <span>{{ $profile->phone }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Email</label>
                                                        <span>{{ $profile->email }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Status Pernikahan</label>
                                                        <span>{{ $profile->marital_status }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Agama</label>
                                                        <span>{{ $profile->religion }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Provinsi</label>
                                                        <span>{{ $provinsi->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Kota</label>
                                                        <span>{{ $kota->name }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label font-weight-bolder">Alamat</label>
                                                        <span>{!! $profile->address !!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="border rounded p-3">
                                            <h3>Cover Letter <span class="text-muted">(Optional)</span></h3>
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class="text-muted">Silahkan tuliskan cover letter anda di bawah. Tapi apabila anda sudah memilikinya, silahkan menguploadnya di bagian “Upload cover letter”</span>
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Cover Letter</label>
                                                        <textarea name="cover_letter" class="form-control" id="" cols="30" rows="10"></textarea>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <label for="" class="col-form-label">Upload Cover Letter</label>
                                                        <div class="upload-file">
                                                            <label for="file-cover-letter" class="btn btn-secondary btn-sm">Attachment</label>
                                                            <span class="text-muted">format : PDF</span>
                                                            <span class="upload-file-label"></span>
                                                            <input id="file-cover-letter" data-toggle="upload_file" style="display: none" name="file_cover_letter" accept=".pdf" type="file"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="border rounded p-3">
                                            <h3>Lampiran <span class="text-muted">(Optional)</span></h3>
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class="text-muted">Silahkan upload CV anda apabila anda sudah memiliki sebelumnya. Tapi kita sudah membuatkan CV untuk anda berdasarkan profile yang anda isi. Lihat profile anda di <a href="">profile saya</a></span>
                                                    <div class="d-flex flex-column">
                                                        <label class="col-form-label">Upload CV</label>
                                                        <div class="upload-file">
                                                            <label for="file-attachment" class="btn btn-secondary btn-sm">Attachment</label>
                                                            <span class="text-muted">format : PDF</span>
                                                            <span class="upload-file-label"></span>
                                                            <input id="file-attachment" data-toggle="upload_file" style="display: none" name="attachment" accept=".pdf" type="file"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            @csrf
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            @if (!empty(Session::get('app_profile')))
                            <input type="hidden" name="id" value="{{ $job->id }}">
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route("bp.job_ads.review_post") }}" method="post">
    <div class="modal fade" tabindex="-1" id="modalTolak">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <span class="fw-semibold">Tolak Tayang Job Ad</span>
                            <div class="cursor-pointer" data-bs-dismiss="modal">
                                <i class="fa fa-times text-dark"></i>
                            </div>
                        </div>
                        <div class="fv-row">
                            <label for="reason" class="col-form-label fw-bold">Alasan</label>
                            <textarea name="reason" id="reason" required class="form-control" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="id" value="{{$job->id}}">
                    <button type="button" class="btn btn-outline btn-outline-dark bg-white" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="submit" value="-1" class="btn btn-primary">Kirim</button>
                </div>
            </div>
        </div>
    </div>
</form>

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

@if (\Session::has("tayang"))
<div class="modal fade" tabindex="-1" id="modal_msg_result">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/megaphone.png") }}" alt="" class="mb-3">
                    <span class="fw-bold mb-3">Job Ad Approval</span>
                    <span class="mb-5">Terimakasih, Job Ad yang anda setujui sudah<br>tayang di job portal kerjaku</span>
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

            @if (\Session::has("tayang"))
                @if(\Session::get("tayang") == 1)
                    $("#modal_msg_result").modal("show")
                @endif
            @endif
        })
    </script>
@endsection
