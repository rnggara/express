@extends('layouts.templatePrint')

@section('name')

@endsection

@section('content')

<div class="container">
    <div class="d-flex flex-column">
        <div class="card border mb-8">
            <div class="card-body">
                <div class="d-flex align-items-start flex-column flex-md-row justify-content-md-between">
                    <div class="d-flex align-items-start">
                        <div class="symbol symbol-30px symbol-circle symbol-md-70px me-5">
                            <img src="{{ asset($applicant->user_img ?? 'theme/assets/media/avatars/blank.png') }}" alt="image">
                        </div>
                        <div class="d-flex flex-fill flex-column">
                            <span class="fs-3 mb-3 fw-bold">{{ $applicant->name }}</span>
                            <div class="d-flex gap-10">
                                <div class="d-flex flex-column gap-1">
                                    <span>{{ $exp->position ?? "-" }} {{ ($exp->yoe ?? null) == null ? "" : "(".($exp->yoe ?? 0)." Tahun)" }}</span>
                                    <span>{{ $exp->company ?? "-" }}</span>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <span>Gaji yang diharapkan </span>
                                    <span>Rp. {{ number_format($profile->salary_expect ?? ($exp->salary ?? 0), 2, ",", ".") }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" onclick="window.print()" class="btn btn-sm me-3 btn-outline btn-outline-danger print-hide">
                            <i class="fi fi-rr-file-pdf"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border mb-8 card-px-0 px-5">
            <div class="card-body">
                <div class="accordion accordion-icon-toggle" id="kt_accordion_profile">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                            <h3 class="fs-5 fw-semibold mb-0">
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
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_profile_item_1" class="fs-6 collapse row show ps-10" data-bs-parent="#kt_accordion_profile">
                            <div class="col-6">
                                <div class="d-flex flex-column mb-5">
                                    <span>Nama Lengkap</span>
                                    <span>{{ $profile->name ?? $applicant->name }}</span>
                                </div>
                                <div class="d-flex flex-column mb-5">
                                    <span>Jenis Kelamin</span>
                                    <span>{{ $profile->gender ?? "-" }}</span>
                                </div>
                                <div class="d-flex flex-column mb-5">
                                    <span>Tanggal Lahir</span>
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
                                <div class="d-flex flex-column mb-5">
                                    <span>Nomor Telepon</span>
                                    <span>{{ $profile->phone ?? $applicant->phone }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column mb-5">
                                    <span>Email</span>
                                    <span>{{ $profile->email ?? $applicant->email }}</span>
                                </div>
                                <div class="d-flex flex-column mb-5">
                                    <span>Status Pernikahan</span>
                                    <span>{{ $profile->marital_status ?? "-" }}</span>
                                </div>
                                <div class="d-flex flex-column mb-5">
                                    <span>Agama</span>
                                    <span>{{ $profile->religion ?? "-" }}</span>
                                </div>
                                <div class="d-flex flex-column mb-5">
                                    <span>Kota & Provinsi</span>
                                    <span>
                                        @php
                                            $cityName = $data['city']->where("id", $profile->city_id ?? null)->first()->name ?? null;
                                            $provName = $data['prov']->where("id", $profile->prov_id ?? null)->first()->name ?? null;
                                        @endphp
                                        {{ $cityName }}
                                        {{ empty($provName) ? "" : ", $provName" }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-column">
                                    <span>Alamat</span>
                                    <span>{{ $profile->address ?? "-" }}</span>
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
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                            <h3 class="fs-5 fw-semibold mb-0">
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
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_experience_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_experience">
                            @if($experience->count() == 0)
                            No data
                            @else
                                @foreach ($experience as $item)
                                    <div class="row mb-5">
                                        <div class="col-6 d-flex flex-column">
                                            <span class="fw-bold">{{ $item->company }}</span>
                                            <span class="fw-bold">{{ $item->position }}</span>
                                            @if (!empty($item->start_date) && $item->start_date != "-")
                                            <span>@monthId($item->start_date) {{ date("Y", strtotime($item->start_date)) }} - @if ($item->still == 1) Sekarang @else @monthId($item->end_date) {{ date("Y", strtotime($item->end_date)) }}  @endif</span>
                                            @endif
                                            <span class="mb-3">{{ $item->location }}</span>
                                        </div>
                                        <div class="col-6 d-flex flex-column">
                                            <span>Deskripsi Pekerjaan:</span>
                                            <span class="mb-3">{!! $item->descriptions !!}</span>
                                            <span>Prestasi Kerja:</span>
                                            <span class="mb-3">{!! $item->achievements !!}</span>
                                        </div>
                                        <div class="col-6 d-flex flex-column">
                                            <span>Jabatan:</span>
                                            <span class="mb-3">{{ $item->ref_pos ?? "-" }}</span>
                                        </div>
                                        <div class="col-6 d-flex flex-column">
                                            <span>Gaji:</span>
                                            <span class="mb-3">{!! number_format($item->salary, 2, ",", ".") !!}</span>
                                        </div>
                                        <div class="col-6 d-flex flex-column">
                                            <span>Referensi:</span>
                                            <span class="mb-3">{!! $item->reference." ($item->phone)" !!}</span>
                                        </div>
                                        <div class="col-6 d-flex flex-column">
                                            <span>Alasan Resign:</span>
                                            <span class="mb-3">{{ $item->resign_reason ?? "-" }}</span>
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
                <div class="accordion accordion-icon-toggle" id="kt_accordion_education">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                            <h3 class="fs-5 fw-semibold mb-0">
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
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_education_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_education">
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-3">Pendidikan Formal</span>
                                    @if($edu_formal->count() == 0)
                                    No data
                                    @else
                                        @foreach ($edu_formal as $item)
                                            <div class="d-flex flex-column mb-5">
                                                <div class="row">
                                                    <div class="col-6 d-flex flex-column">
                                                        <span class="fw-bold">{{ $item->school_name }}</span>
                                                        <span class="fw-bold">{{ $item->degree." ".$item->field_of_study }}</span>
                                                        <span class="mb-3">@monthId($item->start_date) {{ date("Y", strtotime($item->start_date)) }} - @if ($item->still == 1) Sekarang @else @monthId($item->end_date) {{ date("Y", strtotime($item->end_date)) }}  @endif</span>
                                                    </div>
                                                    <div class="col-6 d-flex flex-column">
                                                        <span>Deskripsi:</span>
                                                        <span class="mb-3">{!! $item->descriptions !!}</span>
                                                        <span>Dokumen yang diupload:</span>
                                                        <span class="mb-3">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-3">Pendidikan Informal</span>
                                    @if($edu_informal->count() == 0)
                                    No data
                                    @else
                                        @foreach ($edu_informal as $item)
                                            <div class="row mb-5">
                                                <div class="col-6 d-flex flex-column">
                                                    <span class="fw-bold">{{ $item->vendor }}</span>
                                                    <span class="fw-bold">{{ $item->course_name }}</span>
                                                    <span class="mb-3">@monthId($item->start_date) {{ date("Y", strtotime($item->start_date)) }} - @if ($item->still == 1) Sekarang @else @monthId($item->end_date) {{ date("Y", strtotime($item->end_date)) }}  @endif</span>
                                                </div>
                                                <div class="col-6 d-flex flex-column">
                                                    <span>Deskripsi:</span>
                                                    <span class="mb-3">{!! $item->descriptions !!}</span>
                                                    <span>Dokumen yang diupload:</span>
                                                    <span class="mb-3">-</span>
                                                </div>
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
                <div class="row">
                    <div class="col-4">
                        <div class="accordion accordion-icon-toggle" id="kt_accordion_language">
                            <!--begin::Item-->
                            <div class="mb-5">
                                <!--begin::Header-->
                                <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                                    <h3 class="fs-5 fw-semibold mb-0">
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
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div id="kt_accordion_language_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_language">
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
                    </div>
                    <div class="col-4">
                        <div class="accordion accordion-icon-toggle" id="kt_accordion_portofolio">
                            <!--begin::Item-->
                            <div class="mb-5">
                                <!--begin::Header-->
                                <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                                    <h3 class="fs-5 fw-semibold mb-0">
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
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div id="kt_accordion_portofolio_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_portofolio">
                                    <div class="row">
                                        <div class="col-12">
                                            <table>
                                                <tr>
                                                    <td>Website</td>
                                                    <td>: {{$medsos->website ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Behance</td>
                                                    <td>: {{$medsos->behance ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Dribble</td>
                                                    <td>: {{$medsos->dribble ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Github</td>
                                                    <td>: {{$medsos->github ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Aplikasi Mobile</td>
                                                    <td>: {{$medsos->mobile ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Link lain</td>
                                                    <td>: {{$medsos->others ?? "N/A"}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Item-->
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="accordion accordion-icon-toggle" id="kt_accordion_medsos">
                            <!--begin::Item-->
                            <div class="mb-5">
                                <!--begin::Header-->
                                <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                                    <h3 class="fs-5 fw-semibold mb-0">
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
                                </div>
                                <!--end::Header-->

                                <!--begin::Body-->
                                <div id="kt_accordion_medsos_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_medsos">
                                    <div class="row">
                                        <div class="col-12">
                                            <table>
                                                <tr>
                                                    <td>Linkedin</td>
                                                    <td>: {{$medsos->linkedin ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Facebook</td>
                                                    <td>: {{$medsos->facebook ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Instagram</td>
                                                    <td>: {{$medsos->instagram ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Twitter</td>
                                                    <td>: {{$medsos->twitter ?? "N/A"}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tiktok</td>
                                                    <td>: {{$medsos->tiktok ?? "N/A"}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Item-->
                        </div>
                    </div>
                </div>
                <div class="separator separator-solid my-3"></div>
                <div class="accordion accordion-icon-toggle" id="kt_accordion_skill">
                    <!--begin::Item-->
                    <div class="mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                            <h3 class="fs-5 fw-semibold mb-0">
                                <i class="ki-duotone ki-questionnaire-tablet fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                Kemampuan Khusus
                            </h3>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_skill_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_skill">
                            @if ($skills->count() == 0)
                                No Data
                            @else
                                @foreach ($skills as $item)
                                    @if (!empty($item->skill_name))
                                    <div class="d-flex mb-8">
                                        <div class="d-flex flex-column me-8">
                                            <span class="mb-3">Kemampuan Khusus</span>
                                            <span class="fw-bold">{{ $item->skill_name }}</span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="mb-3">Kemahiran</span>
                                            <span class="fw-bold">{{ $data['proficiency'][$item->proficiency] ?? "-" }}</span>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
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
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                            <h3 class="fs-5 fw-semibold mb-0">
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
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_test_item_1" class="fs-6 collapse show" data-bs-parent="#kt_accordion_test">
                            @if (count($test_list) > 0)
                                <div class="row row-gap-5">
                                    @foreach ($test as $item)
                                        @if (isset($test_list[$item->id]))
                                            @php
                                                $last = $test_list[$item->id][0];
                                                $date_test = $last->result_end ?? null;
                                                $test_uri = "";
                                                $rpoint = $last->result_point;
                                                $last_wpt = [];
                                                if($item->category_id == 3){
                                                    $test_uri = route('test.papikostik.psikogram', $last->id)."?uid=$last->user_id";
                                                } elseif($item->category_id == 5){
                                                    $test_uri = route('test.disc.psikogram', $last->id);
                                                } elseif($item->category_id == 2){
                                                    $test_uri = route("test.mbti.psikogram", $last->id);
                                                } elseif($item->category_id == 4){
                                                    $test_uri = route("test.wpt.psikogram", $last->id);
                                                    $last_wpt = $wpt->where("test_result_id", $last->id)->first();
                                                    $true = $last_wpt->true ?? 0;
                                                    $wrong = $last_wpt->wrong ?? 0;
                                                    $rpoint = ($last_wpt->score ?? 0) + ($last_wpt->age_point ?? 0);
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
                                            <div class="d-flex flex-column col-6" style="font-size: 13px!important">
                                                <span class="fw-bold mb-1" style="font-size: 14px!important">{{ $item->label }}</span>
                                                @if (!in_array($item->category_id, [2, 3, 5, 7]))
                                                    @if($item->category_id == 4)
                                                        @php
                                                        $interpretasi = "-";
                                                        foreach($wpt_interpretasi as $imin => $ilabel){
                                                            if($rpoint <= $imin){
                                                                $interpretasi = $ilabel;
                                                                break;
                                                            }
                                                        }
                                                        @endphp
                                                        <span>IQ : {{ $wpt_iq[$rpoint] ?? "-" }}</span>
                                                        <span>Waktu Test : {{ $elTime }}</span>
                                                        <span>Interpretasi : {{ $interpretasi }}</span>
                                                    @else
                                                    @php
                                                        $correct_answer = round(($rpoint / 100) * $item->question_per_quiz);
                                                    @endphp
                                                    <span>Score : {{ $rpoint }} / 100 ( {{$correct_answer}} jawaban benar dari {{$item->question_per_quiz}} soal)</span>
                                                    <span>Waktu Test : {{ $elTime }}</span>
                                                    @endif
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
                                                @endif
                                                @if ($test_uri != "")
                                                    {{-- <div class="d-flex">
                                                        <span class="me-5">Hasil Tes :</span>
                                                        <a href="{{ $test_uri }}" class="text-primary">Lihat Hasil disini</a>
                                                    </div> --}}
                                                @endif
                                                <div class="d-flex">
                                                    <span class="me-2">Tanggal Test :</span>
                                                    @if (empty($date_test))
                                                        -
                                                    @else
                                                        @dateId(date("Y-m-d", strtotime($date_test))) {{ date("H:i", strtotime($date_test)) }}
                                                    @endif
                                                </div>
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
                        <div class="accordion-header py-3 d-flex collapsed justify-content-between">
                            <h3 class="fs-5 fw-semibold mb-0">
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
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div id="kt_accordion_additional_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_additional">
                            <div class="d-flex flex-column">
                                <div class="row accordion {{ $data['family']->count() == 0 ? "mx-0 mb-5" : "" }}" id="accordion-family">
                                    @if ($data['family']->count() === 0)
                                        {{-- <button type="button" class="btn btn-outline text-dark text-hover-dark" disabled>
                                            Tidak ada informasi keluarga
                                        </button> --}}
                                    @else
                                    @foreach ($data['family'] as $i => $item)
                                            <div class="col-12 accordion mb-5">
                                                <div class="d-flex flex-column border rounded">
                                                    <div class="d-flex justify-content-between accordion accordion-icon-collapse p-5 align-items-center">
                                                        <span class="fw-bold">Informasi Keluarga {{ $i+1 }}</span>
                                                        <div class="d-flex justify-content-between border-0">
                                                            <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                                                <div class="accordion-heder collapsed">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="collapse{{ $item->id }}" class="collapse show border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-family">
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
                                        {{-- <button type="button" class="btn btn-outline text-dark text-hover-dark" disabled>
                                            Tidak ada data lisensi
                                        </button> --}}
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
                                                            <div class="accordion-heder collapsed">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapseLicense{{ $item->id }}" class="collapse show border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-license">
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
                                    {{-- <button type="button" class="btn btn-outline text-dark text-hover-dark" disabled>
                                        Tidak ada data identitas
                                    </button> --}}
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
                                                        <div class="accordion-heder collapsed">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapseKTP{{ $item->id }}" class="collapse show border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-ktp">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="d-flex flex-column mb-3">
                                                            <span class="fw-bold">Nomor KTP</span>
                                                            <span class="fw-semibold">{{ $item->no_kartu }}</span>
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
        @if(!empty($wpt_data))
            <div class="card card-custom bg-transparent border pagebreak">
                <div class="card-header border-0">
                    <h3 class="card-title">Hasil Test {{ $wpt_test->label }}</h3>
                </div>
                <div class="card-body bg-white rounded">
                    <div class="d-flex flex-column align-items-center">
                        {{-- <span class="fs-2 fw-bold mb-8">Hasil Pemeriksaan Psikologis</span> --}}
                        {{-- <div class="d-flex align-items-center justify-content-center">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Nama</th>
                                    <td>:</td>
                                    <td>{{ $profile->name ?? $applicant->name }}</td>
                                </tr>
                                <tr>
                                    <th>Umur</th>
                                    <td>:</td>
                                    <td>{{ $umur }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>:</td>
                                    <td>{{ $profile->gender ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Test</th>
                                    <td>:</td>
                                    <td>@dateId(date("Y-m-d", strtotime($wpt_result->result_end))) {{ date("H:i", strtotime(strtotime($wpt_result->result_end))) }}</td>
                                </tr>
                            </table>
                        </div> --}}
                        <div class="d-flex">
                            @for($i = 0; $i < 4; $i++)
                                @php
                                    $total_skor = 0;
                                    foreach ($wpt_test->questions->skip($i*15)->take(15) as $item){
                                        $_point = $wpt_data['jawaban'][$item->id] ?? [];
                                        $_skor = $wpt_data['skor'][$item->id] ?? 0;
                                        $order = "";
                                        if(!is_array($_point)){
                                            if(!empty($_point)){
                                                $order = $_point ?? "-";
                                            }
                                        } else {
                                            if(!empty($_point)){
                                                foreach($_point as $key => $_pp){
                                                    $order .= $wpt_data['point'][$key].", ";
                                                }
                                            }
                                        }
                                        $total_skor += $_skor;
                                    }
                                @endphp
                            @endfor
                        </div>
                        <div class="d-flex flex-column">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Jumlah yang salah/ditinggalkan</td>
                                    <td>:</td>
                                    <td>{{ $wpt_data['result']->wrong }}</td>
                                </tr>
                                <tr>
                                    <td>Jumlah yang benar</td>
                                    <td>:</td>
                                    <td>{{ $wpt_data['result']->true }}</td>
                                </tr>
                                <tr>
                                    <td>Poin penyesuaian</td>
                                    <td>:</td>
                                    <td>{{ $wpt_data['result']->age_point }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Skor</td>
                                    <td class="fw-bold">:</td>
                                    <td class="fw-bold">{{ $wpt_data['result']->score }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-right" align="right">IQ</td>
                                    <td class="fw-bold">:</td>
                                    <td class="fw-bold">{{ $wpt_data['iq']->iq }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-right" align="right">Interpretasi</td>
                                    <td class="fw-bold">:</td>
                                    <td class="fw-bold"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="fw-bold">{{ $wpt_data['interpretasi']->label }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!empty($disc_data))
            <div class="card card-custom bg-transparent border mt-5">
                <div class="card-header border-0">
                    <h3 class="card-title">Hasil Test {{ $disc_test->label }}</h3>
                    <div class="card-toolbar">

                    </div>
                </div>
                <div class="card-body bg-white rounded">
                    <div class="d-flex flex-column align-items-center">
                        {{-- <span class="fs-2 fw-semibold">D I S C</span>
                        <span class="fs-2 fw-bold mb-8">Personality System Graph Page</span> --}}
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Nama</th>
                                    <td>:</td>
                                    <td>{{ $profile->name ?? $applicant->name }}</td>
                                </tr>
                                <tr>
                                    <th>Umur</th>
                                    <td>:</td>
                                    <td>{{ $umur }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>:</td>
                                    <td>{{ $profile->gender ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Test</th>
                                    <td>:</td>
                                    <td>@dateId(date("Y-m-d", strtotime($disc_result->result_end))) {{ date("H:i", strtotime(strtotime($disc_result->result_end))) }}</td>
                                </tr>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Line</th>
                                        @foreach ($disc_data['disc'] as $item)
                                            <th>{{ $item }}</th>
                                        @endforeach
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($disc_data['psikogram'] as $i => $item)
                                        @php
                                            $_total = 0;
                                            foreach ($disc_data['disc'] as $_disc) {
                                                if(isset($item[$_disc])){
                                                    $_total += $item[$_disc];
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>Line {{ $i }}</td>
                                            @foreach ($disc_data['disc'] as $_disc)
                                                <td align="center" @if(!isset($item[$_disc])) class="bg-secondary" @endif>{{ $item[$_disc] ?? "" }}</td>
                                            @endforeach
                                            @if ($i < 3)
                                                <td align="center">{{ $_total }}</td>
                                            @else
                                                <td align="center" class="bg-secondary"></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-around row row-cols-3 mb-10 px-2 border rounded">
                            <div class="col card card-custom mb-5 mb-md-0">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <span class="fw-bold">GRAPH 1 MOST</span>
                                        <span class="fw-semibold">Mask Public Self</span>
                                    </div>
                                    <div class="mt-10">
                                        <canvas id="chart_line_1" class="h-200px init_chart" data-i="1"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col card card-custom mb-5 mb-md-0">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <span class="fw-bold">GRAPH 2 LEAST</span>
                                        <span class="fw-semibold">Core Private Self</span>
                                    </div>
                                    <div class="mt-10">
                                        <canvas id="chart_line_2" class="h-200px init_chart" data-i="2"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col card card-custom mb-5 mb-md-0">
                                <div class="card-body">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <span class="fw-bold">GRAPH 3 CHANGE</span>
                                        <span class="fw-semibold">Mirror Perceived Self</span>
                                    </div>
                                    <div class="mt-10">
                                        <canvas id="chart_line_3" class="h-200px init_chart" data-i="3"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-custom w-100 mb-5">
                            <div class="card-header border-0">
                                <h3 class="card-title">Deskripsi Kepribadian</h3>
                            </div>
                            <div class="card-body border rounded">
                                {!! $disc_data['desc_kepribadian']->descriptions ?? "" !!}
                            </div>
                        </div>
                        <div class="row row-cols-3">
                            <div class="card card-custom col card-stretch border mb-md-0">
                                <div class="card-body px-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="fw-bold text-center">Kepribadian Saat di Publik</span>
                                        <span class="fw-semibold">Mask Public Self</span>
                                    </div>
                                    <div class="mt-5">
                                        @php
                                            $_desc1 = $disc_data['desc_line'][1];
                                            $_label1 = explode(",", $_desc1->descriptions ?? "");
                                        @endphp
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($_label1 as $i => $lbl)
                                                @if (trim($lbl) != "")
                                                    <span class="{{ $i == 0 ? "w-100" : "" }}">{{ trim($lbl) }},</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-custom col card-stretch border mb-md-0">
                                <div class="card-body px-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="fw-bold text-center">Kepribadian Saat Mendapat Tekanan</span>
                                        <span class="fw-semibold">Core Private Self</span>
                                    </div>
                                    <div class="mt-5">
                                        @php
                                            $_desc2 = $disc_data['desc_line'][2];
                                            $_label2 = explode(",", $_desc2->descriptions ?? "");
                                        @endphp
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($_label2 as $lbl)
                                                @if (trim($lbl) != "")
                                                    <span class="{{ $i == 0 ? "w-100" : "" }}">{{ trim($lbl) }},</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-custom col card-stretch border mb-md-0">
                                <div class="card-body px-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="fw-bold text-center">Kepribadian Asli/Sesungguhnya</span>
                                        <span class="fw-semibold">Mirror Perceived Self</span>
                                    </div>
                                    <div class="mt-5">
                                        @php
                                            $_desc3 = $disc_data['desc_line'][3];
                                            $_label3 = explode(",", $_desc3->descriptions ?? "");
                                        @endphp
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($_label3 as $lbl)
                                                @if (trim($lbl) != "")
                                                    <span class="{{ $i == 0 ? "w-100" : "" }}">{{ trim($lbl) }},</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-custom w-100">
                            <div class="card-header border-0">
                                <h3 class="card-title">Job Match</h3>
                            </div>
                            <div class="card-body border rounded">
                                {!! $disc_data['desc_job']->descriptions ?? "" !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!empty($mbti_data))
            <div class="card card-custom bg-transparent border pagebreak">
                <div class="card-header border-0">
                    <h3 class="card-title">Hasil Test {{ $mbti_test->label }}</h3>
                </div>
                <div class="card-body bg-white rounded">
                    <div class="d-flex flex-column align-items-center">
                        {{-- <span class="fs-2 fw-bold mb-8">Hasil Pemeriksaan Psikologis</span> --}}
                        {{-- <div class="d-flex align-items-center justify-content-center">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Nama</th>
                                    <td>:</td>
                                    <td>{{ $profile->name ?? $applicant->name }}</td>
                                </tr>
                                <tr>
                                    <th>Umur</th>
                                    <td>:</td>
                                    <td>{{ $umur }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>:</td>
                                    <td>{{ $profile->gender ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Test</th>
                                    <td>:</td>
                                    <td>@dateId(date("Y-m-d", strtotime($mbti_result->result_end))) {{ date("H:i", strtotime(strtotime($mbti_result->result_end))) }}</td>
                                </tr>
                            </table>
                        </div> --}}
                        <div class="d-flex flex-column">
                            @php
                                $mbti_data['psikogram_res'] = json_decode($mbti_data['result']->psikogram_result, true);
                                $tagIdentifier = [];
                            @endphp
                            <table class="table table-bordered">
                                @php
                                    foreach ($mbti_data['psikogram_res'] as $item) {
                                        $lTag = $mbti_data['tag'][$item['left']['identifier']];
                                        $rTag = $mbti_data['tag'][$item['right']['identifier']];
                                        $lPctg = $item['left']['%'];
                                        $rPctg = $item['right']['%'];
                                        if($lPctg > $rPctg){
                                            $tagIdentifier[] = $lTag;
                                        } else {
                                            $tagIdentifier[] = $rTag;
                                        }
                                    }
                                @endphp
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th colspan="4" class="text-center">Tipe Kepribadian : {{ implode(" ", $tagIdentifier) }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mbti_data['psikogram_res'] as $i => $item)
                                        @php
                                            $lTag = $mbti_data['tag'][$item['left']['identifier']];
                                            $rTag = $mbti_data['tag'][$item['right']['identifier']];
                                            $lPctg = $item['left']['%'];
                                            $rPctg = $item['right']['%'];
                                            // if($lPctg > $rPctg){
                                            //     $tagIdentifier[] = $lTag;
                                            // } else {
                                            //     $tagIdentifier[] = $rTag;
                                            // }
                                        @endphp
                                        <tr>
                                            <td class="bg-light-primary">{{ $i+1 }}</td>
                                            <td class="bg-light-info">{{ ucwords($item['left']['label']) }} ({{ $lTag }})</td>
                                            <td class="bg-warning text-center">{{ $item['left']['%'] }}%</td>
                                            <td class="bg-warning text-center">{{ $item['right']['%'] }}%</td>
                                            <td class="bg-light-info">({{ $rTag }}) {{ ucwords($item['right']['label']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <div class="border p-5 d-flex flex-column" style="background-color: #bbe7f1">
                                <span>Tipe Kepribadian :</span>
                                <div class="d-flex justify-content-center">
                                    <span class="fw-bold fs-1">{{ implode(" ", $tagIdentifier) }}</span>
                                </div>
                            </div> --}}
                            <table class="table table-borderless">
                                @foreach ($mbti_data['_desc'] as $idesc => $label)
                                    <thead>
                                        <tr>
                                            <th><span class="fs-4 fw-bold">{{ $label }}</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="border">
                                        @foreach ($mbti_data['analysis'][$idesc] as $i => $item)
                                            <tr class="border">
                                                <td class="p-2">
                                                    <div class="d-flex">
                                                        <span class="me-3">{{ $i+1 }}.</span>
                                                        <span>{{ $item->descriptions }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (!empty($papikostik_data))
            <div class="card card-custom bg-transparent border pagebreak">
                <div class="card-header border-0">
                    <h3 class="card-title">Hasil Test Papi Kostik</h3>
                </div>
                <div class="card-body bg-white rounded">
                    <div class="d-flex flex-column align-items-center">
                        {{-- <span class="fs-2 fw-semibold">PSIKOGRAM</span>
                        <span class="fs-2 fw-bold">Sikap dan Potensi Kerja</span> --}}
                        {{-- <span class=" mb-8">@dateId(date("Y-m-d", strtotime($papikostik_result->result_end))) {{ date("H:i", strtotime(strtotime($papikostik_result->result_end))) }}</span> --}}
                        <div class="scroll w-100">
                            <table class="table table-bordered" id="table-psikogram">
                                <thead>
                                    <tr class="bg-light-primary text-white">
                                        <th>Deskripsi</th>
                                        @foreach ($papikostik_data['cat'] as $item)
                                            <th>{{ $item }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px!important">
                                    @foreach ($papikostik_data['param'] as $item)
                                        @php
                                            $result = $papikostik_data['psikogram'][$item->id] ?? [];
                                        @endphp
                                        @if (!empty($result))
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span>{{ $item->type }} : {{ $item->nama }}</span>
                                                    <span>{{ $result->descriptions }}</span>
                                                </div>
                                            </td>
                                            @foreach ($papikostik_data['cat'] as $_i => $c)
                                                @php
                                                    $show = false;
                                                    if($_i == $result->category){
                                                        $show = true;
                                                    }
                                                @endphp
                                                <td class="min-w-30px text-center">
                                                    @if ($show)
                                                        <i class="fa fa-check text-gray-800"></i>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <th colspan="{{ 3 + count($papikostik_data['cat']) }}">
                                            <div class="d-flex justify-content-around">
                                                <span>Keterangan :</span>
                                                <span><span class="fw-bold">R</span> = Rendah</span>
                                                <span><span class="fw-bold">K</span> = Kurang</span>
                                                <span><span class="fw-bold">C</span> = Cukup</span>
                                                <span><span class="fw-bold">B</span> = Baik</span>
                                                <span><span class="fw-bold">T</span> = Tinggi</span>
                                            </div>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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

        async function get_data_line(i){
            return $.ajax({
                url : "{{ route("account.to.pdf", $applicant->id) }}?a=chart&l=" + i,
                type : "get",
                dataType : "json"
            })
        }

        async function generateChart(me){
            return new Promise(async(resolve, reject) => {
                var _id = $(me).attr("id")
            var ctx = document.getElementById(_id);

            var _i = $(me).data("i")
            var _data = await get_data_line(_i)

            var max = 1;

            for (const key in _data) {
                if (Object.hasOwnProperty.call(_data, key)) {
                    const element = _data[key];
                    if(element > max){
                        max = element
                    }
                }
            }

            // Define colors
            var primaryColor = KTUtil.getCssVariableValue('--kt-primary');
            var dangerColor = KTUtil.getCssVariableValue('--kt-danger');

            // Define fonts
            var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');

            // Chart labels
            const labels = ['D', 'I', 'S', 'C'];

            // Chart data
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Dataset 1',
                        data: _data,
                        borderColor: primaryColor,
                        backgroundColor: 'transparent'
                    },
                ]
            };

            // Chart config
            const config = {
                type: 'line',
                data: data,
                options: {
                    layouts: {
                        autoPadding : true
                    },
                    plugins: {
                        title: {
                            display: false,
                        },
                        legend : {
                            display : false
                        },
                        tooltip : {
                            enabled : false
                        }
                    },
                    responsive: true,
                    aspectRatio : 2/3,
                    scales : {
                        y: {
                            min: -10,
                            max: 10,
                            ticks : {
                                stepSize: 1,
                                autoSkip: false,
                                padding : 0
                            }
                        },
                        x : {
                            lineWidth : 1
                        }
                    }
                },
            };

            // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
            var myChart = new Chart(ctx, config);
                resolve(); // Resolve the promise when done
            });
        }

        async function init_chart(){
            $(".init_chart").each(async function(){
                var _id = $(this).attr("id")
                var ctx = document.getElementById(_id);

                var _i = $(this).data("i")
                var _data = await get_data_line(_i)

                var max = Math.max(..._data)
                console.log(max)

                // Define colors
                var primaryColor = KTUtil.getCssVariableValue('--kt-primary');
                var dangerColor = KTUtil.getCssVariableValue('--kt-danger');

                // Define fonts
                var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');

                // Chart labels
                const labels = ['D', 'I', 'S', 'C'];

                // Chart data
                const data = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Dataset 1',
                            data: _data,
                            borderColor: primaryColor,
                            backgroundColor: 'transparent'
                        },
                    ]
                };

                // Chart config
                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        layouts: {
                            autoPadding : true
                        },
                        plugins: {
                            title: {
                                display: false,
                            },
                            legend : {
                                display : false
                            },
                            tooltip : {
                                enabled : false
                            }
                        },
                        responsive: true,
                        aspectRatio : 1,
                        scales : {
                            y: {
                                min: -8,
                                max: 8,
                                ticks : {
                                    stepSize: 1,
                                    autoSkip: false,
                                    padding : 0
                                }
                            },
                            x : {
                                lineWidth : 1
                            }
                        }
                    },
                };

                // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
                var myChart = new Chart(ctx, config);
            })
        }

        function loadPage(){
            if($(".init_chart").length == 0){
                window.print()
            } else {
                $(".init_chart").each(async function(index){
                    var _i = $(this).data("i")
                    await generateChart(this).then(async function(){
                        if(_i == $(".init_chart").length){
                            // window.print()
                            await new Promise(function(){
                                window.setTimeout(function(){
                                    console.log(_i, "print me")
                                    // window.print()
                                }, 1000)
                            });
                        }
                    })
                })
            }

            return new Promise(async(resolve, reject) => {
                resolve(); // Resolve the promise when done
            });
        }

        loadPage().then(function(res){
            // $("#kt_accordion_test_item_1").css("font-size", "13px!important")
            console.log("Function completed, printing now!");
        })
    </script>
@endsection
