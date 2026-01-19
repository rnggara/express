@extends('_crm.preferences.index')

@section('view_content')
    <div class="card shadow-none">
        <div class="card-header border-0">
            <h3 class="card-title">Role Permissions</h3>
        </div>
        <div class="card-body bg-secondary-crm rounded">
            <div class="d-flex flex-column">
                <div class="fv-row w-75">
                    <label class="col-form-label">Role Name</label>
                    <input type="text" name="role_name" value="{{ $data->name }}" readonly>
                </div>
            </div>
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