@php
    $wg = $widget_dashboard->where("position", $key)->first();
@endphp

@if (!empty($wg))
    @include('_attendance.widgets.'.$wg->widget_key)
@else
<div class="card shadow-none card-strech border border-dashed border-primary bg-transparent cursor-pointer flex-fill" onclick="openModalWidget({{ $key }})">
    <div class="card-body">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <span class="fi fi-rr-chart-histogram fs-3 text-primary"></span>
            <span class="text-primary">Tambah Data Dashboard</span>
        </div>
    </div>
</div>
@endif
