<!--begin::Accordion-->
<div class="accordion accordion-icon-collapse">
    <div class="accordion-item border-0 bg-secondary-crm">
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_personal_private">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-user fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Data Pribadi</h3>
                    <span>Data pribadi milik pegawai ini</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_personal_private" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <form action="{{ route("personel.employee_table.profile.store") }}" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="fv-row col-6">
                            <label class="col-form-label">Jenis Identitas</label>
                            <select name="identity_type" class="form-select" data-control="select2" data-dropdown-parent="#tab_general_personal">
                                <option value="ktp" {{ ($profile->identity_type ?? "") == "ktp" ? "SELECTED" : "" }}>KTP</option>
                                <option value="sim" {{ ($profile->identity_type ?? "") == "sim" ? "SELECTED" : "" }}>SIM</option>
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Nomor Identitas</label>
                            <input type="text" name="identity_number" value="{{ $profile->identity_number ?? "" }}" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Unggah Identity Document</label>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary btn-sm">
                                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="file" class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                <span class="text-primary ms-5" data-file></span>
                            </div>
                            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tempat Lahir</label>
                            <input type="text" name="emp_tmpt_lahir" value="{{ $personel->emp_tmpt_lahir ?? "" }}" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tanggal Lahir</label>
                            <input type="date" name="emp_lahir" value="{{ $personel->emp_lahir ?? "" }}" placeholder="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Kewarganegaraan</label>
                            <input type="text" name="citizenship" value="{{ $profile->citizenship ?? "-" }}" placeholder="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Status Pernikahan</label>
                            <select name="marital_status" class="form-select" data-control="select2" data-placeholder="Pilih Status Pernikahan" data-dropdown-parent="#tab_general_personal">
                                <option value=""></option>
                                @foreach ($marital_status as $item)
                                    <option value="{{ $item->id }}" {{ ($profile->marital_status ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Agam</label>
                            <select name="religion" class="form-select" data-control="select2" data-placeholder="Pilih Agam" data-dropdown-parent="#tab_general_personal">
                                <option value=""></option>
                                @foreach ($religion as $item)
                                    <option value="{{ $item->id }}" {{ ($profile->religion ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select" data-control="select2" data-placeholder="Pilih Jenis Kelamin" data-dropdown-parent="#tab_general_personal">
                                <option value=""></option>
                                @foreach ($gender as $item)
                                    <option value="{{ $item->id }}" {{ ($profile->gender ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Golongan Darah</label>
                            <select name="blood_type" class="form-select" data-control="select2" data-placeholder="Pilih Golongan Darah" data-dropdown-parent="#tab_general_personal">
                                <option value=""></option>
                                @foreach ($blood_type as $item)
                                    <option value="{{ $item->id }}" {{ ($profile->blood_type ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tinggi Badan</label>
                            <div class="position-relative">
                                <input type="text" name="height" value="{{ $profile->height ?? "" }}" placeholder="" class="form-control pe-13">
                                <span class="position-absolute end-0 top-25 me-5">cm</span>
                            </div>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Berat Badan</label>
                            <div class="position-relative">
                                <input type="text" name="weight" value="{{ $profile->weight ?? "" }}" placeholder="" class="form-control pe-13">
                                <span class="position-absolute end-0 top-25 me-5">Kg</span>
                            </div>
                        </div>
                        <div class="fv-row col-12">
                            <label class="col-form-label">Alamat Pada Identitas</label>
                            <textarea name="identity[address]" id="" cols="30" class="form-control" rows="5">{{ $profile->identity_address ?? "" }}</textarea>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Kode Pos</label>
                            <input type="text" name="identity[zip_code]" value="{{ $profile->identity_zip_code ?? "" }}" placeholder="Masukan Kode Pos" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Negara</label>
                            <input type="text" name="identity[country]" value="{{ $profile->identity_country ?? "" }}" placeholder="Masukan Negara" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Provinsi</label>
                            <input type="text" name="identity[province]" value="{{ $profile->identity_province ?? "" }}" placeholder="Masukan Provinsi" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Kota</label>
                            <input type="text" name="identity[city]" value="{{ $profile->identity_city ?? "" }}" placeholder="Masukan Kota" class="form-control">
                        </div>
                        <div class="fv-row">
                            <div class="form-check col-form-label">
                                <span class="form-check-label">
                                    Alamat Tempat Tinggal sama dengan Alamat Identitas
                                    <input class="form-check-input" name="resident_identity" type="checkbox" value="1" id="" />
                                </span>
                            </div>
                        </div>
                        <div class="fv-row col-12">
                            <label class="col-form-label">Alamat Tempat Tinggal</label>
                            <textarea name="resident[address]" id="" cols="30" class="form-control" rows="5">{{ $profile->resident_address ?? "" }}</textarea>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Kode Pos</label>
                            <input type="text" name="resident[zip_code]" value="{{ $profile->resident_zip_code ?? "" }}" placeholder="Masukan Kode Pos" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Negara</label>
                            <input type="text" name="resident[country]" value="{{ $profile->resident_country ?? "" }}" placeholder="Masukan Negara" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Provinsi</label>
                            <input type="text" name="resident[province]" value="{{ $profile->resident_province ?? "" }}" placeholder="Masukan Provinsi" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Kota</label>
                            <input type="text" name="resident[city]" value="{{ $profile->resident_city ?? "" }}" placeholder="Masukan Kota" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Nomor HP</label>
                            <input type="text" name="phone" value="{{ $personel->phone }}" placeholder="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Email</label>
                            <input type="text" name="personal_email" value="{{ $personel->email }}" placeholder="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">NPWP</label>
                            <input type="text" name="npwp" value="{{ $profile->npwp ?? "" }}" placeholder="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Unggah NPWP</label>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary btn-sm">
                                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_npwp" class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                @php
                                    $exp = explode("_", $profile->npwp_file ?? "");
                                    $fname = end($exp);
                                @endphp
                                <span class="text-primary ms-5" data-file>
                                    @if (!empty($fname))
                                        <a href="{{ asset($profile->npwp_file) }}">{{ $fname }}</a>
                                    @endif
                                </span>
                            </div>
                            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">BPJS Kesehatan</label>
                            <input type="text" name="bpjs_kes" value="{{ $profile->bpjskes ?? "" }}" placeholder="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Unggah BPJS Kesehatan</label>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary btn-sm">
                                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_bpjs_kes" class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                @php
                                    $exp = explode("_", $profile->bpjskes_file ?? "");
                                    $fname = end($exp);
                                @endphp
                                <span class="text-primary ms-5" data-file>
                                    @if (!empty($fname))
                                        <a href="{{ asset($profile->bpjskes_file) }}">{{ $fname }}</a>
                                    @endif
                                </span>
                            </div>
                            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">BPJS TK</label>
                            <input type="text" name="bpjs_tk" value="{{ $profile->bpjstk ?? "" }}" placeholder="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Unggah BPJS TK</label>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary btn-sm">
                                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_bpjs_tk" class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                @php
                                    $exp = explode("_", $profile->bpjstk_file ?? "");
                                    $fname = end($exp);
                                @endphp
                                <span class="text-primary ms-5" data-file>
                                    @if (!empty($fname))
                                        <a href="{{ asset($profile->bpjstk_file) }}">{{ $fname }}</a>
                                    @endif
                                </span>
                            </div>
                            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-5">
                        @csrf
                        <input type="hidden" name="id" value="{{ $personel->id }}">
                        <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_personal_private">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="my-5"></div>
<div class="accordion accordion-icon-collapse">
    <div class="accordion-item border-0 bg-secondary-crm">
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_personal_family">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-users fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Data Keluarga</h3>
                    <span>Data keluarga milik pegawai ini</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_personal_family" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <div class="d-flex flex-column gap-5">
                    @foreach ($families as $fam)
                        <div class="accordion accordion-icon-collapse">
                            <div class="accordion-item border-0">
                                <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_family_{{ $fam->id }}">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column">
                                            <h3 class="fs-4 fw-semibold mb-0">{{ $fam->name }}</h3>
                                        </div>
                                    </div>
                                    <span class="accordion-icon">
                                        <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                                        <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                                    </span>
                                </div>
                                <div id="kt_family_{{ $fam->id }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_personal_family">
                                    <div class="accordion-body">
                                        <form action="{{ route('personel.employee_table.family.store') }}" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Nama*</label>
                                                    <input type="text" name="name" required value="{{ $fam->name }}" class="form-control" placeholder="Masukan Nama">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Hubungan Keluarga*</label>
                                                    <select name="hubungan" required class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_family" data-placeholder="Pilih Hubungan Keluarga" required>
                                                        <option value=""></option>
                                                        <option {{ $fam->hubungan == "Orang Tua" ? "SELECTED" : "" }} value="Orang Tua">Orang Tua</option>
                                                        <option {{ $fam->hubungan == "Saudara" ? "SELECTED" : "" }} value="Saudara">Saudara</option>
                                                        <option {{ $fam->hubungan == "Suami" ? "SELECTED" : "" }} value="Suami">Suami</option>
                                                        <option {{ $fam->hubungan == "Istri" ? "SELECTED" : "" }} value="Istri">Istri</option>
                                                        <option {{ $fam->hubungan == "Anak" ? "SELECTED" : "" }} value="Anak">Anak</option>
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Status Pernikahan</label>
                                                    <select name="marital_id" class="form-select" data-control="select2" data-placeholder="Pilih Status Pernikahan" data-dropdown-parent="#kt_personal_family">
                                                        <option value=""></option>
                                                        @foreach ($marital_status as $item)
                                                            <option value="{{ $item->id }}" {{ $fam->marital_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Jenis Kelamin</label>
                                                    <select name="gender" class="form-select" data-control="select2" data-placeholder="Pilih Jenis Kelamin" data-dropdown-parent="#kt_personal_family">
                                                        <option value=""></option>
                                                        @foreach ($gender as $item)
                                                            <option value="{{ $item->id }}" {{ $fam->jenis_kelamin == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Tanggal Lahir</label>
                                                    <input type="date" name="tgl_lahir" value="{{ $fam->tgl_lahir }}" class="form-control">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Nomor Telepon*</label>
                                                    <input type="text" name="no_telp" required value="{{ $fam->no_telp }}" placeholder="Masukan Nomor Telepon" class="form-control">
                                                </div>
                                                <div class="fv-row col-12">
                                                    <label class="col-form-label">Unggah Document</label>
                                                    <div class="d-flex align-items-center">
                                                        <label class="btn btn-secondary btn-sm">
                                                            <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                                            Attachment
                                                            <i class="fi fi-rr-clip"></i>
                                                        </label>
                                                        @php
                                                            $fname = explode("_", $fam->lampiran)
                                                        @endphp
                                                        <span class="text-primary ms-5" data-file>{{ end($fname) }}</span>
                                                    </div>
                                                    <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                                                </div>
                                                <div class="fv-row col-12">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            Tambah sebagai kontak darurat
                                                            <input class="form-check-input" type="checkbox" value="1" name="emergency" {{ $fam->emergency_contact == 1 ? "CHECKED" : "" }} />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-5">
                                                    <div class="d-flex justify-content-between mt-5">
                                                        <a href="{{ route('personel.employee_table.family.delete', $fam->id) }}" class="text-danger">Hapus data keluarga</a>
                                                        <div>
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $fam->id }}">
                                                            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_family_{{ $fam->id }}">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <button type="button" data-toggle="form-add" class="btn text-primary">
                            <i class="fi fi-rr-add"></i>
                            Tambah anggota keluarga
                        </button>
                    </div>
                </div>
                <form action="{{ route('personel.employee_table.family.store') }}" method="post" enctype="multipart/form-data">
                    <div class="row d-none" data-form-add>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Nama*</label>
                            <input type="text" name="name" required value="" class="form-control" placeholder="Masukan Name">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Hubungan Keluarga*</label>
                            <select name="hubungan" required class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_family" data-placeholder="Pilih Relation" required>
                                <option value=""></option>
                                <option value="Orang Tua">Orang Tua</option>
                                <option value="Saudara">Saudara</option>
                                <option value="Suami">Suami</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Status Pernikahan</label>
                            <select name="marital_id" class="form-select" data-control="select2" data-placeholder="Pilih Status Pernikahan" data-dropdown-parent="#kt_personal_family">
                                <option value=""></option>
                                @foreach ($marital_status as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select" data-control="select2" data-placeholder="Pilih Jenis Kelamin" data-dropdown-parent="#kt_personal_family">
                                <option value=""></option>
                                @foreach ($gender as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Nomor Telepon*</label>
                            <input type="text" name="no_telp" required value="" placeholder="Masukan Nomor Telepon" class="form-control">
                        </div>
                        <div class="fv-row col-12">
                            <label class="col-form-label">Unggah Document</label>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary btn-sm">
                                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                <span class="text-primary ms-5" data-file></span>
                            </div>
                            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                        </div>
                        <div class="fv-row col-12">
                            <div class="form-check">
                                <label class="form-check-label">
                                    Tambah sebagai kontak darurat
                                    <input class="form-check-input" type="checkbox" name="emergency" value="1" />
                                </label>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="d-flex justify-content-end mt-5">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <button type="button" class="btn text-primary" data-toggle="form-cancel">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="my-5"></div>
<div class="accordion accordion-icon-collapse">
    <div class="accordion-item border-0 bg-secondary-crm">
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_personal_education">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-book-alt fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Pendidikan</h3>
                    <span>Riwayat pendidikan yang telah ditempuh</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_personal_education" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <div class="d-flex flex-column gap-5">
                    @foreach ($education as $fam)
                        <div class="accordion accordion-icon-collapse">
                            <div class="accordion-item border-0">
                                <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_education_{{ $fam->id }}">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column">
                                            <h3 class="fs-4 fw-semibold mb-0">{{ $fam->degree }}</h3>
                                        </div>
                                    </div>
                                    <span class="accordion-icon">
                                        <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                                        <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                                    </span>
                                </div>
                                <div id="kt_education_{{ $fam->id }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_personal_education">
                                    <div class="accordion-body">
                                        <form action="{{ route('personel.employee_table.education.store') }}" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Tingkat Pendidikan*</label>
                                                    <select name="degree" required class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_education" data-placeholder="Pilih Tingkat Pendidikan" required>
                                                        <option value=""></option>
                                                        @foreach ($degree as $item)
                                                            <option value="{{ $item->name }}" {{ $fam->degree == $item->name ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Jurusan</label>
                                                    <input type="text" name="field_of_study" value="{{ $fam->field_of_study }}" placeholder="Masukan Jurusan" class="form-control">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">IPK*</label>
                                                    <input type="number" min="0" max="4" required step=".01" name="grade" value="{{ $fam->grade }}" placeholder="Masukan IPK" class="form-control">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Nama Institusi*</label>
                                                    <input type="text" name="school_name" required placeholder="Masukan Nama Institusi" value="{{ $fam->school_name }}" class="form-control">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Tahun Kelulusan</label>
                                                    <input type="number" name="year_graduate" min="1000"  value="{{ $fam->year_graduate }}" placeholder="Masukan Tahun Kelulusan" class="form-control">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Unggah Document</label>
                                                    <div class="d-flex align-items-center">
                                                        <label class="btn btn-secondary btn-sm">
                                                            <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                                            Attachment
                                                            <i class="fi fi-rr-clip"></i>
                                                        </label>
                                                        @php
                                                            $fname = explode("_", $fam->lampiran)
                                                        @endphp
                                                        <span class="text-primary ms-5" data-file>{{ end($fname) }}</span>
                                                    </div>
                                                    <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                                                </div>
                                                <div class="col-12 mt-5">
                                                    <div class="d-flex justify-content-between mt-5">
                                                        <a href="{{ route('personel.employee_table.education.delete', $fam->id) }}" class="text-danger">Hapus data pendidikan</a>
                                                        <div>
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $fam->id }}">
                                                            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_education_{{ $fam->id }}">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <button type="button" data-toggle="form-add" class="btn text-primary">
                            <i class="fi fi-rr-add"></i>
                            Tambah Pendidikan
                        </button>
                    </div>
                </div>
                <form action="{{ route('personel.employee_table.education.store') }}" method="post" enctype="multipart/form-data">
                    <div class="row d-none" data-form-add>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tingkat Pendidikan*</label>
                            <select name="degree" required class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_education" data-placeholder="Pilih Tingkat Pendidikan" required>
                                <option value=""></option>
                                @foreach ($degree as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Jurusan</label>
                            <input type="text" name="field_of_study" value="" placeholder="Masukan Jurusan" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">IPK*</label>
                            <input type="number" min="0" max="4" required step=".01" name="grade" value="" placeholder="Masukan IPK" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Nama Institusi*</label>
                            <input type="text" name="school_name" required placeholder="Masukan Nama Institusi" value="" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tahun Kelulusan</label>
                            <input type="number" name="year_graduate" min="1000"  value="" placeholder="Masukan Tahun Kelulusan" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Unggah Document</label>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary btn-sm">
                                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                <span class="text-primary ms-5" data-file></span>
                            </div>
                            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="d-flex justify-content-end mt-5">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <button type="button" class="btn text-primary" data-toggle="form-cancel">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="my-5"></div>
<div class="accordion accordion-icon-collapse">
    <div class="accordion-item border-0 bg-secondary-crm">
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_personal_experience">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-briefcase fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Pengalaman Kerja</h3>
                    <span>Riwatay pengalaman kerja yang pernah ditempuh</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_personal_experience" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <div class="d-flex flex-column gap-5">
                    @foreach ($experience as $fam)
                        <div class="accordion accordion-icon-collapse">
                            <div class="accordion-item border-0">
                                <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_work_{{ $fam->id }}">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column">
                                            <h3 class="fs-4 fw-semibold mb-0">{{ $fam->company }}</h3>
                                        </div>
                                    </div>
                                    <span class="accordion-icon">
                                        <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                                        <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                                    </span>
                                </div>
                                <div id="kt_work_{{ $fam->id }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_personal_experience">
                                    <div class="accordion-body">
                                        <form action="{{ route('personel.employee_table.work.store') }}" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Nama Perusahaan*</label>
                                                    <input type="text" name="company" required value="{{ $fam->company }}" placeholder="Masukan Nama Perusahaan" class="form-control">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Bidang Usaha</label>
                                                    <select name="industry" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Bidang Usaha">
                                                        <option value=""></option>
                                                        @foreach ($master['industry'] as $key => $item)
                                                            <option value="{{ $key }}" {{ $key == $fam->industry ? "SELECTED" : "" }}>{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Gaji Terakhir</label>
                                                    <div class="position-relative">
                                                        <input type="text" name="salary" value="{{ $fam->salary }}" placeholder="0" class="form-control number ps-13">
                                                        <span class="position-absolute top-25 ms-5 mt-1">IDR</span>
                                                    </div>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Posisi Terakhir*</label>
                                                    <input type="text" required  name="position" value="{{ $fam->position }}" placeholder="Masukan Posisi Terakhir" class="form-control">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Status Kepegawaian</label>
                                                    <select name="job_type" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Status Kepegawaian">
                                                        <option value=""></option>
                                                        @foreach ($emp_status as $item)
                                                            <option value="{{ $item->id }}" {{ $item->id ==  $fam->job_type ? "SELECTED" : "" }}>{{ $item->label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6"></div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Tanggal Mulai*</label>
                                                    <select name="start_month" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Bulan Mulai">
                                                        <option value=""></option>
                                                        @foreach ($idFullMonth as $key => $item)
                                                            <option value="{{ sprintf("%02d", $key) }}" {{ date("m", strtotime($fam->start_date)) == sprintf("%02d", $key) ? "SELECTED" : "" }}>{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">&nbsp;</label>
                                                    <select name="start_year" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Tahun Mulai">
                                                        <option value=""></option>
                                                        @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                                                            <option value="{{ $i }}" {{ date("Y", strtotime($fam->start_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="fv-row col-12 mt-5">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            Saya sedang bekerja pada perusahaan ini
                                                            <input class="form-check-input" type="checkbox" value="1" {{ $fam->still == 1 ? "CHECKED" : "" }} name="still" data-toggle="still" />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="fv-row col-6 {{ $fam->still == 1 ? "d-none" : "" }}" data-target="still">
                                                    <label class="col-form-label">Tanggal Keluar*</label>
                                                    <select name="end_month" class="form-select" {{ $fam->still == 1 ? "" : "required" }} data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Bulan Keluar">
                                                        <option value=""></option>
                                                        @foreach ($idFullMonth as $key => $item)
                                                            <option value="{{ sprintf("%02d", $key) }}" {{ date("m", strtotime($fam->end_date)) == sprintf("%02d", $key) ? "SELECTED" : "" }}>{{ $item }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="fv-row col-6 {{ $fam->still == 1 ? "d-none" : "" }}" data-target="still">
                                                    <label class="col-form-label">&nbsp;</label>
                                                    <select name="end_year" class="form-select" {{ $fam->still == 1 ? "" : "required" }} data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Tahun Keluar">
                                                        <option value=""></option>
                                                        @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                                                            <option value="{{ $i }}" {{ date("Y", strtotime($fam->end_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="fv-row col-12">
                                                    <label class="col-form-label">Deskripsi*</label>
                                                    <textarea name="descriptions" class="form-control" id="" cols="30" rows="5" placeholder="Masukan Deskripsi">{{ $fam->descriptions }}</textarea>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Reference</label>
                                                    <input type="text" name="reference" class="form-control" placeholder="Masukan reference name" value="{{ $fam->reference }}">
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">&nbsp;</label>
                                                    <input type="text" name="phone" class="form-control" placeholder="Masukan reference number" value="{{ $fam->phone }}">
                                                </div>
                                                <div class="col-12 mt-5">
                                                    <div class="d-flex justify-content-between mt-5">
                                                        <a href="{{ route('personel.employee_table.work.delete', $fam->id) }}" class="text-danger">Hapus data pengalaman kerja</a>
                                                        <div>
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $fam->id }}">
                                                            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_work_{{ $fam->id }}">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <button type="button" data-toggle="form-add" class="btn text-primary">
                            <i class="fi fi-rr-add"></i>
                            Tambah Pengalaman Kerja
                        </button>
                    </div>
                </div>
                <form action="{{ route('personel.employee_table.work.store') }}" method="post" enctype="multipart/form-data">
                    <div class="row d-none" data-form-add>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Nama Perusahaan*</label>
                            <input type="text" name="company" required value="" placeholder="Masukan Nama Perusahaan" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Bidang Usaha</label>
                            <select name="industry" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Bidang Usaha">
                                <option value=""></option>
                                @foreach ($master['industry'] as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Gaji Terakhir</label>
                            <div class="position-relative">
                                <input type="text" name="salary" value="" placeholder="0" class="form-control number ps-13">
                                <span class="position-absolute top-25 ms-5 mt-1">IDR</span>
                            </div>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Posisi Terakhir*</label>
                            <input type="text" required  name="position" value="" placeholder="Masukan Posisi Terakhir" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Status Kepegawaian</label>
                            <select name="job_type" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Status Kepegawaian">
                                <option value=""></option>
                                @foreach ($emp_status as $item)
                                    <option value="{{ $item->id }}">{{ $item->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6"></div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tanggal Mulai*</label>
                            <select name="start_month" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Bulan Mulai">
                                <option value=""></option>
                                @foreach ($idFullMonth as $key => $item)
                                    <option value="{{ sprintf("%02d", $key) }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">&nbsp;</label>
                            <select name="start_year" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Tahun Mulai">
                                <option value=""></option>
                                @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="fv-row col-12 mt-5">
                            <div class="form-check">
                                <label class="form-check-label">
                                    I am currenty working in this job
                                    <input class="form-check-input" type="checkbox" value="1" name="still" data-toggle="still" />
                                </label>
                            </div>
                        </div>
                        <div class="fv-row col-6" data-target="still">
                            <label class="col-form-label">Tanggal Keluar*</label>
                            <select name="end_month" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Bulan Keluar">
                                <option value=""></option>
                                @foreach ($idFullMonth as $key => $item)
                                    <option value="{{ sprintf("%02d", $key) }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6" data-target="still">
                            <label class="col-form-label">&nbsp;</label>
                            <select name="end_year" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_personal_experience" data-placeholder="Pilih Tahun Keluar">
                                <option value=""></option>
                                @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="fv-row col-12">
                            <label class="col-form-label">Description*</label>
                            <textarea name="descriptions" class="form-control" id="" cols="30" rows="5" placeholder="Masukan descriptions"></textarea>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Reference</label>
                            <input type="text" name="reference" class="form-control" placeholder="Masukan reference name" id="">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">&nbsp;</label>
                            <input type="text" name="phone" class="form-control" placeholder="Masukan reference number" id="">
                        </div>
                        <div class="col-12 mt-5">
                            <div class="d-flex justify-content-end mt-5">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <button type="button" class="btn text-primary" data-toggle="form-cancel">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="my-5"></div>
<div class="accordion accordion-icon-collapse">
    <div class="accordion-item border-0 bg-secondary-crm">
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_personal_language">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-briefcase fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Kemampuan Bahasa</h3>
                    <span>Kemampuan bahasa yang dimiliki pegawai ini</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_personal_language" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <div class="d-flex flex-column gap-5">
                    @foreach ($language as $fam)
                        <div class="accordion accordion-icon-collapse">
                            <div class="accordion-item border-0">
                                <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_language_{{ $fam->id }}">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column">
                                            <h3 class="fs-4 fw-semibold mb-0">{{ $languages->where("id", $fam->language)->first()->name ?? "-" }}</h3>
                                        </div>
                                    </div>
                                    <span class="accordion-icon">
                                        <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                                        <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                                    </span>
                                </div>
                                <div id="kt_language_{{ $fam->id }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_personal_language">
                                    <div class="accordion-body">
                                        <form action="{{ route('personel.employee_table.language.store') }}" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="language" class="col-form-label">Bahasa*</label>
                                                            <select name="language" data-placeholder="Pilih Bahasa" data-control="select2" data-dropdown-parent="#kt_personal_language" data-hide-search="true" class="form-control" required>
                                                                <option value=""></option>
                                                                @foreach ($languages as $item)
                                                                    <option value="{{ $item->id }}" {{ $item->id == $fam->language ? "selected" : "" }}>{{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="writing" class="col-form-label">Kemampuan Menulis</label>
                                                            <select name="writing" data-placeholder="Pilih Kemampuan Menulis" data-control="select2" data-dropdown-parent="#kt_personal_language" data-hide-search="true" class="form-select">
                                                                <option value=""></option>
                                                                @for ($item = 1; $item <= 5; $item++)
                                                                    <option value="{{ $item }}" {{ $item == $fam->writing ? "selected" : "" }}>{{ $item }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="reading" class="col-form-label">Kemampuan Membaca</label>
                                                            <select name="reading" data-placeholder="Pilih Kemampuan Membaca" data-control="select2" data-dropdown-parent="#kt_personal_language" data-hide-search="true" class="form-select">
                                                                <option value=""></option>
                                                                @for ($item = 1; $item <= 5; $item++)
                                                                    <option value="{{ $item }}" {{ $item == $fam->reading ? "selected" : "" }}>{{ $item }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="speaking" class="col-form-label">Kemampuan Berbicara</label>
                                                            <select name="speaking" data-placeholder="Pilih Kemampuan Berbicara" data-control="select2" data-dropdown-parent="#kt_personal_language" data-hide-search="true" class="form-select">
                                                                <option value=""></option>
                                                                @for ($item = 1; $item <= 5; $item++)
                                                                    <option value="{{ $item }}" {{ $item == $fam->speaking ? "selected" : "" }}>{{ $item }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-5">
                                                    <div class="d-flex justify-content-between mt-5">
                                                        <a href="{{ route('personel.employee_table.language.delete', $fam->id) }}" class="text-danger">Hapus data kemampuan bahasa</a>
                                                        <div>
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $fam->id }}">
                                                            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_language_{{ $fam->id }}">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <button type="button" data-toggle="form-add" class="btn text-primary">
                            <i class="fi fi-rr-add"></i>
                            Tambah Kemampuan Bahasa
                        </button>
                    </div>
                </div>
                <form action="{{ route('personel.employee_table.language.store') }}" method="post" enctype="multipart/form-data">
                    <div class="row d-none" data-form-add>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="language" class="col-form-label">Bahasa*</label>
                                    <select name="language" data-placeholder="Pilih Bahasa" data-control="select2" data-dropdown-parent="#kt_personal_language" data-hide-search="true" class="form-control" required>
                                        <option value=""></option>
                                        @foreach ($languages as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="writing" class="col-form-label">Kemampuan Menulis</label>
                                    <select name="writing" data-placeholder="Pilih Kemampuan Menulis" data-control="select2" data-dropdown-parent="#kt_personal_language" data-hide-search="true" class="form-select">
                                        <option value=""></option>
                                        @for ($item = 1; $item <= 5; $item++)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="reading" class="col-form-label">Kemampuan Membaca</label>
                                    <select name="reading" data-placeholder="Pilih Kemampuan Membaca" data-control="select2" data-dropdown-parent="#kt_personal_language" data-hide-search="true" class="form-select">
                                        <option value=""></option>
                                        @for ($item = 1; $item <= 5; $item++)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label for="speaking" class="col-form-label">Kemampuan Berbicara</label>
                                    <select name="speaking" data-placeholder="Pilih Kemampuan Berbicara" data-control="select2" data-dropdown-parent="#kt_personal_language" data-hide-search="true" class="form-select">
                                        <option value=""></option>
                                        @for ($item = 1; $item <= 5; $item++)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="d-flex justify-content-end mt-5">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <button type="button" class="btn text-primary" data-toggle="form-cancel">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="my-5"></div>
<div class="accordion accordion-icon-collapse">
    <div class="accordion-item border-0 bg-secondary-crm">
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_personal_medical">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-doctor fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Rekam Medis</h3>
                    <span>Riwayat rekaman medis pegawai ini</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_personal_medical" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <div class="d-flex flex-column gap-5">
                    @foreach ($mcu as $fam)
                        <div class="accordion accordion-icon-collapse">
                            <div class="accordion-item border-0">
                                <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_mcu_{{ $fam->id }}">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column">
                                            <h3 class="fs-4 fw-semibold mb-0">{{ $fam->descriptions }}</h3>
                                        </div>
                                    </div>
                                    <span class="accordion-icon">
                                        <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                                        <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                                    </span>
                                </div>
                                <div id="kt_mcu_{{ $fam->id }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_personal_medical">
                                    <div class="accordion-body">
                                        <form action="{{ route('personel.employee_table.mcu.store') }}" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="fv-row col-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Penyakit</label>
                                                        <input type="text" name="descriptions" value="{{ $fam->descriptions }}" class="form-control" placeholder="Masukan Penyakit">
                                                    </div>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Tahun Kejadian</label>
                                                        <select name="year" data-placeholder="Pilih Tahun" data-control="select2" data-dropdown-parent="#kt_personal_medical" data-hide-search="true" class="form-select">
                                                            <option value=""></option>
                                                            @for ($item = date("Y") - 50; $item <= date("Y"); $item++)
                                                                <option value="{{ $item }}" {{ $fam->year == $item ? "SELECTED" : "" }}>{{ $item }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Unggah Document</label>
                                                    <div class="d-flex align-items-center">
                                                        <label class="btn btn-secondary btn-sm">
                                                            <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                                            Attachment
                                                            <i class="fi fi-rr-clip"></i>
                                                        </label>
                                                        @php
                                                            $fname = explode("_", $fam->lampiran)
                                                        @endphp
                                                        <span class="text-primary ms-5" data-file>{{ end($fname) }}</span>
                                                    </div>
                                                    <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                                                </div>
                                                <div class="col-12 mt-5">
                                                    <div class="d-flex justify-content-between mt-5">
                                                        <a href="{{ route('personel.employee_table.mcu.delete', $fam->id) }}" class="text-danger">Hapus data rekam medis</a>
                                                        <div>
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $fam->id }}">
                                                            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_mcu_{{ $fam->id }}">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <button type="button" data-toggle="form-add" class="btn text-primary">
                            <i class="fi fi-rr-add"></i>
                            Tambah Rekam Medis
                        </button>
                    </div>
                </div>
                <form action="{{ route('personel.employee_table.mcu.store') }}" method="post" enctype="multipart/form-data">
                    <div class="row d-none" data-form-add>
                        <div class="fv-row col-6">
                            <div class="form-group">
                                <label for="" class="col-form-label">Penyakit</label>
                                <input type="text" name="descriptions" class="form-control" placeholder="Masukan Penyakit">
                            </div>
                        </div>
                        <div class="fv-row col-6">
                            <div class="form-group">
                                <label for="" class="col-form-label">Tahun Kejadian</label>
                                <select name="year" data-placeholder="Pilih Tahun" data-control="select2" data-dropdown-parent="#kt_personal_medical" data-hide-search="true" class="form-select">
                                    <option value=""></option>
                                    @for ($item = date("Y") - 50; $item <= date("Y"); $item++)
                                        <option value="{{ $item }}" {{ date("Y") == $item ? "SELECTED" : "" }}>{{ $item }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Unggah Document</label>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary btn-sm">
                                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                <span class="text-primary ms-5" data-file></span>
                            </div>
                            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="d-flex justify-content-end mt-5">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <button type="button" class="btn text-primary" data-toggle="form-cancel">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="my-5"></div>
<div class="accordion accordion-icon-collapse">
    <div class="accordion-item border-0 bg-secondary-crm">
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_personal_license">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-folder fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Lisensi</h3>
                    <span>Lisensi atau sertifikat yang dimiliki pegawai ini</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_personal_license" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <div class="d-flex flex-column gap-5">
                    @foreach ($license as $fam)
                        <div class="accordion accordion-icon-collapse">
                            <div class="accordion-item border-0">
                                <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_license_{{ $fam->id }}">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column">
                                            <h3 class="fs-4 fw-semibold mb-0">{{ $fam->name }}</h3>
                                        </div>
                                    </div>
                                    <span class="accordion-icon">
                                        <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                                        <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                                    </span>
                                </div>
                                <div id="kt_license_{{ $fam->id }}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_personal_license">
                                    <div class="accordion-body">
                                        <form action="{{ route('personel.employee_table.license.store') }}" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="fv-row col-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Nama Lisensi</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $fam->name }}" placeholder="Masukan Nama Lisensi">
                                                    </div>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Nomor Lisensi</label>
                                                        <input type="text" name="number" class="form-control" value="{{ $fam->no_lisensi }}" placeholder="Masukan Nomor Lisensi">
                                                    </div>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <label class="col-form-label">Unggah Document</label>
                                                    <div class="d-flex align-items-center">
                                                        <label class="btn btn-secondary btn-sm">
                                                            <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                                            Attachment
                                                            <i class="fi fi-rr-clip"></i>
                                                        </label>
                                                        @php
                                                            $fname = explode("_", $fam->lampiran)
                                                        @endphp
                                                        <span class="text-primary ms-5" data-file>{{ end($fname) }}</span>
                                                    </div>
                                                    <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                                                </div>
                                                <div class="fv-row col-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-form-label">Tanggal Kedaluwarsa</label>
                                                        <input type="date" name="exp_date" value="{{ $fam->tgl_kadaluarsa }}" class="form-control" placeholder="Masukan Tanggal Kedaluwarsa">
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-5">
                                                    <div class="d-flex justify-content-between mt-5">
                                                        <a href="{{ route('personel.employee_table.license.delete', $fam->id) }}" class="text-danger">Hapus data lisensi</a>
                                                        <div>
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $fam->id }}">
                                                            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_license_{{ $fam->id }}">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <button type="button" data-toggle="form-add" class="btn text-primary">
                            <i class="fi fi-rr-add"></i>
                            Tambah Lisensi
                        </button>
                    </div>
                </div>
                <form action="{{ route('personel.employee_table.license.store') }}" method="post" enctype="multipart/form-data">
                    <div class="row d-none" data-form-add>
                        <div class="fv-row col-6">
                            <div class="form-group">
                                <label for="" class="col-form-label">Nama Lisensi</label>
                                <input type="text" name="name" class="form-control" placeholder="Masukan Nama Lisensi">
                            </div>
                        </div>
                        <div class="fv-row col-6">
                            <div class="form-group">
                                <label for="" class="col-form-label">Nomor Lisensi</label>
                                <input type="text" name="number" class="form-control" placeholder="Masukan Nomor Lisensi">
                            </div>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Unggah Document</label>
                            <div class="d-flex align-items-center">
                                <label class="btn btn-secondary btn-sm">
                                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                    Attachment
                                    <i class="fi fi-rr-clip"></i>
                                </label>
                                <span class="text-primary ms-5" data-file></span>
                            </div>
                            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                        </div>
                        <div class="fv-row col-6">
                            <div class="form-group">
                                <label for="" class="col-form-label">Tanggal Kedaluwarsa</label>
                                <input type="date" name="exp_date" class="form-control" placeholder="Masukan Tanggal Kedaluwarsa">
                            </div>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="d-flex justify-content-end mt-5">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <button type="button" class="btn text-primary" data-toggle="form-cancel">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Accordion-->
