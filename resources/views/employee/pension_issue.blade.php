<style>
    body {
    font-family: DejaVuSansCondensed;
    font-size: 11pt;
}
.head {
    font-family: DejaVuSansCondensed;
    width: 100%;
    border-bottom:1px solid;
}
.body {
    margin-top: 20px;
}
.header {
    text-align: center;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    margin-top: 20px;
}
.content {
    text-align: justify;
}
.header .title {
    font-weight: bold;
    text-decoration: underline;
}
.info td {
    font-family: DejaVuSansCondensed;
    font-size: 11pt;
}
.info_isi {
    text-align: left;
    font-weight: bold;
}
.record th {
    text-align: center;
    font-weight: bold;
    border: 1px solid black;
}
.record td {
    vertical-align: top;
    border: 1px solid black;
}
.record tr {
    border: 1px solid black;
}
.record {
    font-family: DejaVuSansCondensed;
    width: 100%;
}
table {
    border-collapse: collapse;
}
th {
    font-weight: bold;
    vertical-align: top;
    text-align: left;
    padding-left: 2mm;
    padding-right: 2mm;
    padding-top: 0.5mm;
    padding-bottom: 0.5mm;
}
td {
    padding-left: 2mm;
    vertical-align: top;
    text-align: left;
    padding-right: 2mm;
    padding-top: 0.5mm;
    padding-bottom: 0.5mm;
}
th p {
    text-align: left;
    margin: 0pt;
}
td p {
    text-align: left;
    margin: 0pt;
}
hr {
    width: 70%;
    height: 1px;
    text-align: center;
    color: #999999;
    margin-top: 8pt;
    margin-bottom: 8pt;
}
a {
    color: #000066;
    font-style: normal;
    text-decoration: underline;
    font-weight: normal;
}
ul {
    text-indent: 5mm;
    margin-bottom: 9pt;
}
ol {
    text-indent: 5mm;
    margin-bottom: 9pt;
}
pre {
    font-family: DejaVuSansMono;
    font-size: 9pt;
    margin-top: 5pt;
    margin-bottom: 5pt;
}
h1 {
    /*font-weight: normal;*/
    /*font-size: 26pt;*/
    /*color: #000066;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 18pt;*/
    /*margin-bottom: 6pt;*/
    /*border-top: 0.075cm solid #000000;*/
    /*border-bottom: 0.075cm solid #000000;*/
    /*text-align: ;*/
    page-break-after: avoid;
}
h2 {
    /*font-weight: bold;*/
    /*font-size: 12pt;*/
    /*color: #000066;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 6pt;*/
    /*margin-bottom: 6pt;*/
    /*border-top: 0.07cm solid #000000;*/
    /*border-bottom: 0.07cm solid #000000;*/
    /*text-align: ;*/
    /*text-transform: uppercase;*/
    page-break-after: avoid;
}
h3 {
    /*font-weight: normal;*/
    /*font-size: 26pt;*/
    /*color: #000000;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 0pt;*/
    /*margin-bottom: 6pt;*/
    /*border-top: 0;*/
    /*border-bottom: 0;*/
    /*text-align: ;*/
    page-break-after: avoid;
}
h4 {
    /*font-weight: ;*/
    /*font-size: 13pt;*/
    /*color: #9f2b1e;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 10pt;*/
    /*margin-bottom: 7pt;*/
    /*font-variant: small-caps;*/
    /*text-align: ;*/
    /*margin-collapse: collapse;*/
    page-break-after: avoid;
}
h5 {
    /*font-weight: bold;*/
    /*font-style: italic;*/
    /*font-size: 11pt;*/
    /*color: #000044;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 8pt;*/
    /*margin-bottom: 4pt;*/
    /*text-align: ;*/
    page-break-after: avoid;
}
h6 {
    /*font-weight: bold;*/
    /*font-size: 9.5pt;*/
    /*color: #333333;*/
    font-family: DejaVuSansCondensed;
    /*margin-top: 6pt;*/
    /*margin-bottom: ;*/
    /*text-align: ;*/
    page-break-after: avoid;
}

.page {
    page-break-before: always;
}

div.bottom-link {
  position: fixed;
  width: 50%;
  bottom: 10px;
}



