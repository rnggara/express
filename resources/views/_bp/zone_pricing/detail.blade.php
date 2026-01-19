@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Zone Pricing - {{ $vendor->nama }}</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-outline btn-outline-primary me-3" onclick="clearForm()" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="fa fa-plus"></i>
                    Tambah
                </button>
                <a href="{{ route("be.zone_pricing") }}" type="button" class="btn btn-success btn-icon">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column gap-5">
                @foreach ($sku_name as $id => $item)
                    @php
                        $zone_sku = $zones->where("tipe_sku", $id);
                        $zn = [];
                        $show = false;
                        if($zone_sku->count() > 0){
                            $show = true;
                            if ($vendor->type == 1) {
                                $zn = $zone_sku->groupBy("weight")->map(function($pb) { return $pb->keyBy("zone_id"); });
                            } else {
                                $zn = $zone_sku->keyBy("weight");
                            }
                        }
                    @endphp
                    @if ($show)
                        <div class="d-flex flex-column gap-3 pb-3 border-bottom">
                            <span class="fw-bold fs-3">{{ $item }}</span>
                            <table class="table display table-display" data-ordering="false">
                                <thead>
                                    <tr>
                                        <th>Weight (Kg)</th>
                                        @if ($vendor->type == 1)
                                            @foreach ($dataZone as $zone)
                                                <th>{{ $zone->name }}</th>
                                            @endforeach
                                        @else
                                            <th>Price</th>
                                        @endif
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($zn as $weight => $list)
                                        <tr>
                                            <td>{{ $weight }} Kg</td>
                                            @if ($vendor->type == 1)
                                                @foreach ($dataZone as $zone)
                                                    @php
                                                        $zonePrice = $list[$zone->id] ?? null
                                                    @endphp
                                                    <td>{{ number_format($zonePrice->price ?? 0, 0, ",", ".") }}</td>
                                                @endforeach
                                            @else
                                                <td>{{ number_format($list->price ?? 0, 0, ",", ".") }}</td>
                                            @endif
                                            <td>
                                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">
                                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                                </button>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:;" onclick="editForm(this)" data-id="{{ $vendor->type == 1 ? "" : ($list->id ?? "") }}" data-price="{{ $vendor->type == 1 ? "" : ($list->price ?? "") }}" data-sku="{{ $id }}" data-weight="{{ $weight }}" data-zones='@JSON($list->pluck("price", "zone_id"))' class="menu-link px-3">
                                                            Edit
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:;" onclick="deleteData(this)" data-sku="{{ $id }}" data-weight="{{ $weight }}" class="menu-link px-3 text-danger">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
            </div>
            @php
                $zone_sku = $zones->whereNull("tipe_sku");
                $zn = [];
                $show = false;
                if($zone_sku->count() > 0){
                    $show = true;
                    if ($vendor->type == 1) {
                        $zn = $zone_sku->groupBy("weight")->map(function($pb) { return $pb->keyBy("zone_id"); });
                    } else {
                        $zn = $zone_sku->keyBy("weight");
                    }
                }
            @endphp
            <div class="d-flex flex-column gap-3 mt-3">
                <span class="fw-bold fs-3">All Type</span>
                <table class="table display" id="table-applicant">
                    <thead>
                        <tr>
                            <th>Weight (Kg)</th>
                            @if ($vendor->type == 1)
                                @foreach ($dataZone as $zone)
                                    <th>{{ $zone->name }}</th>
                                @endforeach
                            @else
                                <th>Price</th>
                            @endif
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($zn as $weight => $list)
                            <tr>
                                <td>{{ $weight }} Kg</td>
                                @if ($vendor->type == 1)
                                    @foreach ($dataZone as $zone)
                                        @php
                                            $zonePrice = $list[$zone->id] ?? null
                                        @endphp
                                        <td>{{ number_format($zonePrice->price ?? 0, 0, ",", ".") }}</td>
                                    @endforeach
                                @else
                                    <td>{{ number_format($list->price ?? 0, 0, ",", ".") }}</td>
                                @endif
                                <td>
                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" onclick="editForm(this)" data-id="{{ $vendor->type == 1 ? "" : ($list->id ?? "") }}" data-price="{{ $vendor->type == 1 ? "" : ($list->price ?? "") }}" data-sku="" data-weight="{{ $weight }}" data-zones='@JSON($list->pluck("price", "zone_id"))' class="menu-link px-3">
                                                Edit
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" onclick="deleteData(this)" data-sku="" data-weight="{{ $weight }}" class="menu-link px-3 text-danger">
                                                Delete
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('be.zone_pricing_store') }}" id="form-post" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Zone Pricing</span>
                            <div class="fv-row">
                                <label class="col-form-label required">Weight</label>
                                <input type="number" step=".5" value="0" name="weight" class="form-control" required placeholder="Input Weight">
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label">Type SKU</label>
                                <select data-control="select2" data-dropdown-parent="#modalAdd" data-placeholder="All Type" name="tipe_sku" class="form-control">
                                    <option value=""></option>
                                    @foreach ($sku_name as $id => $item)
                                        <option value="{{ $id }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($vendor->type == 1)
                                <div class="fv-row">
                                    <label class="col-form-label required">Zone Pricing</label>
                                    <div class="row row-gap-4">
                                        @foreach ($dataZone as $zone)
                                            <div class="col-6">
                                                <label class="form-check-label">{{ $zone->name }}</label>
                                                <input type="text" name="zone_pricing[{{ $zone->id }}]" class="form-control number" placeholder="Input Price">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="fv-row">
                                    <label class="col-form-label required">Price</label>
                                    <input type="text" name="price" class="form-control number" placeholder="Input Price" required id="">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="act" value="">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="vendor" value="{{ $vendor->id }}">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="submit" value="store" class="btn btn-primary">Tambah</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('be.zone_pricing_store') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Hapus Zone Pricing</span>
                            <span class="text-center fs-3">Apakah Anda yakin untuk menghapus data ini?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="weight" value="">
                        <input type="hidden" name="tipe_sku" value="">
                        <input type="hidden" name="vendor" value="{{ $vendor->id }}">
                        <input type="hidden" name="act" value="delete">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="submit" value="delete" class="btn btn-primary">Ya</a>
                    </div>
                </form>
            </div>
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
            $("#form-post input:not([name=_token], [name=vendor])").val("")
            $("#form-post select").val("").trigger("change")
            $("#form-post button[type=submit]").text("Tambah")
            $("#div-charge").addClass("d-none")
        }

        function deleteData(me){
            var weight = $(me).data("weight")
            var sku = $(me).data("sku")

            $("#modalDelete input[name=weight]").val(weight)
            $("#modalDelete input[name=tipe_sku]").val(sku)
            $("#modalDelete").modal("show")
        }

        function editForm(me){
            var weight = $(me).data("weight")
            var zones = $(me).data("zones")
            var sku = $(me).data("sku")
            var id = $(me).data("id") ?? ""
            var price = $(me).data("price") ?? ""

            $("#form-post input[name=weight]").val(weight)
            $("#form-post select[name=tipe_sku]").val(sku).trigger("change")
            $("#form-post input[name=act]").val("update")
            $("#form-post input[name=id]").val(id)
            $("#form-post input[name=price]").val(price)

            for (const key in zones) {
                if (Object.hasOwnProperty.call(zones, key)) {
                    const element = zones[key];

                    $("#form-post input[name='zone_pricing["+key+"]']").val(element)
                }
            }

            $("#form-post button[type=submit]").text("Update")

            $("#modalAdd").modal("show")
        }

        $(document).ready(function(){

            $("input.number").number(true, 0, ",", ".")

            $("table.table-display").DataTable()

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
