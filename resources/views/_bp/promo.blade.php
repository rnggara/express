@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Promo</h3>
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
                        <th class="text-nowrap">Kode Promo</th>
                        <th class="text-nowrap">Deskripsi</th>
                        <th class="text-nowrap">Tanggal Valid</th>
                        <!--<th class="text-nowrap">Status</th>-->
                        <th class="text-nowrap">Tipe</th>
                        <th class="text-nowrap">Formula/Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promo ?? [] as $i => $item)
                        @php
                            $dates = [$item->start_date];
                            if(!empty($item->end_date)) $dates[] = $item->end_date;
                        @endphp
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>{{ $item->code }}</td>
                            <td>
                                {{ $item->description }}
                            </td>
                            <td>{{ implode(" - ", $dates) }}</td>
                            <!--<td>-->
                            <!--    <span class="badge badge-{{ $item->status == 0 ? "secondary" : "success" }}">{{ $item->status == 0 ? "Tidak Aktif" : "Aktif" }}</span>-->
                            <!--</td>-->
                            <td>
                                <span class="badge badge-{{ $item->source == 0 ? "primary" : "info" }}">{{ $item->source == 0 ? "Fix Amount" : "Formula" }}</span>
                            </td>
                            <td>
                                {{ $item->source == 0 ? number_format($item->amount, 0, ",", ".") : $item->formula_text }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 10px">
                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                </button>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" onclick="editForm(this, {{ $item->id }})" class="menu-link px-3">
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('be.promo.store') }}" id="form-post" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Promo</span>
                            <div class="fv-row">
                                <label class="col-form-label required">Nama Promo</label>
                                <input type="text" name="description" class="form-control" required placeholder="Masukkan Nama Promo">
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label required">Kode Promo</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" oninput="this.value = this.value.toUpperCase()" onkeypress="return /[a-zA-Z0-9]/i.test(event.key)" placeholder="Kode Promo" name="code" id="kode-promo" aria-label="Kode Promo"/>
                                    <button type="button" onclick="randomKodePromo()" class="input-group-text" id="basic-addon2" data-bs-toggle="tooltip" title="Random Promo Kode">
                                        <i class="fi fi-rr-refresh fs-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label required">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label required">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label required">Source</label>
                                <select name="source" id="promo-src" class="form-control">
                                    <option value="">Select Source</option>
                                    <option value="0">Fixed Amount</option>
                                    <option value="1">Formula</option>
                                </select>
                            </div>
                            <div data-source="0" class="d-none">
                                <div class="fv-row">
                                    <label class="col-form-label required">Amount</label>
                                    <input type="text" name="amount" class="form-control number" required>
                                </div>
                            </div>
                            <div data-source="1" class="d-none">
                                <div class="fv-row">
                                    <label class="col-form-label required">Base</label>
                                    <select name="target" id="target" onchange="formulaView()" class="form-control">
                                        <option value="">Select Source</option>
                                        @foreach (\Config::get('constants.promo_src') as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label required">Formula</label>
                                    <textarea name="formula" id="formula" onkeyup="formulaView()" class="form-control" placeholder="Ex : * 0.05" cols="30" rows="10"></textarea>
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label">View Formula : <span id="view-formula"></span></label>
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label required">Maximum Amount</label>
                                    <input type="text" name="amount_limit" class="form-control number" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="submit" value="store" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('be.promo.store') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Hapus Promo</span>
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

        function formulaView(){
            var target = $("#target").val()
            var formula = $("#formula").val()
            var txt = "$" + target + " " + formula
            $("#view-formula").text(txt)
        }

        function randomKodePromo(){
            $.ajax({
                url : "{{ route('be.promo') }}",
                type : "get",
                dataType : "json",
                data : {
                    act : "random",
                }
            }).then(function(resp){
                $("#kode-promo").val(resp.kode)
            })
        }

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

            $.ajax({
                url : "{{ route('be.promo') }}",
                type : "GET",
                dataType : "json",
                data : {
                    id : id,
                    act : "edit"
                },
                success : function(res){
                    var data = res.data
                    $("#form-post input[name=description]").val(data.description)
                    $("#form-post input[name=code]").val(data.code)
                    $("#form-post input[name=start_date]").val(data.start_date)
                    $("#form-post input[name=end_date]").val(data.end_date)
                    $("#form-post select[name=source]").val(data.source).trigger("change")
                    $("#form-post select[name=target]").val(data.target).trigger("change")
                    $("#form-post textarea[name=formula]").val(data.formula).trigger("keyup")
                    $("#form-post input[name=amount_limit]").val(data.amount_limit)
                    $("#form-post input[name=id]").val(data.id)

                    $("#modalAdd").modal("show")
                }
            })
        }

        $(document).ready(function(){

            $("input.number").number(true, 0, ",", ".")
            $("input.number-decimal").number(true, 2, ",", ".")

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

            $("#promo-src").change(function(){
                $("[data-source]").addClass('d-none')
                $("[data-source]").find("input, select").prop('required', false)
                $("[data-source="+this.value+"]").removeClass("d-none")
                $("[data-source="+this.value+"]").find("input, select").prop('required', true)
            })
        })
    </script>
@endsection
