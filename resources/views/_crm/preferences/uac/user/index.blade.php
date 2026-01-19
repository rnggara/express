@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <h3 class="card-title">User</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
                        <i class="fi fi-rr-plus"></i>
                        Add User
                    </button>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="d-flex">
                            <div class="position-relative">
                                <input type="text" class="form-control ps-12" placeholder="Search user here" name="search_table">
                                <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                            </div>
                            <button type="button" class="btn btn-secondary ms-5">
                                <i class="fi fi-rr-filter"></i>
                                Filter
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary me-5">
                                <i class="fi fi-rr-folder-download"></i>
                                Export
                            </button>
                            <button type="button" class="btn btn-secondary">
                                <i class="fi fi-rr-folder-upload"></i>
                                Import
                            </button>
                        </div>
                    </div>
                    <table class="table bg-white" data-ordering="false" id="table-list">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Departement</th>
                                <th>Location</th>
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
                                    <td>
                                        <div class="d-flex">
                                            <div class="symbol symbol-40px">
                                                <div class="symbol-label bgi-size-contain" style="background-image: url({{ asset($item->user_img ?? "images/image_placeholder.png") }})"></div>
                                            </div>
                                            <div class="ms-3 d-flex flex-column">
                                                <span class="fw-bold">{{ $item->name }}</span>
                                                <span class="text-muted">{{ $item->emp->emp_id ?? "-" }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->uacrole->name ?? "-" }}</td>
                                    <td>{{ $item->uacdepartement->name ?? "-" }}</td>
                                    <td>{{ $item->uaclocation->name ?? "-" }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" checked disabled>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                            <i class="fa fa-ellipsis-vertical text-dark"></i>
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $item->id }}" class="menu-link px-3 text-danger">
                                                    Delete Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" onclick="modalEdit({{ $item->id }})" class="menu-link px-3">
                                                    Edit Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>

                                        <form action="{{ route("crm.pref.uac.user.post") }}" method="post">
                                            <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                                <span class="fw-bold fs-3">Are you sure want to delete?</span>
                                                                <span class="text-center">Please becarefull when deleting, it may will cause user setting!</span>
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route("crm.pref.uac.user.post") }}" method="post" enctype="multipart/form-data">
        @component('layouts._crm_modal', [
            "modalId" => "modal_add",
            "modalSize" => "modal-xl"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Add User</span>
                    <span class="fs-base fw-normal mt-2">Fill in the mandatory fields, and click invite. User gets email invitation sent to the email id mentioned. 
                        Once the invitation is accepted, the user becomes part of the organization.</span>
                </div>
            @endslot
            @slot('modalContent')
            <div class="d-flex align-items-center mb-5">
                <div class="form-check form-check-custom me-5">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" value="import" name="add_type"/>
                        From parent company
                    </label>
                </div>
                <div class="form-check form-check-custom">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" value="new"  checked name="add_type"/>
                        Add new User
                    </label>
                </div>
            </div>
            <div class="d-none" data-form="import">
                <div class="fv-row">
                    <label class="col-form-label">User ID*</label>
                    <select name="import_id" class="form-select" data-control="select2" data-dropdown-parent="#modal_add" data-placeholder="Select User ID" id="">
                        <option value=""></option>
                        @foreach ($user_parent as $item)
                            <option value="{{ $item->id }}" data-role="{{ $item->uac_role }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Full Name*</label>
                    <input type="text" name="import_name" class="form-control" disabled id="">
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Role</label>
                    <select name="import_role" class="form-select" disabled data-control="select2" data-dropdown-parent="#modal_add" data-placeholder="Select Role" id="">
                        <option value=""></option>
                        @foreach ($roles as $item)
                            <option value="{{ $item->id }}" {{ old("role") == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex" data-form="new">
                <div class="d-flex flex-column" data-toggle="imageInput">
                    <div class="w-300px img-wrapper h-250px rounded bgi-position-center bgi-no-repeat bgi-size-cover" style="background-image: url({{ asset("images/image_placeholder.png") }})"></div>
                    <span class="my-3 text-muted text-center">Maximum image size is 5 MB</span>
                    <label class="btn btn-primary">
                        Upload Photo
                        <input type="file" name="image" accept="image/*" class="d-none">
                    </label>
                </div>
                <div class="border" style=" margin-left: 12px; margin-right: 12px;"></div>
                <div class="flex-fill">
                    <div class="row">
                        {{-- <div class="col-6 fv-row">
                            <label class="col-form-label">User ID</label>
                            <input type="text" name="user_id" class="form-control" placeholder="Input Employee ID" value="{{ old("user_id") }}" id="">
                            @error('user_id')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div> --}}
                        <div class="col-6 fv-row">
                            <label class="col-form-label required">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Input name Employee" value="{{ old("name") }}" id="">
                            @error('name')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Departement</label>
                            <select name="departement" class="form-select" data-control="select2"  data-placeholder="Select Departement" id="">
                                <option value=""></option>
                                @foreach ($departements as $item)
                                    <option value="{{ $item->id }}" {{ old("departement") == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('departement')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Location</label>
                            <select name="location" class="form-select" data-control="select2" data-placeholder="Select location" id="">
                                <option value=""></option>
                                @foreach ($locations as $item)
                                    <option value="{{ $item->id }}" {{ old("location") == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('location')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label required">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Input employee email" value="{{ old("email") }}" id="">
                            @error('email')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label required">Role</label>
                            <select name="role" class="form-select" data-control="select2" data-placeholder="Select role" id="">
                                <option value=""></option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}" {{ old("role") == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label required">Password</label>
                            <input type="text" name="password" class="form-control" readonly placeholder="Generated Password" value="" id="">
                            @error('password')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-primary btn-sm" onclick="generatePassword(this)">Generate Password</button>
                            </div>
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Status</label>
                            <select name="status" class="form-select" data-control="select2" data-placeholder="Active" id="">
                                <option value="1">Active</option>
                                <option value="0">Non Active</option>
                            </select>
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Connect Employee</label>
                            <select name="emp_id" class="form-select" data-control="select2" data-placeholder="Select Employee" id="">
                                <option value=""></option>
                                @foreach ($personel as $item)
                                    <option value="{{ $item->id }}">{{ $item->emp_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.uac.user.post") }}" method="post" enctype="multipart/form-data">
        @component('layouts._crm_modal', [
            "modalId" => "modal_edit",
            "modalSize" => "modal-xl"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Edit User</span>
                </div>
            @endslot
            @slot('modalContent')
            <div data-content></div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="id">
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>
@endsection

@section('view_script')
<script>

    function modalEdit(id){
        $("#modal_edit div[data-content]").html("")
        $("#modal_edit input[name=id]").val(id)
        $.ajax({
            url : "{{ route("crm.pref.uac.user.index") }}?a=edit&id=" + id,
            type : "get",
            dataType : "json"
        }).then(function(resp){
            $("#modal_edit div[data-content]").html(resp.view)
            $("#modal_edit").modal("show")
            $("#modal_edit select[data-control=select2]").select2()
        })
    }

    function toggleInput(){
        $("div[data-toggle=imageInput]").each(function(){
            var input = $(this).find("input[type=file]")
            var wrapper = $(this).find("div.img-wrapper")
            $(input).off("change")
            $(input).change(function(){
                const file = this.files[0];
                let reader = new FileReader();
                reader.onload = function(event){
                    wrapper.css("background-image", "url("+event.target.result+")")
                    wrapper.css("background-size", "cover")
                }
                reader.readAsDataURL(file);
            })
        })
    }

    function generatePassword(btn){
        var input = $(btn).parents("div.fv-row").find("input[name=password]")
        var pw = makeid(8)

        $(input).val(pw)
    }

    function makeid(length) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < length) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
        counter += 1;
        }
        return result;
    }

    $(document).ready(function(){
        toggleInput()
        @if(Session::has("modal"))
            $("#{{ Session::get("modal") }}").modal("show")
        @endif

        var tb = initTable($("#table-list"))

        tb.on('draw', function(){
            toggleInput()
        })

        $("input[name=add_type]").click(function(){
            $("#modal_add [data-form]").addClass("d-none")
            $(`#modal_add [data-form="${$(this).val()}"]`).removeClass("d-none")
        })

        $('[data-form="import"] select[name=import_id]').change(function(){
            var parent = $(this).parents("div[data-form]")
            var opt = $(this).find("option:selected")
            $(parent).find('input[name=import_name]').prop("disabled", false)
            $(parent).find('input[name=import_name]').val($(opt).text())
            $(parent).find('select[name=import_role]').prop("disabled", false)
            $(parent).find('select[name=import_role]').val($(opt).data("role")).trigger("change")
        })
    })
</script>
@endsection