</style>
@php
    function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
    $d1 = date_create($pension->act_date);
    $d2 = date_create($pension->sev_date);
    $dff = date_diff($d1, $d2);
    $y = $dff->format("%y");
    $m = $dff->format("%m");
    $amount = $pension->sev_amount + $pension->app_amount + $pension->add_out_salary + $pension->add_thr + $pension->add_bonus + $pension->add_others - $pension->deduc_loan - $pension->deduc_union - $pension->deduc_others;
@endphp
<div class="">
    <table class='head'>
        <tr>
            <td width='80'>
                {!! $img_comp !!}
            </td>
            <td style='text-align:left'>
                <h2><strong>{{ strtoupper($comp->company_name) }}</strong></h2>
                <span><strong>Head Office:</strong>{!! $comp->address !!}</span>
                <span>{{(empty($comp->city)) ? "Jakarta"  : $comp->city }} |&nbsp;<strong>Phone</strong>:{{Session::get('company_phone')}}</span>
            </td>
        </tr>
    </table>
    <div class="body">
        <div class="header">
            <span class="title">SURAT PEMBERITAHUAN PENSIUN</span>
        </div>
        <div class="content">
            <p>Menanggapi permohonan pengunduran diri untuk pension tertanggal {{ tgl_indo($pension->approved_at) }}, atas nama {{ $emp->emp_name }}, yang selanjutnya disebut sebagai "Pemohon", perusahaan menyetujui permohonan pengunduran diri untuk pension tersebut. Perusahaan menyatakan terima kasih atas darma bakti Pemohon, selama {{ $y }} tahun, {{ $m }} bulan _sejak diangkat menjadi pegawai/keryawan tetap_ kepada Perusahaan. Diputuskan pengunduran diri efektik pada {{ tgl_indo($pension->sev_date) }}</p>
            <p>Atas jasa yang pernah dipersembahkan Pemohon kepada {{ $comp->company_name }}, maka Perusahaan akan memberikan Dana Kebijaksanaan sebesar <strong>Rp {{ number_format($amount, 0, ".", ",") }}</strong> ({{ucwords(\App\Helpers\Functions::terbilang($amount))}}).</p>
            <p>Berdasarkan kondisi yang saling menghargai tersebut, maka Pemohon menyatakan:</p>
            <ol>
                <li>Menerima proses pengunduran diri untuk pension dari {{ $comp->company_name }} dengan baik. Sehingga dikemudian hari tidak akan ada persoalan hukum yang melibatkan Pemohon dan {{ $comp->company_name }}.</li>
                <li>Pemohon bersedia mengembalikan seluruh alat kerja yang selama ini digunakan Pemohon bekerja, kepada Perusahaan.</li>
                <li>Pemohon akan berkomitmen menjaga seluruh rahasia Perusahaan yang selama ini diketahui.</li>
                <li>Berdasarkan tanggungjawab yang selama ini diemban, maka Pemohon bersedia membantu memberikan informasi kepada Perusahaan. Informasi sebatas hal-hal yang berkaitan dengan tanggungjawab Pemohon selama bekerja di Perusahaan.</li>
            </ol>
            <p></p>
            <p>Surat ini dibuat dengan keadaan saling menghormati antara Pemohon dan Perusahaan, dan kedua belah pihak tidak merasakan adanya paksaan. Dibuat rangkap dua, bermaterai cukup, sehingga masing-masing mempunyai kekuatan hukum yang sama.</p>
            <br>
            <br>
            <br>
            <table style="width: 100%">
                <tr>
                    <td style="width: 50%">Jakarta, {{ tgl_indo(date("Y-m-d")) }}<br>{{ $comp->company_name }}</td>
                    <td style="width: 50%">
                        @if (!empty($pension->received_at))
                            Pemohon,
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%">
                        @if ($signimg != "")
                            <img src="{{ $signimg }}" width="200px" alt="" srcset="">
                        @endif
                    </td>
                    <td style="width: 50%">
                        @if (!empty($pension->received_at) && $empSign!="")
                            <img src="{{ $empSign }}" width="200px" alt="" srcset="">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%"><u>{{ $hrdm }}</u><br>{{ $pos->name ?? "" }} {{ $div->name ?? "" }}</td>
                    <td style="width: 50%">
                        @if (empty($pension->received_at))
                            @if ($empUser->count() > 0)
                                {{-- <a href="{{ route("employee.pension.receive", $pension->id) }}">Klik disini untuk konfirmasi penerimaan surat ini</a> --}}
                            @endif
                        @else
                            {{ $emp->emp_name }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
