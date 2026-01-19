<div class="d-flex flex-column gap-5">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="fi fi-rr-plus"></i>
            Tambah Lisensi
        </button>
    </div>
    <div class="card bg-secondary-crm shadow-none">
        <div class="card-body p-5">
            <table class="table table-display-2 bg-white" data-ordering="false">
                <thead>
                    <tr>
                        <th>Nama Lisensi</th>
                        <th>Nomor Lisensi</th>
                        <th>Dokumen</th>
                        <th>Tanggal Kedaluwarsa</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->no_lisensi }}</td>
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
                            <td>{{ empty($item->tgl_kadaluarsa) ? "-" : date("d-m-Y", strtotime($item->tgl_kadaluarsa)) }}</td>
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
                                        <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('personel.employee_table.license.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                            Hapus
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                            </td>
                            <form action="{{ route("personel.employee_table.license.store") }}" id="form_detail_{{ $item->id }}" method="post" enctype="multipart/form-data">
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
                                                <h3 class="me-2">Edit Lisensi</h3>
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('modalContent')
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="emp" value="{{ $personel->id }}">
                                        <div class="row p-5">
                                            <div class="fv-row col-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Nama Lisensi</label>
                                                    <input type="text" form="form_detail_{{ $item->id }}" value="{{ $item->name }}" name="name" class="form-control" placeholder="Masukan Nama Lisensi">
                                                </div>
                                            </div>
                                            <div class="fv-row col-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Nomor Lisensi</label>
                                                    <input type="text" form="form_detail_{{ $item->id }}" value="{{ $item->no_lisensi }}" name="number" class="form-control" placeholder="Masukan Nomor Lisensi">
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
                                            <div class="fv-row col-6">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Tanggal Kedaluwarsa</label>
                                                    <input type="date" form="form_detail_{{ $item->id }}" value="{{ $item->tgl_kadaluarsa }}" name="exp_date" class="form-control" placeholder="Masukan Tanggal Kedaluwarsa">
                                                </div>
                                            </div>
                                        </div>
                                    @endslot
                                    @slot('modalFooter')
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_detail_{{ $item->id }}">
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="personel_id" value="{{ $personel->id }}">
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="id" value="{{ $item->id }}">
                                        <input type="hidden" form="form_detail_{{ $item->id }}" name="section" value="license">
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

<form action="{{ route("personel.employee_table.license.store") }}" method="post" enctype="multipart/form-data">
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
                    <h3 class="me-2">Tambah Lisensi</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ $personel->id }}">
            <div class="row p-5">
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
                <div class="fv-row col-6">
                    <div class="form-group">
                        <label for="" class="col-form-label">Tanggal Kedaluwarsa</label>
                        <input type="date" name="exp_date" class="form-control" placeholder="Masukan Tanggal Kedaluwarsa">
                    </div>
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
            <input type="hidden" name="section" value="license">
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        @endslot
    @endcomponent
</form>
