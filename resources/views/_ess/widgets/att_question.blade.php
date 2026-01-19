<div class="modal-body">
    <div class="d-flex flex-column gap-3">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                <i class="fi fi-rr-x fs-5"></i>
            </button>
        </div>
        <div class="d-flex flex-column gap-5 min-h-700px">
            <span class="fs-3 fw-bold">Konfirmasi Pengguna</span>
            <span>Silahkan jawab pertanyaan berikut, untuk memastikan bahwa anda benar2 pemegang akun!</span>
            <span class="text-muted" style="font-style: italic">Akun akan terkunci beberapa saat bila anda berulang 3 kali salah dalam menjawab pertanyaan</span>
            <div class="w-70px">
                <div class="bg-light-primary rounded p-1 gap-1 d-flex align-items-center">
                    <div id="loading" data-s="10" class="w-20px h-20px"></div>
                    <span data-timer>00:10</span>
                </div>
            </div>
            <span class="fw-bold">{{ $question['label'] }}</span>
            @foreach ($question['point'] ?? [] as $i => $item)
                <div class="border rounded p-5 d-flex gap-5 align-items-center bg-hover-light-secondary" onclick="take_attendance('question_{{ $item['is_true'] }}')">
                    <span>{{ $item['label'] }}</span>
                </div>
            @endforeach
            <div class="flex-fill d-flex flex-column justify-content-end">
                <a href="javascript:;" class="text-dark d-flex gap-5" onclick="take_attendance('clock_in')">
                    <i class="fi fi-rr-caret-left"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
</div>