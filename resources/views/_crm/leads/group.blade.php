@extends('layouts.templateCrm', ['withoutFooter' => true])

@section('fixaside')
    @include('_crm.leads._aside')
@endsection

@section('content')
<div class="card card-custom not-rounded h-100" id="kt_block_ui_1_target">
    <div class="card-body border-bottom">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-8">
                <span class="me-5 fw-bold">Filter</span>
                <!--begin::Radio group-->
                <div class="btn-group w-20px w-lg-20px" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                    <!--begin::Radio-->
                    <label for="MTD" class="btn btn-outline btn-color-muted btn-active-primary active" data-kt-button="true">
                        <!--begin::Input-->
                        <input class="btn-check" type="radio" name="filter" value="1" onclick="filter_table(this)" id="MTD"/>
                        <!--end::Input-->
                        MTD
                    </label>
                    <!--end::Radio-->

                    <!--begin::Radio-->
                    <label for="YTD" class="btn btn-outline btn-color-muted btn-active-primary" data-kt-button="true">
                        <!--begin::Input-->
                        <input class="btn-check" onclick="filter_table(this)" type="radio" name="filter" value="2" id="YTD"/>
                        <!--end::Input-->
                        YTD
                    </label>
                    <!--end::Radio-->
                </div>
                <!--end::Radio group-->
            </div>
            <div id="table-view">
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        var target = document.querySelector("#kt_block_ui_1_target");

        var blockUI = new KTBlockUI(target);

        function show_table(val){
            $("#table-view").html("")
            $.ajax({
                url : "{{ route("crm.lead.crmLead_d").'?a=table' }}"+ "&f=" +val,
                type : "GET",
                dataType : "json"
            }).then(function(resp){
                $("#table-view").html(resp.view)

                $("table.table").addClass("gy-7 gs-7 border").removeClass("table-striped")

                $("table.table thead tr").addClass("fw-semibold fs-6 text-gray-800 border-bottom border-gray-200").css("background-color", "#FAFAFA")

                var table_display = $("table.display").DataTable({
                    dom : `<"d-flex align-items-center border justify-content-end justify-content-md-end"<"dataTable-length-info-label me-3">lip>`
                })

                $(".dataTable-length-info-label").text("Rows per page:")

                var _selDataTable = $(".dataTables_length").find("select")
                _selDataTable.addClass("border-0")
                _selDataTable.removeClass("form-select-solid")
                _selDataTable.parent().addClass("border-bottom border-dark")
            })
        }

        function filter_table(me){
            var filter = $(me).val();
            show_table(filter)
        }

        $(document).ready(function(){
            show_table(1)
        })
    </script>
@endsection
