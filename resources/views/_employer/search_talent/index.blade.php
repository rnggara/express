@extends('layouts.template')

@section('content')
    <div class="d-flex flex-column">
        <div class="card mb-8 border">
            <div class="card-header border-0">
                <h3 class="card-title">Kandidat</h3>
                <div class="card-toolbar">
                    <a href="{{ route("search_talent.bookmark_page") }}">Lihat Bookmark</a>
                </div>
            </div>
        </div>
        <div class="card border mb-8">
            <div class="card-body rounded">
                <div class="d-flex flex-column">
                    <span class="fw-bold mb-3">Cari kandidat menggunakan kata kunci</span>
                    <span class="mb-5"></span>
                    <div class="d-flex align-items-center flex-column flex-md-row">
                        <div class="border rounded p-3 align-items-center d-flex flex-column flex-md-row flex-fill me-md-5 mb-5 mb-md-0 w-100 w-md-auto">
                            <div class="d-flex align-items-center mb-3 mb-md-0">
                                <i class="fa fa-search me-3 text-dark"></i>
                                <span class="me-3 text-muted">Cari Berdasarkan</span>
                            </div>
                            <button type="button" class="btn btn-secondary w-100 w-md-auto mb-3 mb-md-0 btn-sm rounded-4 me-md-3" data-bs-toggle="modal" data-bs-target="#modalAddJabatan">
                                Jabatan
                                <span id="list-jabatan"></span>
                                <input type="hidden" name="jabatan_list">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                            <button type="button" class="btn btn-secondary w-100 w-md-auto mb-3 mb-md-0 btn-sm rounded-4 me-md-3" data-bs-toggle="modal" data-bs-target="#modalAddSkill">
                                Skill
                                <span id="list-skill"></span>
                                <input type="hidden" name="skill_list">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                            <button type="button" class="btn btn-secondary w-100 w-md-auto mb-3 mb-md-0 btn-sm rounded-4" data-bs-toggle="modal" data-bs-target="#modalAddPengalaman">
                                Pengalaman
                                <span id="list-pengalaman"></span>
                                <input type="hidden" name="pengalaman_list">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        </div>
                        <div class="border rounded p-3 align-items-center d-flex flex-column flex-md-row flex-fill me-md-5 mb-5 mb-md-0 w-100 w-md-auto">
                            <div class="position-relative">
                                <input type="text" name="fname" class="form-control border-0 ps-7" placeholder="Cari Berdasarkan Nama" id="">
                                <i class="fa fa-search me-3 text-dark position-absolute top-25 mt-1"></i>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="btn-search-kandidat">
                            Cari Kandidat
                        </button>
                    </div>
                    <form action="" id="form-filter-mobile">
                        <div class="d-inline d-md-none filter-mobile mt-5">
                            <div class="d-flex scroll hover-scroll align-items-center">
                                @foreach ($sort as $key => $label)
                                    <!--begin::Menu-->
                                    <div class="menu menu-rounded menu-column menu-gray-600 menu-state-bg fw-semibold" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                            <!--begin::Menu link-->
                                            <a href="javascript:;" class="menu-link py-3">
                                                <span class="menu-title text-nowrap">{{ $label }}</span>
                                                <span class="fa fa-chevron-down fs-9 ms-3"></span>
                                            </a>
                                            <!--end::Menu link-->

                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown p-3 w-200px mh-300px scroll">
                                                <!--end::Menu item-->
                                                @foreach ($dataSort[$key] as $isort => $item)
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item">
                                                        <div class="menu-content px-0">
                                                            <div class="form-check form-check-sm form-check-custom form-check-info form-check-solid">
                                                                <input class="form-check-input" type="checkbox" name="{{ "ck$key" }}[]" value="{{$isort}}" id="{{ "ck$key"."_".$isort }}" />
                                                                <label class="form-check-label" for="{{ "ck$key"."_".$isort }}">
                                                                    {{$item}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                    <div class="d-flex align-items-baseline d-none">
                        <span class="fw-semibold me-3">Filtered By</span>
                        <div id="menu-filter-mobile"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-md-row">
            <div class="card border min-w-300px me-8 d-none d-md-inline lside">
                <div class="card-body">
                    <form action="" id="form-filter">
                    <div class="d-flex flex-column">
                        <div class="d-flex mb-8 align-items-center justify-content-between">
                            <span class="fw-bold fs-2">Filter</span>
                            <div class="card-toolbar">
                                <button type="button" onclick="removeAll()" id="btn-remove-filter" class="btn text-danger" style="display: none;">Clear All</button>
                            </div>
                        </div>
                        <div id="filter-badge"></div>
                        @foreach ($sort as $key => $label)
                            <div class="accordion accordion-icon-toggle" id="kt_accordion_{{ $key }}">
                                <!--begin::Item-->
                                <div class="mb-5">
                                    <!--begin::Header-->
                                    <div class="accordion-header py-3 d-flex collapsed justify-content-between" data-bs-toggle="collapse" data-bs-target="#kt_accordion_{{ $key }}_item_1">
                                        <h3 class="fs-4 fw-semibold mb-0">{{ $label }}</h3>
                                        <span class="accordion-icon">
                                            <i class="ki-duotone ki-right text-dark fs-4"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div id="kt_accordion_{{ $key }}_item_1" class="fs-6 collapse" data-bs-parent="#kt_accordion_{{ $key }}">
                                        <div class="scroll mh-500px">
                                            <div class="d-flex flex-column p-2">
                                                @foreach ($dataSort[$key] as $isort => $item)
                                                    <div class="form-check mb-5">
                                                        <input class="form-check-input" type="checkbox" name="{{ "ck$key" }}[]" value="{{$isort}}" id="{{ "sideck$key"."_".$isort }}" />
                                                        <label class="form-check-label" for="{{ "sideck$key"."_".$isort }}">
                                                            {{$item}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Item-->
                            </div>
                        @endforeach
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-fill card border">
                <div class="card-body">
                    <div id="search-div" class="h-100">
                        <div class="h-100 d-flex flex-column justify-content-center align-items-center">
                            <img src="{{ asset("images/search-talent.png") }}" alt="" class="w-50 w-md-25 mb-5">
                            <span class="fw-bold fs-2tx mb-8 text-center">Mulai Cari Kandidat Sekarang</span>
                            <span class="text-center">Dengan mencari kandidat Anda akan mudah terhubung dengan kandidat dengan cepat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="modalAddJabatan">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="fv-row mb-5">
                        <label for="search-jabatan" class="col-form-label">Cari Jabatan</label>
                        <input type="text" class="form-control tag" id="jabatan" placeholder="Jabatan">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="add_search(this)" data-target-input="jabatan_list" data-target="#list-jabatan">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="modalAddSkill">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="fv-row mb-5">
                        <label for="search-skill" class="col-form-label">Cari Skill</label>
                        <input type="text" class="form-control tag" id="skill" placeholder="Skill">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="add_search(this)" data-target-input="skill_list" data-target="#list-skill">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="modalAddPengalaman">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="fv-row mb-5">
                        <label for="search-pengalaman" class="col-form-label">Cari Pengalaman</label>
                        <input type="text" class="form-control tag" id="pengalaman" placeholder="Pengalaman">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" onclick="add_search(this)" data-target-input="pengalaman_list" data-target="#list-pengalaman">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')

<script>

    var target = document.querySelector("#search-div");
    var blockUI = new KTBlockUI(target);

    function add_search(me){
        var target = $(me).data("target")
        var modal = $(me).parents(".modal")
        var tag_val = $(modal).find("input[type=text]").val()
        var target_input = $(me).data("target-input")
        $(target).html("")
        if(tag_val != ""){
            var tag = JSON.parse(tag_val)
            var target_label = ""
            for (const key in tag) {
                if (Object.hasOwnProperty.call(tag, key)) {
                    const element = tag[key];
                    target_label += element.value + ", "
                }
            }
            $(target).html(": " + target_label)
            $(target).parent().removeClass("btn-secondary")
            $(target).parent().addClass("btn-primary")
        } else {
            $(target).parent().removeClass("btn-primary")
            $(target).parent().addClass("btn-secondary")
        }

        $(target).parent().find("input[name="+target_input+"]").val(tag_val)

        modal.modal("hide")
    }

    function getTag(type){
        $.ajax({
            url: encodeURI("{{ route('search_talent.index') }}?a=tag&q="+type),
            type: "get",
            dataType: "json"
        }).then(function(resp){
            init_tag(type, resp.tags)
        })
    }

    function init_tag(tag, tags) {
        var tagify = new Tagify(document.querySelector(`#${tag}`), {
            whitelist: tags,
            dropdown: {
                maxItems: 20, // <- mixumum allowed rendered suggestions
                classname: "", // <- custom classname for this dropdown, so it could be targeted
                enabled: 0, // <- show suggestions on focus
                closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
            },
            callbacks: {
                "change": function(e) {}
            }
        })
    }

    function show_toast(msg){
        toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toastr-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
        };

        toastr.success(msg);
    }

    function searchKandidat(url = null){
        var jabatan = $("input[name=jabatan_list]").val()
        var skill = $("input[name=skill_list]").val()
        var pengalaman = $("input[name=pengalaman_list]").val()
        var nama = $("input[name=fname]").val()
        var menu = $(".lside")
        if($(menu).is(":hidden")){
            menu = $(".filter-mobile")
        }

        var $el = ""

        $(menu).find("input[type=checkbox]:checked").each(function() {
            var $val = $(this).val()
            var id = $(this).attr("id")
            var lbl = $(this).next()
            $el += `<span class="badge badge-filter badge-primary badge-lg cursor-pointer me-3 mb-3 bg-hover-light-primary" onclick="removeTag(this)" data-id="${id}">${lbl.text()}</span>`
        })

        if($el != ""){
            $el += `<span class="badge badge-danger badge-lg cursor-pointer me-3 mb-3 bg-hover-light-danger" onclick="removeAll(this)"><i class="fa fa-times me-2 text-white"></i> Hapus Filter</span>`
        }

        if($(menu).hasClass("filter-mobile")){
            var filter = $("#form-filter-mobile").serialize()
            if($el != ""){
                $("#menu-filter-mobile").parent().removeClass("d-none")
            } else {
                $("#menu-filter-mobile").parent().addClass("d-none")
            }
            $("#menu-filter-mobile").html($el)
        } else {
            var filter = $("#form-filter").serialize()
            $("#filter-badge").html($el)
        }

        var route = url != null ? url + "&" + filter : "{{ route("search_talent.search") }}?" + filter
        console.log(route)

        var post = {
            _token : "{{ csrf_token() }}",
            jabatan : jabatan,
            skill : skill,
            pengalaman : pengalaman,
            name : nama
        }

        blockUI.block();

        $.ajax({
            url : route,
            type : "POST",
            data : post,
            dataType : "JSON"
        }).then(function(resp){
            $("#search-div").html(resp.view)
            blockUI.release();
            $("[data-toggle=bookmark]").click(function(){
                var $i = $(this).find("i")
                var id = $(this).data("id")
                $.ajax({
                    url : "{{ route("search_talent.bookmark") }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id
                    }
                }).then(function(resp){
                    if(resp.bookmark){
                        $i.removeClass("far text-dark")
                        $i.addClass("fa text-primary")
                        show_toast("Ditambahkan ke bookmark")
                    } else {
                        $i.removeClass("fa text-primary")
                        $i.addClass("far text-dark")
                        show_toast("Dihapus dari bookmark")
                    }
                })
            })

            $(".pagination a.page-link").click(function(e){
                e.preventDefault()
                searchKandidat($(this).attr("href"))
            })
        })
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

    $(document).ready(function(){
        getTag("jabatan")
        getTag("skill")
        getTag("pengalaman")

        $("#btn-search-kandidat").click(function(){
            searchKandidat()
        })

        $("#form-filter input[type=checkbox]").click(function(){
            searchKandidat()
        })

        $(".filter-mobile input[type=checkbox]").click(function(){
            searchKandidat()
        })
    })
</script>

@endsection
