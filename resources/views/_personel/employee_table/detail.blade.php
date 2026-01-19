<div class='card-body'>
    <div class="d-flex flex-column">
        <div class="d-flex align-items-center mb-10">
            <div class="symbol symbol-100px me-5">
                <div class="symbol-label" style="background-image: url('{{ asset($personel->user->user_img ?? "images/image_placeholder.png") }}')">
                </div>
            </div>
            <div class="d-flex justify-content-between w-100 align-items-baseline">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fs-3 fw-bold me-5">{{ $personel->emp_name }}</span>
                        <span class="badge badge-{{ empty($personel->expire) ? "success" : "danger" }}">{{ empty($personel->expire) ? "Aktif" : "Resign" }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span>{{ $personel->emp_id }} - {{ $personel->job_level->name ?? "" }} {{ $personel->user->uac_role->name ?? "" }}</span>
                    </div>
                    @if (!empty($personel->expire))
                        <form action="{{ route('personel.employee_table.reactive') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $personel->id }}">
                            <button type="submit" class="d-none"></button>
                            <button type="button" name="reactive" class="btn btn-outline btn-outline-primary btn-sm">Aktifkan kembali karyawan</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-outline btn-outline-primary btn-sm">Atur ulang kata sandi</button>
                    @endif
                    {{-- <span class="text-muted fs-base">{{ $leave->leave_group_id }}</span> --}}
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-primary btn-sm me-5">
                        <i class="fi fi-rr-envelope"></i>
                        Kirim Email
                    </button>
                    <button type="button" class="btn btn-secondary btn-icon btn-sm">
                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                    </button>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 pt-5">
            <li class="nav-item">
                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_detail_general">
                    <span class="nav-text">Umum</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_detail_emergency">
                    <span class="nav-text">Kontak Darurat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_detail_document">
                    <span class="nav-text">Dokumen</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_detail_history">
                    <span class="nav-text">Riwayat</span>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_detail_role">
                    <span class="nav-text">Peran</span>
                </a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_detail_offence">
                    <span class="nav-text">Pelanggaran</span>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_detail_general" role="tabpanel">
                <div class="card card-px-0">
                    <div class="card-header">
                        <div class="d-flex flex-column">
                            <span class="card-title">Data Umum</span>
                            <span>Anda bisa merubah data karyawan</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs border nav-line-tabs nav-fill mb-5 fs-6 border-0 pt-5">
                            <li class="nav-item">
                                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_general_primary">
                                    <span class="nav-text">Data Primer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_general_personal">
                                    <span class="nav-text">Data Personal</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_general_office">
                                    <span class="nav-text">Data Perusahaan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_general_payroll">
                                    <span class="nav-text">Data Penggajian</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="generalTabContent">
                            <div class="tab-pane fade show active" id="tab_general_primary" role="tabpanel">
                                @include('_personel.employee_table._detail_primary_data')
                            </div>
                            <div class="tab-pane fade" id="tab_general_personal" role="tabpanel">
                                @include('_personel.employee_table._detail_personel_data')
                            </div>
                            <div class="tab-pane fade" id="tab_general_office" role="tabpanel">
                                @include('_personel.employee_table._detail_office_data')
                            </div>
                            <div class="tab-pane fade " id="tab_general_payroll" role="tabpanel">
                                @include('_personel.employee_table._detail_payroll_data')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="tab_detail_emergency" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex flex-column gap-3">
                                    <span class="fw-bold fs-3">Kontak Darurat</span>
                                    <span>Anda dapat mengatur kontak darurat karyawan</span>
                                </div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#kt_add_emergency">
                                    Tambah kontak darurat
                                </button>
                            </div>
                            <div class="accordion accordion-icon-collapse">
                                <div class="accordion-item border-0 bg-secondary-crm">
                                    <div id="kt_add_emergency" class="accordion-collapse collapse" data-bs-parent="#tab_detail_emergency">
                                        <div class="accordion-body">
                                            <form action="{{ route("personel.employee_table.family.store") }}" method="POST">
                                                <div class="d-flex flex-column align-items-center" data-form-clone>
                                                    <div class="card bg-transparent mb-5 shadow-none w-100" data-clone>
                                                        <div class="card-body rounded bg-white p-5 border">
                                                            <div class="row">
                                                                <div class="fv-row col-4">
                                                                    <label class="col-form-label">Nama</label>
                                                                    <input type="text" name="name" required class="form-control" placeholder="Input Name">
                                                                </div>
                                                                <div class="fv-row col-4">
                                                                    <label class="col-form-label">Hubungan</label>
                                                                    <select name="hubungan" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_add_emergency" data-placeholder="Select Relation" required>
                                                                        <option value=""></option>
                                                                        <option value="Orang Tua">Orang Tua</option>
                                                                        <option value="Saudara">Saudara</option>
                                                                        <option value="Suami">Suami</option>
                                                                        <option value="Istri">Istri</option>
                                                                        <option value="Anak">Anak</option>
                                                                    </select>
                                                                </div>
                                                                <div class="fv-row col-4">
                                                                    <label class="col-form-label">Nomor Telepon</label>
                                                                    <input type="text" name="no_telp" required placeholder="Input Phone Number" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer border-0">
                                                            <div class="d-flex justify-content-end">
                                                                @csrf
                                                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                                                <input type="hidden" name="emergency" value="1">
                                                                <button type="button" data-bs-toggle="collapse" data-bs-target="#kt_add_emergency" class="btn text-primary">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Tambah</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-secondary-crm rounded p-5">
                                @if ($families->where("emergency_contact", 1)->count() == 0)
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="fs-3 text-muted">Tidak ada kontak darurat</span>
                                    </div>
                                @else
                                    <table class="table table-display-2 bg-white">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Hubungan</th>
                                                <th>Nomor Telepon</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($families->where("emergency_contact", 1) as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->hubungan }}</td>
                                                    <td>{{ $item->no_telp }}</td>
                                                    <td>
                                                        <button class="btn btn-icon btn-sm">
                                                            <i class="fi-rr-menu-dots-vertical"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="tab_detail_document" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex flex-column gap-3">
                                    <span class="fw-bold fs-3">Dokumen</span>
                                    <span>Anda dapat mengatur dokumen karyawan</span>
                                </div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#kt_add_document">
                                    Tambah Dokumen
                                </button>
                            </div>
                            <div class="accordion accordion-icon-collapse">
                                <div class="accordion-item border-0 bg-secondary-crm">
                                    <div id="kt_add_document" class="accordion-collapse collapse" data-bs-parent="#tab_detail_document">
                                        <div class="accordion-body">
                                            <form action="{{ route("personel.employee_table.document.store") }}" enctype="multipart/form-data" method="POST">
                                                <div class="d-flex flex-column align-items-center" data-form-clone>
                                                    <div class="card bg-transparent mb-5 shadow-none w-100" data-clone>
                                                        <div class="card-body rounded bg-white p-5 border">
                                                            <div class="row">
                                                                <div class="fv-row col-12">
                                                                    <label class="col-form-label">Kategori</label>
                                                                    <select name="category" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#kt_add_document" data-placeholder="Pilih Kategori" required>
                                                                        <option value=""></option>
                                                                        @foreach ($master['document_cat'] as $k => $dc)
                                                                            <option value="{{ $k }}">{{ $dc }}</option>
                                                                        @endforeach
                                                                        {{-- @foreach ($master['document_cat'] as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                                                                        @endforeach --}}
                                                                    </select>
                                                                </div>
                                                                <div class="fv-row col-12">
                                                                    <label class="col-form-label">Tanggal Kadaluarsa</label>
                                                                    <input type="text" name="expired_date" class="form-control flatpicker" name="exp_date" placeholder="Tanggal Kadaluarsa">
                                                                </div>
                                                                <div class="fv-row col-6">
                                                                    <label class="col-form-label">Berkas</label>
                                                                    <div class="d-flex align-items-center">
                                                                        <label class="btn btn-secondary btn-sm">
                                                                            <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                                                                            Attachment
                                                                            <i class="fi fi-rr-clip"></i>
                                                                        </label>
                                                                        <span class="text-primary ms-5" data-file></span>
                                                                    </div>
                                                                    <span class="text-muted mt-3">Format Berkas : JPG,PNG,PDF Max 25 mb</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer border-0">
                                                            <div class="d-flex justify-content-end">
                                                                @csrf
                                                                <input type="hidden" name="personel_id" value="{{ $personel->id }}">
                                                                <button type="button" data-bs-toggle="collapse" data-bs-target="#kt_add_document" class="btn text-primary">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Tambah</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-secondary-crm rounded p-5">
                                @if ($documents->count() == 0)
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="fs-3 text-muted">Tidak ada dokumen</span>
                                    </div>
                                @else
                                    <table class="table table-display-2 bg-white">
                                        <thead>
                                            <tr>
                                                <th>Kategori</th>
                                                <th>Berkas</th>
                                                <th>Tanggal ditambahkan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($documents as $docs)
                                                <tr>
                                                    <td>{{ $master['document_cat'][$docs->category_id] }}</td>
                                                    <td>
                                                        <a href="{{ asset($docs->cv_address) }}">{{ $docs->cv_name }}</a>
                                                    </td>
                                                    <td>
                                                        {{ date("d-m-Y", strtotime($docs->created_at)) }}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-icon btn-sm">
                                                            <i class="fi-rr-menu-dots-vertical"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="tab_detail_history" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex flex-column gap-3">
                                    <span class="fw-bold fs-3">Riwayat</span>
                                    <span>Disini Anda dapat melihat rangkuman riwayat karyawan</span>
                                </div>
                            </div>
                            <div class="bg-secondary-crm rounded p-5">
                                @if (empty($transfer_data))
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="fs-3 text-muted">Tidak ada riwayat</span>
                                </div>
                                @else
                                    <table class="table table-display-2 bg-white" data-ordering="false">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Aktifitas</th>
                                                <th>Disetujui Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($transfer_data as $item)
                                                <tr class="{{ $item->start_date > date("Y-m-d") ? "bg-light-danger" : "" }}">
                                                    <td>{{ date("d-m-Y", strtotime($item->start_date)) }}</td>
                                                    <td>{{ ucwords(str_replace("_", " ", $item->type)) }} change to <span class="fw-bold">{{ $tfMaster[$item->type][$item->new] ?? "-" }}</span> </td>
                                                    <td>{{ $user_approve[$item->approved_by] ?? "Auto Approve" }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="tab_detail_role" role="tabpanel">
                <div class="card bg-secondary-crm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="fs-3 text-muted">Tidak ada peran</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="tab_detail_offence" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex flex-column gap-3">
                                    <span class="fw-bold fs-3">Pelanggaran</span>
                                    <span>Anda dapat melihat data pelanggaran karyawan</span>
                                </div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#kt_add_offence">
                                    Tambah Pelanggaran
                                </button>
                            </div>
                            <div class="accordion accordion-icon-collapse">
                                <div class="accordion-item border-0 bg-secondary-crm">
                                    <div id="kt_add_offence" class="accordion-collapse collapse" data-bs-parent="#tab_detail_document">
                                        <div class="accordion-body">
                                            <form action="{{ route("personel.employee_table.offence") }}" enctype="multipart/form-data" method="POST">
                                                <div class="d-flex flex-column align-items-center" data-form-clone>
                                                    <div class="card bg-transparent mb-5 shadow-none w-100" data-clone>
                                                        <div class="card-body rounded bg-white p-5 border">
                                                            <div class="row">
                                                                <div class="fv-row col-6">
                                                                    <label class="col-form-label required">Tipe Pelanggaran</label>
                                                                    <select name="offence" class="form-select" data-control="select2" required data-placeholder="Pilih Tipe Pelanggaran" data-dropdown-parent="#kt_add_offence">
                                                                        <option value=""></option>
                                                                        @foreach ($offence['reason'] as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="fv-row col-6">
                                                                    <label class="col-form-label required">Diberikan Oleh</label>
                                                                    <select name="given_by" class="form-select" data-control="select2" required data-placeholder="Pilih" data-dropdown-parent="#kt_add_offence">
                                                                        <option value=""></option>
                                                                        @foreach ($offence['approval'] as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="fv-row col-6">
                                                                    <label class="col-form-label required">Tanggal Mulai</label>
                                                                    <input type="date" required name="start_date" class="form-control">
                                                                </div>
                                                                <div class="fv-row col-6">
                                                                    <label class="col-form-label">Tanggal Berakhir</label>
                                                                    <input type="date" name="end_date" class="form-control">
                                                                </div>
                                                                <div class="fv-row col-12">
                                                                    <label class="col-form-label">Catatan</label>
                                                                    <textarea name="remarks" class="form-control" placeholder="Masukan Catatan" cols="30" rows="5"></textarea>
                                                                </div>
                                                                <div class="fv-row col-6">
                                                                    <label class="col-form-label">Nomor Referensi</label>
                                                                    <input type="input" name="reference" placeholder="Masukan Nomor Referensi" class="form-control">
                                                                </div>
                                                                <div class="fv-row col-6">
                                                                    <label class="col-form-label">Unggah Berkas</label>
                                                                    <div class="d-flex align-items-center">
                                                                        <label class="btn btn-secondary btn-sm">
                                                                            <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="file" class="d-none">
                                                                            Attachment
                                                                            <i class="fi fi-rr-clip"></i>
                                                                        </label>
                                                                        <span class="text-primary ms-5" data-file>
                                                                        </span>
                                                                    </div>
                                                                    <span class="text-muted mt-3">Format Berkas : JPG,PNG,PDF Max 25 mb</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer border-0">
                                                            <div class="d-flex justify-content-end">
                                                                @csrf
                                                                <input type="hidden" name="drawer" value="1">
                                                                <input type="hidden" name="id" value="{{ $personel->id }}">
                                                                <button type="button" data-bs-toggle="collapse" data-bs-target="#kt_add_offence" class="btn text-primary">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Tambah</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-secondary-crm rounded p-5">
                                @if ($offence['data']->count() == 0)
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="fs-3 text-muted">Tidak ada pelanggaran</span>
                                    </div>
                                @else
                                    <table class="table table-display-2 bg-white">
                                        <thead>
                                            <tr>
                                                <th>Pelanggaran</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Berakhir</th>
                                                <th>Diberikan Oleh</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($offence['data'] as $item)
                                                <tr>
                                                    <td>{{ $item->detail->name }}</td>
                                                    <td>{{ date("d-m-Y", strtotime($item->start_date)) }}</td>
                                                    <td>{{ empty($item->end_date) ? "-" : date("d-m-Y", strtotime($item->end_date)) }}</td>
                                                    <td>{{ $item->user->name ?? "-" }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-icon">
                                                            <i class="fi fi-rr-menu-dots-vertical"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
