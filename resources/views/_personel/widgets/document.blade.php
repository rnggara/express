<div class="d-flex flex-column gap-3 h-450px scroll-y">
    @if ($detail->count() == 0)
        <div class="d-flex justify-content-center">
            <span>No Data Available</span>
        </div>
    @else
        @foreach ($detail as $item)
            @php
                $_emp = $emp->where("id", $item->user_id)->first();
            @endphp
            @if (!empty($_emp))
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="symbol symbol-40px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-document text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-1">
                        <span class="fw-bold">{{ $item->cv_name }}</span>
                        <span class="text-muted">{{ $_emp->emp_name }}</span>
                    </div>
                </div>
                <div class="d-flex flex-column gap-1">
                    <span class="text-muted">Exp at</span>
                    <span class="text-danger">{{ date("d M Y", strtotime($item->expiry_date)) }}</span>
                </div>
            </div>
            @endif
        @endforeach
    @endif
</div>
