<div class="d-flex flex-column flex-md-row">
    <div class="d-flex flex-column flex-fill me-0 me-md-8">
        <div class="card mb-8">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-3 mb-5">Tentang {{$company->company_name}}</span>
                    <div class="row row-cols-3 mb-5">
                        <div class="cols d-flex align-items-baseline mb-3">
                            <i class="fa fa-map-location-dot text-dark me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-3">Lokasi</span>
                                <span>{{ ucwords(strtolower($city->name ?? "-")) }}, {{ ucwords(strtolower($prov->name ?? "-")) }}</span>
                            </div>
                        </div>
                        <div class="cols d-flex align-items-baseline mb-3">
                            <i class="fa fa-users text-dark me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-3">Skala Usaha</span>
                                <span>{{$company->skala_usaha ?? "-"}} Orang</span>
                            </div>
                        </div>
                        <div class="cols d-flex align-items-baseline mb-3">
                            <i class="fa fa-map-location-dot text-dark me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-3">Dress Code</span>
                                <span>{{$company->dess_code ?? "-"}}</span>
                            </div>
                        </div>
                        <div class="cols d-flex align-items-baseline mb-3">
                            <i class="fa fa-map-location-dot text-dark me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-3">Industri</span>
                                <span>{{$industry->name ?? "-"}}</span>
                            </div>
                        </div>
                        <div class="cols d-flex align-items-baseline mb-3">
                            <i class="fa fa-building text-dark me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-3">Didirikan</span>
                                <span>{{$company->year ?? "-"}}</span>
                            </div>
                        </div>
                        <div class="cols d-flex align-items-baseline mb-3">
                            <i class="fa fa-building text-dark me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-3">Benefit</span>
                                <span>{{$company->benefit ?? "-"}}</span>
                            </div>
                        </div>
                        <div class="cols d-flex align-items-baseline mb-3">
                            <i class="fa fa-link text-dark me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-3">Website</span>
                                <a href="{{ $company->link_company }}">{{$company->link_company ?? "-"}}</a>
                            </div>
                        </div>
                        <div class="cols d-flex align-items-baseline mb-3">
                            <i class="fa fa-clock text-dark me-3"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-3">Work hours</span>
                                <span>{{$company->jam_kerja ?? "-"}}</span>
                            </div>
                        </div>
                    </div>
                    <span class="fw-bold fs-3 mb-5">Overview</span>
                    {!! $company->descriptions ?? "-" !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mb-5">
            <span class="fs-1 fw-bold">Review Karyawan</span>
            <a href="{{ route("app.cs.detail", $company->id) }}?v=review">Lihat Semua Review</a>
        </div>
        <div class="card mb-8">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="align-items-center d-flex flex-column flex-md-row justify-content-between mb-5">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <span class="text-warning fs-1 mb-3 fw-semibold">{{ number_format($overall_avg, 1) }}</span>
                            <div class="rating mb-3">
                                <div class="rating-label me-3 {{ $overall_avg >= 1 ? "checked" : "" }}">
                                    <i class="bi bi-star-fill fs-1"></i>
                                </div>
                                <div class="rating-label me-3 {{ $overall_avg >= 2 ? "checked" : "" }}">
                                    <i class="bi bi-star-fill fs-1"></i>
                                </div>
                                <div class="rating-label me-3 {{ $overall_avg >= 3 ? "checked" : "" }}">
                                    <i class="bi bi-star-fill fs-1"></i>
                                </div>
                                <div class="rating-label me-3 {{ $overall_avg >= 4 ? "checked" : "" }}">
                                    <i class="bi bi-star-fill fs-1"></i>
                                </div>
                                <div class="rating-label me-3 {{ $overall_avg >= 5 ? "checked" : "" }}">
                                    <i class="bi bi-star-fill fs-1"></i>
                                </div>
                            </div>
                            <span class="">{{ $reviews->count() }} Ratings in total</span>
                        </div>
                        <div class="d-flex flex-column">
                            @for ($i = 5; $i >= 1; $i--)
                                @php
                                    $ri = $reviews->where("overall_rating", $i);
                                    $sum = ($ri->count() / ($reviews->count() == 0 ? 1 : $reviews->count())) * 100;
                                @endphp
                                <div class="d-flex align-items-center mb-3">
                                    <span class="me-3">{{$i}}</span>
                                    <div class="progress min-w-150px h-15px me-3" style="background-color: rgba(58, 58, 58, .2);">
                                        <div class="progress-bar" role="progressbar" style="background-color: var(--bs-warning); width: {{ $sum }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="5"></div>
                                    </div>
                                    <span>{{$ri->count()}}</span>
                                </div>
                            @endfor
                        </div>
                        <div class="rounded position-relative" style="width: 233px; height: 170px">
                            <div class="h-100 p-3 w-100"></div>
                            <div class="d-flex flex-column position-absolute top-0">
                                <div class="flex-grow-1">
                                    <div class="circle-chart" data-kt-chart-color="primary" data-kt-value="{{ $salary_avg_pctg }}" data-formatter="%" style="height: auto"></div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <span class="text-center fs-6">Tingkat gaji 100 % setinggi atau rata-rata</span>
                                </div>
                            </div>
                        </div>
                        <div class="rounded position-relative" style="width: 233px; height: 170px">
                            <div class="h-100 p-3 w-100"></div>
                            <div class="d-flex flex-column position-absolute top-0">
                                <div class="flex-grow-1">
                                    <div class="circle-chart" data-kt-chart-color="primary" data-kt-value="{{ $is_recommended_pctg }}" data-formatter="%" style="height: auto"></div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <span class="text-center fs-6">100 % karyawan merekomendasikan perusahaan kepada teman</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($job_ads->count() > 0)
        <div class="d-flex justify-content-between mb-5">
            <span class="fs-1 fw-bold">Lowongan di {{$company->company_name}}</span>
            <a href="{{ route("app.cs.detail", $company->id) }}?v=job_ad">Lihat Semua Lowongan</a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-2">
            @foreach ($job_ads->take(4) as $item)
                <div class="cols">
                    <div class="card card-stretch">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="symbol me-5">
                                    <img src="{{ asset($company->icon) }}" alt="" class="w-50px h-auto">
                                </div>
                                <div class="flex-fill d-flex flex-column">
                                    <span class="fw-bold mb-3">{{ $item->position }}</span>
                                    <div class="align-items-md-center d-flex flex-column flex-md-row">
                                        <span class="me-5">
                                            <i class="fa fa-clock text-dark me-3"></i>
                                            {{ $item->job_type == 1 ? "Fulltime" : ($item->job_type == 2 ? "Freelance" : "Contract") }}
                                        </span>
                                        <span class="me-5">
                                            <i class="fa fa-suitcase text-dark me-3"></i>
                                            {{ $item->yoe }} tahun
                                        </span>
                                        <span class="me-5">
                                            <span class="fw-semibold text-dark me-2">Rp.</span>
                                            @if ($item->show_salary == 1)
                                                {{ number_format($item->salary_min, 2, ",", ".") }}{{ !empty($item->salary_max) ? " - ".number_format($item->salary_max, 2, ",", ".") : '' }}
                                                /month
                                            @else
                                                Kompetitif Salary
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
    @include("_applicant.cs.detail_galery")
</div>
