<div class="d-flex gap-5 h-100">
    <div class="border border-bottom-0 pe-5 pt-0 border-left-0 border-top-0 rounded-0 min-w-300px w-300px p-3">
        <ul class="nav nav-tabs nav-pills border-0 flex-row flex-md-column w-100 fw-semibold">
            <li class="nav-item my-1 me-0">
                <a class="nav-link bg-hover-light-primary text-hover-primary text-dark text-active-primary py-3 active" data-bs-toggle="tab" href="#tab_info">Informasi Pribadi</a>
            </li>
            <li class="nav-item my-1 me-0">
                <a class="nav-link bg-hover-light-primary text-hover-primary text-dark text-active-primary py-3" data-bs-toggle="tab" href="#tab_address">Alamat</a>
            </li>
            <li class="nav-item my-1 me-0">
                <a class="nav-link bg-hover-light-primary text-hover-primary text-dark text-active-primary py-3" data-bs-toggle="tab" href="#tab_document">Dokumen</a>
            </li>
        </ul>
    </div>
    <div class="flex-fill">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_info" role="tabpanel">
                <div class="d-flex flex-column gap-5">
                    <form action="{{ route('ess.profile.update_private_data') }}" method="post" enctype="multipart/form-data">
                        <div class="d-flex rounded bg-secondary-crm gap-5 p-5">
                            <div class="d-flex flex-column w-300px" data-toggle="imageInput">
                                <div class="w-300px img-wrapper border h-200px rounded bgi-position-center bgi-no-repeat bgi-size-contain" style="background-image: url('{{ asset( $user->user_img ?? "images/image_placeholder.png") }}')"></div>
                                <span class="my-3 text-muted text-center">Ukuran Maksimum foto 5 MB</span>
                                <label class="btn btn-primary">
                                    Unggah Foto
                                    <input type="file" name="image" class="d-none">
                                </label>
                            </div>
                            <div class="row bg-secondary-crm rounded p-5">
                                <div class="fv-row col-6">
                                    <label class="col-form-label required">Nomor ID</label>
                                    <input type="text" name="emp_id" value="{{ $personel->emp_id }}" disabled class="form-control" placeholder="Masukan Nomor ID">
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label required">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ $personel->emp_name }}" disabled class="form-control" placeholder="Masukan Nama Lengkap">
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label required">Nomor HP</label>
                                    <input type="text" name="phone" value="{{ $personel->phone ?? "" }}" class="form-control" placeholder="Masukan Nomor HP">
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label required">Alamat Email</label>
                                    <input type="text" name="email" value="{{ $personel->email ?? "" }}" disabled class="form-control" placeholder="Masukan Alamat Email">
                                </div>
                                <div class="col-12 separator separator-solid px-5 my-5"></div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label">Tempat Lahir</label>
                                    <input type="text" name="emp_tmpt_lahir" value="{{ $personel->emp_tmpt_lahir ?? "" }}" class="form-control" placeholder="Masukan Tempat Lahir">
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label">Tanggal Lahir</label>
                                    <input type="text" name="emp_lahir" value="{{ empty($personel->emp_lahir) ? "" : date("d/m/Y", strtotime($personel->emp_lahir)) }}" class="form-control flatpicker" placeholder="Masukan Tanggal Lahir">
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label">Kewarganegaraan</label>
                                    <input type="text" name="citizenship" value="{{ $profile->citizenship ?? "" }}" class="form-control" placeholder="Masukan Kewarganegaraan">
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label">Status Pernikahaan</label>
                                    <select name="marital_status" class="form-select" data-control="select2" data-placeholder="Pilih Status Pernikahaan">
                                        <option value=""></option>
                                        @foreach ($marital_status as $item)
                                            <option value="{{ $item->id }}" {{ ($profile->marital_status ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label">Agama</label>
                                    <select name="religion" class="form-select" data-control="select2" data-placeholder="Pilih Agama">
                                        <option value=""></option>
                                        @foreach ($religion as $item)
                                            <option value="{{ $item->id }}" {{ ($profile->religion ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select" data-control="select2" data-placeholder="Pilih Jenis Kelamin">
                                        <option value=""></option>
                                        @foreach ($gender as $item)
                                            <option value="{{ $item->id }}" {{ ($profile->gender ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label">Golongan Darah</label>
                                    <select name="blood_type" class="form-select" data-control="select2" data-placeholder="Pilih Golongan Darah">
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
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            @csrf
                            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                            <input type="hidden" name="post_type" value="personal_info">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Simpan
                            </button>
                        </div>
                    </form>
                    {{-- <form action="{{ route('ess.profile.update_private_data') }}" method="post" enctype="multipart/form-data">
                        <div class="d-flex flex-column gap-5">
                            
                            <div class="d-flex justify-content-end">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <input type="hidden" name="post_type" value="personel_detail">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form> --}}
                </div>
            </div>
            <div class="tab-pane fade" id="tab_address" role="tabpanel">
                <div class="d-flex flex-column gap-5">
                    <form action="{{ route("ess.profile.update_private_data") }}" method="post">
                        <div class="d-flex flex-column gap-3">
                            <h3>Alamat KTP</h3>
                            <div class="rounded p-5 bg-secondary-crm">
                                <div class="row">
                                    <div class="fv-row col-12">
                                        <label class="col-form-label">Alamat Lengkap</label>
                                        <textarea name="identity[address]" id="" cols="30" class="form-control" rows="5">{{ $profile->identity_address ?? "" }}</textarea>
                                    </div>
                                    <div class="fv-row col-3">
                                        <label class="col-form-label">Koda Pos</label>
                                        <input type="text" name="identity[zip_code]" value="{{ $profile->identity_zip_code ?? "" }}" placeholder="Masukan Koda Pos" class="form-control">
                                    </div>
                                    <div class="fv-row col-3">
                                        <label class="col-form-label">Negara</label>
                                        <input type="text" name="identity[country]" value="{{ $profile->identity_country ?? "" }}" placeholder="Masukan Negara" class="form-control">
                                    </div>
                                    <div class="fv-row col-3">
                                        <label class="col-form-label">Provinsi</label>
                                        <input type="text" name="identity[province]" value="{{ $profile->identity_province ?? "" }}" placeholder="Masukan Provinsi" class="form-control">
                                    </div>
                                    <div class="fv-row col-3">
                                        <label class="col-form-label">Kota</label>
                                        <input type="text" name="identity[city]" value="{{ $profile->identity_city ?? "" }}" placeholder="Masukan Kota" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <input type="hidden" name="post_type" value="identity_address">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="fv-row">
                        <div class="form-check col-form-label">
                            <span class="form-check-label">
                                Alamat tempat tinggal sama dengan alamat KTP
                                <input class="form-check-input" name="resident_identity" type="checkbox" value="1" id="" />
                            </span>
                        </div>
                    </div>
                    <form action="{{ route("ess.profile.update_private_data") }}" method="post">
                        <div class="d-flex flex-column gap-3">
                            <h3>Alamat Tempat Tinggal</h3>
                            <div class="rounded p-5 bg-secondary-crm">
                                <div class="row">
                                    <div class="fv-row col-12">
                                        <label class="col-form-label">Alamat Lengkap</label>
                                        <textarea name="resident[address]" id="" cols="30" class="form-control" rows="5">{{ $profile->resident_address ?? "" }}</textarea>
                                    </div>
                                    <div class="fv-row col-6">
                                        <label class="col-form-label">Koda Pos</label>
                                        <input type="text" name="resident[zip_code]" value="{{ $profile->resident_zip_code ?? "" }}" placeholder="Masukan Koda Pos" class="form-control">
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
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                @csrf
                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                <input type="hidden" name="post_type" value="resident_address">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_document" role="tabpanel">
                <form action="{{ route('ess.profile.update_private_data') }}" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column gap-3">
                        <div class="row row-gap-5 bg-secondary-crm rounded p-5">
                            <div class="fv-row col-6">
                                <label class="col-form-label">KTP</label>
                                <input type="text" name="identity_number" value="{{ $profile->identity_number ?? "" }}" placeholder="Masukan KTP" class="form-control">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Unggah KTP</label>
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-secondary btn-sm">
                                        <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="file" class="d-none">
                                        <span>Attachment</span>
                                        <i class="fi fi-rr-clip"></i>
                                    </label>
                                    @php
                                        $exp = explode("_identity_", $profile->identity_file ?? "");
                                        $fname = end($exp);
                                    @endphp
                                    @if (!empty($profile->identity_file ?? null))
                                        <a href="{{ asset($profile->identity_file) }}" class="text-primary ms-5" data-file>{{ $fname }}</a>
                                    @endif
                                </div>
                                <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">KITAS</label>
                                <input type="text" name="kitas" value="{{ $profile->kitas ?? "" }}" placeholder="Masukan KITAS" class="form-control">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Unggah KITAS</label>
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-secondary btn-sm">
                                        <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_kitas" class="d-none">
                                        <span>Attachment</span>
                                        <i class="fi fi-rr-clip"></i>
                                    </label>
                                    @php
                                        $exp = explode("_bpjstk_", $profile->kitas_file ?? "");
                                        $fname = end($exp);
                                    @endphp
                                    @if (!empty($profile->kitas_file ?? null))
                                        <a href="{{ asset($profile->kitas_file) }}" class="text-primary ms-5" data-file>{{ $fname }}</a>
                                    @endif
                                </div>
                                <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">NPWP</label>
                                <input type="text" name="npwp" value="{{ $profile->npwp ?? "" }}" placeholder="Masukan NPWP" class="form-control">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Unggah NPWP</label>
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-secondary btn-sm">
                                        <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_npwp" class="d-none">
                                        <span>Attachment</span>
                                        <i class="fi fi-rr-clip"></i>
                                    </label>
                                    @php
                                        $exp = explode("_npwp_", $profile->npwp_file ?? "");
                                        $fname = end($exp);
                                    @endphp
                                    @if (!empty($profile->npwp_file ?? null))
                                        <a href="{{ asset($profile->npwp_file) }}" class="text-primary ms-5" data-file>{{ $fname }}</a>
                                    @endif
                                </div>
                                <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">BPJS Kesehatan</label>
                                <input type="text" name="bpjs_kes" value="{{ $profile->bpjskes ?? "" }}" placeholder="Masukan BPJS Kesehatan" class="form-control">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Unggah BPJS Kesehatan</label>
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-secondary btn-sm">
                                        <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_bpjs_kes" class="d-none">
                                        <span>Attachment</span>
                                        <i class="fi fi-rr-clip"></i>
                                    </label>
                                    @php
                                        $exp = explode("_bpjskes_", $profile->bpjskes_file ?? "");
                                        $fname = end($exp);
                                    @endphp
                                    @if (!empty($profile->bpjskes_file ?? null))
                                        <a href="{{ asset($profile->bpjskes_file) }}" class="text-primary ms-5" data-file>{{ $fname }}</a>
                                    @endif
                                </div>
                                <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">BPJS TK</label>
                                <input type="text" name="bpjs_tk" value="{{ $profile->bpjstk ?? "" }}" placeholder="Masukan BPJS TK" class="form-control">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Unggah BPJS TK</label>
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-secondary btn-sm">
                                        <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_bpjs_tk" class="d-none">
                                        <span>Attachment</span>
                                        <i class="fi fi-rr-clip"></i>
                                    </label>
                                    @php
                                        $exp = explode("_bpjstk_", $profile->bpjstk_file ?? "");
                                        $fname = end($exp);
                                    @endphp
                                    @if (!empty($profile->bpjstk_file ?? null))
                                        <a href="{{ asset($profile->bpjstk_file) }}" class="text-primary ms-5" data-file>{{ $fname }}</a>
                                    @endif
                                </div>
                                <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            @csrf
                            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                            <input type="hidden" name="post_type" value="document">
                            <button type="submit" class="btn btn-sm btn-primary">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
