<div class="modal-body">
    <div class="d-flex flex-column gap-3">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                <i class="fi fi-rr-x fs-5"></i>
            </button>
        </div>
        <div class="d-flex flex-column gap-5 min-h-700px">
            <span class="fs-3 fw-bold">Deklarasi!</span>
            <span>Karyawan yang bekerja adalah karyawan dalam kondisi sehat. Jika karyawan dalam kondisi tidak sehat maka diwajibkan untuk tidak bekerja.</span>
            <span class="fw-bold">Apakah anda dalam kondisi sehat dan baik untuk bekerja?</span>
            @foreach ($declareState as $i => $item)
                <div class="border rounded p-5 d-flex gap-5 align-items-center bg-hover-light-secondary" onclick="take_attendance('declare_{{ $i }}')">
                    <div class="bg-light-secondary p-5 symbol symbol-80px symbol-circle">
                        <div class="symbol-label" style="background-image: url('{{ asset("images/declare_$i.png") }}')"></div>
                    </div>
                    <span>{{ $item }}</span>
                </div>
            @endforeach
            <div class="flex-fill d-flex flex-column justify-content-end">
                <a href="javascript:;" class="text-dark d-flex gap-5" onclick="take_attendance()">
                    <i class="fi fi-rr-caret-left"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
</div>