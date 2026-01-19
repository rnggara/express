@if(count($applicant) == 0)
<div class="h-100 d-flex flex-column justify-content-center align-items-center">
    <img src="{{ asset("images/search-talent.png") }}" alt="" class="w-25 mb-5">
    <span>Kandidat tidak ditemukan</span>
</div>
@else
<div class="row row-cols-1 row-cols-md-2 g-2">
    @foreach ($applicant as $item)
        @php
            $exp = $exp_list[$item->id] ?? [];
            $edu = $edu_list[$item->id] ?? [];
            $last_edu = [];
            if(!empty($edu)){
                $last_edu = $edu[0];
            }
            $last_salary = 0;
            if(!empty($exp)){
                $last_salary = str_replace(",", "", $exp[0]->salary);
            }
            $test_score = 0;
            if(isset($test_list[$item->id])){
                $test_score = $test_list[$item->id][0]->result_point;
            }
            $booked = $bookmarked[$item->id] ?? 0;
            $profile = $up[$item->id] ?? [];
        @endphp
        <div class="cols">
            <div class="card card-stretch card-px-0">
                <div class="card-body">
                    <div class="d-flex align-items-baseline">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                            <label class="form-check-label" for="flexCheckDefault">
                            </label>
                        </div>
                        <div class="flex-fill mx-5">
                            <div class="d-flex flex-column">
                                <a href="{{ route("search_talent.detail", $item->id) }}" class="fw-bold fs-3 mb-5">{{ ucwords(strtolower($item->name)) }}</a>
                                @empty($exp)
                                    <div class="d-flex mb-5">
                                        <span class="fa fa-briefcase text-dark me-3"></span>
                                        -
                                    </div>
                                @endempty
                                @foreach ($exp as $expVal)
                                    <div class="d-flex align-items-baseline mb-5">
                                        <span class="fa fa-briefcase text-dark me-3"></span>
                                        <div class="d-flex flex-column">
                                            <span>{{ $expVal->position }} {{ $expVal->yoe == null ? "" : "($expVal->yoe tahun)" }}</span>
                                            <span>{{ $expVal->company }}</span>
                                        </div>
                                    </div>
                                @endforeach
                                @empty($last_edu)
                                    <div class="d-flex mb-5">
                                        <span class="fa fa-briefcase text-dark me-3"></span>
                                        -
                                    </div>
                                @else
                                    <div class="d-flex align-items-baseline mb-5">
                                        <span class="fa fa-book text-dark me-3"></span>
                                        <div class="d-flex flex-column">
                                            <span>{{ $last_edu->degree." ".$last_edu->field_of_study }} @empty($last_edu->end_date) (sekarang) @else <?= "(" ?>@monthId($last_edu->end_date)  {{ date("Y", strtotime($last_edu->end_date)).")" }} @endempty</span>
                                        </div>
                                    </div>
                                @endempty
                                <div class="d-flex align-items-baseline mb-5">
                                    <span class="text-dark me-3 fw-semibold">Rp.</span>
                                    <span>{{ number_format($profile->salary_expect ?? $last_salary, 2, ",", ".") }}</span>
                                </div>
                                <div class="d-flex align-items-baseline mb-5">
                                    <span class="far fa-star text-success me-3"></span>
                                    <span class="text-success fw-semibold me-3">{{ $test_score }}</span>
                                    {{-- <a href="{{ route("search_talent.detail", $item->id) }}">Lihat detail score</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-icon btn-sm" data-toggle="bookmark" data-id="{{ $item->id }}">
                                <i class="{{ $booked ? "fa text-primary" : "far text-dark" }} fa-bookmark fs-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
{{ $applicant->links() }}
@endif
