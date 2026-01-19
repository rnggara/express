@extends('layouts.template')

@section('css')
@endsection
@if(\Helper_function::isMobile() == 0)
    @section('content')
        <!--begin::Stepper-->
        <div class="d-flex stepper" id="kt_stepper_example_basic">
            <div class="card me-8 min-w-300px">
                <div class="card-body border rounded">
                    <!--begin::Menu item-->
                    <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6" id="#menu_loc"
                        data-kt-menu="true">
                        <div class="menu-item menu-link-indention active text-active-primary" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                            <!--begin::Menu link-->
                            <a href="#" class="menu-link py-3">
                                <span class="menu-icon">
                                    <i class="fa fa-briefcase"></i>
                                </span>
                                <span class="menu-title active text-active-primary">Detail Job Add</span>
                            </a>
                            <!--end::Menu link-->
                        </div>
                        <div class="menu-item menu-link-indention text-active-primary" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                            <!--begin::Menu link-->
                            <a href="#" class="menu-link py-3">
                                <span class="menu-icon">
                                    <i class="fa fa-question-circle"></i>
                                </span>
                                <span class="menu-title text-active-primary">Screening Question & Test</span>
                            </a>
                            <!--end::Menu link-->
                        </div>
                        @empty($job_ad)
                        <div class="menu-item menu-link-indention text-active-primary" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                            <!--begin::Menu link-->
                            <a href="#" class="menu-link py-3">
                                <span class="menu-icon">
                                    <i class="fa fa-star"></i>
                                </span>
                                <span class="menu-title text-active-primary">Branding Company</span>
                            </a>
                            <!--end::Menu link-->
                        </div>
                        {{-- <div class="menu-item menu-link-indention text-active-primary" data-kt-stepper-element="nav" data-kt-stepper-action="step">
                            <!--begin::Menu link-->
                            <a href="#" class="menu-link py-3">
                                <span class="menu-icon">
                                    <i class="fa fa-id-card"></i>
                                </span>
                                <span class="menu-title text-active-primary">Job Add</span>
                            </a>
                            <!--end::Menu link-->
                        </div> --}}
                        @endempty
                    </div>
                </div>
            </div>
            <div class="flex-fill">
                <form action="{{ route("job.add") }}" novalidate="novalidate" id="form-add" method="post">
                    <div class="flex-column current" data-form="1" data-kt-stepper-element="content">
                        <div class="flex-fill d-flex flex-column">
                            <div class="card mb-5">
                                <div class="card-body border rounded">
                                    <div class="d-flex flex-column">
                                        <h3>{{ empty($job_ad) ? "Buat" : "Edit" }} Job Ad</h3>
                                        <span>Tentukan kandidat ideal yang anda butuhkan saat ini, dan dapatkan segera dengan mudah!</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-5">
                                <div class="card-body border rounded">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fa fa-briefcase me-3"></span>
                                            <span class="fs-3">Detail Pekerjaan</span>
                                        </div>
                                        <div class="fv-row">
                                            <label for="position" class="col-form-label required">Posisi</label>
                                            <input type="text" name="position" id="position" onchange="updateThumbnail()" value="{{$job_ad->position ?? ""}}" class="form-control" required placeholder="Nama Posisi">
                                        </div>
                                        <div class="row row-cols-2">
                                            <div class="cols fv-row">
                                                <label for="job_type" class="col-form-label required">Pilih Tipe Pekerjaan</label>
                                                <select name="job_type" id="job_type" onchange="updateThumbnail()" class="form-select" data-control="select2" required data-placeholder="Pilih tipe pekerjaan">
                                                    <option value=""></option>
                                                    @foreach ($job_type as $item)
                                                        <option value="{{ $item->id }}" {{ !empty($job_ad) && $job_ad->job_type == $item->id ? "SELECTED" : "" }} >{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="cols fv-row">
                                                <label for="jabatan" class="col-form-label required">Jabatan</label>
                                                <select name="jabatan" id="jabatan" class="form-select" data-control="select2" required data-placeholder="Pilih jabatan">
                                                    <option value=""></option>
                                                    @foreach ($jabatan as $item)
                                                        <option value="{{ $item->id }}" {{ !empty($job_ad) && $job_ad->jabatan == $item->id ? "SELECTED" : "" }} >{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="cols fv-row">
                                                <label for="yoe" class="col-form-label required">Pengalaman Kerja</label>
                                                <div class="input-group">
                                                    <input type="number" name="yoe" id="yoe" onchange="updateThumbnail()" class="form-control border-right-0" value="{{ $job_ad->yoe ?? 1 }}" required placeholder="1 (tahun)">
                                                    <span class="input-group-text bg-transparent border-left-0">Tahun</span>
                                                </div>
                                            </div>
                                            <div class="cols fv-row">
                                                <label for="job_spec" class="col-form-label required">Bidang Pekerjaan</label>
                                                <select name="job_spec" id="job_spec" class="form-select" data-control="select2" required data-placeholder="Pilih bidang pekerjaan">
                                                    <option value=""></option>
                                                    @foreach ($job_spec as $item)
                                                        <option value="{{ $item->id }}" {{ !empty($job_ad) && $job_ad->job_spec == $item->id ? "SELECTED" : "" }} >{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-5">
                                <div class="card-body border rounded">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fa fa-briefcase me-3"></span>
                                            <span class="fs-3">Pendidikan</span>
                                        </div>
                                        <div class="fv-row">
                                            <label for="edu" class="col-form-label required">Tingkat pendidikan apa yang anda butuhkan</label>
                                            <select name="edu" id="edu" class="form-select" onchange="updateThumbnail()" data-control="select2" required data-placeholder="Pilih minimal pendidikan">
                                                <option value=""></option>
                                                @foreach ($edu as $item)
                                                    <option value="{{ $item->id }}" {{ !empty($job_ad) && $job_ad->edu == $item->id ? "SELECTED" : "" }} >{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-5">
                                <div class="card-body border rounded">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fa fa-map-location-dot me-3"></span>
                                            <span class="fs-3">Lokasi Tempat Kerja</span>
                                        </div>
                                        <div class="row row-cols-2">
                                            <div class="fv-row cols">
                                                <label for="prov_id" class="col-form-label">Provinsi</label>
                                                <select name="prov_id" id="prov_id" class="form-select" data-control="select2" data-placeholder="Pilih Provinsi">
                                                    <option value=""></option>
                                                    @foreach ($prov as $item)
                                                        <option value="{{ $item->id }}" {{ !empty($job_ad) && $job_ad->prov_id == $item->id ? "SELECTED" :  (!empty($company) && $company->prov_id == $item->id ? "SELECTED" : "") }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row cols">
                                                <label for="city_id" class="col-form-label">Kota</label>
                                                <select name="city_id" id="city_id" class="form-select" data-control="select2" data-placeholder="Pilih Kota">
                                                    <option value=""></option>
                                                    @foreach ($city as $item)
                                                        <option value="{{ $item->id }}" data-prov="{{ $item->prov_id }}" class="city-{{ $item->prov_id }}" {{ !empty($job_ad) && $job_ad->city_id == $item->id ? "SELECTED" : (!empty($company) && $company->city_id == $item->id ? "SELECTED" : "") }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row cols">
                                                <label for="kec_id" class="col-form-label">Kecamatan</label>
                                                <select name="kec_id" id="kec_id" class="form-select" data-placeholder="Pilih Kecamatan">
                                                    <option value=""></option>
                                                    @foreach ($kec_name as $id => $name)
                                                        <option value="{{ $id }}" class="kec-{{ $item->city_id }}" {{ !empty($job_ad) && $job_ad->kec_id == $item->id ? "SELECTED" : (!empty($company) && $company->kec_id == $id ? "SELECTED" : "") }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row cols">
                                                <label for="kode_pos" class="col-form-label">Kode Pos</label>
                                                <input type="text" name="kode_pos" id="kode_pos" class="form-control" value="{{ $job_ad->kode_pos ?? ($company->kode_pos ?? "") }}" placeholder="Masukan Kode Pos">
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <label for="detail_lokasi" class="col-form-label">Detail Lokasi</label>
                                            <textarea name="detail_lokasi" id="detail_lokasi" cols="30" rows="10" class="form-control">{!! $job_ad->detail_lokasi ?? "" !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-5">
                                <div class="card-body border rounded">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fa fa-dollar-sign me-3"></span>
                                            <span class="fs-3">Kompensasi</span>
                                        </div>
                                        <span class="my-3">Kandidat lebih cenderung melamar pekerjaan yang menawarkan informasi gaji</span>
                                        <div class="fv-row mb-5">
                                            <label for="edu" class="col-form-label">Berapa kisaran gaji bulanan yang anda tawarkan?</label>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <input type="text" onchange="updateThumbnail()" class="form-control number" name="salary_min" value="{{ $job_ad->salary_min ?? "" }}" id="salary_min" placeholder="Min">
                                                <span class="mx-2">Hingga</span>
                                                <input type="text" onchange="updateThumbnail()" class="form-control number" name="salary_max" value="{{ $job_ad->salary_max ?? "" }}" id="salary_max" placeholder="Max">
                                            </div>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" onchange="updateThumbnail()" type="checkbox" {{ !empty($job_ad) ? ($job_ad->show_salary == 1 ? "CHECKED" : "") : "" }} value="1" name="show_salary" id="showSalary" />
                                            <label class="form-check-label text-dark" for="showSalary">
                                                Tampilkan gaji di iklan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-5">
                                <div class="card-body border rounded">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fa fa-dollar-sign me-3"></span>
                                            <span class="fs-3">Job Deskripsi</span>
                                        </div>
                                        <span class="my-3">Iklan pekerjaan yang bagus berbicara tentang tanggung jawab, persyaratan, dan Spesifikasi
                                            yang jelas.</span>
                                        <div class="fv-row mb-5">
                                            <textarea name="job_description" class="form-control ck-editor" id="job_description" cols="30" rows="10">{!! $job_ad->job_description ?? "" !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-5">
                                @php
                                    $job_id = [];
                                    $job_point = [];
                                    if(!empty($job_ad)){
                                        $job_test = json_decode($job_ad->test_selected ?? "[]", true);
                                        foreach($job_test as $i => $jobi){
                                            $job_id[$i] = $jobi['id'];
                                            $job_point[$i] = $jobi['point'];
                                        }
                                    }
                                @endphp
                                <div class="card-body border rounded">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="fa fa-dollar-sign me-3"></span>
                                            <span class="fs-3">Test portal {{ \Config::get("constants.APP_LABEL") }}</span>
                                        </div>
                                        <span class="my-3">Kerjaku menyediakan bebebrapa test yang dapat di ikuti oleh semua kandidat, apakah anda ingin menajdikan test yang di ikuti kandidat di portal menjadi bahan untuk memfilter kandidat terbaik, jika ya silahkan masukan bobot nilai</span>
                                        <div class="fv-row mb-5">
                                            <div class="row row-cols-2">
                                                <label class="col-form-label cols">Test portal yang harus diikuti</label>
                                                <label class="col-form-label cols">Bobot point</label>
                                            </div>
                                            <div class="d-flex flex-column">
                                                @foreach ($test as $j => $item)
                                                    @php
                                                        $tpoin = $job_point[$j] ?? null;
                                                    @endphp
                                                    <div class="row row-cols-2 mb-5">
                                                        <div class="d-flex align-items-center cols">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" {{ in_array($item->id, $job_id) ? "CHECKED" : "" }} value="1" name="test[{{ $item->id }}]" id="test{{ $item->id }}" />
                                                                <label class="form-check-label text-dark text-nowrap" for="test{{ $item->id }}">
                                                                    {{ $item->label }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="cols">
                                                            <select name="test_point[{{ $item->id }}]" class="form-select" data-control="select2">
                                                                @for ($i = 1; $i <= 10; $i++)
                                                                    <option value="{{ $i }}" {{ $i == $tpoin ? "SELECTED" : "" }} >{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--begin::Step 1-->
                    <div class="flex-column" data-kt-stepper-element="content">
                        <div class="card mb-5">
                            <div class="card-body border rounded">
                                <div class="d-flex flex-column">
                                    <h3>Screening Question</h3>
                                    <span>Sertakan hingga 8 pertanyaan yang mudah dijawab di iklan pekerjaan Anda.Kandidat yang cocok dengan jawaban pilihan Anda langsung masuk ke bagian atas daftar Anda, sehingga Anda tahu siapa yang menjadi sasaran pertama.</span>
                                    <span><span id="soal-count">0</span>/5 Pertanyaan</span>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-5">
                            <div class="card-body border rounded">
                                <div class="d-flex flex-column">
                                    <button type="button" class="btn text-primary" data-bs-toggle="modal" data-bs-target="#modalAddQuestion">
                                        <i class="fa fa-plus-circle text-primary"></i>
                                        Buat Pertanyaan
                                    </button>
                                    <div class="d-flex flex-column" id="my-question">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--begin::Step 1-->

                    <!--begin::Step 1-->
                    <div class="flex-column" data-kt-stepper-element="content">
                        <div class="card mb-5">
                            <div class="card-body border rounded">
                                <div class="d-flex flex-column">
                                    <h3>Buat kesan pertama yang baik pada kandidat (Optional)</h3>
                                    <span>Lorem ipsum dolor sit amet consectetru adipiscing elit konsectetrut adpiasicing elit eusmood kendore hajeusnk kuljd</span>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-5">
                            <div class="card-body border rounded">
                                <div class="d-flex align-items-start">
                                    <div class="d-flex flex-column flex-fill">
                                        <div class="d-flex align-items-center">
                                            <span class="fa fa-building me-3"></span>
                                            <span class="fs-3">Informasi Perusahaan</span>
                                        </div>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td>Nama Perusahaan</td>
                                                <td>: {{ $company->company_name ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Industry</td>
                                                <td>: {{ $industri->name ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Registration Number</td>
                                                <td>: {{ $company->reg_num ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Perusahaan didirikan tahun</td>
                                                <td>: {{ $company->company_year ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Skala Usaha</td>
                                                <td>: {{ $company->skala_usaha ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Dress Code</td>
                                                <td>: {{ $company->dress_code ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Benefit</td>
                                                <td>: {{ $company->benefit ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Jam Kerja</td>
                                                <td>: {{ $company->jam_kerja ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td>Link Website Perusahaan</td>
                                                <td>: {{ $company->link_company ?? "-" }}</td>
                                            </tr>
                                        </table>
                                        <div class="fv-row">
                                            <label for="description_company" class="col-form-label">Overview Perusahaan</label>
                                            <textarea name="description_company" id="description_company" class="form-control ck-editor" cols="30" rows="10">{!! $company->descriptions ?? "" !!}</textarea>
                                        </div>
                                    </div>
                                    <a href="{{ route("account.setting")."?v=company_profile" }}" target="_blank" class="btn btn-icon text-dark">
                                        <i class="fa fa-edit text-dark"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-5">
                            <div class="card-body border rounded">
                                <div class="d-flex align-items-start">
                                    <div class="d-flex flex-column flex-fill">
                                        <div class="d-flex align-items-center">
                                            <span class="fa fa-star me-3"></span>
                                            <span class="fs-3">Branding</span>
                                        </div>
                                        <div class="d-flex flex-column flex-fill">
                                            <div class="fv-row mb-5">
                                                <label for="company_logo" class="col-form-label w-100">Logo Perusahaan</label>
                                                <!--begin::Image input-->
                                                <div class="image-input image-input-empty" data-kt-image-input="true">
                                                    <!--begin::Image preview wrapper-->
                                                    <div class="image-input-wrapper w-150px h-150px @empty($company->icon) border border-primary border-dashed @endempty" style="background-image: url({{ asset($company->icon ?? "") }}); background-size: cover;">
                                                        @empty($company->icon)
                                                            <div class="align-items-center d-flex flex-column h-100 justify-content-center w-100">
                                                                <i class="fa fa-image text-primary"></i>
                                                                <span class="text-center text-primary">Tambah Logo Perusahaan</span>
                                                                <span class="text-center text-primary">Max 150x150 Pixels</span>
                                                            </div>
                                                        @endempty
                                                    </div>
                                                    <!--end::Image preview wrapper-->
                                                </div>
                                                <!--end::Image input-->
                                            </div>
                                            <div class="fv-row mb-5">
                                                <label for="company_logo" class="col-form-label w-100">Gambar Banner Perusahaan</label>
                                                <!--begin::Image input-->
                                                <div class="image-input image-input-empty w-100" data-kt-image-input="true">
                                                    <!--begin::Image preview wrapper-->
                                                    <div class="image-input-wrapper @empty($company->banner) border border-primary border-dashed @endempty w-100" style="background-image: url({{ asset($company->banner ?? "") }});height: 305px; background-size: cover;">
                                                        @empty($company->banner)
                                                            <div class="align-items-center d-flex flex-column h-100 justify-content-center w-100">
                                                                <i class="fa fa-image text-primary"></i>
                                                                <span class="text-center text-primary">Tambah Banner Perusahaan</span>
                                                                <span class="text-center text-primary">Max 1168x305 Pixels</span>
                                                            </div>
                                                        @endempty
                                                    </div>
                                                    <!--end::Image preview wrapper-->
                                                </div>
                                                <!--end::Image input-->
                                            </div>
                                            <div class="fv-row mb-5">
                                                <label for="youtube_link" class="col-form-label w-100 d-flex flex-column">
                                                    <span class="fw-bold">Tautan Youtube Perusahaan Anda</span>
                                                    <span>Bagikan Video Youtube Anda Untuk memberikan pengenalan dan informasi yang baik tentang perusahaan anda</span>
                                                </label>
                                                <input type="text" class="form-control" readonly name="youtube_link" value="{{ $company->youtube_link ?? "" }}" id="youtube_link" placeholder="Masukan link youtube anda">
                                            </div>
                                            <div class="fv-row">
                                                <label for="comIm" class="col-form-label w-100 d-flex flex-column">
                                                    <span class="fw-bold">Foto Perusahaan</span>
                                                    <span>Pamerkan 10 Foto kegiatan dan perusahaan Anda</span>
                                                </label>
                                                @if ($compImages->count() == 0)
                                                    <!--begin::Image input-->
                                                    <div class="image-input image-input-empty w-100" data-kt-image-input="true">
                                                        <!--begin::Image preview wrapper-->
                                                        <div class="image-input-wrapper border border-primary border-dashed w-150px h-150px">
                                                            <div class="align-items-center d-flex flex-column h-100 justify-content-center w-100">
                                                                <i class="fa fa-image text-primary"></i>
                                                                <span class="text-center text-primary">Tambah Foto Perusahaan</span>
                                                            </div>
                                                        </div>
                                                        <!--end::Image preview wrapper-->
                                                    </div>
                                                    <!--end::Image input-->
                                                @else
                                                    <div class="d-flex">
                                                        <!--begin::Image input-->
                                                        <div class="image-input image-input-empty me-5" data-kt-image-input="true">
                                                            <!--begin::Image preview wrapper-->
                                                            <div class="image-input-wrapper w-300px h-300px" style="background-image: url({{ asset($compImages[0]->file_address) }}); background-size: cover;">
                                                            </div>
                                                            <!--end::Image preview wrapper-->
                                                        </div>
                                                        <!--end::Image input-->
                                                        @php
                                                            $_r = ($compImages->count() - 1) / 2;
                                                        @endphp
                                                        @for ($ir = 0; $ir < $_r; $ir++)
                                                            <div class="d-flex flex-column me-5">
                                                                @foreach ($compImages->skip((2 * ($ir)) + 1)->take(2) as $cim)
                                                                    {{ $cim->id }}
                                                                    <div class="cols">
                                                                        <!--begin::Image input-->
                                                                        <div class="image-input image-input-empty" data-kt-image-input="true">
                                                                            <!--begin::Image preview wrapper-->
                                                                            <div class="image-input-wrapper w-150px h-150px" style="background-image: url({{ asset($cim->file_address) }}); background-size: cover;">
                                                                            </div>
                                                                            <!--end::Image preview wrapper-->
                                                                        </div>
                                                                        <!--end::Image input-->
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endfor
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--begin::Step 1-->

                    <!--begin::Step 1-->
                    <div class="flex-column" data-kt-stepper-element="content">
                        <div class="card mb-5">
                            <div class="card-body border rounded">
                                <div class="d-flex flex-column">
                                    <h3>Job Ad</h3>
                                    <span>Pilih jenis iklan yang paling sesuai dengan peran yang ingin Anda isi.Semua iklan dicantumkan di desktop, seluler, dan tablet dan direkomendasikan untuk kandidat yang relevan.</span>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-5">
                            <div class="card-body border rounded">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-5">
                                        <span class="fa fa-credit-card me-3"></span>
                                        <span class="fs-3">Pilih tipe kredit job ad</span>
                                    </div>
                                    <!--begin::Alert-->
                                    <div class="alert bg-light-danger d-flex flex-column flex-sm-row p-3 mb-10">
                                        <!--begin::Icon-->
                                        <i class="ki-duotone ki-information-2 fs-2hx text-danger me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <!--end::Icon-->

                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column pe-0 pe-sm-10">

                                            <!--begin::Content-->
                                            <span>Harga iklan yang ditampilkan di sini belum termasuk PPN. Anda akan melihat jumlah pembayaran saat checkout.</span>
                                            <!--end::Content-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Alert-->
                                    <div class="row row-cols-3">
                                        @foreach ($packages as $k => $item)
                                            <div class="border rounded border-active-primary bg-active-light p-3 div-package">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold mb-3">{{ $item->label }}</span>
                                                    <span class="text-muted" style="text-decoration: line-through">Rp. {{ number_format($item->price, 2, ",", ".") }}</span>
                                                    <span class="fw-bold">Rp. {{ number_format($item->price - ($item->price * ($item->discount / 100)), 2, ",", ".") }}</span>
                                                    <div class="my-2">
                                                        <span class="badge badge-light-primary">{{ "$item->discount%" }}</span>
                                                    </div>
                                                    <span>{{ $item->descriptions }}</span>
                                                    <button type="button" onclick="pilihPackage(this)" class="btn btn-outline btn-outline-primary my-5 bg-active-primary text-active-white">
                                                        Pilih
                                                    </button>
                                                    <span class="fw-bold">Tayang {{ $item->tayang_days }} Hari</span>
                                                    @if (!empty($item->job_credits))
                                                        <span class="fw-bold">Credit job ads {{ $item->job_credits }}</span>
                                                    @endif
                                                    @if (!empty($item->search_applicant))
                                                        <span class="fw-bold">Search Kandidat {{ $item->search_applicant }}</span>
                                                    @endif
                                                    <input type="radio" name="package" {{ $k == 0 ? "CHECKED" : "" }} style="display: none" value="{{ $item->id }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-5">
                            <div class="card-body border rounded">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-5">
                                        <span class="fa fa-calendar me-3"></span>
                                        <span class="fs-3">Tanggal Posting</span>
                                    </div>
                                    <div class="fv-row">
                                        <label for="tanggal_posting" class="col-form-label">Kapan anda akan posting pekerjaan ini?</label>
                                        <input type="text" class="form-control tempusDominus" id="tanggal_posting" name="tanggal_posting" value="{{ date("d/m/Y") }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--begin::Step 1-->

                    <!--begin::Actions-->
                    <div class="d-flex flex-stack">
                        <!--begin::Wrapper-->
                        <div class="me-2">
                            <button type="button" class="btn btn-light btn-active-light-primary"
                                data-kt-stepper-action="previous">
                                Kembali
                            </button>
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Wrapper-->
                        <div>
                            @csrf
                            <input type="hidden" name="job_id" value="{{$job_ad->id ?? null}}">
                            <button type="submit" class="btn btn-primary" data-kt-stepper-action="submit">
                                <span class="indicator-label">
                                    Simpan
                                </span>
                                <span class="indicator-progress">
                                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>

                            <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                                Selanjutnya
                            </button>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Actions-->
                </form>
            </div>
            <div class="d-flex flex-column min-w-300px ms-5">
                <span class="fw-bold mb-3">Preview job ads thumbnail</span>
                <div class="d-flex bg-white border rounded p-3">
                    <div class="symbol symbol-40px me-5">
                        <img src="{{ asset($company->icon ?? "theme/assets/media/avatars/blank.png") }}" alt="">
                    </div>
                    <div class="d-flex flex-column flex-fill">
                        <span class="fw-bold mb-3 txt-posisi">-</span>
                        <span class="fw-semibold mb-3 txt-perusahaan">{{$company->company_name ?? "-"}}</span>
                        <span class="mb-5 txt-lokasi">-</span>
                        <div class="d-flex align-items-center">
                            <i class="far text-dark fa-clock me-3"></i>
                            <span class="txt-tipe">-</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa text-dark fa-briefcase me-3"></i>
                            <span class="txt-ex">-</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa text-dark fa-book me-3"></i>
                            <span class="txt-edu">-</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="text-dark me-2">Rp.</span>
                            <span class="txt-salary">-</span>
                        </div>
                    </div>
                    <i class="far fa-bookmark text-dark"></i>
                </div>
            </div>
        </div>
        {{-- Modal add --}}
        <div class="modal fade" id="modalAddQuestion" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalAddQuestion" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Job Vacancy</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>

                    <form method="post" action="{{route('job.add')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="d-flex flex-column">
                                <div class="row row-cols-2 mb-5">
                                    <div class="cols">
                                        <div class="fv-row">
                                            <label for="tq" class="col-form-label">Pilih type pertanyaan</label>
                                            <input type="text" name="question" id="question" class="form-control" placeholder="Masukan soal pertanyaan">
                                        </div>
                                    </div>
                                    <div class="cols">
                                        <div class="fv-row">
                                            <label for="tq" class="col-form-label">type pertanyaan</label>
                                            <select name="qtype" id="qtype" class="form-select" data-control="select2">
                                                <option value="1">Pilihan ganda</option>
                                                <option value="2">Checkbox</option>
                                                <option value="3">Isian bebas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="options mt-2" style="display: none">
                                    <div class="d-flex flex-column opt-div mb-5"></div>
                                    <div class="d-flex q-action">
                                        <button type="button" class="btn btn-outline btn-outline-danger me-3" onclick="cancelPertanyaan()">
                                            Batal
                                        </button>
                                        <button type="button" class="btn btn-outline btn-outline-primary" onclick="addOptions()">
                                            Tambah pilihan
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="button" onclick="buatPertanyaan(this)" id="btn-add-pt" class="btn btn-outline btn-outline-primary">
                                        Buat pilihan
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="btn-add-pertanyaan" class="btn btn-primary font-weight-bold">
                                <i class="fa fa-check"></i>
                                Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('custom_script')
        <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
        <script>

            function updateThumbnail(){
                $(".txt-posisi").text( $("[name=position]").val() == "" ? "-" : $("[name=position]").val() )
                $(".txt-tipe").text( $("[name=job_type]").val() == "" ? "-" : $("[name=job_type] option:selected").text() )
                $(".txt-ex").text($("[name=yoe]").val() == "" ? "-" : $("[name=yoe]").val() + " tahun")
                $(".txt-edu").text( $("[name=edu]").val() == "" ? "-" : $("[name=edu] option:selected").text())

                var salary_min = $("[name=salary_min]").val()
                var salary_max = $("[name=salary_max]").val()
                var show_salary = $("[name=show_salary]:checked")
                if(show_salary.length == 0){
                    $(".txt-salary").text("Kompetitif Salary")
                } else {
                    var slText = "-"
                    if(salary_min != ""){
                        slText = ""
                        slText += $.number(salary_min, 2, ",", ".")
                    }
                    if(salary_max != ""){
                        slText += " - "+ $.number(salary_max, 2, ",", ".")
                    }
                    $(".txt-salary").text(slText)
                }

                var prov = $("#prov_id option:selected").text()
                var city = $("#city_id option:selected").text()
                var lokasi = (city == "" ? "-" : city) + ", " + (prov == "" ? "-" : prov)
                $(".txt-lokasi").text(lokasi)
            }

            Inputmask({
                "mask" : "99/99/9999"
            }).mask(".tempusDominus");

            $(".tempusDominus").each(function(){
                var _id = $(this).attr("id")
                var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                    display : {
                        viewMode: "calendar",
                        components: {
                            decades: true,
                            year: true,
                            month: true,
                            date: true,
                            hours: false,
                            minutes: false,
                            seconds: false
                        }
                    },
                    localization: {
                        locale: "id",
                        startOfTheWeek: 1,
                        format: "dd/MM/yyyy"
                    }
                });
            })

            $("[name=qtype]").change(function(){
                $("#btn-add-pt").show()
                if($(this).val() == 3){
                    $("#btn-add-pt").hide()
                }
            })

            function pilihPackage(me){
                var parents = $(me).parents(".div-package")
                $(".div-package").removeClass("active")
                $(".div-package").find("button").addClass("btn-outline").removeClass("active")
                parents.find("input[name=package]").prop("checked", true)
                parents.addClass("active")
                parents.find("button").removeClass("btn-outline").addClass("active")
            }

            $("[name=package]").each(function(){
                var parents = $(this).parents(".div-package")
                $(parents).removeClass("active")
                $(parents).find("button").addClass("btn-outline").removeClass("active")
                if(this.checked == true){
                    parents.addClass("active")
                    parents.find("button").removeClass("btn-outline").addClass("active")
                }
            })

            var soal = []

            function isEdit(){
                $.ajax({
                    url : encodeURI("{{ route('job.add.view')."?id=".($job_ad->id ?? null) }}" + "&e=soal"),
                    type : "get",
                    dataType : "json"
                }).then(function(resp){
                    soal = resp.data
                    drawSoal()
                })
            }

            function drawSoal(){
                $("#my-question").html("")
                console.log(soal)
                for (let i = 0; i < soal.length; i++) {
                    var element = soal[i]
                    var opts_label = ""
                    var opt_checked = ""
                    if(element['is_required'] != undefined){
                        if(element['is_required'] == 1){
                            opt_checked = "checked"
                        }
                    }
                    var soal_id = element['id'] ?? null
                    if(element['type'] != 3){
                        var opts = element['options']
                        var _tp = element['type'] == 1 ? "radio" : "checkbox"
                        for (let j = 0; j < opts.length; j++) {
                            const elOpt = opts[j];
                            var isChecked = elOpt['is_true'] ? "checked" : "";

                            var opt_id = elOpt['id'] ?? null
                            var input_opt_id = ""
                            if(opt_id != null){
                                input_opt_id = `<input type="hidden" name="soal[${i}][opt][${j}][id]" value="${opt_id}">`
                            }

                            opts_label += `<div class="form-check form-check-custom form-check-solid mb-5">
                                <input class="form-check-input" type="${_tp}" ${isChecked} value="1" name="options_true"/>
                                <label class="form-check-label">
                                    ${elOpt['label']}
                                </label>
                            </div>`
                            opts_label += `<input type="hidden" name="soal[${i}][opt][${j}][label]" value="${elOpt['label']}">`
                            opts_label += `<input type="hidden" name="soal[${i}][opt][${j}][is_true]" value="${elOpt['is_true']}">`
                            opts_label += input_opt_id
                        }
                    }

                    var input_id = ""
                    if(soal_id != null){
                        input_id = `<input type="hidden" name="soal[${i}][id]" value="${soal_id}">`
                    }

                    var el = `<div class="card-body bg-secondary border rounded mb-5" data-role='question'>
                                <div class="d-flex align-items-start">
                                    <div class="form-check form-check-custom form-check-solid me-5">
                                        <input class="form-check-input" type="checkbox" checked value="1" name="q_sel[${i}]"/>
                                        <label class="form-check-label">
                                        </label>
                                    </div>
                                    <div class="d-flex flex-fill flex-column">
                                        <span class="fw-bold">${element['soal']}</span>
                                        <span>Saya akan menerima jawaban di atas dan termasuk di bawah</span>
                                        <div class="d-flex flex-column my-5">
                                            ${opts_label}
                                        </div>
                                        <div class="separator separator-solid mb-5"></div>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" ${opt_checked} value="is_required[${i}]" id=""/>
                                            <label class="form-check-label" for="">
                                                Persyaratan yang wajib dimiliki
                                            </label>
                                            <input type="hidden" name="soal[${i}][label]" value="${element['soal']}">
                                            <input type="hidden" name="soal[${i}][type]" value="${element['type']}">
                                            ${input_id}
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeQuestion(this)" data-index='${i}' class="btn btn-icon btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>`

                    $("#my-question").append(el)
                }
                $("#soal-count").text(soal.length);
            }

            $("#btn-add-pertanyaan").click(function(){
                if(soal.length >= 5){
                    Swal.fire("", "Soal sudah mencapai maksimal", "warning")
                } else {
                    if($("[name=question]").val() == ""){
                    Swal.fire("", "soal harus diisi", 'warning')
                } else {
                    var tp = $("[name=qtype]").val()
                    var col = {}
                    col['soal'] = $("[name=question]").val()
                    col['type'] = tp
                    if(tp == 3){
                        soal.push(col)
                        cancelPertanyaan();
                        $("#modalAddQuestion").modal("hide")
                        drawSoal()
                    } else {
                        var opts = []
                        $("#modalAddQuestion .opt-div").find("input[name='options_label[]']").each(function(index){
                            if(tp == 1){
                                var _opt = $("#modalAddQuestion .opt-div").find("input[type=radio]").eq(index).is(":checked")
                            } else {
                                var _opt = $("#modalAddQuestion .opt-div").find("input[type=checkbox]").eq(index).is(":checked")
                            }
                            if ($(this).val() != "") {
                                var op = {}
                                op['label'] = $(this).val()
                                op['is_true'] = _opt;
                                opts.push(op)
                            }
                        })

                        var _checked = 0
                        if(tp == 1){
                            $("#modalAddQuestion .opt-div").find("input[type=radio]").each(function(){
                                if(this.checked){
                                    _checked++
                                }
                            })
                        } else {
                            $("#modalAddQuestion .opt-div").find("input[type=checkbox]").each(function(){
                                if(this.checked){
                                    _checked++
                                }
                            })
                        }

                        if(opts.length != $("#modalAddQuestion .opt-div").find("input[name='options_label[]']").length){
                            return Swal.fire("", "Pilihan tidak boleh kosong", "warning")
                        } else {
                            if(_checked == 0){
                                return Swal.fire("", "Jawaban benar harus dipilih", "warning")
                            } else {
                                col['options'] = opts
                                soal.push(col)
                                cancelPertanyaan();
                                $("#modalAddQuestion").modal("hide")
                                drawSoal()
                            }
                        }
                    }
                }
                }
            })

            function removeQuestion(me){
                var parents = $(me).parents("div[data-role='question']")
                var index = $(me).data('index')
                soal.splice(index, 1)

                console.log(soal)
                drawSoal()
            }

            function removeOptions(me){
                $(me).parent().remove()
                var opts = $(".opt-div").find("div.d-flex.mb-3")
                if(opts.length == 0){
                    cancelPertanyaan()
                }
            }

            function addOptions(){
                var tp = $("[name=qtype]").val()
                var opt = initOptions(tp)
                $("#modalAddQuestion .opt-div").append(opt)
            }

            function initOptions(type){
                if(type == 1){
                    var options = `<div class="d-flex mb-3">
                            <input type="text" placeholder="Masukan jawab option 1" class="form-control me-5" name='options_label[]'>
                            <div class="form-check form-check-custom form-check-solid me-5">
                                <input class="form-check-input" type="radio" value="1" name="options_true"/>
                                <label class="form-check-label">
                                    Jawaban benar
                                </label>
                            </div>
                            <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="removeOptions(this)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>`
                } else if (type == 2){
                    var options = `<div class="d-flex mb-3">
                            <input type="text" placeholder="Masukan jawab option 1" class="form-control me-5" name='options_label[]'>
                            <div class="form-check form-check-custom form-check-solid me-5">
                                <input class="form-check-input" type="checkbox" value="1" name="options_true"/>
                                <label class="form-check-label">
                                    Jawaban benar
                                </label>
                            </div>
                            <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="removeOptions(this)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>`
                }

                return options
            }

            function cancelPertanyaan(){
                $("#modalAddQuestion .opt-div").html("")
                $("[name=question]").prop('disabled', false).val("")
                $("[name=qtype]").prop('disabled', false).val("1").trigger('change')
                $("#modalAddQuestion .options").hide()
                $("#btn-add-pt").show()
            }

            function buatPertanyaan(me){
                if($("[name=question]").val() == ""){
                    Swal.fire("", "soal harus diisi", 'warning')
                } else {
                    $(me).hide()
                    $("[name=question]").prop('disabled', true)
                    $("[name=qtype]").prop('disabled', true)
                    $("#modalAddQuestion .options").show()

                    var tp = $("[name=qtype]").val()
                    var opt = initOptions(tp)
                    $("#modalAddQuestion .opt-div").append(opt)
                    $("#modalAddQuestion .opt-div").append(opt)
                }
            }

            // Stepper lement
            var element = document.querySelector("#kt_stepper_example_basic");

            // Initialize Stepper
            var stepper = new KTStepper(element);

            // Handle next step
            stepper.on("kt.stepper.next", async function(stepper) {
                var $next = await validate_button(stepper.getCurrentStepIndex())
                console.log($next)
                if($next){
                    stepper.goNext(); // go next step
                    var now = stepper.getElement();
                    menu_change(stepper.getCurrentStepIndex() - 1);
                }
            });

            stepper.on("kt.stepper.submit", function(stepper) {
                console.log("heho")
            })

            // Handle previous step
            stepper.on("kt.stepper.previous", function(stepper) {
                stepper.goPrevious(); // go previous step
                menu_change(stepper.getCurrentStepIndex() - 1);
            });

            // Handle navigation click
            stepper.on("kt.stepper.click", function (stepper) {
                console.log($(this))
                stepper.goTo(stepper.getClickedStepIndex()); // go to clicked step
                menu_change(stepper.getCurrentStepIndex() - 1);
            });

            function menu_change(index){
                $("[data-kt-stepper-action='step']").removeClass("active")
                $("[data-kt-stepper-action='step']").find(".menu-title").removeClass("active")
                $("[data-kt-stepper-action='step']").eq(index).addClass("active")
                $("[data-kt-stepper-action='step']").eq(index).find(".menu-title").addClass("active")
            }

            function validation_step(index){
                var f = document.querySelector(`#form-add`)

                var required = $(f).find("[data-form="+index+"]").find(":required")
                var fields = {}
                required.each(function() {
                    var fv = $(this).parents("div.fv-row")
                    var lbl = $(fv).find("label.col-form-label")

                    var name = $(this).attr('name')

                    var validators = {
                        notEmpty: {
                            message: lbl.text() + " harus diisi"
                        }
                    }

                    var isNpwp = $(this).hasClass("npwp");
                    if (isNpwp) {
                        validators.callback = {
                            message: "Invalid NPWP",
                            callback: function(input) {
                                return Inputmask.isValid(input.value, {
                                    "mask": "99.999.999.9-999.999"
                                });
                            }
                        }
                    }

                    fields[name] = {
                        validators: validators
                    }
                })

                fv = FormValidation.formValidation(f, {
                    fields: fields,
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                })

                $(f).find("select[data-control=select2]").change(function() {
                    var name = $(this).attr("name");
                    fv.revalidateField(name);
                })
            }

            async function validate_button(index){
                if(index == 1){
                    var $next = false
                    $next = await fv.validate().then(function(status) {
                        console.log(status)
                        if (status == 'Valid') {
                            return true
                        } else {
                            return false
                        }
                    })

                    console.log($next)

                    return $next
                } else {
                    return true;
                }
            }

            var kecSel = $("#kec_id").select2()


            function kec_url(){

                var _kec_post = {}
                _kec_post['prov'] = $("#prov_id").val()
                _kec_post['city'] = $("#city_id").val()

                var _kec_url = "{{ route("job.add.view") }}"
                _kec_url += "?t=kecamatan&f=" + JSON.stringify(_kec_post)
                kecSel.select2('destroy')

                $("#kec_id").select2({
                    ajax : {
                        url : function(){
                            var _kec_url = "{{ route("job.add.view") }}"
                            _kec_url += "?t=kecamatan&f=" + JSON.stringify(_kec_post)
                            console.log(_kec_url)
                            return encodeURI(_kec_url)
                        },
                        type : "GET",
                        dataType : "json"
                    }
                }).change(function(){
                    var data = $(this).select2("data")
                    var el = data[0]
                    console.log(el)
                    $("#city_id").val(el['city_id']).trigger("change")
                })
            }

            $(document).ready(function() {

                $("input.number").number(true, 2)

                validation_step(1)

                // kec_url()

                @if(!empty($job_ad))
                    isEdit()
                @endif

                $("#prov_id").on("change load",function(){
                    var id = $(this).val()
                    updateThumbnail()
                    if(id != ""){
                        // kec_url()
                        if(id != $("#city_id option:selected").data("prov")){
                            $("#city_id").val("").trigger("change")
                        }
                        $("#city_id option").prop("disabled", true)
                        $(`#city_id option[data-prov=${id}]`).prop("disabled", false)
                        $("#kec_id").val("").trigger("change")
                    }
                })

                var pr_id = $("#prov_id").val()
                if(pr_id != ""){
                    $("#city_id option").prop("disabled", true)
                    $(`#city_id option[data-prov=${pr_id}]`).prop("disabled", false)
                }

                $("#city_id").on("change load", function(){
                    var id = $(this).val()
                    updateThumbnail()
                    if(id != ""){
                        // kec_url()
                        var city_data = $("#kec_id").select2("data")
                        var data = city_data[0]
                        if(data['city_id'] != id){
                            $("#kec_id").val("").trigger("change")
                        }
                        if($("#prov_id").val() == ""){
                            $("#prov_id").val($("#city_id option:selected").data("prov")).trigger('change')
                        }
                    }
                })

                $("#kec_id").select2({
                    ajax : {
                        url : function(){
                            var _kec_post = {}
                            _kec_post['prov'] = $("#prov_id").val()
                            _kec_post['city'] = $("#city_id").val()
                            var _kec_url = "{{ route("job.add.view") }}"
                            _kec_url += "?t=kecamatan&f=" + JSON.stringify(_kec_post)
                            return encodeURI(_kec_url)
                        },
                        type : "GET",
                        dataType : "json"
                    }
                }).change(function(){
                    var data = $(this).select2("data")
                    var el = data[0]
                    if($(this).val() != "" && $("#city_id").val() == null){
                        $("#city_id").val(el['city_id']).trigger("change")
                    }
                })

                updateThumbnail()


            })
        </script>
    @endsection
@else
    @section('content')
        @include('_error.desktop_only')
    @endsection
@endif
