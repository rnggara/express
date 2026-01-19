<div class="d-flex flex-column">
    <div class="row">
        <div class="fv-row col-6">
            <label class="col-form-label">Employee ID</label>
            <input type="hidden" name="id" value="{{ $pr->id }}">
            <input type="text" name="emp_id" readonly class="form-control" value="{{ $pr->emp_id }}" id="">
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label">Employee Name</label>
            <input type="text" name="emp_name" readonly class="form-control" value="{{ $pr->emp_name }}" id="">
        </div>
    </div>
    <div class="mb-5"></div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-start">
                <div class="symbol symbol-100px symbol-circle me-5">
                    <div class="symbol-label" style="background-image: url({{ asset($pr->user->user_img ?? "images/image_placeholder.png") }})"></div>
                </div>
                <div class="flex-fill">
                    <div class="row">
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Full Name</label>
                            <label class="fw-normal">{{ $pr->emp_name }}</label>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Join Date</label>
                            <label class="fw-normal">30-10-2023</label>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Employee Status</label>
                            <label class="fw-normal">{{ $pr->employee_status->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Workgroup</label>
                            <label class="fw-normal">{{ $pr->reg->wg->workgroup_name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Job Grade</label>
                            <label class="fw-normal">{{ $pr->job_grade->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Job Level</label>
                            <label class="fw-normal">{{ $pr->job_level->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Position</label>
                            <label class="fw-normal">{{ $pr->position->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Departement</label>
                            <label class="fw-normal">{{ $pr->user->uac_departement->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label w-100 fw-bold">Location</label>
                            <label class="fw-normal">{{ $pr->user->uac_location->name ?? "-" }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="fv-row col-6">
            <label class="col-form-label">Resign Date</label>
            <input type="date" placeholder="Input Date" name="resign_date" class="form-control">
        </div>
        <div class="fv-row col-6" id="sel-resign-type">
            <label class="col-form-label">Reason Type</label>
            <select name="resign_type" class="form-select" data-control="select2" data-dropdown-parent="#sel-resign-type" data-placeholder="Select resign type">
                <option value=""></option>
                <option value="0">Non PHK</option>
                <option value="1">PHK</option>
            </select>
        </div>
        <div class="fv-row col-6" id="sel-resign-reason">
            <label class="col-form-label">Reason for resigning</label>
            <select name="resign_reason" class="form-select" data-control="select2" data-dropdown-parent="#sel-resign-reason" data-placeholder="Select reason">
                <option value=""></option>
                @foreach ($resign_reason as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="fv-row col-6" id="sel-blacklist-flag">
            <label class="col-form-label">Blacklist Flag</label>
            <select name="blacklist_flag" class="form-select" data-control="select2" data-dropdown-parent="#sel-blacklist-flag" data-placeholder="No">
                <option value=""></option>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="fv-row col-12">
            <label class="col-form-label">Remarks</label>
            <textarea name="remarks" class="form-control" id="" cols="5" rows="2"></textarea>
        </div>
        <div class="fv-row col-12" id="sel-approved-by">
            <label class="col-form-label">Must Approve by*</label>
            <select name="must_approved_by" required class="form-select" data-control="select2" data-dropdown-parent="#sel-approved-by" data-placeholder="Select approval">
                <option value=""></option>
                @foreach ($approval as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- <div class="fv-row col-12">
            <label class="col-form-label w-100">Remarks</label>
            <span>&nbsp;&nbsp;.<br>Convallis hac neque mollis id varius.</span>
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center me-3">
                    <i class="fa fa-check-circle me-1"></i>
                    Personel
                </div>
                <div class="d-flex align-items-center me-3">
                    <i class="fa fa-check-circle me-1"></i>
                    Attendance
                </div>
                <div class="d-flex align-items-center me-3">
                    <i class="fa fa-check-circle me-1"></i>
                    Payroll
                </div>
            </div>
        </div> --}}
    </div>
</div>
