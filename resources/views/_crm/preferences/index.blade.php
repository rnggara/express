@php
    $route = \Route::getCurrentRoute()->action['as'];

    $_route = explode(".", $route);

    $_title = "Pengaturan Perusahaan";

    $desc = "Anda bisa merubah pengaturan perusahaan Anda disini";
    if(\Session::get('session_state') == "attendance"){
        $desc = "You need to what you need to do.";
    } elseif(\Session::get('session_state') == "personel"){
        $desc = "You need to what you need to do.";
        $_title = "Pengaturan Personal";
    } elseif(\Session::get('session_state') == "crm"){
        $desc = "You need to what you need to do.";
        $_title = "Pengaturan CRM";
    }

    if(\Session::get('session_state') == "attendance"){
        $desc = "Sesuaikan aplikasi menurut preferensi anda";
        $_title = "Pengaturan";
    }

    $menu = "menu_".\Session::get("session_state");

    $subtitle = \Session::get("session_state") == "intranet" ? "" : (\Session::get("session_state") == "crm" ? "CRM" : ucwords(\Session::get("session_state")));

@endphp

@extends('layouts.templateCrm', ["menuCrm" => "$menu", 'withoutFooter' => true, "bgWrapper" => "bg-white", "subTitle" => $subtitle,"style" => ["border" => "border-bottom", "box-shadow" => "none"]])

@section('css')
    <style>
        .bg-secondary-crm {
            background-color: #F7F8FA;
        }
    </style>
@endsection

