@php
    $routeName = Route::getFacadeRoot()
        ->current()
        ->getAction()['as'];
@endphp

<div id="kt_aside" class="aside card bg-transparent border-right" style="left: 0px; width: 200px" data-kt-drawer="true"
    data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'100px', '200px': '150px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_toggle">
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid px-4">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y mh-100 my-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="{default: '#kt_aside_footer', lg: '#kt_header, #kt_aside_footer'}"
            data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu" data-kt-scroll-offset="{default: '5px', lg: '75px'}">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_aside_menu"
                data-kt-menu="true">
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link text-hover-primary {{ stripos($routeName, 'crm.index') !== false ? 'active bg-active-secondary' : '' }}"
                        href="{{ route('crm.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-home fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title text-active-primary">{{ __('Dashboard') }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link text-hover-primary {{ stripos($routeName, 'crm.lead') !== false ? 'active bg-active-secondary' : '' }}"
                        href="{{ route('crm.lead.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-briefcase fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title text-active-primary">{{ __('Lead') }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ stripos($routeName, 'crm.list') !== false ? ' show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ _('List') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion {{ stripos($routeName, 'crm.list') !== false ? 'show' : '' }}">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ isset($t) && $t == "perusahaan" ? 'active bg-active-secondary' : '' }}" href="{{ route('crm.list.index') }}?t=perusahaan">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-cube-2 fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Perusahaan</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ isset($t) && $t == "kontak" ? 'active bg-active-secondary' : '' }}" href="{{ route('crm.list.index') }}?t=kontak">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-badge fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Kontak</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link {{ isset($t) && $t == "file" ? 'active bg-active-secondary' : '' }}" href="{{ route('crm.list.index') }}?t=file">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-file fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">File</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link text-hover-primary {{ stripos($routeName, 'crm.products') !== false ? 'active bg-active-secondary' : '' }}"
                        href="{{ route('crm.products.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-cube-3 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title text-active-primary">{{ __('Product') }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            </div>
            <!--end::Menu-->
        </div>
    </div>
    <!--end::Aside menu-->
</div>
