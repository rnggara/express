<div class="d-flex flex-column gap-5">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="fi fi-rr-plus"></i>
            Tambah Data Keluarga
        </button>
    </div>
    <div class="card bg-secondary-crm shadow-none">
        <div class="card-body p-5">
            <table class="table table-display-2 bg-white" data-ordering="false">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Hubungan Kekeluargaan</th>
                        <th>Status Pernikahan</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Nomor Telepon</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->hubungan }}</td>
                            <td>{{ $master['marital_status'][$item->marital_id] ?? "-" }}</td>
                            <td>{{ $master['gender'][$item->jenis_kelamin] ?? "-" }}</td>
                            <td>{{ empty($item->tgl_lahir) ? "-" : date("d-m-Y", strtotime($item->tgl_lahir)) }}</td>
                            <td>{{ $item->no_telp ?? "-" }}</td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                    <i class="fi fi-rr-menu-dots-vertical"></i>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_detail_{{ $item->id }}" class="menu-link px-3">
                                            Detil
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('personel.employee_table.family.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                            Hapus
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                            </td>
                            <form action="{{ route("personel.employee_table.family.store") }}" id="form_detail_{{ $item->id }}" method="post" enctype="multipart/form-data">
                                @component('layouts._crm_modal', [
                                    'modalSize' => "modal-lg"
                                    ])
                                    @slot('modalId')
                                        modal_detail_{{ $item->id }}
                                    @endslot
                                    @slot('modalTitle')
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-5">
                                                <div class="symbol-label bg-light-primary">
                                                    <span class="fi fi-sr-add text-primary"></span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h3 class="me-2">Edit Data Keluarga</h3>
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('modalContent')
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="emp" value="{{ $personel->id }}">
                                        <div class="row p-5">
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Nama*</label>
                                                <input type="text" form="form_detail_{{ $item->id }}" value="{{ $item->name }}" name="name" required value="" class="form-control" placeholder="Input Nama">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Hubungan Kekeluargaan*</label>
                                                <select form="form_detail_{{ $item->id }}" name="hubungan" required class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-placeholder="Select Relation" required>
                                                    <option value=""></option>
                                                    <option value="Orang Tua" {{ $item->hubungan == "Orang Tua" ? "SELECTED" : "" }}>Orang Tua</option>
                                                    <option value="Saudara" {{ $item->hubungan == "Saudara" ? "SELECTED" : "" }}>Saudara</option>
                                                    <option value="Suami" {{ $item->hubungan == "Suami" ? "SELECTED" : "" }}>Suami</option>
                                                    <option value="Istri" {{ $item->hubungan == "Istri" ? "SELECTED" : "" }}>Istri</option>
                                                    <option value="Anak" {{ $item->hubungan == "Anak" ? "SELECTED" : "" }}>Anak</option>
                                                </select>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Status Pernikahan</label>
                                                <select form="form_detail_{{ $item->id }}" name="marital_id" class="form-select" data-control="select2" data-placeholder="Select Status Pernikahan" data-dropdown-parent="#modal_detail_{{ $item->id }}">
                                                    <option value=""></option>
                                                    @foreach ($master['marital_status'] as $id => $name)
                                                        <option value="{{ $id }}" {{ $id == $item->marital_id ? "SELECTED" : "" }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Jenis Kelamin</label>
                                                <select form="form_detail_{{ $item->id }}" name="gender" class="form-select" data-control="select2" data-placeholder="Select Gender" data-dropdown-parent="#modal_detail_{{ $item->id }}">
                                                    <option value=""></option>
                                                    @foreach ($master['gender'] as $id => $name)
                                                        <option value="{{ $id }}" {{ $id == $item->jenis_kelamin ? "SELECTED" : "" }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Tanggal Lahir</label>
                                                <input type="date" form="form_detail_{{ $item->id }}" name="tgl_lahir" value="{{ $item->tgl_lahir }}" class="form-control">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Nomor Telepon*</label>
                                                <input type="text" form="form_detail_{{ $item->id }}" name="no_telp" required value="{{ $item->no_telp }}" placeholder="Input Nomor Telepon" class="form-control">
                                            </div>
                                            <div class="fv-row col-12">
                                                <label class="col-form-label">Unggah Dokumen</label>
                                                <div class="d-flex align-items-center">
                                                    <label class="btn btn-secondary btn-sm">
                                                        <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" form="form_detail_{{ $item->id }}" name="lampiran" class="d-none">
                                                        <span>Attachment</span>
                                                        <i class="fi fi-rr-clip"></i>
                                                    </label>
                                                    @php
                                                        $fname = null;
                                                        if(!empty($item->lampiran)){
                                                            $lmp = explode("_family_", $item->lampiran ?? "");
                                                            $fname = end($lmp);
                                                        }
                                                    @endphp
                                                    <span class="text-primary ms-5" data-file>
                                                        @if (!empty($fname))
                                                            <a href="{{ asset($item->lampiran) }}">{{ $fname }}</a>
                                                        @endif
                                                    </span>
                                                </div>
                                                <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                                            </div>
                                            <div class="fv-row col-12">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        Tambah sebagai kontak darurat
                                                        <input class="form-check-input" type="checkbox" form="form_detail_{{ $item->id }}" {{ $item->emergency_contact == 1 ? "checked" : "" }} name="emergency" value="1" />
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('modalFooter')
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_detail_{{ $item->id }}">
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="personel_id" value="{{ $personel->id }}">
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="id" value="{{ $item->id }}">
                                        <input type="hidden" name="section" form="form_detail_{{ $item->id }}" value="family_data">
                                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" form="form_detail_{{ $item->id }}" class="btn btn-primary">Simpan</button>
                                    @endslot
                                @endcomponent
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form action="{{ route("personel.employee_table.family.store") }}" method="post" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modalAdd
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-sr-add text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Tambah Data Keluarga</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ $personel->id }}">
            <div class="row p-5">
                <div class="fv-row col-6">
                    <label class="col-form-label">Nama*</label>
                    <input type="text" name="name" required value="" class="form-control" placeholder="Input Nama">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Hubungan Kekeluargaan*</label>
                    <select name="hubungan" required class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#modalAdd" data-placeholder="Select Relation" required>
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
                    <select name="marital_id" class="form-select" data-control="select2" data-placeholder="Select Status Pernikahan" data-dropdown-parent="#modalAdd">
                        <option value=""></option>
                        @foreach ($master['marital_status'] as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Jenis Kelamin</label>
                    <select name="gender" class="form-select" data-control="select2" data-placeholder="Select Gender" data-dropdown-parent="#modalAdd">
                        <option value=""></option>
                        @foreach ($master['gender'] as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" value="" class="form-control">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Nomor Telepon*</label>
                    <input type="text" name="no_telp" required value="" placeholder="Input Nomor Telepon" class="form-control">
                </div>
                <div class="fv-row col-12">
                    <label class="col-form-label">Unggah Dokumen</label>
                    <div class="d-flex align-items-center">
                        <label class="btn btn-secondary btn-sm">
                            <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran" class="d-none">
                            <span>Attachment</span>
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
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
            <input type="hidden" name="section" value="family_data">
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        @endslot
    @endcomponent
</form>
