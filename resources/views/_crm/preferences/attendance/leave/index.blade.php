@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Pengaturan kelompok cuti</h3>
                    <span>Atur preferensi anda tentang aturan cuti perusahaan</span>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_leave_group">
                            Tambah Leave Group
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                        <label class="form-check-label" for="ck1">
                                        </label>
                                    </div>
                                </th>
                                <th>Leave Group ID</th>
                                <th>Leave Group Name</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $item)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ck1" />
                                        </div>
                                    </td>
                                    <td>{{ $item->leave_group_id }}</td>
                                    <td>{{ $item->leave_group_name }}</td>
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
                                                <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" onclick="show_detail({{$item->id}})" class="menu-link px-3">
                                                    Edit Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" @if($item->is_default == 0) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.leave.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->leave_group_id }}" @endif class="menu-link px-3 {{ $item->is_default == 1 ? "disabled text-muted" : "" }}">
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

<form action="{{ route("crm.pref.attendance.leave.store") }}" method="post">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-xl"
        ])
        @slot('modalId')
            modal_add_leave_group
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-calendar text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Tambah New Leave Group</h3>
                    <span class="text-muted fs-base">Create New Type & Manage it!</span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <div class="row">
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Leave Group ID</label>
                    <input type="text" name="leave_group_id" class="form-control" placeholder="Input Data">
                    @error('leave_group_id')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Leave Group Name</label>
                    <input type="text" name="leave_group_name" class="form-control" placeholder="Input Data">
                    @error('leave_group_name')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="mt-5">
                <div class="form-check">
                    <input class="form-check-input" name="show_type" checked type="radio" value="0" id="ckjoindate" />
                    <label class="form-check-label" for="ckjoindate">
                        Muncul Kuota Cuti Setiap tanggal Karyawan bergabung (By Join Date)
                    </label>
                </div>
            </div>
            <div class="mt-5">
                <div class="form-check">
                    <input class="form-check-input" name="show_type" type="radio" value="1" id="ckcutoff" />
                    <label class="form-check-label" for="ckcutoff">
                        Muncul Kuota Cuti berdasarkan start leave period (Cut Off Date)
                    </label>
                </div>
            </div>
            <div class="row mt-5 cut-off d-none">
                <div class="col-6">
                    <div class="fv-row">
                        <label for="" class="col-form-label">Start Leave Periode</label>
                        <input type="text" value="" name="start_leave_periode" class="form-control tempusDominus" data-format="dd/MM" id="start_l_add">
                    </div>
                </div>
                <div class="col-6">
                    <div class="fv-row">
                        <label for="" class="col-form-label">Grant Leave</label>
                        <select name="grant_leave_type" class="form-select" data-control="select2" data-placeholder="Select Type" data-dropdown-parent="#modal_add_leave_group">
                            <option value=""></option>
                            @foreach ($grant_types as $item)
                                <option value="{{ $item->id }}">{{ $item->grant_leave_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column mt-5">
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6 px-5 pt-5 nav-fill">
                    <li class="nav-item">
                        <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_annual_leave">
                            <span class="nav-text">Annual Leave</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_long_leave">
                            <span class="nav-text">Long Leave</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_special_leave">
                            <span class="nav-text">Special Leave</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent" style="padding: 0">
                    <div class="tab-pane fade show active" id="tab_annual_leave" role="tabpanel">
                        <!--begin::Repeater-->
                        <div class="repeater">
                            <div class="d-flex justify-content-between mb-10">
                                <h3 class="text-primary">Total Leaves</h3>
                                <button type="button" data-repeater-create class="btn text-primary">
                                    <i class="fa fa-plus text-primary"></i>
                                    Tambah
                                </button>
                            </div>
                            <!--begin::Form group-->
                            <div class="form-group">
                                <div data-repeater-list="annual_total_leaves">
                                    <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="fv-row me-3 flex-fill">
                                                <label class="col-form-label">Range in Year</label>
                                                <input type="number" name="range_from" class="form-control" placeholder="5">
                                            </div>
                                            <div class="fv-row me-3 flex-fill">
                                                <label class="col-form-label">&nbsp;</label>
                                                <input type="number" name="range_to" class="form-control" placeholder="5">
                                            </div>
                                            <div class="fv-row me-3">
                                                <label class="col-form-label">Total Leaves</label>
                                                <input type="text" name="total_leave" class="form-control" placeholder="20 days">
                                            </div>
                                            <div class="fv-row">
                                                <label class="col-form-label">&nbsp;</label>
                                                <div>
                                                    <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                        <i class="fi fi-rr-trash text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Form group-->
                        </div>
                        <!--end::Repeater-->
                        <div class="row mt-3">
                            <div class="fv-row col-4">
                                <label class="col-form-label">Annual Leave Expired in month</label>
                                <input type="text" class="form-control" name="annual_leave_expired" placeholder="Input total in month">
                            </div>
                            <div class="fv-row col-4">
                                <label class="col-form-label">Over Right Leaves (Minus Leave)</label>
                                <input type="text" class="form-control" name="annual_over_right" placeholder="Input total leave">
                            </div>
                            <div class="fv-row col-4">
                                <label class="col-form-label">Expired Change Leave in Month</label>
                                <input type="text" class="form-control" name="annual_expired_change" placeholder="Input total in month">
                            </div>
                        </div>
                        <div class="mt-5">
                            <div class="form-check">
                                <input class="form-check-input" name="annual_pay_end_periode" value="1" type="checkbox" id="ck{{ rand(1000,9999) }}" />
                                <label class="form-check-label" for="ck{{ rand(1000,9999) }}">
                                    Ijinkan sisa cuti dibayar pada akhir periode cuti
                                </label>
                            </div>
                        </div>
                        <div class="my-5">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="mass_leave" type="checkbox" value="1" />
                                    Terapkan Mass Leave untuk Grup Cuti Ini
                                </label>
                            </div>
                        </div>
                        <div class="d-none row" data-mass-leave>
                            <div class="fv-row col-4">
                                <label class="col-form-label">Total Mass Leave</label>
                                <input type="text" name="mass_leave_total" class="form-control" placeholder="Masukan Total Mass Leave">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab_long_leave" role="tabpanel">
                        <!--begin::Repeater-->
                        <div class="repeater">
                            <div class="d-flex justify-content-between mb-10">
                                <h3 class="text-primary">Total Leaves</h3>
                                <button type="button" data-repeater-create class="btn text-primary">
                                    <i class="fa fa-plus text-primary"></i>
                                    Tambah
                                </button>
                            </div>
                            <!--begin::Form group-->
                            <div class="form-group">
                                <div data-repeater-list="long_total_leaves">
                                    <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="fv-row me-3 flex-fill">
                                                <label class="col-form-label">Lama Bekerja</label>
                                                <input type="text" name="lama_kerja" class="form-control" placeholder="5">
                                            </div>
                                            <div class="fv-row me-3 flex-fill">
                                                <label class="col-form-label">Total Leaves</label>
                                                <input type="text" name="total_leave" class="form-control" placeholder="20 Days">
                                            </div>
                                            <div class="fv-row">
                                                <label class="col-form-label">&nbsp;</label>
                                                <div>
                                                    <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                        <i class="fi fi-rr-trash text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="fv-row me-3 flex-fill">
                                                <label class="col-form-label">Lama Bekerja</label>
                                                <input type="text" name="lama_kerja" class="form-control" placeholder="5">
                                            </div>
                                            <div class="fv-row me-3 flex-fill">
                                                <label class="col-form-label">Total Leaves</label>
                                                <input type="text" name="total_leave" class="form-control" placeholder="20 Days">
                                            </div>
                                            <div class="fv-row">
                                                <label class="col-form-label">&nbsp;</label>
                                                <div>
                                                    <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                        <i class="fi fi-rr-trash text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Form group-->
                        </div>
                        <!--end::Repeater-->
                        <div class="fv-row">
                            <label class="col-form-label">Leave Expired in month</label>
                            <input type="number" class="form-control" name="long_expired" placeholder="12 month">
                        </div>
                        <div class="mt-5">
                            <div class="form-check">
                                <input class="form-check-input" name="long_pay_end_periode" type="checkbox" value="1" id="ck{{ rand(1000,9999) }}" />
                                <label class="form-check-label" for="ck{{ rand(1000,9999) }}">
                                    Ijinkan sisa cuti dibayar pada akhir periode cuti
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab_special_leave" role="tabpanel">
                        <!--begin::Repeater-->
                        <div class="repeater">
                            <div class="d-flex justify-content-between mb-10">
                                <h3 class="text-primary">Total Leaves</h3>
                                <button type="button" data-repeater-create class="btn text-primary">
                                    <i class="fa fa-plus text-primary"></i>
                                    Tambah
                                </button>
                            </div>
                            <!--begin::Form group-->
                            <div class="form-group">
                                <div data-repeater-list="special_total_leaves">
                                    <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="fv-row me-3 flex-fill">
                                                <label class="col-form-label">Reason</label>
                                                <select name="reason" class="form-select" data-kt-repeater="select2" data-placeholder="Cuti Melahirkan" data-dropdown-parent="#tab_special_leave">
                                                    <option value=""></option>
                                                    @foreach ($reason_cond as $item)
                                                        <option value="{{ $item->id }}">{{ $item->reasonName->reason_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row me-3 w-50">
                                                <label class="col-form-label">Total Leaves</label>
                                                <input type="text" name="total_leaves" class="form-control" placeholder="20 Days">
                                            </div>
                                            <div class="fv-row">
                                                <label class="col-form-label">&nbsp;</label>
                                                <div>
                                                    <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                        <i class="fi fi-rr-trash text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="fv-row me-3 flex-fill">
                                                <label class="col-form-label">Reason</label>
                                                <select name="reason" class="form-select" data-kt-repeater="select2" data-placeholder="Cuti Melahirkan" data-dropdown-parent="#tab_special_leave">
                                                    <option value=""></option>
                                                    @foreach ($reason_cond as $item)
                                                        <option value="{{ $item->id }}">{{ $item->reasonName->reason_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row me-3 w-50">
                                                <label class="col-form-label">Total Leaves</label>
                                                <input type="text" name="total_leaves" class="form-control" placeholder="20 Days">
                                            </div>
                                            <div class="fv-row">
                                                <label class="col-form-label">&nbsp;</label>
                                                <div>
                                                    <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                        <i class="fi fi-rr-trash text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Form group-->
                        </div>
                        <!--end::Repeater-->
                    </div>
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
            <button type="submit" class="btn btn-primary">Submit</button>
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
    <script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
    <script>

        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_detail");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        function show_detail(id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("crm.pref.attendance.leave.detail")}}/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)
                repeater_form("#kt_drawer_detail")

                $("#kt_drawer_detail select[data-control=select2]").select2()

                $("#kt_drawer_detail input[name=show_type]").change(function(){
                    hide_cut_off(this)
                })

                massLeave("#kt_drawer_detail")

                $("#kt_drawer_detail .tempusDominus").each(function(){
                    var _id = $(this).attr("id")
                    var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                        display : {
                            viewMode: "calendar",
                            components: {
                                decades: true,
                                year: true,
                                month: true,
                                date: true,
                                hours: false,
                                minutes: false,
                                seconds: false
                            }
                        },
                        localization: {
                            locale: "id",
                            startOfTheWeek: 1,
                            format:  $(this).data("format") ?? "dd/MM/yyyy"
                        }
                    });
                })
            })
        }

        function archiveItem(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            console.log($(me).data('name'))
            $("#delete-label").text($(me).data("name"))
            $("#delete-url").attr("href", uri)
        }

        function repeater_form(head = null){
            var target = '.repeater'
            if(head != null){
                target = head + " .repeater"
            }
            $(target).each(function(){
                var me = $(this)
                $(this).repeater({
                    initEmpty : false,
                    defaultValues : {
                        'text-input' : ""
                    },
                    show : function(){
                        console.log($(this))
                        $(this).slideDown();
                        $(this).find('[data-kt-repeater="select2"]').select2()
                    },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    },
                    ready: function(){
                        $(me).find('[data-kt-repeater="select2"]').select2()
                    }
                })
            })
        }

        function hide_cut_off(me){
            var form = $(me).parents("form")
            $(form).find(".cut-off").addClass("d-none")
            if($(me).val() == 1){
                $(form).find(".cut-off").removeClass("d-none")
            }
        }

        function massLeave(target){
            $(target + " input[name=mass_leave]").click(function(){
                var ck = $(this).prop("checked")
                var form = $(this).parents("form")
                var dt = $(form).find("[data-mass-leave]")
                $(dt).addClass("d-none")
                if(ck){
                    $(dt).removeClass("d-none")
                }
            })
        }

        $(document).ready(function(){

            $("#modal_add_leave_group input[name=show_type]").change(function(){
                hide_cut_off(this)
            })

            massLeave("#modal_add_leave_group")

            repeater_form()

            @if(Session::has("modal"))
                $("#{{ Session::get("modal") }}").modal("show")
            @endif

            @if(Session::has("drawer"))
                @if(Session::get("drawer") != null)
                    drawer.show()
                    show_detail({{Session::get("drawer")}})
                @endif
            @endif
        })
    </script>
@endsection
