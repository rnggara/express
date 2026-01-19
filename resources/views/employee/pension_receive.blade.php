@extends('layouts.templateContract')

@section('css')

@endsection

@section('content')
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
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <h3 class="card-title">Receive Pension Letter</h3>
                <div class="card-toolbar">
                    <div class="btn-group">

                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (!empty($pension->deleted_at))
                    <div class="row mt-10">
                        <div class="col-12">
                            <div class="alert alert-custom alert-outline-dark justify-content-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="alert-text font-size-h3 font-weight-boldest"></span>
                                    <span class="alert-text">Not available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                <form action="{{ route('employee.pension.confirm') }}" method="post">
                    @empty($pension->received_at)
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-custom alert-outline-dark justify-content-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="alert-text font-size-h3 font-weight-boldest"></span>
                                        <span class="alert-text">{{ $emp->emp_name }} sudah membaca dan sudah memahami surat ini?</span>
                                        <div class="mt-3">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pension->id }}">
                                            <input type="hidden" name="type" value="{{ $type }}">
                                            <button type="submit" name="submit" value="1" class="btn btn-primary">Receive</button>
                                            <button type="submit" name="submit" value="0" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endempty
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="separator separator-solid separator-dark"></div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-md-center align-items-start d-flex">
                                {!! $img_comp !!}
                                <div class="d-flex flex-column">
                                    <h3><strong>{{ strtoupper($comp->company_name) }}</strong></h3>
                                    <span><strong>Head Office:</strong>{!! $comp->address !!}</span>
                                    <span>{{(empty($comp->city)) ? "Jakarta"  : $comp->city }} |&nbsp;<strong>Phone</strong>:{{Session::get('company_phone')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="separator separator-solid separator-dark"></div>
                        </div>
                        <div class="col-12 mt-3 text-center">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bolder"><u>SURAT PEMBERITAHUAN PERSETUJUAN</u></span>
                            </div>
                        </div>
                        <div class="col-12 mt-3 container">
                            <div class="px-lg-48">
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
                            </div>
                            <br>
                            <br>
                            <br>
                            <table class="mx-auto w-100 w-md-500px">
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
                                            <img src="{{ $signimg }}" class="max-w-100px" alt="" srcset="">
                                        @endif
                                    </td>
                                    <td style="width: 50%">
                                        @if (!empty($pension->received_at) && $empSign!="")
                                            <img src="{{ $empSign }}" class="max-w-100px" alt="" srcset="">
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
                        <div class="col-12 mt-3">
                            <div class="separator separator-solid separator-dark"></div>
                        </div>
                    </div>
                    @empty($pension->received_at)
                        <div class="row mt-10">
                            <div class="col-12">
                                <div class="alert alert-custom alert-outline-dark justify-content-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="alert-text font-size-h3 font-weight-boldest"></span>
                                        <span class="alert-text">{{ $emp->emp_name }} sudah membaca dan sudah memahami surat ini?</span>
                                        <div class="mt-3">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pension->id }}">
                                            <input type="hidden" name="type" value="{{ $type }}">
                                            <button type="submit" name="submit" value="1" class="btn btn-primary">Receive</button>
                                            <button type="submit" name="submit" value="0" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endempty
                </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
