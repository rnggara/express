<div class="d-flex flex-column gap-5">
    @foreach ($lists as $i => $item)
        <div class="card shadow-none border bg-active-light {{ $i == 0 ? "active" : "" }} cursor-pointer" data-filter>
            <div class="card-body">
                <div class="d-flex flex-column gap-5">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa fa-circle" style="color: {{ $clr[$i] ?? "#000" }}"></i>
                        <span data-text>{{ $item }}</span>
                    </div>
                    <span class="fs-3x text-active-primary {{ $i == 0 ? "active" : "" }} fw-bold">{{ $x[$i] ?? 0 }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
