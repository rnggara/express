<div class="d-flex flex-column gap-5">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="fi fi-rr-plus"></i>
            Tambah Data Kemampuan Bahasa
        </button>
    </div>
    <div class="card bg-secondary-crm shadow-none">
        <div class="card-body p-5">
            <table class="table table-display-2 bg-white" data-ordering="false">
                <thead>
                    <tr>
                        <th>Bahasa</th>
                        <th>Kemampuan Menulis</th>
                        <th>Kemampuan Membaca</th>
                        <th>Kemampuan Berbicara</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        @if (isset($master['language'][$item->language]))
                            <tr>
                                <td>{{ $master['language'][$item->language] }}</td>
                                <td>{{ $item->writing }}/5</td>
                                <td>{{ $item->reading }}/5</td>
                                <td>{{ $item->speaking }}/5</td>
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
                                            <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('personel.employee_table.language.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                                Hapus
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                </td>
                                <form action="{{ route("personel.employee_table.language.store") }}" id="form_detail_{{ $item->id }}" method="post" enctype="multipart/form-data">
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
                                                    <h3 class="me-2">Edit Data Kemampuan Bahasa</h3>
                                                </div>
                                            </div>
                                        @endslot
                                        @slot('modalContent')
                                            <input type="hidden" form="form_detail_{{ $item->id }}" name="emp" value="{{ $personel->id }}">
                                            <div class="row p-5">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="language" class="col-form-label">Bahasa*</label>
                                                        <select form="form_detail_{{ $item->id }}" name="language" data-placeholder="Pilih Bahasa" data-control="select2" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-hide-search="true" class="form-control" required>
                                                            <option value=""></option>
                                                            @foreach ($master['language'] as $id => $name)
                                                                <option value="{{ $id }}" {{ $item->language == $id ? "SELECTED" : "" }}>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="writing" class="col-form-label">Kemampuan Menulis</label>
                                                        <select form="form_detail_{{ $item->id }}" name="writing" data-placeholder="Pilih Kemampuan Menulis" data-control="select2" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-hide-search="true" class="form-select">
                                                            <option value=""></option>
                                                            @for ($nn = 1; $nn <= 5; $nn++)
                                                                <option value="{{ $nn }}" {{ $nn == $item->writing ? "SELECTED" : "" }}>{{ $nn }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="reading" class="col-form-label">Kemampuan Membaca</label>
                                                        <select form="form_detail_{{ $item->id }}" name="reading" data-placeholder="Pilih Kemampuan Membaca" data-control="select2" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-hide-search="true" class="form-select">
                                                            <option value=""></option>
                                                            @for ($nn = 1; $nn <= 5; $nn++)
                                                                <option value="{{ $nn }}" {{ $nn == $item->reading ? "SELECTED" : "" }}>{{ $nn }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="speaking" class="col-form-label">Kemampuan Berbicara</label>
                                                        <select form="form_detail_{{ $item->id }}" name="speaking" data-placeholder="Pilih Kemampuan Berbicara" data-control="select2" data-dropdown-parent="#modal_detail_{{ $item->id }}" data-hide-search="true" class="form-select">
                                                            <option value=""></option>
                                                            @for ($nn = 1; $nn <= 5; $nn++)
                                                                <option value="{{ $nn }}" {{ $nn == $item->speaking ? "SELECTED" : "" }}>{{ $nn }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <span class="text-muted">*) 1 : kurang mampu, 5 : paling mampu</span>
                                                </div>
                                            </div>
                                        @endslot
                                        @slot('modalFooter')
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form_detail_{{ $item->id }}">
                                            <input type="hidden" form="form_detail_{{ $item->id }}" name="personel_id" value="{{ $personel->id }}">
                                            <input type="hidden" form="form_detail_{{ $item->id }}" name="id" value="{{ $item->id }}">
                                            <input type="hidden" form="form_detail_{{ $item->id }}" name="section" value="language_skill">
                                            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" form="form_detail_{{ $item->id }}" class="btn btn-primary">Simpan</button>
                                        @endslot
                                    @endcomponent
                                </form>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form action="{{ route("personel.employee_table.language.store") }}" method="post" enctype="multipart/form-data">
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
                    <h3 class="me-2">Tambah Data Kemampuan Bahasa</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ $personel->id }}">
            <div class="row p-5">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label for="language" class="col-form-label">Bahasa*</label>
                        <select name="language" data-placeholder="Pilih Bahasa" data-control="select2" data-dropdown-parent="#modalAdd" data-hide-search="true" class="form-control" required>
                            <option value=""></option>
                            @foreach ($master['language'] as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label for="writing" class="col-form-label">Kemampuan Menulis</label>
                        <select name="writing" data-placeholder="Pilih Kemampuan Menulis" data-control="select2" data-dropdown-parent="#modalAdd" data-hide-search="true" class="form-select">
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
                        <select name="reading" data-placeholder="Pilih reading ability" data-control="select2" data-dropdown-parent="#modalAdd" data-hide-search="true" class="form-select">
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
                        <select name="speaking" data-placeholder="Pilih Kemampuan Berbicara" data-control="select2" data-dropdown-parent="#modalAdd" data-hide-search="true" class="form-select">
                            <option value=""></option>
                            @for ($item = 1; $item <= 5; $item++)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <span class="text-muted">*) 1 : kurang mampu, 5 : paling mampu</span>
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <input type="hidden" name="personel_id" value="{{ $personel->id }}">
            <input type="hidden" name="section" value="language_skill">
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        @endslot
    @endcomponent
</form>
