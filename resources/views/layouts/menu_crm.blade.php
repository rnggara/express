{{-- <a href="{{ route('crm.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-none d-md-inline">{{ __('Dashboard') }}</span>
</a> --}}
@hasPermission("crm", "view", "opportunity")
<a href="{{ route('crm.lead.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-none d-md-inline">{{ __('Opportunity') }}</span>
</a>
@endPermission
<div>
    <button type="button"
        class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px"
        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
        <span class="d-none d-md-inline">{{ __('List') }}</span>
        <i class="ki-duotone ki-down fs-4 ms-2 ms-md-3 me-0"></i>
    </button>
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg fw-semibold w-auto py-3"
        data-kt-menu="true">
        <!--begin::Menu item-->
        @hasPermission("crm", "view", "list_company")
        <div class="menu-item px-3">
            <a href="{{ route('crm.list.index') }}?t=perusahaan" class="menu-link px-3 d-flex">
                <span class="menu-title">Company</span>
            </a>
        </div>
        @endPermission
        <!--end::Menu item-->
        <!--begin::Menu item-->
        @hasPermission("crm", "view", "list_contact")
        <div class="menu-item px-3">
            <a href="{{ route('crm.list.index') }}?t=kontak" class="menu-link px-3 d-flex">
                <span class="menu-title">Contact</span>
            </a>
        </div>
        @endPermission
        <!--end::Menu item-->
        <!--begin::Menu item-->
        @hasPermission("crm", "view", "list_file")
        <div class="menu-item px-3">
            <a href="{{ route('crm.list.index') }}?t=file" class="menu-link px-3 d-flex">
                <span class="menu-title">File</span>
            </a>
        </div>
        @endPermission
        <!--end::Menu item-->
    </div>
</div>
@hasPermission("crm", "view", "products")
<a href="{{ route('crm.products.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-none d-md-inline">{{ __('Product') }}</span>
</a>
@endPermission
@hasPermission("crm", "view", "archive")
<a href="{{route('crm.archive.index')}}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-none d-md-inline">{{ __('Archive') }}</span>
</a>
@endPermission
{{-- <a href="{{ route('crm.pref.index') }}"
    class="btn btn-flex btn-active-color-primary align-items-cenrer justify-content-center justify-content-md-between align-items-lg-center flex-md-content-between bg-opacity-10 px-0 ps-md-6 pe-md-5 h-30px h-md-35px">
    <span class="d-none d-md-inline">{{ __('Setting') }}</span>
</a> --}}
