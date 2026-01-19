@extends('_express.layout')

@section('view_content')
    <div class="d-flex flex-column gap-5">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-1">Booking</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-end">
                        <div class="btn-group" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                            <!--begin::Radio-->
                            <a href="{{ route("booking.index") }}" class="btn btn-outline btn-color-muted btn-active-primary {{ $a == "outstanding" ? "active" : "" }}" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="method" value="1"/>
                                <!--end::Input-->
                                Outstanding
                            </a>
                            <!--end::Radio-->

                            <!--begin::Radio-->
                            <a href="{{ route("booking.index") }}?a=archive" class="btn btn-outline btn-color-muted btn-active-success {{ $a == "archive" ? "active" : "" }}" data-kt-button="true">
                                <!--begin::Input-->
                                <input class="btn-check" type="radio" name="method" checked="checked" value="2"/>
                                <!--end::Input-->
                                Archive
                            </a>
                            <!--end::Radio-->
                        </div>
                    </div>
                    <table class="table table-display table-bordered display" data-ordering="false">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Kode Booking</th>
                                <th>Tujuan</th>
                                <th>Nominal</th>
                                <th>Nomor Resi</th>
                                <th>Nomor AWB</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $item)
                                @php
                                    $book = $bs[$item->book_id] ?? [];
                                    $tb = $item->grand_total;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date("d M Y H:i", strtotime($item->created_at)) }}</td>
                                    <td>
                                        <a href="{{ route("booking.views", base64_encode($book->book_kode)) }}" class="fw-bold">{{ $item->kode_book }}</a>
                                        @if ($item->ddp == 1)
                                            <i class="fa fa-info-circle text-dark" data-bs-toggle="tooltip" title="Akan ada biaya tambahan untuk import tax dan lain-lainnya"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $tujuan[$book->tujuan_id]->nama ?? "-" }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 align-items-center">
                                            <span>{{ number_format($tb, 0, ',', ".") }}</span>
                                            @if (!empty($item->koreksi_biaya))
                                                <i class="fa fa-arrow-right text-dark"></i>
                                                <span>{{ number_format($item->koreksi_biaya, 0, ',', ".") }}</span>
                                                <i class="fa fa-info-circle text-dark" data-bs-toggle="tooltip" title="{{ $item->koreksi_notes }} {{ number_format($item->koreksi_biaya, 0, ',', ".") }}"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if (!empty($item->nomor_resi))
                                        <a href="{{ route("cek.resi", $item->nomor_resi) }}" class="badge badge-warning">
                                            {{ $item->nomor_resi }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $item->nomor_awb ?? "-" }}</td>
                                    <td>
                                        @if ($item->status == 0)
                                            <span class="badge badge-secondary">Menunggu Pembayaran</span>
                                        @else
                                            @if ($item->status == 1)
                                                {{-- <span class="badge badge-primary">Menunggu Pembayaran</span> --}}
                                                <span class="badge badge-primary">Menunggu Konfirmasi</span>
                                            @elseif($item->status == 2)
                                                @if ($item->outstanding_payment > 0)
                                                    <span class="badge badge-success">Menunggu Pembayaran Tambahan</span>
                                                @else
                                                    <span class="badge badge-success">Menunggu Konfirmasi</span>
                                                    @if ($item->outstanding_payment < 0)
                                                        <i class="fa fa-info-circle text-dark" data-bs-toggle="tooltip" title="Kelebihan pembayaran sebesar {{ number_format(abs($item->outstanding_payment), 0, ',', ".") }} akan menjadi Saldo Deposit"></i>
                                                    @endif
                                                @endif
                                            @elseif($item->status == 3)
                                                <span class="badge badge-success">Pembayaran Selesai</span>
                                            @elseif($item->status == -1)
                                                <span class="badge badge-warning">Refund Request</span>
                                            @elseif($item->status == -2)
                                                <span class="badge badge-danger">Refund Success</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status == 0 || $item->status == 2)
                                            @if ($item->status == 2 && $item->outstanding_payment <= 0)
                                                <a href='javascript:;' onclick="confirmData({{ $item->id }}, this)" data-oustanding-payment="{{ abs($item->outstanding_payment) }}" class="btn btn-success btn-sm">
                                                    Konfirmasi
                                                </a>
                                            @else
                                                <a href='{{ route('booking.invoice', ['type' => $item->status == 0 ? "order" : "additional", 'id' => $item->id]) }}' class="btn btn-success btn-sm">
                                                    Bayar Sekarang
                                                </a>
                                            @endif
                                        @if ($item->status == 0)
                                            <a href="{{ route("booking.delete", $item->id) }}" class="btn btn-danger btn-sm">
                                                <i class="fa fa-times"></i>
                                                Batal
                                            </a>
                                        @else
                                            @if ($item->status == 2 && $item->outstanding_payment > 0)
                                                <button type="button" onclick="refundData({{ $item->id }}, '{{ $tb }}')" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times"></i>
                                                    Batal & Request Refund
                                                </buttont>
                                            @endif
                                        @endif
                                        @php
                                            $paymentAmount = $item->status == 0 ? $tb : $item->outstanding_payment;
                                            // $paymentAmount += $item->vat;
                                        @endphp
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route("booking.refund") }}" method="post">
        <div class="modal fade" tabindex="-1" id="modalRefund">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route("booking.confirm_payment") }}" method="post">
                        <div class="modal-body p-10">
                            <div class="d-flex flex-column gap-5">
                                <div class="d-flex w-100 justify-content-end">
                                    <a href="javascript:;" data-bs-dismiss="modal">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-3" data-virual>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fs-3">Refund Request</span>
                                    </div>
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label">Amount</label>
                                    <input type="text" name="amount" class="form-control number" readonly>
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label required">Nama Bank</label>
                                    <input type="text" name="bank_name" class="form-control" required>
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label required">Nomor Rekening</label>
                                    <input type="text" name="no_rekening" class="form-control" required>
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label required">Nama Rekening</label>
                                    <input type="text" name="account_name" class="form-control" required>
                                </div>
                                @csrf
                                <input type="hidden" name="id">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route("booking.confirm_order") }}" method="post">
        <div class="modal fade" tabindex="-1" id="modalConfirm">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route("booking.confirm_payment") }}" method="post">
                        <div class="modal-body p-10">
                            <div class="d-flex flex-column gap-5">
                                <div class="d-flex w-100 justify-content-end">
                                    <a href="javascript:;" data-bs-dismiss="modal">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-3" data-virual>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fs-3">Order Confirmation</span>
                                    </div>
                                    <span>Are you sure to confirm this order?</span>
                                    <span id="oustanding-payment"></span>
                                </div>
                                @csrf
                                <input type="hidden" name="id">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('view_script')
    <script>

        function confirmData(id, el){
            $("#modalConfirm input[name=id]").val(id)
            $("#oustanding-payment").text("")
            if($(el).data("oustanding-payment") > 0){

                $("#oustanding-payment").text(`Kelebihan pembayaran sebesar ${$.number($(el).data("oustanding-payment"), 0, ',', ".")} akan menjadi Saldo Deposit`)
            }

            $("#modalConfirm").modal("show")
        }

        function refundData(id, amount){
            $("#modalRefund input[name=id]").val(id)
            $("#modalRefund input[name=amount]").val($.number(amount, 0,',','.'))

            $("#modalRefund").modal("show")
        }

        var tb = $("table.display").DataTable()

        function submitForm(me){
            $(me).parents("form").eq(0).submit()
        }

        function pay(me){
            var modal = $(me).parents("form").eq(0)

            var total_biaya = $(modal).find("span[data-total-biaya]").text().replaceAll(".", "") * 1

            if(total_biaya > 0){
                var target = $(modal).find("input[name=virtual_account]:checked").val()
                $(modal).find('[data-virual]').addClass('d-none')
                modal.find('[data-virual-detail="'+target+'"]').removeClass("d-none")
                // $(me).attr("type", "submit")
                $(me).attr("onclick", "submitForm(this)")

            } else {
                $(modal).find("form").submit()
            }
        }

        function toggleSaldo(me){
            var checked = $(me).prop('checked')
            var modal = $(me).parents("form").eq(0)
            var biaya = $(modal).find("span[data-biaya]").data("biaya") * 1
            var saldo = $(modal).find("span[data-saldo]").data("saldo") * 1
            $(modal).find("span[data-pakai-saldo]").parent().addClass("d-none")
            var total_biaya = biaya
            if(checked){
                total_biaya = (biaya - saldo) < 0 ? 0 : biaya - saldo
                biaya = saldo > biaya ? biaya : saldo
                $(modal).find("span[data-pakai-saldo]").text($.number(biaya * -1, 0, ",", "."))
                $(modal).find("span[data-pakai-saldo]").parent().removeClass("d-none")
            }
            $(modal).find("span[data-total-biaya]").text($.number(total_biaya, 0, ",", "."))
        }

        $(document).ready(function(){

            $(tb).on("draw", function(){
                $("[data-bs-toggle=tooltip]").tooltip()
            })

            $('[data-toggle="virtual"]').click(function(){
                // var modal = $(this).parents("div.modal").eq(0)
                // var target = $(this).attr("data-target")
                // $(modal).find('[data-virual]').addClass('d-none')
                // modal.find('[data-virual-detail="'+target+'"]').removeClass("d-none")
                // modal.find('[data-virual-button]').removeClass("d-none")
            })
        })

    </script>
@endsection

