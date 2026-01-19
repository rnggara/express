@extends('layouts.template')

@section('aside')
    <div class="lside d-inline">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column d-inline d-md-none mb-5 mb-md-0">
                    @if (\Helper_function::getProfile() < 100)
                        @php
                            $pct = \Helper_function::getProfile()
                        @endphp
                        <div class="d-flex">
                            <div class="d-flex flex-column flex-fill me-3">
                                <span class="fw-bold">Kelengkapan Profil {{ $pct }}%</span>
                                <span class="text-muted d-none d-md-inline">Lengkapkan profilmu untuk meningkatkan kesempatanmu</span>
                                <div class="progress flex-fill">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm">Lengkapi</button>
                        </div>
                    @endif
                </div>
                <!--begin::Menu-->
                <div class="flex-md-column flex-row fs-6 fw-semibold menu menu-column menu-gray-600 menu-rounded menu-sub-indention overflow-auto" id="#kt_aside_menu"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 d-none d-md-inline">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5 cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalUploadImage">
                                <img alt="Logo" src="{{ asset($user->user_img ?? 'theme/assets/media/avatars/blank.png') }}" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}
                                    {{-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> --}}
                                </div>
                                <a href="{{ route("account.info") }}?v=view_profile"
                                    class="fw-semibold text-muted text-hover-primary fs-7">Lihat Profil</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    @foreach ($aside_menu as $amenu)
                        <!--begin:Menu item-->
                        <div class="menu-item my-2">
                            <!--begin:Menu link-->
                            <a class="menu-link text-hover-primary {{ $v == $amenu['label'] ? 'text-active-primary active' : '' }} "
                                href="{{ route('account.info') }}?v={{ $amenu['label'] }}">
                                <span class="menu-icon">
                                    <i class="fi fi-{{ $amenu['icon'] }} fs-2"></i>
                                </span>
                                <span class="menu-title text-hover-primary text-nowrap {{ $v == $amenu['label'] ? 'text-primary' : '' }}">{{ __($amenu['id']) }}</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                    @endforeach
                </div>
                <!--end::Menu-->
            </div>
        </div>
    </div>
@endsection

@if (\Helper_function::getProfile() < 100)
    @section('rside')
        @component('layouts.components.complete_profile')

        @endcomponent
    @endsection
@endif

@section('content')
<div class="d-flex flex-column-fluid mt-n5 mt-md-0">
    <!--begin::Container-->
    <div class="ms-md-5 w-100">
        <!--begin::Profile Account Information-->
        <div class="d-flex flex-row">
            <!--begin::Content-->
            <div class="flex-row-fluid ml-lg-8">
                <!--begin::Card-->
                @include("users._$v")
            </div>
            <div class="modal fade" id="modalSign" data-backdrop="static" tabindex="-1" role="dialog"
                aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">Create/Upload your <span id="sign-title"></span></h1>
                        </div>
                        <form action="{{ route('account.sign.add', $user->id) }}" id="form-sign" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <div class="radio-inline">
                                                    <label class="radio radio-rounded">
                                                        <input type="radio" name="rb_sign" required class="rb-sign"
                                                            value="1" />
                                                        <span></span>
                                                        Create
                                                    </label>
                                                    <label class="radio radio-rounded">
                                                        <input type="radio" name="rb_sign" required class="rb-sign"
                                                            value="2" />
                                                        <span></span>
                                                        Upload
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="sign-upload">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-3">
                                                    Upload File
                                                </label>
                                                <div class="col-9">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                            id="input-file" name="file_upload">
                                                        <span class="custom-file-label">Choose File</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="sign-create">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="wrapper">
                                                        <canvas class="signature-pad border"></canvas>
                                                    </div>
                                                    <br>
                                                    <button type="button"
                                                        class="btn btn-primary btn-xs clear">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="type" id="sign-type">
                                <button type="button" class="btn btn-light-primary"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" name="submit_sign" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Profile Account Information-->
    </div>
    <!--end::Container-->
