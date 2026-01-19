@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <h3 class="card-title">Permission</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" onclick="modalReset()" data-bs-target="#kt_modal_1">
                        <i class="la la-plus"></i>
                        Add Roles
                    </button>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="row row-cols-12 row-cols-md-3">
                    @foreach ($roles as $item)
                        <div class="col px-3 my-5">
                            <div class="bg-primary p-5 rounded text-white text-center cursor-pointer" onclick="editPermission(this)" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-admin="{{ $item->is_admin }}">{{ $item->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('crm.pref.crm.permission.store') }}" method="post" id="form-role">
        @csrf
        @component('layouts._crm_modal')
            @slot('modalId')
                kt_modal_1
            @endslot
            @slot('modalTitle')
                Add Roles
            @endslot
            @slot('modalContent')
                <div class="fv-row">
                    <label for="role_name" class="col-form-label required">Roles Name</label>
                    <input type="text" class="form-control" name="role_name" required placeholder="Input name">
                </div>
                {{-- <div class="fv-row">
                    <label for="is_admin" class="col-form-label required">Admin?</label>
                    <select name="is_admin" class="form-select" data-control="select2" data-dropdown-parent="#kt_modal_1" data-placeholder="Yes">
                        <option value=""></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div> --}}
            @endslot
            @slot('modalFooter')
                <input type="hidden" name="id">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <!--begin::Actions-->
                <button id="kt_role_submit" type="submit" class="btn btn-primary">
                    <span class="indicator-label">
                        Next
                    </span>
                    <span class="indicator-progress">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <!--end::Actions-->
            @endslot
        @endcomponent
    </form>

    <form action="{{ route('crm.pref.crm.permission.store') }}" method="post" id="form-admin-role">
        @csrf
        @component('layouts._crm_modal')
            @slot('modalId')
                kt_modal_2
            @endslot
            @slot('modalSize')
                modal-lg
            @endslot
            @slot('modalTitle')
            <div class="d-flex flex-column">
                <span class="fw-bold">Admin Permission</span>
                <span>Setup permission for role(s)</span>
            </div>
            @endslot
            @slot('modalBg')

            @endslot
            @slot('modalPadding')
                px-0
            @endslot
            @slot('modalContent')
                <div class="d-flex flex-column">
                    <span class="mb-5">Permission</span>
                    <table class="table table-striped table-row-bordered">
                        <thead>
                            <tr>
                                <th>Field Name</th>
                                @foreach ($crmAdminAction as $item)
                                    <th>{{ ucwords($item) }}</th>
                                @endforeach
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($crmAdminModules as $key => $desc)
                                <tr>
                                    <td colspan="{{ count($crmAdminAction) + 2 }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold mb-2">{{ ucwords(str_replace("_", " ", $key)) }}</span>
                                                <span>{{ $desc }}</span>
                                            </div>
                                            <i class="fa fa-caret-down text-dark fs-2"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">
                                        <span class="ms-5">Permission</span>
                                    </td>
                                    @if ($key == "dashboard_access")
                                        <td align="right" colspan="{{ count($crmAdminAction) + 1 }}">
                                            @foreach ($crmAdminAction as $item)
                                                <input type="hidden" name="ck[{{ $key }}][]" value="{{ $item }}">
                                            @endforeach
                                            <span class="text-muted">Everything</span>
                                        </td>
                                    @else
                                        @foreach ($crmAdminAction as $item)
                                            <td>
                                                <input type="checkbox" name="ck[{{ $key }}][]" checked value="{{ $item }}">
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endslot
            @slot('modalFooter')
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <!--begin::Actions-->
                <input type="hidden" name="type" value="admin">
                <button id="kt_role_admin_submit" type="submit" class="btn btn-primary">
                    <span class="indicator-label">
                        Submit
                    </span>
                    <span class="indicator-progress">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <!--end::Actions-->
            @endslot
        @endcomponent
    </form>

    <form action="{{ route('crm.pref.crm.permission.store') }}" method="post" id="form-user-role">
        @csrf
        @component('layouts._crm_modal')
            @slot('modalId')
                kt_modal_3
            @endslot
            @slot('modalSize')
                modal-lg
            @endslot
            @slot('modalTitle')
            <div class="d-flex flex-column">
                <span class="fw-bold">User Permission</span>
                <span>Setup permission for role(s)</span>
            </div>
            @endslot
            @slot('modalBg')

            @endslot
            @slot('modalPadding')
                px-0
            @endslot
            @slot('modalContent')
                <div class="d-flex flex-column">
                    <span class="mb-5">Permission</span>
                    <table class="table table-row-bordered">
                        <thead>
                            <tr>
                                <th>Pipeline</th>
                                <th>View & Edit</th>
                                <th>View Only</th>
                                {{-- <th style="width: 5%"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pipeline as $key => $desc)
                                <tr class="bg-gray-100">
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">{{ ucwords(str_replace("_", " ", $desc->label)) }}</span>
                                            {{-- <i class="fa fa-caret-down text-dark fs-2"></i> --}}
                                        </div>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="ck[{{ $desc->id }}][view_edit]" value="1">
                                    </td>
                                    <td>
                                        <input type="checkbox" name="ck[{{ $desc->id }}][view_only]" value="1">
                                    </td>
                                </tr>
                                {{-- @foreach ($crmUserAction as $action)
                                    <tr>
                                        <td class="fw-bold">
                                            <span class="ms-5">{{ ucwords(str_replace("_", " ", $action)) }}</span>
                                        </td>
                                        @foreach ($crmUserPermission as $perm => $item)
                                            <td>
                                                <input type="radio" name="ck[{{ $desc }}][{{ $action }}][]" value="{{ $perm }}">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endslot
            @slot('modalFooter')
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <!--begin::Actions-->
                <input type="hidden" name="type" value="user">
                <button id="kt_role_user_submit" type="submit" class="btn btn-primary">
                    <span class="indicator-label">
                        Submit
                    </span>
                    <span class="indicator-progress">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <!--end::Actions-->
            @endslot
        @endcomponent
    </form>

    {{-- <div class="modal fade" tabindex="-1" id="kt_modal_1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header border-0 px-0">
                            <h3 class="card-title">Add Roles</h3>
                        </div>
                        <div class="card-body rounded bg-secondary-crm">
                            test
                        </div>
                    </div>
                </div>
                <div class="border-0 modal-footer mt-n10">
                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('view_script')
    <script>

        function modalReset(){
            var form = document.getElementById("form-role")
            $("#kt_modal_1 select[name=is_admin]").val("").trigger("change")
            $("#kt_modal_1 input[name=id]").val("")
            form.reset()
        }

        function editPermission(me){
            var id = $(me).data("id")
            var name = $(me).data("name")
            var admin = $(me).data("admin")

            $("#kt_modal_1").modal("show")
            $("#kt_modal_1 input[name=role_name]").val(name)
            $("#kt_modal_1 select[name=is_admin]").val(admin).trigger("change")
            $("#kt_modal_1 input[name=id]").val(id)
        }

        // Define form element
        const form = document.getElementById('form-role');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'role_name': {
                        validators: {
                            notEmpty: {
                                message: 'Roles name is required'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        const submitButton = document.getElementById("kt_role_submit")

        $("#kt_role_submit").click(function(e){
            // Validate form before submit
            e.preventDefault()
            if (validator) {
                validator.validate().then(function (status) {

                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;

                        $.ajax({
                            url : $(form).attr('action'),
                            type : "post",
                            dataType : "json",
                            data : {
                                _token : $(form).find("input[name=_token]").val(),
                                role_name : $(form).find("input[name=role_name]").val(),
                                is_admin : 0,
                                id : $(form).find("input[name=id]").val(),
                                type : "data"
                            }
                        }).then(function(resp){
                            // Remove loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;
                            if(resp.success){
                                // open next modal
                                var detail = resp.edit
                                var edit = [];
                                if(detail != null){
                                    edit = JSON.parse(detail.permission ?? "[]")
                                }
                                if(resp.is_admin){
                                    $("#kt_modal_2").modal("show")
                                    if (detail != null) {
                                        $("#form-admin-role input[type=checkbox]").prop("checked", false)
                                        var roleAdmin = edit.admin
                                        for (const key in roleAdmin) {
                                            if (Object.hasOwnProperty.call(roleAdmin, key)) {
                                                const element = roleAdmin[key];
                                                for (let i = 0; i < element.length; i++) {
                                                    const v = element[i];
                                                    $(`#form-admin-role input[type=checkbox][name='ck[${key}][]'][value='${v}']`).prop("checked", true)
                                                }
                                            }
                                        }
                                        // var roleUser = edit.user
                                        // for (const i in roleUser) {
                                        //     if (Object.hasOwnProperty.call(roleUser, i)) {
                                        //         const element = roleUser[i];
                                        //         for (const key in element) {
                                        //             if (Object.hasOwnProperty.call(element, key)) {
                                        //                 const v = element[key];
                                        //                 console.log(i,key,v[0])
                                        //                 $("#form-user-role").find(`input[type=radio][name='ck[${i}][${key}][]'][value=${v[0]}]`).prop('checked', true)
                                        //             }
                                        //         }
                                        //         // console.log(i, element)
                                        //         // $("#form-user-role").find("input[type=radio][value=3]").prop('checked', true)
                                        //     }
                                        // }
                                    } else {
                                        $(`#form-admin-role input[type=checkbox]`).prop("checked", true)
                                    }
                                } else {
                                    $("#kt_modal_3").modal("show")
                                    $("#form-user-role").find(`input[type=checkbox]`).prop("checked", false)
                                    if (detail != null) {
                                        var roleUser = edit.user
                                        console.log(roleUser)
                                        for (const i in roleUser) {
                                            if (Object.hasOwnProperty.call(roleUser, i)) {
                                                const element = roleUser[i];
                                                console.log(element)
                                                for (const key in element) {
                                                    if (Object.hasOwnProperty.call(element, key)) {
                                                        const v = element[key];
                                                        console.log(v, element, key)
                                                        $("#form-user-role").find(`input[type=checkbox][name='ck[${i}][${key}]'][value=${v[0]}]`).prop('checked', true)
                                                    }
                                                }
                                                // console.log(i, element)
                                                // $("#form-user-role").find("input[type=radio][value=3]").prop('checked', true)
                                            }
                                        }
                                    } else {
                                        $("#form-user-role").find("input[type=radio][value=3]").prop('checked', true)
                                    }
                                }
                            } else {
                                Swal.fire({
                                    text: resp.message,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        }).catch(function(e){
                            // Show popup confirmation
                            // Remove loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;

                            Swal.fire({
                                text: e.statusText,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        })
                    }
                });
            }
        })

        $("#kt_role_admin_submit").click(function(e){
            e.preventDefault()
            // Show loading indication
            $(this).attr('data-kt-indicator', 'on');

            // Disable button to avoid multiple click
            $(this).prop("disabled", true);

            var btn = $(this)

            var admin_form = $(this).parents("form")

            $.ajax({
                url : $(admin_form).attr('action'),
                type : "post",
                dataType : "json",
                data : new FormData($(admin_form)[0]),
                contentType: false,
                processData: false,
            }).then(function(resp){
                // Remove loading indication
                $(btn).removeAttr('data-kt-indicator');

                // Enable button
                $(btn).prop("disabled", false);
                if(resp.success){
                    $("#kt_modal_3").modal("show")
                    var detail = resp.edit
                    var edit = [];
                    if(detail != null){
                        edit = JSON.parse(detail.permission ?? "[]")
                    }
                    if (detail != null) {
                        var roleUser = edit.user
                        for (const i in roleUser) {
                            if (Object.hasOwnProperty.call(roleUser, i)) {
                                const element = roleUser[i];
                                for (const key in element) {
                                    if (Object.hasOwnProperty.call(element, key)) {
                                        const v = element[key];
                                        console.log(i,key,v[0])
                                        $("#form-user-role").find(`input[type=radio][name='ck[${i}][${key}][]'][value=${v[0]}]`).prop('checked', true)
                                    }
                                }
                                // console.log(i, element)
                                // $("#form-user-role").find("input[type=radio][value=3]").prop('checked', true)
                            }
                        }
                    } else {
                        $("#form-user-role").find("input[type=radio][value=3]").prop('checked', true)
                    }
                } else {
                    Swal.fire({
                        text: resp.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            }).catch(function(e){
                // Remove loading indication
                $(btn).removeAttr('data-kt-indicator');

                // Enable button
                $(btn).prop("disabled", false);

                Swal.fire({
                    text: e.statusText,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            })
        })

        $("#kt_role_user_submit").click(function(e){
            e.preventDefault()
            // Show loading indication
            $(this).attr('data-kt-indicator', 'on');

            // Disable button to avoid multiple click
            $(this).prop("disabled", true);

            var user_form = $(this).parents("form")

            var btn = $(this)

            $.ajax({
                url : $(user_form).attr('action'),
                type : "post",
                dataType : "json",
                data : new FormData($(user_form)[0]),
                contentType: false,
                processData: false,
            }).then(function(resp){
                /// Remove loading indication
                $(btn).removeAttr('data-kt-indicator');

                // Enable button
                $(btn).prop("disabled", false);
                if(resp.success){
                    location.reload()
                } else {
                    Swal.fire({
                        text: resp.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            }).catch(function(e){
                /// Remove loading indication
                $(btn).removeAttr('data-kt-indicator');

                // Enable button
                $(btn).prop("disabled", false);

                Swal.fire({
                    text: e.statusText,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            })
        })
    </script>
@endsection
