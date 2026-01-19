@extends('layouts.template', ['bgWrapper' => "bg-white"])

@section('content')
    <div class="card card-custom bg-light-primary gradient-card-test">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ asset("images/test.png") }}" alt="">
                <div class="d-flex flex-column ms-5">
                    <span class="fw-bold fs-3tx">Ujian diri yang meningkatkan peluang kerja kamu</span>
                    <span class="fw-semibold">Cobalah ujian diri yang sudah kami sediakan secara gratis. Uji berbagai kemampuan profesional kamu, serta kepribadian dan kecocokanmu dalam lingkungan kerja.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom bg-transparent">
        <div class="card-header border-0">
            <h3 class="card-title">Tes yang wajib kamu coba!</h3>
        </div>
        <div class="card-body">
            <div class="row g-2">
                @foreach ($test as $item)
                    <div class="col-md-6 col-sm-12 mb-5">
                        <div class="card card-stretch border">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between align-center mb-5">
                                        <div class="d-flex align-items-center">
                                            <span class="round p-3 rounded bg-primary">
                                                <i class="fa fa-book text-white fs-2"></i>
                                            </span>
                                            <span class="ms-3 fw-bold fs-2">{{ $item->label }}</span>
                                        </div>
                                        <a href="{{ route("login") }}" class="btn {{ "btn-outline btn-outline-primary" }}">Ambil test</a>
                                    </div>
                                    <p>{!! $item->descriptions !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

