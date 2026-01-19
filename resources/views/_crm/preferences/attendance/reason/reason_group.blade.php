<div class="d-flex flex-column gap-3">
    <div class="position-relative">
        <input type="text" class="form-control ps-12" placeholder="Search" name="search_reason">
        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
        <input type="hidden" name="id" value="{{ $gr->id }}">
    </div>
    <div class="d-flex flex-column h-400px scroll" data-content>
        @foreach ($rr as $item)
            <div class="p-3 d-flex align-items-center gap-3 rounded bg-white border" data-item>
                <div class="form-check">
                    <input class="form-check-input" name="reasons[]" type="checkbox" {{ in_array($item->id, $gr->reasons ?? []) ? "checked" : "" }} value="{{ $item->id }}" />
                </div>
                <span class="col-form-label">{{ $item->reason_name }}</span>
            </div>
        @endforeach
    </div>
</div>