@section('content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-10">
        <div class="symbol symbol-50px me-5">
            <div class="symbol-label bg-primary">
                <i class="fa fa-{{ \Session::get('session_state') == "crm" ? "layer-group" : "cog" }} text-white fs-2"></i>
            </div>
        </div>
        <div class="d-flex flex-column justify-content-between">
            <span class="fw-bold fs-2">{{ \Session::get('session_state') == "crm" ? "Preferences" : $_title }}</span>
            <span>{{ $desc }}</span>
        </div>
    </div>
    <div class="d-flex">
        <div id="kt_drawer_example_basic d-none d-md-inline" class="d-flex flex-column flex-md-row">
            <div class="card min-w-300px me-8 d-none d-md-inline bg-secondary-crm" id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true"
            data-kt-drawer-toggle="#kt_job_aside_toggle" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
            data-kt-drawer-direction="start" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true">
                {{-- being::nav --}}
                <!--begin::Menu-->
                <div class="menu menu-rounded menu-column menu-title-gray-700 menu-icon-gray-400 menu-arrow-gray-400 menu-bullet-gray-400 menu-arrow-gray-400 menu-state-bg-light-primary fw-semibold w-100 p-3" data-kt-menu="true">
                    @if (\Session::get("session_state") == "intranet")
                        <!--begin::Menu item-->
                        <div class="menu-item menu-sub-indention menu-accordion {{ stripos($route, 'crm.pref.general') !== false ? 'show' : "" }}" data-kt-menu-trigger="click">
                            <a href="javascript:;" class="menu-link py-4 {{ stripos($route, 'crm.pref.general') !== false ? 'active' : "" }}">
                                <span class="menu-icon">
                                    <i class="fi fi-rr-user text-primary fs-2"></i>
                                </span>
                                <span class="menu-title text-dark">Akun Saya</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.general.basic_information.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.general.basic_information") !== false ? "active" : "" }}">
                                        <span class="menu-title">Informasi</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.general.password.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.general.password") !== false ? "active" : "" }}">
                                        <span class="menu-title">Kata Sandi</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item menu-sub-indention menu-accordion {{ stripos($route, 'crm.pref.uac') !== false ? 'show' : "" }}" data-kt-menu-trigger="click">
                            <a href="javascript:;" class="menu-link py-4 {{ stripos($route, 'crm.pref.uac') !== false ? 'active' : "" }}">
                                <span class="menu-icon">
                                    <i class="fi fi-rr-code-branch text-primary fs-2"></i>
                                </span>
                                <span class="menu-title text-dark">User Access Control</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.uac.role.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.uac.role") !== false ? "active" : "" }}">
                                        <span class="menu-title">Role Setting</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.uac.user.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.uac.user") !== false ? "active" : "" }}">
                                        <span class="menu-title">Users</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item my-1">
                            <a href="{{ route("crm.pref.company.company_list.index") }}" class="menu-link py-4 {{ stripos($route, 'crm.pref.company') !== false ? 'active' : "" }}">
                                <span class="menu-icon">
                                    <i class="fi fi-rr-building text-primary fs-2"></i>
                                </span>
                                <span class="menu-title text-dark">Perusahaan</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    @endif
                    @if (\Session::get("session_state") == "personel")
                        <div class="menu-item menu-sub-indention menu-accordion {{ stripos($route, 'rm.pref.personel') !== false ? 'show' : "" }}" data-kt-menu-trigger="click">
                            <a href="javascript:;" class="menu-link py-4 {{ stripos($route, 'rm.pref.personel') !== false ? 'active' : "" }}">
                                <span class="menu-icon">
                                    <i class="fi fi-rr-users-alt text-primary fs-2"></i>
                                </span>
                                <span class="menu-title text-dark">Personel</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.employee_status.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.employee_status") !== false ? "active" : "" }}">
                                        <span class="menu-title">Status Karyawan</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.identity.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.identity") !== false ? "active" : "" }}">
                                        <span class="menu-title">Kartu/Identitas</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.education.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.education") !== false ? "active" : "" }}">
                                        <span class="menu-title">Pendidikan</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.major.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.major") !== false ? "active" : "" }}">
                                        <span class="menu-title">Jurusan</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.language.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.language") !== false ? "active" : "" }}">
                                        <span class="menu-title">Bahasa</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.religion.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.religion") !== false ? "active" : "" }}">
                                        <span class="menu-title">Agama</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.marital_status.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.marital_status") !== false ? "active" : "" }}">
                                        <span class="menu-title">Status Pernikahan</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="javascript:;" class="menu-link py-3">
                                        <span class="menu-title">Kewarganegaraan</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.licenses.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.licenses") !== false ? "active" : "" }}">
                                        <span class="menu-title">Lisensi</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.blood_type.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.blood_type") !== false ? "active" : "" }}">
                                        <span class="menu-title">Golongan Darah</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.gender.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.gender") !== false ? "active" : "" }}">
                                        <span class="menu-title">Jenis Kelamin</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.custom_properties.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.custom_properties") !== false ? "active" : "" }}">
                                        <span class="menu-title">Kolom Khusus</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                {{-- <div class="menu-item menu-sub-indention menu-accordion {{ stripos($route, 'crm.pref.personel') !== false ? 'show' : "" }}" data-kt-menu-trigger="click">
                                    <a href="javascript:;" class="menu-link py-4 {{ stripos($route, 'crm.pref.personel') !== false ? 'active' : "" }}">
                                        <span class="menu-icon">
                                            <i class="fi fi-rr-users-alt text-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark">Personel</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="menu-sub menu-sub-accordion pt-3">

                                    </div>
                                </div> --}}
                                <!--end::Menu item-->
                            </div>
                        </div>
                        <div class="menu-item menu-sub-indention menu-accordion {{ stripos($route, 'crm.pref.onboarding') !== false ? 'show' : "" }}" data-kt-menu-trigger="click">
                            <a href="javascript:;" class="menu-link py-4 {{ stripos($route, 'crm.pref.onboarding') !== false ? 'active' : "" }}">
                                <span class="menu-icon">
                                    <i class="fi fi-rr-handshake text-primary fs-2"></i>
                                </span>
                                <span class="menu-title text-dark">Onboarding</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.onboarding.fd.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.onboarding.fd") !== false ? "active" : "" }}">
                                        <span class="menu-title">Form Database</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.onboarding.ot.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.onboarding.ot") !== false ? "active" : "" }}">
                                        <span class="menu-title">Onboarding Template</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                        </div>
                        <div class="menu-item menu-sub-indention menu-accordion {{ stripos($route, 'ccrm.pref.personel.approval') !== false ? 'show' : "" }}" data-kt-menu-trigger="click">
                            <a href="javascript:;" class="menu-link py-4 {{ stripos($route, 'ccrm.pref.personel.approval') !== false ? 'active' : "" }}">
                                <span class="menu-icon">
                                    <i class="fi fi-rr-list-check text-primary fs-2"></i>
                                </span>
                                <span class="menu-title text-dark">Approval Setting</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="menu-sub menu-sub-accordion pt-3">
                                <!--begin::Menu item-->
                                <div class="menu-item">
                                    <a href="{{ route("crm.pref.personel.approval.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.personel.approval.index") !== false ? "active" : "" }}">
                                        <span class="menu-title">Transfer</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                        </div>
                    @endif
                    @if (\Session::get('session_state') == "attendance")
                        <!--begin::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <a href="{{ route("crm.pref.attendance.reason_name.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.attendance.reason_name") !== false ? "active" : "" }}">
                                <span class="menu-title">Alasan</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <a href="{{ route("crm.pref.attendance.workgroup.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.attendance.workgroup") !== false ? "active" : "" }}">
                                <span class="menu-title">Kelompok Kerja</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <a href="{{ route("crm.pref.attendance.overtime.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.attendance.overtime") !== false ? "active" : "" }}">
                                <span class="menu-title">Lembur</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <a href="{{ route("crm.pref.attendance.leave.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.attendance.leave") !== false ? "active" : "" }}">
                                <span class="menu-title">Cuti</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <a href="{{ route("crm.pref.attendance.machine_type.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.attendance.machine_type") !== false ? "active" : "" }}">
                                <span class="menu-title">Mesin Absensi</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <a href="{{ route("crm.pref.attendance.holiday_table.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.attendance.holiday_table") !== false ? "active" : "" }}">
                                <span class="menu-title">Tabel Hari Libur</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <a href="{{ route("crm.pref.attendance.periode.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.attendance.periode") !== false ? "active" : "" }}">
                                <span class="menu-title">Periode</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <a href="{{ route("crm.pref.attendance.preferences.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.attendance.preferences") !== false ? "active" : "" }}">
                                <span class="menu-title">Lainnya</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        {{-- <div class="menu-item menu-sub-indention menu-accordion {{ stripos($route, 'crm.pref.attendance') !== false ? 'show' : "" }}" data-kt-menu-trigger="click">
                            <!--begin::Menu link-->
                            <a href="javascript:;" class="menu-link py-3 {{ stripos($route, 'crm.pref.attendance') !== false ? 'active' : "" }}">
                                <span class="menu-icon">
                                    <i class="fi fi-rr-list-check text-primary fs-2"></i>
                                </span>
                                <span class="menu-title">Attendance</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <!--end::Menu link-->
                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-accordion pt-3">

                            </div>
                        </div> --}}
                        <!--end::Menu item-->
                    @endif
                    {{-- <!--begin::Menu item-->
                    <div class="menu-item my-1">
                        <a href="javascript:;" class="menu-link px-4">
                            <span class="menu-icon">
                                <i class="fi fi-rr-money-bill-wave text-primary fs-2"></i>
                            </span>
                            <span class="menu-title text-dark">Payroll</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item my-1">
                        <a href="javascript:;" class="menu-link px-4">
                            <span class="menu-icon">
                                <i class="fi fi-rr-book-alt text-primary fs-2"></i>
                            </span>
                            <span class="menu-title text-dark">Training</span>
                        </a>
                    </div>
                    <!--end::Menu item--> --}}
                    @if (\Session::get("session_state") == "crm")
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route("crm.pref.crm.dashboard.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.crm.dashboard") !== false ? "active" : "" }}">
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route("crm.pref.crm.opportunity.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.crm.opportunity") !== false ? "active" : "" }}">
                            <span class="menu-title">Opportunity</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route("crm.pref.crm.company.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.crm.company") !== false ? "active" : "" }}">
                            <span class="menu-title">Company</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route("crm.pref.crm.contact.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.crm.contact") !== false ? "active" : "" }}">
                            <span class="menu-title">Contact</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route("crm.pref.crm.file.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.crm.file") !== false ? "active" : "" }}">
                            <span class="menu-title">File</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route("crm.pref.crm.product.index") }}" class="menu-link py-3 {{ stripos($route, "crm.pref.crm.product") !== false ? "active" : "" }}">
                            <span class="menu-title">Product</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route('crm.pref.crm.user.index') }}" class="menu-link py-3 {{ stripos($route, "crm.pref.crm.user") !== false ? "active" : "" }}">
                            <span class="menu-title">User</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item">
                        <a href="{{ route("crm.pref.crm.permission.index") }}" class="menu-link py-3 {{ $route == "crm.pref.crm.permission.index" ? "active" : "" }}">
                            <span class="menu-title">Permission</span>
                        </a>
                    </div>
                    <!--end::Menu item-->
                        <!--begin::Menu item-->
                        {{-- <div class="menu-item menu-sub-indention menu-accordion {{ stripos($route, 'crm.pref.crm') !== false ? 'show' : "" }}" data-kt-menu-trigger="click">
                            <!--begin::Menu link-->
                            <a href="javascript:;" class="menu-link py-3 {{ stripos($route, 'crm.pref.crm') !== false ? 'active' : "" }}">
                                <span class="menu-icon">
                                    <i class="fi fi-rr-briefcase text-primary fs-2"></i>
                                </span>
                                <span class="menu-title">CRM</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <!--end::Menu link-->

                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-accordion pt-3">

                            </div>
                        </div> --}}
                        <!--end::Menu item-->
                    @endif
                    {{-- <!--begin::Menu item-->
                    <div class="menu-item my-1">
                        <a href="javascript:;" class="menu-link px-4">
                            <span class="menu-icon">
                                <i class="fi fi-rr-document text-primary fs-2"></i>
                            </span>
                            <span class="menu-title text-dark">Data Administrations</span>
                        </a>
                    </div>
                    <!--end::Menu item--> --}}
                    {{-- <div class="menu-"></div> --}}
                </div>
                <!--end::Menu-->
                {{-- end::nav --}}
            </div>
        </div>
        <div class="flex-fill flex-row-fluid">
            @yield('view_content')
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    @yield('view_script')
    <script>
        @if (\Session::has("toast"))
            // const toastElement = document.getElementById('kt_docs_toast_toggle');

            // // Get toast instance --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#getinstance
            // const toast = bootstrap.Toast.getOrCreateInstance(toastElement);

            // $(document).ready(function(){
            //     // toast.show();
            // })
        @endif

        var tb_list = []


        $("table.table-display-2").each(function(){
            var input_search = $(this).parent().find("input[name=search_table]")
            var tb = $(this).DataTable({
                dom : `t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">><"col-sm-12 col-md-7 d-flex align-items-center"p>>`,
                "fnDrawCallback": function( oSettings ) {
                    try {
                        KTMenu.createInstances();
                        $("[data-bs-toggle=tooltip]").tooltip()
                    } catch (error) {

                    }
                },
            })

            tb_list.push(tb)

            $(input_search).on("keyup", function(){
                tb.search( this.value ).draw();
            })
        })

        function initTable(id){
            var input_search = $(id).parent().find("input[name=search_table]")
            var tb = $(id).DataTable({
                dom : `t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">><"col-sm-12 col-md-7 d-flex align-items-center"p>>`,
                "fnDrawCallback": function( oSettings ) {
                    try {
                        KTMenu.createInstances();
                        $("[data-bs-toggle=tooltip]").tooltip()
                    } catch (error) {

                    }
                },
            })

            tb_list.push(tb)

            $(input_search).on("keyup", function(){
                tb.search( this.value ).draw();
            })

            $("table.table").addClass("gy-7 gs-7 border table-rounded")

            $("table.table thead tr").addClass("fw-semibold fs-6 text-gray-800 border-bottom border-gray-200")

            var table_display = $("table.display").DataTable({
                // dom : `<"d-flex align-items-center justify-content-between justify-content-md-end"f>t<"dataTable-length-info-label me-3">lip`
                dom : `<"d-flex align-items-center justify-content-between justify-content-start"f>t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">l><"col-sm-12 col-md-7 d-flex align-items-center"p>>`,
                "fnDrawCallback": function( oSettings ) {
                    try {
                        KTMenu.createInstances();
                        $("[data-bs-toggle=tooltip]").tooltip()
                    } catch (error) {

                    }
                },
            })

            $(".dataTable-length-info-label").text("Lihat:")

            var _selDataTable = $(".dataTables_length").find("select")
            _selDataTable.addClass("border-0 bg-white")
            _selDataTable.removeClass("form-select-solid")
            // _selDataTable.parent().addClass("border-bottom border-dark")
            var _filterDataTable = $(".dataTables_filter")
            _filterDataTable.find("input[type=search]").removeClass("form-control-solid")
            var _filterLabel = _filterDataTable.find("label")
            _filterLabel.each(function(){
                var id = $(this).parents(".dataTables_filter").attr("id")
                var id_split = id.split("_")
                var id_split2 = id_split[0].split("-")
                var _html = $(this).html()
                var _exp = _html.split(":")
                var input = $(this).find("input")
                var _input = $(input).addClass("ps-10")
                var el = '<i class="fs-3 fa fa-search ms-4 position-absolute text-gray-500 top-50 translate-middle-y"></i>'
                _input.attr("placeholder", "Cari " + id_split2[1])
                $(this).contents().filter(function(){ return this.nodeType != 1; }).remove();
                $(el).insertBefore(input)
                $(this).addClass("d-lg-block d-none mb-5 mb-lg-0 position-relative w-100")
            })

            return tb
        }
    </script>
@endsection
