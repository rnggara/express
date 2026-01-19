@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Zone Pricing</h3>
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
                            <td >
                                <a href="{{ route("be.zone_pricing_detail", $item->id) }}" class="btn btn-icon btn-sm">
                                    <i class="fi fi-rr-settings"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-centered">
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
                                <label class="col-form-label required">Surcharge  (Rp)</label>
                                <input type="text" name="price" class="form-control number" placeholder="Masukkan Harga per kg">
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
