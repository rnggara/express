<div class="d-flex flex-column gap-8 h-100 {{ $class ?? '' }}">
    @if ($hasApproval)
    <div class="card shadow-none card-stretch mh-400px card-p-0">
        <div class="card-body p-5">
            <div class="d-flex flex-column gap-3 h-100">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span class="fs-3 fw-bold">Persetujuan</span>
                    <a href="{{ route("ess.approval.index") }}?t=tab_approval">Lihat Semua <i class="fi fi-rr-caret-right"></i></a>
                </div>
                <div class="d-flex align-items-center scroll-x gap-5">
                    @foreach ($listcat as $i => $item)
                        <button type="button" data-list-item onclick="listRequest(this)" data-target='#persetujuan-content' data-key="{{ $i }}" class="btn {{ $i == "leave" ? "active" : "" }} btn-sm btn-active-primary border text-nowrap rounded-4">{{ ucwords(str_replace("_", " ", $item)) }}</button>
                    @endforeach
                </div>
                <div data-id="#persetujuan-content" class="flex-fill"></div>
            </div>
        </div>
    </div>
    @endif
    <div class="card shadow-none card-stretch mh-400px card-p-0">
        <div class="card-body p-5">
            <div class="d-flex flex-column gap-3 h-100">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span class="fs-3 fw-bold">Pengajuan Saya</span>
                    <a href="{{ route("ess.approval.index") }}?t=tab_my_request">Lihat Semua <i class="fi fi-rr-caret-right"></i></a>
                </div>
                <div class="d-flex align-items-center scroll-x gap-5">
                    @foreach ($listcat as $i => $item)
                        <button type="button" data-list-item onclick="listRequest(this)" data-target='#pengajuan-content' data-key="{{ $i }}" class="btn {{ $i == "leave" ? "active" : "" }} btn-sm btn-active-primary border text-nowrap rounded-4">{{ ucwords(str_replace("_", " ", $item)) }}</button>
                    @endforeach
                </div>
                <div data-id="#pengajuan-content" class="flex-fill"></div>
            </div>
        </div>
    </div>
    <div class="card shadow-none card-stretch mh-400px card-p-0 flex-fill">
        <div class="card-body p-5">
            <div class="d-flex flex-column gap-3 h-100">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <span class="fs-3 fw-bold">Kehadiran tidak tepat</span>
                    <a href="{{ route("ess.attendance.index") }}">Lihat Semua <i class="fi fi-rr-caret-right"></i></a>
                </div>
                <div class="d-flex flex-column gap-3 h-100">
                    @if (count($incorrect_att) > 0)
                        @foreach ($incorrect_att as $i => $item)
                            <span class="d-flex justify-content-between">
                                <span>{{ date("d-m-Y", strtotime($item['date'])) }}</span>
                                <span class="text-danger">{{ $item['remarks'] }}</span>
                            </span>
                            @if ($i < count($incorrect_att) - 1)
                                <div class="separator separator-solid"></div>
                            @endif
                        @endforeach
                    @else
                        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                            <span class="fi fi-rr-document fs-3tx text-muted"></span>
                            <span class="text-muted">Tidak Ada Data</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>