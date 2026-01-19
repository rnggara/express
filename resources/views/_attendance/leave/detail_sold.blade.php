<div class='card-body'>
    <form action="{{route("attendance.leave.approve")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10 justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-3">
                        <img src="{{ asset($uImg[$sold_req->emp_id] ?? "theme/assets/media/avatars/blank.png") }}" class="w-100" alt="">
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $sold_req->emp->emp_name }}</span>
                        <span class="text-muted">{{ $sold_req->emp->emp_id }} - {{ $sold_req->emp->user->uacdepartement->name ?? "-" }}</span>
                    </div>
                </div>
                @if (empty($sold_req->approved_at) && empty($sold_req->rejected_at))
                    <span class="btn btn-outline btn-outline-warning">Persetujuan</span>
                @else
                    @if (!empty($sold_req->approved_at))
                        <span class="btn btn-outline btn-outline-success">Approved</span>
                    @else
                        <span class="btn btn-outline btn-outline-danger">Rejected</span>
                    @endif
                @endif
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex flex-column">
                    <div class="bg-white rounded row p-5 my-5">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold fs-3 me-2">{{ ucwords($emp_leaves->type) }} Leave {{ date("Y", strtotime($emp_leaves->start_periode)) }}</span>
                                <span class="text-muted">Exp {{ date("d F Y", strtotime("$emp_leaves->end_periode")) }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted mb-3">Kuota Cuti</span>
                                <span class="fs-3 fw-bold text-primary">{{ $leave['jatah'] }}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted mb-3">Cuti Terpakai</span>
                                <span class="fs-3 fw-bold">{{ $leave['used'] }}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted mb-3">Reserve Cuti</span>
                                <span class="fs-3 fw-bold">{{ $leave['reserve'] }}</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted mb-3">Sisa Cuti</span>
                                <span class="fs-3 fw-bold text-primary">{{ $leave['jatah'] - $leave['used'] - $leave['reserve'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded row p-5 my-5">
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="text-muted mb-3">Request date</span>
                                <span class="fw-bold">{{ $sold_req->created_at }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="text-muted mb-3">Cuti Ditukarkan</span>
                                <span class="fw-bold">{{ $sold_req->days }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="text-muted mb-3">Peride Pembayaran</span>
                                <span class="fw-bold">{{ date("F Y", strtotime($sold_req->periode)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!empty($sold_req->approved_at) || !empty($sold_req->approved_at))
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
                        <span class="text-muted">{{ date("d F Y", strtotime($sold_req->created_at)) }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ !empty($sold_req->approved_at) ? "Approve" : "Reject" }} At</span>
                        <span class="text-muted">{{ date("d F Y", strtotime($sold_req->approved_at ?? $sold_req->rejected_at)) }}</span>
                    </div>
                </div>
            </div>
            @endif
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="id" value="{{ $sold_req->id }}">
                <input type="hidden" name="type" value="sold">
                <button type="button" class="btn text-primary me-3" id="kt_drawer_detail_close">Close</button>
                @if (empty($sold_req->approved_at) && empty($sold_req->rejected_at))
                    <button type="submit" name="submit" value="reject" class="btn btn-danger me-3">Reject</button>
                    <button type="submit" name="submit" value="approve" class="btn btn-primary">Approve</button>
                @endif
            </div>
        </div>
    </form>
</div>
