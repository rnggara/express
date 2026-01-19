<div class="d-flex flex-column gap-5">
    <div class="accordion accordion-icon-collapse">
        <div class="accordion-item border-0 bg-secondary-crm">
            <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_payroll_sum">
                <div class="d-flex">
                    <div class="symbol symbol-40px me-5">
                        <div class="symbol-label bg-light-primary">
                            <i class="fi fi-sr-money-check-edit fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="fs-4 fw-semibold mb-0">Summary Data</h3>
                        <span>Data general payroll</span>
                    </div>
                </div>
                <span class="accordion-icon">
                    <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                    <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                </span>
            </div>
            <div id="kt_payroll_sum" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#tab_general_primary">
                <div class="accordion-body">
                    <form action="{{ route('personel.employee_table.update_data') }}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="fv-row col-6">
                                <label class="col-form-label">Tax Status</label>
                                <select name="tax_status" class="form-select" data-control="select2" data-placeholder="Select Tax Status">
                                    <option value=""></option>
                                    @foreach ($tax_status as $item)
                                        <option value="{{ $item->id }}" {{ $personel->tax_status_id == $item->id ? "SELECTED" : "" }}>{{ $item->name."($item->code)" }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Tax Type</label>
                                <select name="tax_type" class="form-select" data-control="select2" data-placeholder="Select Tax Type">
                                    <option value=""></option>
                                    @foreach (\Config::get("constants.tax_type") ?? [] as $key => $item)
                                        <option value="{{ $key }}" {{ $personel->tax_type == $key ? 'selected' : "" }}>{{ ucwords($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Currency</label>
                                <select name="currency" class="form-select" data-control="select2" data-placeholder="Select Currency">
                                    <option value=""></option>
                                    @foreach ($countries as $item)
                                        <option value="{{ $item->id }}" {{ $personel->payroll_currency == $item->id ? "SELECTED" : "" }}>{{ $item->currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" data-control="select2" data-placeholder="Select Payment Method">
                                    <option value=""></option>
                                    @foreach (\Config::get("constants.payment_method") ?? [] as $key => $item)
                                        <option value="{{ $key }}" {{ $personel->payroll_payment_method == $key ? "selected" : "" }}>{{ ucwords($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Payment Schedule</label>
                                <select name="payment_schedule" class="form-select" data-control="select2" data-placeholder="Select Payment Schedule">
                                    <option value=""></option>
                                    @foreach (\Config::get("constants.payment_schedule") ?? [] as $key => $item)
                                        <option value="{{ $key }}" {{ $personel->payroll_payment_schedule == $key ? "selected" : "" }}>{{ ucwords($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Active Period Date</label>
                                <input type="text" name="active_period_date" value="{{ empty($personel->payroll_active_period_date) ? "" : date("d/m/Y", strtotime($personel->payroll_active_period_date)) }}" class="form-control flatpicker" id="active_periode_date" placeholder="Input Active Period Date">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-5">
                            @csrf
                            <input type="hidden" name="type" value="payroll_summary">
                            <input type="hidden" name="id" value="{{ $personel->id }}">
                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_payroll_sum">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion accordion-icon-collapse">
        <div class="accordion-item border-0 bg-secondary-crm">
            <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_payroll_personal">
                <div class="d-flex">
                    <div class="symbol symbol-40px me-5">
                        <div class="symbol-label bg-light-primary">
                            <i class="fi fi-sr-comment-user fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="fs-4 fw-semibold mb-0">Personal Account</h3>
                        <span>Personal bank account</span>
                    </div>
                </div>
                <span class="accordion-icon">
                    <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                    <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                </span>
            </div>
            <div id="kt_payroll_personal" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#tab_general_primary">
                <div class="accordion-body">
                    <form action="{{ route('personel.employee_table.update_data') }}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="fv-row col-6">
                                <label class="col-form-label">Bank Name</label>
                                <select name="bank_name" class="form-select" data-control="select2" data-placeholder="Select Bank Name">
                                    <option value=""></option>
                                    @foreach ($banks as $item)
                                        <option value="{{ $item->id }}" {{ $personel->personal_bank_id == $item->id ? "SELECTED" : "" }}>{{ sprintf("%03d", $item->id)."-".$item->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Account Number</label>
                                <input type="text" name="account_number" value="{{ $personel->personal_bank_number }}" class="form-control" placeholder="Input Account Number">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Account Name</label>
                                <input type="text" name="account_name" value="{{ $personel->personal_bank_name }}" class="form-control" placeholder="Input Account Name">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-5">
                            @csrf
                            <input type="hidden" name="type" value="payroll_personal">
                            <input type="hidden" name="id" value="{{ $personel->id }}">
                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_payroll_personal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion accordion-icon-collapse">
        <div class="accordion-item border-0 bg-secondary-crm">
            <div class="accordion-header py-3 px-5 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_payroll_company">
                <div class="d-flex">
                    <div class="symbol symbol-40px me-5">
                        <div class="symbol-label bg-light-primary">
                            <i class="fi fi-sr-briefcase fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <h3 class="fs-4 fw-semibold mb-0">Company Account</h3>
                        <span>Company bank account</span>
                    </div>
                </div>
                <span class="accordion-icon">
                    <i class="fs-3 fi fi-rr-caret-down text-dark accordion-icon-off"></i>
                    <i class="fs-3 fi fi-rr-caret-up accordion-icon-on"></i>
                </span>
            </div>
            <div id="kt_payroll_company" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#tab_general_primary">
                <div class="accordion-body">
                    <form action="{{ route('personel.employee_table.update_data') }}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="fv-row col-12">
                                <label class="col-form-label">Bank Name</label>
                                <select name="bank_name" class="form-select" data-control="select2" data-placeholder="Select Bank Name">
                                    <option value=""></option>
                                    @foreach ($banks as $item)
                                        <option value="{{ $item->id }}" {{ $personel->company_bank_id == $item->id ? "SELECTED" : "" }}>{{ sprintf("%03d", $item->id)."-".$item->bank_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Account Number</label>
                                <input type="text" name="account_number" value="{{ $personel->company_bank_number }}" class="form-control" placeholder="Input Account Number">
                            </div>
                            <div class="fv-row col-6">
                                <label class="col-form-label">Company Bank Account</label>
                                <input type="text" name="account_name" value="{{ $personel->company_bank_name }}" class="form-control" placeholder="Input Company Bank Account">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-5">
                            @csrf
                            <input type="hidden" name="type" value="payroll_company">
                            <input type="hidden" name="id" value="{{ $personel->id }}">
                            <button type="button" class="btn text-primary" data-bs-toggle="collapse" data-bs-target="#kt_payroll_company">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
