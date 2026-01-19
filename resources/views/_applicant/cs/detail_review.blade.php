<div class="d-flex flex-column flex-md-row">
    <div class="d-flex flex-column flex-fill me-0 me-md-8 mb-3 mb-md-0">
        <div class="card mb-8">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center flex-column flex-md-row justify-content-between mb-5">
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
                    <div class="border p-3 rounded row row-cols-1 row-cols-md-4">
                        @foreach ($rev_list as $i => $item)
                            @php
                                $rsum = $rating_avg[$i] ?? 0;
                                $avg = $rsum / ($reviews->count() == 0 ? 1 : $reviews->count())
                            @endphp
                            <div class="d-flex flex-column mb-3 cols">
                                <span class="fw-semibold mb-3">{{$item}}</span>
                                <div class="d-flex align-items-center">
                                    <span class="text-warning me-3 fw-semibold fs-3">{{number_format($avg, 1)}}</span>
                                    <div class="rating">
                                        <div class="rating-label me-3 {{ $avg >= 1 ? "checked" : "" }}">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </div>
                                        <div class="rating-label me-3 {{ $avg >= 2 ? "checked" : "" }}">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </div>
                                        <div class="rating-label me-3 {{ $avg >= 3 ? "checked" : "" }}">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </div>
                                        <div class="rating-label me-3 {{ $avg >= 4 ? "checked" : "" }}">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </div>
                                        <div class="rating-label me-3 {{ $avg >= 5 ? "checked" : "" }}">
                                            <i class="bi bi-star-fill fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <span class="fw-bold mb-3">Filter Review</span>
        <div class="border rounded p-3 d-flex flex-column flex-md-row align-items-center bg-white mb-5">
            <input type="text" name="job_title" id="job_title" placeholder="Input job title" class="form-control me-md-3 mb-3 mb-md-0">
            <select name="by_rating" id="by_rating" class="form-select" data-control="select2" data-allow-clear="true" data-placeholder="By rating">
                <option value=""></option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">Bintang {{ $i }}</option>
                @endfor
            </select>
            <div class="me-md-3 mb-3 mb-md-0"></div>
            <select name="by_kategori" id="by_kategori" class="form-select" data-control="select2" data-allow-clear="true" data-placeholder="Kategori">
                <option value=""></option>
                @foreach ($rev_list as $i => $item)
                    <option value="{{ $i }}">{{ $item }}</option>
                @endforeach
            </select>
            <div class="me-md-3 mb-3 mb-md-0"></div>
            <button type="button" class="btn btn-primary" onclick="list_review()">Cari</button>
        </div>
        <div id="review-list" class="d-flex flex-column mb-5"></div>
        <ul class="pagination">
            <li class="page-item previous"><a href="javascript:;" onclick="goToPage(this)" class="page-link"><i class="previous"></i></a></li>
            @for ($i = 1; $i <= $rev_paginate->lastPage(); $i++)
                <li class="page-item page"><a href="javascript:;" onclick="goToPage(this)" class="page-link {{$i == 1 ? "active" : ""}}">{{ $i }}</a></li>
            @endfor
            <li class="page-item next"><a href="javascript:;" onclick="goToPage(this)"  class="page-link"><i class="next"></i></a></li>
        </ul>
    </div>
    @include("_applicant.cs.detail_galery")
</div>

