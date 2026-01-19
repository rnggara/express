@extends("_express.layout")

@section('view_content')
@php
    function roundUpToNearestHalf($number) {
        return ceil($number * 2) / 2;
    }
@endphp
<link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet"
    type="text/css" />
<div class="align-items-center d-flex flex-column gap-5">
    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex flex-column gap-5">
                <span class="fs-1">Booking</span>
            </div>
        </div>
    </div>
    <div class="row w-100">
        <div class="col-7">
            <a href="{{ route("lp") }}" class="btn btn-outline btn-outline-warning mb-5 btn-sm rounded-4">
                <i class="fa fa-times"></i>
                Batal
            </a>
            <form action="{{ route('booking.confirm') }}" id="form-book" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <div id="smartwizard">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#step-1">


                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#step-2">


                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#step-3">


                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="#step-4">


                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="tab-c">
                        <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                            <div class="d-flex flex-column">
                                <div class="bg-warning text-white p-5">
                                    <span class="fs-3">Informasi Pengiriman</span>
                                </div>
                                <div class="bg-white p-5 d-flex flex-column gap-5">
                                    <span>Hanya untuk kiriman barang tidak berbahaya dan bukan larangan export. Contoh yang
                                        diperbolehkan: pakaian, elektronik, buku dan bukan cairan.</span>
                                    <div class="bg-info p-10 d-flex flex-column gap-5 text-white">
                                        @if (isset($dataPage['informasi_pengiriman']))
                                            {!! $dataPage['informasi_pengiriman']->content !!}
                                        @else
                                            <div class="d-flex flex-column">
                                                <span>Raih manfaat dari jaringan global kami yang tak tertandingi, dipadukan
                                                    dengan layanan andal dengan harga terjangkau untuk pengiriman Anda yang
                                                    tidak mendesak.</span>
                                                <span>Waktu & Cakupan : Biasanya 2 hingga 7 hari kerja ke pasar utama</span>
                                                <span>Senin sampai Jumat.</span>
                                                <span>Layanan hari Sabtu tersedia di beberapa pasar berdasarkan permintaan.
                                                    (dibayar)</span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span>Ukuran & Berat Hingga:</span>
                                                <span>Panjang 274cm dan panjang + lingkar 330cm.</span>
                                                <span>berat 68kg.</span>
                                                <span>Layanan hari Sabtu tersedia di beberapa pasar berdasarkan permintaan.
                                                    (dibayar)</span>
                                            </div>
                                            <span>Untuk pengiriman satuan di bawah 68kg</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-secondary p-5">
                                    <span class="fs-3">Bea cukai & Pajak - {{ $tujuan->nama }}</span>
                                </div>
                                <div class="bg-white p-5 d-flex flex-column gap-5">
                                    @if (isset($dataPage['bea_cukai_dan_pajak']))
                                        {!! $dataPage['bea_cukai_dan_pajak']->content !!}
                                    @else
                                        <p>
                                            Perpajakan untuk negara tertentu tergantung pada GST/PPN lokal, serta kategori
                                            barang dan nilainya yang dinyatakan.<br>
                                            Ambang pajak (tax threshold) &amp; bea cukai (duty threshold) adalah jumlah dimana
                                            seseorang mulai membayar pajak berdasarkan nilai barang yang dinyatakan.<br>
                                            Pajak wajib dibayar oleh penerima di negara tujuan (jika ada). </p>
                                        <div class="row">
                                            <div class="col-6 col-md-4 mb-10"><strong>Tax Threshold</strong><br>100 USD</div>
                                            <div class="col-6 col-md-4 mb-10"><strong>Duty Threshold</strong><br>200 USD</div>
                                            <div class="col-12 col-md-4 mb-10"><strong>Tax (VAT/GST)</strong><br>10 %</div>
                                        </div>
                                        <p class="text-bold mb-0">Bea masuk</p>
                                        <div class="row">
                                            <div class="col-6 col-md-4 pr-0">
                                                Mobiles 20%<br>
                                                Tablets 20%<br>
                                                Computers &amp; Laptops 20%<br>
                                                Cameras 20%<br>
                                                Accessories (no-battery) 20%<br>
                                                Accessories (battery) 20%<br>
                                                Health &amp; Beauty 20%<br> </div>
                                            <div class="col-6 col-md-4 pr-0">
                                                Fashion 20%<br>
                                                Watches 20%<br>
                                                Jewelry 6%<br>
                                                Dry Food &amp; Supplements 14%<br>
                                                Home &amp; Appliances 20%<br>
                                                Home &amp; Garden 20%<br>
                                                Toys 20%<br> </div>
                                            <div class="col-12 col-md-4">
                                                Sports 20%<br>
                                                Luggage 20%<br>
                                                Audio Video 20%<br>
                                                Documents 0%<br>
                                                Gaming 6%<br>
                                                Books &amp; Collectibles 1%<br>
                                                Pet Accessories 12%<br> </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="bg-secondary p-5">
                                    <span class="fs-3">Syarat & Ketentuan</span>
                                </div>
                                <div class="bg-white h-300px overflow-auto p-5">
                                    @if (isset($dataPage['syarat_dan_ketentuan']))
                                        {!! $dataPage['syarat_dan_ketentuan']->content !!}
                                    @else
                                        <ol>
                                            <li>Dengan membayar invoice/tagihan {{ \Config::get("constants.APP_LABEL") }}, pengirim setuju bahwa pengiriman ini akan mengikuti aturan dan ketentuan yang berlaku sebagaimana yang tertuang di bagian ini.</li>
                                            <li>{{ \Config::get("constants.APP_LABEL") }} tidak bertanggung jawab atas perbedaan pernyataan isi kiriman dan nilai barang yang bisa menimbulkan masalah, atau tidak bisa diproses lebih lanjut, ataupun barang ditahan bea cukai sehingga barang tidak sampai. Segala biaya yang timbul karena ketidaksesuaian informasi mengenai isi barang akan ditanggung oleh pengirim. Biaya Ongkos Kirim hangus jika terdapat barang yang tidak dilaporkan ketika mengirim barang sehingga ditahan / dikembalikan oleh bea cukai.</li>
                                            <li>Setiap klaim kerusakan harus dilaporkan oleh Pengirim/Penerima kepada {{ \Config::get("constants.APP_LABEL") }}, dalam waktu 7 hari setelah paket diterima, {{ \Config::get("constants.APP_LABEL") }} tidak akan melayani klaim yang dilakukan lebih dari waktu tersebut. Foto barang yang rusak, termasuk kondisi packaging, dan jangan coba untuk diperbaiki. Barang pecah belah tidak termasuk sebagai barang yang bisa diklaim.</li>
                                            <li>{{ \Config::get("constants.APP_LABEL") }} tidak bertanggung jawab untuk setiap biaya, kerugian dan kerusakan yang terjadi karena kondisi natural/bawaan isi paket, atau isi barang yang tidak sesuai dengan pernyataan awal dan jika memang kerugian dan kerusakan terjadi karena faktor eksternal seperti kerusakan / kehilangan, limit tanggung jawab {{ \Config::get("constants.APP_LABEL") }} adalah sampai dengan USD 100, jumlah ini adalah jumlah maksimal penggantian, dan tidak lebih dari nilai yang dicantumkan ketika mengirim paket sebagaimana yang dijelaskan pada ketentuan point 5. Setiap pengiriman paket ataupun dokumen dilakukan oleh partner kurir {{ \Config::get("constants.APP_LABEL") }}, sehingga jika terjadi resiko, {{ \Config::get("constants.APP_LABEL") }} akan membantu sebagai wakil pengirim untuk melakukan klaim ke pihak Kurir. Jika membeli asuransi, maka akan ada waktu untuk claim sekitar 25 hari Kerja dari tanggal pelaporan diterima.</li>
                                            <li>Nilai Aktual                                            <ul>
                                                    <li>Dokumen tidak memiliki nilai komersial, akan tetapi ada biaya untuk persiapan dan penggantian, ataupun nilai lainnya..</li>
                                                    <li>Paket selain Dokumen harus menyatakan nilai komersial /aktual yang sebenarnya sesuai referensi yang ada, ataupun biaya untuk perbaikan dan penggantian barang, dan diambil yang paling rendahnya.</li>
                                                    <li>Tanggung jawab {{ \Config::get("constants.APP_LABEL") }} untuk setiap kehilangan dan kerusakan adalah senilai USD 100. Di atas itu mohon membeli asuransi.</li>
                                                </ul>
                                            </li>
                                            <li>Kerusakan dan kehilangan tidak termasuk kerugian tidak langsung yang mana disebabkan antara lain: keterlambatan pengiriman, kerugian pendapatan, keuntungan, bunga, dan perbedaan harga selisih pasar, dan kerugian lainnya.</li>
                                            <li>{{ \Config::get("constants.APP_LABEL") }} tidak bertanggung jawab atas kerusakan yang ditimbulkan karena proses pembungkusan yang dilakukan oleh pengirim yang tidak baik, ataupun proses pengepakan dan aspek lainnya yang dilakukan oleh pengirim.</li>
                                            <li>Proses Bea Cukai/Custom Clearance. Dengan ini pengirim menyatakan {{ \Config::get("constants.APP_LABEL") }} sebagai agen pengirim dan menunjuk kurir pengiriman untuk melakukan proses di bea cukai baik di Negara pengirim, maupun di Negara Tujuan.</li>
                                            <li>Biaya bea cukai. Pengirim bertanggung jawab penuh untuk menjamin bahwa penerima di Negara tujuan bersedia menerima barang tersebut dan membayar segala biaya bea cukai maupun biaya lainnya yang timbul, seperti biaya ganti alamat, biaya permit untuk barang tertentu dan jika tidak dibayar, maka pihak pengirim bertanggung jawab atas segala biaya yang mungkin terjadi.</li>
                                            <li>Tidak mengirim barang berupa barang palsu, daging, binatang, emas lantakan/bullion, rokok, vape, bubuk terlarang, batu mulia, senjata, peledak dan amunisi; abu manusia, barang-barang ilegal seperti gading dan narkotika, diklasifikasikan sebagai bahan berbahaya, barang berbahaya, bahan dilarang atau dibatasi menurut IATA (International Air Transport Association), ICAO (International Civil Aviation Organisation), ADR (Regulasi Transportasi Jalan Raya Eropa untuk barang berbahaya/ European Road Transport Regulation on dangerous goods), atau organisasi yang berkaitan lainnya ("Barang Berbahaya").</li>
                                            <li>Bertanggung jawab sebagai pribadi dan membebaskan PT. {{ \Config::get("constants.APP_LABEL") }} Global Indonesia dari segala tuntutan hukum yang terjadi jika terjadi hal-hal yang melanggar hukum dikarenakan pengiriman paket ini.</li>
                                            <li>Jika pengiriman barang lewat dari 1 bulan, maka mohon segera informasikan ke {{ \Config::get("constants.APP_LABEL") }} agar kami bisa segera lakukan klaim ke pihak kurir.</li>
                                            <li>Proses klaim atas kehilangan barang kiriman akan dilakukan dalam 40 hari kerja.</li>
                                            <li>{{ \Config::get("constants.APP_LABEL") }} tidak bisa memberikan ganti rugi atas kerugian karena keterlambatan ataupun resiko bisnis karena barang hilang/terlambat.</li>
                                            <li>Jika kiriman menggunakan fitur asuransi tambahan, maka otomatis klaim kehilangan akan diproses oleh kurir yang bersangkutan dan penggantian sesuai dengan nilai barang yang telah diinformasikan pada saat pengiriman.</li>
                                            <li>Setiap shipment yang sudah di bayar dan sudah sampai di kantor kami akan melewati proses reweight
                                                (pengukuran dan penimbangan ulang oleh pihak kami) dan pengecekan surcharges sebelum di proses. Jika ada
                                                biaya tambahan terkait perubahan berat/volume ataupun surcharges tambahan, mohon melunasi biaya
                                                tambahan tersebut  terlebih dahulu agar shipment dapat kembali di proses.
                                            </li>
                                        </ol>
                                    @endif
                                </div>
                                <div class="bg-white d-flex justify-content-end px-5 py-10">
                                    <div class="d-flex flex-column gap-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" />
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Ya, saya mengerti
                                            </label>
                                        </div>
                                        <span>(Centang untuk melanjutkan)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                            <div class="d-flex flex-column bg-white mb-0">
                                <div class="bg-warning text-white p-5">
                                    <span class="fs-3">Detail Alamat</span>
                                </div>
                                <div class="bg-secondary p-5">
                                    <h5 class="fs-3">Data Pengirim</h5>
                                    @php
                                        $last_data = $last_book->first();
                                    @endphp
                                </div>
                                <div class="bg-white p-5 mb-0">
                                    <div class="row">
                                        <div class="col-md-6 sender">
                                            <div class="d-flex flex-column gap-3">
                                                <input class="form-control" type="hidden" name="sender_as" value="">
                                                <div class="form-check form-check-custom">
                                                    <input class="form-check-input" checked name="sender_as" required onclick="typeCheck()" type="radio" value="Personal" id="sender-as-personal"/>
                                                    <label class="form-check-label" for="sender-as-personal">
                                                        Personal
                                                    </label>
                                                </div>
                                                @if ($vendor->disabled_company == 0)
                                                    <div class="form-check form-check-custom">
                                                        <input class="form-check-input" name="sender_as" required onclick="typeCheck()" type="radio" value="Perusahaan" id="sender-as-perusahaan"/>
                                                        <label class="form-check-label" for="sender-as-perusahaan">
                                                            Perusahaan
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="fv-row text"><label class="required col-form-label" for="sender-name">Nama
                                                    Pengirim</label><input class="form-control" type="text"
                                                    name="sender_name" autofocus="autofocus" autocomplete="off"
                                                    required="required" maxlength="200" id="sender-name"
                                                    value="{{ Auth::user()->name }}"></div>
                                            <div data-sender="Personal" class="fv-row text"><label class="required col-form-label" for="sender-nik">NIK / No. Passport
                                                    / NPWP</label><input class="form-control" type="text" name="sender_nik"
                                                    onchange="insuranceFumigasiCheck();" onkeyup="insuranceFumigasiCheck();"
                                                    required="required" id="sender-nik" value="{{ Auth::user()->about }}"></div>
                                            <div data-sender="Perusahaan" class="fv-row text d-none"><label
                                                    for="sender-npwp" class="required">NPWP</label><input class="form-control" type="text"
                                                    name="sender_npwp" onchange="insuranceFumigasiCheck();"
                                                    onkeyup="insuranceFumigasiCheck();" pattern="[0-9]{15}"
                                                    title="NPWP hanya boleh 15 angka." maxlength="100" id="sender-npwp" value="{{ Auth::user()->about }}">
                                            </div>
                                            <div data-sender="Perusahaan" class="fv-row text d-none"><label
                                                for="sender-company-name">Nama Perusahaan</label><input class="form-control" type="text"
                                                name="sender_company_name" value="{{$last_data->sender_company_name ?? ""}}" onchange="insuranceFumigasiCheck();"
                                                onkeyup="insuranceFumigasiCheck();" pattern="[0-9]{15}"
                                                title="Nama Perusahaan" maxlength="100" id="sender-company-name" value="">
                                            </div>

                                            <div class="fv-row textarea"><label class="required col-form-label" for="sender-address">Alamat
                                                    Lengkap</label><textarea name="sender_address" placeholder="Alamat
