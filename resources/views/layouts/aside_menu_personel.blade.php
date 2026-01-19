{{-- <div>
    <button type="button"
        class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px"
        data-kt-menu-triggermb-5 ="click" data-kt-menu-placement="bottom-start">
        <span class="d-inline">{{ __('Employee') }}</span>
        <i class="ki-duotone ki-down fs-4 ms-2 ms-md-3 me-0"></i>
    </button>
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
        data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{ route('attendance.collect_data.index') }}" class="menu-link px-3 d-flex">
                <span class="menu-title">Collect Data</span>
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="javascript:;" class="menu-link px-3 d-flex">
                <span class="menu-title">Send Approval Attendance</span>
            </a>
        </div>
        <!--end::Menu item-->
    </div>
</div> --}}
@hasPermission("personel", "view", "employee")
<a href="{{ route('personel.employee_table.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Pegawai') }}</span>
</a>
@endPermission
@hasPermission("personel", "view", "onboarding")
<a href="{{ route("personel.onboarding.index") }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Orientasi') }}</span>
</a>
@endPermission
@hasPermission("personel", "view", "request")
<a href="{{ route('personel.request.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Permintaan') }}</span>
</a>
@endPermission
@hasPermission("personel", "view", "request")
<a href="{{ route('personel.fl.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Format Surat Formal') }}</span>
</a>
@endPermission
