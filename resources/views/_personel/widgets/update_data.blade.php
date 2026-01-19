@php
    // $res = [];
    // foreach ($detail as $key => $value) {
    //     $res[$value->personel_id] = $value;
    // }
@endphp
<div class="d-flex flex-column gap-3 h-450px scroll-y">
    @foreach ($detail as $_ob)
        @php
            $item = $emp->where("id", $_ob->personel_id)->first();
        @endphp
        @if (!empty($item))
            <div class="d-flex align-items-center gap-3 border-bottom pb-3">
                <div class="symbol symbol-40px">
                    <div class="symbol-label" style="background-image: url('{{ asset($user_img[$item->id] ?? 'images/image_placeholder.png') }}')"></div>
                </div>
                <div class="flex-fill justify-content-between d-flex">
                    <div class="d-flex flex-column gap-1">
                        <span class="fw-bold">{{ $item->emp_name }}</span>
                        <span>Update {{  ucwords(str_replace("_", " ", $_ob->type)) }}</span>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <span class="text-muted">Time</span>
                        <span class="text-danger">{{ date("d M Y", strtotime($_ob->start_date)) }}</span>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    @if ($emp->count() == 0)
        <div class="d-flex justify-content-center">
            <span>No Data Available</span>
        </div>
    @endif
</div>
