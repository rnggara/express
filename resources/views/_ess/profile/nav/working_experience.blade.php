<div class="d-flex flex-column gap-5">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="fi fi-rr-plus"></i>
            Tambah Pengalaman Kerja
        </button>
    </div>
    <div class="card bg-secondary-crm shadow-none">
        <div class="card-body p-5">
            <table class="table table-display-2 bg-white" data-ordering="false">
                <thead>
                    <tr>
                        <th>Nama Perusahaan</th>
                        <th>Bidang Usaha</th>
                        <th>Gaji Terakhir</th>
                        <th>Posisi Terakhir</th>
                        <th>Status Kepegawaian</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->company }}</td>
                            <td>{{ $master['industry'][$item->industry] ?? "-" }}</td>
                            <td>Rp. {{ number_format($item->salary ?? 0, 0, ",", ".") }}</td>
                            <td>{{ $item->position }}</td>
                            <td>{{ $master['job_type'][$item->job_type] ?? "-" }}</td>
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
                                        <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('personel.employee_table.work.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                            Hapus
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                            </div>
                            </td>
                            <form action="{{ route("personel.employee_table.work.store") }}" id="form_detail_{{ $item->id }}" method="post" enctype="multipart/form-data">
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
                                                <h3 class="me-2">Edit Pengalaman Kerja</h3>
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('modalContent')
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="emp" value="{{ $personel->id }}">
                                        <div class="row p-5">
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Nama Perusahaan*</label>
                                                <input type="text" form="form_detail_{{ $item->id }}" value="{{ $item->company }}" name="company" required value="" placeholder="Masukan Nama Perusahaan" class="form-control">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Bidang Usaha</label>
                                                <select form="form_detail_{{ $item->id }}" name="industry" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-placeholder="Pilih Bidang Usaha">
                                                    <option value=""></option>
                                                    @foreach ($master['industry'] as $key => $nn)
                                                        <option value="{{ $key }}" {{ $key == $item->industry ? "SELECTED" : "" }}>{{ $nn }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Gaji Terakhir</label>
                                                <div class="position-relative">
                                                    <input type="text" form="form_detail_{{ $item->id }}" name="salary" value="{{ $item->salary }}" placeholder="0" class="form-control number ps-13">
                                                    <span class="position-absolute top-25 ms-5 mt-1">IDR</span>
                                                </div>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Posisi Terakhir*</label>
                                                <input type="text" required  form="form_detail_{{ $item->id }}" name="position" value="{{ $item->position }}" placeholder="Masukan Posisi Terakhir" class="form-control">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Status Kepegawaian</label>
                                                <select form="form_detail_{{ $item->id }}" name="job_type" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-placeholder="Pilih Status Kepegawaian">
                                                    <option value=""></option>
                                                    @foreach ($master['job_type'] as $key => $nn)
                                                        <option value="{{ $key }}" {{ $key == $item->job_type ? "SELECTED" : "" }}>{{ $nn }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row col-6"></div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Tanggal Mulai*</label>
                                                <select form="form_detail_{{ $item->id }}" name="start_month" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-placeholder="Pilih Bulan Mulai">
                                                    <option value=""></option>
                                                    @foreach ($idFullMonth as $key => $nn)
                                                        <option value="{{ sprintf("%02d", $key) }}" {{ $key == date("m", strtotime($item->start_date)) ? "SELECTED" : "" }}>{{ $nn }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">&nbsp;</label>
                                                <select form="form_detail_{{ $item->id }}" name="start_year" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-placeholder="Pilih Tahun Mulai">
                                                    <option value=""></option>
                                                    @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                                                        <option value="{{ $i }}" {{ $i == date("Y", strtotime($item->start_date)) ? "SELECTED" : "" }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="fv-row col-12 mt-5">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        Saya masih bekerja di perusahaan ini
                                                        <input class="form-check-input" type="checkbox" value="1" {{ $item->still == 1 ? "CHECKED" : "" }} form="form_detail_{{ $item->id }}" name="still" data-toggle="still" />
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="fv-row col-6" data-target="still">
                                                <label class="col-form-label">Tanggal Keluar*</label>
                                                <select form="form_detail_{{ $item->id }}" name="end_month" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-placeholder="Pilih Bulan Keluar">
                                                    <option value=""></option>
                                                    @foreach ($idFullMonth as $key => $nn)
                                                        <option value="{{ sprintf("%02d", $key) }}" {{ $key == date("m", strtotime($item->end_date)) ? "SELECTED" : "" }}>{{ $nn }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row col-6" data-target="still">
                                                <label class="col-form-label">&nbsp;</label>
                                                <select form="form_detail_{{ $item->id }}" name="end_year" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-placeholder="Pilih Tahun Keluar">
                                                    <option value=""></option>
                                                    @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                                                        <option value="{{ $i }}" {{ $i == date("Y", strtotime($item->end_date)) ? "SELECTED" : "" }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="fv-row col-12">
                                                <label class="col-form-label">Deskripsi*</label>
                                                <textarea form="form_detail_{{ $item->id }}" name="descriptions" class="form-control" id="" cols="30" rows="5" placeholder="Masukan descriptions">{!! $item->descriptions !!}</textarea>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Reference</label>
                                                <input type="text" form="form_detail_{{ $item->id }}" name="reference" class="form-control" placeholder="Masukan reference name" value="{{ $item->reference }}" id="">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">&nbsp;</label>
                                                <input type="text" form="form_detail_{{ $item->id }}" name="phone" class="form-control" placeholder="Masukan reference number" value="{{ $item->phone }}" id="">
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('modalFooter')
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_detail_{{ $item->id }}">
                                    <input type="hidden" form="form_detail_{{ $item->id }}" name="personel_id" value="{{ $personel->id }}">
                                    <input type="hidden" form="form_detail_{{ $item->id }}" name="id" value="{{ $item->id }}">
                                    <input type="hidden" form="form_detail_{{ $item->id }}" name="section" value="{{ $section }}">
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

<form action="{{ route("personel.employee_table.work.store") }}" method="post" enctype="multipart/form-data">
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
                    <h3 class="me-2">Tambah Pengalaman Kerja</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ $personel->id }}">
            <div class="row p-5">
                <div class="fv-row col-6">
                    <label class="col-form-label">Nama Perusahaan*</label>
                    <input type="text" name="company" required value="" placeholder="Masukan Nama Perusahaan" class="form-control">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Bidang Usaha</label>
                    <select name="industry" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#modalAdd" data-placeholder="Pilih Bidang Usaha">
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
                    <select name="job_type" class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#modalAdd" data-placeholder="Pilih Status Kepegawaian">
                        <option value=""></option>
                        @foreach ($master['job_type'] as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6"></div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Tanggal Mulai*</label>
                    <select name="start_month" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#modalAdd" data-placeholder="Pilih Bulan Mulai">
                        <option value=""></option>
                        @foreach ($idFullMonth as $key => $item)
                            <option value="{{ sprintf("%02d", $key) }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">&nbsp;</label>
                    <select name="start_year" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#modalAdd" data-placeholder="Pilih Tahun Mulai">
                        <option value=""></option>
                        @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="fv-row col-12 mt-5">
                    <div class="form-check">
                        <label class="form-check-label">
                            Saya masih bekerja di perusahaan ini
                            <input class="form-check-input" type="checkbox" value="1" name="still" data-toggle="still" />
                        </label>
                    </div>
                </div>
                <div class="fv-row col-6" data-target="still">
                    <label class="col-form-label">Tanggal Keluar*</label>
                    <select name="end_month" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#modalAdd" data-placeholder="Pilih Bulan Keluar">
                        <option value=""></option>
                        @foreach ($idFullMonth as $key => $item)
                            <option value="{{ sprintf("%02d", $key) }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6" data-target="still">
                    <label class="col-form-label">&nbsp;</label>
                    <select name="end_year" class="form-select" required data-control="select2" data-hide-search="true" data-dropdown-parent="#modalAdd" data-placeholder="Pilih Tahun Keluar">
                        <option value=""></option>
                        @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="fv-row col-12">
                    <label class="col-form-label">Deskripsi*</label>
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
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
            <input type="hidden" name="section" value="{{ $section }}">
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        @endslot
    @endcomponent
</form>
