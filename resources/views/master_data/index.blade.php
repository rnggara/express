@extends('layouts.template')

@section('aside')
<div class="lside min-w-300px">
    <div class="card">
        <div class="card-body">
            <!--begin::Menu-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_aside_menu"
                data-kt-menu="true">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <div class="menu-content d-flex align-items-center px-3">
                        <!--begin::Username-->
                        <div class="d-flex flex-column">
                            <div class="fw-bold d-flex align-items-center fs-5">Master Data
                                {{-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> --}}
                            </div>
                        </div>
                        <!--end::Username-->
                    </div>
                </div>
                <!--end::Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "locations" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "locations" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_location") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "educations" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "educations" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_study") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "marriege" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "marriege" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_marriege") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "religion" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "religion" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_religion") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "gender" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "gender" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_gender") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "language" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "language" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_language") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "industry" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "industry" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_industry") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "job_level" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "job_level" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_job_level") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "job_type" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "job_type" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_job_type") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "proficiency" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "proficiency" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_proficiency") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "skill" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "skill" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_skill") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item my-2">
                    <!--begin:Menu link-->
                    <a class="menu-link bg-hover-secondary {{ $v == "specialization" ? 'bg-active-secondary active' : '' }} "
                        href="{{ route('master_data.index') }}?v={{ "specialization" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ __("view.master_specialization") }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            </div>
            <!--end::Menu-->
        </div>
    </div>
</div>
@endsection

@section('content')
<!--end::Subheader-->
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class=" container-fluid ">
        <!--begin::Profile Overview-->
        <div class="d-flex flex-row">
            <!--begin::Aside-->
            <!--end::Aside-->

            <!--begin::Content-->
            <div class="flex-row-fluid ml-lg-8">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::List Widget 14-->
                        @if ($v == "locations")
                            @include("master_data._$v")
                        @else
                            @include("master_data._master")
                        @endif
                        <!--end::List Widget 14-->
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Profile Overview-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
@endsection

@section('custom_script')
<script>
    $(document).ready(function () {
        $("table.display").DataTable()
    })
</script>
@endsection
