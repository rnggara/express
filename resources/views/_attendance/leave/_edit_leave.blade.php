<div class='card-body'>
    <form action="{{route("attendance.leave.update_leave")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10 justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-50px me-3">
                        <img src="{{ asset($uImg ?? "theme/assets/media/avatars/blank.png") }}" class="w-100" alt="">
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $personel->emp_name }}</span>
                        <span class="text-muted">{{ $personel->emp_id }} - {{ $personel->user->uacdepartement->name ?? "-" }}</span>
                    </div>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div class="d-flex align-items-center">
                            <span class="fw-bold fs-3 me-2">Detail Leave</span>
                            <span class="text-muted">(This Year Period)</span>
                        </div>
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
                    <div data-toggle='drawer-detail-{{ $personel->id }}'>
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
                                $anulir = $item->anulir;
                                $sisa = $jatah - $terpakai - $reserved - $sold;
                            @endphp
                            <div data-cc class="bg-white rounded row p-5 my-5 {{ date("Y-m-d") < $item->end_periode ? "" : "opacity-50" }}">
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
                                        <input type="number" step="0.1" data-c="jatah" name="leave[{{ $item->id }}][jatah]" class="form-control" value="{{ $jatah }}" id="">
                                        {{-- <span class="fs-3 fw-bold text-primary">{{ $jatah }}</span> --}}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted">Cuti Terpakai</span>
                                        <input type="number" step="0.1" data-c="used" name="leave[{{ $item->id }}][used]" class="form-control" value="{{ $terpakai + $sold - $anulir }}" id="">
                                        {{-- <span class="fs-3 fw-bold">{{ $terpakai + $sold }}</span> --}}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted">Reserve Cuti</span>
                                        <input type="number" step="0.1" data-c="reserve" name="leave[{{ $item->id }}][reserved]" class="form-control" value="{{ $reserved }}" id="">
                                        {{-- <span class="fs-3 fw-bold">{{ $reserved }}</span> --}}
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted">Sisa Cuti</span>
                                        <input type="number" step="0.1" data-c="sisa" name="leave[{{ $item->id }}][leave]" class="form-control" readonly value="{{ $sisa }}" id="">
                                        {{-- <span class="fs-3 fw-bold text-primary">{{ $sisa}}</span> --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="emp_id" value="{{ $personel->id }}">
                <input type="hidden" name="type" value="request">
                <button type="button" class="btn text-primary me-3" id="kt_drawer_detail_close">Close</button>
                <button type="submit" name="submit" value="approve" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
