@php
    function roundUpToNearestHalf($number) {
        return ceil($number * 2) / 2;
    }
@endphp


@extends('layouts.templatePrint', ['bgWrapper' => "bg-secondary"])

@section('content')
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                visibility: hidden;
            }
            #print {
                visibility: visible;
                /* position: absolute;
                left: 0;
                top: 0; */
            }
        }
    </style>
    <div class="container">
        <div class="d-flex flex-column gap-3">
            <div class="d-flex justify-content-end">
                @if ($order->status == 0 || $order->status == 2)
                <button type="button" class="btn btn-success no-print" data-bs-toggle="modal" data-bs-target="#modalPay">Pay</button>
                @endif
                <button type="button" class="btn btn-primary no-print" onclick="window.print()">Print</button>
            </div>
            <div class="card shadow-none" id="print">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-baseline">
                            <div class="d-flex flex-column">
                                <span class="fs-3 fw-bold">Invoice</span>
                                <span>Date : {{ date("d M Y H:I a") }}</span>
                            </div>
                            <span>{{ $comp->company_name }}</span>
                        </div>
                        <div class="row mt-5">
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <span>Ship From</span>
                                    <span class="fw-semibold">{{ $order->sender_name }}</span>
                                    <div class="d-flex flex-column mt-5">
                                        <span>{{ $order->sender_address }}, Indonesia</span>
                                        <div>
                                            <span class="fw-semibold">Phone : </span>
                                            <span>{{ $order->sender_phone }}</span>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">Email : </span>
                                            <span>{{ $order->sender_email }}</span>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">NIK/No. Passport/NPWP : </span>
                                            <span>{{ $order->sender_npwp ?? $order->sender_nik }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <span>Ship To</span>
                                    <span class="fw-semibold">{{ $order->recipient_name }}</span>
                                    <div class="d-flex flex-column mt-5">
                                        <span>{{ $order->recipient_address }}, {{ $negara->nama ?? "-" }}</span>
                                        <div>
                                            <span class="fw-semibold">Phone : </span>
                                            <span>{{ $order->recipient_phone }}</span>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">Email : </span>
                                            <span>{{ $order->recipient_email }}</span>
                                        </div>
                                        {{-- <div>
                                            <span class="fw-semibold">NIK/No. Passport/NPWP : </span>
                                            <span>{{ $order->sender_npwp ?? $order->sender_nik }}</span>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <table>
                                        <tr>
                                            <td>Total Parcel</td>
                                            <td>:</td>
                                            <td>{{ $total_parcel }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Weight</td>
                                            <td>:</td>
                                            <td>{{ roundUpToNearestHalf($total_berat ?? 0) }} Kg</td>
                                        </tr>
                                        @if ($produk->tipe_kategori != "w")
                                            @foreach ($content as $item)
                                                <tr>
                                                    <td>Dimensions Item {{ $loop->iteration }} (LxWxH)</td>
                                                    <td>:</td>
                                                    <td>{{ $item['panjang'] }} x {{ $item['lebar'] }} x {{ $item['tinggi'] }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>Volumetric</td>
                                                <td>:</td>
                                                <td>{{ roundUpToNearestHalf(collect($content)->sum('volumetric')) }} kg</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Chargable</td>
                                            <td>:</td>
                                            <td>{{ roundUpToNearestHalf(collect($content)->sum('multiplier')) }} kg</td>
                                        </tr>
                                        <tr>
                                            <td>Shipment Terms</td>
                                            <td>:</td>
                                            <td>{{ $order->ddp == 1 ? "DDP" : "DDU" }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-6">

                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td style="width: 80%;" class="text-center">DESCRIPTION</td>
                                            <td class="text-center">AMOUNT</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sub_total = 0;
                                            $invoice = 0;
                                        @endphp
                                        @if($type == "order")
                                            <tr>
                                                <td>Biaya Kirim {{ $vendor->nama }} ke {{ $book->tujuan->nama ?? "-" }}</td>
                                                <td align="right">
                                                    <div class="d-flex justify-content-between">
                                                        <span>IDR</span>
                                                        <span>{{ number_format($order->biaya_kirim, 0, ',', ".") }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @if (!empty($order->green))
                                                <tr>
                                                    <td>Go Green</td>
                                                    <td align="right">
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->green, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($order->demand_surcharges))
                                                <tr>
                                                    <td>Demand Surcharges</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->demand_surcharges, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($order->fuel_surcharge))
                                                <tr>
                                                    <td>Fuel Surcharge</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->fuel_surcharge, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($order->ddp == 1)
                                            <tr>
                                                <td>Delivery Duty Paid</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <span>IDR</span>
                                                        <span>{{ number_format($order->delivery_duty, 0, ',', ".") }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($order->peb == 1)
                                            <tr>
                                                <td>Export Declaration</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <span>IDR</span>
                                                        <span>{{ number_format($order->export_declare, 0, ',', ".") }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @if ($order->fumigasi > 0)
                                                <tr>
                                                    <td>Fumigasi</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->fumigasi, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->nsu > 0)
                                                <tr>
                                                    <td>Non Stackable Unit</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->nsu, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->overweight > 0)
                                                <tr>
                                                    <td>Overweight</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->overweight, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->oversize > 0)
                                                <tr>
                                                    <td>Oversize</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->oversize, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @else
                                            @php
                                                $bk = $order->base_price;
                                                $kbk = $order->koreksi_biaya_kirim - $bk;
                                                $kasuransi = $order->koreksi_asuransi - $order->asuransi;
                                                $kfcharge = $order->koreksi_fuel_charge - $order->fuel_surcharge;
                                                $kfumigasi = $order->koreksi_fumigasi - $order->fumigasi;
                                                $knsu = $order->koreksi_nsu - $order->nsu;
                                                $kduty = $order->koreksi_delivery_duty - $order->delivery_duty;
                                                $keb = $order->koreksi_export_declare - $order->export_declare;
                                                $ddp = $order->ddp_amount;
                                                $kvat = $order->koreksi_vat - $order->vat;
                                                $sub_total = $kbk + $kasuransi + $kfcharge + $kfumigasi + $kduty + $keb + $ddp + $order->remote_area + $order->elevated_risk + $order->restricted_destination + $knsu;
                                                // dd($sub_total);
                                                $invoice = $sub_total + $kvat;

                                            @endphp
                                            @if ($kbk > 0)
                                                <tr>
                                                    <td>Biaya Kirim {{ $vendor->nama }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($kbk, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($kfcharge > 0)
                                                <tr>
                                                    <td>Fuel Surcharge</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($kfcharge, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($kasuransi > 0)
                                                <tr>
                                                    <td>Asuransi</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($kasuransi, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($kfumigasi > 0)
                                                <tr>
                                                    <td>Fumigasi</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($kfumigasi, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($knsu > 0)
                                                <tr>
                                                    <td>Non Stacakble Unit</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($knsu, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($kduty > 0)
                                                <tr>
                                                    <td>Delivery Duty</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($kduty, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($ddp > 0)
                                                <tr>
                                                    <td>Delivery Duty Import</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($ddp, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($keb > 0)
                                                <tr>
                                                    <td>Export Declare</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($keb, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->remote_area > 0)
                                                <tr>
                                                    <td>Remote Area</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->remote_area, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->elevated_risk > 0)
                                                <tr>
                                                    <td>Elevated Risk</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->elevated_risk, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($order->restricted_destination > 0)
                                                <tr>
                                                    <td>Restricted Destination</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->restricted_destination, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        @php
                                            $total = 0;
                                            if($type == "order"){
                                                $sub_total = $order->biaya_kirim + $order->demand_surcharges + $order->green + $order->fumigasi + $order->delivery_duty + $order->export_declare + $order->fuel_surcharge + $order->overweight + $order->oversize + $order->ncp + $order->nsu;
                                                $total = ($sub_total + $order->pickup_amount) - $order->promo_amount;
                                                $invoice = $total + $order->vat;
                                            }
                                        @endphp
                                        <tr>
                                            <td align="right" class="fw-bold">Sub Total</td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <span>IDR</span>
                                                    <span>{{ number_format($sub_total, 0, ',', ".") }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @if($type == "order")
                                            @if ($order->pickup == 1 && $order->pickup_amount > 0)
                                                <tr>
                                                    <td align="right" class="fw-bold">Pickup Amount</td>
                                                    <td>
                                                        <div class="d-flex justify-content-between">
                                                            <span>IDR</span>
                                                            <span>{{ number_format($order->pickup_amount, 0, ',', ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td align="right" class="fw-bold">Promo</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <span>IDR</span>
                                                        <span>({{ number_format($order->promo_amount, 0, ',', ".") }})</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" class="fw-bold">Total</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <span>IDR</span>
                                                        <span>{{ number_format($total, 0, ',', ".") }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" class="fw-bold">VAT</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <span>IDR</span>
                                                        <span>{{ number_format($order->vat, 0, ',', ".") }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td align="right" class="fw-bold">VAT</td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <span>IDR</span>
                                                        <span>{{ number_format($kvat, 0, ',', ".") }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td align="right" class="fw-bold">Invoice Amount</td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <span>IDR</span>
                                                    <span>{{ number_format($invoice, 0, ',', ".") }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <span class="mt-3">I hereby declare the contents are as stated and non-dangerous good and certify that the information on this packing
                            list is all the true and correct.</span> --}}
                        <div class="d-flex flex-column mt-20">
                            <span>Designation of Authorised Signatory</span>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <span>Signature/Stamp</span>
                            <span>{{ $comp->company_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade no-print" tabindex="-1" id="modalPay">
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
                                    <span class="fs-3">Saldo Deposit (<span class="fs-3" data-saldo="{{ $saldoDeposit }}">{{ number_format($saldoDeposit, 0, ",", ".") }}</span>)</span>
                                    <div class="d-flex gap-2 align-items-center">
                                        <div>
                                            <div class="form-check form-switch form-check-custom form-check-solid">
                                                <label class="align-items-center d-flex flex-row-reverse form-check-label gap-3">
                                                    <input class="form-check-input" name="use_saldo" {{ $saldoDeposit == 0 ? "disabled" : "" }} type="checkbox" onclick="toggleSaldo(this)" value="1"/>
                                                    Gunakan Saldo
                                                </label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="saldo" value="{{ $saldoDeposit }}">
                                        {{-- <span class="fs-3" data-saldo="{{ $saldoDeposit }}">{{ number_format($saldoDeposit, 0, ",", ".") }}</span> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="separator separtor-solid" data-virual></div>
                            {{-- <span class="fs-3 fw-bold">Total {{ number_format($order->koreksi_biaya, 0, ",", ".") }}</span> --}}
                            <div class="d-flex flex-column" data-virual data-kt-buttons="true">
                                <span class="fs-3 fw-bold mb-5">Pilih Metode Pembayaran</span>
                                <span class="fs-3 mb-5">Virtual Account</span>
                                <label data-kt-button="true" data-toggle="virtual" data-target="permata" class="p-5 border-bottom d-flex align-items-center active justify-content-between bg-hover-light gap-5">
                                    <div class="d-flex align-items-center gap-5">
                                        <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgYRXRn-ok9aV3B9zGFZqp3DpWXOtLu4Pf_3ErDOUhxEDQaLeHBTIbzJM_gbQ44XFXA2pEXv4yZek05MHHi0RsoQm_RIWJcgNqqpAr_fc3qP-PpgGnK5Tn7plQbNxwyPvaLW8YNwsfqjTcVm_htDWCHvi83bP2tOc4bZl9HaqU3rlzTc2GPcNu5wA/w640-h160/Bank%20Permata%20-koleksilogo.com.png" class="w-50px" alt="">
                                        <span class="fs-3">Permata Bank</span>
                                    </div>
                                    <input class="form-check-input" type="radio" checked name="virtual_account" value="permata"/>
                                </label>
                                <label data-kt-button="true" data-toggle="virtual" data-target="qris" class="p-5 border-bottom d-flex align-items-center justify-content-between bg-hover-light gap-5">
                                    <div class="d-flex align-items-center gap-5">
                                        {{-- <img src="https://d2f3dnusg0rbp7.cloudfront.net/snap/v4/assets/bni-163d98085f5fe9df4068b91d64c50f5e5b347ca2ee306d27954e37b424ec4863.svg" class="w-50px" alt=""> --}}
                                        <span class="fs-3">QRIS</span>
                                    </div>
                                    <input class="form-check-input" type="radio" name="virtual_account" value="qris"/>
                                </label>
                            </div>
                            <div class="separator separator-solid"></div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fs-3">Nominal</span>
                                    <span class="fs-3" data-biaya="{{ $invoice }}">{{ number_format($invoice, 0, ",", ".") }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between d-none">
                                    <span class="fs-3">Pakai Saldo</span>
                                    <span class="fs-3" data-pakai-saldo></span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fs-3 fw-bold">Total Pembayaran</span>
                                    <span class="fs-3 fw-bold" data-total-biaya>{{ number_format($invoice, 0, ",", ".") }}</span>
                                </div>
                            <div class="d-flex flex-column gap-5 d-none" data-virual-detail="permata">
                                <div class="d-flex justify-content-between">
                                    <span>Permata Bank</span>
                                    <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgYRXRn-ok9aV3B9zGFZqp3DpWXOtLu4Pf_3ErDOUhxEDQaLeHBTIbzJM_gbQ44XFXA2pEXv4yZek05MHHi0RsoQm_RIWJcgNqqpAr_fc3qP-PpgGnK5Tn7plQbNxwyPvaLW8YNwsfqjTcVm_htDWCHvi83bP2tOc4bZl9HaqU3rlzTc2GPcNu5wA/w640-h160/Bank%20Permata%20-koleksilogo.com.png" class="w-50px" alt="">
                                </div>
                                <span>Lakukan pembayaran dari rekening bank Permata ke nomor rekening berikut.</span>
                                <div class="fv-row">
                                    <label class="col-form-label">Nomor Rekening</label>
                                    <input type="text" class="form-control" readonly value="0060032300323">
                                </div>
                                <div class="fv-row">
                                    <label class="col-form-label">Nama Rekening</label>
                                    <input type="text" class="form-control" readonly value="PT. Samaya Karina Abadi">
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-5 d-none" data-virual-detail="qris">
                                <div class="d-flex justify-content-between">
                                    <span>QRIS</span>
                                    {{-- <img src="https://d2f3dnusg0rbp7.cloudfront.net/snap/v4/assets/bni-163d98085f5fe9df4068b91d64c50f5e5b347ca2ee306d27954e37b424ec4863.svg" class="w-50px" alt=""> --}}
                                </div>
                                <span>Lakukan pembayaran dengan scan QRIS di bawah ini.</span>
                                <img src="{{ asset("media/qris.jpg") }}" class="w-100" alt="">
                            </div>
                            <input type="hidden" name="deposit_id" value="{{ $order->id }}">
                            <input type="hidden" name="status" value="{{ $order->status }}">
                            @csrf
                            <button type="button" onclick="pay(this)" data-virual-button class="btn btn-warning">
                                Bayar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

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
    </script>
@endsection
