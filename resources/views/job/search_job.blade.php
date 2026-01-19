@foreach ($job_list as $i => $item)
    @php
        $jtype = $job_type->where('id', $item->job_type)->first();
        $d1 = date_create(date('Y-m-d'));
        $d2 = date_create(date('Y-m-d', strtotime($item->created_at)));
        $d = date_diff($d1, $d2);
        $days = $d->format('%a');
        $months = $d->format('%m');
        $years = $d->format('%y');
        $nlabel = 'Hari ini';
        if ($days > 0) {
            $nlabel = "$days hari yang lalu";
        }
        if ($months > 0) {
            $nlabel = "$months bulan yang lalu";
        }
        if ($years > 0) {
            $nlabel = "$years tahun yang lalu";
        }
        $com = $companies->where('id', $item->company_id)->first();
        $pr = $province->where('id', $com->prov_id ?? null)->first();
        $ct = $city->where('id', $com->city_id ?? null)->first();
        $booked = $bookmark->where('job_id', $item->id)->first();
        $appld = $applied->where("job_id", $item->id)->first();
    @endphp
    <div class="col-sm-12 mb-5">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-body">
                @auth
                <div class="d-flex d-none d-md-inline justify-content-end position-absolute" style="right: 0px">
                    <button type="button" class="btn btn-icon btn-sm me-5 btn-bookmark" data-id="{{ $item->id }}">
                        <i class="{{ empty($booked) ? 'far' : 'fa' }} fa-bookmark text-primary bookmark"></i>
                    </button>
                </div>
                @endauth
                <div class="row">
                    <div class="@auth col-11 @else col-12 @endauth">
                        <div class="d-flex flex-column flex-md-row justify-content-between cursor-pointer rounded"
                            onclick="javascript:;location.href='@auth{{ route('applicant.job.detail', $item->id) }}@else{{ route('applicant.job_guest.detail', $item->id) }}@endauth'">
                            <div class="d-flex">
                                <div class="symbol me-5">
                                    <img src="{{ asset($com->icon ?? "theme/assets/media/avatars/blank.png") }}" class="h-auto w-50px" alt="">
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="font-size-h3 fw-bold">{{ $item->position }} @if(!empty($appld))<span class="badge badge-success">Applied</span>@endif</span>
                                    <span class="fw-semibold">{{ $com->company_name ?? '-' }}</span>
                                    <span class="fw-semibold mb-3">{{ $ct->name ?? $item->placement }} -
                                        {{ $pr->name ?? '' }}</span>
                                    <span class="mb-3">
                                        <i class="fa fa-clock text-dark me-2"></i>
                                        {{ $jtype->name ?? 'Fulltime' }}
                                    </span>
                                    <span class="mb-3">
                                        <i class="fa fa-suitcase text-dark me-2"></i>
                                        {{ $item->yoe }} tahun
                                    </span>
                                    <span class="mb-3">
                                        <span class="text-dark me-2 fw-bold">Rp. </span>
                                        {{ number_format($item->salary_min, 2, ",", ".") }}{{ !empty($item->salary_max) ? " - ".number_format($item->salary_max, 2, ",", ".") : '' }}
                                        /bulan
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end justify-content-md-start">
                                <span class="text-primary">{{ $nlabel }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@if ($job_list->count() == 0)
<div class="col-sm-12">
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center">
                <span>Pekerjaan tidak ditemukan</span>
            </div>
        </div>
    </div>
</div>
@endif
