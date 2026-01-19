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
                                <span class="font-weight-bolder"><u>SURAT PEMBERITAHUAN PENSIUN</u></span>
                                <span>No. {{ $pension->issue_number }}</span>
                            </div>
                        </div>
                        <div class="col-12 mt-3 container">
                            <div class="px-lg-48">
                                <p>Dengan ini Perusahaan menginformasikan bahwa karyawan;</p>
                                <table>
                                    <tr>
                                        <td>Nama</td>
                                        <td>: {{ $emp->emp_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Lahir</td>
                                        <td>: {{ tgl_indo($emp->emp_lahir) }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIK</td>
                                        <td>: {{ $emp->nik }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Induk Karyawan</td>
                                        <td>: {{ $emp->emp_id }}</td>
                                    </tr>
                                </table>
                                <p></p>
                                @php
                                    $pendate = explode(" ", tgl_indo($pension->pension_date));
                                @endphp
                                <p>Telah memasuki masa persiapan pensiun yang akan berlaku efektif sejak tanggal surat ini dikeluarkan.<br>Dengan jangka waktu masa persiapan pensiun maksimum sampai dengan bulan <strong>{{ $pendate[1] }} {{ $pendate[2] }}</strong>.</p>
                                <p>Demikian surat pemberitahuan ini diinformasikan</p>
                            </div>
                            <br>
                            <br>
                            <br>
                            <table class="mx-auto w-100 w-md-500px">
                                <tr>
                                    <td style="width: 50%">Jakarta, {{ tgl_indo($pension->issue_date) }}</td>
                                    <td style="width: 50%">
                                        @if (!empty($pension->received_at))
                                            Telah membaca dan memahami,
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
