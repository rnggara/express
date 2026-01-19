<div class='card-body'>
    <form action="{{route("attendance.leave.approve")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10 justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-3">
                        <img src="{{ asset($uImg[$leave_request->emp_id] ?? "theme/assets/media/avatars/blank.png") }}" class="w-100" alt="">
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $leave_request->emp->emp_name }}</span>
                        <span class="text-muted">{{ $leave_request->emp->emp_id }} - {{ $leave_request->emp->user->uacdepartement->name ?? "-" }}</span>
                    </div>
                </div>
                @if (empty($leave_request->approved_at) && empty($leave_request->rejected_at))
                    <span class="btn btn-outline btn-outline-warning">Persetujuan</span>
                @else
                    @if (!empty($leave_request->approved_at))
                        <span class="btn btn-outline btn-outline-success">Approved</span>
                    @else
                        <span class="btn btn-outline btn-outline-danger">Rejected</span>
                    @endif
                @endif
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="d-flex align-items-center">
                            <span class="fw-bold fs-3 me-2">Detail Leave</span>
                            <span class="text-muted">(This Year Period)</span>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm" onclick="openDetail(this, 'drawer-detail-{{ $leave_request->emp_id }}')">
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
                    <div class="d-none" data-toggle='drawer-detail-{{ $leave_request->emp_id }}'>
                        @foreach ($emp_leaves as $item)
                            @php
                                $jatah = $item->jatah ?? 0;
                                $terpakai = $item->used - $item->anulir + $item->unrecorded;
                                $reserved = $item->reserved ?? 0;
                                $sold = $item->sold ?? 0;
                                // $total_leaves = $item->leave["$item->type"."_total_leaves"] ?? [];
                                // foreach ($total_leaves as $key => $vv) {
                                //     $jatah += ($vv['total_leave'] ?? $vv['total_leaves']) ?? 0;
                                // }
                                $sisa = $jatah - $terpakai - $reserved - $sold;
                            @endphp
                            <div class="bg-white rounded row p-5 my-5 {{ date("Y-m-d") < $item->end_periode ? "" : "opacity-50" }}">
                                <div class="col-12">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold fs-3 me-2">{{ ucwords($item->type) }} Leave</span>
                                        <span class="text-muted">Exp {{ date("d F Y", strtotime("$item->end_periode")) }}</span>
                                    </div>
                                </div>
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
                                        <span class="fs-3 fw-bold text-primary">{{ $sisa}}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                @php
                    $tgl_anulir = json_decode($leave_request->tanggal_anulir ?? "[]", true);
                    $tday = $leave_request->total_day;
                @endphp
                <div class="row">
                    <div class="fv-row col-4">
                        <label for="" class="col-form-label">Reason Type</label>
                        <input type="text" name="reason_type" class="form-control" readonly value="{{ $leave_request->rt->name }}" id="">
                    </div>
                    <div class="fv-row col-4">
                        <label for="" class="col-form-label">Cuti Dipakai</label>
                        <input type="text" name="reason_type" class="form-control" readonly value="{{ ucwords($rcon->reasonName->reason_name ?? "") }} Leave" id="">
                    </div>
                    <div class="fv-row col-4">
                        <label for="" class="col-form-label">Total Day</label>
                        <input type="text" name="reason_type" class="form-control" readonly value="{{ $tday }} day" id="">
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-12">
                        <label for="" class="col-form-label required w-100">Date</label>
                        @php
                            $d1 = $leave_request->start_date;
                            $d2 = $leave_request->end_date;
                            $dates = [];
                            $num = 1;
                            while($d1 <= $d2){
                                $N = date("N", strtotime($d1));
                                if($N >= 6){
                                    $num++;
                                } else {
                                    $dates[$num][] = $d1;
                                }
                                $d1 = date("Y-m-d", strtotime($d1." +1 day"));
                            }
                        @endphp
                        <div class="d-flex flex-wrap align-items-center">
                            @foreach ($dates as $item)
                                @if (count($item) == 1)
                                    <span class="badge badge-light-primary me-3">{{ $hariId[date("N", strtotime($item[0]))] }}, @dateId($item[0])</span>
                                @else
                                    <span class="badge badge-light-primary me-3">{{ $hariId[date("N", strtotime($item[0]))] }}, @dateId($item[0]) - {{ $hariId[date("N", strtotime(end($item)))] }}, @dateId(end($item))</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @if (count($tgl_anulir) > 0)
                        <div class="fv-row col-12">
                            <label for="" class="col-form-label required w-100">Tanggal Anulir</label>
                            <div class="d-flex flex-wrap align-items-center">
                                @foreach ($tgl_anulir as $tg)
                                    <span class="badge badge-light-danger me-3">{{ $hariId[date("N", strtotime($tg))] }}, @dateId($tg)</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="fv-row col-12">
                        <label for="" class="col-form-label">Notes</label>
                        <textarea name="notes" class="form-control" id="" cols="30" rows="5" readonly>{{ $leave_request->notes }}</textarea>
                    </div>
                    <div class="fv-row col-12">
                        <label for="" class="col-form-label">Reference Number</label>
                        <input type="text" name="ref_number" value="{{ $leave_request->ref_num }}" class="form-control" readonly id="">
                    </div>
                    @if (!empty($leave_request->file_name))
                    <div class="col-12 mt-5">
                        <div class="bg-white p-5 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px me-5">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="fi fi-rr-document text-primary fs-2"></i>
                                    </div>
                                </div>
                                <span class="text-primary">{{$leave_request->file_name}}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ asset($leave_request->file_url) }}" target="_blank" class="btn btn-icon bg-white bg-hover-light-primary me-3">
                                    <i class="fi fi-rr-eye text-primary"></i>
                                </a>
                                <a href="{{ asset($leave_request->file_url) }}" target="_blank" download class="btn btn-icon bg-white bg-hover-light-primary">
                                    <i class="fi fi-rr-download text-primary"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @if (!empty($leave_request->approved_at) || !empty($leave_request->approved_at))
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
                        <span class="text-muted">{{ date("d F Y", strtotime($leave_request->created_at)) }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ !empty($leave_request->approved_at) ? "Approve" : "Reject" }} At</span>
                        <span class="text-muted">{{ date("d F Y", strtotime($leave_request->approved_at ?? $leave_request->rejected_at)) }}</span>
                    </div>
                </div>
            </div>
            @endif
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="id" value="{{ $leave_request->id }}">
                <input type="hidden" name="type" value="request">
                <button type="button" class="btn text-primary me-3" id="kt_drawer_detail_close">Close</button>
                @if (empty($leave_request->approved_at) && empty($leave_request->rejected_at))
                    <button type="button" class="btn btn-outline btn-outline-danger me-3" onclick="batalkan({{ $leave_request->id }}, 'delete')">
                        <i class="fi fi-rr-trash"></i>
                        Batalkan Pengajuan
                    </button>
                    <button type="submit" name="submit" value="reject" class="btn btn-danger me-3">Reject</button>
                    <button type="submit" name="submit" value="approve" class="btn btn-primary" {{ $tday <= 0 ? "disabled" : "" }}>Approve</button>
                @endif
                @if (!empty($leave_request->approved_at) || !empty($leave_request->rejected_at))
                    <button type="button" class="btn btn-outline btn-outline-danger" onclick="batalkan({{ $leave_request->id }}, 'cancel')">
                        <i class="fi fi-rr-trash"></i>
                        Batalkan Pengajuan
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>
