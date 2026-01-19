@extends('_crm.preferences.index')

@section('view_content')
<div class="card">
    <div class="card-body">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between">
                <span class="fw-bold fs-3">User</span>
                <button type="button" class="btn btn-primary btn-add kt_drawer_example_basic_button" data-tab-parent="#kt_tab_user" onclick="show_detail()">
                    <i class="la la-user-plus"></i>
                    Add User
                </button>
                <button type="button" class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#modal_team_add" style="display: none" data-tab-parent="#kt_tab_team">
                    <i class="la la-clipboard-check"></i>
                    Add Team
                </button>
            </div>
        </div>
        <div class="d-flex align-items-center mb-5">
            <span class="text-muted">Preference <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
            <span class="fw-semibold">User</span>
        </div>
        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6 border-0">
            <li class="nav-item">
                <a class="nav-link px-10 active text-active-dark" data-bs-toggle="tab" href="#kt_tab_user">User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-10 text-active-dark" onclick="resetMember()" data-bs-toggle="tab" href="#kt_tab_team">Team</a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-10 text-active-dark" data-bs-toggle="tab" href="#kt_tab_job_title">Job Title</a>
            </li>
        </ul>
        <div class="card-body bg-secondary-crm rounded">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_user" role="tabpanel">
                    <table class="table display bg-white" id="table-user">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Role</th>
                                <th>Team</th>
                                <th>Pipeline</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $item)
                                @php
                                    $_team = $userTeam[$item->id] ?? [];
                                    $uTeam = $_team[0] ?? [];
                                @endphp
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->crmJobTitle->name ?? "-" }}</td>
                                    <td>{{ $item->crmRole->name ?? "-" }}</td>
                                    <td>
                                        @if (count($_team) == 0)
                                            -
                                        @else
                                            <div class="d-flex flex-column">
                                                @foreach ($_team as $tm)
                                                    <span>{{ $tm->name }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->dPipeline->label ?? "-" }}</td>
                                    <td align="center">
                                        <button type="button" onclick="show_detail({{ $item->id }})" class="btn p-0 kt_drawer_example_basic_button">
                                            <i class="fa fa-ellipsis-v text-dark"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="kt_tab_team" role="tabpanel">
                    <table class="table display bg-white" id="table-team">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Number of Team Members</th>
                                <th>Pipeline</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teams as $item)
                                @php
                                    $pipe = json_decode($item->pipeline_id ?? "[]", true);
                                @endphp
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td align="center">{{ $users->whereIn("id", $item->members ?? [])->count() ?? 0 }}</td>
                                    <td>
                                        @foreach ($pipe as $pId)
                                            @if (isset($pipe_name[$pId]))
                                                <span>{{ $pipe_name[$pId] }}, </span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                            <i class="fa fa-ellipsis-vertical text-dark"></i>
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" onclick="detailTeam({{ $item->id }})" data-name="{{ $item->name }}" data-pipeline_id="{{ $item->pipeline_id }}" data-id="{{ $item->id }}" class="menu-link px-3">
                                                    Edit
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" onclick="archive(this)" data-url="{{ route('crm.pref.crm.user.archive', ['type' => 'team', 'id' => $item->id]) }}" data-label="{{ $item->name }}" class="menu-link px-3 text-danger">
                                                    Archive
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="kt_tab_job_title" role="tabpanel">
                    <div class="d-flex flex-column">
                        <form action="" method="post" id="form-job-title">
                            @csrf
                            <div class="d-flex align-items-center">
                                <div class="fv-row me-md-5">
                                    <label for="job_title" class="col-form-label">Job Title Name</label>
                                    <input type="text" class="form-control" name="job_title" placeholder="Input job title name">
                                </div>
                                <div class="fv-row me-md-5">
                                    <label for="parent_id" class="col-form-label">Parent ID</label>
                                    <select name="parent_id" id="parent_id" class="form-select" data-placeholder="Select Parent ID"></select>
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="fv-row me-md-5">
                                    <label for="" class="col-form-label w-100">&nbsp;</label>
                                    <button id="btn-input-job-title" type="submit" class="btn btn-primary">
                                        <span class="indicator-label">
                                            Add
                                        </span>
                                        <span class="indicator-progress">
                                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                            </div>
                        </form>
                        <div class="bg-white rounded p-2">
                            <div id="kt_docs_jstree_ajax"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{route("crm.pref.crm.user.store", 'user')}}" method="post">
    @csrf
    @component('layouts._crm_modal')
        @slot('modalId')
            modal_user_add
        @endslot
        @slot('modalTitle')
            Add User
        @endslot
        @slot('modalContent')
            <div class="fv-row" id="sel-user">
                <label for="team_user" class="col-form-label required">Add User</label>
                <select name="team_user_sel" class="form-select" data-dropdown-parent="#sel-user" id="team_user" data-placeholder="Input user (search by name)">
                    <option value=""></option>
                    @if ($emp->count() > 0)
                    <option value="_all" data-email="Select All Employee">Select All</option>
                    @endif
                    @foreach ($emp as $item)
                        <option value="{{ $item->id }}" data-email="{{ $item->email }}">{{ $item->emp_name }}</option>
                    @endforeach
                </select>
                <div id="user-list" class="mt-3">
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
            <button type="submit" id="btn-add-user" class="btn btn-primary">Submit</button>
        @endslot
    @endcomponent
