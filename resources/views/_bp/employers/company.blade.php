@extends('layouts.template')

@section('content')
<div class="d-flex flex-column">
    <a href="{{ route("bp.employers.index") }}" class="text-primary mb-5">
        <i class="fa fa-arrow-left text-primary me-3"></i>
        Back
    </a>
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-circle me-3">
                            <img src="{{ asset($mComp->icon) }}" alt="">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <span class="font-size-h3 fw-bold">{{ $mComp->company_name ?? "-" }}</span>
                            <span class="fw-semibold">{{ ucwords(strtolower($comp_city->name ?? "")) }}, {{ $comp_prov->name ?? "" }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h3>Company Overview:</h3>
                        <p>{{ $mComp->descriptions }}</p>
                    </div>
                    <div class="row row-cols-3">
                        <div class="col mb-10">
                            <h3>Registration Number</h3>
                            <span>-</span>
                        </div>
                        <div class="col mb-10">
                            <h3>Company Size</h3>
                            <span>-</span>
                        </div>
                        <div class="col mb-10">
                            <h3>Location</h3>
                            <span>{{ ucwords(strtolower($comp_city->name ?? "")) }}, {{ $comp_prov->name ?? "" }}</span>
                        </div>
                        <div class="col mb-10">
                            <h3>Average Processing Time</h3>
                            <span>-</span>
                        </div>
                        <div class="col mb-10">
                            <h3>Industry</h3>
                            <span>{{ $comp_industry->name ?? "-" }}</span>
                        </div>
                        <div class="col mb-10">
                            <h3>Benefits & Other</h3>
                            <span>-</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column w-50 ms-10">
                    <h3>Company Photo</h3>
                    <a class="d-block overlay" data-fslightbox="lightbox-basic" href="{{ asset("images/company1.png") }}">
                        <!--begin::Image-->
                        <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                            style="background-image:url('{{ asset("images/company1.png") }}')">
                        </div>
                        <!--end::Image-->

                        <!--begin::Action-->
                        <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                            <i class="bi bi-eye-fill text-white fs-3x"></i>
                        </div>
                        <!--end::Action-->
                    </a>
                    <div class="row row-cols-3 mt-5">
                        @for ($i = 2; $i<=4; $i++)
                            <div class="col">
                                <a class="d-block overlay" data-fslightbox="lightbox-basic" href="{{ asset("images/company$i.png") }}">
                                    <!--begin::Image-->
                                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px w-100"
                                        style="background-image:url('{{ asset("images/company$i.png") }}')">
                                    </div>
                                    <!--end::Image-->

                                    <!--begin::Action-->
                                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                                        <i class="bi bi-eye-fill text-white fs-3x"></i>
                                    </div>
                                    <!--end::Action-->
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