</div>
    <div class="modal fade" id="modalShow" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Loading</h1>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <form action="{{ route('account.setting.update_profile') }}" enctype="multipart/form-data" method="post" id="form-profile" class="form">
        <div class="modal fade" id="modalUploadImage" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1>Ganti Foto Profile</h1>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="type" value="image">
                        <div class="d-flex justify-content-center">
                            <div class="fv-row">
                                <!--begin::Image input-->
                                <div class="image-input image-input-empty border" data-kt-image-input="true">
                                    <!--begin::Image preview wrapper-->
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset($user->user_img ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                    <!--end::Image preview wrapper-->

                                    <!--begin::Edit button-->
                                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change"
                                    data-bs-toggle="tooltip"
                                    data-bs-dismiss="click"
                                    title="Ganti foto profile">
                                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                        <!--begin::Inputs-->
                                        <input type="file" name="profile_img" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="profile_img_remove" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Edit button-->

                                    <!--begin::Cancel button-->
                                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="cancel"
                                    data-bs-toggle="tooltip"
                                    data-bs-dismiss="click"
                                    title="Cancel">
                                        <i class="ki-outline ki-cross fs-3"></i>
                                    </span>
                                    <!--end::Cancel button-->
                                </div>
                                <!--end::Image input-->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('custom_script')
    <script src="{{ asset('theme/assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
    <script async>
        var avatar1 = new KTImageInput.getInstance($("#printed_logo"));

        function modal_sign(x) {
            $("#sign-title").text(x)
            $("#sign-type").val(x)
        }

        function modalShow(me) {
            var _title = $("#modalShow .modal-header").find("h1")
            var _modalContent = $("#modalShow .modal-body")
            var _footer = $("#modalShow .modal-footer")
            var _form = $("#modalShow form")
            _form.attr("action", "")
            $("#modalShow").modal("show")
            var _name = $(me).data("name")
            var _id = $(me).data("id")
            var _act = $(me).data("act")

            _title.text("Loading")
            _modalContent.addClass("spinner spinner-center spinner-lg")
            _modalContent.html("")
            _footer.html("")
            $.ajax({
                url: "{{ route('account.info')}}",
                type: "get",
                data: {
                    _name: _name,
                    _id: _id,
                    _act: _act,
                },
                dataType: "json",
                success: function(resp) {
                    _modalContent.removeClass("spinner spinner-center spinner-lg")
                    _title.text(resp.title)
                    _footer.html(resp.foot)
                    _modalContent.html(resp.view)
                    _form.attr("action", resp.url)
                    _form.find("input.number").number(true, 2, ",", ".")
                    $("select[data-control=select2]").each(function(){
                        var _parent = $(this).parents("#modalShow .modal-body")
                        var _hideSearch = $(this).data("hide-search") ? -1 : null
                        $(this).select2({
                            dropdownParent : _parent,
                            minimumResultsForSearch : _hideSearch
                        })
                    })


                    $("#modalShow")
                    if (resp.mode == "experience") {
                        var still_work = _form.find("input[name=still_work]")
                        if (still_work.attr("checked") == "checked") {
                        _form.find("select[name=end_month]").parent().prev().hide()
                        _form.find("select[name=end_month]").parent().hide()
                        _form.find("select[name=end_year]").parent().hide()
                        _form.find("select[name=end_month]").prop("required", false)
                        _form.find("select[name=end_year]").prop("required", false)
                    } else {
                        _form.find("select[name=end_month]").parent().prev().show()
                        _form.find("select[name=end_month]").parent().show()
                        _form.find("select[name=end_year]").parent().show()
                        _form.find("select[name=end_month]").prop("required", true)
                        _form.find("select[name=end_year]").prop("required", true)
                    }

                        still_work.click(function() {
                            var _check = this.checked
                            if (_check) {
                                _form.find("select[name=end_month]").parent().prev().hide()
                                _form.find("select[name=end_month]").parent().hide()
                                _form.find("select[name=end_year]").parent().hide()
                                _form.find("select[name=end_month]").prop("required", false)
                                _form.find("select[name=end_year]").prop("required", false)
                            } else {
                                _form.find("select[name=end_month]").parent().prev().show()
                                _form.find("select[name=end_month]").parent().show()
                                _form.find("select[name=end_year]").parent().show()
                                _form.find("select[name=end_month]").prop("required", true)
                                _form.find("select[name=end_year]").prop("required", true)
                            }
                        })
                    }

                    var still = _form.find("#still")
                    if (still.attr("checked") == "checked") {
                        _form.find("select[name=end_month]").parent().prev().hide()
                        _form.find("select[name=end_month]").parent().hide()
                        _form.find("select[name=end_year]").parent().hide()
                        _form.find("select[name=end_month]").prop("required", false)
                        _form.find("select[name=end_year]").prop("required", false)
                    } else {
                        _form.find("select[name=end_month]").parent().prev().show()
                        _form.find("select[name=end_month]").parent().show()
                        _form.find("select[name=end_year]").parent().show()
                        _form.find("select[name=end_month]").prop("required", true)
                        _form.find("select[name=end_year]").prop("required", true)
                    }

                    still.click(function() {
                        var _check = this.checked
                        if (_check) {
                            _form.find("select[name=end_month]").parent().prev().hide()
                            _form.find("select[name=end_month]").parent().hide()
                            _form.find("select[name=end_year]").parent().hide()
                            _form.find("select[name=end_month]").prop("required", false)
                            _form.find("select[name=end_year]").prop("required", false)
                        } else {
                            _form.find("select[name=end_month]").parent().prev().show()
                            _form.find("select[name=end_month]").parent().show()
                            _form.find("select[name=end_year]").parent().show()
                            _form.find("select[name=end_month]").prop("required", true)
                            _form.find("select[name=end_year]").prop("required", true)
                        }
                    })

                    $("#modalShow select[name=city_id]").change(function() {
                        var opt = $(this).find("option:selected")
                        var prov_id = opt.data("prov")
                        $("select[name=prov_id]").val(prov_id).trigger("change")
                    })

                    $("input[type=file]").change(function(){
                        var _t = $(this).prev()
                        var _f = $(this).val().split('\\')
                        _t.text(_f[_f.length - 1])
                        _t.addClass("text-primary")
                        _t.removeClass("text-muted")
                    })

                    Inputmask({
                        "mask" : "99-99-9999"
                    }).mask(".tempusDominus");

                    $(".tempusDominus").each(function(){
                        var _id = $(this).attr("id")
                        var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                            display : {
                                viewMode: "calendar",
                                components: {
                                    decades: true,
                                    year: true,
                                    month: true,
                                    date: true,
                                    hours: false,
                                    minutes: false,
                                    seconds: false
                                }
                            },
                            localization: {
                                locale: "id",
                                startOfTheWeek: 1,
                                format: "dd-MM-yyyy"
                            }
                        });
                    })

                    Inputmask({
                        "mask" : "9999-9999-9999"
                    }).mask(".no-telp");

                    $(".inputmask").each(function(){
                        Inputmask({
                            "mask" : "9999-9999-9999-9999"
                        }).mask($(this));
                    })
                }
            })
        }

        function readMore(me) {
            $("#dot").hide()
            $("#more").show()
            $(me).hide()
        }

        var type

        $(document).ready(function(params) {
            $("input.number").number(true, 2)
            var wrapper = document.getElementById("form-sign"),
                saveButton = wrapper.querySelector("[name=submit_sign]"),
                canvas = wrapper.querySelector("canvas"),
                signaturePad;

            signaturePad = new SignaturePad(canvas);

            saveButton.addEventListener('click', function(event) {
                event.preventDefault();
                if (type == 2) {
                    $.ajax({
                        url: '{{ route('account.sign.add', $user->id) }}',
                        type: 'POST',
                        data: new FormData(wrapper),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function(result) {
                            if (result.success === true) {
                                Swal.fire({
                                    title: "Success",
                                    text: result.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(res) {
                                    if (res.value) {
                                        location.reload()
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Failed",
                                    text: result.message,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(res) {
                                    if (res.value) {
                                        location.reload()
                                    }
                                });
                            }
                        }
                    });
                    return false;

                } else {
                    var dataUrl = signaturePad.toDataURL();
                    ardata = {
                        imageData: dataUrl,
                        _token: '{{ csrf_token() }}',
                        rb_sign: $("input[name=rb_sign]").val(),
                        type: $("#sign-type").val()
                    };
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('account.sign.add', $user->id) }}',
                        data: ardata,
                        dataType: "json",
                        success: function(result) {
                            console.log(result)
                            if (result.success === true) {
                                Swal.fire({
                                    title: "Success",
                                    text: result.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(res) {
                                    if (res.value) {
                                        location.reload()
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Failed",
                                    text: result.message,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                }).then(function(res) {
                                    if (res.value) {
                                        location.reload()
                                    }
                                });
                            }
                        }
                    });
                    return false;
                }
            })

            // var kt_logo = new KTImageInput.getInstance($("#kt_image_logo"));

            $("div[data-toggle=collapse]").click(function() {
                if ($(this).hasClass("collapsed")) {
                    var _text = "See Less"
                    var _i = `<i class="fa fa-chevron-up"></i>`
                    $(this).html(_text)
                    $(this).append(_i)
                } else {
                    var _text = "See More"
                    var _i = `<i class="fa fa-chevron-down"></i>`
                    $(this).html(_text)
                    $(this).append(_i)
                }
            })

            $("#modalShow").on("hidden", function() {
                $(this).find(".modal-body").html("")
            })

            $("select[name=city_id]").change(function() {
                var opt = $(this).find("option:selected")
                var prov_id = opt.data("prov")
                $("select[name=prov_id]").val(prov_id).trigger("change")
            })

            var _dt = document.getElementById("inputDates")
            if(_dt){
                var dt = new tempusDominus.TempusDominus(_dt, {
                    localization: {
                        locale: "id",
                        startOfTheWeek: 1,
                        format: "dd-MM-yyyy"
                    }
                });
            }

            $("#sign-create").hide()
            $("#sign-upload").hide()

            $(".rb-sign").click(function() {
                if (this.value == 1) {
                    $("#sign-create").show()
                    $("#sign-upload").hide()
                    signaturePad.clear();
                    $("#input-file").val("")
                    $(".custom-file-label").text("Choose File")
                    type = 1
                } else {
                    $("#sign-create").hide()
                    $("#sign-upload").show()
                    signaturePad.clear();
                    type = 2
                }
            })

            $('.clear').click(function() {
                signaturePad.clear();
            });


            $("#btn-random").click(function() {
                var att = $("#attend_code")
                $.ajax({
                    url: "{{ route('account.randomize') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: att.data("id")
                    },
                    beforeSend: function() {
                        $("#btn-random").addClass("spinner spinner-right").prop('disabled',
                            true)
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.reload()
                        }
                        $("#btn-random").removeClass("spinner spinner-right").prop('disabled',
                            false)
                    }
                })
            })

            $("#more").hide()

            $("#p_logo").change(function() {
                readURL(this, 'blah');
            });
        });
    </script>
@endsection
