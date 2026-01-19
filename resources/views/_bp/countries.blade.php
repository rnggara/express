@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Negara Tujuan</h3>
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
                        <th class="text-nowrap">Nama Negara</th>
                        <th class="text-nowrap">Zone</th>
                        <th class="text-nowrap">Post Code</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $vendor_names = $vendors->pluck("nama", "id");
                    @endphp
                    @foreach ($countries as $i => $item)
                        @php
                            $dataZone = $item->zones ?? [];
                        @endphp
                        <tr>
                            <td>
                                {{$i+1}}
                            </td>
                            <td>
                                {{ $item->nama }}
                            </td>
                            {{-- <td>{{ $province[$item->profile->prov_id ?? null] ?? "-" }}</td>
                            <td>{{ $job_name[$jb->job_id ?? null] ?? "-" }}</td>
                            <td>
                                {{ $item->edu ?? "-" }}
                            </td>
                            <td class="text-center">
                                {{ $item->yoe }} Tahun
                            </td>
                            <td class="text-center">{{ $item->applied->count() }}</td> --}}
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    @foreach ($dataZone as $vid => $zn)
                                        @if (isset($vendors[$vid]))
                                            @php
                                                $_zone = $zones->find($zn);
                                            @endphp
                                            <div class="d-flex align-items-center gap-1">
                                                <span>{{ $vendors[$vid]->nama ?? "-" }}</span>
                                                <span>: Zone {{ $_zone->zone ?? 0 }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch form-check-custom form-check-solid">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="post_code" {{ $item->postcode == 1 ? "checked" : "" }} value="1" onchange="updatePostCode({{ $item->id }}, this)"/>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 10px">
                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                </button>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" onclick="editForm(this, {{ $item->id }})" data-zones="{{ json_encode($item->zones) }}" data-nama="{{ $item->nama }}" class="menu-link px-3">
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('be.settings_post') }}" id="form-post" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Tambah Negara</span>
                            <div class="fv-row">
                                <label class="col-form-label required">Nama Negara</label>
                                <input type="text" name="nama" class="form-control" required placeholder="Masukkan Nama Negara">
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label required">Zone Pricing</label>
                                <div class="d-flex flex-column gap-2 border rounded p-2">
                                    @foreach ($vendors as $item)
                                        <div class="fv-row">
                                            <label class="col-form-label">{{ $item->nama }}</label>
                                            <select name="zones[{{ $item->id }}]" class="form-control" data-zone data-control="select2" data-dropdown-parent="#modalAdd" data-placeholder="Select Zone" data-allow-clear="true">
                                                <option value=""></option>
                                                @foreach ($grouped[$item->id] ?? [] as $val)
                                                    <option value="{{ $val->id }}">{{ $val->zone }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
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
                <form action="{{ route('be.settings_post') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Hapus Negara</span>
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

        function updatePostCode(id, me){
            var checked = $(me).is(":checked")
            $.ajax({
                url: "{{ route('be.country_update_post_code') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}",
                    postcode: checked
                }
            })
        }

        function clearForm(){
            $("#form-post input:not([name=_token])").val("")
            $("#form-post button[type=submit]").text("Tambah")
        }

        function deleteData(id){
            $("#modalDelete input[name=id]").val(id)

            $("#modalDelete").modal("show")
        }

        function editForm(me, id){
            var nama = $(me).data("nama")
            var zones = $(me).data('zones')

            $("#form-post input[name=nama]").val(nama)

            $("#form-post select[data-zone]").val("").trigger("change")

            for (const key in zones) {
                if (Object.hasOwnProperty.call(zones, key)) {
                    const element = zones[key];
                    $("#form-post select[name='zones["+key+"]']").val(element).trigger('change')
                }
            }

            $("#form-post input[name=id]").val(id)
            $("#form-post button[type=submit]").text("Update")

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