</form>

<form action="{{route("crm.pref.crm.user.store", 'team')}}" method="post">
    @csrf
    @component('layouts._crm_modal')
        @slot('modalId')
            modal_team_add
        @endslot
        @slot('modalTitle')
            Add Team
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label for="team_name" class="col-form-label required">Team Name</label>
                <input type="text" class="form-control" name="team_name" required placeholder="Input name">
            </div>
            <div class="fv-row" id="sel-pipeline">
                <label for="pipeline_id" class="col-form-label required">Pipeline</label>
                <select name="pipeline_id[]" multiple class="form-select" data-control="select2" data-dropdown-parent="#sel-pipeline" id="pipeline_id" data-placeholder="Select pipeline">
                    <option value=""></option>
                    @foreach ($pipelines as $item)
                        <option value="{{ $item->id }}">{{ $item->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row" id="sel-member">
                <label for="team_member" class="col-form-label required">Add Member</label>
                <select name="team_member_sel" class="form-select" data-dropdown-parent="#sel-member" id="team_member" data-placeholder="Input member (search by name or job title)">
                    <option value=""></option>
                    <option value="_all" data-job="Select All User">Select All</option>
                    @foreach ($users as $item)
                        <option value="{{ $item->id }}"
                            data-email="{{ $item->email }}"
                            data-company="{{ $item->company->company_name }}"
                            data-phone="{{ $item->phone ?? "-" }}"
                            data-job="{{ $item->crmRole->name ?? "-" }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <div id="member-list" class="mt-3">
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        @endslot
    @endcomponent
</form>

<form action="{{route("crm.pref.crm.user.store", 'team')}}" method="post">
    @csrf
    @component('layouts._crm_modal')
        @slot('modalId')
            modal_team_edit
        @endslot
        @slot('modalTitle')
            Edit Team
        @endslot
        @slot('modalContent')
            <div id="team-edit"></div>
        @endslot
        @slot('modalFooter')
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
            <button type="submit" class="btn btn-primary">Edit</button>
        @endslot
    @endcomponent
</form>

<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3">Are you sure want to archive?</span>
                    <span class="text-center">Are you sure you want to archive <span id="archive-label"></span>?</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="" id="archive-url" class="btn btn-white">Yes</a>
                        <button class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@component('layouts.components.modal_address')

@endcomponent

<!--begin::View component-->
<div
    id="kt_drawer_example_basic"

    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle=".kt_drawer_example_basic_button"
    data-kt-drawer-close="#kt_drawer_example_basic_close"
    data-kt-drawer-width="500px"
    >
    <div class="card w-100 rounded-0" id="drawer-content">

    </div>
</div>
<!--end::View component-->

<!--begin::Toast-->
<div class="end-0 mt-20 p-3 position-fixed pt-10 top-0" style="top: 90%; z-index: 999;">
    <div id="toast_validator" class="toast text-white" role="alert" aria-live="assertive" aria-atomic="true">
        {{-- <div class="toast-header">
            <i class="ki-duotone ki-abstract-19 fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
            <strong class="me-auto">Keenthemes</strong>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div> --}}
        <div class="toast-body py-5">
            <div class="d-flex justify-content-between align-items-center">
                <span class="toast-message"></span>
                {{-- <button type="button" class="btn-close btn-white" data-bs-dismiss="toast" aria-label="Close"></button> --}}
            </div>
        </div>
    </div>
</div>
<!--end::Toast-->

@endsection

@section('view_script')
    <link href="{{ asset("theme/assets/plugins/custom/jstree/jstree.bundle.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("theme/assets/plugins/custom/jstree/jstree.bundle.js") }}"></script>
    <script>

        function edit_team(me){
            $("#modal_team_edit").modal("show")
            $("#modal_team_edit input[name=team_name]").val($(me).data("name"))
            $("#modal_team_edit select[name=pipeline_id]").val($(me).data("pipeline_id")).trigger('change')
            $("#modal_team_edit input[name=id]").val($(me).data("id"))

        }

        function archive(me){
            $("#modalDelete").modal("show")
            $("#modalDelete #archive-url").attr("href", $(me).data("url"))
            $("#modalDelete #archive-label").text($(me).data("label"))
        }

        var drawerElement = document.querySelector("#kt_drawer_example_basic");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");

        var blockUI = new KTBlockUI(target);

        function removeDet(me){
            $(me).parent().remove()
        }

        function show_detail(id = null){
            $("#drawer-content").html("")
            blockUI.block();
            $.ajax({
                url : `{{ route("crm.pref.crm.user.detail") }}/${id}`,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release()
                $("#drawer-content").html(resp.view)
                $("#drawer-content #kt_accordion_1_header_1").click(function(){
                    if($(this).find('.accordion-button').hasClass("collapsed") == false) {
                        $("#drawer-content [data-accordion=collapsed]").prev().hide()
                        $("#drawer-content [data-accordion=collapsed]").show()
                    } else {
                        $("#drawer-content [data-accordion=collapsed]").prev().show()
                        $("#drawer-content [data-accordion=collapsed]").hide()
                    }
                })

                KTImageInput.createInstances();

                $("#drawer-content [data-control=select2]").select2()

                Inputmask({
                    "mask" : "999999999999"
                }).mask("#drawer-content .phone-number");

                $("#d-add-email").click(function(){
                    var email = $(this).parents("form").find("input[name=email_input]")
                    if(email.val() != ""){
                        var el = "<div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>"
                            el += `<div class="d-flex align-items-center">
                                <i class="la la-mail-bulk me-1 text-primary"></i>
                                <span>${email.val()}</span>
                            </div>`
                            el += `<input type='hidden' name='email[]' value="${email.val()}">`
                            el += `<button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>`
                            el += "</div>"

                        $("#d-email-user").append($(el))
                        email.val("")
                    }
                })
                $("#d-add-phone").click(function(){
                    var phone = $(this).parents("form").find("input[name=phone_number]")
                    var phone_type = $(this).parents("form").find("select[name=phone_type]").val()
                    if(phone.val() != ""){
                        if(phone_type == ""){
                            phone_type = "Work"
                        }
                        var el = "<div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>"
                            el += `<div class="d-flex align-items-center">
                                <i class="la la-phone me-1 text-primary"></i>
                                <span>${phone_type} : ${phone.val()}</span>
                            </div>`
                            el += `<input type='hidden' name='phone[]' value="${phone.val()}">`
                            el += `<input type='hidden' name='phone_types[]' value="${phone_type}">`
                            el += `<button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>`
                            el += "</div>"

                        $("#d-phone-user").append($(el))
                        phone.val("")
                        $(this).parents("form").find("select[name=phone_type]").val("").trigger('change')
                    }
                })
            })
        }

        $("[data-bs-toggle=tab]").click(function(){
            $(".btn-add").hide()
            var href = $(this).attr("href")
            $(`[data-tab-parent='${href}']`).show()
        })

        var member = []

        function resetMember(){
            member = []
        }

        function detailTeam(id){
            $("#team-edit").html("")
            $.ajax({
                url : "{{route("crm.pref.crm.user.edit")}}/team/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#modal_team_edit").modal("show")
                $("#team-edit").html(resp.view)
                selectMember("#team_member_edit", "#member-list-edit")
                member = resp.member
                showTeamMember("#member-list-edit")
                $("#modal_team_edit [data-control=select2]").select2()
            })
        }

        function showTeamMember(target){
            if(member.length > 0){
                var el = ""
                for (let i = 0; i < member.length; i++) {
                    const element = member[i];
                    var cm = member.length > 1 && i != member.length - 1 ? "," : ""
                    var tooltip = "<div class='d-flex flex-column align-items-center'>"
                        tooltip += `<span class='fw-semibold'>${element['name']}</span>`
                        tooltip += `<span class='fw-semibold'>${element['email']}</span>`
                        tooltip += `<span class='fw-semibold text-primary'>${element['company']}</span>`
                        tooltip += `<span class='fw-semibold'>${element['phone']}</span>`
                        tooltip += `<span class='fw-semibold'>${element['email']}</span>`
                        tooltip += "</div>"
                    el += "<div class='d-flex align-items-center me-2'>"
                    el += `<span class="btn btn-link text-dark p-0" data-bs-placement="top" data-bs-toggle="tooltip" data-bs-html="true" title="${tooltip}">${element['name']}${cm}</span>`
                    el += `<input type="hidden" name="team_member[]" value="${element['id']}">`
                    el += "</div>"
                }
                $(target).html(`<div class='d-flex flex-wrap'>${el}</div>`)
                $('[data-bs-toggle="tooltip"]').tooltip()
            } else {
                $(target).html("")
            }
        }

        const userFormat = (item) => {
            if (!item.id) {
                return item.text;
            }

            var val = item.element.value

            var selected = $(item.element).prop("selected")

            var checked = selected ? "checked" : ""

            var span = document.createElement('span');
            var template = '';
            template += '<div class="d-flex align-items-center">'
            if(val != ""){
                template += '<div class="form-check">'
                template += '<input class="form-check-input" data-member-ck='+val+' type="checkbox" value="" />'
                template += '<label class="form-check-label" for=""></label>'
                template += '</div>'
            }
            template += '<div class="d-flex flex-column">'
            template += '<span class="fw-semibold">' + item.text + '</span>';
            template += '<span class="text-muted">' + item.element.getAttribute('data-job') + '</span>';
            template += '</div>';
            template += '</div>';

            span.innerHTML = template;

            return $(span);
        }

        const empFormat = (item) => {
            if (!item.id) {
                return item.text;
            }

            var span = document.createElement('span');
            var template = '';
            template += '<div class="d-flex flex-column">'
            template += '<span class="fw-semibold">' + item.text + '</span>';
            template += '<span class="text-muted">' + item.element.getAttribute('data-email') + '</span>';
            template += '</div>';

            span.innerHTML = template;

            return $(span);
        }

        selectMember("#team_member", "#member-list")

        function removeMember(me){
            var div = $(me).parent()
            var inp = $(div).find("input[name='team_member[]']").val()

            const index = member.findIndex(x => parseInt(x.id) === parseInt(inp));

            console.log(index, member, inp)

            if (index > -1) { // only splice array when item is found
                member.splice(index, 1); // 2nd parameter means remove one item only
            }

            var form = $(me).parents("form")

            $(form).find(`select[name=team_member_sel] option[value=${inp}]`).prop("disabled", false)
            var modal = $(me).parents("div.modal")
            var id = $(modal).attr("id")
            if(id == "modal_team_add"){
                showTeamMember("#member-list")
            } else {
                showTeamMember("#member-list-edit")
            }
        }

        function selectMember(me, target){
            $(me).select2({
                // minimumResultsForSearch: Infinity,
                // templateSelection: userFormat,
                templateResult: userFormat
            }).change(function(){
                if($(this).val() != ""){
                    if($(this).val() != "_all"){
                        var opt = $(this).find("option:selected")
                        var id = $(opt).val()
                        var index = member.findIndex(x => parseInt(x.id) === parseInt(id));
                        if (index > -1) { // only splice array when item is found
                            member.splice(index, 1); // 2nd parameter means remove one item only
                        } else {
                            var col = {}
                            col['id'] = $(opt).val()
                            col['name'] = $(opt).text()
                            col['email'] = $(opt).data("email")
                            col['company'] = $(opt).data("company")
                            col['phone'] = $(opt).data("phone")
                            member.push(col)
                        }
                        showTeamMember(target)
                        $(this).val("").trigger("change")
                    } else {
                        member = []
                        showTeamMember(target)
                    }
                }
            }).on("select2:open", async function (e) {
                await new Promise(function(){
                    window.setTimeout(function(){
                        var f = $(me).parents(".fv-row")
                        for (let i = 0; i < member.length; i++) {
                            const element = member[i];
                            $(f).find('input[data-member-ck='+element['id']+']').prop("checked", true)
                        }
                    }, 0)
                });
            })

            var f = $(me).parents(".fv-row")
            var $searchfield = $(f).find('.select2-search__field');
            $(f).on("keydown", ".select2-search__field", async function(){
                await new Promise(function(){
                    window.setTimeout(function(){
                        for (let i = 0; i < member.length; i++) {
                            const element = member[i];
                            $(f).find('input[data-member-ck='+element['id']+']').prop("checked", true)
                        }
                    }, 0)
                });
            })
        }

        function removeMe(me) {
            var val = $(me).data("id")

            $(me).parent().remove()
            $(`#team_user option[value=${val}]`).prop("disabled", false)
        }

        $("#team_user").select2({
            // minimumResultsForSearch: Infinity,
            templateSelection: empFormat,
            templateResult: empFormat
        }).change(function(){
            var opt = $(this).find("option:selected")

            if($(this).val() != "" && $(this).val() != "_all"){
                var el = "<div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>"
                    el += `<span>${opt.text()}</span>`
                    el += `<input type='hidden' name='emp[]' value="${$(this).val()}">`
                    el += `<button type="button" onclick='removeMe(this)' data-id="${$(this).val()}" class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>`
                    el += "</div>"

                $(opt).prop("disabled", true)
                $(this).val("").trigger("change")

                $("#user-list").append($(el))
            }

            if($(this).val() == "_all"){
                $("#user-list").html("")
                $(this).find("option").prop("disabled", false)
            }
        })

        $("#btn-add-user").click(function(e){
            e.preventDefault()

            var val = $("#team_user").val()
            var form = $(this).parents("form")
            var users = $(form).find("input[name='emp[]']")
            if(val == ""){
                if(users.length == 0){
                    return false
                }
            }

            $(form).submit()
        })

        $("#parent_id").select2({
            ajax : {
                url : "{{ route("crm.pref.crm.user.get_job_title", 'select') }}",
                type : "get",
                dataType : "json"
            }
        })

        // Define form element
        const form_job_title = document.getElementById('form-job-title');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator_job_title = FormValidation.formValidation(
            form_job_title,
            {
                fields: {
                    'job_title': {
                        validators: {
                            notEmpty: {
                                message: 'Job title is required'
                            },
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // bootstrap: new FormValidation.plugins.Bootstrap5({
                    //     rowSelector: '.fv-row',
                    //     eleInvalidClass: '',
                    //     eleValidClass: ''
                    // }),
                    message : new FormValidation.plugins.Message({
                        clazz: "d-none",
                        // container: function(field, element) {
                        //     // field is the field name
                        //     // element is the field element

                        //     // Returns an element that will be used as message container
                        // }
                    })
                }
            }
        );

        function showToast(message){
            // / Select elements
            const toastElement = document.getElementById('toast_validator');

            const toast = bootstrap.Toast.getOrCreateInstance(toastElement);

            $(toastElement).find(".toast-message").text(message)
            $(toastElement).addClass('bg-danger')

            toast.show();
        }

        $("#btn-input-job-title").click(function(e){
            e.preventDefault()

            var btn = $(this)

            if($("input[name=job_title]").val() == ""){
                showToast("Job title is required")
            }

            validator_job_title.validate().then(function(status){
                if(status == "Valid"){
                    // Show loading indication
                    $(btn).attr('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    $(btn).prop("disabled", true)
                    var form = $(btn).parents("form")

                    var name = $(form).find("input[name=job_title]")
                    var parent_id = $(form).find("select[name=parent_id]")
                    var token = $(form).find("input[type=hidden][name='_token']")
                    $.ajax({
                        url : "{{ route("crm.pref.crm.user.store", 'job-title') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : token.val(),
                            name : name.val(),
                            parent_id : parent_id.val()
                        }
                    }).then(function(resp){
                        form_job_title.reset()
                        $("#parent_id").val("").trigger("change")
                        // Remove loading indication
                        $(btn).removeAttr('data-kt-indicator');

                        // Enable button
                        $(btn).prop("disabled", false)

                        $('#kt_docs_jstree_ajax').jstree(true).refresh();
                    })
                }
            })
        })

        function customMenu(node) {
            // The default set of all items
            var tree =  $("#kt_docs_jstree_ajax").jstree(true)
            var items = {
                renameItem: { // The "rename" menu item
                    label: "Edit",
                    action: function (obj) {
                        tree.edit(node);
                    }
                },
                deleteItem: { // The "delete" menu item
                    label: "Delete",
                    action: function (obj) {
                        $.ajax({
                            url : "{{ route("crm.pref.crm.user.delete_job_title") }}/" + node.id,
                            type : "get"
                        }).then(function(resp){
                            tree.refresh();
                        })
                    }
                }
            };

            if ($(node).hasClass("folder")) {
                // Delete the "delete" menu item
                delete items.deleteItem;
            }

            return items;
        }

        function job_title_tree(){
            $("#kt_docs_jstree_ajax").jstree({
                "core": {
                    "themes": {
                        "responsive": false,
                        "icons" : false
                    },
                    // so that create works
                    "check_callback": true,
                    'data': {
                        'url': function(node) {
                            return '{{ route("crm.pref.crm.user.get_job_title", 'tree') }}'; // Demo API endpoint -- Replace this URL with your set endpoint
                        },
                        'data': function(node) {
                            return {
                                'parent': node.id
                            };
                        }
                    }
                },
                "state": {
                    "key": "demo3"
                },
                "plugins": ["dnd", "state", "types", 'contextmenu'],
                "contextmenu": {items: customMenu}
            });

            $('#kt_docs_jstree_ajax').on('rename_node.jstree', function (e, data) {
                //data.text is the new name:
                //MAKE AJAX CALL HERE
                $.ajax({
                    url : "{{ route('crm.pref.crm.user.edit_job_title') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : data.node.id,
                        text : data.text,
                        type : "name"
                    }
                })
            });

            $('#kt_docs_jstree_ajax').on('move_node.jstree', function (e, data) {
                //data.text is the new name:
                //MAKE AJAX CALL HERE
                $.ajax({
                    url : "{{ route('crm.pref.crm.user.edit_job_title') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : data.node.id,
                        parent : data.parent,
                        type : "parent"
                    }
                })
            });

            // $("#kt_docs_jstree_ajax").on("ready.jstree", function(e, data){
            //     $("#kt_docs_jstree_ajax .jstree-anchor").each(function(){
            //         $(this).addClass("bg-secondary rounded px-2")
            //     })
            // })
        }

        job_title_tree()
    </script>
@endsection
