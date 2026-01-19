@extends('layouts.template')

@section('css')
@endsection

@section('content')
    <div class="container">
        <div class="container">
            <div class="card card-custom gutter-b card-stretch bg-transparent mb-5">
                <div class="card-header border-0">
                    <a href="{{ route('job.add.view') }}" class="card-title text-primary">
                        <i class="fa fa-arrow-left fs-3 text-primary me-3"></i> Kembali ke pembuatan Job Ad
                    </a>
                </div>
            </div>
            <div class="card card-custom mb-5">
                <div class="card-body border rounded">
                    <div class="d-flex flex-column">
                        <span class="fs-2 fw-bold mb-3">Job Ad</span>
                        <div class="d-flex align-items-center">
                            <div class="w-40px h-40px bg-light-primary me-3"></div>
                            <span style="font-size: 14px">Ini halaman terakhir dari proses belanja kamu,<br>pastikan semua
                                sudah
                                benar dan sesuai yaa.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-custom mb-5">
                <div class="card-body border rounded">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span class="fw-bold">Informasi Pengguna</span>
                            <a href="{{ route('account.setting') }}" class="btn text-primary">
                                <i class="fa fa-edit text-primary"></i>
                                Edit
                            </a>
                        </div>
                        <div class="border rounded p-5">
                            <table>
                                <tr>
                                    <th>Nama</th>
                                    <th>: {{ $user->name }}</th>
                                </tr>
                                <tr>
                                    <th>Nama Perusahaan</th>
                                    <th>: {{ $company->company_name }}</th>
                                </tr>
                                <tr>
                                    <th>Nomor Registrasi</th>
                                    <th>: {{ $company->reg_num ?? '-' }}</th>
                                </tr>
                                <tr>
                                    <th>Lokasi Perusahaan</th>
                                    <th>: {!! strip_tags($company->address) !!}, {{ $kec->name }}, {{ $city->name }},
                                        {{ $prov->name }}, Indonesia {{ $company->kode_pos }}</th>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <th>: {{ $user->phone ?? '-' }}</th>
                                </tr>
                                <tr>
                                    <th>Alamat Email</th>
                                    <th>: {{ $user->email ?? '-' }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-custom mb-5">
                <div class="card-body border rounded">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span class="fw-bold">Nama Paket</span>
                        </div>
                        @php
                            $discount = $package->discount;
                            $cut = $package->price * ($discount / 100);
                            $net = $package->price - $cut;
                        @endphp
                        <div class="border rounded p-5">
                            <div class="row row-cols-2">
                                <div class="cols mb-3">
                                    <span class="fw-bold fs-3">{{ $package->label }}</span>
                                </div>
                                <div class="cols mb-3">
                                    <span class="fw-bold fs-3">Rp. {{ number_format($net, 2, ',', '.') }}</span>
                                </div>
                                <div class="cols mb-3">
                                    <span class="fw-bold fs-3">@dateId($job->created_at) to @dateId(date('Y-m-d', strtotime("+$package->package_duration month $job->created_at")))</span>
                                </div>
                                <div class="cols mb-3">
                                    <span class="fw-bold text-muted fs-3" style="text-decoration: line-through">Rp.
                                        {{ number_format($package->price, 2, ',', '.') }}</span>
                                </div>
                                <div class="cols mb-3">
                                    <span class="badge badge-light-primary">{{ "$package->discount%" }}</span>
                                </div>
                            </div>
                            <div class="mb-5">
                                <span class="mb-5">{!! $package->descriptions !!}</span>
                            </div>
                            <div class="border rounded p-5">
                                <div class="mb-5">
                                    <span class="mb-5">Isi Paket : </span>
                                </div>
                                <ul>
                                    <li class="fw-bold">Tayang {{ $package->tayang_days }} Hari</li>
                                    @if (!empty($package->job_credits))
                                        <li class="fw-bold">Credit job ads {{ $package->job_credits }}</li>
                                    @endif
                                    @if (!empty($package->search_applicant))
                                        <li class="fw-bold">Search Kandidat {{ $package->search_applicant }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-custom mb-5">
                <div class="card-body border rounded">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span class="fw-bold">Voucher Promo</span>
                        </div>
                        <div class="fv-row">
                            <span class="col-form-label">Punya Kode Voucher?</span>
                            <div class="d-flex">
                                <input type="text" class="form-control me-5"
                                    placeholder="Masukan kode voucher anda di sini">
                                <button type="button" class="btn btn-primary text-nowrap">Gunakan Voucher</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="">
                <div class="d-flex flex-column">
                    <div class="card card-custom mb-5">
                        <div class="card-body border rounded">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-5">
                                    <span class="fw-bold">Metode Pembayaran</span>
                                </div>
                                <span class="fw-bold">Bayar dengan Transfer Bank</span>
                                <div class="row mb-5" data-kt-buttons="true"
                                    data-kt-buttons-target=".form-check-image, .form-check-input">
                                    <!--begin::Col-->
                                    <div class="col-12">
                                        <label class="form-check-image active w-100">
                                            <div class="form-check-wrapper p-5 d-flex align-items-center">
                                                <img src="{{ asset("images/bank-bca.png") }}" class="h-50px" />
                                                <span class="fs-3">Transfer Bank BCA</span>
                                            </div>

                                            <div class="form-check form-check-custom form-check-solid" style="display: none">
                                                <input class="form-check-input" type="radio" checked value="bca"
                                                    name="option2" />
                                                <div class="form-check-label">
                                                    Option 1
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-12">
                                        <label class="form-check-image">
                                            <div class="form-check-wrapper">
                                                <img src="assets/media/stock/600x400/img-2.jpg" />
                                            </div>

                                            <div class="form-check form-check-custom form-check-solid me-10">
                                                <input class="form-check-input" type="radio" value="1" name="option2"
                                                    id="text_wow" />
                                                <div class="form-check-label">
                                                    Option 2
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div class="col-12">
                                        <label class="form-check-image">
                                            <div class="form-check-wrapper">
                                                <img src="assets/media/stock/600x400/img-3.jpg" />
                                            </div>

                                            <div class="form-check form-check-custom form-check-solid me-10">
                                                <input class="form-check-input" type="radio" value="1"
                                                    name="option2" />
                                                <div class="form-check-label">
                                                    Option 3
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("input.number").number(true, 2)
            $("table.display").DataTable()
        })
    </script>
@endsection
