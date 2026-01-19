@extends('layouts.templatePrint', ['bgWrapper' => "bg-secondary"])

@section('content')
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            .no-print {
                display: none;
            }

            body {
                visibility: hidden;
            }
            #print {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
            }
        }

        .page {
            /* height: 297mm; */
        }
    </style>
    <div class="container page">
        <div class="d-flex flex-column gap-3 h-100">
            <div class="card shadow-none h-100" id="print">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Proforma Invoice</span>
                        <div class="d-flex gap-5 align-items-center">
                            <label><span class="fw-bold">AWB No: </span>{{ $order->nomor_awb ?? "" }}</label>
                            <label><span class="fw-bold">Invoice Date: </span>{{ date("Y-m-d") }}</label>
                            <label><span class="fw-bold">Invoice No: </span></label>
                        </div>
                        <table>
                            <tr>
                                <td style="width: 50%">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">SHIP FROM</span>
                                        <span>{{ $order->sender_name }}</span>
                                        <span>{{ $order->sender_address }}</span>
                                    </div>
                                </td>
                                <td style="width: 50%">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">SHIP TO</span>
                                        <span>{{ $order->recipient_name }}</span>
                                        <span>{{ $order->recipient_address }}<br> {{ $order->zip }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>Indonesia</span>
                                        <span>+62{{ ltrim($order->sender_phone, "0") }}</span>
                                        <span>{{ $order->sender_email }}</span>
                                        <span>Trader Type : {{ $order->sender_as == "Personal" ? "PRIVATE" : "BUSINESS" }}</span>
                                        <span>VAT No : </span>
                                        <span>EORI : </span>
                                        <span>TAX ID : </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $negara->nama }}</span>
                                        <span>{{ $order->recipient_phone }}</span>
                                        <span>{{ $order->recipient_email }}</span>
                                        <span>Trader Type : {{ $order->recipient_as == "Personal" ? "PRIVATE" : "BUSINESS" }}</span>
                                        <span>VAT No : </span>
                                        <span>EORI : </span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="row mt-5">
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <label><span class="fw-bold">Shippper Reference:</span> </label>
                                    <label><span class="fw-bold">Receiver Reference:</span> </label>
                                </div>
                            </div>
                            <div class="col-12 mt-5 mb-10">
                                <div class="d-flex flex-column">
                                    <label><span class="fw-bold">Remarks:</span> </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Item</td>
                                            <td>Description</td>
                                            <td>Commodity Code</td>
                                            <td>Tax Paid</td>
                                            <td>Tax Item Weight/Item Total Weight</td>
                                            <td>COO</td>
                                            <td>Reference Type & ID</td>
                                            <td>QTY</td>
                                            <td>Unit Value</td>
                                            <td>Sub Total Value</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listItem as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <th>{{ $item['nama'] }}</th>
                                                <td>IB:B701.<br>3990090</td>
                                                <td></td>
                                                <td>{{ $total_berat }} kg</td>
                                                <td>INDONESIA</td>
                                                <td></td>
                                                <th>{{ $item['jumlah'] }} pcs</th>
                                                <td>{{ number_format($item['harga'], 2) }} {{ $order->currency ?? "IDR" }}</td>
                                                <td>{{ number_format($item['harga'] * $item['jumlah'], 2) }} {{ $order->currency ?? "IDR" }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                @php
                                    $total_goods = collect($listItem)->sum(function($item){
                                        return $item['jumlah'] * $item['harga'];
                                    })
                                @endphp
                                <table class="w-75">
                                    <tr>
                                        <td style="width: 40%; vertical-align: baseline">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Total Goods Value:</span>
                                                    <span>{{ number_format($total_goods, 2) }} {{ $order->currency ?? "IDR" }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Total Invoice Amount:</span>
                                                    <span>{{ number_format($total_goods, 2) }} {{ $order->currency ?? "IDR" }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Currency Code:</span>
                                                    <div>
                                                        <span>{{ $order->currency ?? "IDR" }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Terms of Payment:</span>
                                                    <div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 10%"></td>
                                        <td style="width: 40%; vertical-align: baseline">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Total line Items:</span>
                                                    <span>{{ count($listItem) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Number of Pallets:</span>
                                                    <span>{{ $order->jumlah_fumigasi }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Total Units:</span>
                                                    <span>{{ count($listItem) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Package Marks / Other Info:</span>
                                                    <div>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%; vertical-align: baseline">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Terms of Trade:</span>
                                                    <span>Delivered at Place</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Place of Incoterm:</span>
                                                    <span></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Reason Export:</span>
                                                    <div>
                                                        <span>{{ $order->alasan_ekspor }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Type of Export:</span>
                                                    <div>
                                                        <span>{{ $order->alasan_ekspor }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 10%"></td>
                                        <td style="width: 40%; vertical-align: baseline"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%; vertical-align: baseline">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Total Net Weight:</span>
                                                    <span>{{ number_format($total_berat, 0) }}kg</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Total Gross Weight:</span>
                                                    <span>{{ number_format($total_berat, 0) }}kg</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width: 10%"></td>
                                        <td style="width: 40%; vertical-align: baseline">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Payer of GST / VAT:</span>
                                                    <span></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Duty / taxes acct:</span>
                                                    <span></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>equire Pedimento:</span>
                                                    <span></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Duty / tax billing service:</span>
                                                    <span></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Carrier:</span>
                                                    <span>{{ $vendor->nama }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Ultimate Consignee:</span>
                                                    <span></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>Exemption Citation:</span>
                                                    <span></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <span class="mt-3">I/We hereby certify that the information contained in the invoice is true and correct and that the contents of this shipment are as stated above.</span>
                        <div class="mt-5 d-flex justify-content-between">
                            <div class="d-flex flex-column">
                                <span class="fw-bold">Name:</span>
                                <span class="fw-bold">Position:</span>
                                <span class="fw-bold">Date of Signature: {{ date("Y-m-d") }}</span>
                            </div>
                            <div class="d-flex gap-20">
                                <span class="fw-bold">Signature:</span>
                                <div class="border w-60px h-60px"></div>
                                <span class="fw-bold">Company Stamp</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            window.print()
        })
    </script>
@endsection
