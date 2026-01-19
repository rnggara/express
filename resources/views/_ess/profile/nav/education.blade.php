<div class="d-flex flex-column gap-5">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="fi fi-rr-plus"></i>
            Tambah Pendidikan
        </button>
    </div>
    <div class="card bg-secondary-crm shadow-none">
        <div class="card-body p-5">
            <table class="table table-display-2 bg-white" data-ordering="false">
                <thead>
                    <tr>
                        <th>Tingkat Pendidikan</th>
                        <th>Jurusan</th>
                        <th>IPK</th>
                        <th>Nama Institusi</th>
                        <th>Tahun Kelulusan</th>
                        <th>Dokumen</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->degree }}</td>
                            <td>{{ $item->field_of_study }}</td>
                            <td>{{ $item->grade }}</td>
                            <td>{{ $item->school_name }}</td>
                            <td>{{ $item->year_graduate ?? "-" }}</td>
                            <td>
                                @if (empty($item->lampiran))
                                    -
                                @else
                                    @php
                                        $exp = explode("_education_", $item->lampiran);
                                        $fname = end($exp);
                                    @endphp
                                    <a href="{{ asset($item->lampiran) }}">{{ $fname }}</a>
                                @endif
                            </td>
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
                                        <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('personel.employee_table.education.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                            Hapus
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                            </td>
                            <form action="{{ route("personel.employee_table.education.store") }}" method="post" id="form_detail_{{ $item->id }}" enctype="multipart/form-data">
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
                                                <h3 class="me-2">Edit Pendidikan</h3>
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('modalContent')
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="emp" value="{{ $personel->id }}">
                                        <div class="row p-5">
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Tingkat Pendidikan*</label>
                                                <select form="form_detail_{{ $item->id }}" name="degree" required class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-placeholder="Pilih Tingkat Pendidikan" required>
                                                    <option value=""></option>
                                                    @foreach ($degree as $vv)
                                                        <option value="{{ $vv->name }}" {{ $item->degree == $vv->name ? "SELECTED" : "" }}>{{ $vv->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Jurusan</label>
                                                <input type="text" form="form_detail_{{ $item->id }}" name="field_of_study" value="{{ $item->field_of_study }}" placeholder="Masukan Jurusan" class="form-control">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">IPK*</label>
                                                <input type="number" min="0" max="4" required step=".01" form="form_detail_{{ $item->id }}" name="grade" value="{{ $item->grade }}" placeholder="Masukan IPK" class="form-control">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Nama Institusi*</label>
                                                <input type="text" form="form_detail_{{ $item->id }}" name="school_name" required placeholder="Masukan Nama Institusi" value="{{ $item->school_name }}" class="form-control">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Tahun Kelulusan</label>
                                                <input type="number" form="form_detail_{{ $item->id }}" name="year_graduate" min="1000"  value="{{ $item->year_graduate }}" placeholder="Masukan Tahun Kelulusan" class="form-control">
                                            </div>
                                            <div class="fv-row col-6">
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
                                                            $lmp = explode("_education_", $item->lampiran ?? "");
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
                                        </div>
                                    @endslot
                                    @slot('modalFooter')
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_detail_{{ $item->id }}">
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="personel_id" value="{{ $personel->id }}">
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="id" value="{{ $item->id }}">
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="section" value="education">
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

<form action="{{ route("personel.employee_table.education.store") }}" method="post" enctype="multipart/form-data">
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
                    <h3 class="me-2">Tambah Pendidikan</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ $personel->id }}">
            <div class="row p-5">
                <div class="fv-row col-6">
                    <label class="col-form-label">Tingkat Pendidikan*</label>
                    <select name="degree" required class="form-select" data-control="select2" data-hide-search="true" data-dropdown-parent="#modalAdd" data-placeholder="Pilih Tingkat Pendidikan" required>
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
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
            <input type="hidden" name="section" value="education">
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        @endslot
    @endcomponent
</form>
