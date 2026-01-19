<!--begin::Accordion-->
<div class="accordion accordion-icon-collapse">
    <div class="accordion-item border-0 bg-secondary-crm">
        <div class="accordion-header py-3 px-5 d-flex justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_primary_mandatory">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-user fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Data Wajib</h3>
                    <span>Data pekerja untuk masuk ke {{ \Config::get("constants.APP_LABEL") }}</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_primary_mandatory" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <form action="{{ route('personel.employee_table.update_data') }}" method="post">
                    <div class="row">
                        <div class="fv-row col-6">
                            <label class="col-form-label">ID Pekerja</label>
                            <input type="text" name="emp_id" value="{{ $personel->emp_id }}" class="form-control" disabled>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Nama Perusahaan</label>
                            <input type="text" name="comp" value="{{ $personel->comp->company_name ?? "" }}" class="form-control" disabled>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Nama</label>
                            <input type="text" name="emp_name" value="{{ $personel->emp_name }}" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Email Kantor</label>
                            <input type="text" name="email" value="{{ $personel->user->email ?? $personel->email }}" class="form-control">
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Posisi</label>
                            <select name="position" class="form-select" data-control="select2" data-dropdown-parent="#kt_primary_mandatory" data-placeholder="Pilih Posisi">
                                <option value=""></option>
                                @foreach ($position as $item)
                                    <option value="{{ $item->id }}" {{ $personel->position_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Posisi Sementara</label>
                            <select name="acting_position_id" class="form-select" data-control="select2" data-dropdown-parent="#kt_primary_mandatory" data-placeholder="Pilih Posisi Sementara">
                                <option value=""></option>
                                @foreach ($position as $item)
                                    <option value="{{ $item->id }}" {{ $personel->acting_position_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Departemen</label>
                            <select name="departement" class="form-select" data-control="select2" data-dropdown-parent="#kt_primary_mandatory" data-placeholder="Pilih Departemen">
                                <option value=""></option>
                                @foreach ($departements as $item)
                                    <option value="{{ $item->id }}" {{ ($personel->user->uac_departement ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tingkat kerja</label>
                            <select name="job_level" class="form-select" data-control="select2" data-dropdown-parent="#kt_primary_mandatory" data-placeholder="Pilih Tingkat kerja">
                                <option value=""></option>
                                @foreach ($master['job_level'] as $id => $item)
                                    <option value="{{ $id }}" {{ $id == $personel->job_level_id ? "SELECTED" : "" }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Lokasi</label>
                            <select name="location" class="form-select" data-control="select2" data-dropdown-parent="#kt_primary_mandatory" data-placeholder="Pilih Lokasi" id="">
                                <option value=""></option>
                                @foreach ($loc as $item)
                                    <option value="{{ $item->id }}" {{ ($personel->user->uac_location ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tanggal Bergabung</label>
                            <input type="text" name="join_date" value="{{ date("d/m/Y", strtotime($personel->join_date ?? $personel->created_at)) }}" placeholder="" class="form-control flatpicker">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-5">
                        @csrf
                        <input type="hidden" name="type" value="mandatory">
                        <input type="hidden" name="id" value="{{ $personel->id }}">
                        <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_primary_mandatory">Batal</button>
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
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_primary_attendance">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-time-quarter-past fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Pendaftaran Kehadiran</h3>
                    <span>Daftarkan data untuk kehadiran</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_primary_attendance" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <form action="{{ route('personel.employee_table.update_data') }}" method="post">
                    <div class="row">
                        <div class="fv-row col-6">
                            <label class="col-form-label">ID Barcode</label>
                            <input type="text" name="card_id" value="{{ $reg->id_card ?? ($personel->temp_id_card ?? "-") }}" class="form-control">
                            <span>ID yang digunakan pada mesin absensi</span>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Grup Kerja</label>
                            <select name="workgroup" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail" disabled data-placeholder="Grup Kerja" id="">
                                <option value=""></option>
                                <option value="{{ $reg->wg->id ?? "" }}">{{ $reg->wg->workgroup_name ?? "" }}</option>
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Grup Cuti</label>
                            <select name="leavegroup" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail" disabled data-placeholder="Grup Cuti" id="">
                                <option value=""></option>
                                <option value="{{ $reg->leavegroup ?? "" }}">{{ $reg->leave->leave_group_name ?? "" }}</option>
                            </select>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label">Tanggal Bergabung Grup</label>
                            <input type="text" name="email" value="{{ !empty($reg) ? date("d-m-Y", strtotime($reg->date1)) : "" }}" disabled class="form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-5">
                        @csrf
                        <input type="hidden" name="type" value="attendance">
                        <input type="hidden" name="id" value="{{ $personel->id }}">
                        <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_primary_attendance">Batal</button>
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
        <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_primary_role">
            <div class="d-flex">
                <div class="symbol symbol-40px me-5">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-rr-document fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="fs-4 fw-semibold mb-0">Peran</h3>
                    <span>Peran untuk pekerja</span>
                </div>
            </div>
            <span class="accordion-icon">
                <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
            </span>
        </div>
        <div id="kt_primary_role" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#tab_general_primary">
            <div class="accordion-body">
                <form action="{{ route('personel.employee_table.update_data') }}" method="post">
                    <div class="row">
                        <div class="fv-row col-12">
                            <label class="col-form-label">Pilih Peran</label>
                            <select name="role" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail" data-placeholder="Pilih Peran" id="">
                                <option value=""></option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}" {{ ($personel->user->uac_role ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-5">
                        @csrf
                        <input type="hidden" name="type" value="roles">
                        <input type="hidden" name="id" value="{{ $personel->id }}">
                        <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_primary_role">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::Accordion-->
