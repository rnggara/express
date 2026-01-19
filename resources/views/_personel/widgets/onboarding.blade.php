@php
    $ob = [];
    foreach ($detail as $key => $value) {
        $ob[$value->user_id] = $value;
    }
@endphp
<div class="d-flex flex-column gap-3 h-450px scroll-y">
    @foreach ($emp as $item)
        @php
            $_uId = $item->user->id ?? null;
            $_ob = $ob[$_uId] ?? [];
        @endphp
        <div class="d-flex align-items-center gap-3 border-bottom pb-3">
            <div class="symbol symbol-40px">
                <div class="symbol-label" style="background-image: url('{{ asset($user_img[$item->id] ?? 'images/image_placeholder.png') }}')"></div>
            </div>
            <div class="flex-fill justify-content-between d-flex">
                <div class="d-flex flex-column gap-1">
                    <span class="fw-bold">{{ $item->emp_name }}</span>
                    <span>{{ $item->position->name ?? "-" }}</span>
                </div>
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted">Finish Date</span>
                    <span class="text-danger">{{ date("d M Y", strtotime($_ob->updated_at)) }}</span>
                </div>
            </div>
        </div>
    @endforeach
    @if ($emp->count() == 0)
        <div class="d-flex justify-content-center">
            <span>No Data Available</span>
        </div>
    @endif
</div>
