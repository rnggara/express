@extends('layouts.template')

@section('content')
    <div class="d-flex flex-column">
        <span class="fw-bold fs-3 mb-5">
            Nilai Sebuah Perusahaan
        </span>
        <span class="mb-5">Bagikan pengalaman anda tetantang perusahaan anda sebelumnya/saat ini. nama nada akan dirahasiakan.semua ulasan perusahaan akan di pubulikasikan untuk mebantu orang lain merencanakan kariri berikutnya sama seprti ulasan mereka dapat  membantu anda merencanakan langkah anda.</span>
        <form action="{{route('app.cs.review_post')}}" method="post" class="d-flex flex-column">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <span class="fw-bold mb-5">Gambaran Umum Perusahaan</span>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol me-5">
                                <img src="{{ asset($company->icon) }}" alt="">
                            </div>
                            <div class="fv-row flex-fill">
                                <label for="company_name" class="col-form-label fw-semibold">Perusahaan yang akan di tinjau</label>
                                <input type="text" readonly class="form-control" value="{{ $company->company_name }}">
                            </div>
                        </div>
                        <span class="mb-3 fw-semibold">Peringkat Keselururhan dari penglaman kamu di sini</span>
                        <div class="rating mb-5">
                            <input class="rating-input" name="overall_rating" value="0" checked type="radio" id="overall_rating_0"/>
                            <!--begin::Star 1-->
                            <label class="rating-label me-3" for="overall_rating_1">
                                <i class="bi bi-star-fill fs-1"></i>
                            </label>
                            <input class="rating-input" name="overall_rating" value="1" type="radio" id="overall_rating_1"/>
                            <!--end::Star 1-->

                            <!--begin::Star 2-->
                            <label class="rating-label me-3" for="overall_rating_2">
                                <i class="bi bi-star-fill fs-1"></i>
                            </label>
                            <input class="rating-input" name="overall_rating" value="2" type="radio" id="overall_rating_2"/>
                            <!--end::Star 2-->

                            <!--begin::Star 3-->
                            <label class="rating-label me-3" for="overall_rating_3">
                                <i class="bi bi-star-fill fs-1"></i>
                            </label>
                            <input class="rating-input" name="overall_rating" value="3" type="radio" id="overall_rating_3"/>
                            <!--end::Star 3-->

                            <!--begin::Star 4-->
                            <label class="rating-label me-3" for="overall_rating_4">
                                <i class="bi bi-star-fill fs-1"></i>
                            </label>
                            <input class="rating-input" name="overall_rating" value="4" type="radio" id="overall_rating_4"/>
                            <!--end::Star 4-->

                            <!--begin::Star 5-->
                            <label class="rating-label me-3" for="overall_rating_5">
                                <i class="bi bi-star-fill fs-1"></i>
                            </label>
                            <input class="rating-input" name="overall_rating" value="5" type="radio" id="overall_rating_5"/>
                            <!--end::Star 5-->
                        </div>
                        <span class="mb-3 fw-semibold">Apakah kamu akan merekomendasikan bekerja di sini kepada seorang teman?</span>
                        <div class="d-flex align-items-center mb-5" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-primary me-5" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="is_recommended" value="1"/>
                                <!--end::Input-->
                                Ya
                            </label>
                            <!--end::Radio-->

                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-primary" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="is_recommended" value="0"/>
                                <!--end::Input-->
                                Tidak
                            </label>
                            <!--end::Radio-->
                        </div>
                        <span class="mb-3 fw-semibold">Bagaimana anda menilai gaji anda?</span>
                        <div class="d-flex align-items-center" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-primary me-5" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="salary_avg" value="2"/>
                                <!--end::Input-->
                                Tinggi
                            </label>
                            <!--end::Radio-->

                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-primary me-3" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="salary_avg" value="1"/>
                                <!--end::Input-->
                                Rata-rata
                            </label>
                            <!--end::Radio-->

                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-primary" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="salary_avg" value="0"/>
                                <!--end::Input-->
                                Rendah
                            </label>
                            <!--end::Radio-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <span class="fw-bold mb-5">Peran kamu di perusahaan</span>
                        <span class="mb-3 fw-semibold">Apakah kamu seorang karyawan saat ini atau pernah bekerja di sini?</span>
                        <div class="d-flex align-items-center mb-5" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-primary me-5" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="is_employee" value="1"/>
                                <!--end::Input-->
                                Karyawan
                            </label>
                            <!--end::Radio-->

                            <!--begin::Radio-->
                            <label class="btn btn-outline btn-color-muted btn-active-primary" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="is_employee" value="0"/>
                                <!--end::Input-->
                                Mantan Karyawan
                            </label>
                            <!--end::Radio-->
                        </div>
                        <div class="fv-row mb-5">
                            <label for="position" class="col-form-label fw-semibold">Job Title</label>
                            <input type="text" id="position" class="form-control" placeholder="Masukan Jabatan/Posisi Kamu" name="position">
                        </div>
                        <div class="fv-row mb-5">
                            <label for="job_type" class="col-form-label fw-semibold">Type Karyawan</label>
                            <select class="form-control" name="job_type" id="job_type" data-control="select2" data-placeholder="Pilih opsi">
                                <option value=""></option>
                                @foreach ($job_type as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <span class="fw-bold mb-5">Ayo bagikan pengalaman mu</span>
                        <div class="fv-row mb-5">
                            <label for="title" class="col-form-label required fw-semibold">Beri Judul Ulasan</label>
                            <input type="text" id="title" class="form-control" required placeholder="Masukan Judul" name="title">
                        </div>
                        <div class="fv-row mb-5">
                            <label for="pros" class="col-form-label fw-semibold">Kelebihan</label>
                            <textarea name="pros" id="pros" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                        <div class="fv-row mb-5">
                            <label for="cons" class="col-form-label fw-semibold">Kontra</label>
                            <textarea name="cons" id="cons" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                        <span class="mb-3 fw-semibold">Apakah kamu seorang karyawan saat ini atau pernah bekerja di sini?</span>
                        <div class="d-flex flex-column">
                            @foreach ($rev_list as $i => $item)
                                <div class="d-flex justify-content-between w-50 mb-5">
                                    <span>{{ $item }}</span>
                                    <div class="rating">
                                        <input class="rating-input" name="rating[{{$i}}]" value="0" checked type="radio" id="rating_{{ $i }}_0"/>
                                        <!--begin::Star 1-->
                                        <label class="rating-label me-3" for="rating_{{ $i }}_1">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="rating[{{$i}}]" value="1" type="radio" id="rating_{{ $i }}_1"/>
                                        <!--end::Star 1-->

                                        <!--begin::Star 2-->
                                        <label class="rating-label me-3" for="rating_{{ $i }}_2">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="rating[{{$i}}]" value="2" type="radio" id="rating_{{ $i }}_2"/>
                                        <!--end::Star 2-->

                                        <!--begin::Star 3-->
                                        <label class="rating-label me-3" for="rating_{{ $i }}_3">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="rating[{{$i}}]" value="3" type="radio" id="rating_{{ $i }}_3"/>
                                        <!--end::Star 3-->

                                        <!--begin::Star 4-->
                                        <label class="rating-label me-3" for="rating_{{ $i }}_4">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="rating[{{$i}}]" value="4" type="radio" id="rating_{{ $i }}_4"/>
                                        <!--end::Star 4-->

                                        <!--begin::Star 5-->
                                        <label class="rating-label me-3" for="rating_{{ $i }}_5">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </label>
                                        <input class="rating-input" name="rating[{{$i}}]" value="5" type="radio" id="rating_{{ $i }}_5"/>
                                        <!--end::Star 5-->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <span class="mb-3 fw-semibold">Dari skala 1 sampai 10, seberapa streskah Anda bekerja di sini?</span>
                        <div class="d-flex align-items-center">
                            <span class="far fa-laugh fs-3 me-5"></span>
                            <div class="d-flex align-items-center" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                                @for($i = 1; $i <= 10; $i++)
                                    <!--begin::Radio-->
                                    <label class="btn btn-outline btn-color-muted btn-active-primary me-5" data-kt-button="true">
                                        <!--begin::Input-->
                                        <input class="btn-check" type="radio" name="stress_level" value="{{ $i }}"/>
                                        <!--end::Input-->
                                        {{$i}}
                                    </label>
                                    <!--end::Radio-->
                                @endfor
                            </div>
                            <span class="far fa-sad-tear fs-3"></span>
                        </div>
                    </div>
                </div>
            </div>
            <span class="mb-3">Ulasan Perusahaan kerjaku portal adalah inisiatif baru yang diujicobakan oleh kerjak untuk membantu orang menemukan perusahaan yang tepat bagi mereka. Dengan mengirimkan ulasan Anda, Anda menerima syarat & ketentuan kami kami.</span>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="id" value="{{ $company->id }}">
                <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
            </div>
        </form>
    </div>
@endsection
