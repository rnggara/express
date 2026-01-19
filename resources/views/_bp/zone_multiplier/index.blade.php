@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Zone Multiplier</h3>
            <div class="card-toolbar">
                {{-- <button type="button" class="btn btn-outline btn-outline-primary" onclick="clearForm()" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="fa fa-plus"></i>
                    Tambah
                </button> --}}
            </div>
        </div>
        <div class="card-body">
            <table class="table display" id="table-applicant">
                <thead>
                    <tr>
                        <th class="text-nowrap">#</th>
                        <th class="text-nowrap">Nama Vendor</th>
                        <th class="text-nowrap">Zone Pricing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($countries as $i => $item)
                        <tr>
                            <td>
                                {{$i+1}}
                            </td>
                            <td>
                                {{ $item->nama }}
                            </td>
                            <td >
                                <a href="{{ route("be.zone_multiplier_detail", $item->id) }}" class="btn btn-icon btn-sm">
                                    <i class="fi fi-rr-settings"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.min.js") }}"></script>
    <script>

        var tb_charge = $("#table-charge").DataTable({
            searching : true,
            dom : 't<"d-flex justify-content-center"p>'
        })

        function clearForm(){
            $("#form-post input:not([name=_token])").val("")
            $("#form-post button[type=submit]").text("Tambah")
            $("#div-charge").addClass("d-none")
        }

        function deleteData(id){
            $("#modalDelete input[name=id]").val(id)

            $("#modalDelete").modal("show")
        }

        function editForm(me, id){
            var nama = $(me).data("nama")
            var price = $(me).data("price")
            var logo = $(me).data("logo")

            $("#form-post input[name=nama]").val(nama)
            $("#form-post input[name=price]").val(price)
            $("#form-post div.image-input-wrapper").attr("style", "background-image: url('" + logo + "')")
            $("#form-post input[name=id]").val(id)
            $("#form-post button[type=submit]").text("Update")

            $("#div-charge").removeClass("d-none")
            tb_charge.columns(3).search(id).draw()

            $("#modalAdd").modal("show")
        }

        $(document).ready(function(){

            $("input.number").number(true, 0, ",", ".")

            var table_job = $("#table-applicant").DataTable({
            dom : `<"d-flex flex-column align-items-baseline"<"d-flex align-items-center justify-content-between justify-content-start"f>>t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">l><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"ip>>`,
            colReorder: true,
            columDefs : [
                {"targets" : 0, "orderable": false}
            ],
            order: [],
            initComplete: function () {

                var _selDataTable = $(".dataTables_length").find("select")
                _selDataTable.addClass("border-0 bg-secondary")
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
                    _input.attr("placeholder", "Search ")
                    $(this).contents().filter(function(){ return this.nodeType != 1; }).remove();
                    $(el).insertBefore(input)
                    $(this).addClass("d-lg-block mb-5 mb-md-0 mb-lg-0 position-relative w-100")
                })

                var _selParent = $(".dataTables_filter").parent()
                // _selParent.append("<button class='btn d-none d-md-inline  btn-secondary btn-sm ms-5'><i class='la la-filter fs-2'></i> Filter</button>")

                var _selParentDiv = $(_selParent).parent()
                var _filterColumn = "<div class='d-none d-md-flex align-items-center w-100' id='filter-by-column'></div>"
                _selParentDiv.append(_filterColumn)
                _selParentDiv.addClass("mb-5")

                var columns = this.api().columns()

                $("select[data-control=select2]").select2()
            }
        })
        })
    </script>
@endsection
