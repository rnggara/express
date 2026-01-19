<div class="modal-body">
    <div class="card">
        <div class="card-header border-0 px-0">
            <div class="card-title">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <div class="symbol-label bg-light-primary">
                                <span class="fi fi-sr-time-quarter-past text-primary"></span>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="me-2">{{ !empty($_GET['approval']) ? "Persetujuan" : "Lembur" }} Detail</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-toolbar">
                @if (empty($overtime->approved_at))
                    <span class="badge badge-secondary badge-lg">Menunggu</span>
                @else
                    @if (!empty($overtime['approved_at']))
                        <span class="badge badge-success badge-outline">Disetujui</span>
                    @else
                        <span class="badge badge-danger badge-outline">Ditolak</span>
                    @endif
                @endif
            </div>
        </div>
        <div class="card-body rounded bg-secondary-crm">
            <div class="rounded p-5 bg-white">
                <div class="row row-gap-6">
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">Tanggal</span>
                            <span>{{ date("d-m-Y", strtotime($overtime->overtime_date)) }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">Alasan</span>
                            <span>{{ $dcode->day_name ?? "Workday" }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">Jam Masuk Kerja</span>
                            <span>{{ empty($overtime->work_start) ? "-" : date("H:i", strtotime($overtime->work_start)) }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">Jam Keluar Kerja</span>
                            <span>{{ empty($overtime->work_end) ? "-" : date("H:i", strtotime($overtime->work_end)) }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">Tipe Lembur</span>
                            <span>Overtime {{ strtoupper($overtime->overtime_type) }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex flex-column gap-2">
                                    <span class="fw-bold">Dimulai</span>
                                    <span>{{ date("H:i",strtotime($overtime->overtime_start_time)) }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column gap-2">
                                    <span class="fw-bold">Selesa</span>
                                    <span>{{ date("H:i",strtotime($overtime->overtime_end_time)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($overtime->breaks ?? [] as $i => $item)
                        <div class="col-6">
                            <div class="d-flex flex-column gap-2">
                                <span class="fw-bold">Break {{ $i+1 }}</span>
                                <span>{{ date("H:i",$item['start']) }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column gap-2">
                                <span class="fw-bold">Break {{ $i+1 }} Berakhir</span>
                                <span>{{ date("H:i",$item['end']) }}</span>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">Paid By</span>
                            <span>{{ ucwords($overtime->paid) }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">Departemen</span>
                            <span>{{ $dep->name }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">Berkas Diunggah</span>
                            <span>
                                @if (empty($overtime->file_address))
                                    -
                                @else
                                    <a href="{{ asset($overtime->file_address) }}">{{ $overtime->file_name }}</a>
                                @endif
                            </span>
                        </div>
                    </div>
                    @if (!empty($_GET['approval']))
                        <div class="col-12">
                            <form action="{{ route('ess.approval.approve', ['type' => "overtime"]) }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $overtime['id'] }}">
                                <div class="d-flex gap-3 justify-content-between">
                                    <button type="submit" name="submit" value="approve" class="btn btn-sm w-100 btn-success">Setujui</button>
                                    <button type="button" onclick="rejectRequest('{{ $overtime['id'] }}', '{{ 'overtime' }}')" class="btn btn-sm w-100 btn-danger">Tolak</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="border-0 modal-footer">
    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Tutup</button>
</div>
