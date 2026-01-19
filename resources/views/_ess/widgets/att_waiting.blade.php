<div class="modal-body">
    <div class="d-flex flex-column align-items-center justify-content-center h-100 w-100 gap-3">
        <div class="bg-light-secondary rounded p-1 gap-1 d-flex align-items-center">
            <div id="loading" data-s="{{ intval($s ?? 30) }}" data-total="30" data-cl="#DD3545" data-next="dismiss" class="w-20px h-20px"></div>
            <span data-timer>00:{{ $s ?? "30" }}</span>
        </div>
        <span class="text-danger fw-bold">Akun Terkunci beberapa saat</span>
        <span class="text-center">Patikan menjawab pertanyaan Konfirmasi Akun dengan benar untuk membuktikan bahwa anda benar benar merupakan pemilik akun ini</span>
    </div>
</div>