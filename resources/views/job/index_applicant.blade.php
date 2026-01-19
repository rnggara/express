@extends('layouts.template', ["sub_head" => "job", "menuFilter" => "job", "dataFilter" => ["fedu" => $fedu, "flokasi" => $flokasi, "fspec" => $fspec, "ftype" => $ftype]])

@section('aside')
    @include('job.aside')
@endsection

@auth
@if (\Helper_function::getProfile() < 100)
    @section('rside')
        @component('layouts.components.complete_profile')

        @endcomponent
    @endsection
@endif
@endauth

@section('content')
    <div class="d-flex flex-column-fluid ms-md-5">
        <div class="d-flex flex-column rounded w-100" id="job-search">

        </div>
    </div>
@endsection

@section('custom_script')
    @include('job._bookmark')
    <script>
        function init_location(){
            $.ajax({
                url : encodeURI("@auth{{ route("applicant.job.index") }}@else{{ route("applicant.job_guest.index") }}@endauth?a=location"),
                type : "get",
                dataType : "json"
            }).then(function(resp){
                var tagify = new Tagify(document.querySelector("#search-location-input"), {
                    whitelist : resp.locations,
                    enforceWhitelist: true,
                    dropdown: {
                        maxItems: 20,           // <- mixumum allowed rendered suggestions
                        classname: "", // <- custom classname for this dropdown, so it could be targeted
                        enabled: 0,             // <- show suggestions on focus
                        closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
                    },
                    callbacks : {
                        "change" : function (e) {
                            var _head = $("#kt_header")
                            var _wrapper = $(_head).parents("div.wrapper")
                            var _height = _head.height()
                            _wrapper.css("padding-top", _height + 30)
                        }
                    }
                })

                $(".tagify__input").css("margin-top", "-3px")
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

            $(".btn-bookmark").click(function(){
                var _i = $(this).find("i")
                var _id = $(this).data("id")
                bookmark(this, _id, function(resp){
                    if(resp.booked){
                        if($(_i).hasClass("fa")){
                            $(_i).addClass("far")
                            $(_i).removeClass("fa")
                        } else {
                            $(_i).addClass("fa")
                            $(_i).removeClass("far")
                        }

                        show_toast("Ditambahkan ke bookmark")
                    } else {
                        if($(_i).hasClass("fa")){
                            $(_i).addClass("far")
                            $(_i).removeClass("fa")
                        } else {
                            $(_i).addClass("fa")
                            $(_i).removeClass("far")
                        }

                        show_toast("Dihapus dari bookmark")
                    }
                })
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

        function search_job(is_search = false){
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
                var $val = $(this).next().text()
                var id = $(this).attr("id")
                $el += `<span class="badge badge-primary badge-lg badge-filter cursor-pointer me-3 mb-3 bg-hover-light-primary" onclick="removeTag(this)" data-id="${id}">${$val}</span>`
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
                var mHead = $("#kt_header").height()
                var mOffset = mHead + 30
                $("#kt_wrapper").css("padding-top", `${mOffset}px`)
                $("#kt_aside").css("top", `${mOffset}px`)
            } else {
                $("#filter-badge").html($el)
            }

            $.ajax({
                url : "@auth{{ route("applicant.job.search") }}@else{{ route("applicant.job_guest.search") }}@endauth",
                type : "post",
                dataType : "json",
                data : {
                    job : _job,
                    loc : JSON.parse((_loc == "" ? "[]" : _loc)),
                    sort : $("#search-sort").val(),
                    flokasi : $(menu).find("input[name='filter_lokasi[]']:checked").map(function(){return $(this).val();}).get(),
                    fspec : $(menu).find("input[name='filter_spec[]']:checked").map(function(){return $(this).val();}).get(),
                    ftype : $(menu).find("input[name='filter_type[]']:checked").map(function(){return $(this).val();}).get(),
                    fsalary : $(menu).find("input[name='filter_salary[]']:checked").map(function(){return $(this).val();}).get(),
                    fedu : $(menu).find("input[name='filter_edu[]']:checked").map(function(){return $(this).val();}).get(),
                    fgender : $(menu).find("input[name='filter_gender[]']:checked").map(function(){return $(this).val();}).get(),
                    is_search : is_search,
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
            @if(!empty($cari))
                $("#search-job-input").val('{{ $cari['pos'] ?? "" }}')
                $("#search-location-input").val('{!! $cari['loc'] ?? "" !!}')
            @endif
            init_location()
            $("#search-button").click(function(){
                search_job(true)
            })
            search_job()
        })
    </script>
@endsection
