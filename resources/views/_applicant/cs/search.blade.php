@foreach ($companies as $i => $item)
    <div class="col-sm-6 mb-5">
        <div class="card card-custom gutter-b card-stretch">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex flex-column cursor-pointer" onclick="javascript:;location.href='@auth{{ route('app.cs.detail', $item->id) }}@else{{ route('app.cs_guest.detail', $item->id) }}@endauth'">
                            <div class="d-flex justify-content-between rounded mb-5">
                                <div class="d-flex">
                                    <div class="symbol me-5">
                                        <img src="{{ asset($item->icon ?? "theme/assets/media/avatars/blank.png") }}" class="w-50px h-auto">
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="font-size-h3 fw-bold">{{ $item->company_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="d-flex align-items-center mb-5">
                                <i class="fa fa-map-location-dot me-3 text-dark"></i>
                                <span>Lokasi {{ ucwords(strtolower($city[$item->city_id] ?? "-")) }} {{ ucwords(strtolower($prov[$item->prov_id] ?? "-")) }}</span>
                            </span>
                            <span class="d-flex align-items-center mb-5">
                                <i class="fa fa-users me-3 text-dark"></i>
                                <span>Jumlah Karyawan {{ $item->skala_usaha ?? "-" }} Orang</span>
                            </span>
                            <span class="d-flex align-items-center mb-5">
                                <i class="fa fa-building me-3 text-dark"></i>
                                <span>Industri {{ $industry[$item->industry_id] ?? "-" }} Orang</span>
                            </span>
                            <span class="d-flex flex-column mb-5">
                                <span class="mb-5">Deskripsi Perusahaan : </span>
                                <p>{!! strlen($item->descriptions) > 200 ? substr($item->descriptions, 0, 200)."..." : $item->descriptions !!}</p>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@if ($companies->count() == 0)
<div class="col-sm-12">
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center">
                <span>Perusahaan tidak ditemukan</span>
            </div>
        </div>
    </div>
</div>
@endif
