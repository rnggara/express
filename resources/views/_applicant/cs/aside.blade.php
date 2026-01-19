<style>
    .form-check-input:checked {
        background-color: #5936d9;
    }
</style>

<div class="lside d-none d-md-inline mw-300px">
    <div class="card">
        <div class="card-body">
            <!--begin::Menu-->
            <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6"
                id="#menu_sort" data-kt-menu="true">
                <div class="menu-item">
                    <div class="menu-content d-flex align-items-center">
                        <div class="d-flex align-items-center fs-5 text-gray-600 text-nowrap me-3">
                            <span>Peringkat</span>
                        </div>
                        <select name="sort_by" class="form-select" data-control="select2" onchange="search_job()" data-hide-search="true" data-placeholder="Pilih" id="search-sort">
                            <option value=""></option>
                            <option value="5">Bintang 5</option>
                            <option value="4">Bintang 4</option>
                            <option value="3">Bintang 3</option>
                            <option value="2">Bintang 2</option>
                            <option value="1">Bintang 1</option>
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
                        @foreach ($flokasi as $id => $item)
                            <!--begin::Menu item-->
                            <div class="menu-item">
                                <div class="menu-content px-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ $id }}" name="filter_lokasi[]" onchange="search_job()" id="ck{{ str_replace(" ", "_", $item) }}" />
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
                        <span class="menu-title">Jumlah Karyawan</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-accordion show menu-sub-indention h-150px scroll">
                        @foreach ($fkaryawan as $item)
                            <!--begin::Menu item-->
                            <div class="menu-item">
                                <div class="menu-content px-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ ucwords($item) }}" name="filter_karyawan[]" onchange="search_job()" id="ck{{ str_replace(" ", "_", $item) }}" />
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
                        <span class="menu-title">Industri</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <!--end::Menu link-->

                    <!--begin::Menu sub-->
                    <div class="menu-sub menu-sub-accordion show menu-sub-indention h-150px scroll">
                        @foreach ($findustri as $id => $item)
                            <!--begin::Menu item-->
                            <div class="menu-item">
                                <div class="menu-content px-0">
                                    <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                        <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ $id }}" name="filter_industri[]" onchange="search_job()" id="ck{{ str_replace(" ", "_", $item) }}" />
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
        </div>
    </div>
</div>
