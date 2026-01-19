@extends('layouts.template', [
    "withoutFooter" => true
])

@section('content')
    <div class="d-flex flex-column gap-5">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-1">Orders</span>
                <span>Order List</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-display table-bordered display" data-ordering="false">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Kode Booking</th>
                            <th>User</th>
                            <th>Shipper</th>
                            <th>Consignee</th>
                            <th>Status</th>
                            <th>CIPL</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $books = $book;
                        @endphp
                        @foreach ($orders as $item)
                            @php
                                $book = $books[$item->book_id] ?? [];
                                $vendor = $vendor[$book->vendor_id ?? 0] ?? [];
                                $fcharges = collect($fcharges[$book->vendor_id ?? 0] ?? [])->last();
                                $total_berat = 0;
                                $content = $book->content['request'] ?? [];
                                $remote_area = $fcharges->remote_area ?? 0;
                                foreach ($content as $ct) {
                                    $volume = $ct['panjang'] * $ct['lebar'] * $ct['tinggi'];
                                    $colv = round(($volume * $ct['total_paket']) / 5000,1);
                                    $total_berat += $colv > ($ct['berat'] * $ct['total_paket']) ? $colv : ($ct['berat'] * $ct['total_paket']);
                                }

                                $rm = ($fcharges->remote_area_multiplier ?? 0) * $total_berat;
                                if($rm > $remote_area){
                                    $remote_area = $rm;
                                }
                            @endphp
                            @if (!empty($book))
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date("d M Y H:i", strtotime($item->created_at)) }}</td>
                                <td>{{ $item->kode_book }}</td>
                                <td>{{ $item->user->name ?? "" }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>Name : {{ $item->sender_name }}</span>
                                        @if ($item->sender_as == "Perusahaan")
                                            <span>Company_name : {{ $item->sender_company_name }}</span>
                                        @endif
                                        <span>Email : {{ $item->sender_email }}</span>
                                        <span>Phone : {{ $item->sender_phone }}</span>
                                        <span>Province/State : {{ $item->sender_province ?? '-' }}</span>
                                        <span>City : {{ $item->sender_city ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>Country : {{ $book->tujuan->nama }}</span>
                                        <span>Name : {{ $item->recipient_name }}</span>
                                        @if ($item->recipient_as == "Perusahaan")
                                            <span>Company_name : {{ $item->recipient_company_name }}</span>
                                        @endif
                                        <span>Email : {{ $item->recipient_email }}</span>
                                        <span>Phone : {{ $item->recipient_phone }}</span>
                                        <span>Province/State : {{ $item->recipient_province ?? "-" }}</span>
                                        <span>City : {{ $item->recipient_city ?? "-" }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->status == 0)
                                        <span class="badge badge-secondary">Menunggu Persetujuan</span>
                                    @else
                                        @if ($item->status == 1)
                                            <span class="badge badge-primary">Menunggu Pembayaran</span>
                                        @elseif($item->status == 2)
                                            @if ($item->outstanding_payment > 0)
                                                <span class="badge badge-warning">Menunggu Pembayaran Tambahan</span>
                                            @else
                                                <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                            @endif
                                        @elseif($item->status == 3)
                                            <span class="badge badge-success">Pembayaran Diterima</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status >= 2)
                                        <a href="{{ route('be.orders.views', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fi fi-rr-print"></i>
                                            CIPL
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == 1)
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi{{ $item->id }}" class="btn btn-warning btn-sm">Konfirmasi</button>
                                        <div class="modal fade" tabindex="-1" id="modalKonfirmasi{{ $item->id }}">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content px-10">
                                                    <form action="{{ route("order.konfirmasi") }}" method="post">
                                                        <div class="modal-header border-0">
                                                            <div class="d-flex w-100 justify-content-end">
                                                                <a href="javascript:;" data-bs-dismiss="modal">
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="d-flex flex-column gap-5">
                                                                <span class="fs-3 fw-bold">Kode Booking: {{ $item->kode_book }}</span>
                                                                <div class="d-flex align-items-center gap-3 align-items-center">
                                                                    <span>{{ $book->dari->nama ?? "" }}</span>
                                                                    <span class="fa fa-plane"></span>
                                                                    <span>{{ $book->tujuan->nama ?? "" }}</span>
                                                                </div>
                                                                <span>Produk Tipe: {{ $book->produk->nama ?? "" }}</span>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Biaya Kirim</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="biaya_kirim" value="{{ number_format($item->base_price - $item->asuransi, 2, ',', '.') }}">
                                                                    <input type="hidden" name="biaya_kirim_hidden" value="{{ $item->base_price - $item->asuransi }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Non Stackable Unit</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="nsu" value="{{ number_format($item->nsu, 2, ',', '.') }}">
                                                                    <input type="hidden" name="nsu_hidden" value="{{ $item->nsu }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Fumigasi</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="fumigasi" value="{{ number_format($item->fumigasi, 2, ',', '.') }}">
                                                                    <input type="hidden" name="fumigasi_hidden" value="{{ $item->fumigasi }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Fuel Surcharge</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="fuel_surcharge" value="{{ number_format($item->fuel_surcharge, 2, ',', '.') }}">
                                                                    <input type="hidden" name="fuel_surcharge_hidden" value="{{ $item->fuel_surcharge }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Delivery Duty Paid</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="delivery_duty" value="{{ number_format($item->delivery_duty, 2, ',', '.') }}">
                                                                    <input type="hidden" name="delivery_duty_hidden" value="{{ $item->delivery_duty }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Delivery Duty Additional Charge</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="ddp_amount" value="{{ number_format($item->ddp_amount, 2, ',', '.') }}">
                                                                    <input type="hidden" name="ddp_amount_hidden" value="{{ $item->ddp_amount }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Export Declaration</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="export_declare" value="{{ number_format($item->export_declare, 2, ',', '.') }}">
                                                                    <input type="hidden" name="export_declare_hidden" value="{{ $item->export_declare }}">
                                                                </div>
                                                                <div class="fv-row {{ $item->with_insurance == 1 ? "" : "d-none" }}">
                                                                    <label class="col-form-label">Asuransi</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="asuransi" value="{{ number_format($item->asuransi, 2, ',', '.') }}">
                                                                    <input type="hidden" name="asuransi_hidden" value="{{ $item->asuransi }}">
                                                                </div>
                                                                {{-- <div class="fv-row d-none">
                                                                    <label class="col-form-label">Fuel Charge</label>
                                                                    <input type="text" class="form-control number" data-element onkeyup="updateTotal(this)" name="fuel_charge" value="{{ number_format($item->fuel_charge, 2, ',', '.') }}">
                                                                </div> --}}
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <label class="col-form-label">Remote Areas (IDR {{ number_format($remote_area ?? 0, 2, ',', '.') }})</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" name="remote_areas" onchange="updateTotal(this)" value="{{ $remote_area ?? 0 }}"/>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <label class="col-form-label">Elevated-risk Destination (IDR {{ number_format($fcharges->elevated_risk_destination_price ?? 0, 2, ',', '.') }})</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" name="elevated_risk" onchange="updateTotal(this)" value="{{ $fcharges->elevated_risk_destination_price ?? 0 }}"/>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <label class="col-form-label">Restricted Destination (IDR {{ number_format($fcharges->restricted_destination_price ?? 0, 2, ',', '.') }})</label>
                                                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" name="restricted_destination" onchange="updateTotal(this)" value="{{ $fcharges->restricted_destination_price ?? 0 }}"/>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Promo</label>
                                                                    <input type="text" class="form-control number bg-transparent border-gray-300" name="promo" disabled value="{{ number_format($item->promo_amount, 2, ',', '.') }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Sub Total Biaya</label>
                                                                    <div class="d-flex gap-2 align-items-center">
                                                                        <div class="flex-fill">
                                                                            <input type="text" class="form-control number bg-transparent border-gray-300" disabled value="{{ number_format($item->sub_total, 2, ',', '.') }}">
                                                                        </div>
                                                                        <i class="fa fa-chevron-right text-dark"></i>
                                                                        <div class="flex-fill">
                                                                            <input type="text" class="form-control number bg-transparent border-gray-300" name="sub_total" disabled value="{{ number_format($item->sub_total, 2, ',', '.') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">VAT</label>
                                                                    <div class="d-flex gap-2 align-items-center">
                                                                        <div class="flex-fill">
                                                                            <input type="text" class="form-control number" readonly value="{{ number_format($item->vat, 2, ',', '.') }}">
                                                                        </div>
                                                                        <i class="fa fa-chevron-right text-dark"></i>
                                                                        <div class="flex-fill">
                                                                            <input type="text" class="form-control number" name="vat" readonly value="{{ number_format($item->vat, 2, ',', '.') }}">
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="vat_hidden" readonly value="{{ $item->vat }}">
                                                                    <input type="hidden" name="promo_hidden" readonly value="{{ $item->promo_amount }}">
                                                                    <input type="hidden" name="ncp_hidden" readonly value="{{ $item->ncp }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Total Biaya</label>
                                                                    <input type="text" class="form-control number bg-transparent border-gray-300" name="total_biaya" disabled value="{{ number_format($item->sub_total + $item->vat, 2, ',', '.') }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Outstanding Payment</label>
                                                                    <input type="text" class="form-control number" name="outstanding_payment" value="{{ number_format(0, 2, ',', '.') }}">
                                                                </div>
                                                                <div class="fv-row">
                                                                    <label class="col-form-label">Catatan</label>
                                                                    <textarea name="koreksi_notes" class="form-control" placeholder="Masukkan Catatan disini" id="" cols="30" rows="10"></textarea>
                                                                </div>
                                                                <input type="hidden" name="deposit_id" value="{{ $item->id }}">
                                                                @csrf
                                                                <button type="submit" data-virual-button class="btn btn-warning">
                                                                    Konfirmasi
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="border-0 modal-footer">

                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @if ($item->status >= 3)
                                            @if (empty($item->nomor_resi) && empty($item->nomor_awb))
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi{{ $item->id }}" class="btn btn-warning btn-sm">Input Resi & AWB</button>
                                                <div class="modal fade" tabindex="-1" id="modalKonfirmasi{{ $item->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content px-10">
                                                            <form action="{{ route("order.awb") }}" method="post" enctype="multipart/form-data">
                                                                <div class="modal-header border-0">
                                                                    <div class="d-flex w-100 justify-content-end">
                                                                        <a href="javascript:;" data-bs-dismiss="modal">
                                                                            <i class="fa fa-times"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="d-flex flex-column gap-5">
                                                                        <span class="fs-3 fw-bold">Kode Booking: {{ $item->kode_book }}</span>
                                                                        <div class="d-flex align-items-center gap-3 align-items-center">
                                                                            <span>{{ $book->dari->nama ?? "" }}</span>
                                                                            <span class="fa fa-plane"></span>
                                                                            <span>{{ $book->tujuan->nama ?? "" }}</span>
                                                                        </div>
                                                                        <span>Produk Tipe: {{ $book->produk->nama ?? "" }}</span>
                                                                        <div class="fv-row">
                                                                            <label class="col-form-label">Nomor Resi {{ env("APP_NAME") }}</label>
                                                                            <input type="text" class="form-control" required name="nomor_resi">
                                                                        </div>
                                                                        <div class="fv-row">
                                                                            <label class="col-form-label">AWB (Airway Bill) {{ $book->vendor->nama ?? "" }}</label>
                                                                            <input type="text" class="form-control" required name="nomor_awb">
                                                                        </div>
                                                                        <div class="fv-row">
                                                                            <label class="col-form-label">AWB (Airway Bill) Attachment</label>
                                                                            <input type="file" name="awb_file" class="form-control" accept=".png, .jpeg, .jpg, .pdf">
                                                                        </div>
                                                                        <input type="hidden" name="deposit_id" value="{{ $item->id }}">
                                                                        @csrf
                                                                        <button type="submit" data-virual-button class="btn btn-warning">
                                                                            Simpan
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="border-0 modal-footer">

                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                            <div class="d-flex flex-column gap-1">
                                                <span>Nomor Resi: {{ $item->nomor_resi }}</span>
                                                <span>Nomor AWB: {{ $item->nomor_awb }}</span>
                                                @if (empty($item->received_at))
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi{{ $item->id }}" class="btn btn-warning btn-sm">Pesanan Selesai</button>
                                                    <div class="modal fade" tabindex="-1" id="modalKonfirmasi{{ $item->id }}">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form action="{{ route("order.done") }}" method="post">
                                                                    <div class="modal-body p-10">
                                                                        <div class="d-flex flex-column gap-5">
                                                                            <div class="d-flex w-100 justify-content-end">
                                                                                <a href="javascript:;" data-bs-dismiss="modal">
                                                                                    <i class="fa fa-times"></i>
                                                                                </a>
                                                                            </div>
                                                                            <span class="fs-3 fw-bold text-center">Apakah Anda yaking pesanan telah Sampai di tujuan?</span>
                                                                            <div class="border d-flex flex-column rounded gap-5 p-3">
                                                                                <span class="fs-3 fw-bold">Kode Booking: {{ $item->kode_book }}</span>
                                                                                <div class="d-flex align-items-center gap-3 align-items-center">
                                                                                    <span>{{ $book->dari->nama ?? "" }}</span>
                                                                                    <span class="fa fa-plane"></span>
                                                                                    <span>{{ $book->tujuan->nama ?? "" }}</span>
                                                                                </div>
                                                                                <span>Produk Tipe: {{ $book->produk->nama ?? "" }}</span>
                                                                                <span>Nomor Resi: <a href="{{ route("cek.resi", $item->nomor_resi) }}" target="_blank">{{ $item->nomor_resi }}</a></span>
                                                                            </div>
                                                                            <input type="hidden" name="deposit_id" value="{{ $item->id }}">
                                                                            @csrf
                                                                            <div class="d-flex gap-3 w-100 justify-content-end">
                                                                                <button type="button" data-bs-dismiss="modal" class="btn text-primary">
                                                                                    Batal
                                                                                </button>
                                                                                <button type="submit" data-virual-button class="btn btn-primary">
                                                                                    Ya
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else

                                                @endif
                                            </div>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
    <script>

        function updateTotal(me){
            var modal = $(me).parents("div.modal").eq(0)

            var biayaKirim = modal.find("input[name=biaya_kirim]").val()
            var biayaKirimHidden = modal.find("input[name=biaya_kirim_hidden]").val()
            var fuelCharge = modal.find("input[name=fuel_surcharge]").val()
            var fuelChargeHidden = modal.find("input[name=fuel_surcharge_hidden]").val()
            var fumigasi = modal.find("input[name=fumigasi]").val()
            var fumigasiHidden = modal.find("input[name=fumigasi_hidden]").val()
            var nsu = modal.find("input[name=nsu]").val()
            var nsuHidden = modal.find("input[name=nsu_hidden]").val()
            var asuransi = modal.find("input[name=asuransi]").val()
            var asuransiHidden = modal.find("input[name=asuransi_hidden]").val()
            var delivery_duty = modal.find("input[name=delivery_duty]").val()
            var delivery_dutyHidden = modal.find("input[name=delivery_duty_hidden]").val()
            var export_declare = modal.find("input[name=export_declare]").val()
            var export_declareHidden = modal.find("input[name=export_declare_hidden]").val()
            var vat_hidden = modal.find("input[name=vat_hidden]").val()
            var ddp_amount = modal.find("input[name=ddp_amount]").val()
            var promo = modal.find("input[name=promo_hidden]").val()
            var ncp = modal.find("input[name=ncp_hidden]").val()

            var total = parseFloat(biayaKirim) + parseFloat(fuelCharge) + parseFloat(fumigasi) + parseFloat(delivery_duty) + parseFloat(export_declare) + parseFloat(asuransi) + parseFloat(ddp_amount)
                // total += parseFloat(ncp)
                total += parseFloat(nsu)
                total -= parseFloat(promo)

            var remoteAreas = modal.find("input[name=remote_areas]").is(":checked")
            var elevatedRisk = modal.find("input[name=elevated_risk]").is(":checked")
            var restrictedDestination = modal.find("input[name=restricted_destination]").is(":checked")

            if(remoteAreas){
                total += parseFloat(modal.find("input[name=remote_areas]").val())
            }

            if(elevatedRisk){
                total += parseFloat(modal.find("input[name=elevated_risk]").val())
            }

            if(restrictedDestination){
                total += parseFloat(modal.find("input[name=restricted_destination]").val())
            }

            var vat = total * 0.011
            var final = total + vat

            var outstandingPayment = (final - biayaKirimHidden - fuelChargeHidden - fumigasiHidden - delivery_dutyHidden - export_declareHidden - vat_hidden - asuransiHidden - ncp - nsuHidden)
                outstandingPayment += promo * 1

            modal.find("input[name=sub_total]").val(total)
            modal.find("input[name=vat]").val(vat)
            modal.find("input[name=total_biaya]").val(final)

            modal.find("input[name=outstanding_payment]").val(outstandingPayment)
        }


        $(document).ready(function(){
            $("table.display").DataTable()

            $("input.number").number(true, 2, ",", ".")


        })
    </script>
@endsection
