@extends('layouts.templateCrm', ['menuCrm' => 'menu_attendance', 'bgWrapper' => $bgWrapper ?? "bg-white", 'subTitle' => "Attendance", 'withoutFooter' => true, 'style' => ['border' => 'border-bottom', 'box-shadow' => 'none']])

@section('content')
    @yield('view_content')
@endsection

@section('custom_script')
    @yield('view_script')
    <script>
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
            @if (!empty($_GET['modal']))
                $("#{{ $_GET['modal'] }}").modal("show")
            @endif
        })
    </script>
@endsection
