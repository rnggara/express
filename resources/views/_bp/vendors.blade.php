@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Vendor</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-outline btn-outline-primary" onclick="clearForm()" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="fa fa-plus"></i>
                    Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table display" id="table-applicant">
                <thead>
                    <tr>
                        <th class="text-nowrap">#</th>
                        <th class="text-nowrap">Nama Vendor</th>
                        <th class="text-nowrap">Surcharge</th>
                        <th class="text-nowrap">Zones</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($countries as $i => $item)
                        @php
                            $fuel_charge = $fuel_charges->where("vendor_id", $item->id)->first();
                        @endphp
                        <tr>
                            <td>
                                {{$i+1}}
                            </td>
                            <td>
                                {{ $item->nama }}
                            </td>
                            <td >{{ number_format($fuel_charge->price ?? 0, 0, ',', ".") }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="view_zone({{$item->id}})">
                                    View Zones
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 10px">
                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                </button>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" onclick="editForm(this, {{ $item->id }})" data-type="{{$item->type}}" data-nama="{{ $item->nama }}" data-price="{{ $fuel_charge->price ?? 0 }}" data-logo="{{ asset($item->logo_path) }}" class="menu-link px-3">
                                            Edit
                                        </a>
                                        <a href="javascript:;" onclick="deleteData({{ $item->id }})" class="menu-link px-3 text-danger">
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

    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form action="{{ route('be.vendors_post') }}" id="form-post" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Vendor</span>
                            <div class="fv-row">
                                <label class="col-form-label required">Nama Vendor</label>
                                <input type="text" name="nama" class="form-control" required placeholder="Masukkan Nama Negara">
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label required">Vendor Type</label>
                                <select name="vendor_type" class="form-control">
                                    <option value="1">Location Based</option>
                                    <option value="2">Weight based</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="fv-row">
                                        <label class="col-form-label required">Surcharge Type</label>
                                        <select name="surcharge_type" onchange="surchargeType()" class="form-control">
                                            <option value="0">Percentage</option>
                                            <option value="1">Fixed Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <label class="col-form-label required" data-surcharge>Surcharge (Rp)</label>
                                        <input type="text" name="price" class="form-control number-decimal" data-decimal='2' step=".001"
                                            placeholder="Masukkan Harga">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Remote Area (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_remote_area" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="remote_area" class="form-control number"
                                            placeholder="Masukkan Remote Area">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fv-row">
                                        <label class="col-form-label required">Remote Area Multiplier (Rp)</label>
                                        <input type="text" name="remote_area_multiplier" class="form-control number"
                                            placeholder="Masukkan Remote Area Multiplier">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fv-row">
                                        <label class="col-form-label required">Remote Area Limit</label>
                                        <input type="text" step="0.01" name="remote_area_limit" class="form-control number-decimal"
                                            placeholder="Masukkan Remote Area Limit">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Elevated-Risk Destination (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_elevated_risk" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="elevated_risk_destination_price" class="form-control number"
                                            placeholder="Masukkan Elevated-Risk Destination">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Restricted Destination (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_restricted_destination" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="restricted_destination_price" class="form-control number"
                                            placeholder="Masukkan Restricted Destination">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Overweight (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_overweight" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="overweight_price" class="form-control number"
                                            placeholder="Masukkan Overweight">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <label class="col-form-label required">Overweight Limit</label>
                                        <input type="text" step="0.01" name="overweight_limit" class="form-control number-decimal"
                                            placeholder="Masukkan Overweight Limit">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Oversize (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_oversize" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="oversize_price" class="form-control number"
                                            placeholder="Masukkan Oversize">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <label class="col-form-label required">Oversize Limit</label>
                                        <input type="text" step="0.01" name="oversize_limit" class="form-control number-decimal"
                                            placeholder="Masukkan Oversize Limit">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Non-Stackable Price (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_nsu" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="non_stackable_price" class="form-control number"
                                            placeholder="Masukkan Non-Stackable Price">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Insurance (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_insurance" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="insurance_price" class="form-control number"
                                            placeholder="Masukkan Insurance">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Delivery Duty (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_ddp" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="delivery_duty_price" class="form-control number"
                                            placeholder="Masukkan Delivery Duty">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Export Declaration (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_peb" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="export_declaration_price" class="form-control number"
                                            placeholder="Masukkan Export Declaration">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="col-form-label required">Non Conveyable Price (Rp)</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" name="surcharge_ncp" type="checkbox" value="1" />
                                                    Surcharge terkena Fuel
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="ncp_price" class="form-control number"
                                            placeholder="Masukkan Non Conveyable Price">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fv-row">
                                        <label class="col-form-label required">Non Conveyable Min (kg)</label>
                                        <input type="text" name="ncp_min" class="form-control number"
                                            placeholder="Masukkan Non Conveyable Min (kg)">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="fv-row">
                                        <label class="col-form-label required">Non Conveyable Max (kg)</label>
                                        <input type="text" name="ncp_max" class="form-control number"
                                            placeholder="Masukkan Non Conveyable Max (kg)">
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label required w-100">Logo</label>
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('assets/media/avatars/blank.png') }}')">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('')"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change logo">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="logo" accept=".png, .jpg, .jpeg"/>
                                        <input type="hidden" name="logo_remove"/>
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel logo">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    {{-- <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove logo">
                                        <i class="bi bi-x fs-2"></i>
                                    </span> --}}
                                </div>
                            </div>
                            <div class="row" id="div-charge">
                                <div class="col-12">
                                    <table class="table display" id="table-charge" data-ordering="false">
                                        <thead>
                                            <tr>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Price</th>
                                                <th class="d-none">Vendor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fuel_charges as $i => $item)
                                                <tr>
                                                    <td>{{ $item->start_date }}</td>
                                                    <td>{{ $item->end_date ?? "now" }}</td>
                                                    <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                                                    <td class="d-none">{{ $item->vendor_id }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="submit" value="store" class="btn btn-primary">Tambah</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalZone">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('be.vendors_post') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Hapus Vendor</span>
                            <span class="text-center fs-3">Apakah Anda yakin untuk menghapus data ini?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id" value="">
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

        function removeZone(id, vendor){
            Swal.fire({
                text: `Are you sure remove this zone?`,
                icon: "info",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: 'btn btn-danger'
                }
            }).then(function(value){
                if(value.isConfirmed){
                    window.location = "{{route('be.vendors_zone_remove', ['id' => ':id', 'vendor' => ':vendor'])}}".replaceAll(":id", id).replaceAll(":vendor", vendor)
                }
            });
        }

        function view_zone(id){
            $("#modalZone div.modal-content").html("")
            $.ajax({
                url : "{{ route('be.vendors') }}",
                type : "GET",
                dataType : "json",
                data : {
                    id : id,
                    act : "zone"
                }
            }).then(function(resp){
                $("#modalZone div.modal-content").html(resp.view)
                $("#modalZone table").DataTable()
                $("#modalZone select[data-control=select2]").select2()

                $("#modalZone").modal("show")
            })
        }

        function surchargeType(){
            var st = $("select[name=surcharge_type]").val()
            var lbl = "Fuel Surcharge (%)"
            if(st == 1){
                lbl = "Fuel Surcharge (Rp)"
            }

            $("label[data-surcharge]").text(lbl)
        }

        var tb_charge = $("#table-charge").DataTable({
            searching : true,
            dom : 't<"d-flex justify-content-center"p>'
        })

        function clearForm(){
            $("#form-post input:not([name=_token])").val("")
            $("#form-post button[type=submit]").text("Tambah")
            $("#div-charge").addClass("d-none")

            surchargeType()
        }

        function deleteData(id){
            $("#modalDelete input[name=id]").val(id)

            $("#modalDelete").modal("show")
        }

        function editForm(me, id){
            var nama = $(me).data("nama")
            var price = $(me).data("price")
            var logo = $(me).data("logo")
            var type = $(me).data("type") ?? 1

            $.ajax({
                url : "{{ route('be.vendors') }}",
                type : "GET",
                dataType : "json",
                data : {
                    id : id,
                    act : "edit"
                },
                success : function(res){
                    var fcharges = res.fcharges ?? []
                    $("#form-post input[name=nama]").val(nama)
                    if((fcharges.surcharge_type ?? 0) == 0){
                        $("#form-post input[name=price]").val((fcharges.fuel_surcharge ?? 0) * 100)
                    } else {
                        $("#form-post input[name=price]").val(price)
                    }
                    $("#form-post select[name=vendor_type]").val(type)
                    $("#form-post input[name=remote_area]").val(fcharges.remote_area ?? 0)
                    $("#form-post input[name=remote_area_multiplier]").val(fcharges.remote_area_multiplier ?? 0)
                    $("#form-post input[name=remote_area_limit]").val(fcharges.remote_area_limit ?? 0)
                    $("#form-post input[name=restricted_destination_price]").val(fcharges.restricted_destination_price ?? 0)
                    $("#form-post input[name=elevated_risk_destination_price]").val(fcharges.elevated_risk_destination_price ?? 0)
                    $("#form-post input[name=overweight_price]").val(fcharges.overweight_price ?? 0)
                    $("#form-post input[name=overweight_limit]").val(fcharges.overweight_limit ?? 0)
                    $("#form-post input[name=oversize_price]").val(fcharges.oversize_price ?? 0)
                    $("#form-post input[name=oversize_limit]").val(fcharges.oversize_limit ?? 0)
                    $("#form-post input[name=non_stackable_price]").val(fcharges.non_stackable_price ?? 0)
                    $("#form-post input[name=insurance_price]").val(fcharges.insurance_price ?? 0)
                    $("#form-post input[name=delivery_duty_price]").val(fcharges.delivery_duty_price ?? 0)
                    $("#form-post input[name=export_declaration_price]").val(fcharges.export_declaration_price ?? 0)
                    $("#form-post input[name=ncp_price]").val(fcharges.ncp_price ?? 0)
                    $("#form-post input[name=ncp_min]").val(fcharges.ncp_min ?? 0)
                    $("#form-post input[name=ncp_max]").val(fcharges.ncp_max ?? 0)
                    $("#form-post input[name=surcharge_remote_area]").prop("checked", fcharges.surcharge_remote_area == 1 ? true : false)
                    $("#form-post input[name=surcharge_elevated_risk]").prop("checked", fcharges.surcharge_elevated_risk == 1 ? true : false)
                    $("#form-post input[name=surcharge_restricted_destination]").prop("checked", fcharges.surcharge_restricted_destination == 1 ? true : false)
                    $("#form-post input[name=surcharge_overweight]").prop("checked", fcharges.surcharge_overweight == 1 ? true : false)
                    $("#form-post input[name=surcharge_oversize]").prop("checked", fcharges.surcharge_oversize == 1 ? true : false)
                    $("#form-post input[name=surcharge_nsu]").prop("checked", fcharges.surcharge_nsu == 1 ? true : false)
                    $("#form-post input[name=surcharge_insurance]").prop("checked", fcharges.surcharge_insurance == 1 ? true : false)
                    $("#form-post input[name=surcharge_ddp]").prop("checked", fcharges.surcharge_ddp == 1 ? true : false)
                    $("#form-post input[name=surcharge_peb]").prop("checked", fcharges.surcharge_peb == 1 ? true : false)
                    $("#form-post input[name=surcharge_ncp]").prop("checked", fcharges.surcharge_ncp == 1 ? true : false)
                    $("#form-post div.image-input-wrapper").attr("style", "background-image: url('" + logo + "')")
                    $("#form-post input[name=id]").val(id)
                    $("#form-post button[type=submit]").text("Update")
                    $("#div-charge").removeClass("d-none")
                    tb_charge.columns(3).search(id).draw()

                    $("#modalAdd").modal("show")

                    surchargeType()
                }
            })
        }

        $(document).ready(function(){

            $("input.number").number(true, 0, ",", ".")
            $("input.number-decimal").each(function(){
                var dec = $(this).data("decimal") ?? 2
                $(this).number(true, dec, ",", ".")
            })

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
