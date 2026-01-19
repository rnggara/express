@extends('layouts.template', ["bgWrapper" => "bg-white"])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />

@section('content')
<div class="card card-custom">
    <div class="card-header border-0 bg-transparent">
        <span class="card-title"></span>
        <div class="card-toolbar">
            <a href="{{ route("test.page") }}" class="btn btn-outline btn-outline-primary">Kembali</a>
        </div>
    </div>
    <div class="card-body bg-light-primary gradient-card-test rounded">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-center container">
            <img src="{{ asset("images/take_exam.png") }}" class="w-100 w-md-500px" alt="">
            <div class="d-flex flex-column ms-10">
                <span class="fs-2tx fw-bold">{{ $test->label }}</span>
                <p class="mb-10">{!! $test->descriptions !!}</p>
                <div class="d-flex flex-column">
                    <div class="d-flex mb-3 align-items-baseline">
                        <span class="me-5">
                            <i class="fa fa-check-circle text-primary"></i>
                        </span>
                        <span class="menu-title">{{ $test->question_per_quiz }} Pertanyaan</span>
                    </div>
                    <div class="d-flex mb-3 align-items-baseline">
                        <span class="me-5">
                            <i class="fa fa-check-circle text-primary"></i>
                        </span>
                        <span class="menu-title">Waktu pengerjaan {{ $test->time_limit }} menit</span>
                    </div>
                    <div class="d-flex mb-3 align-items-baseline">
                        <span class="me-5">
                            <i class="fa fa-check-circle text-primary"></i>
                        </span>
                        <span class="menu-title">Anda dapat mengikuti {{ $test->label }} hanya sekali setiap {{$cf->delay_retake_test ?? 0}} Hari</span>
                    </div>
                    <div class="d-flex mb-3 align-items-baseline">
                        <span class="me-5">
                            <i class="fa fa-check-circle text-primary"></i>
                        </span>
                        <span class="menu-title">Harap pastikan koneksi internet anda stabil, agar tidak ada hambatan, waktu  test akan terus
                            berjalan, dan akan terkuci jika waktu habis</span>
                    </div>
                    <div class="mt-3">
                        <form action="{{ route('hrd.text.exam_start') }}" method="post">
                            <input type="hidden" name="id" value="{{ $test->id }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-200px">
                                Ikuti Test
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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
