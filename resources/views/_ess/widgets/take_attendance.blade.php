<div class="modal-body">
    <div class="d-flex flex-column gap-3">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                <i class="fi fi-rr-x fs-5"></i>
            </button>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-5 align-items-center">
                <div class="symbol symbol-40px">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-sr-clock-eight-thirty fs-3 mt-2 text-primary"></i>
                    </div>
                </div>
                <span class="fs-3 fw-bold">Ambil Absen </span>
            </div>
            <div class="rounded bg-light-primary p-3 d-flex align-items-center gap-3">
                <span class="fi fi-sr-marker text-primary fs-3"></span>
                <div class="d-flex flex-column">
                    <span class="fw-bold">Office</span>
                    <span>{{ Auth::user()->uaclocation->name ?? "-" }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-column">
            <span class="fs-1">{{ date("H:i") }}</span>
            <span>{{ $data['today'] ?? "-" }}</span>
            <div class="flex-fill d-flex flex-column justify-content-center align-items-center" style="height: 532px">
                @php
                    $state = "clock_in";
                    if (!empty($data['clockin'])) {
                        $state = "break_out";
                    }
                    if (!empty($data['breakout'])) {
                        $state = "break_in";
                    }
                    if (!empty($data['breakin'])) {
                        $state = "clock_out";
                    }
                @endphp
                <img src="{{ asset("images/$state.png") }}" alt="" class="cursor-pointer" onclick="take_attendance('{{ $state }}')">
            </div>
        </div>
    </div>
</div>