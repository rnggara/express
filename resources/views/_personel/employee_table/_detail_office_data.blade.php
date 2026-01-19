<div class="d-flex flex-column gap-5">
    <div class="accordion accordion-icon-collapse">
        <div class="accordion-item border-0 bg-secondary-crm">
            <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_office_data">
                <div class="d-flex">
                    <div class="symbol symbol-40px me-5">
                        <div class="symbol-label bg-light-primary">
                            <i class="fi fi-rr-briefcase fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="fs-4 fw-semibold mb-0">Data Perusahaan</h3>
                        <span>Employee office data</span>
                    </div>
                </div>
                <span class="accordion-icon">
                    <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                    <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                </span>
            </div>
            <div id="kt_office_data" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#tab_general_primary">
                <div class="accordion-body">
                    <form action="{{ route('personel.employee_table.update_data') }}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="fv-row col-12">
                                <label class="col-form-label">Posisi</label>
                                <select name="position" class="form-select" data-control="select2" data-dropdown-parent="#tab_general_office" data-placeholder="Pilih Posisi" id="">
                                    <option value=""></option>
                                    @foreach ($position as $item)
                                        <option value="{{ $item->id }}" {{ $personel->position_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Status Kepegawaian</label>
                                <select name="employee_status" class="form-select" data-control="select2" data-dropdown-parent="#tab_general_office" data-placeholder="Pilih Status Kepegawaian" id="">
                                    <option value=""></option>
                                    @foreach ($emp_status as $item)
                                        <option value="{{ $item->id }}" data-end-date="{{ $item->end_date }}" {{ $personel->employee_status_id == $item->id ? "SELECTED" : "" }}>{{ $item->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6 {{ ($_estat->end_date ?? 0) == 1 ? "" : "d-none" }}">
                                <label class="col-form-label">Tanggal Berakhir</label>
                                <input type="text" name="emp_status_end_date" value="{{ date("d/m/Y", strtotime($personel->employee_status_mutation_end)) }}" class="form-control flatpicker">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Tingkat</label>
                                <select name="job_grade" class="form-select" data-control="select2" data-dropdown-parent="#tab_general_office" data-placeholder="Pilih Tingkat" id="">
                                    <option value=""></option>
                                    @foreach ($master['job_type'] as $id => $name)
                                        <option value="{{ $id }}" {{ $personel->job_grade_id == $id ? "SELECTED" : "" }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Kelas</label>
                                <select name="class" class="form-select" data-control="select2" data-dropdown-parent="#tab_general_office" data-placeholder="Pilih Kelas" id="">
                                    <option value=""></option>
                                    @foreach ($master['class'] as $id => $name)
                                        <option value="{{ $id }}" {{ $personel->class_id == $id ? "SELECTED" : "" }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Lokasi Kantor</label>
                                <select name="location" class="form-select" data-control="select2" data-dropdown-parent="#tab_general_office" data-placeholder="Pilih Lokasi Kantor" id="">
                                    <option value=""></option>
                                    @foreach ($loc as $item)
                                        <option value="{{ $item->id }}" {{ ($personel->user->uac_location ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-12">
                                <label class="col-form-label">Unggah Document</label>
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-secondary btn-sm">
                                        <input type="file" data-toggle='file' accept=".jpg, .pdf, .png" name="file" class="d-none">
                                        Attachment
                                        <i class="fi fi-rr-clip"></i>
                                    </label>
                                    <span class="text-primary ms-5" data-file>
                                        @if (!empty($personel->office_file))
                                            @php
                                                $fname = explode("_", $personel->office_file);
                                            @endphp
                                            <a href="{{ asset($personel->office_file) }}">{{ end($fname) }}</a>
                                        @endif
                                    </span>
                                </div>
                                <span class="text-muted mt-3">File Format : JPG, PDF, PNG Max 25 mb</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-5">
                            @csrf
                            <input type="hidden" name="type" value="office">
                            <input type="hidden" name="id" value="{{ $personel->id }}">
                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_office_data">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion accordion-icon-collapse">
        <div class="accordion-item border-0 bg-secondary-crm">
            <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_asset_data">
                <div class="d-flex">
                    <div class="symbol symbol-40px me-5">
                        <div class="symbol-label bg-light-primary">
                            <i class="fi fi-rr-briefcase fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="fs-4 fw-semibold mb-0">Asset Data</h3>
                        <span>Employee asset data</span>
                    </div>
                </div>
                <span class="accordion-icon">
                    <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                    <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                </span>
            </div>
            <div id="kt_asset_data" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#tab_general_primary">
                <div class="accordion-body">
                    <div class="d-flex flex-column">
                        <div class="card shadow-none bg-transparent mb-5" id="kt_per_ass">
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <div class="d-flex flex-column">
                                        <h3>Asset Pribadi</h3>
                                        <span class="fs-base fw-normal">Input personal asset that no need to return</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-5">
                                    @php
                                        $aname = $assets->pluck("name", "id");
                                    @endphp
                                    @if ($emp_asset->where("type", "personal")->count() > 0)
                                        <div class="accordion accordion-icon-collapse" id="kt_per_asset">
                                            @foreach ($emp_asset->where("type", "personal") as $epp)
                                                <div class="bg-white border mb-5 px-5 rounded">
                                                    <div class="accordion-header py-3 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_per_asset_{{ $epp->id }}">
                                                        <h3 class="fs-4 fw-semibold mb-0">{{ $aname[$epp->asset_id] }}</h3>
                                                        <span class="accordion-icon">
                                                            <i class="fi fi-rr-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                            <i class="fi fi-rr-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <div id="kt_per_asset_{{ $epp->id }}" class="collapse" data-bs-parent="#kt_per_asset">
                                                        <form action="{{ route("personel.employee_table.asset.store") }}" method="post">
                                                            <div class="d-flex flex-column gap-5 mb-5" data-form-clone>
                                                                <div class="row" data-form-add>
                                                                    <div class="fv-row col-12">
                                                                        <label class="col-form-label">Asset</label>
                                                                        <select name="asset" data-label class="form-select" data-control="select2" data-placeholder="Pilih Asset" required>
                                                                            <option value=""></option>
                                                                            @foreach ($assets ?? [] as $item)
                                                                                <option value="{{ $item->id }}" {{ $epp->asset_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="fv-row col-6">
                                                                        <label class="col-form-label">Description</label>
                                                                        <input type="text" name="description" class="form-control" value="{{ $epp->descriptions }}"  placeholder="Input Description" id="" required>
                                                                    </div>
                                                                    <div class="fv-row col-6">
                                                                        <label class="col-form-label">Date Received</label>
                                                                        <input type="date" name="date" class="form-control" value="{{ $epp->date_received }}" placeholder="Input Date" id="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end w-100">
                                                                    @csrf
                                                                    <input type="hidden" name="type" value="personal">
                                                                    <input type="hidden" name="id" value="{{ $epp->id }}">
                                                                    <input type="hidden" name="emp_id" value="{{ $personel->id }}">
                                                                    <button type="button" data-bs-toggle="collapse" data-bs-target="#kt_per_asset_{{ $epp->id }}" class="btn text-primary">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="accordion accordion-icon-collapse">
                                        <div class="accordion-item border-0 bg-secondary-crm">
                                            <div id="kt_personal_asset" class="accordion-collapse collapse" data-bs-parent="#kt_per_ass">
                                                <div class="accordion-body">
                                                    <form action="{{ route("personel.employee_table.asset.store") }}" method="POST">
                                                        <div class="d-flex flex-column align-items-center" data-form-clone>
                                                            <div class="card bg-transparent mb-5 shadow-none w-100" data-clone>
                                                                <div class="card-body rounded bg-white p-5 border">
                                                                    <div class="d-flex justify-content-between align-items-center d-none" data-head>
                                                                        <span class="fs-3 fw-bold">Nama</span>
                                                                        <button type="button" class="btn btn-icon" onclick="accrd(this)">
                                                                            <i class="fi fi-rr-caret-down" data-accr="expand"></i>
                                                                            <i class="fi fi-rr-caret-up d-none" data-accr="collapse"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="row" data-form-add>
                                                                        <div class="fv-row col-12">
                                                                            <label class="col-form-label">Asset</label>
                                                                            <select name="asset" data-label class="form-select" data-control="select2" data-placeholder="Pilih Asset" required>
                                                                                <option value=""></option>
                                                                                @foreach ($assets ?? [] as $item)
                                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="fv-row col-6">
                                                                            <label class="col-form-label">Description</label>
                                                                            <input type="text" name="description" class="form-control" placeholder="Input Description" id="" required>
                                                                        </div>
                                                                        <div class="fv-row col-6">
                                                                            <label class="col-form-label">Date Received</label>
                                                                            <input type="date" name="date" class="form-control" placeholder="Input Date" id="" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer border-0">
                                                                    <div class="d-flex justify-content-end">
                                                                        @csrf
                                                                        <input type="hidden" name="type" value="personal">
                                                                        <input type="hidden" name="emp_id" value="{{ $personel->id }}">
                                                                        <button type="button" data-bs-toggle="collapse" data-bs-target="#kt_personal_asset" onclick="collapseDisabled(this)" class="btn text-primary">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <button type="button" class="btn text-primary" onclick="collapseDisabled(this)" data-disabled data-bs-toggle="collapse" data-bs-target="#kt_personal_asset">
                                                <i class="fi fi-rr-add"></i>
                                                Tambah Asset Pribadi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-none bg-transparent" id="kt_comp_ass">
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <div class="d-flex flex-column">
                                        <h3>Asset Perusahaan</h3>
                                        <span class="fs-base fw-normal">Input company asset that need to return when employee resign</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-5">
                                    @if ($emp_asset->where("type", "company")->count() > 0)
                                        <div class="accordion accordion-icon-collapse" id="kt_per_asset">
                                            @foreach ($emp_asset->where("type", "company") as $epp)
                                                <div class="bg-white border mb-5 px-5 rounded">
                                                    <div class="accordion-header py-3 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_per_asset_{{ $epp->id }}">
                                                        <h3 class="fs-4 fw-semibold mb-0">{{ $aname[$epp->asset_id] }}</h3>
                                                        <span class="accordion-icon">
                                                            <i class="fi fi-rr-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                            <i class="fi fi-rr-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    <div id="kt_per_asset_{{ $epp->id }}" class="collapse" data-bs-parent="#kt_per_asset">
                                                        <form action="{{ route("personel.employee_table.asset.store") }}" method="post">
                                                            <div class="d-flex flex-column gap-5 mb-5" data-form-clone>
                                                                <div class="row" data-form-add>
                                                                    <div class="fv-row col-6">
                                                                        <label class="col-form-label">Asset</label>
                                                                        <select name="asset" data-label class="form-select" data-control="select2" data-placeholder="Pilih Asset" required>
                                                                            <option value=""></option>
                                                                            @foreach ($assets ?? [] as $item)
                                                                                <option value="{{ $item->id }}" {{ $epp->asset_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="fv-row col-6">
                                                                        <label class="col-form-label">Serial Number</label>
                                                                        <input type="text" name="serial_num" class="form-control" value="{{ $epp->serial_num }}" placeholder="Input Serial Number" id="">
                                                                    </div>
                                                                    <div class="fv-row col-6">
                                                                        <label class="col-form-label">Description</label>
                                                                        <input type="text" name="description" class="form-control" value="{{ $epp->descriptions }}"  placeholder="Input Description" id="" required>
                                                                    </div>
                                                                    <div class="fv-row col-6">
                                                                        <label class="col-form-label">Date Received</label>
                                                                        <input type="date" name="date" class="form-control" value="{{ $epp->date_received }}" placeholder="Input Date" id="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-end w-100">
                                                                    @csrf
                                                                    <input type="hidden" name="type" value="personal">
                                                                    <input type="hidden" name="id" value="{{ $epp->id }}">
                                                                    <input type="hidden" name="emp_id" value="{{ $personel->id }}">
                                                                    <button type="button" data-bs-toggle="collapse" data-bs-target="#kt_per_asset_{{ $epp->id }}" class="btn text-primary">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="accordion accordion-icon-collapse">
                                        <div class="accordion-item border-0 bg-secondary-crm">
                                            <div id="kt_company_asset" class="accordion-collapse collapse" data-bs-parent="#kt_comp_ass">
                                                <div class="accordion-body">
                                                    <form action="{{ route("personel.employee_table.asset.store") }}" method="POST">
                                                        <div class="d-flex flex-column align-items-center" data-form-clone>
                                                            <div class="card bg-transparent mb-5 shadow-none w-100" data-clone>
                                                                <div class="card-body rounded bg-white p-5 border">
                                                                    <div class="row" data-form-add>
                                                                        <div class="fv-row col-6">
                                                                            <label class="col-form-label">Asset</label>
                                                                            <select name="asset" class="form-select" data-control="select2" data-placeholder="Pilih Workgroup">
                                                                                <option value=""></option>
                                                                                @foreach ($assets ?? [] as $item)
                                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="fv-row col-6">
                                                                            <label class="col-form-label">Serial Number</label>
                                                                            <input type="text" name="serial_num" class="form-control" placeholder="Input Serial Number" id="">
                                                                        </div>
                                                                        <div class="fv-row col-6">
                                                                            <label class="col-form-label">Description</label>
                                                                            <input type="text" name="description" class="form-control" placeholder="Input Description" id="">
                                                                        </div>
                                                                        <div class="fv-row col-6">
                                                                            <label class="col-form-label">Date Received</label>
                                                                            <input type="date" name="date" class="form-control" placeholder="Input Date" id="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-footer border-0">
                                                                    <div class="d-flex justify-content-end">
                                                                        @csrf
                                                                        <input type="hidden" name="type" value="company">
                                                                        <input type="hidden" name="emp_id" value="{{ $personel->id }}">
                                                                        <button type="button" data-bs-toggle="collapse" data-bs-target="#kt_company_asset" onclick="collapseDisabled(this)" class="btn text-primary">Batal</button>
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column align-items-center">
                                            <button type="button" class="btn text-primary" onclick="collapseDisabled(this)" data-disabled data-bs-toggle="collapse" data-bs-target="#kt_company_asset">
                                                <i class="fi fi-rr-add"></i>
                                                Tambah Asset Perusahaan
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

