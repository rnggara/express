@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Perusahaan</h3>
                    <div class="d-flex align-items-center mb-5">
                        <span class="text-muted">Pengaturan <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">Perusahaan <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="fw-semibold">Perusahaan</span>
                    </div>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
                            Tambah Perusahaan
                        </button>
                        <div class="position-relative">
                            <input type="text" class="form-control ps-12" placeholder="Cari" name="search_table">
                            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                        </div>
                    </div>
                    <table class="table table-display-2 bg-white" data-ordering="false" id="table-list">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama Perusahaan</th>
                                <th>Induk Perusahaan</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""/>
                                        </div>
                                    </td>
                                    <td>{{ $item->company_name }}</td>
                                    <td>{{ $item->parentComp->company_name ?? "-" }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" checked disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route("crm.pref.company.company_list.detail", base64_encode($item->id)) }}" class="btn btn-icon btn-secondary btn-sm" data-bs-toggle="tooltip" title="Pengaturan Perusahaan">
                                            <i class="fi fi-rr-settings"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route("crm.pref.company.company_list.structure", base64_encode($item->id)) }}" class="btn btn-icon btn-secondary btn-sm" data-bs-toggle="tooltip" title="Pengaturan Struktur">
                                            <i class="fi fi-rr-code-branch"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                            <i class="fa fa-ellipsis-vertical text-dark"></i>
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $item->id }}" class="menu-link px-3 text-danger">
                                                    Hapus
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $item->id }}" class="menu-link px-3">
                                                    Ubah
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>

                                        <form action="{{ route("crm.pref.company.company_list.post") }}" method="post">
                                            <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                                <span class="fw-bold fs-3">Are you sure want to delete?</span>
                                                                <span class="text-center">Please becarefull when deleting it will cause payroll employee !</span>
                                                                <span class="text-center">make sure Perusahaan not used to employee</span>
                                                                <div class="d-flex align-items-center mt-5">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                                    <button type="submit" name="submit" value="delete" class="btn btn-white">Yes</button>
                                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <form action="{{ route("crm.pref.company.company_list.post") }}" method="post">
                                            @component('layouts._crm_modal', [
                                                "modalId" => "modal_edit_$item->id",
                                                "modalSize" => "modal-lg"
                                            ])
                                                @slot('modalTitle')
                                                    <div class="d-flex flex-column">
                                                        <span class="fs-1 fw-bold">Edit Perusahaan</span>
                                                        <span class="fs-base fw-normal mt-2">&nbsp;&nbsp; </span>
                                                    </div>
                                                @endslot
                                                @slot('modalContent')
                                                    <div class="fv-row">
                                                        <label class="col-form-label required">Nama Perusahaan</label>
                                                        <input type="text" name="company_name" value="{{ old("company_name") ?? $item->company_name }}" placeholder="Masukkan Nama Perusahaan" class="form-control">
                                                        @error('company_name')
                                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label">Nomor Telepon</label>
                                                        <input type="text" name="phone" value="{{ old("phone") ?? $item->phone }}" placeholder="Masukkan Nomor Telepon" class="form-control">
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label">Email</label>
                                                        <input type="text" name="email" value="{{ old("email") ?? $item->email }}" placeholder="Masukkan Email" class="form-control">
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label">Alamat</label>
                                                        <textarea name="address" placeholder="Masukkan Alamat" class="form-control">{{ old("address") ?? $item->address }}</textarea>
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label">Induk Perusahaan</label>
                                                        <select name="id_parent" data-dropdown-parent="#modal_edit_{{ $item->id }}" class="form-select" data-control="select2" data-placeholder="Select User">
                                                            @foreach ($data as $val)
                                                                <option value="{{ $val->id }}" {{ (old("id_parent") ?? $item->id_parent) == $item->id ? "SELECTED" : "" }}>{{ $val->company_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endslot
                                                @slot('modalFooter')
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="submit" value="store" class="btn btn-primary">Simpan</button>
                                                @endslot
                                            @endcomponent
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route("crm.pref.company.company_list.post") }}" method="post">
        @component('layouts._crm_modal', [
            "modalId" => "modal_add",
            "modalSize" => "modal-lg"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Tambah Perusahaan</span>
                    <span class="fs-base fw-normal mt-2">&nbsp;&nbsp; </span>
                </div>
            @endslot
            @slot('modalContent')
                <div class="fv-row">
                    <label class="col-form-label required">Nama Perusahaan</label>
                    <input type="text" name="company_name" value="{{ old("company_name") }}" placeholder="Masukkan Nama Perusahaan" class="form-control">
                    @error('company_name')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old("phone") }}" placeholder="Masukkan Nomor Telepon" class="form-control">
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Email</label>
                    <input type="text" name="email" value="{{ old("email") }}" placeholder="Masukkan Email" class="form-control">
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Alamat</label>
                    <textarea name="address" placeholder="Masukkan Alamat" class="form-control">{{ old("address") }}</textarea>
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Induk Perusahaan</label>
                    <select name="id_parent" data-dropdown-parent="#modal_add" class="form-select" data-control="select2" data-placeholder="Select Parent">
                        @foreach ($data as $item)
                            <option value="{{ $item->id }}" {{ old("id_parent") == $item->id ? "SELECTED" : "" }}>{{ $item->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Simpan</button>
            @endslot
        @endcomponent
    </form>
@endsection

@section('view_script')
<script>
    $(document).ready(function(){
        @if(Session::has("modal"))
            $("#{{ Session::get("modal") }}").modal("show")
        @endif
    })
</script>
@endsection
