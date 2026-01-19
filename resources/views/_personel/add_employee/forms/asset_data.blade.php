<div class="d-flex flex-column">
    <div class="card shadow-none bg-transparent mb-5">
        <div class="card-header border-0">
            <div class="card-title">
                <div class="d-flex flex-column">
                    <h3>Personal Asset</h3>
                    <span class="fs-base fw-normal">Input personal asset that no need to return</span>
                </div>
            </div>
        </div>
        <div class="card-body">
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
                            <div class="fv-row col-12">
                                <label class="col-form-label">Asset</label>
                                <select name="personal[asset][]" data-label class="form-select" data-control="select2" data-placeholder="Select Workgroup">
                                    <option value=""></option>
                                    @foreach ($assets as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Description</label>
                                <input type="text" name="personal[description][]" class="form-control" placeholder="Input Description" id="">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Date Received</label>
                                <input type="date" name="personal[date][]" class="form-control" placeholder="Input Date" id="">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0">
                        <input type="hidden" name="personal[saved][]" data-saved>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" data-m-save onclick="save_multiple(this)">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center">
                <button type="button" class="btn text-primary" onclick="cloneForm(this)">
                    <i class="fi fi-rr-add"></i>
                    Add Personal Asset
                </button>
            </div>
        </div>
    </div>
    <div class="card shadow-none bg-transparent">
        <div class="card-header border-0">
            <div class="card-title">
                <div class="d-flex flex-column">
                    <h3>Company Asset</h3>
                    <span class="fs-base fw-normal">Input company asset that need to return when employee resign</span>
                </div>
            </div>
        </div>
        <div class="card-body bg-white border rounded">
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
                        <div class="row" data-form-add="">
                            <div class="fv-row col-6">
                                <label class="col-form-label">Asset</label>
                                <select name="company[asset][]" class="form-select" data-control="select2" data-placeholder="Select Workgroup">
                                    <option value=""></option>
                                    @foreach ($assets as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Serial Number</label>
                                <input type="text" name="company[serial_num][]" class="form-control" placeholder="Input Serial Number" id="">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Description</label>
                                <input type="text" name="company[description][]" class="form-control" placeholder="Input Description" id="">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Date Received</label>
                                <input type="date" name="company[date][]" class="form-control" placeholder="Input Date" id="">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0">
                        <input type="hidden" name="company[saved][]" data-saved>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" data-m-save onclick="save_multiple(this)">Save</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center">
                <button type="button" class="btn text-primary" onclick="cloneForm(this)">
                    <i class="fi fi-rr-add"></i>
                    Add Company Asset
                </button>
            </div>

        </div>
    </div>
</div>
