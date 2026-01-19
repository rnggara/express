@hasPermission("attendance", "view", "schedule")
<a href="{{ route('attendance.schedule.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    {{-- <span class="d-none d-md-inline">{{ __('Schedule') }}</span> --}}
    <span class="d-none d-md-inline">{{ __('Jadwal') }}</span>
</a>
@endPermission
@hasPermission("attendance", "view", "registrations")
<a href="{{ route('attendance.registration.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    {{-- <span class="d-none d-md-inline">{{ __('Registration') }}</span> --}}
    <span class="d-none d-md-inline">{{ __('Registrasi') }}</span>
</a>
@endPermission
@hasPermission("attendance", "view", "correction")
<a href="{{ route('attendance.correction.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    {{-- <span class="d-none d-md-inline">{{ __('Correction') }}</span> --}}
    <span class="d-none d-md-inline">{{ __('Koreksi') }}</span>
</a>
@endPermission
@hasPermission("attendance", "view", "overtime")
<a href="{{ route("attendance.overtime.index") }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    {{-- <span class="d-none d-md-inline">{{ __('Overtime') }}</span> --}}
    <span class="d-none d-md-inline">{{ __('Lembur') }}</span>
</a>
@endPermission
@hasPermission("attendance", "view", "leave")
<a href="{{ route('attendance.leave.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    {{-- <span class="d-none d-md-inline">{{ __('Leave') }}</span> --}}
    <span class="d-none d-md-inline">{{ __('Cuti') }}</span>
</a>
@endPermission
{{-- @hasPermission("attendance", "view", "approval")
<a href="{{ route('attendance.approval.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-none d-md-inline">{{ __('Persetujuan') }}</span>
</a>
@endPermission --}}
@hasPermission("attendance", "view", "collect_data")
<div>
    <button type="button"
        class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px"
        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
        {{-- <span class="d-none d-md-inline">{{ __('More') }}</span> --}}
        <span class="d-none d-md-inline">{{ __('Lainnya') }}</span>
        <i class="ki-duotone ki-down fs-4 ms-2 ms-md-3 me-0"></i>
    </button>
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
        data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{ route('attendance.collect_data.index') }}" class="menu-link px-3 d-flex">
                {{-- <span class="menu-title">Collect Data</span> --}}
                <span class="menu-title">Ambil Data</span>
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{ route('attendance.approval_attendance.index') }}" class="menu-link px-3 d-flex">
                {{-- <span class="menu-title">Send Approval Attendance</span> --}}
                <span class="menu-title">Kirim Persetujuan kehadiran</span>
            </a>
        </div>
        <!--end::Menu item-->
    </div>
</div>
@endPermission
