<div class='card-body'>
    <div class="d-flex flex-column gap-5">
        <div class="d-flex align-items-center mb-10">
            <div class="symbol symbol-100px me-5">
                <div class="symbol-label" style="background-image: url({{ asset($personel->user->user_img ?? "images/image_placeholder.png") }})">
                </div>
            </div>
            <div class="d-flex justify-content-between w-100 align-items-baseline">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fs-3 fw-bold me-5">{{ $personel->emp_name }}</span>
                        <span class="badge badge-{{ empty($personel->expire) ? "success" : "danger" }}">{{ empty($personel->expire) ? "Aktif" : "Resign" }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span>{{ $personel->emp_id }} - {{ $personel->job_level->name ?? "" }} {{ $personel->user->uac_role->name ?? "" }}</span>
                    </div>
                    {{-- <span class="text-muted fs-base">{{ $leave->leave_group_id }}</span> --}}
                </div>
            </div>
        </div>
        <table class="table table-display-2" id="table-list">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Format Letter Template</th>
                    <th>Issued By</th>
                    <th>Issued Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tename = $template->pluck("name", "id");
                @endphp
                @foreach ($myFl as $i => $item)
                    @if (isset($tename[$item->template_id]))
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $tename[$item->template_id] ?? "-" }}</td>
                            <td>{{ $user_name[$item->created_by] ?? "-" }}</td>
                            <td>{{ date("d F Y", strtotime($item->created_at)) }}</td>
                            <td>
                                <button type="button" onclick="printmv('frame{{ $item->id }}')" class="btn btn-primary btn-icon btn-sm">
                                    <i class="fi fi-sr-print"></i>
                                </button>
                                <iframe id="frame{{ $item->id }}" name="frame{{ $item->id }}" src="{{ route('personel.fl.print', $item->id) }}" width="0" height="0" frameborder="0"></iframe>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="accordion accordion-icon-collapse">
            <div class="accordion-item border-0 bg-secondary-crm">
                <div id="kt_personal_asset" class="accordion-collapse collapse" data-bs-parent="#kt_per_ass">
                    <div class="accordion-body">
                        <form action="{{ route("personel.employee_table.flpost") }}" method="POST">
                            <div class="d-flex flex-column align-items-center" data-form-clone>
                                <div class="card bg-transparent mb-5 shadow-none w-100" data-clone>
                                    <div class="card-body rounded bg-white p-5 border">
                                        <div class="row" data-form-add>
                                            <div class="fv-row col-12">
                                                <label class="col-form-label">Formal Letter Template</label>
                                                <select name="template_id" data-label class="form-select" data-control="select2" data-placeholder="Select Template" required>
                                                    <option value=""></option>
                                                    @foreach ($template ?? [] as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div data-content class="d-flex flex-column"></div>
                                        </div>
                                    </div>
                                    <div class="card-footer border-0">
                                        <div class="d-flex justify-content-end">
                                            @csrf
                                            <input type="hidden" name="emp_id" value="{{ $personel->id }}">
                                            <button type="button" data-bs-toggle="collapse" data-bs-target="#kt_personal_asset" onclick="collapseDisabled(this)" class="btn text-primary">Cancel</button>
                                            <button type="submit" disabled class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center">
                <button type="button" class="btn text-primary" onclick="collapseDisabled(this)" data-disabled data-bs-toggle="collapse" data-bs-target="#kt_personal_asset">
                    <i class="fi fi-rr-add"></i>
                    Add Formal Letter
                </button>
            </div>
        </div>
        <div class="separator separator-solid"></div>
        <h3>Permintaan</h3>
        <table class="table table-display-2" id="table-fl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipe Surat</th>
                    <th>Tanggal Permintaan</th>
                    <th>Pilih Surat</th>
                    <th>Persetujuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($flRequest as $i => $item)
                <form action="{{ route('personel.fl.approve_request') }}" id="form-request-{{ $item->id }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" form="form-request-{{ $item->id }}">
                    <input type="hidden" name="req_id" form="form-request-{{ $item->id }}" value="{{ $item->id }}">
                </form>
                    @php
                        $_lt = null;
                        if(!empty($item->approved_at)){
                            $_lt = $myFl->where("id", $item->letter_id)->first();
                        }
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $lname[$item->letter_type] ?? "-" }}</td>
                        <td>{{ date("d-m-Y", strtotime($item->created_at)) }}</td>
                        <td>
                            @if (empty($item->approved_at))
                                <select name="letter_id" class="form-select mw-200px" form="form-request-{{ $item->id }}" data-control="select2" data-dropdown-parent="#table-fl" data-placeholder="Pilih Surat" required>
                                    <option value=""></option>
                                    @foreach ($myFl as $fl)
                                        @if (isset($tename[$fl->template_id]))
                                            <option value="{{ $fl->id }}">
                                                {{ $tename[$fl->template_id] }} - {{ date("d F Y", strtotime($fl->created_at)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            @else
                                {{ $tename[$_lt->template_id] }} - {{ date("d F Y", strtotime($_lt->created_at)) }}
                            @endif
                        </td>
                        <td>
                            @if (empty($item->approved_at))
                                <button type="submit" class="btn btn-primary btn-sm" form="form-request-{{ $item->id }}">
                                    Approve
                                </button>
                            @else
                                <button type="submit" name="submit" value="cancel" class="btn btn-danger btn-sm" form="form-request-{{ $item->id }}">
                                    Cancel
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
