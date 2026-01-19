@extends('layouts.templateCrm', ['menuCrm' => 'menu_ess', "contentHeight" => $contentHeight ?? "", 'bgWrapper' => $bgWrapper ?? "bg-white", 'subTitle' => "ESS", 'withoutFooter' => true, 'style' => ['border' => 'border-bottom', 'box-shadow' => 'none']])

@section('content')

    @yield('view_content')

    <div class="modal fade" tabindex="1" id="modalTakeAttendance">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    @yield('view_script')
    <script src="https://cdn.jsdelivr.net/gh/tomickigrzegorz/circular-progress-bar@1.2.3/dist/circularProgressBar.min.js"></script>
    <script src="{{ asset("js/progressbar.js/dist/progressbar.js") }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

    <script>

        var lastInterValID

        takeSnapShot = function () {
            Webcam.snap(function (data_uri) {
                $("#file__").val(data_uri)
                $("#snapShot").css("background-image", "url('"+data_uri+"')")
                $("#snapShot").css("height", $("#camera").css("height"))
                $("#snapShot").css("width", $("#camera").css("width"))
                $("#snapShot").removeClass("d-none")
                $("#camera").addClass("d-none")
                $("#btnSnap").addClass("d-none")
                $("#uploadDiv").removeClass("d-none")
                Webcam.freeze()
            });
        }

        takeSnapAgain = function(){
            $("#snapShot").addClass("d-none")
            $("#camera").removeClass("d-none")
            $("#btnSnap").removeClass("d-none")
            $("#uploadDiv").addClass("d-none")
            Webcam.unfreeze()
        }

        // Webcam.reset()

        saveSnapShot = function() {
            takeSnapShot()
            Swal.fire({
                title: "Log In",
                text: "Processing Data",
                onOpen: function() {
                    Swal.showLoading()
                }
            })
            var img = $("#file__").val()
            try {
                Webcam.upload(img, '', function(code, text){
                    var response = JSON.parse(text)
                    if(response.success){
                        swal.close()
                        Swal.fire("Success", "Login Success", 'success').then(function(result) {
                            if(result.isConfirmed){
                                location.reload()
                            }
                        })
                    } else {
                        swal.close()
                        Swal.fire("Error", response.error, 'error')
                    }
                })
            } catch (error) {
                swal.close()
                Swal.fire("Error", error.message, 'error')
            }
        }

        function resetWebcam() {
            Webcam.reset()
        }

        function take_attendance(step = null){
            if(step == "upload"){
                var img = $("#file__").val()
                Webcam.upload(img, '{{ route('ess.att') }}?_token={{ csrf_token() }}', function(code, text){
                    var response = JSON.parse(text)
                    console.log(response)
                    if(response.success){
                        var lbl = $("#btnUp").text()
                        var el = "<i class='fi fi-sr-check-circle text-white'></i>"
                            el += lbl
                            el += " success"
                        console.log(el)
                        $("#btnUp").html(el)
                        $("#btnUp").removeClass("btn-primary")
                        $("#btnUp").addClass("btn-success")
                        $("#btnClose").text("Tutup")
                        $("#btnUp").attr("onclick", "")
                        $("#btnClose").attr("onclick", "")
                        $("#btnUp").off("click")
                        $("#btnClose").off("click")

                        $("#btnClose").click(function(){
                            $("#modalTakeAttendance").modal("hide")
                        })
                        Webcam.reset()
                    } else {
                        swal.close()
                        Swal.fire("Error", response.error, 'error')
                    }
                })
            } else {
                $.ajax({
                url : "{{ route("ess.index") }}?a=take_attendance",
                type : "get",
                dataType : "json",
                data : {
                    step : step
                }
            }).then(function(resp){
                $("#modalTakeAttendance div.modal-content").html(resp.view)
                $("#modalTakeAttendance").modal("show")

                clrInterval()

                if(step == "question_1" || step == "break_out" || step == "break_in" || step == "clock_out"){
                    try{
                        Webcam.set({
                            width: 500,
                            height: 280,
                            image_format: 'jpeg',
                            jpeg_quality: 100
                        });
                        Webcam.attach('#camera');
                        var vd = $("#camera").find("video")
                        $("#camera").css("width", "100%")
                        $(vd).css('width', '100%')
                        console.log($(vd).css('width'))
                        console.log('webcam attached')
                    } catch (e) {
                        console.log(e.message)
                    }   
                }

                if($("#loading").length > 0){
                    var bar = new ProgressBar.Circle(document.getElementById("loading"), {
                        strokeWidth: 50,
                        easing: 'easeIn',
                        duration: 1000,
                        color: "#E1D7FA",
                        trailColor: $("#loading").data("cl") ?? '#7340E5',
                        trailWidth: 45,
                        svgStyle: null,
                        fill : $("#loading").data("cl") ?? "#7340E5"
                    });

                    var cd = $("#loading").data("s")

                    cdBar(bar, cd, $("#loading").data("next"), $("#loading").data('total'))
                }

                // if (step == "question_1") {
                //     try{
                //         Webcam.set({
                //             width: 500,
                //             height: 280,
                //             image_format: 'jpeg',
                //             jpeg_quality: 100
                //         });
                //         Webcam.attach('#camera');
                //         var vd = $("#camera").find("video")
                //         $("#camera").css("width", "100%")
                //         $(vd).css('width', '100%')
                //         console.log($(vd).css('width'))
                //         console.log('webcam attached')
                //     } catch (e) {
                //         console.log(e.message)
                //     }   
                // }
            })
            }
        }

        var cdBar = function(bar, cd, next = null, total = 10){
            var td = total
            lastInterValID = setInterval(() => {
                cd--
                $("[data-timer]").text("00:" + String(cd).padStart(2, "0"))
                var v = (td - cd) / td
                bar.set(v)
                if(cd == 0){
                    clrInterval(lastInterValID)
                    if(next != ""){
                        $("#modalTakeAttendance").modal("hide")
                    } else {
                        take_attendance("question_0")
                    }
                }
            }, 1000);
        }

        function clrInterval(){
            clearInterval(lastInterValID)
        }

        var tb_list = []


        $("table.table-display-2").each(function(){
            var input_search = $(this).parent().find("input[name=search_table]")
            var tb = $(this).DataTable({
                dom : `t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">><"col-sm-12 col-md-7 d-flex align-items-center"p>>`,
                "fnDrawCallback": function( oSettings ) {
                    try {
                        KTMenu.createInstances();
                        $("[data-bs-toggle=tooltip]").tooltip()
                    } catch (error) {

                    }
                },
                language: {
                    emptyTable: 'Tidak ada data tersedia'
                }
            })

            tb_list.push(tb)

            $(input_search).on("keyup", function(){
                tb.search( this.value ).draw();
            })
        })

        function initTable(id){
            var input_search = $(id).parent().find("input[name=search_table]")
            var tb = $(id).DataTable({
                dom : `t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">><"col-sm-12 col-md-7 d-flex align-items-center"p>>`,
                "fnDrawCallback": function( oSettings ) {
                    try {
                        KTMenu.createInstances();
                        $("[data-bs-toggle=tooltip]").tooltip()
                    } catch (error) {

                    }
                },
            })

            tb_list.push(tb)

            $(input_search).on("keyup", function(){
                tb.search( this.value ).draw();
            })

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

            $(".dataTable-length-info-label").text("")

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

            return tb
        }

        $(document).ready(function(){
            @if(!empty($_GET['modal']))
                $("#{{ $_GET['modal'] }}").modal("show")
            @endif
        })

    </script>
@endsection
