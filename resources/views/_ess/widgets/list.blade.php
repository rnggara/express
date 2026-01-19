@if (count($list) == 0)
<div class="d-flex align-items-center flex-column h-100 justify-content-center">
    <span class="fi fi-rr-document fs-3tx text-muted"></span>
    <span class="text-muted">Tidak Ada Data</span>
</div>
@else
<div class="d-flex flex-column scroll-y h-175px">
    @foreach ($list as $item)
        <div class="d-flex flex-column gap-3 p-3 border rounded">
            @if ($tg == "#pengajuan-content")
                <div>
                    <span class="badge border">Menunggu</span>
                </div>
            @endif
            <span>{!! $item['desc'] !!}</span>
            <span class="text-muted">{{ date("d F Y, H:i A", strtotime($item['created_at'])) }}</span>
        </div>
    @endforeach
</div>
@endif
