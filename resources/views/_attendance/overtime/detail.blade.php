<div class='card-body'>
    <div class="d-flex flex-column">
        <div class="d-flex align-items-center mb-10">
            <div class="symbol symbol-100px me-5">
                <div class="symbol-label" style="background-image: url({{ asset($personel->user->user_img ?? "images/image_placeholder.png") }})">
                </div>
            </div>
            <div class="d-flex justify-content-between w-100 align-items-baseline">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fs-3 fw-bold me-5">{{ $personel->emp_name }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span>{{ $personel->emp_id }} - {{ $personel->dept->name ?? "" }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge badge-success badge-lg">
                        Approved
                    </span>
                </div>
            </div>
        </div>
        <div class="card bg-secondary-crm mb-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 fv-row">
                        <label class="col-form-label">Date</label>
                        <input type="text" readonly class="form-control" value="{{ date("d F Y", strtotime($overtime->overtime_date)) }}" id="">
                    </div>
                    <div class="col-6 fv-row">
                        <label class="col-form-label">Reason</label>
                        <input type="text" readonly class="form-control" value="{{ $overtime->reason->day_name }}" id="">
                    </div>
                    <div class="col-6 fv-row">
                        <label class="col-form-label">Overtime Type</label>
                        <input type="text" readonly class="form-control" value="Overtime {{ ucwords($overtime->overtime_type) }}" id="">
                    </div>
                    <div class="col-3 fv-row">
                        <label class="col-form-label">Start</label>
                        <input type="text" readonly class="form-control" value="{{ $overtime->overtime_start_time }}" id="">
                    </div>
                    <div class="col-3 fv-row">
                        <label class="col-form-label">End</label>
                        <input type="text" readonly class="form-control" value="{{ $overtime->overtime_end_time }}" id="">
                    </div>
                    @if ($overtime->add_break == 1)
                        @foreach ($overtime->breaks as $i => $item)
                            <div class="col-6 fv-row">
                                <label class="col-form-label">Break {{ $i + 1 }}</label>
                                <input type="text" readonly class="form-control" value="{{ $item['start'] }}" id="">
                            </div>
                            <div class="col-6 fv-row">
                                <label class="col-form-label">Break End {{ $i+1 }}</label>
                                <input type="text" readonly class="form-control" value="{{ $item['end'] }}" id="">
                            </div>
                        @endforeach
                    @endif
                    <div class="col-{{ in_array($overtime->paid, ["money", "no paid"]) ? "6" : "4" }} fv-row">
                        <label class="col-form-label">Paid By</label>
                        <input type="text" readonly class="form-control" value="{{ ucwords($overtime->paid) }}" id="">
                    </div>
                    @if ( $overtime->paid == "days")
                        <div class="col-4 fv-row">
                            <label class="col-form-label">Day</label>
                            <input type="text" readonly class="form-control" value="{{ ucwords($overtime->days) }}" id="">
                        </div>
                    @endif
                    <div class="col-{{ $overtime->paid == "money" ? "6" : "4" }} fv-row">
                        <label class="col-form-label">Allocation Departement</label>
                        <input type="text" readonly class="form-control" value="{{ $overtime->dept->name }}" id="">
                    </div>
                </div>
                @if (!empty($overtime->file_address))
                    <div class="row mt-5 rounded bg-white p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px me-5">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="fi fi-rr-file-invoice text-primary fs-3"></i>
                                    </div>
                                </div>
                                <span class="text-primary">{{ $overtime->file_name }}</span>
                            </div>
                            <a href="{{ asset($overtime->file_address) }}" target="_blank" download="download" class="btn btn-icon">
                                <i class="fi fi-rr-eye text-primary"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @if ($approval == "approval")
            <form action="{{ route('attendance.approval.approve') }}" method="post">
                <div class="d-flex justify-content-end align-items-center">
                    @csrf
                    <input type="hidden" name="id" value="{{ $overtime->id }}">
                    <input type="hidden" name="type" value="overtime">
                    <button type="button" class="btn btn-outline btn-outline-danger me-3" onclick="batalkan({{ $overtime->id }}, 'delete')">
                        <i class="fi fi-rr-trash"></i>
                        Batalkan Pengajuan
                    </button>
                    <button type="submit" class="btn btn-danger me-3" name="submit" value="reject">Reject</button>
                    <button type="submit" class="btn btn-primary" name="submit" value="approve">Approve</button>
                </div>
            </form>
        @else
            <form action="{{ route('attendance.approval.approve') }}" method="post">
                <div class="d-flex justify-content-end align-items-center">
                    @csrf
                    <input type="hidden" name="id" value="{{ $overtime->id }}">
                    <input type="hidden" name="type" value="overtime">
                    <button type="button" class="btn btn-outline btn-outline-danger" onclick="batalkan({{ $overtime->id }}, 'cancel')">
                        <i class="fi fi-rr-trash"></i>
                        Batalkan Pengajuan
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

