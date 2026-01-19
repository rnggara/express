@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Department</h3>
                    <div class="d-flex align-items-center mb-5">
                        <span class="text-muted">Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">Company <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">{{ $company->company_name }} <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <a href="{{ route("crm.pref.company.company_list.structure", base64_encode($company->id)) }}" class="text-muted">Structure Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></a>
                        <span class="fw-semibold">Department</span>
                    </div>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
                        <i class="fi fi-rr-plus"></i>
                        Add Department
                    </button>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="position-relative">
                            <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                        </div>
                        <a href="{{ route('crm.pref.company.departement.structure', base64_encode($company->id)) }}" class="btn btn-primary">
                            <i class="fi fi-rr-chart-tree"></i>
                            View department structure
                        </a>
                    </div>
                    <table class="table bg-white" data-ordering="false" id="table-list">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Record ID</th>
                                <th>Department</th>
                                <th>Parent Department</th>
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
                                    <td>{{ $item->parentDept->name ?? "-" }}</td>
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
                                                <a href="javascript:;" onclick="editData('{{ $item->id }}')" class="menu-link px-3">
                                                    Edit Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>

                                        <form action="{{ route("crm.pref.company.departement.post", base64_encode($company->id)) }}" method="post">
                                            <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                                <span class="fw-bold fs-3">Are you sure want to delete?</span>
                                                                <span class="text-center">PPlease becarefull when deleting, it will cause payroll employee !</span>
                                                                <span class="text-center">make sure Department not used to employee</span>
                                                                <div class="d-flex align-items-center mt-5">
                                                                    @csrf
                                                                    <input type="hidden" name="id_detail" value="{{ $item->id }}">
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

    <form action="{{ route("crm.pref.company.departement.post", base64_encode($company->id)) }}" method="post">
        @component('layouts._crm_modal', [
            "modalId" => "modal_add",
            "modalSize" => "modal-lg"
        ])
            @slot('modalTitle')
                <div class="d-flex flex-column">
                    <span class="fs-1 fw-bold">Add Department</span>
                    <span class="fs-base fw-normal mt-2">&nbsp;&nbsp; </span>
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
                    <label class="col-form-label required">Department</label>
                    <input type="text" name="departement" value="{{ old("departement") }}" placeholder="Example: Marketing, Sales, Developer, and others" class="form-control">
                    @error('departement')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Parent Department</label>
                    <select name="parent_id" data-dropdown-parent="#modal_add" class="form-select" data-control="select2" data-placeholder="Select Department">
                        <option value=""></option>
                        @foreach ($data as $item)
                            <option value="{{ $item->id }}" {{ old("parent_id") == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Person In Charge</label>
                    <select name="pic" data-dropdown-parent="#modal_add" class="form-select" data-control="select2" data-placeholder="Select User">
                        <option value=""></option>
                        @foreach ($pic as $item)
                            <option value="{{ $item->id }}" {{ old("pic") == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row">
                    <label class="col-form-label required">Sub Level Organisasi</label>
                    <input type="number" name="sub_level" value="0" placeholder="Input Sub Level Organisasi" class="form-control">
                    @error('sub_level')
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

    <div id="edit-data"></div>

@endsection

@section('view_script')
<script>

    function editData(id){
        $.ajax({
            url : "{{ route("crm.pref.company.departement.index", base64_encode($company->id)) }}?a=edit",
            type : "get",
            data : {
                id : id
            },
            dataType : "json",
        }).then(function(resp){
            $("#edit-data").html(resp.view)
            $("#modal_edit").modal("show")
        })
    }

    $(document).ready(function(){
        @if(Session::has("modal"))
            $("#{{ Session::get("modal") }}").modal("show")
        @endif

        initTable($("#table-list"))
    })
</script>
@endsection
