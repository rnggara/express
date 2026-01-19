<div class="fv-row">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
</div>
<div class="d-flex justify-content-center mb-10">
    <button type="button" class="btn p-0 text-primary">
        <i class="la la-plus text-primary"></i>
        Add address
    </button>
</div>

<div class="card">
    <div class="card-body">
        <div class="fv-row">
            <label for="" class="col-form-label fs-3">Add Address</label>
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">Address Title</label>
            <input type="text" class="form-control" readonly placeholder="Input address title">
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">Full address</label>
            <input type="text" class="form-control" readonly placeholder="Input Full address">
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">Country</label>
            <input type="text" class="form-control" readonly placeholder="Input Country">
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">Province</label>
            <input type="text" class="form-control" readonly placeholder="Input Province">
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">City</label>
            <input type="text" class="form-control" readonly placeholder="Input City">
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">Subdistrict</label>
            <input type="text" class="form-control" readonly placeholder="Input Subdistrict">
        </div>
        <div class="fv-row mb-5">
            <label for="" class="col-form-label">Postal Code</label>
            <input type="text" class="form-control" readonly placeholder="Input Postal Code">
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn text-dark">Cancel</button>
            <button type="button" class="btn btn-primary">Submit</button>
        </div>
    </div>
</div>
