<style>
    .form-check-input:checked {
        background-color: #5936d9;
    }
</style>

<div class="lside w-300px d-none d-md-inline">
    <div class="card">
        <div class="card-body">
            <!--begin::Menu-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#menu_sort" data-kt-menu="true">
                <div class="menu-item">
                    <div class="menu-content d-flex align-items-center">
                        <div class="d-flex align-items-center fs-5 text-gray-600 text-nowrap me-3">
                            <span>Urutkan</span>
                        </div>
                        <select name="sort_by" class="form-select" data-control="select2" onchange="search_job(true)" data-hide-search="true" data-placeholder="Pilih" id="search-sort">
                            <option value=""></option>
                            <option value="no">Tidak ada sorting</option>
                            <option value="Lokasi">Lokasi</option>
                            <option value="Salary">Salary</option>
                            <option value="Job Title">Job Title</option>
                            <option value="Specialis">Specialis</option>
                            <option value="Work Experience">Work Experience</option>
                            <option value="Strata Pendidikan">Strata Pendidikan</option>
                            <option value="Tipe Pekerjaan">Tipe Pekerjaan</option>
                            <option value="Gender">Gender</option>
                            <option value="Umur">Umur</option>
                        </select>
                    </div>
                </div>
                <div id="filter-badge">
                </div>
            </div>
            <!--end::Menu item-->
            <div class="separator my-5"></div>
            <!--begin::Menu item-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#menu_loc" data-kt-menu="true">
                <div class="menu-item menu-link-indention menu-accordion show" data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3">
                        <span class="menu-title">Lokasi</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-accordion show menu-sub-indention h-150px scroll">
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <div class="menu-content px-0">
                                <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                    <input class="cursor-pointer form-check-input ck-filter" name="filter_lokasi[]" onchange="search_job(true)" type="checkbox" value="Remote" id="ckRemote" />
                                    <label class="cursor-pointer form-check-label" for="ckRemote">
                                        Remote
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu item-->
                        @foreach ($flokasi as $id => $item)
                            <!--begin::Menu item-->
                            <div class="menu-item">
                                <div class="menu-content px-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" {{ in_array($id, $locSelected) ? "checked" : "" }}  value="{{ ucwords($item) }}" name="filter_lokasi[]" onchange="search_job(true)" id="ck{{ str_replace(" ", "_", $item) }}" />
                                        <label class="cursor-pointer form-check-label" for="ck{{ str_replace(" ", "_", $item) }}">
                                            {{ ucwords($item) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--end::Menu item-->
                        @endforeach
                    </div>
                    <!--end::Menu sub-->
                </div>
            </div>
            <!--end::Menu item-->
            <div class="separator my-5"></div>
            <!--begin::Menu item-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#menu_spec" data-kt-menu="true">
                <div class="menu-item menu-link-indention menu-accordion show" data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3">
                        <span class="menu-title">Spesialisasi</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-accordion show menu-sub-indention h-150px scroll">
                        @foreach ($fspec as $id=> $item)
                            <!--begin::Menu item-->
                            <div class="menu-item">
                                <div class="menu-content px-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ $id }}" {{ in_array($id, $specSelected) ? "checked" : "" }} name="filter_spec[]" onchange="search_job(true)" id="ck{{ str_replace(" ", "_", $item) }}" />
                                        <label class="cursor-pointer form-check-label" for="ck{{ str_replace(" ", "_", $item) }}">
                                            {{ ucwords($item) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--end::Menu item-->
                        @endforeach
                    </div>
                    <!--end::Menu sub-->
                </div>
            </div>
            <!--end::Menu item-->
            <div class="separator my-5"></div>
            <!--begin::Menu item-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#menu_tp" data-kt-menu="true">
                <div class="menu-item menu-link-indention menu-accordion show" data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3">
                        <span class="menu-title">Tipe Kepegawaian</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-accordion show menu-sub-indention">
                        @foreach ($ftype as $item)
                            <!--begin::Menu item-->
                            <div class="menu-item">
                                <div class="menu-content px-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ ucwords($item) }}" name="filter_type[]" onchange="search_job(true)" id="ck{{ str_replace(" ", "_", $item) }}" />
                                        <label class="cursor-pointer form-check-label" for="ck{{ str_replace(" ", "_", $item) }}">
                                            {{ ucwords($item) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--end::Menu item-->
                        @endforeach
                    </div>
                    <!--end::Menu sub-->
                </div>
            </div>
            <div class="separator my-5"></div>
            <!--begin::Menu item-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#menu_sal" data-kt-menu="true">
                <div class="menu-item menu-link-indention menu-accordion show" data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3">
                        <span class="menu-title">Gaji</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-accordion show menu-sub-indention">
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <div class="menu-content px-0">
                                <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                    <input class="cursor-pointer form-check-input ck-filter" name="filter_salary[]" onchange="search_job(true)" type="checkbox" value="1.000.000 - 5.000.000" id="ck1000" />
                                    <label class="cursor-pointer form-check-label" for="ck1000">
                                        1.000.000 - 5.000.000
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <div class="menu-content px-0">
                                <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                    <input class="cursor-pointer form-check-input ck-filter" name="filter_salary[]" onchange="search_job(true)" type="checkbox" value="5.000.000 - 10.000.000" id="ck5000" />
                                    <label class="cursor-pointer form-check-label" for="ck5000">
                                        5.000.000 - 10.000.000
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item">
                            <div class="menu-content px-0">
                                <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                    <input class="cursor-pointer form-check-input ck-filter" name="filter_salary[]" onchange="search_job(true)" type="checkbox" value="> 10.000.000" id="ckMore" />
                                    <label class="cursor-pointer form-check-label" for="ckMore">
                                        > 10.000.000
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu sub-->
                </div>
            </div>
            <!--end::Menu item-->
            <div class="separator my-5"></div>
            <!--begin::Menu item-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#menu_edu" data-kt-menu="true">
                <div class="menu-item menu-link-indention menu-accordion show" data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3">
                        <span class="menu-title">Edukasi</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-accordion show menu-sub-indention h-150px scroll">
                        @foreach ($fedu as $item)
                            <!--begin::Menu item-->
                            <div class="menu-item">
                                <div class="menu-content px-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ ucwords($item) }}" name="filter_edu[]" onchange="search_job(true)" id="ck{{ str_replace(" ", "_", $item) }}" />
                                        <label class="cursor-pointer form-check-label" for="ck{{ str_replace(" ", "_", $item) }}">
                                            {{ ucwords($item) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--end::Menu item-->
                        @endforeach
                    </div>
                    <!--end::Menu sub-->
                </div>
            </div>
            <!--end::Menu item-->
            <div class="separator my-5"></div>
            <!--begin::Menu item-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#menu_gender" data-kt-menu="true">
                <div class="menu-item menu-link-indention menu-accordion show" data-kt-menu-trigger="click">
                    <!--begin::Menu link-->
                    <a href="#" class="menu-link py-3">
                        <span class="menu-title">Gender</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-accordion show menu-sub-indention">
                        @foreach ($fgender as $item)
                            <!--begin::Menu item-->
                            <div class="menu-item">
                                <div class="menu-content px-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ ucwords($item) }}" name="filter_gender[]" onchange="search_job(true)" id="ck{{ str_replace(" ", "_", $item) }}" />
                                        <label class="cursor-pointer form-check-label" for="ck{{ str_replace(" ", "_", $item) }}">
                                            {{ ucwords($item) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--end::Menu item-->
                        @endforeach
                    </div>
                    <!--end::Menu sub-->
                </div>
            </div>
            <!--end::Menu item-->
        </div>
    </div>
</div>
