<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
        <base href="../"/>
		<title>{{ \Config::get("constants.APP_NAME") }}</title>
		<meta charset="utf-8" />
		{{-- <meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
		<meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" /> --}}
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		{{-- <meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metronic - Bootstrap Admin Template, HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template" />
		<meta property="og:url" content="https://keenthemes.com/metronic" />
		<meta property="og:site_name" content="Keenthemes | Metronic" /> --}}
		{{-- <link rel="canonical" href="https://preview.keenthemes.com/metronic8" /> --}}
		<link rel="shortcut icon" href="{{asset(\Config::get("constants.APP_ICON"))}}" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="{{ asset("theme/assets/plugins/custom/datatables/datatables.bundle.css") }}" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ asset("theme/assets/plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset("theme/assets/css/style.bundle.css") }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset("theme/assets/css/variables.css?v=".date("YmdHis")) }}" type="text/css">
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
        @include('layouts.styles')
        <style>
            .select2-results__option[aria-disabled=true]
            {
                display: none;
            }

            .bg-secondary-crm {
                background-color: #F7F8FA;
            }

            @media (min-width: 992px) {
                .container,.container-fluid,.container-lg,.container-md,.container-sm,.container-xl,.container-xxl {
                    padding: 0 26px!important;
                }
                .header-fixed .wrapper {
                    padding-top: 116px!important;
                }
            }
        </style>
        @yield('css')
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed">
		<!--begin::Theme mode setup on page load-->
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if (localStorage.getItem("data-bs-theme") !== null) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }
                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid @if(isset($bgWrapper)) {{ $bgWrapper }} @endif" @if(!isset($aside)) style="padding-left: 0px" @endif id="kt_wrapper">
					<!--begin::Header-->
					@include('layouts.header', ["sub_head" => $sub_head ?? null])
                    @include('layouts.menu_aside')
					<!--end::Header-->
					<!--begin::Content wrapper-->
					<div class="d-flex flex-column-fluid">
                        {{-- @yield('aside') --}}
						<!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid">
							<!--begin::Post-->
							<div class="content flex-column-fluid mb-md-10" id="kt_content">
								<!--begin::Content card-->
                                <div class="d-flex flex-row-fluid">
                                    @yield('aside')
                                    <div class="flex-fill w-100">
                                        @yield('content')
                                    </div>
                                    @yield('rside')
                                </div>
								<!--end::Content card-->
							</div>
							<!--end::Post-->
						</div>
						<!--end::Container-->
					</div>
                    <!--begin::Footer-->
                    @if (!isset($withoutFooter))
                    @include('layouts.footer')
                    @endif
                    <!--end::Footer-->
					<!--end::Content wrapper-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->
		<!--end::Main-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
        {{-- Toast --}}
        <!--begin::Toast-->
        <div class="end-0 mt-20 p-3 position-fixed pt-10 top-0" style="top: 90%; z-index: 999;">
            <div id="kt_toast_notif" class="toast text-white" role="alert" aria-live="assertive" aria-atomic="true">
                {{-- <div class="toast-header">
                    <i class="ki-duotone ki-abstract-19 fs-2 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
                    <strong class="me-auto">Keenthemes</strong>
                    <small>11 mins ago</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div> --}}
                <div class="toast-body py-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="toast-message"></span>
                        {{-- <button type="button" class="btn-close btn-white" data-bs-dismiss="toast" aria-label="Close"></button> --}}
                    </div>
                </div>
            </div>
        </div>
        <!--end::Toast-->
        {{--  --}}
		<!--begin::Modals-->
		<!--end::Modals-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{ asset("theme/assets/plugins/global/plugins.bundle.js") }}"></script>
		<script src="{{ asset("theme/assets/js/scripts.bundle.js") }}"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="{{ asset("theme/assets/plugins/custom/fslightbox/fslightbox.bundle.js") }}"></script>
		<script src="{{ asset("theme/assets/plugins/custom/datatables/datatables.bundle.js") }}"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="{{ asset("theme/assets/js/widgets.bundle.js") }}"></script>
		<script src="{{ asset("theme/assets/js/custom/widgets.js") }}"></script>
		<script src="{{ asset("theme/assets/js/custom/apps/chat/chat.js") }}"></script>
		<script src="{{ asset("theme/assets/js/custom/utilities/modals/create-app.js") }}"></script>
		<script src="{{ asset("theme/assets/js/custom/utilities/modals/users-search.js") }}"></script>
        <script src="{{ asset("theme/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js") }}"></script>
        <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
        <script>var HOST_URL = "{{ URL::to('/') }}";</script>
        @include('layouts._scripts')
        <script>

            let _editor = {}
            $(".ck-editor").each(function(){
                var _id = $(this).attr("id")
                ClassicEditor.create(this, {
                    toolbar: ['link', '|', 'bold', 'italic', 'underline', '|', 'alignment', 'bulletedList', 'numberedList', 'blockQuote' ],
                }).then(function(editor){
                    _editor[_id] = editor
                })
            })
            var optionFormat = function(item) {
                if ( !item.id ) {
                    return item.text;
                }

                var span = document.createElement('span');
                var imgUrl = item.element.getAttribute('data-kt-select2-country');
                var template = '';

                template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
                template += item.text;

                span.innerHTML = template;

                return $(span);
            }

            var elAddress = ''

            function addAddress(target, modal){
                var title = $(modal +" select[name=title]").val()
                var title_other = $(modal +" input[name=title_other]").val()
                var full_address = $(modal +" input[name=full_address]").val()
                var postal_code = $(modal +" input[name=postal_code]").val()
                var country = $(modal +" input[name=country]").val()
                var province = $(modal +" input[name=province]").val()
                var city = $(modal +" input[name=city]").val()
                var subdistrict = $(modal +" input[name=subdistrict]").val()

                var div = $(target).parents(".fv-row").find(".d-add-address")

                var fAddress = ""
                fAddress += full_address == "" ? "" : full_address+", "
                fAddress += subdistrict == "" ? "" : "kec." + subdistrict+", "
                fAddress += city == "" ? "" : city+", "
                fAddress += province == "" ? "" : province+", "
                fAddress += country == "" ? "" : country+", "
                fAddress += postal_code == "" ? "" : postal_code

                elAddress = `<div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                    <div class="align-items-baseline align-items-center d-flex flex-column">
                        <span class="fw-bold">${title == "Other" ? title_other : (title ?? "-")}</span>
                        <span>${fAddress}</span>
                    </div>
                    <input type='hidden' name='address[]' value="${fAddress}">
                    <input type='hidden' name='title[]' value="${title == "Other" ? title_other : (title ?? "-")}">
                    <input type='hidden' name='full_address[]' value="${full_address}">
                    <input type='hidden' name='postal_code[]' value="${postal_code}">
                    <input type='hidden' name='country[]' value="${country}">
                    <input type='hidden' name='province[]' value="${province}">
                    <input type='hidden' name='city[]' value="${city}">
                    <input type='hidden' name='subdistrict[]' value="${subdistrict}">
                    <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                </div>`

                if(fAddress == ""){
                    return Swal.fire("Address Empty", "Form need to be filled", "error")
                }

                $(div).append(elAddress)

                elAddress = ""
                $(modal).modal("hide")
            }

            function modalAddress(me){
                $("#modalAddAddress").modal("show")
                $("#modalAddAddress select[name=title]").change(function(){
                    $("#modalAddAddress input[name=title_other]").addClass("d-none")
                    $("#modalAddAddress input[name=title_other]").val("")
                    if($(this).val() == "Other"){
                        $("#modalAddAddress input[name=title_other]").removeClass("d-none")
                    }
                })

                $("#modalAddAddress input").val("")
                $("#modalAddAddress select").val("").trigger("change")

                try {
                    $("#modalAddAddress [data-button]").off()
                    $("#modalAddAddress [data-toggle=pos]").off()
                } catch (error) {

                }

                $("#modalAddAddress [data-toggle=pos]").click(function(){
                    var val = $(this).prev().val()
                    $(this).find("i").removeClass("fi fi-rr-search")
                    $(this).find("i").addClass("spinner-border")
                    var btn = $(this)
                    $.ajax({
                        url : "https://kodepos.vercel.app/search/?q=" + val,
                        type : "get",
                        dataType : "json"
                    }).then(function(resp){
                        $(btn).find('i').addClass("fi fi-rr-search")
                        $(btn).find('i').removeClass("spinner-border")
                        var data = resp.data[0]
                        console.log(resp.data)
                        if(resp.data.length > 0){
                            $("#modalAddAddress input[name=country]").val("Indonesia")
                            $("#modalAddAddress input[name=province]").val(data.province)
                            $("#modalAddAddress input[name=city]").val(data.city)
                            $("#modalAddAddress input[name=subdistrict]").val(data.district)
                        } else {
                            showToast(`${val} not found`, 'bg-danger')
                        }
                    })
                })

                $("#modalAddAddress [data-button]").bind("click",function(){
                    addAddress(me, "#modalAddAddress")
                })
            }

            function showToast(message, bg){
                // / Select elements
                const toastElement = document.getElementById('kt_toast_notif');

                const toast = bootstrap.Toast.getOrCreateInstance(toastElement);

                $(toastElement).find(".toast-message").text(message)
                $(toastElement).addClass(bg)

                toast.show();
            }

            // Init Select2 --- more info: https://select2.org/
            $('#change_locale').select2({
                templateSelection: optionFormat,
                templateResult: optionFormat,
                minimumResultsForSearch: -1
            });


            $("#change_locale").change(function(){
                $.ajax({
                    url : window.HOST_URL+"/locale-switch",
                    type : "post",
                    dataType : "json",
                    data : {
                        locale : $(this).val()
                    },
                    success : function(resp){
                        location.reload()
                    }
                })
            })

            var toggle_file = function() {
                $("[data-toggle=upload_file]").change(function(){
                    var parent = $(this).parents("div.upload-file")
                    var btn = $(parent).find("label[data-toggle=upload_file]")
                    console.log(this.value)
                    var _html = `<i class="fa fa-file"></i>${$(this).val().split('\\').pop()}`
                    console.log(_html)
                    btn.html("")
                    btn.html(_html)
                    btn.removeClass("btn-outline btn-outline-primary")
                    btn.addClass("btn-primary")
                })
            }

            toggle_file()

            $(".flatpicker").each(function(){
                $(this).flatpickr({
                    dateFormat: "d/m/Y",
                });
            })

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
                        format:  $(this).data("format") ?? "dd/MM/yyyy"
                    }
                });
            })

            $("input.number").number(true, 2, ",", ".")

            $("table.table").addClass("gy-7 gs-7 border table-rounded")

            $("table.table thead tr").addClass("fw-semibold fs-6 text-gray-800 border-bottom border-gray-200")

            var table_display = $("table.display").DataTable({
                // dom : `<"d-flex align-items-center justify-content-between justify-content-md-end"f>t<"dataTable-length-info-label me-3">lip`
                dom : `<"d-flex align-items-center justify-content-between justify-content-start"f>t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">l><"col-sm-12 col-md-7 d-flex align-items-center"p>>`,
                "fnDrawCallback": function( oSettings ) {
                    try {
                        KTMenu.createInstances();
                        $("[data-bs-toggle=tooltip]").tooltip()
                    } catch (error) {

                    }
                },
            })

            $(".dataTable-length-info-label").text("Lihat:")

            var _selDataTable = $(".dataTables_length").find("select")
            _selDataTable.addClass("border-0 bg-white")
            _selDataTable.removeClass("form-select-solid")
            // _selDataTable.parent().addClass("border-bottom border-dark")
            var _filterDataTable = $(".dataTables_filter")
            _filterDataTable.find("input[type=search]").removeClass("form-control-solid")
            var _filterLabel = _filterDataTable.find("label")
            _filterLabel.each(function(){
                var id = $(this).parents(".dataTables_filter").attr("id")
                var id_split = id.split("_")
                var id_split2 = id_split[0].split("-")
                var _html = $(this).html()
                var _exp = _html.split(":")
                var input = $(this).find("input")
                var _input = $(input).addClass("ps-10")
                var el = '<i class="fs-3 fa fa-search ms-4 position-absolute text-gray-500 top-50 translate-middle-y"></i>'
                _input.attr("placeholder", "Cari " + id_split2[1])
                $(this).contents().filter(function(){ return this.nodeType != 1; }).remove();
                $(el).insertBefore(input)
                $(this).addClass("d-lg-block d-none mb-5 mb-lg-0 position-relative w-100")
            })

            @if(\Session::has("toast"))
                showToast("{{ \Session::get("toast")['message'] }}", "{!! \Session::get("toast")['bg'] ?? 'bg-success' !!}")
            @endif

            function collapseDisabled(me){
                var tg = $(me).data("bs-target")
                var acc = $(tg).parents(".accordion").eq(0)
                var btn = $(acc).find("[data-disabled]")
                console.log(btn)
                if(!$(btn).hasClass("collapsed")){
                    $(btn).addClass("disabled")
                } else {
                    $(btn).removeClass("disabled")
                }
            }

        </script>
        @yield('custom_script')
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
