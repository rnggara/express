<div class="position-fixed end-0 me-10 mt-10 z-index-1" style="top: 90%">
    <button type="button" class="btn btn-primary btn-icon btn-circle" data-kt-menu-trigger="click" data-kt-menu-placement="left-end" data-kt-menu-offset="15px, 20px">
        <i class="fi fi-rr-plus"></i>
    </button>
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded shadow-none bg-transparent menu-state-bg fw-semibold w-auto py-3"
        data-kt-menu="true">
        @foreach ($fab as $fa)
            <!--begin::Menu item-->
            <div class="menu-item bg-white rounded shadow mb-5 px-3">
                <a href="{{ $fa['url'] ?? 'javascript:;' }}" {!! $fa['toggle'] ?? "" !!} class="menu-link px-3 d-flex">
                    <span class="menu-title">{{ $fa['label'] }}</span>
                </a>
            </div>
            <!--end::Menu item-->
        @endforeach
    </div>
</div>
