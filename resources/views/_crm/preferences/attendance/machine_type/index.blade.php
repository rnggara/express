@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Pengaturan Mesin Absensi</h3>
                    <span>Atur mesin absensi yang anda gunakan agar data bisa diproses</span>
                </div>
            </div>
            <div class="card-header border-bottom-0 px-0">
                <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                    <li class="nav-item">
                        <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_collect_program">
                            <span class="nav-text">Collect Program</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_machine_name">
                            <span class="nav-text">Machine Name</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="tab-content" id="myTabContent" style="padding: 0">
                        <div class="tab-pane fade show active" id="tab_collect_program" role="tabpanel">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_collect_program">
                                        Tambah Collect Program
                                    </button>
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-collect-program">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    <label class="form-check-label" for="ck1">
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Collect Program ID</th>
                                            <th>Collect Method</th>
                                            <th>File Type</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($programs as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    </div>
                                                </td>
                                                <td>{{ $item->program_id }}</td>
                                                <td>{{ $item->program_name }}</td>
                                                <td>{{ $item->program_type == "txt" ? "Text" : ($item->program_type == "csv" ? "CSV" : "Excel") }}</td>
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
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('program',{{$item->id}})" class="menu-link px-3">
                                                                Edit Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.machine_type.delete', ['type' => "program", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->program_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
                                                                Delete Data
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
                        </div>
                        <div class="tab-pane fade" id="tab_machine_name" role="tabpanel">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_machine">
                                        Tambah Machine
                                    </button>
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" data-ordering="false" id="table-machine_name">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    <label class="form-check-label" for="ck1">
                                                    </label>
                                                </div>
                                            </th>
                                            <th>Machine ID</th>
                                            <th>Name Machine</th>
                                            <th>Type Program</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($names as $item)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                                    </div>
                                                </td>
                                                <td>{{ $item->machine_id }}</td>
                                                <td>{{ $item->machine_name }}</td>
                                                <td>{{ $item->program->program_name ?? "-" }}</td>
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
                                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail('name',{{$item->id}})" class="menu-link px-3">
                                                                Edit Data
                                                            </a>
                                                        </div>
                                                        <!--end::Menu item-->
                                                        <!--begin::Menu item-->
                                                        <div class="menu-item px-3">
                                                            <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.machine_type.delete', ['type' => "name", 'id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->machine_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
                                                                Delete Data
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route("crm.pref.attendance.machine_type.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_collect_program
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-alarm-clock text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Tambah New Collect Program</h3>
                        <span class="text-muted fs-base">Create New Data & Manage it!</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Collect Program ID</label>
                        <input type="text" name="program_id" class="form-control" placeholder="Input Data">
                        @error('program_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Method Name</label>
                        <input type="text" name="program_name" class="form-control" placeholder="Input Data">
                        @error('program_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">File Type</label>
                        <select name="program_type" class="form-select" data-control="select2" data-dropdown-parent="#modal_add_collect_program" data-placeholder="Select Type" id="">
                            <option value=""></option>
                            <option value="txt">Text</option>
                            <option value="xlsx">Excel</option>
                            <option value="csv">csv</option>
                        </select>
                        @error('program_type')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="program">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

    <form action="{{ route("crm.pref.attendance.machine_type.store") }}" method="post">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_machine
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-alarm-clock text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Tambah New Machine</h3>
                        <span class="text-muted fs-base">Create New Data & Manage it!</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Machine ID</label>
                        <input type="text" name="machine_id" class="form-control" placeholder="013232">
                        @error('machine_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Machine Name</label>
                        <input type="text" name="machine_name" class="form-control" placeholder="Face Attendace CX">
                        @error('machine_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Collect Program</label>
                        <select name="program_id" class="form-select" data-control="select2" data-dropdown-parent="#modal_add_machine" data-placeholder="Select Type" id="">
                            <option value=""></option>
                            @foreach ($programs as $item)
                                <option value="{{ $item->id }}">{{ $item->program_name }}</option>
                            @endforeach
                        </select>
                        @error('program_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div data-content-program></div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="type" value="name">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

<div
    id="kt_drawer_detail"

    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_detail_button"
    data-kt-drawer-close="#kt_drawer_detail_close"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default : '50%', md: '50%', sm: '500px'}">
    <div class="card rounded-0 w-100" id="drawer-content">

    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3">Are you sure want to delete?</span>
                    <span class="text-center">Are you sure you want to delete <span id="delete-label"></span>? This action cannot be undone and will impact employee attendance settings.</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="" id="delete-url" class="btn btn-white">Yes</a>
                        <button class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('view_script')
    <script>

        function archiveItem(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            console.log($(me).data('name'))
            $("#delete-label").text($(me).data("name"))
            $("#delete-url").attr("href", uri)
        }

        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_detail");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        function show_detail(type, id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("crm.pref.attendance.machine_type.detail")}}/" + type + "/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)
                $(target).find("select[data-control=select2]").select2()
                if(type == "name"){
                    machine_program_type('drawer-content', id)
                    $("#drawer-content select[name=program_id]").trigger("change")
                }
            })
        }

        function machine_program_type(modal, name = null){
            $(`#${modal}`).find("select[name=program_id]").change(function(){
                var url = `{{ route("crm.pref.attendance.machine_type.index") }}?a=program_type&modal=${modal}&id=` + $(this).val()
                if(name != null){
                    url += "&name=" + name
                }
                $.ajax({
                    url : url,
                    type : "get",
                    dataType : "json"
                }).then(function(resp){
                    if(resp.success){
                        $(`#${modal}`).find('[data-content-program]').html(resp.view)
                    }
                })
            })
        }

        $(document).ready(function(){
            @if(Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ Session::get("tab") }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif

            machine_program_type("modal_add_machine")

            @if(Session::has("modal"))
                $("#{{ Session::get("modal") }}").modal("show")
            @endif

            @if(Session::has("drawer"))
                @if(Session::get("drawer") != null)
                    drawer.show()
                    show_detail('{{Session::get("type")}}', {{Session::get("drawer")}})
                @endif
            @endif
        })
    </script>
@endsection
