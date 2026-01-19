@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Pengaturan Hari Libur</h3>
                    <span>Atur preferensi anda mengenai hari libur nasional dan perusahaan</span>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $holidays->count() == 0 ? "modalNewYear" : "modal_add_holiday" }}">
                                Tambah Holiday
                            </button>
                            <div class="mx-3">
                                <select name="f_year" class="form-select" data-control="select2" data-placeholder='{{$year}} '>
                                    <option value=""></option>
                                    @for($i = date("Y") - 5; $i <= date("Y") + 5; $i++)
                                        <option value="{{$i}}" {{$year == $i ? "SELECTED" : ""}} >{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mx-3">
                                <select name="f_category" class="form-select w-auto" data-allow-clear="true" data-control="select2" data-placeholder="Select Category">
                                    <option value=""></option>
                                    @foreach($categories as $item)
                                        <option value="{{$item->category_name}}">{{$item->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="position-relative">
                            <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                        </div>
                    </div>
                    <table class="table table-display-2 bg-white" data-ordering="false" id="table-holiday">
                        <thead>
                            <tr>
                                <th>
                                    Date
                                </th>
                                <th>Category</th>
                                <th>Name Holiday</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidays as $item)
                                <tr>
                                    <td>@dateId($item->holiday_date)</td>
                                    <td>{{ $item->category->category_name ?? "-" }}</td>
                                    <td>{{ $item->name }}</td>
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
                                                <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_holiday" onclick="show_detail({{$item->id}})" class="menu-link px-3">
                                                    Edit Data
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.pref.attendance.holiday_table.delete', $item->id) }}" data-id="{{ $item->id }}" data-name="{{ $item->name }}" class="menu-link px-3">
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

    <form action="{{route("crm.pref.attendance.holiday_table.store")}}" method="POST">
        @component('layouts._crm_modal', [
            'modalSize' => "modal-lg"
        ])
            @slot('modalId')
                modal_add_holiday
            @endslot
            @slot('modalTitle')
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-5">
                        <div class="symbol-label bg-light-primary">
                            <span class="fi fi-rr-alarm-clock text-primary"></span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="me-2">Tambah Holiday</h3>
                        <span class="text-muted fs-base">Create New Type & Manage it!</span>
                    </div>
                </div>
            @endslot
            @slot('modalContent')
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Name Holiday</label>
                    <input type="text" class="form-control" name="name" placeholder="Input Data">
                    @error('name')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Date</label>
                        <input type="date" class="form-control" name="holiday_date" placeholder="Input Data">
                        @error('holiday_date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Holiday Category</label>
                        <select name="category" class="form-select" data-control="select2" data-dropdown-parent="#modal_add_holiday" data-placeholder="Select Holiday Category" id="">
                            <option value=""></option>
                            @foreach($categories as $item)
                                <option value="{{$item->id}}">{{$item->category_name}}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="fv-row mt-5">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="send_email" value="1" id="ckm1" />
                        <label class="form-check-label" for="ckm1">
                            Kirim Notifikasi Email
                        </label>
                    </div>
                </div>
            @endslot
            @slot('modalFooter')
                @csrf
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary">Save</button>
            @endslot
        @endcomponent
    </form>

<div class="modal fade" tabindex="-1" id="modalNewYear">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center p-5">
                    <img src="{{ asset("images/new_holiday.png") }}" class="w-50 mb-10" alt="">
                    <h3>Selamat Tahun Baru</h3>
                    <span class="text-center mb-10">Tahun Baru Semangat Baru, Saatnya untuk menyusun jadwal libur karyawan yang efisien. Untuk memastikan kelancaran proses ini, mari bersama-sama mengambil langkah</span>
                    <div class="d-flex justify-content-around">
                        <button type="button" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modal_add_holiday" class="btn btn-sm text-muted">Tambah Manual</button>
                        <form action="{{ route('crm.pref.attendance.holiday_table.assign_year') }}" method="post">
                            @csrf
                            <input type="hidden" name="year" value="{{$year}}">
                            <button type="submit" class="btn btn-sm btn-primary">Sesuaikan Jadwal Pemerintah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    id="kt_drawer_holiday"

    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_holiday_button"
    data-kt-drawer-close="#kt_drawer_holiday_close"
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
        var drawerElement = document.querySelector("#kt_drawer_holiday");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        function show_detail(id){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{route("crm.pref.attendance.holiday_table.detail")}}/" + id,
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
            console.log($(me).data('name'))
            $("#delete-label").text($(me).data("name"))
            $("#delete-url").attr("href", uri)
        }

        $(document).ready(function(){
            @if($errors->any())
                $("#modal_add_holiday").modal("show")
            @endif

            @if(Session::has("detail"))
                @if(Session::get("detail") != null)
                    drawer.show()
                    show_detail({{Session::get("detail")}})
                @endif
            @endif

            $("select[name=f_year]").change(function(){
                location.href = "{{route("crm.pref.attendance.holiday_table.index")}}?year=" + $(this).val()
            })

            $("select[name=f_category]").change(function(){
                tb_list[0].column(1).search($(this).val()).draw()
            })
        })
    </script>
@endsection
