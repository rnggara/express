@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Pendidikan</h3>
                    <span>Detail dan aturan pendidikan</span>
                    {{-- <div class="d-flex align-items-center mb-5">
                        <span class="text-muted">Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">Personel <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="fw-semibold">Pendidikan</span>
                    </div> --}}
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
                            Tambah Pendidikan
                        </button>
                        <div class="position-relative">
                            <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                        </div>
                    </div>
                    <table class="table table-display-2 bg-white" data-ordering="false" id="table-list">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Record ID</th>
                                <th>Description</th>
                                <th>Status</th>
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
                                    <td>{{ $item->record_id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" {{ $item->status == 1 ? "checked" : "" }} disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                            <i class="fa fa-ellipsis-vertical text-dark"></i>
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" @if(!empty($item->company_id)) data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ empty($item->company_id) ? "disabled text-muted" : "" }}">
                                                    Delete Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $item->id }}" class="menu-link px-3">
                                                    Edit Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>

                                        <form action="{{ route("crm.pref.personel.education.post") }}" method="post">
                                            <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                                <span class="fw-bold fs-3">Are you sure want to delete?</span>
                                                                <span class="text-center">Please becarefull when deleting it will cause payroll employee !</span>
                                                                <span class="text-center">make sure Pendidikan not used to employee</span>
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

                                        <form action="{{ route("crm.pref.personel.education.post") }}" method="post">
                                            @component('layouts._crm_modal', [
                                                "modalId" => "modal_edit_$item->id",
                                                "modalSize" => "modal-lg"
                                            ])
                                                @slot('modalTitle')
                                                    <div class="d-flex flex-column">
                                                        <span class="fs-1 fw-bold">Edit Education</span>
                                                        <span class="fs-base fw-normal mt-2">Fill the form to set employee Education</span>
                                                    </div>
                                                @endslot
                                                @slot('modalContent')
                                                    <div class="fv-row">
                                                        <label class="col-form-label required">Record ID</label>
                                                        <input type="text" name="record_id" value="{{ $item->record_id }}" placeholder="Input Record ID" class="form-control">
                                                        @error('record_id')
                                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label required">Description</label>
                                                        <input type="text" name="description" value="{{ $item->name }}" placeholder="Example: S1, S2, SMA, SMP, and others" class="form-control">
                                                        @error('description')
                                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                @endslot
                                                @slot('modalFooter')
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                                                    <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
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

    <form action="{{ route("crm.pref.personel.education.post") }}" method="post">
        @component('layouts._crm_modal', [
            "modalId" => "modal_add",
            "modalSize" => "modal-lg"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Tambah Pendidikan</span>
                    <span class="fs-base fw-normal mt-2">Fill the form to set employee Pendidikan</span>
                </div>
            @endslot
            @slot('modalContent')
                <div class="fv-row">
                    <label class="col-form-label required">Record ID</label>
                    <input type="text" name="record_id" value="{{ old("record_id") }}" placeholder="Input Record ID" class="form-control">
                    @error('record_id')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row">
                    <label class="col-form-label required">Description</label>
                    <input type="text" name="description" value="{{ old("description") }}" placeholder="Example: S1, S2, SMA, SMP, and others" class="form-control">
                    @error('description')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
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
