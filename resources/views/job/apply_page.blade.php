@extends('layouts.template')

@section('css')

@endsection

@if (\Helper_function::getProfile() < 100)
    @section('rside')
    @php
        $pct = \Helper_function::getProfile()
    @endphp
        <div class="rside d-none">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-column">
                            <span class="fw-bold">Kelengkapan Profil {{ $pct }}%</span>
                            <span class="text-muted">Lengkapkan profil Anda untuk meningkatkan kesempatan Anda</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-title">Personal Data</span>
                                <span class="menu-arrow"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif

@section('content')
<div class="row">
        <div class="col-md-3 mb-5">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between rounded p-3">
                        <div class="d-flex">
                            <div class="symbol  me-5">
                                <img src="{{ asset($company->icon ?? "theme/assets/media/avatars/blank.png") }}" class="w-50px h-auto" alt="">
                            </div>
                            <div class="d-flex flex-column">
                                <span class="font-size-h3 fw-bold">{{ $job->position }}</span>
                                <span class="fw-semibold">{{ $company->company_name ?? '-' }}</span>
                                <span class="fw-semibold mb-3">{{ $comp_city->name ?? $job->placement }} -
                                    {{ $comp_prov->name ?? '' }}</span>
                                <span class="mb-3">
                                    <i class="fa fa-clock text-dark me-2"></i>
                                    {{ $job->job_type_label ?? 'Fulltime' }}
                                </span>
                                <span class="mb-3">
                                    <i class="fa fa-suitcase text-dark me-2"></i>
                                    {{ $job->yoe }} tahun
                                </span>
                                <span class="mb-3">
                                    <span class="me-2 fw-semibold">Rp.</span>
                                    {{ number_format($job->salary_min, 2, ",", ".") }}{{ !empty($job->salary_max) ? " - ".number_format($job->salary_max, 2, ",", ".") : '' }}
                                    /bulan
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            @php
                $score = \Helper_function::getProfile();
            @endphp
            @if ($score < 50)
                <div class="alert alert-warning alert-custom">
                    <div class="d-flex flex-column">
                        <span class="alert-label mb-5">Untuk mengirim lamaran, Anda harus melengkapi profil Anda minimal 50%.</span>
                        <div>
                            <a href="{{ route("complete.profile.page") }}" class="btn btn-warning w-100 w-md-auto">Lengkapi Profil</a>
                        </div>
                    </div>
                </div>
            @else
            <form action="{{ route('applicant.job.apply') }}" method="post" enctype="multipart/form-data">
                <div class="card bg-transparent card-custom">
                    <div class="card-header border-0 px-0">
                        <h3 class="card-title">
                            Profil Saya
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route("account.info") }}?v=view_profile" target="_blank" class="text-muted">View</a> <span class="mx-3">|</span> <a href="{{ route("account.info") }}?v=personal_data&act=edit" target="_blank" class="text-muted">Edit</a>
                        </div>
                    </div>
                    <div class="card-body bg-white rounded border">
                        @php
                            $tgl = $profile->birth_date ?? null;
                        @endphp
                        <h3>Profil Saya</h3>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Nama Lengkap</label>
                                    <span>{{ $profile->name ?? $applicant->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Jenis Kelamin</label>
                                    <span>{{ $profile->gender ?? "-" }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Tanggal Lahir</label>
                                    <span>
                                        @if (empty($tgl))
                                            -
                                        @else
                                            @dateId($tgl)
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Nomor Telepon</label>
                                    <span>{{ $profile->phone ?? "-" }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Email</label>
                                    <span>{{ $profile->email ?? $applicant->email }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Status Pernikahan</label>
                                    <span>{{ $profile->marital_status ?? "-" }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Agama</label>
                                    <span>{{ $profile->religion ?? "-" }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Provinsi</label>
                                    <span>{{ $provinsi->name ?? "-" }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Kota</label>
                                    <span>{{ $kota->name ?? "-" }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label font-weight-bolder">Alamat</label>
                                    <span>{!! $profile->address ?? "-" !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($job_question->count() > 0)
                    <div class="card bg-transparent card-custom">
                        <div class="card-header border-0 px-0">
                            <h3 class="card-title required">
                                Pertanyaan dari pemberi kerja</span>
                            </h3>
                        </div>
                        <div class="card-body bg-white rounded border">
                            <div class="d-flex flex-column">
                                @foreach ($job_question as $item)
                                    <div class="d-flex flex-column mb-5">
                                        <span class="fw-bold mb-3">{{ $item->label }}</span>
                                        @if ($item->type == "3")
                                            <div class="fv-row">
                                                <textarea name="question[{{ $item->id }}]" required class="form-control" id="" cols="30" rows="10"></textarea>
                                            </div>
                                        @else
                                            @php
                                                $opt = $job_opt->where("q_id", $item->id);
                                                $optTp = $item->type == 1 ? "radio" : "checkbox";
                                                $optName = $item->type == 1 ? "question[$item->id]" : "question[$item->id][]"
                                            @endphp
                                            @foreach ($opt as $qOpt)
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" name="{{ $optName }}" type="{{ $optTp }}" value="{{ $qOpt->id }}" id="opt{{ $item->id."_".$qOpt->id }}" />
                                                    <label class="form-check-label" for="opt{{ $item->id."_".$qOpt->id }}">
                                                        {{ $qOpt->label }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card bg-transparent card-custom">
                    <div class="card-header border-0 px-0">
                        <h3 class="card-title">
                            Surat Lamaran <span class="text-muted">(Optional)</span>
                        </h3>
                    </div>
                    <div class="card-body bg-white rounded border">
                        <div class="row">
                            <div class="col-12">
                                <span class="text-muted">Silakan tulis surat lamaran Anda di bawah. Apabila Anda sudah memiliki, silakan unggah di bagian “Attachment”</span>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Surat Lamaran</label>
                                    <textarea name="cover_letter" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Upload Surat Lamaran</label>
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
                </div>
                <div class="card bg-transparent card-custom">
                    <div class="card-header border-0 px-0">
                        <h3 class="card-title">
                            Curriculum Vitae/CV <span class="text-muted">(Optional)</span>
                        </h3>
                    </div>
                    <div class="card-body bg-white rounded border">
                        <div class="row">
                            <div class="col-12">
                                <span class="text-muted">Silakan unggah CV Anda atau lengkapi profil Anda sebagai CV.</span>
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
                </div>
                <div class="d-flex justify-content-end mt-5">
                    @csrf
                    <input type="hidden" name="id" value="{{ $job->id }}">
                    <a href="{{ route("applicant.job.detail", $job->id) }}" class="btn text-primary">Batal</a>
                    <button type="submit" class="btn btn-primary">Lamar</button>
                </div>
            </form>
            @endif
        </div>
    </div>
@endsection
