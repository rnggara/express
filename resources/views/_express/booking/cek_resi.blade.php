@extends('_express.layout')

@section('view_css')
<style>
    @media print {
        @page {
            size: A4;
            margin: 0;
        }
        .no-print {
            display: none;
        }

        .pb {
            page-break-before: always;
        }

        body {
            visibility: hidden;
        }

        #printId {
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
            margin: 10;
        }
    }

    .page {
        /* height: 297mm; */
    }
</style>
@endsection

@section('view_content')
@php
    function roundUpToNearestHalf($number) {
        return ceil($number * 2) / 2;
    }
@endphp
<div class="d-flex flex-column gap-5" id="printId">
    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-primary no-print" onclick="window.print()">Print</button>
    </div>
    <div class="accordion" id="kt_accordion_1">
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">
                <button class="accordion-button text-dark bg-secondary rounded-0 fs-4 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="true" aria-controls="kt_accordion_1_body_1">
                    Alamat
                </button>
            </h2>
            <div id="kt_accordion_1_body_1" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <span class="fs-4 mb-5">Pengirim</span>
                                <span>{{ "$book_order->sender_name ($book_order->sender_as)" }}</span>
                                <span>NIK/No. Passport/NPWP: {{ $book_order->nik ?? $book_order->npwp }}</span>
                                <span>{{ $book_order->sender_address }}</span>
                                <a href="whatsapp://send?phone={{ $book_order->sender_phone }}">{{ $book_order->sender_phone }}</a>
                                <a href="mailto:{{ $book_order->sender_email }}">{{ $book_order->sender_email }}</a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <span class="fs-4 mb-5">Penerima</span>
                                @if($book_order->recipient_as == "Perusahaan")
                                    <span>{{ $book_order->recipient_company_name }}</span>
                                @endif
                                <span>{{ "$book_order->recipient_name ($book_order->recipient_as)" }}</span>
                                <span>{{ $book_order->recipient_address }}</span>
                                <span>{{ $tujuan->nama }}</span>
                                <span>{{ $book_order->zip }}</span>
                                <a href="whatsapp://send?phone={{ $book_order->recipient_phone }}">{{ $book_order->recipient_phone }}</a>
                                <a href="mailto:{{ $book_order->recipient_email }}">{{ $book_order->recipient_email }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion" id="kt_accordion_2">
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="kt_accordion_2_header_1">
                <button class="accordion-button bg-info text-white rounded-0 fs-4 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_2_body_1" aria-expanded="true" aria-controls="kt_accordion_2_body_1">
                    Kurir
                </button>
            </h2>
            <div id="kt_accordion_2_body_1" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_2_header_1" data-bs-parent="#kt_accordion_2">
                <div class="accordion-body">
                    <div class="d-flex flex-column gap-5">
                        <div class="row">
                            <label class="col-6">
                                <span class="fw-bold">Kurir: </span>
                                <span>{{ $vendor->nama }}</span>
                                <a href="{{ $vendor->link_tracking.$book_order->nomor_awb }}" class="btn btn-sm rounded-4 btn-success">Lacak Pengiriman</a>
                            </label>
                            <div class="d-flex align-items-center gap-1 col-6">
                                <span class="fw-bold">Negara Tujuan: </span>
                                <div class="d-flex gap-3 align-items-center">
                                    <span>{{ $dari->nama }}</span>
                                    <i class="fa fa-plane text-dark"></i>
                                    <span>{{ $tujuan->nama }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-6">
                                <span class="fw-bold">Jenis Barang: </span>
                                <span>{{ $produk->nama }}</span>
                            </label>
                            <div class="d-flex align-items-center gap-1 col-6">
                                @if ($produk->tipe_kategori != "w")
                                    @php
                                        $cat = [];
                                        foreach ($kategori as $key => $value) {
                                            $cat[] = $value->nama;
                                        }
                                    @endphp
                                    <span class="fw-bold">Kategori: </span>
                                    <div class="d-flex gap-3 align-items-center">
                                        {{ implode(", ", $cat) }}
                                    </div>
                                @else
                                    <span class="fw-bold">Kategori: </span>
                                    <div class="d-flex gap-3 align-items-center">
                                        {{ $produk->nama }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <label>
                                <span class="fw-bold">Nilai Barang: </span>
                                <span>{{$book_order->currency ?? "USD"}} {{ number_format($book_order->total_harga_usd, 0, ",", ".") }}</span>
                            </label>
                            {{-- <span class="text-info">Pengiriman ini kemungkinan akan dikenakan pajak 17% dan bea cukai di negara tujuan: ANGOLA, karena nilai CIF (Cost Insurance and Freight) barang / total nilai perolehan barang (nilai barang + ongkir + asuransi (jika ada) melewati batas bebas pajak (USD 100) dan batas bea cukai (USD 100).</span> --}}
                        </div>
                        <div class="d-flex flex-column">
                            @php
                                $total_berat = 0;
                            @endphp
                            @foreach ($content as $item)
                                @php
                                    $volume = $item['panjang'] * $item['lebar'] * $item['tinggi'];
                                    $colv = round(($volume * $item['total_paket']) / 5000,1);
                                    $total_berat += $colv > ($item['berat'] * $item['total_paket']) ? $colv : ($item['berat'] * $item['total_paket']);
                                @endphp
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Jumlah Paket: </span>
                                    <span>{{ $item['total_paket'] }}</span>
                                    <span class="fw-bold">Berat: </span>
                                    <span>{{ $item['berat'] }} kg</span>
                                    @if ($produk->tipe_kategori != "w")
                                        <span class="fw-bold">Dimensi (cm): </span>
                                        <span>{{ $item['panjang'] }} x {{ $item['lebar'] }} x {{ $item['tinggi'] }}</span>
                                        <span class="fw-bold">Volumetric (kg): </span>
                                        <span>{{ roundUpToNearestHalf($item['volumetric']) }}</span>
                                    @endif
                                </div>
                                @if (!empty($item['surcharge']))
                                    <div class="ms-5">- {{ $item['surcharge']['label'] }} : {{ number_format($item['surcharge']['price'], 0, ',', '.') }}</div>
                                @endif
                            @endforeach
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Total Berat: </span>
                                <span>{{ collect($content ?? [])->sum("berat") }} kg</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Berat Terhitung: </span>
                                <span>{{ $total_berat }} kg</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Biaya kirim: </span>
                                <span>IDR {{ number_format($book_order->biaya_kirim, 0, ",", ".") }}</span>
                                <input type="hidden" name="biaya_kirim" id="biaya_kirim" value="{{ $book_order->biaya_kirim }}">
                            </div>
                            @if (!empty($book_order->green))
                                <div class="d-flex align-items-center gap-1" id="fumigasi-show">
                                    <span class="fw-bold">Go Green: </span>
                                    <span>IDR {{ number_format($book_order->green, 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @if (!empty($book_order->demand_surcharges))
                                <div class="d-flex align-items-center gap-1" id="fumigasi-show">
                                    <span class="fw-bold">Demand Surcharges: </span>
                                    <span>IDR {{ number_format($book_order->demand_surcharges, 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @if (!empty($book_order->promo_amount))
                                <div class="d-flex align-items-center gap-1" id="vat-show">
                                    <span class="fw-bold">Promo: </span>
                                    <span>IDR ({{ number_format(($book_order->promo_amount), 0, ",", ".") }})</span>
                                </div>
                            @endif
                            @if (!empty($book_order->fuel_surcharge))
                                <div class="d-flex align-items-center gap-1" id="fumigasi-show">
                                    <span class="fw-bold">Fuel Surcharge: </span>
                                    <span>IDR {{ number_format($book_order->fuel_surcharge, 0, ",", ".") }}</span>
                                </div>
                            @endif
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Delivery Duty Paid: </span>
                                <span>IDR {{ number_format($book_order->delivery_duty, 0, ",", ".") }}</span>
                                <input type="hidden" name="delivery_duty" id="delivery_duty" value="{{ $book_order->delivery_duty }}">
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Export Declaration: </span>
                                <span>IDR {{ number_format($book_order->export_declare, 0, ",", ".") }}</span>
                                <input type="hidden" name="export_declare" id="export_declare" value="{{ $book_order->export_declare }}">
                            </div>
                            @if ($book_order->overweight > 0)
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Overweight: </span>
                                    <span>IDR {{ number_format($book_order->overweight, 0, ",", ".") }}</span>
                                    <input type="hidden" name="overweight" id="overweight" value="{{ $book_order->overweight }}">
                                </div>
                            @endif
                            @if ($book_order->oversize > 0)
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Oversize: </span>
                                    <span>IDR {{ number_format($book_order->oversize, 0, ",", ".") }}</span>
                                    <input type="hidden" name="oversize" id="oversize" value="{{ $book_order->oversize }}">
                                </div>
                            @endif
                            @if ($book_order->ncp > 0)
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">NCP: </span>
                                    <span>IDR {{ number_format($book_order->ncp, 0, ",", ".") }}</span>
                                    <input type="hidden" name="ncp" id="ncp" value="{{ $book_order->ncp }}">
                                </div>
                            @endif
                            @if (!empty($book_order->asuransi))
                                <div class="d-flex align-items-center gap-1" id="asuransi-show">
                                    <span class="fw-bold">Asuransi: </span>
                                    <span>IDR {{ number_format($book_order->asuransi, 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @if (!empty($book_order->fumigasi))
                                <div class="d-flex align-items-center gap-1" id="fumigasi-show">
                                    <span class="fw-bold">Fumigasi: </span>
                                    <span>IDR {{ number_format($book_order->fumigasi, 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @if (!empty($book_order->nsu))
                                <div class="d-flex align-items-center gap-1" id="nsu-show">
                                    <span class="fw-bold">Non Stacakble Unit: </span>
                                    <span>IDR {{ number_format($book_order->nsu, 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @if ($book_order->pickup == 1 && $book_order->pickup_amount > 0)
                                <div class="d-flex align-items-center gap-1" id="fumigasi-show">
                                    <span class="fw-bold">Pickup Amount: </span>
                                    <span>IDR {{ number_format($book_order->pickup_amount, 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @if (!empty($book_order->vat))
                                <div class="d-flex align-items-center gap-1" id="vat-show">
                                    <span class="fw-bold">VAT: </span>
                                    <span>IDR {{ number_format($book_order->vat, 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @php
                                $tb = $book_order->total_biaya + $book_order->vat;
                            @endphp
                            <div class="d-flex align-items-center gap-1 mt-2">
                                <span class="fw-bold fs-3">Total Biaya Kirim: </span>
                                <span class="fw-bold fs-3" id="total-biaya">IDR {{ number_format($tb, 0, ",", ".") }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion" id="kt_accordion_3">
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="kt_accordion_3_header_1">
                <button class="accordion-button bg-warning text-white text-active-white rounded-0 fs-4 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_body_1" aria-expanded="true" aria-controls="kt_accordion_3_body_1">
                    Kiriman
                </button>
            </h2>
            <div id="kt_accordion_3_body_1" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_3_header_1" data-bs-parent="#kt_accordion_3">
                <div class="accordion-body">
                    <div class="d-flex flex-column gap-5">
                        @if($produk->tipe_kategori != "w")
                        <div class="row">
                            <div class="col-6">
                                <label for="">
                                    <span class="fw-bold">Alasan Ekspor: </span>
                                    <span>{{ $book_order->alasan_ekspor }}</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <label for="">
                                    <span class="fw-bold">Isi Kiriman: </span>
                                    <span>{{ $book_order->isi_kiriman }}</span>
                                </label>
                            </div>
                        </div>
                        @endif
                        <table class="table table-display-2 display table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @if ($produk->tipe_kategori == "w")
                                        <th>Deskripsi Dokumen</th>
                                    @else
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga ({{ $book_order->currency ?? "IDR" }})</th>
                                        <th>Sub-Total ({{ $book_order->currency ?? "IDR" }})</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($produk->tipe_kategori == "w")
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $book_order->isi_kiriman }}</td>
                                    </tr>
                                @else
                                    @foreach (collect($book_order->items ?? [])->where("jumlah", ">", 0) as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td>{{ $item['jumlah'] }}</td>
                                            <td align="right">{{ number_format($item['harga'], 0, ",", ".") }}</td>
                                            <td align="right">{{ number_format($item['harga'] * $item['jumlah'], 0, ",", ".") }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            @if ($produk->tipe_kategori != "w")
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Nilai Barang / Total (USD)	</th>
                                    <th class="text-end">{{ number_format($book_order->total_harga_usd, 0, ",", ".") }}</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
