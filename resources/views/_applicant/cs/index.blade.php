@extends('layouts.template')

{{-- @section('aside')
    @include('_applicant.cs.aside')
@endsection --}}

@section('content')
    <div class="d-flex flex-column">
        <div class="card card-custom bg-light-primary gradient-card-test mb-8">
            <div class="card-body">
                <div class="align-items-center d-flex flex-column flex-md-row justify-content-center">
                    <div class="d-flex flex-column ms-5 justify-content-between">
                        <span class="fw-bold fs-1 mb-5">Segera temukan tempat kerja yang cocok untukmu</span>
                        <span class="fw-semibold">Temukan seperti apa tempat kerja yang kamu inginkan sebelum kamu mengambil langkah selanjutnya. Cari ulasan dan peringkat, dan filter perusahaan berdasarkan kualitas yang paling penting untuk pencarian kerja kamu.</span>
                    </div>
                    <div class="min-w-300px"></div>
                    <img src="{{ asset("images/search_page.png") }}" alt="">
                </div>
            </div>
        </div>
        <div class="bg-white d-flex flex-column flex-md-row mb-8 p-5 rounded">
            <div class="input-group border rounded mb-3 mb-md-0 me-md-3">
                <span class="input-group-text bg-transparent border-0">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" class="form-control border-0" id="search" placeholder="Cari Perusahaan">
            </div>
            <div class="input-group border rounded mb-3 mb-md-0 me-md-3 pe-2">
                <span class="input-group-text bg-transparent border-0">
                    <i class="fa fa-map-marker-alt"></i>
                </span>
                <input type="text" class="form-control border-0" id="lokasi" placeholder="Lokasi Perusahaan">
            </div>
            <button type="button" class="btn btn-primary" onclick="search_job()">Cari</button>
            <div class="d-inline d-md-none filter-mobile mt-5">
                <div class="d-flex scroll hover-scroll align-items-center">
                    <!--begin::Menu-->
                    <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                            <!--begin::Menu link-->
                            <a href="javascript:;" class="menu-link py-3">
                                <span class="menu-title text-nowrap">Lokasi</span>
                                <span class="fa fa-chevron-down fs-9 ms-3"></span>
                            </a>
                            <!--end::Menu link-->

                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-dropdown p-3 w-200px mh-300px scroll">
                                <!--end::Menu item-->
                                @foreach ($flokasi as $id => $item)
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <div class="menu-content px-0">
                                            <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ $id }}" name="filter_lokasi[]" onchange="search_job()" id="mobileck{{ str_replace(" ", "_", $item) }}" />
                                                <label class="cursor-pointer form-check-label" for="mobileck{{ str_replace(" ", "_", $item) }}">
                                                    {{ ucwords($item) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!--begin::Menu-->
                    <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                            <!--begin::Menu link-->
                            <a href="javascript:;" class="menu-link py-3">
                                <span class="menu-title text-nowrap">Jumlah Karyawan</span>
                                <span class="fa fa-chevron-down fs-9 ms-3"></span>
                            </a>
                            <!--end::Menu link-->

                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-dropdown p-3 w-200px mh-300px scroll">
                                <!--end::Menu item-->
                                @foreach ($fkaryawan as $item)
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <div class="menu-content px-0">
                                            <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ ucwords($item) }}" name="filter_karyawan[]" onchange="search_job()" id="mobileck{{ str_replace(" ", "_", $item) }}" />
                                                <label class="cursor-pointer form-check-label" for="mobileck{{ str_replace(" ", "_", $item) }}">
                                                    {{ ucwords($item) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                @endforeach
                                <!--end::Menu item-->
                            </div>
                        </div>
                    </div>
                    <!--begin::Menu-->
                    <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                            <!--begin::Menu link-->
                            <a href="javascript:;" class="menu-link py-3">
                                <span class="menu-title text-nowrap">Industri</span>
                                <span class="fa fa-chevron-down fs-9 ms-3"></span>
                            </a>
                            <!--end::Menu link-->

                            <!--begin::Menu sub-->
                            <div class="menu-sub menu-sub-dropdown p-3 w-200px mh-300px scroll">
                                <!--begin::Menu item-->
                                @foreach ($findustri as $id => $item)
                                    <!--begin::Menu item-->
                                    <div class="menu-item">
                                        <div class="menu-content px-0">
                                            <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="{{ $id }}" name="filter_industri[]" onchange="search_job()" id="mobileck{{ str_replace(" ", "_", $item) }}" />
                                                <label class="cursor-pointer form-check-label" for="mobileck{{ str_replace(" ", "_", $item) }}">
                                                    {{ ucwords($item) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Menu item-->
                                @endforeach
                                <!--end::Menu item-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-baseline d-none">
                <span class="fw-semibold me-3">Filtered By</span>
                <div id="menu-filter-mobile"></div>
            </div>
        </div>
        <div class="d-flex flex-column flex-md-row">
            @include("_applicant.cs.aside")
            <div class="flex-fill">
                <div class="ms-md-10 w-100">
                    <div class="row rounded g-5" id="job-search">
                        <div class="col-12">
                            <div class="card card-custom gutter-b card-stretch bg-transparent">
                                <div class="card-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function init_location(){
            $.ajax({
                url : encodeURI("@auth{{ route("app.cs.getLocation") }}@else{{ route("app.cs_guest.getLocation") }}@endauth"),
                type : "get",
                dataType : "json"
            }).then(function(resp){
                var tagify = new Tagify(document.querySelector("#lokasi"), {
                    tagTextProp: "name",
                    whitelist : resp.locations,
                    enforceWhitelist: true,
                    dropdown: {
                        maxItems: 20,           // <- mixumum allowed rendered suggestions
                        classname: "", // <- custom classname for this dropdown, so it could be targeted
                        enabled: 0,             // <- show suggestions on focus
                        closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
                    },
                    callbacks : {
                        // "change" : function (e) {
                        //     var _head = $("#kt_header")
                        //     var _wrapper = $(_head).parents("div.wrapper")
                        //     var _height = _head.height()
                        //     _wrapper.css("padding-top", _height + 30)
                        // }
                    }
                })
            })
        }

        function _init_event(){
            $(".btn-bookmark").on( "mouseenter", function(){
                var _i = $(this).find("i")
                if($(_i).hasClass("fa")){
                    $(_i).addClass("far")
                    $(_i).removeClass("fa")
                } else {
                    $(_i).addClass("fa")
                    $(_i).removeClass("far")
                }
            }).on( "mouseleave", function(){
                var _i = $(this).find("i")
                if($(_i).hasClass("fa")){
                    $(_i).addClass("far")
                    $(_i).removeClass("fa")
                } else {
                    $(_i).addClass("fa")
                    $(_i).removeClass("far")
                }
            })
        }

        function init_search(){
            $("#job-search").html(`<div class="col-12">
                    <div class="card card-custom gutter-b card-stretch bg-transparent">
                        <div class="card-body">

                        </div>
                    </div>
                </div>`)
        }

        function removeAll(me){
            $(".badge-filter").each(function(){
                removeTag(this)
            })
        }

        function removeTag(me){
            var menu = $(".lside")
            if($(menu).is(":hidden")){
                menu = $(".filter-mobile")
            }
            var id = $(me).data("id")
            console.log($(menu).find(`#${id}`))
            $(menu).find(`#${id}`).click()
        }

        function search_job(){
            var _job = $("#search-job-input").val()
            var _loc = $("#search-location-input").val()
            init_search()
            search_loading()

            var $el = ""

            var menu = $(".lside")
            if($(menu).is(":hidden")){
                menu = $(".filter-mobile")
            }

            $(menu).find(".ck-filter:checked").each(function() {
                var $val = $(this).val()
                var id = $(this).attr("id")
                var lbl = $(this).next()
                $el += `<span class="badge badge-filter badge-primary badge-lg cursor-pointer me-3 mb-3 bg-hover-light-primary" onclick="removeTag(this)" data-id="${id}">${lbl.text()}</span>`
            })

            if($el != ""){
                $el += `<span class="badge badge-danger badge-lg cursor-pointer me-3 mb-3 bg-hover-light-danger" onclick="removeAll(this)"><i class="fa fa-times me-2 text-white"></i> Hapus Filter</span>`
            }

            if($(menu).hasClass("filter-mobile")){
                if($el != ""){
                    $("#menu-filter-mobile").parent().removeClass("d-none")
                } else {
                    $("#menu-filter-mobile").parent().addClass("d-none")
                }
                $("#menu-filter-mobile").html($el)
            } else {
                $("#filter-badge").html($el)
            }

            $.ajax({
                url : "@auth{{ route("app.cs.search") }}@else{{ route("app.cs_guest.search") }}@endauth",
                type : "post",
                dataType : "json",
                data : {
                    // job : _job,
                    // loc : JSON.parse((_loc == "" ? "[]" : _loc)),
                    name : $("#search").val(),
                    loc : $("#lokasi").val(),
                    sort : $("#search-sort").val(),
                    flokasi : $(menu).find("input[name='filter_lokasi[]']:checked").map(function(){return $(this).val();}).get(),
                    fkaryawan : $(menu).find("input[name='filter_karyawan[]']:checked").map(function(){return $(this).val();}).get(),
                    findustri : $(menu).find("input[name='filter_industri[]']:checked").map(function(){return $(this).val();}).get(),
                    _token : "{{ csrf_token() }}"
                }
            }).then(function(resp){
                $("#job-search").html(resp.view)
                _init_event()
            })
        }

        var blockUI = new KTBlockUI(document.querySelector("#job-search"), {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        })

        function search_loading(){
            blockUI.block();
        }

        $(document).ready(function(){
            init_location()
            $("#search-button").click(function(){
                search_job()
            })
            search_job()
        })
    </script>
@endsection
