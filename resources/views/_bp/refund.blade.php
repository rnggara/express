@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Refund Request</h3>
        </div>
        <div class="card-body">
            <table class="table display" id="table-applicant">
                <thead>
                    <tr>
                        <th class="text-nowrap">#</th>
                        <th class="text-nowrap">Kode Booking</th>
                        <th class="text-nowrap">Amount</th>
                        <th class="text-nowrap">Nama Bank</th>
                        <th class="text-nowrap">Nomor Rekening</th>
                        <th class="text-nowrap">Nama Rekening</th>
                        <th class="text-nowrap">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($refunds as $i => $item)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>{{ $item->order->kode_book ?? "-" }}</td>
                            <td>
                                {{ number_format($item->amount, 0, ",", ".") }}
                            </td>
                            <td>{{ $item->bank_name }}</td>
                            <td>{{ $item->no_rekening }}</td>
                            <td>{{ $item->account_name }}</td>
                            <td>
                                <span class="badge badge-{{ empty($item->transfer_at) ? "secondary" : "success" }}">{{ empty($item->transfer_at) ? "waiting" : "refunded" }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 10px">
                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                </button>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" onclick="approveData({{ $item->id }})" class="menu-link px-3">
                                            Approve
                                        </a>
                                        <a href="javascript:;" onclick="deleteData({{ $item->id }})" class="menu-link px-3 text-danger">
                                            Cancel
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

    <div class="modal fade" tabindex="-1" id="modalApprove">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('be.refund.store') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Approve Refund?</span>
                            <span class="text-center fs-3">Apakah Anda sudah mentrasfer data ini?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id" value="">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="submit" value="approve" class="btn btn-primary">Ya</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('be.refund.store') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <span class="fw-bold fs-3 mb-5">Batalkan Refund</span>
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

        function approveData(id){
            $("#modalApprove input[name=id]").val(id)

            $("#modalApprove").modal("show")
        }

        function deleteData(id){
            $("#modalDelete input[name=id]").val(id)

            $("#modalDelete").modal("show")
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
        })
    </script>
@endsection
