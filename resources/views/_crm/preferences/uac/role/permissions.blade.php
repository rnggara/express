@extends('_crm.preferences.index')

@section('view_content')
    <div class="card shadow-none">
        <div class="card-header border-0">
            <h3 class="card-title">Role Permissions</h3>
        </div>
        <div class="card-body bg-secondary-crm rounded">
            <div class="d-flex flex-column">
                <div class="fv-row w-50 mb-10">
                    <label class="col-form-label">Role Name</label>
                    <input type="text" name="role_name" class="form-control" value="{{ $data->name }}" readonly>
                </div>
                <div class="d-flex mb-5">
                    <div class="rounded d-flex flex-column bg-white min-w-250px align-items-center pb-5">
                        <div class="bg-primary rounded-top p-3 w-100">
                            <span class="text-white">Departement</span>
                        </div>
                        <div class="h-200px scroll d-flex flex-column p-3 w-100">
                            @if (in_array("_all", $data->departements ?? []))
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="p-3 bg-secondary rounded">All Departement</span>
                                    <a href="{{ route('crm.pref.uac.role.permission_remove', [
                                        "type" => "departements",
                                        "id" => $data->id,
                                        "key" => 0
                                    ]) }}" class="btn btn-icon btn-sm btn-light-danger">
                                        <i class="fi fi-rr-trash"></i>
                                    </a>
                                </div>
                            @else
                                @foreach ($data->departements ?? [] as $key => $item)
                                    @if (isset($dept_name[$item]))
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="p-3 bg-secondary rounded">{{ $dept_name[$item] }}</span>
                                        <a href="{{ route('crm.pref.uac.role.permission_remove', [
                                            "type" => "departements",
                                            "id" => $data->id,
                                            "key" => $key
                                        ]) }}" class="btn btn-icon btn-sm btn-light-danger">
                                            <i class="fi fi-rr-trash"></i>
                                        </a>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div>
                            <button type="button" data-bs-toggle="modal"d data-bs-target="#modal_departement" class="btn border bg-hover-secondary border-dashed">
                                Add Departement
                            </button>
                        </div>
                    </div>
                    <div class="mx-5"></div>
                    <div class="rounded d-flex flex-column bg-white min-w-250px align-items-center pb-5">
                        <div class="bg-primary rounded-top p-3 w-100">
                            <span class="text-white">Location</span>
                        </div>
                        <div class="h-200px scroll d-flex flex-column p-3 w-100">
                            @if (in_array("_all", $data->locations ?? []))
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="p-3 bg-secondary rounded">All Location</span>
                                    <a href="{{ route('crm.pref.uac.role.permission_remove', [
                                        "type" => "locations",
                                        "id" => $data->id,
                                        "key" => 0
                                    ]) }}" class="btn btn-icon btn-sm btn-light-danger">
                                        <i class="fi fi-rr-trash"></i>
                                    </a>
                                </div>
                            @else
                                @foreach ($data->locations ?? [] as $key => $item)
                                    @if (isset($loc_name[$item]))
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="p-3 bg-secondary rounded">{{ $loc_name[$item] }}</span>
                                            <a href="{{ route('crm.pref.uac.role.permission_remove', [
                                                "type" => "locations",
                                                "id" => $data->id,
                                                "key" => $key
                                            ]) }}" class="btn btn-icon btn-sm btn-light-danger">
                                                <i class="fi fi-rr-trash"></i>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div>
                            <button type="button" data-bs-toggle="modal"d data-bs-target="#modal_location" class="btn border bg-hover-secondary border-dashed">
                                Add Location
                            </button>
                        </div>
                    </div>
                </div>
                <table class="w-50">
                    <tr>
                        <th colspan="3">KerjaKu Modul</th>
                    </tr>
                    @foreach ($perm as $item => $val)
                        @php
                            $pdata = $data->permissions ?? [];
                            $_perm = $pdata[$item] ?? [];
                            
                            $checkedDefault = "";
                            $checkedCustom = "";
                            if(!empty($_perm)){
                                if($_perm['type'] == "default" && $_perm['enable']){
                                    $checkedDefault = "checked";
                                }

                                if($_perm['type'] == "custom" && $_perm['enable']){
                                    $checkedCustom = "checked";
                                }
                            }
                        @endphp
                        <tr>
                            <td class="pb-3">
                                <span class="fw-semibold">{{ ucwords(str_replace("_", " ", $item)) }}</span>
                            </td>
                            <td class="pb-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        Default
                                        <input class="form-check-input" onclick="updatePermission(this)" {{ $checkedDefault }} data-name="{{ $item }}" data-type="default" type="checkbox" value=""/>
                                    </label>
                                </div>
                            </td>
                            <td class="pb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            Custom
                                            <input class="form-check-input" onclick="updatePermission(this)" {{ $checkedCustom }} data-name="{{ $item }}" data-type="custom" type="checkbox" value=""/>
                                        </label>
                                    </div>
                                    @if ($checkedCustom != "")
                                        <a href="javascript:;" onclick="updatePermission(this, 'view')" data-che data-name="{{ $item }}" data-type="custom" class="ms-3">Lihat Detail</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    <form action="{{ route("crm.pref.uac.role.permission_update") }}" method="post">
        @component('layouts._crm_modal', [
            "modalId" => "modal_departement",
            "modalSize" => "modal-lg",
            "modalBg" => "bg-white px-0"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Departement</span>
                    <span class="fs-base fw-normal mt-2">Select which “Departement” Field can do on this Roles</span>
                </div>
            @endslot
            @slot('modalContent')
                <div class="d-flex flex-column">
                    <div class="position-relative mb-5">
                        <input type="text" name="search_table" class="form-control ps-13" placeholder="Search" id="">
                        <span class="fi fi-rr-search fs-3 ms-5 position-absolute text-muted top-25"></span>
                    </div>
                    <table class="table" id="table-dept" data-ordering="false">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="all" value="_all"/>
                                    </div>
                                </th>
                                <th>Departement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($depts->whereNotIn("id", $data->departements) as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $item->id }}"/>
                                        </div>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="id_dept">
                <input type="hidden" name="type" value="departements">
                <input type="hidden" name="id" value="{{ $data->id }}">
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.uac.role.permission_update") }}" id="form-loc" method="post">
        @component('layouts._crm_modal', [
            "modalId" => "modal_location",
            "modalSize" => "modal-lg",
            "modalBg" => "bg-white px-0"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Location</span>
                    <span class="fs-base fw-normal mt-2">Select which “Location” Field can do on this Roles</span>
                </div>
            @endslot
            @slot('modalContent')
                <div class="d-flex flex-column">
                    <div class="position-relative mb-5">
                        <input type="text" name="search_table" class="form-control ps-13" placeholder="Search" id="">
                        <span class="fi fi-rr-search fs-3 ms-5 position-absolute text-muted top-25"></span>
                    </div>
                    <table class="table" id="table-loc" data-ordering="false">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="all" value="_all"/>
                                    </div>
                                </th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locations->whereNotIn("id", $data->locations) as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $item->id }}"/>
                                        </div>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="id_locs">
                <input type="hidden" name="type" value="location">
                <input type="hidden" name="id" value="{{ $data->id }}">
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.uac.role.permission_update") }}" id="form-loc" method="post">
        @component('layouts._crm_modal', [
            "modalId" => "modal_custom",
            "modalSize" => "modal-lg",
            "modalBg" => "bg-white px-0"
        ])
            @slot('modalTitle')
            @endslot
            @slot('modalContent')
                
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="permission">
                <input type="hidden" name="id" value="{{ $data->id }}">
                <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
                <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>
@endsection

@section('view_script')
<script>

    function updatePermission(me, view = null){
        var name = $(me).data("name")
        var type = $(me).data('type')
        if(type == "default"){
            $(`input[data-name='${name}'][data-type='custom']`).prop("checked", false)
        } else {
            $(`input[data-name='${name}'][data-type='default']`).prop("checked", false)
        }

        $("#modal_custom .modal-body").html("")

        $.ajax({
            url : "{{ route("crm.pref.uac.role.permission", $data->id) }}?a=role",
            type : "get",
            dataType : "json",
            data : {
                checked : $(me).prop("checked"),
                type : type,
                name : name,
                view : view
            }
        }).then(function(resp){
            if(type == "default"){
                showToast("Permission updated", "bg-success")
                var tr = $(me).parents("tr")
                $(tr).find("[data-che]").remove()
            } else {
                if($(me).prop("checked") || view != null){
                    $("#modal_custom .modal-body").html(resp.view)
                    // initTable($("#table-perm"))
                    // $("#table-perm").DataTable()
                    $("#modal_custom").modal("show")

                    $("#modal_custom input[data-action]").each(function(){
                        var td = $(this).parents("td")
                        var ck = $(this)
                        $(td).click(function(){
                            $(ck).prop("checked", $(ck).prop("checked"))
                        })
                    })

                    $("#modal_custom input[data-action=view]").click(function(){
                        var checked = $(this).prop("checked")
                        var tr = $(this).parents("tr")
                        $(tr).find("input[data-action]").each(function(){
                            var action = $(this).data("action")
                            if(action != "view"){
                                if(checked){
                                    $(this).prop("disabled", false)
                                    $(this).parents(".form-check").removeClass("form-check-solid")
                                } else {
                                    $(this).prop("disabled", true)
                                    $(this).prop("checked", false)
                                    $(this).parents(".form-check").addClass("form-check-solid")
                                }
                            }
                        })
                    })

                    $("#modal_custom button[data-bs-dismiss]").click(function(){
                        $(me).prop("checked", false)
                    })
                } else {
                    showToast("Permission updated", "bg-success")
                }
            }
        })
    }

    function tableFn(id, table){
        $(`${id} tbody tr td:first-child`).off("click")
        $(`${id} tbody tr td:first-child`).click(function(){
            var tr = $(this).parents("tr")
            if($(tr).hasClass("selected")){
                table.row(tr).deselect()
                $(this).find(".form-check-input").prop('checked', false)
            } else {
                table.row(tr).select()
                $(this).find(".form-check-input").prop('checked', true)
            }

            var all_row = table.rows()[0].length
            var selected_row = table.rows({selected: true})[0].length
            if(selected_row != all_row){
                $(`${id} thead input[type=checkbox]`).prop("checked", false)
            }
        })

        $(`${id} thead input[type=checkbox]`).click(function(){
            var checked = this.checked
            if(checked){
                table.rows().select()
                $(`${id} tbody`).find(".form-check-input").prop('checked', true)
            } else {
                table.rows().deselect()
                $(`${id} tbody`).find(".form-check-input").prop('checked', false)
            }
        })

        $(`${id} tbody tr`).each(function(){
            if($(this).hasClass("selected")){
                $(this).find(".form-check-input").prop('checked', true)
            } else {
                $(this).find(".form-check-input").prop('checked', false)
            }
        })
    }

    $(document).ready(function(){
        @if(Session::has("modal"))
            $("#{{ Session::get("modal") }}").modal("show")
        @endif

        var tb_loc = initTable($("#table-loc"))
        var tb_dept = initTable($("#table-dept"))

        tb_loc.select();
        tb_dept.select();

        tableFn("#table-loc", tb_loc)
        tableFn("#table-dept", tb_dept)

        tb_dept.on("init draw", function(){
            tableFn("#table-dept", tb_dept)
        })

        tb_loc.on("init draw", function(){
            tableFn("#table-loc", tb_loc)
        })

        $("#modal_location button[type=submit]").click(function(e){
            // e.preventDefault()
            var selected = tb_loc.rows({selected: true})
            var data = selected.data()
            var count = data.length
            var locs = []
            var form = $(this).parents("form")
            for (let i = 0; i < data.length; i++) {
                const element = data[i];
                var dom_nodes = $($.parseHTML(element[0]));
                var ck = $(dom_nodes).find("input[type=checkbox]").val()
                locs.push(ck)
            }

            var _all = $(form).find("input[name=all]").prop("checked")
            if(_all){
                locs = ["_all"]
            }
            
            $(form).find("input[name=id_locs]").val(JSON.stringify(locs))
            $(form).submit()
        })

        $("#modal_departement button[type=submit]").click(function(e){
            // e.preventDefault()
            var selected = tb_dept.rows({selected: true})
            var data = selected.data()
            var count = data.length
            var locs = []
            var form = $(this).parents("form")
            for (let i = 0; i < data.length; i++) {
                const element = data[i];
                var dom_nodes = $($.parseHTML(element[0]));
                var ck = $(dom_nodes).find("input[type=checkbox]").val()
                locs.push(ck)
            }

            var _all = $(form).find("input[name=all]").prop("checked")
            if(_all){
                locs = ["_all"]
            }
            
            $(form).find("input[name=id_dept]").val(JSON.stringify(locs))
            $(form).submit()
        })

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