<div class="modal-body">
    <div class="card">
        <div class="card-header border-0 px-0">
            <h3 class="card-title">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <div class="symbol-label bg-light-primary">
                                <span class="fi fi-sr-calendar-clock text-primary"></span>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="me-2">{{ !empty($_GET['approval']) ? "Persetujuan" : "Cuti" }} Detail</h3>
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
            <div class="row row-gap-3 p-5">
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Kategori Cuti</span>
                        <span>{{ $rt->name }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Alasan Cuti</span>
                        <span>{{ $rs->reasonName->reason_name ?? "-" }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Tanggal Dimulai</span>
                        <span>{{ date("d-m-Y", strtotime($item->start_date)) }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Tanggal Berakhir</span>
                        <span>{{ date("d-m-Y", strtotime($item->end_date)) }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Catatan</span>
                        <span>{{ $item->notes }}</span>
                    </div>
                </div>
                {{-- <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Reference Number</span>
                        <span>{{ $item->ref_num }}</span>
                    </div>
                </div> --}}
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Berkas Diunggah</span>
                        <span>
                            @if (empty($item->file_url))
                                -
                            @else
                                <a href="{{ asset($item->file_url) }}">{{ $item->file_name }}</a>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex flex-column gap-2">
                        <span class="fw-bold">Catatan Penolakan</span>
                        <span>{{ $item->rejected_notes ?? "-" }}</span>
                    </div>
                </div>
                @if (!empty($_GET['approval']))
                    <div class="col-12">
                        <form action="{{ route('ess.approval.approve', ['type' => "leave"]) }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                            <div class="d-flex gap-3 justify-content-between">
                                <button type="submit" name="submit" value="approve" class="btn btn-sm w-100 btn-success">Setujui</button>
                                <button type="button" onclick="rejectRequest('{{ $item['id'] }}', '{{ 'leave' }}')" class="btn btn-sm w-100 btn-danger">Tolak</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="border-0 modal-footer">
    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Close</button>
</div>
