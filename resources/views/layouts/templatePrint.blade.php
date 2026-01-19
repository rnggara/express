<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="../"/>
		<title>{{ \Config::get("constants.APP_NAME") }}</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" href="{{ asset(\Config::get("constants.APP_ICON")) }}" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="{{ asset("theme/assets/plugins/custom/datatables/datatables.bundle.css") }}" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ asset("theme/assets/plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset("theme/assets/css/style.bundle.css") }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset("theme/assets/css/variables.css")."?v=".date("YmdHis") }}" type="text/css">
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
        @include('layouts.styles')
        <style>
            .select2-results__option[aria-disabled=true]
            {
                display: none;
            }
            @media print {
                .pagebreak { page-break-before: always; } /* page-break-after works, as well */

                .print-hide {
                    display : none;
                }
            }
        </style>
        @yield('css')
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed bg-white">
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
		<div class="d-flex flex-column flex-root bg-white">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid bg-white">
				<!--begin::Wrapper-->
				<div class="pt-10 d-flex flex-column flex-row-fluid bg-white @if(isset($bgWrapper)) {{ $bgWrapper }} @endif" @if(!isset($aside)) style="padding-left: 0px" @endif id="kt_wrapper">
					<!--begin::Header-->
					<!--end::Header-->
					<!--begin::Content wrapper-->
					<div class="d-flex flex-column-fluid">
                        {{-- @include('layouts.menu_aside') --}}
						<!--begin::Container-->
						<div class="d-flex flex-column flex-column-fluid container-fluid">
							<!--begin::Post-->
							<div class="content flex-column-fluid mb-10 px-md-20" id="kt_content">
								<!--begin::Content card-->
                                <div class="d-md-flex flex-row-fluid">
                                    @yield('aside')
                                    <div class="flex-fill mt-5 mt-md-0">
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
		{{-- <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div> --}}
		<!--end::Scrolltop-->
		<!--begin::Modals-->
		<!--end::Modals-->
		<!--begin::Javascript-->
		@include('layouts.scripts')
        <script>

            function hide_notif_badge(){
                if($("#notification-count").text() == 0){
                    $("#notification-count").hide()
                }
            }

            function notification_click(id, me){
                $.ajax({
                    url : "{{ route("notif.view") }}/"+id,
                    type : "get",
                    dataType : "json",
                    success : function(resp){
                        // console.log(resp)
                        $(me).parents("div.notif-div").remove()
                        $("#notification-count").text(resp.count)
                        window.open(resp.url, "_blank")
                        hide_notif_badge()
                    }
                })
            }

            function getNotifications(){
                hide_notif_badge()
                $.ajax({
                    url : "{{ route("notif.list") }}",
                    type : "get",
                    dataType : "json",
                    success : function(resp){
                        $("#notification-spinner").hide()
                        $("#notification-spinner div.spinner").hide()
                        $(".notif-content").html(resp.view)
                        if(resp.count > 0){
                            $("#notification-count").show()
                        }
                        $("#notification-count").text(resp.count)
                    }
                })
            }

            getNotifications();


            let _editor = {}
            $(".ck-editor").each(function(){
                var _id = $(this).attr("id")
                ClassicEditor.create(this).then(function(editor){
                    _editor[_id] = editor
                })
            })
            // ClassicEditor
            //     .create(document.querySelector('.ck-editor'));
            // Format options
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

            $("[data-toggle=upload_file]").change(function(){
                var parent = $(this).parents("div.upload-file")
                var label = $(parent).find(".upload-file-label")
                console.log(parent)
                console.log(label)
                label.text($(this).val().split('\\').pop())
                label.addClass("text-primary")
            })

            @if (isset($sub_head))
                var mHead = $("#kt_header").height()
                var mOffset = mHead + 30
                $("#kt_wrapper").css("padding-top", `${mOffset}px`)
                $("#kt_aside").css("top", `${mOffset}px`)
            @endif

            $("table.display").addClass("gy-7 gs-7 border").removeClass("table-striped")

            $("table.display thead tr").addClass("fw-semibold fs-6 text-gray-800 border-bottom border-gray-200").css("background-color", "#FAFAFA")

            // $('[data-toggle="tooltip"]').tooltip()

        </script>
        @yield('custom_script')
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
