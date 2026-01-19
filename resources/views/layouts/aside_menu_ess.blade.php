<a href="{{ route('ess.profile.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Profil') }}</span>
</a>
<a href="{{ route("ess.attendance.index") }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Kehadiran') }}</span>
</a>
<a href="{{ route('ess.overtime.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Lembur') }}</span>
</a>
<a href="{{ route('ess.leave.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Cuti') }}</span>
</a>
<a href="{{ route('ess.benefit.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Keuntungan') }}</span>
</a>
<a href="{{ route('ess.team.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Tim Saya') }}</span>
</a>
<a href="{{ route('ess.approval.index') }}"
    class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-inline">{{ __('Persetujuan') }}</span>
</a>
<div>
    <button type="button"
        class="btn btn-flex mb-5 btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px"
        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
        <span class="d-inline">{{ __('Lainnya') }}</span>
        <i class="ki-duotone ki-down fs-4 ms-2 ms-md-3 me-0"></i>
    </button>
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
        data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{route("ess.loan.index")}}" class="menu-link px-3 d-flex">
                <div class="symbol symbol-40px me-2">
                    <div class="symbol-label fw-semibold bg-light-primary">
                        <i class="fi fi-sr-sack-dollar fs-2 spaper text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold">Pinjaman</span>
                    <span class="text-muted">&nbsp;&nbsp;</span>
                </div>
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{ route('ess.cash-advance.index') }}" class="menu-link px-3 d-flex">
                <div class="symbol symbol-40px me-2">
                    <div class="symbol-label fw-semibold bg-light-primary">
                        <i class="fi fi-sr-envelope-open-dollar fs-2 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold">Penarikan Tunai</span>
                    <span class="text-muted">&nbsp;&nbsp;</span>
                </div>
            </a>
        </div>
        <!--end::Menu item-->
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{ route('ess.employment-letter.index') }}" class="menu-link px-3 d-flex">
                <div class="symbol symbol-40px me-2">
                    <div class="symbol-label fw-semibold bg-light-primary">
                        <i class="fi fi-sr-file-invoice fs-2 text-primary"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold">Surat Keterangan Kerja</span>
                    <span class="text-muted">&nbsp;&nbsp;</span>
                </div>
            </a>
        </div>
        <!--end::Menu item-->
    </div>
</div>
