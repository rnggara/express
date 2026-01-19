<div class='card-body'>
    <form action="{{route("attendance.leave.approve")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10 justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-3">
                        <img src="{{ asset($uImg[$extend_req->emp_id] ?? "theme/assets/media/avatars/blank.png") }}" class="w-100" alt="">
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $extend_req->emp->emp_name }}</span>
                        <span class="text-muted">{{ $extend_req->emp->emp_id }} - {{ $extend_req->emp->user->uacdepartement->name ?? "-" }}</span>
                    </div>
                </div>
                @if (empty($extend_req->approved_at) && empty($extend_req->rejected_at))
                    <span class="btn btn-outline btn-outline-warning">Persetujuan</span>
                @else
                    @if (!empty($extend_req->approved_at))
                        <span class="btn btn-outline btn-outline-success">Approved</span>
                    @else
                        <span class="btn btn-outline btn-outline-danger">Rejected</span>
                    @endif
                @endif
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold fs-3 me-2">Detail Leave</span>
                                <span class="text-muted">(This Year Period)</span>
                            </div>
                            <button type="button" class="btn btn-icon btn-sm" onclick="openDetail(this, 'drawer-detail-{{ $extend_req->emp_id }}')">
                                <i class="fi fi-rr-caret-down text-dark"></i>
                            </button>
                        </div>
                        <div class="bg-white rounded row p-5" data-border>
                            <div class="col-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">Kuota Cuti</span>
                                    <span class="fs-3 fw-bold text-primary">{{ $leave['jatah'] }}</span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">Cuti Terpakai</span>
                                    <span class="fs-3 fw-bold">{{ $leave['used'] }}</span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">Reserve Cuti</span>
                                    <span class="fs-3 fw-bold">{{ $leave['reserve'] }}</span>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">Sisa Cuti</span>
                                    <span class="fs-3 fw-bold text-primary">{{ $leave['jatah'] - $leave['used'] - $leave['reserve'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-none" data-toggle='drawer-detail-{{ $extend_req->emp_id }}'>
                            @foreach ($emp_leaves as $item)
                                @php
                                    $jatah = $item->jatah;
                                    $terpakai = $item->used - $item->anulir + $item->unrecorded;
                                    $reserved = $item->reserved ?? 0;
                                    $sold = $item->sold ?? 0;
                                    $sisa = $jatah - $terpakai - $reserved - $sold;
                                    $pr = date("Y", strtotime($item->start_periode));
                                @endphp
                                <div class="bg-white rounded row p-5 my-5 {{ ($pr == $extend_req->periode && $item->type == $extend_req->type) ? "" : "opacity-50" }}">
                                    <div class="col-12 mb-3">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold fs-3 me-2">{{ ucwords($item->type) }} Leave</span>
                                            <span class="text-muted">Exp {{ date("d F Y", strtotime("$item->end_periode")) }} {{ date("Y-m-d") > $item->end_periode ? "(Expired)" : "" }}</span>
                                        </div>
                                    </div>
                                    @if (($pr == $extend_req->periode && $item->type == $extend_req->type))
                                        <div class="col-4">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted">Exp Date</span>
                                                <span class="fw-bold">{{ date("d F Y", strtotime($item->end_periode)) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted">Request Date</span>
                                                <span class="fw-bold">{{ date("d F Y", strtotime($extend_req->created_at)) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted">Total Extend</span>
                                                <span class="fw-bold">{{ $extend_req->months }} Months</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Kuota Cuti</span>
                                            <span class="fs-3 fw-bold text-primary">{{ $jatah }}</span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Cuti Terpakai</span>
                                            <span class="fs-3 fw-bold">{{ $terpakai + $sold }}</span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Reserve Cuti</span>
                                            <span class="fs-3 fw-bold">{{ $reserved }}</span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted">Sisa Cuti</span>
                                            <span class="fs-3 fw-bold text-primary">{{ $sisa }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @if (!empty($extend_req->approved_at) || !empty($extend_req->approved_at))
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-3">
                            <img src="{{ asset($userAction->user_img ?? "theme/assets/media/avatars/blank.png") }}" class="w-100" alt="">
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">{{ $userAction->name }}</span>
                            <span class="text-muted">{{ $userAction->emp->user->uacdepartement->name ?? "-" }}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">Request At</span>
                        <span class="text-muted">{{ date("d F Y", strtotime($extend_req->created_at)) }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ !empty($extend_req->approved_at) ? "Approve" : "Reject" }} At</span>
                        <span class="text-muted">{{ date("d F Y", strtotime($extend_req->approved_at ?? $extend_req->rejected_at)) }}</span>
                    </div>
                </div>
            </div>
            @endif
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="id" value="{{ $extend_req->id }}">
                <input type="hidden" name="type" value="extend">
                <button type="button" class="btn text-primary me-3" id="kt_drawer_detail_close">Close</button>
                @if (empty($extend_req->approved_at) && empty($extend_req->rejected_at))
                    <button type="submit" name="submit" value="reject" class="btn btn-danger me-3">Reject</button>
                    <button type="submit" name="submit" value="approve" class="btn btn-primary">Approve</button>
                @endif
            </div>
        </div>
    </form>
</div>
