@extends('_crm.preferences.index')

@section('view_content')
    <div class="card shadow-none">
        <div class="card-header border-0">
            <h3 class="card-title">Role Setting</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_add">
                    <i class="fi fi-rr-plus"></i>
                    Add Role
                </button>
            </div>
        </div>
        <div class="card-body bg-secondary-crm rounded">
            @if($data->count() == 0)
            <div class="bg-white d-flex flex-column align-items-center p-10" data-list-if>
                <span class="fi fi-rr-document fs-1 text-muted"></span>
                <span class="text-muted">No data available at the moment. Begin by adding your first data entry.</span>
            </div>
            @else
            <div class="row">
                @foreach ($data as $item)
                    <div class="col-md-3 col-6 mb-6">
                        <div class="d-flex flex-column align-items-center shadow-sm rounded px-10 py-5 bg-white">
                            <div class="d-flex justify-content-between w-100 mb-10">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $item->name }}</span>
                                    <span class="text-muted">Default</span>
                                </div>
                                <div>
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

                                    <form action="{{ route("crm.pref.uac.role.post") }}" method="post">
                                        <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item->id }}">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                            <span class="fw-bold fs-3">Are you sure want to delete?</span>
                                                            <span class="text-center">Please becarefull when deleting it will cause system employee !</span>
                                                            <span class="text-center">make sure the role is not used.</span>
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

                                    <form action="{{ route("crm.pref.uac.role.post") }}" method="post">
                                        @component('layouts._crm_modal', [
                                            "modalId" => "modal_edit_$item->id",
                                            "modalSize" => "modal-lg"
                                        ])
                                            @slot('modalTitle')
                                                <div class="d-flex flex-column">
                                                    <span class="fs-1 fw-bold">Edit Role</span>
                                                    <span class="fs-base fw-normal mt-2">Edit role for KerjaKu apps.</span>
                                                </div>
                                            @endslot
                                            @slot('modalContent')
                                                <div class="fv-row">
                                                    <label class="col-form-label required">Role Name</label>
                                                    <input type="text" name="role_name" value="{{ old("role_name") ?? $item->name }}" placeholder="Input Role Name" class="form-control">
                                                    @error('role_name')
                                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="fv-row">
                                                    <label class="col-form-label required">Clone Role Permissions</label>
                                                    <select name="clone" class="form-select" data-control="select2" data-allow-clear="true" data-dropdown-parent="#modal_edit_{{ $item->id }}" data-placeholder="Select role" id="">
                                                        <option value=""></option>
                                                        @foreach ($data as $val)
                                                            <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
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
                                </div>
                            </div>
                            <div class="separator separator-solid w-100 mb-3"></div>
                            <div>
                                <a href="{{ route('crm.pref.uac.role.permission', $item->id) }}" class="btn btn-primary btn-sm">Role Permission</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endempty
        </div>
    </div>

    <form action="{{ route("crm.pref.uac.role.post") }}" method="post">
        @component('layouts._crm_modal', [
            "modalId" => "modal_add",
            "modalSize" => "modal-lg"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Add Role</span>
                    <span class="fs-base fw-normal mt-2">Adding new role for KerjaKu apps.</span>
                </div>
            @endslot
            @slot('modalContent')
                <div class="fv-row">
                    <label class="col-form-label required">Role Name</label>
                    <input type="text" name="role_name" value="{{ old("role_name") }}" placeholder="Input Role Name" class="form-control">
                    @error('role_name')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row">
                    <label class="col-form-label required">Clone Role Permissions</label>
                    <select name="clone" class="form-select" data-control="select2" data-dropdown-parent="#modal_add" data-allow-clear="true" data-placeholder="Select role" id="">
                        <option value=""></option>
                        @foreach ($data as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <span data-role-text></span>
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

        $("select[name=clone]").change(function(){
            var par = $(this).parents("div.fv-row")
            $(par).find("[data-role-text]").html("")
            if($(this).val() != ""){
                var el = "<span>You can directly edit the permissions for this role by clicking <a href='{{ route('crm.pref.uac.role.permission') }}/"+$(this).val()+"'>Role Permissions</a></span>"
                $(par).find("[data-role-text]").html(el)
            }
        })
    })
</script>
@endsection