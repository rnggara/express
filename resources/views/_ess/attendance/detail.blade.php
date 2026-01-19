<div class="modal-body">
    <div class="card">
        <div class="card-header border-0 px-0">
            <h3 class="card-title">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <div class="symbol-label bg-light-primary">
                                <span class="fi fi-sr-envelope-open-dollar text-primary"></span>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="me-2">Persetujuan Detail</h3>
                        </div>
                    </div>
                </div>
            </h3>
            <div class="card-toolbar">
                @if (empty($item['approved_at']) && empty($item['rejected_at']))
                    <span class="badge badge-secondary">Menunggu</span>
                @else
                    @if (!empty($item['approved_at']))
                        <span class="badge badge-success badge-outline">Disetujui</span>
                    @else
                        <span class="badge badge-danger badge-outline">Ditolak</span>
                    @endif
                @endif
            </div>
        </div>
        <div class="card-body rounded bg-secondary-crm">
            <div class="row row-gap-3 p-5 bg-white rounded">
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Tipe Pengajuan</span>
                        <span>{{ "Attendance Correction" }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Nomor Referensi</span>
                        <span>{{ $item['ref_num'] }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Jam Masuk Sekarang</span>
                        <span>{{ $item['last_clock_in'] ?? "N/A" }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Koreksi Jam Masuk</span>
                        <span>{{ $item['clock_in'] }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Jam Keluar Sekarang</span>
                        <span>{{ $item['last_clock_out'] ?? "N/A" }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Koreksi Jam Keluar</span>
                        <span>{{ $item['clock_out'] }}</span>
                    </div>
                </div>
                <div class="col-12">
                    @if (!empty($_GET['approval']))
                        <form action="{{ route('ess.approval.approve', ['type' => 'attendance']) }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                            <div class="d-flex gap-3 justify-content-between">
                                <button type="submit" name="submit" value="approve" class="btn btn-sm w-100 btn-success">Setujui</button>
                                <button type="button" onclick="rejectRequest('{{ $item['id'] }}', '{{ 'attendance' }}')" class="btn btn-sm w-100 btn-danger">Tolak</button>
                            </div>
                        </form>
                    @else
                        @if (empty($item->approved_at) && empty($item->rejected_at))
                            <button type="button" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('ess.attendance.delete', ['id' => $item['id']]) }}" data-id="{{ $item['id'] }}" class="btn btn-danger btn-sm w-100">
                                Batalkan Pengajuan
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="border-0 modal-footer">
    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Tutup</button>
</div>
