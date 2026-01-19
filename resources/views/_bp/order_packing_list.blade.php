@extends('layouts.templatePrint', ['bgWrapper' => "bg-secondary"])

@php
    function roundUpToNearestHalf($number) {
        return ceil($number * 2) / 2;
    }
@endphp

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

            .pb {
                page-break-before: always;
            }

            body {
                position: absolute;
                left: 0;
                top: 0;
            }

            #kt_wrapper {
                background-color: white !important;
            }

            /* body {
                visibility: hidden;
            } */
            #print-div {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
    <div class="page">
        <div class="d-flex flex-column h-100 gap-2">
            <div class="d-flex justify-content-end gap-2 mb-5">
                @php
                    $_url = route("be.orders.invoice", $order->id);
                    if(!empty($order->peb_invoice)){
                        $_url = asset($order->peb_invoice);
                    }
                @endphp
                <a href="{{ $_url }}" target="_blank" class="btn btn-success no-print">Download Invoice</a>
                <button type="button" class="btn btn-primary no-print" onclick="window.print()">Print</button>
            </div>
            <div class="flex flex-column gap-2 min-w-1000px" id="print-div">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <span class="fs-3 fw-bold">Packing List / Customs Declaration</span>
                            <span>Date : {{ date("d M Y H:I a") }}</span>
                            <div class="row mt-5">
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <span>Shipper (From)</span>
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
                                        <span>Consignee (To)</span>
                                        @if($order->recipient_as == "Perusahaan")
                                            <span class="fw-semibold">{{ $order->recipient_company_name }}</span>
                                        @endif
                                        <span class="fw-semibold">{{ $order->recipient_name }}</span>
                                        <div class="d-flex flex-column mt-5">
                                            <span>{{ $order->recipient_address }}<br> {{ $order->zip }}</span>
                                            <div>
                                                <span class="fw-semibold">Phone : </span>
                                                <span>{{ $order->recipient_phone }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-6"></div>
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <span>Courier : {{ $vendor->nama }}</span>
                                        <span>Destination Country : {{ $negara->nama }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <table>
                                            <tr>
                                                <td>Total Weight</td>
                                                <td>:</td>
                                                <td>{{ $total_berat ?? 0 }} Kg</td>
                                            </tr>
                                            <tr>
                                                <td>Total Parcel</td>
                                                <td>:</td>
                                                <td>{{ $total_parcel }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">&nbsp;</td>
                                            </tr>
                                            @foreach ($content as $item)
                                                <tr>
                                                    <td>Berat Paket {{ $loop->iteration }}</td>
                                                    <td>:</td>
                                                    <td>{{ roundUpToNearestHalf($item['berat']) }} kg</td>
                                                </tr>
                                                @if ($produk->tipe_kategori != "w")
                                                    <tr>
                                                        <td>Dimensions (LxWxH) Paket {{ $loop->iteration }}</td>
                                                        <td>:</td>
                                                        <td>{{ $item['panjang'] }} x {{ $item['lebar'] }} x {{ $item['tinggi'] }} = {{ roundUpToNearestHalf($item['volumetric']) }} kg</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr>
                                            @endforeach
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
                                                <td style="width: 80%;" class="text-center">NAME</td>
                                                <td class="text-center">QTY.</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($produk->tipe_kategori == "w")
                                                <tr>
                                                    <th>{{ $order->isi_kiriman }}</th>
                                                    <th>1 pcs</th>
                                                </tr>
                                            @else
                                                @foreach ($listItem as $item)
                                                    <tr>
                                                        <th>{{ $item['nama'] }}</th>
                                                        <th>{{ $item['jumlah'] }} pcs</th>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <span class="mt-3">I hereby declare the contents are as stated and non-dangerous good and certify that the information on this packing
                                list is all the true and correct.</span>
                            <div class="d-flex flex-column mt-20">
                                <span>Designation of Authorised Signatory</span>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <span>Signature/Stamp</span>
                                <span class="fw-bold">{{ $comp->company_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-none pb">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <span class="fs-3 fw-bold">Invoice</span>
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
                                            @if($order->recipient_as == "Perusahaan")
                                                <span>{{ $order->recipient_company_name }}</span>
                                            @endif
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
                                <div class="col-12 mt-5 mb-5">
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
                                                @if ($produk->tipe_kategori != "w")
                                                    {{-- <td>Commodity Code</td> --}}
                                                    {{-- <td>Tax Paid</td> --}}
                                                    {{-- <td>Tax Item Weight/Item Total Weight</td> --}}
                                                    <td>Origin</td>
                                                    {{-- <td>Reference Type & ID</td> --}}
                                                @endif
                                                <td>QTY</td>
                                                @if ($produk->tipe_kategori != "w")
                                                    <td>Unit Value</td>
                                                    <td>Sub Total Value</td>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($produk->tipe_kategori != "w")
                                                @foreach ($listItem as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <th>{{ $item['nama'] }}</th>
                                                        {{-- <td>IB:B701.<br>3990090</td> --}}
                                                        {{-- <td></td> --}}
                                                        {{-- <td>{{ $total_berat }} kg</td> --}}
                                                        <td>INDONESIA</td>
                                                        {{-- <td></td> --}}
                                                        <th>{{ $item['jumlah'] }} pcs</th>
                                                        <td>{{ number_format($item['harga'], 2) }} {{ $order->currency ?? "IDR" }}</td>
                                                        <td>{{ number_format($item['harga'] * $item['jumlah'], 2) }} {{ $order->currency ?? "IDR" }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>{{ $produk->nama }}</td>
                                                    <td>{{ $order->isi_kiriman }}</td>
                                                    <td>1 pcs</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12">
                                    @php
                                        $total_goods = collect($listItem)->sum(function($item){
                                            return $item['jumlah'] * $item['harga'];
                                        })
                                    @endphp
                                    <table class="w-100">
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
                                                            {{ $order->ddp == 1 ? "DDP" : "DDU" }}
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
                                                        <span>Terms of Trade: </span>
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
    </div>
@endsection

@section('custom_script')
    <script>
        function printDiv(divId) {
            // Get the content of the div
            const content = document.getElementById(divId).innerHTML;

            // Create a new window
            const printWindow = window.open('', '', 'width=800,height=600');

            // Write the HTML for printing
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print</title>
                    <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    h2 { color: #333; }
                    /* Add your custom print CSS here */
                    </style>
                </head>
                <body>
                    ${content}
                </body>
                </html>
            `);

            // Close the document to finish loading content
            printWindow.document.close();

            // Wait a bit to ensure all content is loaded, then print
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script>
@endsection