<div class="d-flex flex-column align-items-center" data-form-clone>
    <div class="card bg-transparent mb-5 shadow-none w-100" data-clone>
        <div class="card-body rounded bg-white p-5 border">
            <div class="d-flex justify-content-between align-items-center d-none" data-head>
                <span class="fs-3 fw-bold">Nama</span>
                <button type="button" class="btn btn-icon" onclick="accrd(this)">
                    <i class="fi fi-rr-caret-down" data-accr="expand"></i>
                    <i class="fi fi-rr-caret-up d-none" data-accr="collapse"></i>
                </button>
            </div>
            <div class="row" data-form-add>
                <div class="fv-row col-6">
                    <label class="col-form-label">Company Name</label>
                    <input type="text" name="company[]" data-label value="" placeholder="Input Company Name" class="form-control">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Line of Business</label>
                    <select name="industry[]" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select Line of Business">
                        <option value=""></option>
                        @foreach ($master['industry'] as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Salary</label>
                    <div class="position-relative">
                        <input type="text" name="salary[]" value="" placeholder="0" class="form-control number ps-13">
                        <span class="position-absolute top-25 ms-5 mt-1">IDR</span>
                    </div>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Position</label>
                    <input type="text"  name="position[]" value="" placeholder="Input Position" class="form-control">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Employment Status</label>
                    <select name="job_type[]" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select Employment Status">
                        <option value=""></option>
                        @foreach ($emp_status as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6"></div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Start Date</label>
                    <select name="start_month[]" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select Start Month">
                        <option value=""></option>
                        @foreach ($idFullMonth as $key => $item)
                            <option value="{{ sprintf("%02d", $key) }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">&nbsp;</label>
                    <select name="start_year[]" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select Start Year">
                        <option value=""></option>
                        @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="fv-row col-12 mt-5">
                    <div class="form-check">
                        <label class="form-check-label">
                            I am currenty working in this job
                            <input class="form-check-input" type="checkbox" value="1" name="still[]" data-toggle="still" />
                        </label>
                    </div>
                </div>
                <div class="fv-row col-6" data-target="still">
                    <label class="col-form-label">End Date</label>
                    <select name="end_month[]" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select End Month">
                        <option value=""></option>
                        @foreach ($idFullMonth as $key => $item)
                            <option value="{{ sprintf("%02d", $key) }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6" data-target="still">
                    <label class="col-form-label">&nbsp;</label>
                    <select name="end_year[]" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select End Year">
                        <option value=""></option>
                        @for ($i = date("Y") - 50; $i <= date("Y") + 50; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="fv-row col-12">
                    <label class="col-form-label">Description</label>
                    <textarea name="descriptions[]" class="form-control" id="" cols="30" rows="5" placeholder="Input descriptions"></textarea>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Reference</label>
                    <input type="text" name="reference[]" class="form-control" placeholder="Input reference name" id="">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">&nbsp;</label>
                    <input type="text" name="phone[]" class="form-control" placeholder="Input reference number" id="">
                </div>
            </div>
        </div>
        <div class="card-footer border-0">
            <input type="hidden" name="saved[]">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-m-save onclick="save_multiple(this)">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="d-flex flex-column align-items-center">
    <button type="button" class="btn text-primary" onclick="cloneForm(this)">
        <i class="fi fi-rr-add"></i>
        Add {{ ucwords(str_replace("_", " ", $sec)) }}
    </button>
</div>
