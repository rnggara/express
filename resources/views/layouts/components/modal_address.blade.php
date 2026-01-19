<div class="modal fade" tabindex="-1" id="modalAddAddress">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body px-15 py-10">
                <div class="d-flex mb-5 align-items-center">
                    <span class="fs-3 fw-bold">Add Address</span>
                </div>
                <div class="py-5 rounded mb-5">
                    <div class="fv-row" id="sel-add-address">
                        <label class="col-form-label">Address Title</label>
                        <select name="title" class="form-select" data-control="select2" data-dropdown-parent="#sel-add-address" data-placeholder="Input Address Title">
                            <option value=""></option>
                            <option value="Head Office">Head Office</option>
                            <option value="Branch Office">Branch Office</option>
                            <option value="Warehouse">Warehouse</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="text" name="title_other" class="form-control d-none mt-3" placeholder="Input Full Name">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Full Address</label>
                        <input type="text" name="full_address" class="form-control" placeholder="Input Full Address">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Postal Code</label>
                        <div class="position-relative">
                            <input type="text" name="postal_code" class="form-control pe-17" placeholder="Input Postal Code">
                            <button type="button" data-toggle='pos' class="btn position-absolute top-0 btn-icon end-0">
                                <i class="fi fi-rr-search text-primary"></i>
                            </button>
                        </div>
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Country</label>
                        <input type="text" name="country" class="form-control" placeholder="Input Country">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Province</label>
                        <input type="text" name="province" class="form-control" placeholder="Input Province">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">City</label>
                        <input type="text" name="city" class="form-control" placeholder="Input City">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Subdistrict</label>
                        <input type="text" name="subdistrict" class="form-control" placeholder="Input Subdistrict">
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    @csrf
                    <button type="button" class="btn btn-white me-5" data-bs-dismiss="modal">Batal</button>
                    <button type="button" data-button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
