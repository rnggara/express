@foreach ($reviews as $item)
<div class="d-flex flex-column bg-white border rounded p-5 mb-5">
    <div class="d-flex align-items-center mb-5">
        <div class="rating me-3">
            <div class="rating-label me-3 {{ $item->overall_rating >= 1 ? "checked" : "" }}">
                <i class="bi bi-star-fill fs-3"></i>
            </div>
            <div class="rating-label me-3 {{ $item->overall_rating >= 2 ? "checked" : "" }}">
                <i class="bi bi-star-fill fs-3"></i>
            </div>
            <div class="rating-label me-3 {{ $item->overall_rating >= 3 ? "checked" : "" }}">
                <i class="bi bi-star-fill fs-3"></i>
            </div>
            <div class="rating-label me-3 {{ $item->overall_rating >= 4 ? "checked" : "" }}">
                <i class="bi bi-star-fill fs-3"></i>
            </div>
            <div class="rating-label me-3 {{ $item->overall_rating >= 5 ? "checked" : "" }}">
                <i class="bi bi-star-fill fs-3"></i>
            </div>
        </div>
        <span class="text-success fw-semibold me-3 fs-3">{{number_format($item->overall_rating, 1)}}</span>
        <span class="fa fa-caret-down text-dark fs-3"></span>
    </div>
    <span class="fw-bold">{{$item->position}}</span>
    <span class="mb-5">{{ $item->is_employee == 1 ? "Karyawan" : "Mantan Karyawan" }} - {{ $job_type[$item->job_type] ?? "" }} @dateId($item->created_at)</span>
    <div class="d-flex align-items-center mb-5">
        <div class="d-flex align-items-center me-5">
            <i class="far fa-thumbs-{{ $item->is_recommended == 1 ? "up" : "down" }} text-{{ $item->is_recommended == 1 ? "success" : "danger" }} me-3"></i>
            Recomendation
        </div>
        <div class="d-flex align-items-center me-5">
            <i class="fa fa-dollar-sign text-{{$item->salary_avg > 0 ? "success" : "danger"}} me-3"></i>
            Gajih {{ $item->salary_avg <= 0 ? "Rendah" : ($item->salary_avg == 1 ? "Rata-rata" : "Tinggi") }}
        </div>
        <div class="d-flex align-items-center me-5">
            <i class="far fa-{{ $item->stress_level < 5 ? "sad-tear" : "laugh" }} text-{{ $item->stress_level < 5 ? "danger" : "success" }} me-3"></i>
            Tingkat Stress {{ $item->stress_level }}
        </div>
    </div>
    <span class="fw-bold mb-5">{{$item->title}}</span>
    <span class="fw-semibold">Kelebihan:</span>
    <span class="mb-5">{!! $item->pros !!}</span>
    <span class="fw-semibold">Kontra:</span>
    <span class="mb-5">{!! $item->cons !!}</span>
</div>
@endforeach

@if ($reviews->count() == 0)
    <div class="d-flex justify-content-center rounded p-5 bg-white">
        <span>Data tidak ditemukan</span>
    </div>
@endif
