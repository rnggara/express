<div class="d-flex flex-column gap-5">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="fi fi-rr-plus"></i>
            Tambah Rekam Medis
        </button>
    </div>
    <div class="card bg-secondary-crm shadow-none">
        <div class="card-body p-5">
            <table class="table table-display-2 bg-white" data-ordering="false">
                <thead>
                    <tr>
                        <th>Nama Penyakit</th>
                        <th>Tahun Kejadian</th>
                        <th>Dokumen</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->descriptions }}</td>
                            <td>{{ $item->year }}</td>
                            <td>
                                @if (empty($item->lampiran))
                                    -
                                @else
                                    @php
                                        $exp = explode("_mcu_", $item->lampiran);
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
                                        <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('personel.employee_table.mcu.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                            Hapus
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                            </td>
                            <form action="{{ route("personel.employee_table.mcu.store") }}" id="form_detail_{{ $item->id }}" method="post" enctype="multipart/form-data">
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
                                                <h3 class="me-2">Edit Rekam Medis</h3>
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('modalContent')
                                        <input type="hidden" name="emp" value="{{ $personel->id }}">
                                        <div class="row p-5">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="fv-row col-6">
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Nama Penyakit</label>
                                                            <input type="text" form="form_detail_{{ $item->id }}" name="descriptions" value="{{ $item->descriptions }}" class="form-control" placeholder="Input disease">
                                                        </div>
                                                    </div>
                                                    <div class="fv-row col-6">
                                                        <div class="form-group">
                                                            <label for="" class="col-form-label">Historical year</label>
                                                            <select form="form_detail_{{ $item->id }}" name="year" data-placeholder="Select year" data-control="select2" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-hide-search="true" class="form-select">
                                                                <option value=""></option>
                                                                @for ($nn = date("Y") - 10; $nn <= date("Y"); $nn++)
                                                                    <option value="{{ $nn }}" {{ $item->year == $nn ? "SELECTED" : "" }}>{{ $nn }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
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
                                                                    $lmp = explode("_mcu_", $item->lampiran ?? "");
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

<form action="{{ route("personel.employee_table.mcu.store") }}" method="post" enctype="multipart/form-data">
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
                    <h3 class="me-2">Tambah Rekam Medis</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ $personel->id }}">
            <div class="row p-5">
                <div class="col-12">
                    <div class="row">
                        <div class="fv-row col-6">
                            <div class="form-group">
                                <label for="" class="col-form-label">Nama Penyakit</label>
                                <input type="text" name="descriptions" class="form-control" placeholder="Input disease">
                            </div>
                        </div>
                        <div class="fv-row col-6">
                            <div class="form-group">
                                <label for="" class="col-form-label">Historical year</label>
                                <select name="year" data-placeholder="Select year" data-control="select2" data-dropdown-parent="#modalAdd" data-hide-search="true" class="form-select">
                                    <option value=""></option>
                                    @for ($item = date("Y") - 10; $item <= date("Y"); $item++)
                                        <option value="{{ $item }}" {{ date("Y") == $item ? "SELECTED" : "" }}>{{ $item }}</option>
                                    @endfor
                                </select>
                            </div>
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
