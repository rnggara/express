@extends('_ess.layout', [
    "contentHeight" => "h-100"
])

@section('view_content')
    <div class="card shadow-none card-p-0 h-100">
        <div class="card-body">
            <div class="d-flex flex-column gap-5 h-100">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px" id="kt_job_aside_toggle">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-user text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Profil</span>
                        <span>Lihat detil profil Anda disini</span>
                    </div>
                </div>
                <div class="d-flex flex-row flex-fill gap-5">
                    <div id="kt_drawer_example_basic d-md-inline" class="d-flex flex-column flex-md-row">
                        <div class="card min-w-300px d-md-inline pe-5 border border-bottom-0 border-left-0 border-top-0 rounded-0" id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true"
                        data-kt-drawer-toggle="#kt_job_aside_toggle" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
                        data-kt-drawer-direction="start" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true">
                            {{-- being::nav --}}
                            <!--begin::Menu-->
                            <div class="menu menu-rounded menu-column menu-title-gray-700 pt-0 menu-icon-gray-400 menu-arrow-gray-400 menu-bullet-gray-400 menu-arrow-gray-400 menu-state-bg-light-primary fw-semibold w-100 p-3" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item my-1">
                                    <a href="javascript:;" data-toggle="nav" data-section="private_data" class="menu-link px-4">
                                        <span class="menu-icon">
                                            <i class="fi fi-rr-user text-active-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark text-active-primary text-hover-primary">Data Pribadi</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item my-1">
                                    <a href="javascript:;" data-toggle="nav" data-section="family_data" class="menu-link px-4">
                                        <span class="menu-icon">
                                            <i class="fi fi-rr-users-alt text-active-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark text-active-primary text-hover-primary">Data Keluarga</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item my-1">
                                    <a href="javascript:;" data-toggle="nav" data-section="education" class="menu-link px-4">
                                        <span class="menu-icon">
                                            <i class="fi fi-rr-school text-active-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark text-active-primary text-hover-primary">Pendidikan</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item my-1">
                                    <a href="javascript:;" data-toggle="nav" data-section="working_experience" class="menu-link px-4">
                                        <span class="menu-icon">
                                            <i class="fi fi-rr-briefcase text-active-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark text-active-primary text-hover-primary">Pengalaman Kerja</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item my-1">
                                    <a href="javascript:;" data-toggle="nav" data-section="language_skill" class="menu-link px-4">
                                        <span class="menu-icon">
                                            <i class="fi fi-sr-list-check text-active-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark text-active-primary text-hover-primary">Kemampuan Bahasa</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item my-1">
                                    <a href="javascript:;" data-toggle="nav" data-section="medical_record" class="menu-link px-4">
                                        <span class="menu-icon">
                                            <i class="fi fi-rr-doctor text-active-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark text-active-primary text-hover-primary">Rekam Medis</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item my-1">
                                    <a href="javascript:;" data-toggle="nav" data-section="license" class="menu-link px-4">
                                        <span class="menu-icon">
                                            <i class="fi fi-rr-book-alt text-active-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark text-active-primary text-hover-primary">Lisensi</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item my-1">
                                    <a href="javascript:;" data-toggle="nav" data-section="office_data" class="menu-link px-4">
                                        <span class="menu-icon">
                                            <i class="fi fi-rr-building text-active-primary fs-2"></i>
                                        </span>
                                        <span class="menu-title text-dark text-active-primary text-hover-primary">Data Kantor</span>
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                            {{-- end::nav --}}
                        </div>
                    </div>
                    <div class="flex-fill border-right" id="section-nav"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="1" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                        <span class="fw-bold fs-3 text-center">Apakah Anda yakin ingin menghapus data ini?</span>
                        <div class="d-flex align-items-center mt-5">
                            <a href="#" name="submit" value="cancel" id="delete-url" class="btn btn-white">Lanjutkan</a>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batalkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script>

        function archiveItem(me){
            var p = $("input[name=section]").val()
            var url = $(me).data("url") + "?section=" + p
            $("#delete-url").attr("href", url)
            $("#modalDelete").modal("show")
        }

        function navToggle(section){
            $("#section-nav").html("")
            var me =$("a[data-toggle=nav][data-section="+section+"]")

            $("a[data-toggle=nav]").removeClass("active")
            $("a[data-toggle=nav] .text-active-primary").removeClass("active")
            $(me).find(".text-active-primary").addClass("active")
            $(me).addClass("active")

            $.ajax({
                url : "{{ route("ess.profile.index") }}",
                data : {
                    v : "nav",
                    section : section
                },
                type : "get",
                dataType : "json"
            }).then(function(resp){
                KTImageInput.createInstances();
                $("#section-nav").html(resp.view)

                $("#section-nav input.number").number(true, 2)

                $("#section-nav div[data-toggle=imageInput]").each(function(){
                    var input = $(this).find("input[type=file]")
                    var wrapper = $(this).find("div.img-wrapper")
                    $(input).change(function(){
                        console.log(input, wrapper)
                        const file = this.files[0];
                        let reader = new FileReader();
                        reader.onload = function(event){
                            wrapper.css("background-image", "url("+event.target.result+")")
                            wrapper.css("background-size", "cover")
                        }
                        reader.readAsDataURL(file);
                    })
                })

                $("#section-nav table.table-display-2").each(function(){
                    var t = initTable($(this))
                })

                $("#section-nav .flatpicker").each(function(){
                    $(this).flatpickr({
                        dateFormat: "d/m/Y",
                    });
                })

                $("#section-nav input[type=file][data-toggle=file]").change(function(){
                    var val = $(this).val().split("\\")
                    var parent = $(this).parent()

                    $(parent).find("span").text(val[val.length - 1])
                    $(parent).addClass("btn-primary")
                    $(parent).removeClass("btn-secondary")

                    // $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
                })

                $('#section-nav [data-toggle="still"]').click(function(){
                    var checked = this.checked
                    var form = $(this).parents("div.modal")
                    if(checked){
                        $(form).find('[data-target="still"]').addClass("d-none")
                        $(form).find('[data-target="still"]').find("select").prop("required", false)
                    } else {
                        $(form).find('[data-target="still"]').removeClass("d-none")
                        $(form).find('[data-target="still"]').find("select").prop("required", true)
                    }
                })

                $('#section-nav [data-toggle="still"]').each(function(){
                    var checked = this.checked
                    var form = $(this).parents("div.modal")
                    if(checked){
                        $(form).find('[data-target="still"]').addClass("d-none")
                        $(form).find('[data-target="still"]').find("select").prop("required", false)
                    } else {
                        $(form).find('[data-target="still"]').removeClass("d-none")
                        $(form).find('[data-target="still"]').find("select").prop("required", true)
                    }
                })

                $("#section-nav input[name=resident_identity]").click(function(){
                    var checked = this.checked

                    if(checked){
                        var address = $("#section-nav [name='identity[address]']")
                        var zip_code = $("#section-nav [name='identity[zip_code]']")
                        var country = $("#section-nav [name='identity[country]']")
                        var city = $("#section-nav [name='identity[city]']")
                        var province = $("#section-nav [name='identity[province]']")

                        $("#section-nav [name='resident[address]']").val($(address).val())
                        $("#section-nav [name='resident[zip_code]']").val($(zip_code).val())
                        $("#section-nav [name='resident[country]']").val($(country).val())
                        $("#section-nav [name='resident[city]']").val($(city).val())
                        $("#section-nav [name='resident[province]']").val($(province).val())
                    }
                })

                $("#section-nav select[data-control=select2]").select2()

                @if(\Session::has("ess_private_tab"))
                    var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ \Session::get("ess_private_tab") }}']")
                    bootstrap.Tab.getOrCreateInstance(triggerEl).show()
                @endif
            })
        }

        $(document).ready(function(){
            $("a[data-toggle=nav]").click(function(){
                var section = $(this).data("section")
                navToggle(section)
            })

            @if(\Session::has("ess_section"))
                navToggle("{{ \Session::get("ess_section") }}")
            @else
                navToggle("private_data")
            @endif
        })

    </script>
@endsection
