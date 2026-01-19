@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Periode Management</h3>
                    <span>More details and settings</span>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_period">
                            Add Period
                        </button>
                        <div class="position-relative">
                            <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                        </div>
                    </div>
                    <table class="table table-display-2 bg-white" data-ordering="false" id="table-reason-name">
                        <thead>
                            <tr>
                                <th>
                                    Period
                                </th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($periodes as $item)
                                <tr>
                                    <td>{{$item->name}}</td>
                                    <td>@dateId($item->start_date)</td>
                                    <td>@dateId($item->end_date)</td>
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
                                                <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_periode" onclick="show_detail({{$item->id}})" class="menu-link px-3">
                                                    Edit Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.periode.delete', $item->id) }}" data-id="{{ $item->id }}" data-name="{{ $item->name }}" class="menu-link px-3">
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

    <form action="{{route("crm.pref.attendance.periode.store")}}" method="POST">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_period
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-calendar text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Add Periode</h3>
                        <span class="text-muted fs-base">Create New Category & Manage it!</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" placeholder="Input Data">
                        @error('start_date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">End Date</label>
                        <input type="date" name="end_date" class="form-control" placeholder="Input Data">
                        @error('end_date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row" form-name>
                        <label class="col-form-label fw-bold">Period Name</label>
                        <select name="pname" class="form-select" required id="pname" data-dropdown-parent="#modal_add_period" data-placeholder="Select Period Name">
                            <option value=""></option>
                        </select>
                        @error('pname')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row d-flex align-items-center mt-5">
                        <label class="col-form-label fw-bold flex-fill">Otomatis buat periode untuk</label>
                        <div class="position-relative flex-fill">
                            <input type="number" name="months" value="1" class="form-control pe-20" placeholder="Input Data">
                            <span class="position-absolute top-25 end-0 me-5">Months</span>
                        </div>
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label fw-bold">Akan dibuat periode</label>
                        <div class="d-flex flex-wrap align-items-center" form-created></div>
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <input type="hidden" name="auto" value="1">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            @endslot
        @endcomponent
    </form>

<div
    id="kt_drawer_periode"

    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_periode_button"
    data-kt-drawer-close="#kt_drawer_periode_close"
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

        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_periode");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        function show_detail(id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("crm.pref.attendance.periode.detail")}}/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)
            })
        }

        function archiveItem(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            $("#delete-label").text($(me).data("name"))
            $("#delete-url").attr("href", uri)
        }

        function autoMonth(head){

            var mId = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]

            var sdate = $(head).find("input[name=start_date]").val()
            var start_date = new Date(sdate)
            var months = $(head).find("input[name=months]").val()
            var sp = ""
            if(sdate != ""){
                var m = start_date.getMonth()
                for (let i = 0; i <= months; i++) {
                    var addM = (m + i) > 11 ? 0 : m + i;
                    const ndate = new Date(start_date.setMonth(addM))
                    var el = mId[ndate.getMonth()] + " " + ndate.getFullYear()
                    if (months >= 1 && i < months) {
                        el += ", "
                    }
                    sp += el
                }

                $(head).find("[form-created]").text(sp)
            }
        }

        function autoPeriode(head){
            var auto = $(head).find("input[name=auto]")
            var ck = $(auto).prop("checked")
            $(head).find("[form-auto]").addClass("d-none")
            $(head).find("[form-name]").removeClass("d-none")
            $(head).find("input[name=months]").attr("min", 0)

            if(ck){
                $(head).find("input[name=months]").attr("min", 1)
                $(head).find("[form-auto]").removeClass("d-none")
                $(head).find("[form-name]").addClass("d-none")

                autoMonth(head)

                $(head).find("input[name=months]").change(function(){
                    autoMonth(head)
                })

                $(head).find("input[name=start_date]").change(function(){
                    autoMonth(head)
                })
            }
        }

        function createdPeriod(){
            var data = {
                start_date : $("#modal_add_period input[name=start_date]").val(),
                end_date : $("#modal_add_period input[name=end_date]").val(),
                pname : $("#pname").val(),
                months : $("#modal_add_period input[name=months]").val(),
                a : "months"
            }

            $.ajax({
                url : "{{ route("crm.pref.attendance.periode.index") }}",
                type : "get",
                dataType : "json",
                data : data
            }).then(function(resp){
                $("#modal_add_period [form-created]").text(resp.text)
            })
        }

        $(document).ready(function(){
            autoPeriode("#modal_add_period")
            @if($errors->any())
                $("#modal_add_period").modal("show")
            @endif

            @if(Session::has("detail"))
                @if(Session::get("detail") != null)
                    drawer.show()
                    show_detail({{Session::get("detail")}})
                @endif
            @endif

            $("#pname").select2({
                ajax : {
                    url : "{{ route("crm.pref.attendance.periode.index") }}?a=pname",
                    data : function(params){
                        var q = {
                            search : params.term,
                            start_date : $("#modal_add_period input[name=start_date]").val(),
                            end_date : $("#modal_add_period input[name=end_date]").val(),
                        }

                        return q;
                    },
                    dataType : "json",
                    type : "get"
                },
                "language": {
                    "noResults": function(){
                        return "Please select Start Date and End Date first";
                    }
                },
            }).change(function(){
                createdPeriod()
            })

            $("#modal_add_period").find("input[name=months]").change(function(){
                createdPeriod()
            })
        })
    </script>
@endsection