Kota/Kabupaten
Kecamatan
Provinsi
Kode POS" required="required" id="sender-address" rows="5"
                                                    class="form-control">{{ Auth::user()->address }}</textarea>
                                            </div>
                                            <div class="fv-row">
                                                <label class="required col-form-label required">Kota/Kabupaten</label>
                                                <input type="text" name="sender_city" class="form-control" value="{{$last_data->sender_city ?? ''}}">
                                            </div>
                                            <div class="fv-row">
                                                <label class="required col-form-label required">Provinsi</label>
                                                <input type="text" name="sender_province" class="form-control" value="{{$last_data->sender_province ?? ''}}">
                                            </div>
                                            <div class="fv-row">
                                                <label class="required col-form-label">Kode POS</label>
                                                <input type="text" name="sender_zip" class="form-control" value="{{$last_data->sender_zip ?? ""}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="fv-row text"><label
                                                    for="sender-phone" class="required">Handphone</label><input class="form-control"
                                                    type="text" name="sender_phone" required="required" maxlength="200"
                                                    id="sender-phone" value="{{ Auth::user()->phone }}"></div>
                                            <div class="fv-row text"><label for="sender-telephone">Telp. Rumah/Kantor
                                                    (Optional)</label><input class="form-control" type="text"
                                                    name="sender_telephone" maxlength="200" id="sender-telephone" value="">
                                            </div>
                                            <div class="fv-row email"><label
                                                    for="sender-email" class="required">Email</label><input class="form-control" type="email"
                                                    name="sender_email" required="required" maxlength="200"
                                                    id="sender-email" value="{{ Auth::user()->email }}"></div>
                                            <div id="peb-declare" class="d-none">
                                                <input type="hidden" name="peb" value="0">
                                                <div class="fv-row">
                                                    <label class="required">File NPWP Perusahaan</label>
                                                    <input type="file" name="npwp_file" class="form-control">
                                                </div>
                                                <div class="fv-row">
                                                    <label class="required">NIB Perusahaan</label>
                                                    <input type="text" name="nib" class="form-control">
                                                </div>
                                                <div class="fv-row">
                                                    <label class="required">File NIB Perusahaan</label>
                                                    <input type="file" name="nib_file" class="form-control">
                                                </div>
                                                <div class="fv-row">
                                                    <label>Commercial Invoice</label>
                                                    <input type="file" name="peb_invoice" class="form-control">
                                                </div>
                                                <div class="fv-row">
                                                    <label>Packing List</label>
                                                    <input type="file" name="peb_packing_list" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="bg-secondary p-5">
                                    <h5 class="box-title">Data Penerima</h5>
                                </div>
                                <div class="p-5 mb-0">
                                    <div class="row">
                                        <div class="col-md-6 recipient">
                                            <div class="d-flex flex-column gap-3">
                                                <input class="form-control" type="hidden" name="recipient_as" value="">
                                                <div class="form-check form-check-custom">
                                                    <input class="form-check-input" onclick="typeCheckRecipient()" checked name="recipient_as" required type="radio" value="Personal" id="recipient-as-personal"/>
                                                    <label class="form-check-label" for="recipient-as-personal">
                                                        Personal
                                                    </label>
                                                </div>
                                                @if ($vendor->disabled_company == 0)
                                                    <div class="form-check form-check-custom">
                                                        <input class="form-check-input" onclick="typeCheckRecipient()" name="recipient_as" required type="radio" value="Perusahaan" id="recipient-as-perusahaan"/>
                                                        <label class="form-check-label" for="recipient-as-perusahaan">
                                                            Perusahaan
                                                        </label>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="fv-row text"><label class="required col-form-label" for="recipient-name">Nama
                                                    Penerima</label><input class="form-control" type="text"
                                                    name="recipient_name" autocomplete="off" required="required"
                                                    maxlength="200" id="recipient-name"></div>
                                            <div class="recipient_company d-none">
                                                <div class="fv-row text"><label
                                                    for="recipient-company-name">Nama Perusahaan</label><input class="form-control" type="text"
                                                    name="recipient_company_name" onchange="insuranceFumigasiCheck();"
                                                    onkeyup="insuranceFumigasiCheck();" pattern="[0-9]{15}"
                                                    title="Nama Perusahaan" maxlength="100" id="recipient-company-name" value="">
                                                </div>
                                            </div>
                                            <div class="fv-row textarea"><label class="required col-form-label" for="recipient-address">Alamat
                                                    Lengkap</label><textarea name="recipient_address" required="required"
                                                    id="recipient-address" rows="5" class="form-control"></textarea></div>
                                            <div class="fv-row text"><label class="required col-form-label" for="country">Negara</label><input
                                                    class="form-control" type="text" name="country" readonly="readonly"
                                                    id="country" value="{{ $tujuan->nama }}"></div>
                                            <div class="fv-row">
                                                <label class="required col-form-label required">Kota</label>
                                                <input type="text" name="recipient_city" class="form-control">
                                            </div>
                                            <div class="fv-row">
                                                <label class="required col-form-label">Provinsi/State</label>
                                                <input type="text" name="recipient_province" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            @if ($vendor->postcode_check == 1)
                                            <p class="text-muted">Silakan cek kode pos. Jika termasuk wilayah pelosok/susah
                                                dijangkau (remote area), akan ada biaya tambahan. Berikut daftar remote
                                                area: <a href="#">{{ $vendor->nama }}</a>.</p>
                                            @endif
                                            <div class="fv-row text">
                                                <label for="zip" class="required">Kode POS/Suburban</label>
                                                <input class="form-control" type="text" required name="zip" oninput="remotearea(1)" maxlength="10" id="zip">
                                            </div>
                                            {{-- <div class="form-check my-5">
                                                <input class="form-check-input" onchange="remoteareaforce()" name="asremotearea" type="checkbox" value="1" id="asremotearea" />
                                                <label class="form-check-label" for="asremotearea">
                                                    Kode POS termasuk dalam Remote Area. (Abaikan jika tidak)
                                                </label>
                                            </div> --}}
                                            {{-- @endif --}}
                                            <div class="fv-row text"><label
                                                    for="recipient-phone" class="required">Telepon/Handphone</label>
                                                <div class="input-group"><span class="badge border input-group-addon">+ {{ $tujuan->phonecode }}</span><input
                                                        class="form-control" type="text" name="recipient_phone"
                                                        required="required" maxlength="200" id="recipient-phone"></div>
                                            </div>
                                            <div class="fv-row"><label for="recipient-email" class="required">Email</label><input
                                                    class="form-control" type="email" name="recipient_email" maxlength="200"
                                                    id="recipient-email"></div>
                                            <div class="recipient_passport_detail">
                                                <hr>
                                                <div class="fv-row text"><label for="recipient-passport">ARC/Passport
                                                        No. (Optional)</label><input class="form-control" type="text"
                                                        name="recipient_passport" maxlength="20" id="recipient-passport">
                                                </div>
                                                <div class="fv-row text"><label for="recipient-clearance">Personal
                                                        Clearance Code (Optional)</label><input class="form-control"
                                                        type="text" name="recipient_clearance" maxlength="30"
                                                        id="recipient-clearance"></div>
                                                <div class="fv-row text"><label for="recipient-taxid">TAX ID/EORI
                                                        (Economic Operator Registration and Identification)
                                                        (Optional)</label><input class="form-control" type="text"
                                                        name="recipient_taxid" maxlength="30" id="recipient-taxid"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-secondary p-5">
                                    <h5 class="box-title">Delivery Duty Paid (Pajak dibayarkan oleh Penerima)</h5>
                                </div>
                                <div class="p-5 mb-0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex flex-column gap-3">
                                                <input class="form-control" type="hidden" name="recipient_as" value="">
                                                <div class="form-check form-check-custom">
                                                    <input class="form-check-input" onclick="ddp_check(this)" name="ddp" required type="checkbox" value="1" id="ddp"/>
                                                    <label class="form-check-label" for="ddp">
                                                        Delivery Duty dibayarkan Pengirim?
                                                    </label>
                                                </div>
                                                <span class="text-muted">*Biaya Additional Delivery Duty nya akan menyusul di invoice selanjutnya</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                            <div class="d-flex flex-column bg-white">
                                <div class="p-5 bg-warning text-white">
                                    <span class="fs-3">Detail Kiriman</span>
                                </div>
                                <div class="p-5 d-flex flex-column gap-5">
                                    <div class="bg-info p-5 text-white">
                                        <p>Perhatian mohon untuk mengisi data isi kiriman dengan benar, ketidaksesuaian isi kiriman dengan data yang diisi bisa mengakibatkan pengiriman gagal / disita bea cukai dan biaya pengiriman hangus</p>
                                    </div>
                                    @if ($produk->tipe_kategori == 'w')
                                        <div class="fv-row col-4">
                                            <label class="col-form-label required">Deskripsi Dokumen</label>
                                            <input type="text" name="isi_kiriman" class="form-control" placeholder="Dokumen yang anda kirimkan. Contoh : Aggreement, Annual Reports, Prescriptions, Invoice, ETC" id="">
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="fv-row col-4">
                                                <label class="col-form-label required">Alasan Ekspor</label>
                                                <select name="alasan_ekspor" onchange="peb_check()" class="form-select" data-control="select2" data-placeholder="- Pilih -" id="">
                                                    <option value=""></option>
                                                    <option value="Commercial">Commercial</option>
                                                    <option value="Personal Use">Personal Use</option>
                                                    <option value="Gift">Gift</option>
                                                    <option value="Sample">Sample</option>
                                                    <option value="Moving - Personal Effect">Moving - Personal Effect</option>
                                                    <option value="Repair & Return" disabled>Repair & Return</option>
                                                </select>
                                            </div>
                                            <div class="fv-row col-4">
                                                <label class="col-form-label required">Isi Kiriman Secara Umum</label>
                                                <input type="text" name="isi_kiriman" class="form-control" placeholder="Dalam bahasa Inggris" id="">
                                            </div>
                                            <div class="fv-row col-4">
                                                <label class="col-form-label required">Kurs Mata Uang</label>
                                                <select name="currency" class="form-select" data-control="select2">
                                                    @foreach (config('constants.currency') ?? [] as $key => $curr)
                                                        <option value="{{ $key }}" {{ $key == "IDR" ? "SELECTED" : "" }}>{{ "[$key] - $curr" }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div id="form_repeat" data-form="repeater">
                                            <!--begin::Form group-->
                                            <div class="form-group">
                                                <div data-repeater-list="barang" class="d-flex flex-column gap-5">
                                                    @for ($i = 0; $i < $totalPaket; $i++)
                                                        <div data-repeater-item>
                                                            <div class="form-group row">
                                                                <div class="col-md-5">
                                                                    <label class="col-form-label required">Nama Barang</label>
                                                                    <input type="text" onchange="checkTax()" onkeyup="checkTax()" data-tax='name' name="nama" class="form-control" placeholder="Dalam bahasa Inggris. Isilah detail merk, seri, warna selengkap-lengkapnya" id="">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="col-form-label required">Jumlah (pcs)</label>
                                                                    <input type="number" onchange="checkTax()" onkeyup="checkTax()" data-tax='qty' name="jumlah" class="form-control" id="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="col-form-label required">Harga Satuan (<span data-label="curr-label">IDR</span>)</label>
                                                                    <input type="number" onchange="checkTax()" onkeyup="checkTax()" data-tax='price' name="harga" class="form-control" id="">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="col-form-label w-100">&nbsp;</label>
                                                                    <a href="javascript:;" data-repeater-delete class="btn btn-light-danger btn-icon">
                                                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>

                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                            <!--end::Form group-->

                                            <!--begin::Form group-->
                                            <div class="form-group mt-5 d-flex justify-content-end gap-5">
                                                <a href="javascript:;" data-repeater-create class="btn btn-success">
                                                    <i class="fa fa-plus"></i>
                                                    Tambah Barang
                                                </a>
                                            </div>
                                            <!--end::Form group-->
                                        </div>
                                        <span>Pengiriman ini kemungkinan akan dikenakan pajak dan bea cukai di negara tujuan: {{ $tujuan->nama }}</span>
                                    @endif
                                    {{-- <div class="form-check d-none" id="tax_form">
                                        <input class="form-check-input" type="checkbox" value="1" id="tax_check" />
                                        <label class="form-check-label required" for="tax_check">
                                            . Lanjutkan? (centang)
                                        </label>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                            <div class="bg-white d-flex flex-column">
                                <div class="p-5 bg-warning text-white">
                                    <span class="fs-3">Tambahan</span>
                                </div>
                                <div class="p-5 bg-secondary">
                                    <span class="fs-3">Asuransi</span>
                                </div>
                                <div class="p-5">
                                    <div class="d-flex justify-content-between">
                                        <input class="form-control" type="hidden" name="asuransi" id="asuransi" value="0">
                                        <div class="form-check w-50">                                            
                                             <label class="form-check-label fw-bold text-dark" for="asuransi_check">
                                                <input class="form-check-input" checked type="checkbox" value="1" id="with_asuransi" />
                                                Dengan Asuransi?
                                            </label>
                                            <div class="d-flex flex-column gap-3 mt-3">
                                                <span>Asuransi akan dikirimkan pada tagihan tambahan.</span>
                                                <span>Jika nilai asuransi tidak sesuai dengan yang diharapkan, maka asuransi dapat tidak dipakai.</span>
                                                {{-- <span data-asuransi="label">IDR {{ number_format($sc['insurance'], 0, ",", ".") }}</span> --}}
                                                <span>Cover Up : Mengikuti Harga Barang</span>
                                            </div>
                                        </div>
                                        <div class="w-50 d-flex flex-column gap-5">
                                            @if (isset($dataPage['tambahan']))
                                                {!! $dataPage['tambahan']->content !!}
                                            @else
                                                <p>Fitur asuransi akan mengcover sesuai dengan Nilai Barang yang disebutkan, dan mengacu pada ketentuan dari {{ $vendor->nama }}.</p>
                                                <p>Untuk barang hilang atau rusak, namun tidak termasuk untuk barang pecah belah, barang antik, dan kondisi force majeur seperti bencana alam, perang dan ataupun serangan teroris yang terjadi di negara tujuan.</p>
                                                <p>Proses klaim akan dibantu oleh {{ \Config::get("constants.APP_LABEL") }} ke Kurir terkait jika terjadi hal tersebut di atas.</p>
                                                <p>Jika Anda memilih untuk tidak diasuransikan dan paket dinyatakan hilang, maka {{ \Config::get("constants.APP_LABEL") }} akan mengcover secara pribadi sesuai nilai barang yang disebutkan, atau maksimal IDR 1,000,000 (Satu Juta Rupiah) untuk setiap pengiriman.</p>
                                                <p>Dalam hal keterlambatan/ketidak sesuaian jadwal, dan resiko bisnis lainnya yang terjadi adalah di luar wewenang kami, dan tidak bisa kami cover untuk ganti ruginya.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 bg-secondary">
                                    <span class="fs-3">Fumigasi</span>
                                </div>
                                <div class="p-5">
                                    <div class="d-flex flex-column gap-5">
                                        @if (isset($dataPage['fumigasi']))
                                                {!! $dataPage['fumigasi']->content !!}
                                        @else
                                            <span>Fumigasi adalah salahsatu metode pengendalian hama (bakteri, rayap, jamur, dan serangga) pada kayu menggunakan gas fumigan.</span>
                                        @endif
                                        <div class="d-flex align-items-center gap-5">
                                            <label class="col-form-label">Jumlah paket yang menggunakan pallet kayu?</label>
                                            <div>
                                                <select name="jumlah_fumigasi" onchange="fumigasiCheck()" class="form-select" data-control="select2" id="">
                                                    <option value="0">Tidak Ada</option>
                                                    @for($i = 1; $i <= collect($content ?? [])->sum("total_paket"); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <input class="form-control" type="hidden" name="fumigasi" id="fumigasi" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 bg-secondary">
                                    <span class="fs-3">Non Stacakble Unit</span>
                                </div>
                                <div class="p-5">
                                    <div class="d-flex flex-column gap-5">
                                        @if (isset($dataPage['non_stackable']))
                                                {!! $dataPage['non_stackable']->content !!}
                                        @else
                                        <span>Non Stackable Unit adalah paket atau barang yang tidak dapat ditumpuk dengan paket lain di atasnya selama proses pengiriman. Biasanya karena bentuk, berat, atau sifat barang yang dapat rusak jika ditumpuk, sehingga memerlukan penanganan khusus dan dapat dikenakan biaya tambahan.</span>
                                        @endif
                                        <div class="d-flex align-items-center gap-5">
                                            <label class="col-form-label">Jumlah paket yang tidak boleh ditumpuk?</label>
                                            <div>
                                                <select name="jumlah_nsu" onchange="nsuCheck()" class="form-select" data-control="select2" id="">
                                                    <option value="0">Tidak Ada</option>
                                                    @for($i = 1; $i <= collect($content ?? [])->sum("total_paket"); $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <input class="form-control" type="hidden" name="nsu" id="nsu" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 bg-secondary">
                                    <span class="fs-3">Kode Voucher</span>
                                    <div id="promo-div" class="d-flex flex-column gap-2"></div>
                                </div>
                                <div class="p-5">
                                    <div class="input-group">
                                        <input class="form-control" type="hidden" name="promo_amount" id="promo" value="0">
                                        <input class="form-control" type="text" name="promo_code" id="promocode">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="button" id="btn-promo-check" onclick="promoCheck()">Gunakan</button>
                                            <button class="btn btn-danger d-none btn-icon" id="btn-promo-remove" type="button" onclick="removePromo()"><i class="fa fa-times"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Include optional progressbar HTML -->
                    <div class="progress d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                    @php
                        $total_berat = 0;
                        $_bp = $res['pr'];
                        // dd($res, $surcharge);
                        if(count($res['additional_prices'] ?? [])){
                            $_bp += collect($res['additional_prices'])->sum("value");
                        }
                        foreach ($content as $item){
                            $total_berat += $item['multiplier'] * $item['total_paket'];
                        }
                        if($surcharge->surcharge_type == 0){
                            $fuel = $_bp * $sc['fuel_charge'];
                        } else {
                            $fuel = $_bp + $sc['fuel_charge'];
                        }
                        $tb = $res['pr'] +  $sc['oversize'] + $sc['overweight'] + $fuel + $sc['ncp'];
                        foreach ($res['additional_prices'] ?? [] as $key => $ap) {
                            $tb += $ap['value'];
                        }
                        $vat = $tb * 0.011;
                        $tbAfterVat = $tb + $vat;
                    @endphp
                    <input type="hidden" name="weight_total" value="{{ $total_berat }}">
                    {{-- <input type="hidden" name="promo_id"> --}}
                    <input type="hidden" name="biaya_kirim" id="biaya_kirim" value="{{ $res['pr'] }}">
                    <input type="hidden" name="total_biaya" id="total_biaya" value="{{ $tbAfterVat }}">
                    <input type="hidden" name="overweight" id="overweight" value="{{ $sc['overweight'] }}">
                    <input type="hidden" name="oversize" id="oversize" value="{{ $sc['oversize'] }}">
                    <input type="hidden" name="ncp" id="ncp" value="{{ $sc['ncp'] }}">
                    <input type="hidden" name="delivery_duty" id="delivery_duty" data-amount="{{ $sc['delivery_duty'] }}" value="0">
                    <input type="hidden" name="export_declare" id="export_declare" data-amount="{{ $sc['export_declare'] }}" value="0">
                    <input type="hidden" name="fuel_surcharge" id="fuel_surcharge" value="{{ $fuel }}">
                    <input type="hidden" name="vat" id="vat" value="{{ $vat }}">
                    <input type="hidden" name="pickup_amount" id="pickup_amount" data-amount="50000" value="50000">
                    @foreach ($res['additional_prices'] ?? [] as $ap)
                        <input type="hidden" name="{{ $ap['key'] }}" data-ap value="{{ $ap['value'] }}">
                    @endforeach
                </div>
            </form>
        </div>
        <div class="col-5">
            <div class="d-flex flex-column bg-white">
                <div class="bg-info p-5 text-white">
                    <span class="fs-3">Detail</span>
                </div>
                <div class="p-5">
                    <div class="d-flex flex-column gap-5">
                        <div class="d-flex flex-column">
                            <label>
                                <span class="fw-bold">Kurir: </span>
                                <span>{{ $vendor->nama }}</span>
                            </label>
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Negara Tujuan: </span>
                                <div class="d-flex gap-3 align-items-center">
                                    <span>{{ $dari->nama }}</span>
                                    <i class="fa fa-plane text-dark"></i>
                                    <span>{{ $tujuan->nama }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <label>
                                <span class="fw-bold">Jenis Barang: </span>
                                <span>{{ $produk->nama }}</span>
                            </label>
                            <div class="d-flex align-items-center gap-1">
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
                        <div class="d-flex flex-column">
                            @php
                                $total_berat = 0;
                                $berat_terhitung = 0;
                                // dd($res)
                            @endphp
                            @foreach ($content as $item)
                                @php
                                    $total_berat += $item['berat'] * $item['total_paket'];
                                    $vm = $item['berat'] * $item['total_paket'] >= $item['volumetric'] ? $item['berat'] * $item['total_paket'] : $item['volumetric'];
                                    $berat_terhitung += $vm;
                                @endphp
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Jumlah Paket: </span>
                                    <span>{{ $item['total_paket'] }}</span>
                                    <span class="fw-bold">Berat: </span>
                                    <span class="{{ $item['berat'] * $item['total_paket'] >= $item['volumetric'] ? "text-primary fw-bold" : "" }}">{{ $item['berat'] * $item['total_paket'] }} kg</span>
                                    <span class="fw-bold">Dimensi (cm): </span>
                                    <span>{{ $item['panjang'] }} x {{ $item['lebar'] }} x {{ $item['tinggi'] }}</span>
                                    <span class="fw-bold">Volumetric (kg): </span>
                                    <span class="{{ $item['berat'] * $item['total_paket'] < $item['volumetric'] ? "text-primary fw-bold" : "" }}">{{ roundUpToNearestHalf($item['volumetric']) }}</span>
                                </div>
                                @if (!empty($item['surcharge']))
                                    <div class="ms-5">- {{ $item['surcharge']['label'] }} : {{ number_format($item['surcharge']['price'], 0, ',', '.') }}</div>
                                @endif
                            @endforeach
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Total Berat: </span>
                                <span>{{ $total_berat }} kg</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Berat Terhitung: </span>
                                <span>{{ $berat_terhitung }} kg</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Biaya kirim: </span>
                                <span>IDR {{ number_format($res['pr'], 0, ",", ".") }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1 d-none">
                                <span class="fw-bold">Promo: </span>
                                <span id="promo-label">IDR {{ number_format(0, 0, ",", ".") }}</span>
                            </div>
                            @foreach ($res['additional_prices'] ?? [] as $ap)
                                <div class="d-flex align-items-center gap-1" id="fuel-show">
                                    <span class="fw-bold">{{ $ap['label'] }}: </span>
                                    <span>IDR {{ number_format($ap['value'], 0, ",", ".") }}</span>
                                </div>
                            @endforeach
                            <div class="d-flex align-items-center gap-1" id="fuel-show">
                                <span class="fw-bold">Fuel Surcharge: </span>
                                <span>IDR {{ number_format($fuel, 0, ",", ".") }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Delivery Duty Paid: </span>
                                <span id="ddp-label">IDR {{ number_format(0, 0, ",", ".") }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Export Declaration: </span>
                                <span id="peb-label">IDR {{ number_format(0, 0, ",", ".") }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1 d-none" id="asuransi-show">
                                <span class="fw-bold">Asuransi: </span>
                                <span data-asuransi="label">IDR {{ number_format(0, 0, ",", ".") }}</span>
                            </div>
                            @if ($sc['overweight'] > 0)
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Total Overweight: </span>
                                    <span>IDR {{ number_format($sc['overweight'], 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @if ($sc['oversize'] > 0)
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Total Oversize: </span>
                                    <span>IDR {{ number_format($sc['oversize'], 0, ",", ".") }}</span>
                                </div>
                            @endif
                            @if ($sc['ncp'] > 0)
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Total NCP: </span>
                                    <span>IDR {{ number_format($sc['ncp'], 0, ",", ".") }}</span>
                                </div>
                            @endif
                            <div class="d-flex align-items-center gap-1 d-none" id="fumigasi-show">
                                <span class="fw-bold">Fumigasi: </span>
                                <label>IDR <span id="fumigasi-label">{{ number_format(0, 0, ",", ".") }}</span></label>
                            </div>
                            <div class="d-flex align-items-center gap-1 d-none" id="nsu-show">
                                <span class="fw-bold">Non Stackable Unit: </span>
                                <label>IDR <span id="nsu-label">{{ number_format(0, 0, ",", ".") }}</span></label>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="fw-bold">Biaya Pickup: </span>
                                <span id="pickupamount-label">IDR {{ number_format(50000, 0, ",", ".") }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1 mt-2">
                                <span class="fw-bold fs-3">Estimasi Total Biaya Kirim: </span>
                                <span class="fw-bold fs-3" id="total-biaya">IDR {{ number_format($tb, 0, ",", ".") }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1 mt-2">
                                <span class="fw-bold fs-3">VAT : </span>
                                <span class="fw-bold fs-3" id="vat-label">IDR {{ number_format($vat, 0, ",", ".") }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-1 mt-2">
                                <span class="fw-bold fs-3">Estimasi Total Pembayaran: </span>
                                <span class="fw-bold fs-3" id="total-biaya-akhir">IDR {{ number_format($tbAfterVat, 0, ",", ".") }}</span>
                            </div>
                        </div>
                        <div class="card rounded-0 bg-success shadow-none card-p-0 px-5">
                            <div class="card-header">
                                <h3 class="card-title text-white d-flex align-items-center gap-2">
                                    <i class="fa fa-truck fs-3 text-white"></i>
                                    Layanan Pickup
                                </h3>
                            </div>
                            <div class="card-body py-5">
                                <div class="d-flex flex-column gap-5">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" onchange="pickup_check()" checked type="radio" form="form-book" name="pickup" value="1" id="radio_pickup"/>
                                        <label class="form-check-label text-white" for="radio_pickup">
                                            Pickup
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" onchange="pickup_check()" type="radio" form="form-book" name="pickup" value="2" id="radio_antar"/>
                                        <label class="form-check-label text-white" for="radio_antar">
                                            Diantar ke {{ env("APP_NAME") }}
                                        </label>
                                    </div>
                                </div>
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
<link rel="stylesheet" href="{{asset("theme/jquery-ui/jquery-ui.min.css")}}">
<script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
<script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript">
// <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script src="{{asset("theme/jquery-ui/jquery-ui.min.js")}}"></script>
</script>
<script>

    function removePromo(el){
        var par = $(el).parents("div.promo-div").eq(0)
        $(par).remove()
        renderPromo()
    }

    function renderPromo(){
        var promos = $("input[data-promo]")
        $("#promo-label").parent().addClass("d-none")
        $("#promo-label").text("("+$.number(0, 0, ",", ".")+")")
        $("#promo").val(0)
        var totalPromo = 0
        $(promos).each(function(){
            var am = $(this).data("amount")
            totalPromo += am * 1
        })
        if(totalPromo > 0){
            $("#promo-label").parent().removeClass("d-none")
            $("#promo-label").text("("+$.number(totalPromo, 0, ",", ".")+")")
            $("#promo").val(totalPromo)
        }

        var h = $("#step-4").height() + 100
        $("#tab-c").css("height", h)

        biayaKirim()
    }

    function promoCheck(){

        $("#promo-label").parent().addClass("d-none")
        var exist = []
        $("input[name='promo_id[]']").each(function(){
            exist.push($(this).val())
        })

        $.ajax({
            url : "{{ route('booking.get_promo') }}",
            type : "POST",
            dataType : "json",
            data : {
                _token : "{{ csrf_token() }}",
                promo : $("#promocode").val(),
                biaya_kirim : $("#biaya_kirim").val(),
                pickup_amount : $("#pickup_amount").val(),
                exist : exist
            }
        }).then(function(resp){
            $("#promo").val(resp.promo)
            if(resp.success){
                var html = `<div class='promo-div'><input type="hidden" name="promo_id[]" data-promo data-amount="${resp.promo}" value="${resp.promo_id}">`
                    html += `<div class="bg-info d-flex justify-content-between p-3 rounded text-white align-items-center">`
                        html += `<span>${resp.label}</span>`
                        html += `<div><button class="btn btn-circle btn-icon btn-danger btn-sm" type="button" onclick="removePromo(this)"><i class="fa fa-times" /></button></div>`
                    html += `</div>`

                $("#promocode").val('')
                $("#promo-div").append(html)
            } else {
                Swal.fire("", resp.message, "warning")
            }
            renderPromo()
        })
    }

    function pickup_check(){
        var checked = document.querySelector("input[name=pickup]:checked")
        var pamount = 0
        var pdata = $("#pickup_amount")

        if(checked.value == 1){
            pamount = $(pdata).data('amount')
            $("#pickupamount-label").closest("div.d-flex").removeClass("d-none")
        } else {
            $("#pickupamount-label").closest("div.d-flex").addClass("d-none")
        }

        $(pdata).val(pamount)
        $("#pickupamount-label").text("IDR " + $.number(pamount, 0, ",", "."))
        biayaKirim()

    }

    function ddp_check(el){
        var ddp = 0
        if(el.checked){
            ddp = $("#delivery_duty").data("amount")
        }

        $("#delivery_duty").val(ddp)
        $("#ddp-label").text("IDR " + $.number(ddp, 0, ",", "."))
        var h = $("#step-2").height() + 100
        $("#tab-c").css("height", h)

        biayaKirim()
    }

    function peb_check(){
        var peb = $("input[name=peb]").val()

        var peb_amount = 0;
        if(peb == 1){
            peb_amount = $("#export_declare").data("amount")
            console.log($("select[name=alasan_ekspor]").val())
            console.log($("input[name=sender_as]:checked").val())
            if($("select[name=alasan_ekspor]").val() == "Repair & Return" && $("input[name=sender_as]:checked").val() == "Perusahaan"){
                peb_amount = 650000
            }
        }

        $("#export_declare").val(peb_amount)
        $("#peb-label").text("IDR " + $.number(peb_amount, 0, ",", "."))
        var h = $("#step-2").height() + 100
        $("#tab-c").css("height", h)

        biayaKirim()
    }

    function typeCheckRecipient(){
        var recipient = document.querySelector("input[name=recipient_as]:checked").value
        if (recipient == "Personal") {
            $(".recipient_passport_detail").removeClass("d-none")
            $(".recipient_company").addClass("d-none")
        } else {
            $(".recipient_passport_detail").addClass("d-none")
            $(".recipient_company").removeClass("d-none")
        }
    }

    function typeCheck(){
        $("[data-sender]").addClass("d-none")
        var sender = document.querySelector("input[name=sender_as]:checked").value
        if(sender == "Personal"){
            $("#peb-declare").addClass("d-none")
            $("#peb-declare input").prop("required", false)
            $("#peb-declare label").removeClass("required")
            $("input[name=peb]").val(0)
            $("select[name=alasan_ekspor] option[value='Repair & Return']").prop("disabled", true)
            peb_check()
        } else {
            var tb = {{ $total_berat }}
            if(tb < 30){
                Swal.fire({
                    title: "Export Declaration",
                    text: "Apakah Anda akan menggunakan Export Declaration?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $("#peb-declare").removeClass("d-none")
                            // $("#peb-declare input").prop("required", true)
                            // $("#peb-declare label").addClass("required")
                            $("input[name=peb]").val(1)
                            peb_check()
                            $("[data-sender='"+sender+"']").removeClass("d-none")
                            $("select[name=alasan_ekspor] option[value='Repair & Return']").prop("disabled", false)
                        }
                    });
            } else {
                $("#peb-declare").removeClass("d-none")
                // $("#peb-declare input").prop("required", true)
                // $("#peb-declare input").each(function(){
                //     var pr = $(this).parents("div.fv-row").eq(0)
                //     $(pr).find("label").addClass("required")
                // })
                $("input[name=peb]").val(1)
                peb_check()
                $("[data-sender='"+sender+"']").removeClass("d-none")
                $("select[name=alasan_ekspor] option[value='Repair & Return']").prop("disabled", false)
            }
        }

        var h = $("#step-2").height() + 100
        $("#tab-c").css("height", h)
    }

    function biayaKirim(){
        var asuransi = $("input[name=asuransi]").val() * 1
        var fumigasi = $("input[name=fumigasi]").val() * 1
        var nsu = $("input[name=nsu]").val() * 1
        var delivery_duty = $("input[name=delivery_duty]").val() * 1
        var export_declare = $("input[name=export_declare]").val() * 1
        var overweight = $("input[name=overweight]").val() * 1
        var oversize = $("input[name=oversize]").val() * 1
        var ncp = $("input[name=ncp]").val() * 1
        var biaya_kirim = $("input[name=biaya_kirim]").val() * 1
        var fuel_surcharge = $("input[name=fuel_surcharge]").val() * 1
        var pickup_amount = $("input[name=pickup_amount]").val() * 1
        var promo = $("#promo").val() * -1
        var ap = 0
        $("input[data-ap]").each(function(){
            ap += $(this).val() * 1
        })

        var total = biaya_kirim + asuransi + fumigasi + nsu + delivery_duty + export_declare + overweight + oversize + fuel_surcharge + ap + promo + ncp + pickup_amount

        var vat = total * 0.011
        $("input[name=vat]").val(vat)

        var total_biaya_akhir = total + vat
        $("input[name=total_biaya_akhir]").val(total_biaya_akhir)

        $("input[name=total_biaya]").val(total)
        var lbl = "IDR " + $.number(total, 0, ",", ".")
        $("#total-biaya").html(lbl)
        $("#total-biaya-akhir").html("IDR " + $.number(total_biaya_akhir, 0, ",", "."))
        $("#vat-label").html("IDR " + $.number(vat, 0, ",", "."))
    }

    function asuransiCheck(){
        var checked = $("#asuransi_check").prop("checked")
        var nilai_barang = 0
        $("input[data-tax=qty]").each(function(){
            var qty = $(this).val() * 1
            var fg = $(this).closest('div.form-group')
            var price = $(fg).find("input[data-tax=price]").val() * 1
            var pr = qty * price
            nilai_barang += pr
        })
        var pct = (2/100) * nilai_barang
        var base = "{{ $sc['insurance'] }}" * 1
        var insurance = pct < base ? base : pct
        console.log(insurance)
        insurance = insurance.toFixed(2)
        $("span[data-asuransi]").text("IDR " + $.number(insurance, 0, ",", "."))
        if(checked){
            $("#asuransi").val(insurance)
            $("#asuransi-show").removeClass("d-none")
        } else {
            $("#asuransi").val(0)
            $("#asuransi-show").addClass("d-none")
        }

        biayaKirim()
    }

    function fumigasiCheck(){
        var fum = $("select[name=jumlah_fumigasi]").val()
        if(fum > 0){
            var base = {{ $dataPage['fumigasi']->fumigasi_base_price ?? 50000 }}
            var add = {{ $dataPage['fumigasi']->fumigasi_additional_price ?? 25000 }} * fum
            $("#fumigasi").val(base + add)
            $("#fumigasi-label").html($.number(base + add, 0, ",", "."))
            $("#fumigasi-show").removeClass("d-none")
        } else {
            $("#fumigasi").val(0)
            $("#fumigasi-label").html($.number(0, 0, ",", "."))
            $("#fumigasi-show").addClass("d-none")
        }

        biayaKirim()
    }

    function nsuCheck(){
        var fum = $("select[name=jumlah_nsu]").val()
        if(fum > 0){
            var base = {{ $surcharge->non_stackable_price ?? 0 }} * fum
            $("#nsu").val(base)
            $("#nsu-label").html($.number(base, 0, ",", "."))
            $("#nsu-show").removeClass("d-none")
        } else {
            $("#nsu").val(0)
            $("#nsu-label").html($.number(0, 0, ",", "."))
            $("#nsu-show").addClass("d-none")
        }

        biayaKirim()
    }

    function checkTax() {
        $("#tax_form").addClass("d-none")
        var el = []
        var input = []
        $("input[data-tax]").each(function(){
            var name = $(this).attr("name")
            el.push(name)
            if ($(this).val() != "") {
                input.push(name)
            }
        })

        if(input.length == el.length){
            $("#tax_form").removeClass("d-none")
        }

        var h = $("#step-3").height() + 100
        $("#tab-c").css("height", h)
    }

    function onFinish(){
        $("#form-book").submit()
    }

    $(document).ready(function () {

        typeCheck()

        $("#form_repeat").repeater({
            initEmpty: false,

            show: function () {
                $(this).slideDown();
                // var h = $(this).parents("div[role=tabpanel]").eq(0).height() + 255 + "px"
                // $("#tab-c").css("height", h)
                // $(this).find("input[data-input]").each(function(){
                //     var name = $(this).attr("name")
                //     name += "["+$(this).data("input")+"]"
                //     $(this).attr("name", name)
                // })
                checkTax()
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            ready: function(setIndexes){
                setTimeout(() => {

                }, 1000);
            },
            isFirstItemUndeletable: true
        });

        $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
            $("#btn-finish").hide();

            // Get step info from Smart Wizard
            let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
            var stp = stepInfo.currentStep + 1

            if(stepIndex == 0){
                $(".sw-btn-prev").hide()
            } else {
                $(".sw-btn-prev").show()
            }

            if(stepIndex < (stepInfo.totalSteps - 1)){
                $(".sw-btn-next").show()
                // $("#btn-finish").hide()
                $("#btn-finish").hide()
            } else {
                $("#btn-finish").show()
                $(".sw-btn-next").hide()
            }
        });

        $("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIndex, nextStepIndex, stepDirection) {
            if (currentStepIndex > 0 && stepDirection == "forward") {
                var tg = "#step-"+(currentStepIndex + 1)
                var lbl = $(tg).find("label.required")
                var element = []
                var val = []
                $(lbl).each(function(){
                    var parent = $(this).parent()
                    var input = $(parent).find("input, textarea")
                    if(!$(parent).hasClass("d-none")){
                        element.push($(input).attr("name"))
                        if($(input).is("[type=checkbox]")){
                            if($(input).prop('checked')){
                                val.push($(input).val())
                            }
                        } else {
                            if($(input).val() != ""){
                                val.push($(input).val())
                            }
                        }
                    }
                })

                asuransiCheck()

                if(element.length != val.length){
                    Swal.fire("", "Periksa kembali, kesalahan pengisian", "warning")
                    return false
                }
            }

            let stepInfo = $('#smartwizard').smartWizard("getStepInfo");

            // $('#smartwizard').smartWizard("loader", "show");
            // $('#smartwizard').smartWizard("loader", "hide");
        });

        $("#smartwizard").on("loaded", function(e) {
            let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
            // $(this).find("ul.nav").hide()

            $(".sw-btn-prev").hide()
            $(".sw-btn-next").hide()
        });

        $("select[name=currency]").change(function(){
            $("span[data-label=curr-label]").text($(this).val())
        })

        $('#smartwizard').smartWizard({
            theme: "round",
            autoAdjustHeight: true,
            enableUrlHash: false,
            toolbar: {
                position: 'bottom', // none|top|bottom|both
                showNextButton: true, // show/hide a Next button
                showPreviousButton: true, // show/hide a Previous button
                extraHtml: `<button type="submit" id="btn-finish" class="btn btn-primary mx-5 sw-btn-finish" onclick="onFinish()">Finish</button>` // Extra html to show on toolbar
            },
            lang: { // Language variables for button
                next: 'Selanjutnya',
                previous: 'Kembali'
            },
            style: { // CSS Class settings
                mainCss: 'sw',
                navCss: 'nav',
                navLinkCss: 'nav-link',
                contentCss: 'tab-content',
                contentPanelCss: 'tab-pane',
                themePrefixCss: 'sw-theme-',
                anchorDefaultCss: 'default',
                anchorDoneCss: 'done bg-success',
                anchorActiveCss: 'active bg-success',
                anchorDisabledCss: 'disabled',
                anchorHiddenCss: 'hidden',
                anchorErrorCss: 'error',
                anchorWarningCss: 'warning',
                justifiedCss: 'sw-justified',
                btnCss: 'mx-5',
                btnNextCss: 'btn-primary sw-btn-next',
                btnPrevCss: 'btn-outline btn-outline-primary sw-btn-prev',
                loaderCss: 'sw-loading',
                progressCss: 'progress',
                progressBarCss: 'progress-bar',
                toolbarCss: 'toolbar justify-content-end',
                toolbarPrefixCss: 'toolbar-',
            }
        });

        $("#flexCheckDefault").click(function(){
            var checked = this.checked

            $(".sw-btn-next").hide()
            if(checked){
                $(".sw-btn-next").show()
            }
        })

        $("input[name=recipient_name]").autocomplete({
            source: @JSON($last_book->whereNotNull('recipient_name')->pluck("recipient_name")->unique()->values()->toArray())
        });
        $("input[name=recipient_company_name]").autocomplete({
            source: @JSON($last_book->whereNotNull('recipient_company_name')->pluck("recipient_company_name")->unique()->values()->toArray())
        });
        $("input[name=recipient_city]").autocomplete({
            source: @JSON($last_book->whereNotNull('recipient_city')->pluck("recipient_city")->unique()->values()->toArray())
        });
        $("input[name=recipient_province]").autocomplete({
            source: @JSON($last_book->whereNotNull('recipient_province')->pluck("recipient_province")->unique()->values()->toArray())
        });
        $("input[name=recipient_phone]").autocomplete({
            source: @JSON($last_book->whereNotNull('recipient_phone')->pluck("recipient_phone")->unique()->values()->toArray())
        });
        $("input[name=recipient_email]").autocomplete({
            source: @JSON($last_book->whereNotNull('recipient_email')->pluck("recipient_email")->unique()->values()->toArray())
        });
        $("input[name=zip]").autocomplete({
            source: @JSON($last_book->whereNotNull('zip')->pluck("zip")->unique()->values()->toArray())
        });
        $("textarea[name=recipient_address]").autocomplete({
            source: @JSON($last_book->whereNotNull('recipient_address')->pluck("recipient_address")->unique()->values()->toArray())
        });


    })

</script>
@endsection
