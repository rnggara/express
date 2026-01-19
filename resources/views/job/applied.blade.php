@extends('layouts.template')

@section('content')
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center">
                <img src="{{ asset("images/applied.png") }}" class="w-100 w-md-300px" alt="">
                <span class="mt-3 fs-2tx">Lamaran Berhasil!</span>
                <span>Silahkan menunggu informasi selanjutnya!</span>
                <div class="mt-5 d-flex flex-column align-items-center">
                    <a href="{{ route("applicant.job.index") }}" class="btn btn-primary mb-5">Kembali ke Cari Pekerjaan</a>
                    <a href="{{ url()->to("/") }}" class="fw-semibold">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection
