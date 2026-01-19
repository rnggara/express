@extends('layouts.template', ['bgWrapper' => "bg-white"])

@section('content')

<div class="container">
    <div class="d-flex flex-column">
        <a href="{{ $returnUri }}" class="d-flex align-items-center mb-3">
            <i class="fa fa-arrow-left me-3 text-primary"></i>
            Kembali
        </a>
        <div class="card border mb-8">
            <div class="card-body">
                <div class="d-flex align-items-start flex-column flex-md-row">
                    <div class="d-flex align-items-start">
                        <div class="symbol symbol-30px symbol-circle symbol-md-70px me-5">
                            <img src="{{ asset($applicant->user_img ?? 'theme/assets/media/avatars/blank.png') }}" alt="image">
                        </div>
                        <div class="d-flex flex-fill flex-column">
                            <span class="fs-3 mb-3 fw-bold">{{ $applicant->name }}</span>
                            <span class="mb-1">{{ $exp->position ?? "" }} {{ "(".($exp->yoe ?? 0)." Tahun)" }}</span>
                            <span class="mb-1">{{ $exp->company ?? "" }}</span>
                            <span class="mb-1">Gaji yang diharapkan Rp. {{ number_format($exp->salary ?? 0, 2, ",", ".") }}</span>
                        </div>
                    </div>
                    @if($job_applicant->status == 0)
                        <form action="{{route('job_report.update')}}" method="post">
                            <div class="d-flex">
                                @csrf
                                <input type="hidden" name="id" value="{{$job_applicant->id}}">
                                <input type="hidden" name="returnUri" value="{{$returnUri}}">
                                <button type="submit" name="submit" value="1" class="btn btn-outline me-3">
                                    <i class="far fa-thumbs-up text-success"></i>
                                    <span>Pilih</span>
                                </button>
                                <button type="submit" name="submit" value="-1" class="btn btn-outline">
                                    <i class="fa fa-ban text-danger"></i>
                                    <span>Reject</span>
                                </button>
                            </div>
                        </form>
                    @else
                        @php
                            $btnName = "Rejected";
                            $btnClass = "danger";
                            $btnIcon = "ban";
                            if($job_applicant->status == 1){
                                $btnName = "Dipilih";
                                $btnClass = "success";
                                $btnIcon = "check";
                            } elseif($job_applicant->status == 2){
                                $btnName = "Interview";
                                $btnClass = "success";
                                $btnIcon = "video";
                            }
                        @endphp
                        <button type="button" disabled value="-1" class="btn btn-outline">
                            <i class="fa fa-{{$btnIcon}} text-{{$btnClass}}"></i>
                            <span>{{$btnName}}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card border mb-8 card-px-0 px-5">
            <div class="card-body ">
                <div class="accordion accordion-icon-toggle" id="kt_accordion_profile">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_profile_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-user fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Data Pribadi
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_profile_item_1" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_profile">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Nama Lengkap</label>
                                            <span>{{ $profile->name ?? $applicant->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Jenis Kelamin</label>
                                            <span>{{ $profile->gender ?? "-" }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Tanggal Lahir</label>
                                            <span>
                                                @if (empty($profile))
                                                    -
                                                @else
                                                    @if (empty($profile->birth_date))
                                                    @else
                                                        @dateId($profile->birth_date)
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Nomor Telepon</label>
                                            <span>{{ $profile->phone ?? $applicant->phone }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Email</label>
                                            <span>{{ $profile->email ?? $applicant->email }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Status Pernikahan</label>
                                            <span>{{ $profile->marital_status ?? "-" }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Agama</label>
                                            <span>{{ $profile->religion ?? "-" }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Provinsi</label>
                                            <span>{{ $data['prov']->where("id", $profile->prov_id)->first()->name ?? "-" }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Kota</label>
                                            <span>{{ $data['city']->where("id", $profile->city_id)->first()->name ?? "-" }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="d-flex flex-column">
                                            <label for="" class="col-form-label">Alamat</label>
                                            <span>{{ $profile->address ?? "-" }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_experience">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_experience_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-briefcase fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Pengalaman Kerja
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_experience_item_1" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_experience">
                            @if($experience->count() == 0)
                            No data
                            @else
                                @foreach ($experience as $item)
                                    <div class="d-flex flex-column mb-5">
                                        <span class="fw-bold">{{ $item->company }}</span>
                                        <span class="fw-bold">{{ $item->position }}</span>
                                        <span>@monthId($item->start_date) {{ date("Y", strtotime($item->start_date)) }} - @if ($item->still == 1) Sekarang @else @monthId($item->end_date) {{ date("Y", strtotime($item->end_date)) }}  @endif</span>
                                        <span class="mb-3">{{ $item->location }}</span>
                                        <span>Deskripsi Pekerjaan:</span>
                                        <span class="mb-3">{!! $item->descriptions !!}</span>
                                        <span>Prestasi Kerja:</span>
                                        <span class="mb-3">{!! $item->achievements !!}</span>
                                        <span>Referensi:</span>
                                        <span class="mb-3">{!! $item->reference." ($item->phone)" !!}</span>
                                        <span>Gaji:</span>
                                        <span class="mb-3">{!! number_format($item->salary, 2, ",", ".") !!}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_education">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_education_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-book fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Pendidikan
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_education_item_1" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_education">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-column mb-8">
                                    <span class="fw-bold mb-5">Pendidikan Formal</span>
                                    @if($edu_formal->count() == 0)
                                    No data
                                    @else
                                        @foreach ($edu_formal as $item)
                                            <div class="d-flex flex-column mb-5">
                                                <span class="fw-bold">{{ $item->school_name }}</span>
                                                <span class="fw-bold">{{ $item->degree." ".$item->field_of_study }}</span>
                                                <span class="mb-3">@monthId($item->start_date) {{ date("Y", strtotime($item->start_date)) }} - @if ($item->still == 1) Sekarang @else @monthId($item->end_date) {{ date("Y", strtotime($item->end_date)) }}  @endif</span>
                                                <span>Deskripsi:</span>
                                                <span class="mb-3">{!! $item->descriptions !!}</span>
                                                <span>Dokumen yang diupload:</span>
                                                <span class="mb-3">-</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="d-flex flex-column mb-8">
                                    <span class="fw-bold mb-5">Pendidikan Informal</span>
                                    @if($edu_informal->count() == 0)
                                    No data
                                    @else
                                        @foreach ($edu_informal as $item)
                                            <div class="d-flex flex-column mb-5">
                                                <span class="fw-bold">{{ $item->vendor }}</span>
                                                <span class="fw-bold">{{ $item->course_name }}</span>
                                                <span class="mb-3">@monthId($item->start_date) {{ date("Y", strtotime($item->start_date)) }} - @if ($item->still == 1) Sekarang @else @monthId($item->end_date) {{ date("Y", strtotime($item->end_date)) }}  @endif</span>
                                                <span>Deskripsi:</span>
                                                <span class="mb-3">{!! $item->descriptions !!}</span>
                                                <span>Dokumen yang diupload:</span>
                                                <span class="mb-3">-</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_language">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_language_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-questionnaire-tablet fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Kemampuan Bahasa
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_language_item_1" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_language">
                            @if ($languages->count() == 0)
                                No Data
                            @else
                                @foreach ($languages as $item)
                                    <div class="d-flex mb-8 justify-content-between flex-column flex-md-row">
                                        <div class="d-flex flex-column mb-5">
                                            <span class="mb-md-3">Bahasa</span>
                                            <span class="fw-bold">{{ $data['language'][$item->language] }}</span>
                                        </div>
                                        <div class="d-flex flex-column mb-5">
                                            <span class="mb-md-3">Kemampuan Menulis</span>
                                            <span class="fw-bold">{{ "$item->writing/5" }}</span>
                                        </div>
                                        <div class="d-flex flex-column mb-5">
                                            <span class="mb-md-3">Kemampuan Berbicara</span>
                                            <span class="fw-bold">{{ "$item->speaking/5" }}</span>
                                        </div>
                                        <div class="d-flex flex-column mb-5">
                                            <span class="mb-md-3">Kemampuan Membaca</span>
                                            <span class="fw-bold">{{ "$item->reading/5" }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_skill">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_skill_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-questionnaire-tablet fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Kemampuan
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_skill_item_1" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_skill">
                            @if ($skills->count() == 0)
                                No Data
                            @else
                                @foreach ($skills as $item)
                                    <div class="d-flex mb-8">
                                        <div class="d-flex flex-column me-8">
                                            <span class="mb-3">Kemampuan</span>
                                            <span class="fw-bold">{{ $item->skill_name }}</span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="mb-3">Kemahiran</span>
                                            <span class="fw-bold">{{ $data['proficiency'][$item->proficiency] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_portofolio">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_portofolio_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-icon fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Portofolio
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_portofolio_item_1" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_portofolio">
                            @empty($portofolio)
                                No Data
                            @else
                                @if(!empty($portofolio->website))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Website</label>
                                            <label class="col-form-label">{{ $portofolio->website ?? "" }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($portofolio->behance))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Behance</label>
                                            <label class="col-form-label">{{ $portofolio->behance ?? "" }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($portofolio->dribble))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Dribble</label>
                                            <label class="col-form-label">{{ $portofolio->dribble ?? "" }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($portofolio->github))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Github</label>
                                            <label class="col-form-label">{{ $portofolio->github ?? "" }}</  label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($portofolio->mobile))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Aplikasi Mobile</label>
                                            <label class="col-form-label">{{ $portofolio->mobile ?? "" }}</  label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($portofolio->others))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Link lain</label>
                                            <label class="col-form-label">{{ $portofolio->others ?? "" }}</  label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endempty
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_medsos">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_medsos_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-phone fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Media Sosial
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_medsos_item_1" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_medsos">
                            @empty($medsos)
                                No Data
                            @else
                                @if(!empty($medsos->linkedin))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Linkedin</label>
                                            <label class="col-form-label">{{ $medsos->linkedin ?? "" }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($medsos->facebook))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Facebook</label>
                                            <label class="col-form-label">{{ $medsos->facebooke ?? "" }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($medsos->instagram))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Instagram</label>
                                            <label class="col-form-label">{{ $medsos->instagram ?? "" }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(!empty($medsos->twitter))
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="fw-semibold col-form-label w-100">Twitter</label>
                                            <label class="col-form-label">{{ $medsos->twitter ?? "" }}</  label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endempty
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_test">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_test_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-book fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Hasil Test
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_test_item_1" class="fs-6 collapse" data-bs-parent="#kt_accordion_test">
                            @if (count($test_list) > 0)
                                <div class="d-flex flex-column">
                                    @foreach ($test as $item)
                                        @if (isset($test_list[$item->id]))
                                            @php
                                                $last = $test_list[$item->id][0];
                                                $test_uri = "";
                                                $rpoint = $last->result_point;
                                                if($item->category_id == 3){
                                                    $test_uri = route('test.papikostik.psikogram', $last->id)."?uid=$last->user_id";
                                                } elseif($item->category_id == 5){
                                                    $test_uri = route('test.disc.psikogram', $last->id);
                                                } elseif($item->category_id == 2){
                                                    $test_uri = route("test.mbti.psikogram", $last->id);
                                                } elseif($item->category_id == 4){
                                                    $test_uri = route("test.wpt.psikogram", $last->id);
                                                    $last_wpt = $wpt->where("test_result_id", $last->id)->first();
                                                    $true = $last_wpt->true;
                                                    $wrong = $last_wpt->wrong;
                                                    $rpoint = ($true / ($true + $wrong)) * 100;
                                                }

                                                $answered = 0;

                                                if($item->category_id == 6){
                                                    $answered = $last->att_detail_result;
                                                }

                                                $d1 = date_create($last->created_at);
                                                $d2 = date_create($last->result_end);
                                                $diff = date_diff($d1, $d2);
                                                $H = $diff->format("%h");
                                                $m = $diff->format("%i");
                                                $elTime = $m."m";
                                                if($H > 0){
                                                    $elTime = $H."j, ".$m."m";
                                                }
                                            @endphp
                                            <div class="d-flex flex-column mb-5">
                                                <span class="fw-bold mb-1">{{ $item->label }}</span>
                                                @if (!in_array($item->category_id, [2, 3, 5, 7]))
                                                    <span>Score : {{ $last->result_point }}</span>
                                                    <span>Waktu Test : {{ $elTime }}</span>
                                                @else
                                                    @if ($item->category_id == 7)
                                                        @php
                                                            $cb_res = json_decode($last->color_blind_result ?? "[]", true);
                                                        @endphp
                                                        <div class="d-flex">
                                                            <span class="me-5">Kondisi mata anda adalah :</span>
                                                            <span class="fw-bold">{{ $cb_res['text'] ?? "-" }}</span>
                                                        </div>
                                                    @endif
                                                    @if ($test_uri != "")
                                                        <div class="d-flex">
                                                            <span class="me-5">Hasil Tes :</span>
                                                            <a href="{{ $test_uri }}" class="text-primary">Lihat Hasil disini</a>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                No Data
                            @endif
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_additional">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_additional_item_1">
                            <h3 class="fs-4 fw-semibold mb-0">
                                <i class="ki-duotone ki-questionnaire-tablet fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Tambahan
                            </h3>
                            <span class="accordion-icon">
                                <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_additional_item_1" class="fs-6 collapse ps-10" data-bs-parent="#kt_accordion_additional">
                            <div class="d-flex flex-column">
                                <div class="row accordion {{ $data['family']->count() == 0 ? "mx-0 mb-5" : "" }}" id="accordion-family">
                                    @if ($data['family']->count() === 0)
                                        <button type="button" class="btn btn-outline text-dark text-hover-dark" disabled>
                                            Tidak ada informasi keluarga
                                        </button>
                                    @else
                                    @foreach ($data['family'] as $i => $item)
                                            <div class="col-12 accordion mb-5">
                                                <div class="d-flex flex-column border rounded">
                                                    <div class="d-flex justify-content-between accordion accordion-icon-collapse p-5 align-items-center">
                                                        <span class="fw-bold">Informasi Keluarga {{ $i+1 }}</span>
                                                        <div class="d-flex justify-content-between border-0">
                                                            <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                                <div class="accordion-heder collapsed" data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}" aria-controls="collapse{{ $item->id }}">
                                                                    <span class="accordion-icon">
                                                                        <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                        <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="collapse{{ $item->id }}" class="collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-family">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="d-flex flex-column mb-3">
                                                                    <span class="fw-bold">Nama</span>
                                                                    <span class="fw-semibold">{{ $item->name }}</span>
                                                                </div>
                                                                <div class="d-flex flex-column mb-3">
                                                                    <span class="fw-bold">Relasi</span>
                                                                    <span class="fw-semibold">{{ $item->hubungan }}</span>
                                                                </div>
                                                                <div class="d-flex flex-column mb-3">
                                                                    <span class="fw-bold">Status Pernikahan</span>
                                                                    <span class="fw-semibold">{{ $item->hubungan }}</span>
                                                                </div>
                                                                <div class="d-flex flex-column mb-3">
                                                                    <span class="fw-bold">Jenis Kelamin</span>
                                                                    <span class="fw-semibold">{{ $item->hubungan }}</span>
                                                                </div>
                                                                <div class="d-flex flex-column mb-3">
                                                                    <span class="fw-bold">Tanggal Lahir</span>
                                                                    <span class="fw-semibold">@dateId($item->tgl_lahir)</span>
                                                                </div>
                                                                <div class="d-flex flex-column mb-3">
                                                                    <span class="fw-bold">Nomor Telepon</span>
                                                                    <span class="fw-semibold">{{ $item->no_telp }}</span>
                                                                </div>
                                                                <div class="d-flex flex-column">
                                                                    <span class="fw-bold">Upload Dokumen</span>
                                                                    <span class="fw-semibold">
                                                                        @if (!empty($item->lampiran))
                                                                        <a href="{{ asset($item->lampiran) }}">
                                                                            @php
                                                                                $_lampiran = explode("/", $item->lampiran);
                                                                                $_fname = explode("_", end($_lampiran));
                                                                            @endphp
                                                                            {{ implode("_", array_slice($_fname, 3)) }}
                                                                        </a>
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="row accordion {{ $data['license']->count() == 0 ? "mx-0 mb-5" : "" }}" id="accordion-license">
                                    @if ($data['license']->count() === 0)
                                        <button type="button" class="btn btn-outline text-dark text-hover-dark" disabled>
                                            Tidak ada data lisensi
                                        </button>
                                    @else
                                    @foreach ($data['license'] as $i => $item)
                                        <div class="col-12 accordion mb-5">
                                            <div class="d-flex flex-column border rounded">
                                                <div class="d-flex justify-content-between accordion accordion-icon-collapse p-5 align-items-center">
                                                    <span class="fw-bold">Lisensi {{ $i+1 }}</span>
                                                    <div class="d-flex justify-content-between border-0">
                                                        <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="license" data-id="{{ $item->id }}" data-act="edit">
                                                            <i class="fa fa-edit text-dark"></i>
                                                        </span>
                                                        <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="license" data-id="{{ $item->id }}" data-act="delete">
                                                            <i class="fa fa-trash text-danger"></i>
                                                        </span>
                                                        <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                            <div class="accordion-heder collapsed" data-bs-toggle="collapse" data-bs-target="#collapseLicense{{ $item->id }}" aria-controls="collapseLicense{{ $item->id }}">
                                                                <span class="accordion-icon">
                                                                    <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                    <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapseLicense{{ $item->id }}" class="collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-license">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="d-flex flex-column mb-3">
                                                                <span class="fw-bold">Nama</span>
                                                                <span class="fw-semibold">{{ $item->name }}</span>
                                                            </div>
                                                            <div class="d-flex flex-column mb-3">
                                                                <span class="fw-bold">Organisasi Penerbit</span>
                                                                <span class="fw-semibold">{{ $item->organisasi }}</span>
                                                            </div>
                                                            <div class="d-flex flex-column mb-3">
                                                                <span class="fw-bold">Tanggal Penerbitan</span>
                                                                <span class="fw-semibold">@dateId($item->tgl_penerbitan)</span>
                                                            </div>
                                                            <div class="d-flex flex-column mb-3">
                                                                <span class="fw-bold">Jenis Kelamin</span>
                                                                <span class="fw-semibold">
                                                                    @if (!empty($item->tgl_kadaluarsa))
                                                                    @dateId($item->tgl_kadaluarsa)
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="d-flex flex-column mb-3">
                                                                <span class="fw-bold">Nomor Lisensi</span>
                                                                <span class="fw-semibold">{{ $item->no_lisensi }}</span>
                                                            </div>
                                                            <div class="d-flex flex-column mb-3">
                                                                <span class="fw-bold">URL</span>
                                                                <span class="fw-semibold">{{ $item->url ?? "-" }}</span>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bold">Upload Dokumen</span>
                                                                <span class="fw-semibold">
                                                                    @if (!empty($item->lampiran))
                                                                    <a href="{{ asset($item->lampiran) }}">
                                                                        @php
                                                                            $_lampiran = explode("/", $item->lampiran);
                                                                            $_fname = explode("_", end($_lampiran));
                                                                        @endphp
                                                                        {{ implode("_", array_slice($_fname, 3)) }}
                                                                    </a>
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="row accordion {{ empty($data['id_card']) ? "mx-0 mb-5" : "" }}" id="accordion-ktp">
                                    @if (empty($data['id_card']))
                                    <button type="button" class="btn btn-outline text-dark text-hover-dark" disabled>
                                        Tidak ada data identitas
                                    </button>
                                    @else
                                    @php
                                        $item = $data['id_card']
                                    @endphp
                                    <div class="col-12 accordion mb-5">
                                        <div class="d-flex flex-column border rounded">
                                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-5 align-items-center">
                                                <span class="fw-bold">KTP</span>
                                                <div class="d-flex justify-content-between border-0">
                                                    <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="ktp" data-id="{{ $item->id }}" data-act="edit">
                                                        <i class="fa fa-edit text-dark"></i>
                                                    </span>
                                                    <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="ktp" data-id="{{ $item->id }}" data-act="delete">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </span>
                                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                        <div class="accordion-heder collapsed" data-bs-toggle="collapse" data-bs-target="#collapseKTP{{ $item->id }}" aria-controls="collapseKTP{{ $item->id }}">
                                                            <span class="accordion-icon">
                                                                <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapseKTP{{ $item->id }}" class="collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-ktp">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="d-flex flex-column mb-3">
                                                            <span class="fw-bold">Nomor KTP</span>
                                                            <span class="fw-semibold">{{ $item->no_kartu }}</span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">Upload Dokumen</span>
                                                            <span class="fw-semibold">
                                                                @if (!empty($item->lampiran))
                                                                <a href="{{ asset($item->lampiran) }}">
                                                                    @php
                                                                        $_lampiran = explode("/", $item->lampiran);
                                                                        $_fname = explode("_", end($_lampiran));
                                                                    @endphp
                                                                    {{ implode("_", array_slice($_fname, 3)) }}
                                                                </a>
                                                                @else
                                                                -
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_script')
    <script>
        function show_toast(msg){
            toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toastr-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
            };

            toastr.success(msg);
        }

        $(document).ready(function(){
            $("[data-toggle=bookmark]").click(function(){
                var $i = $(this).find("i")
                var id = $(this).data("id")
                console.log("hi")
                $.ajax({
                    url : "{{ route("search_talent.bookmark") }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id
                    }
                }).then(function(resp){
                    if(resp.bookmark){
                        $i.removeClass("far")
                        $i.addClass("fa")
                        $i.next().text("Bookmarked")
                        show_toast("Ditambahkan ke bookmark")
                    } else {
                        $i.removeClass("fa")
                        $i.addClass("far")
                        $i.next().text("Bookmark")
                        show_toast("Dihapus dari bookmark")
                    }
                })
            })
        })
    </script>
@endsection
