<div class="card card-custom bg-transparent">
    <div class="card-header border-0">
        <h3 class="card-title">{{ __("user.company_profile") }}</h3>
    </div>
</div>
<!--begin::Form-->
<div class="card card-custom card-stretch bg-transparent">
    <div class="card-header w-100 border-0">
        <div class="card-toolbar w-100">
            <ul class="border-0 d-flex fs-6 justify-content-between mb-5 nav nav-line-tabs nav-tabs w-100 fw-bold">
                <li class="nav-item">
                    <a class="nav-link text-hover-primary text-dark text-active-primary active" data-bs-toggle="tab" href="#kt_tab_pane_1">Informasi Perusahaan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-hover-primary text-dark text-active-primary" data-bs-toggle="tab" href="#kt_tab_pane_2">Lokasi Perusahaan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-hover-primary text-dark text-active-primary" data-bs-toggle="tab" href="#kt_tab_pane_3">Tambahan Informasi Perusahaan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-hover-primary text-dark text-active-primary" data-bs-toggle="tab" href="#kt_tab_pane_4">Branding</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
            <div class="card-body border bg-white rounded">
                <div class="d-flex">
                    <div class="flex-fill d-flex flex-column">
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Nama Perusahaan Terdaftar</label>
                            <label class="col-form-label">{{ $company->company_name ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Industri</label>
                            <label class="col-form-label">{{ $industri_name[$company->industry_id ?? -1] ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">NPWP</label>
                            <label class="col-form-label">{{ $company->npwp ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Nomor Registrasi</label>
                            <label class="col-form-label">{{ $company->reg_num ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Overview Perusahaan</label>
                            <label class="col-form-label">{!! $company->descriptions ?? "-" !!}</label>
                        </div>
                    </div>
                    {{-- @actionStart('employer_profile', 'update') --}}
                    <button class="btn btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalCompany">
                        <i class="fa fa-edit text-dark"></i>
                    </button>
                    {{-- @actionEnd --}}
                </div>
                {{-- @actionStart('employer_profile', 'update') --}}
                <div class="modal fade" tabindex="-1" id="modalCompany">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Company Information</h3>

                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <form action="{{ route("account.comp.store") }}" id="form-company-info" method="post">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="company_name" class="col-form-label required">Nama Perusahaan Terdaftar</label>
                                                <input type="text" name="company_name" id="company_name" class="form-control" value="{{ $company->company_name ?? "" }}" required placeholder="Masukkan Nama Perusahaan Terdaftar">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="industri" class="col-form-label required">Industri</label>
                                                <select name="industri" id="industri" class="form-select" data-control="select2" data-dropdown-parent="#modalCompany" required data-placeholder="Pilih Industri">
                                                    <option value=""></option>
                                                    @foreach ($industri as $item)
                                                        <option value="{{ $item->id }}" {{ !empty($company) && $company->industry_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="npwp" class="col-form-label required">NPWP</label>
                                                <input type="text" name="npwp" id="npwp" class="form-control npwp masked" value="{{ $company->npwp ?? "" }}" required placeholder="Masukkan NPWP">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="reg_num" class="col-form-label required">Registrasi Number</label>
                                                <input type="text" name="reg_num" id="reg_num" class="form-control" value="{{ $company->reg_num ?? "" }}" required placeholder="Masukkan Registrasi Number">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="fv-row">
                                                <label for="overview" class="col-form-label required">Overview Perusahaan</label>
                                                <textarea name="overview" id="overview" required class="ck-editor form-control" cols="30" rows="10">{!! $company->descriptions ?? "" !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $company->id ?? null }}">
                                    <input type="hidden" name="type" value="info">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- @actionEnd --}}
            </div>
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
            <div class="card-body border bg-white rounded">
                <div class="d-flex">
                    <div class="flex-fill d-flex flex-column">
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Alamat Lengkap</label>
                            <label class="col-form-label">{!! $company->address ?? "-" !!}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Provinsi</label>
                            <label class="col-form-label">{{ $prov_name[$company->prov_id ?? -1] ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Kota</label>
                            <label class="col-form-label">{{ $city_name[$company->city_id ?? -1] ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Kecamatan</label>
                            <label class="col-form-label">{{ $kec_name[$company->kec_id ?? -1] ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Kode Pos</label>
                            <label class="col-form-label">{!! $company->kode_pos ?? "-" !!}</label>
                        </div>
                    </div>
                    {{-- @actionStart('employer_profile', 'update') --}}
                    <button class="btn btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalLocation">
                        <i class="fa fa-edit text-dark"></i>
                    </button>
                    {{-- @actionEnd --}}
                </div>
                {{-- @actionStart('employer_profile', 'update') --}}
                <div class="modal fade" tabindex="-1" id="modalLocation">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Lokasi Perusahaan</h3>

                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <form action="{{ route("account.comp.store") }}" id="form-company-lokasi" method="post">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="fv-row">
                                                <label for="address" class="col-form-label">Alamat Lengkap</label>
                                                <textarea name="address" id="address" class="ck-editor form-control" cols="30" rows="10">{!! $company->address ?? "" !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="prov_id" class="col-form-label">Provinsi</label>
                                                <select name="prov_id" id="prov_id" class="form-select" data-control="select2" data-dropdown-parent="#modalLocation" data-placeholder="Pilih Provinsi">
                                                    <option value=""></option>
                                                    @foreach ($prov as $item)
                                                        <option value="{{ $item->id }}" {{ !empty($company) && $company->prov_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="city_id" class="col-form-label">Kota</label>
                                                <select name="city_id" id="city_id" class="form-select" data-control="select2" data-dropdown-parent="#modalLocation" data-placeholder="Pilih Kota">
                                                    <option value=""></option>
                                                    @foreach ($city as $item)
                                                        <option value="{{ $item->id }}" data-prov="{{ $item->prov_id }}" class="city-{{ $item->prov_id }}" {{ !empty($company) && $company->city_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="kec_id" class="col-form-label">Kecamatan</label>
                                                <select name="kec_id" id="kec_id" class="form-select" data-dropdown-parent="#modalLocation" data-placeholder="Pilih Kecamatan">
                                                    <option value=""></option>
                                                    @foreach ($kec as $item)
                                                        <option value="{{ $item->id }}" data-prov="{{ $city_prov[$item->city_id] }}" data-city="{{ $item->city_id }}" class="kec-{{ $item->city_id }}" {{ !empty($company) && $company->kec_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="kode_pos" class="col-form-label">Kode Pos</label>
                                                <input type="text" name="kode_pos" id="kode_pos" class="form-control" value="{{ $company->kode_pos ?? "" }}" placeholder="Masukan Kode Pos">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $company->id ?? null }}">
                                    <input type="hidden" name="type" value="lokasi">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- @actionEnd --}}
            </div>
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
            <div class="card-body border bg-white rounded">
                <div class="d-flex">
                    <div class="flex-fill d-flex flex-column">
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Perusahaan didirikan pada tahun?</label>
                            <label class="col-form-label">{!! $company->company_year ?? "-" !!}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Skala Usaha</label>
                            <label class="col-form-label">{{ $company->skala_usaha ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Dress code yang digunakan</label>
                            <label class="col-form-label">{{ $company->dress_code ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Benefit yang diberikan</label>
                            <label class="col-form-label">{{ $company->benefit ?? "-" }}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Jam kerja perusahaan</label>
                            <label class="col-form-label">{!! $company->jam_kerja ?? "-" !!}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Link website perusahaan</label>
                            <label class="col-form-label">{!! $company->link_company ?? "-" !!}</label>
                        </div>
                        <div class="separator separator-solid"></div>
                        <div class="fv-row">
                            <label class="col-form-label fw-bold w-100">Bahasa yang digunakan</label>
                            <label class="col-form-label">{!! $languages_name[$company->bahasa_id ?? -1] ?? "-" !!}</label>
                        </div>
                    </div>
                    {{-- @actionStart('employer_profile', 'update') --}}
                    <button class="btn btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdditional">
                        <i class="fa fa-edit text-dark"></i>
                    </button>
                    {{-- @actionEnd --}}
                </div>

                {{-- @actionStart('employer_profile', 'update') --}}
                <div class="modal fade" tabindex="-1" id="modalAdditional">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Tambahan informasi perusahaan</h3>

                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <form action="{{ route("account.comp.store") }}" id="form-company-additional-info" method="post">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="company_year" class="col-form-label">Perusahaan didirikan pada tahun?</label>
                                                <input type="text" name="company_year" id="company_year" class="form-control mask-year" value="{{ $company->company_year ?? "" }}" placeholder="Input tahun">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="skala_usaha" class="col-form-label">Skala Usaha</label>
                                                <select name="skala_usaha" id="skala_usaha" class="form-select" data-control="select2" data-dropdown-parent="#modalAdditional" data-placeholder="Pilih Skala Usaha">
                                                    <option value=""></option>
                                                    <option value="<100" {{ !empty($company) && $company->skala_usaha == "<100" ? "SELECTED" : "" }}>{{ __("<100") }}</option>
                                                    <option value="100-500" {{ !empty($company) && $company->skala_usaha == "100-500" ? "SELECTED" : "" }}>{{ __("100-500") }}</option>
                                                    <option value="500-1000" {{ !empty($company) && $company->skala_usaha == "500-1000" ? "SELECTED" : "" }}>{{ __("500-1000") }}</option>
                                                    <option value=">1000" {{ !empty($company) && $company->skala_usaha == ">1000" ? "SELECTED" : "" }}>{{ __(">1000") }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="dress_code" class="col-form-label">Dress code yang digunakan</label>
                                                <select name="dress_code" id="dress_code" class="form-select" data-control="select2" data-dropdown-parent="#modalAdditional" data-placeholder="Pilih Dress code">
                                                    <option value=""></option>
                                                    <option value="Formal" {{ !empty($company) && $company->dress_code == "Formal" ? "SELECTED" : "" }}>{{ __("Formal") }}</option>
                                                    <option value="Casual" {{ !empty($company) && $company->dress_code == "Casual" ? "SELECTED" : "" }}>{{ __("Casual") }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="benefit" class="col-form-label">Benefit yang diberikan</label>
                                                <input type="text" name="benefit" id="benefit" class="form-control" value="{{ $company->benefit ?? "" }}" placeholder="Masukan benefit">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="jam_kerja" class="col-form-label">Jam kerja perusahaan</label>
                                                <select name="jam_kerja" id="jam_kerja" class="form-select" data-control="select2" data-dropdown-parent="#modalAdditional" data-placeholder="Pilih Jam kerja">
                                                    <option value=""></option>
                                                    <option value="09:00-18:00" {{ !empty($company) && $company->jam_kerja == "09:00-18:00" ? "SELECTED" : "" }}>{{ __("09:00-18:00") }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="fv-row">
                                                <label for="link_company" class="col-form-label">Link website perusahaan</label>
                                                <input type="text" name="link_company" id="link_company" class="form-control" value="{{ $company->link_company ?? "" }}" placeholder="Masukan link perusahaan anda">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="fv-row">
                                                <label for="bahasa_id" class="col-form-label">Bahasa yang digunakan</label>
                                                <select name="bahasa_id" id="bahasa_id" class="form-select" data-control="select2" data-dropdown-parent="#modalAdditional" data-placeholder="Pilih bahasa">
                                                    <option value=""></option>
                                                    @foreach ($languages_name as $id => $name)
                                                        <option value="{{ $id }}" {{ !empty($company) && $company->bahasa_id == $id ? "SELECTED" : "" }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $company->id ?? null }}">
                                    <input type="hidden" name="type" value="additional">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- @actionEnd --}}
            </div>
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
            <div class="card-body border bg-white rounded">
                <div class="d-flex">
                    <div class="d-flex flex-column flex-fill">
                        <span class="fs-3 text-dark">
                            <span class="fa fa-briefcase me-3"></span>
                            <span class="fw-bold">Branding</span>
                        </span>
                        <div class="fv-row mb-5">
                        @php
                        $comp_icon = null;
                        if(!empty($company->icon)){
                            $_icon = explode("/", $company->icon);
                            $_icon[count($_icon) - 1] = rawurlencode($_icon[count($_icon) - 1]);
                            $comp_icon = implode("/", $_icon);
                        }
                        @endphp
                            <label for="company_logo" class="col-form-label w-100">Logo Perusahaan</label>
                            <!--begin::Image input-->
                            <div class="image-input image-input-empty" data-kt-image-input="true">
                                <!--begin::Image preview wrapper-->
                                <div class="image-input-wrapper w-150px h-150px @empty($comp_icon) border border-primary border-dashed @endempty" style="background-image: url({{ asset($comp_icon ?? "") }}); background-size: cover;">
                                    @empty($comp_icon)
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
                    {{-- @actionStart('employer_profile', 'update') --}}
                    <button class="btn btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalBranding">
                        <i class="fa fa-edit text-dark"></i>
                    </button>
                    {{-- @actionEnd --}}
                </div>
                {{-- @actionStart('employer_profile', 'update') --}}
                <div class="modal fade" tabindex="-1" id="modalBranding">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content modal-lg">
                            <div class="modal-header">
                                <h3 class="modal-title">Edit Branding</h3>

                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <form action="{{ route("account.comp.store") }}" id="form-company-branding" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="d-flex flex-column flex-fill">
                                        <span class="fs-3 text-dark">
                                            <span class="fa fa-briefcase me-3"></span>
                                            <span class="fw-bold">Branding</span>
                                        </span>
                                        <div class="fv-row mb-10">
                                            <label for="company_icon" class="col-form-label w-100">Logo Perusahaan</label>
                                            <div class="d-flex flex-column">
                                                <!--begin::Image input-->
                                                <div class="image-input image-input-empty" data-kt-image-input="true">
                                                    <!--begin::Image preview wrapper-->
                                                    <div class="image-input-wrapper w-150px h-150px @empty($comp_icon) border border-primary border-dashed @endempty" style="background-image: url({{ asset($comp_icon ?? "") }}); background-size: cover;">
                                                        @empty($comp_icon)
                                                            <div class="align-items-center d-flex flex-column h-100 justify-content-center w-100">
                                                                <i class="fa fa-image text-primary"></i>
                                                                <span class="text-center text-primary">Tambah Logo Perusahaan</span>
                                                                <span class="text-center text-primary">Max 150x150 Pixels</span>
                                                            </div>
                                                        @endempty
                                                    </div>
                                                    <!--end::Image preview wrapper-->
                                                </div>
                                                <div class="image-input-edit">
                                                    <label  class="btn text-primary">
                                                        <i class="fa fa-edit text-primary"></i>
                                                        Edit Foto
                                                        <!--begin::Inputs-->
                                                        <input type="file" name="company_icon" accept=".png, .jpg, .jpeg" style="display: none" />
                                                        <!--end::Inputs-->
                                                    </label>
                                                </div>
                                                <!--end::Image input-->
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <div class="d-flex justify-content-between mb-5">
                                                <label for="company_banner" class="col-form-label w-100">Gambar Banner Perusahaan</label>
                                                <div class="image-input-edit">
                                                    <label  class="btn text-primary text-nowrap">
                                                        <i class="fa fa-edit text-primary"></i>
                                                        Edit Foto
                                                        <!--begin::Inputs-->
                                                        <input type="file" name="company_banner" accept=".png, .jpg, .jpeg" style="display: none" />
                                                        <!--end::Inputs-->
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
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
                                        </div>
                                        <div class="fv-row">
                                            <label for="youtube_link" class="col-form-label w-100 d-flex flex-column">
                                                <span class="fw-bold">Tautan Youtube Perusahaan Anda</span>
                                                <span>Bagikan Video Youtube Anda Untuk memberikan pengenalan dan informasi yang baik tentang perusahaan anda</span>
                                            </label>
                                            <input type="text" class="form-control" name="youtube_link" id="youtube_link" value="{{ $company->link_company ?? "" }}" placeholder="Masukan link youtube anda">
                                        </div>
                                        <div class="fv-row">
                                            <label for="comIm" class="col-form-label w-100 d-flex flex-column">
                                                <span class="fw-bold">Foto Perusahaan</span>
                                                <span>Pamerkan 10 Foto kegiatan dan perusahaan Anda</span>
                                            </label>
                                            <div class="row row-cols-5 img-multiple">
                                                <div class="cols d-flex flex-column img-clone">
                                                    <!--begin::Image input-->
                                                    <div class="image-input image-input-empty w-100" data-kt-image-input="true">
                                                        <!--begin::Image preview wrapper-->
                                                        <div class="image-input-wrapper @empty($company->icon) border border-primary border-dashed @endempty w-150px h-150px">
                                                            <div class="align-items-center d-flex flex-column h-100 justify-content-center w-100">
                                                                <i class="fa fa-image text-primary"></i>
                                                                <span class="text-center text-primary">Tambah Foto Perusahaan</span>
                                                            </div>
                                                        </div>
                                                        <!--end::Image preview wrapper-->
                                                    </div>
                                                    <div class="image-input-edit">
                                                        <label class="btn text-primary">
                                                            <i class="fa fa-edit text-primary"></i>
                                                            Edit Foto
                                                            <!--begin::Inputs-->
                                                            <input type="file" name="company_images[]" data-multiple="true" accept=".png, .jpg, .jpeg" style="display: none" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <label class="btn text-danger" style="display: none">
                                                            <i class="fa fa-trash text-danger"></i>
                                                            Hapus Foto
                                                        </label>
                                                    </div>
                                                    <!--end::Image input-->
                                                </div>
                                                @foreach ($compImages as $item)
                                                <div class="cols d-flex flex-column img-clone">
                                                    <!--begin::Image input-->
                                                    <div class="image-input image-input-empty w-100" data-kt-image-input="true">
                                                        <!--begin::Image preview wrapper-->
                                                        <div class="image-input-wrapper @empty($company->icon) border border-primary border-dashed @endempty w-150px h-150px" style="background-image: url({{ asset($item->file_address) }})"></div>
                                                        <!--end::Image preview wrapper-->
                                                    </div>
                                                    <div class="image-input-edit">
                                                        <label class="btn text-primary" style="display: none">
                                                            <i class="fa fa-edit text-primary"></i>
                                                            Edit Foto
                                                            <!--begin::Inputs-->
                                                            <input type="file" name="company_images[]" data-multiple="true" accept=".png, .jpg, .jpeg" style="display: none" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <label class="btn text-danger" onclick="removeImg(this)" data-id="{{$item->id}}">
                                                            <i class="fa fa-trash text-danger"></i>
                                                            Hapus Foto
                                                        </label>
                                                    </div>
                                                    <!--end::Image input-->
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $company->id ?? null }}">
                                    <input type="hidden" name="type" value="branding">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- @actionEnd --}}
            </div>
        </div>
    </div>
</div>
<!--end::Form-->

<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <form class="form" action="{{route('account.delete')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="align-items-center d-flex flex-column p-5">
                        <img src="{{ asset("images/delete-confirmation.png") }}" class="w-50 mb-5">
                        <span class="fw-bold fs-2 mb-3">Apakah anda yakin ingin menghapus akun?</span>
                        <span class="mb-5">Anda dapat mengubah kata sandi lagi setelah 15 hari</span>
                        <div>
                            <button type="submit" class="btn btn-primary">Yakin, Hapus</button>
                        </div>
                        <div>
                            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
