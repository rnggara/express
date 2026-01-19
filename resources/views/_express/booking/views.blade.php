@php
    function roundUpToNearestHalf($number) {
        return ceil($number * 2) / 2;
    }
@endphp

@extends("_express.layout")

@section('view_content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<style>
    #map { height: 600px; }
    #search-container { margin: 10px; }
    #search-input { width: 300px; }
    #suggestions { border: 1px solid #ccc; max-height: 200px; overflow-y: auto; background: #fff; }
    .suggestion-item { padding: 5px; cursor: pointer; }
    .suggestion-item:hover { background: #ddd; }
</style>
    <div class="d-flex flex-column gap-5">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Detail Booking</h3>
            </div>
        </div>
        <div class="container d-flex flex-column gap-5 align-items-center">
            @if (\Session::has("notif"))
                <div class="alert alert-success">
                    <div class="d-flex flex-column gap-5">
                        <div class="d-flex justify-content-between">
                            <span class="fs-3">Pesan</span>
                        </div>
                        <span>Pesanan telah berhasil dibuat.</span>
                    </div>
                </div>
            @endif
            <div class="w-100 d-flex flex-column gap-5">
                <div class="d-flex justify-content-between">
                    <a href="{{ route("booking.index") }}" class="btn btn-secondary btn-sm rounded-4">
                        <i class="fa fa-chevron-left"></i>
                        Kembali
                    </a>
                    <a href="{{ route("booking.index") }}" class="btn btn-success btn-sm rounded-4">
                        <i class="fa fa-chevron-right"></i>
                        Lanjutkan
                    </a>
                </div>
                <div class="bg-white border border-success p-5">
                    <div class="d-flex flex-column gap-10">
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <span>Nomor Booking: </span>
                                    <span class="fw-bold">{{ $book_order->kode_book }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center gap-1">
                                    <span>Tanggal: </span>
                                    <span class="fw-bold">{{ date("d F Y h:i a", strtotime($book_order->created_at)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span>Langkah yang harus dilakukan</span>
                            <ol>
                                <li>Atur penjemputan barang secara gratis melalui bagian Free Pickup.</li>
                                <li>Cantumkan kode booking {{ $book_order->kode_book }} dipaket Anda.</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="card card-p-0">
                    <div class="card-body p-5">
                        <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_1">Detail</a>
                            </li>
                            @if ($book_order->pickup == 1)
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_2">Pickup</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_3">Kirim ke {{ env("APP_NAME") }}</a>
                                </li>
                            @endif
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="kt_tab_pane_1" role="tabpanel">
                                <div class="d-flex flex-column gap-5">
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
                                                            </div>
                                                        </div>
                                                        @if ($produk->tipe_kategori != "w")
                                                            <div class="d-flex flex-column">
                                                                <label>
                                                                    <span class="fw-bold">Nilai Barang: </span>
                                                                    <span>{{ $book_order->currency ?? "IDR" }} {{ number_format($book_order->total_harga_usd, 0, ",", ".") }}</span>
                                                                </label>
                                                                <span class="text-info">Pengiriman ini kemungkinan akan dikenakan pajak dan bea cukai di negara tujuan: {{ $tujuan->nama }}.</span>
                                                            </div>
                                                        @else
                                                            
                                                        @endif
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
                                                                <div class="d-flex align-items-center gap-1">
                                                                    <span class="fw-bold">Go Green: </span>
                                                                    <span>IDR {{ number_format($book_order->green, 0, ",", ".") }}</span>
                                                                </div>
                                                            @endif
                                                            @if (!empty($book_order->demand_surcharges))
                                                                <div class="d-flex align-items-center gap-1">
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
                                                                <div class="d-flex align-items-center gap-1">
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
                                                                <div class="d-flex align-items-center gap-1">
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
                            </div>
                            <div class="tab-pane fade show active" id="kt_tab_pane_2" role="tabpanel">
                                <form method="post" action="{{ route("booking.pickup_request") }}" class="d-flex flex-column gap-5">
                                    <div id="search-container">
                                        <input type="text" class="form-control w-100" id="search-input" name="full_address" placeholder="Cari alamat/lokasi pengambilan di sini, lalu tekan Enter">
                                        <div id="suggestions"></div>
                                    </div>
                                    <span>Pastikan Anda memilih lokasi pengambilan sesuai dengan list yang muncul dibagian pencarian alamat/lokasi diatas.</span>
                                    <div id="map" class="h-300px"></div>
                                    <div class="separator separator-solid"></div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label required">Nama</label>
                                                <input type="text" class="form-control" name="sender_name" value="{{ !empty($pickup_request) ? $pickup_request->sender_name : $book_order->sender_name }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label required">Handphone (cth: 81234567890)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">+62</span>
                                                    <input type="text" class="form-control" name="sender_phone" value="{{ !empty($pickup_request) ? ltrim($pickup_request->sender_phone, "0") : ltrim($book_order->sender_phone, "0") }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label required">Detail Lokasi (cth: nomor rumah, patokan)</label>
                                                <input type="text" class="form-control" name="sender_address" value="{{ !empty($pickup_request) ? $pickup_request->sender_address : $book_order->sender_address }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label required">Tanggal Pengambilan Kiriman (kecuali hari minggu dan libur nasional)</label>
                                                <input type="text" class="form-control flatpicker" id="tgl" name="tanggal" value="{{ !empty($pickup_request) ? $pickup_request->tanggal : date("Y-m-d") }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label required">Jam Pengambilan Kiriman</label>
                                                <select name="jam" class="form-select" id="jam" data-placeholder="Pilih Pengambilan Jam">
                                                    <option value=""></option>
                                                    @if (!empty($pickup_request))
                                                        <option value="{{ $pickup_request->pickup_jam }}" selected>{{ $pickup_request->pickup_jam }}</option>
                                                    @endif
                                                    {{-- <option value="09:00 - 10:00" {{ !empty($pickup_request) && $pickup_request->jam == "09:00 - 10:00" ? "selected" : "" }}>Jam 09:00 - 10:00</option>
                                                    <option value="10:00 - 11:00" {{ !empty($pickup_request) && $pickup_request->jam == "10:00 - 11:00" ? "selected" : "" }}>Jam 10:00 - 11:00</option>
                                                    <option value="11:00 - 12:00" {{ !empty($pickup_request) && $pickup_request->jam == "11:00 - 12:00" ? "selected" : "" }}>Jam 11:00 - 12:00</option>
                                                    <option value="12:00 - 13:00" {{ !empty($pickup_request) && $pickup_request->jam == "12:00 - 13:00" ? "selected" : "" }}>Jam 12:00 - 13:00</option>
                                                    <option value="13:00 - 14:00" {{ !empty($pickup_request) && $pickup_request->jam == "13:00 - 14:00" ? "selected" : "" }}>Jam 13:00 - 14:00</option>
                                                    <option value="14:00 - 15:00" {{ !empty($pickup_request) && $pickup_request->jam == "14:00 - 15:00" ? "selected" : "" }}>Jam 14:00 - 15:00</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        @csrf
                                        <input type="hidden" name="latitude" id="latitude" value="{{ !empty($pickup_request) ? $pickup_request->latitude : "" }}">
                                        <input type="hidden" name="longitude" id="longitude" value="{{ !empty($pickup_request) ? $pickup_request->longitude : "" }}">
                                        <input type="hidden" name="order_id" value="{{ $book_order->id }}">
                                        @empty($pickup_request)
                                        <div class="col-6 mt-5">
                                            <button type="submit" class="btn btn-primary">Kirim</button>
                                        </div>
                                        @endempty
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                                <div class="d-flex flex-column gap-5 align-items-center">
                                    <div class="bg-info text-white p-5">
                                        <div class="box-body text-left">
                                            Jika Anda tidak ingin menggunakan layanan free pickup (penjemputan
                                            barang secara gratis) dari kami. Silakan lakukan langkah-langkah
                                            berikut, untuk mengirimkan barang Anda kepada kami.
                                        </div>
                                    </div>
                                    <h5>Step 1: Nomor Booking</h5>
                                    <p>Demi kelancaran proses pengiriman, harap cantumkan Nomor Booking berikut di kiriman Anda.</p>
                                    <div class="bgi-no-repeat w-100 bgi-position-center bgi-size-contain booking-box d-flex flex-column justify-content-center" style=" height: 180px; background-image: url('{{ asset("images/box.png") }}')">
                                        <p class="text-center"><span class="bg-white">{{ $book_order->kode_book }}</span></p>
                                    </div>
                                    <br><br>

                                    <h5>Step 2: Kirim Ke Alamat</h5>
                                    <div class="d-flex flex-column align-items-center">
                                        <span>{{ $cmp->address }}</span>
                                        <div>
                                            Phone: <a href="tel:{{ $cmp->phone }}">{{ $cmp->phone }}</a>
                                        </div>
                                        <div class="text-center">
                                            Business Hours:<br>
                                            {!! $cmp->p_subtitle !!}
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>

        const map = L.map('map').setView([-6.181814216283196, 106.82281654922433], 11);

        const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const geocoder = L.Control.Geocoder.nominatim(); // You can use different geocoders

        function reverseGeocode(lat, lng, callback) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const address = data.display_name || 'Unknown address';
                    callback(address);
                })
                .catch(() => {
                    callback('Unable to get address');
                });
        }

        // Get the search input element and suggestions container
        const searchInput = document.getElementById('search-input');
        const suggestionsContainer = document.getElementById('suggestions');

        // Handle input event to show address suggestions
        searchInput.addEventListener('input', function() {
            const query = this.value;
            if (query.length > 2) { // Only search if the query is longer than 2 characters
                fetchSuggestions(query);
            } else {
                suggestionsContainer.innerHTML = ''; // Clear suggestions if query is too short
            }
        });

        // Function to fetch address suggestions
        function fetchSuggestions(query) {
            geocoder.geocode(query, function(results) {
                suggestionsContainer.innerHTML = ''; // Clear previous suggestions
                results.forEach(result => {
                    const { center, name } = result;
                    const suggestionItem = document.createElement('div');
                    suggestionItem.className = 'suggestion-item';
                    suggestionItem.textContent = name;
                    suggestionItem.onclick = function() {
                        selectSuggestion(center, name);
                    };
                    suggestionsContainer.appendChild(suggestionItem);
                });
            });
        }

        function setMarkerFromLatLng(lat, lng) {
            if (typeof marker !== 'undefined') {
                map.removeLayer(marker);
            }
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            map.setView([lat, lng], 13);
            reverseGeocode(lat, lng, function(address) {
                marker.bindPopup(address).openPopup();
                $(searchInput).val(address)
            });
        }

        function selectSuggestion(center, name) {
            if (typeof marker !== 'undefined') {
                map.removeLayer(marker);
            }

            // Add a new marker at the selected address
            $(searchInput).val(name)
            marker = L.marker(center, { draggable: true }).addTo(map)
                .bindPopup(name)
                .openPopup();

            $("#latitude").val(center[0])
            $("#longitude").val(center[1])

            // Center map on the new marker
            map.setView(center, 13);

            // Handle marker dragend event
            marker.on('dragend', function(event) {
                const { lat, lng } = event.target.getLatLng();
                console.log(`Marker dragged to: ${lat}, ${lng}`);

                // Reverse geocode the new position
                $("#latitude").val(lat)
                $("#longitude").val(lng)
                reverseGeocode(lat, lng, function(address) {
                    event.target.setPopupContent(`${address}`);
                    $(searchInput).val(address)
                });
            });

            // Clear suggestions
            suggestionsContainer.innerHTML = '';
        }

        document.getElementById('search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchAddress(this.value);
            }
        });

        $(document).ready(function() {

            @if(!empty($pickup_request))
                setMarkerFromLatLng($("#latitude").val(), $("#longitude").val())
            @endif


            // L.Control.geocoder({
            //     geocoder: geocoder,
            //     defaultMarkGeocode: false
            // }).on('markgeocode', function(e) {
            //     const { center } = e.geocode;

            //     if (typeof marker !== 'undefined') {
            //         map.removeLayer(marker);
            //     }

            //     map.setView(center, 13);
            //     marker = L.marker(center, { draggable: true }).addTo(map)
            //         .bindPopup(e.geocode.name)
            //         .openPopup();

            //     marker.on('dragend', function(event) {
            //         console.log(event)
            //         const { lat, lng } = event.target.getLatLng();
            //         console.log(`Marker dragged to: ${lat}, ${lng}`);
            //         // Update marker popup with new position
            //         reverseGeocode(lat, lng, function(address) {
            //             event.target.setPopupContent(`${address}`).openPopup();
            //         });
            //     });
            // }).addTo(map);


            function onMapClick(e) {
                console.log(e.latlng, e)
            }

            map.on('click', onMapClick);

            $("table.table-display-2").DataTable()

            $("input.flatpicker").flatpickr()

            $("#jam").select2({
                ajax : {
                    url : "{{ url()->current() }}",
                    type : "get",
                    dataType : "json",
                    data : function(d){
                        d.date = $("#tgl").val()
                        d.act = "jam"

                        return d
                    }
                }
            })
        })


    </script>
@endsection
