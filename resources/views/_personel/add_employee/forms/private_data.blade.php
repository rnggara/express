<div class="row">
    <div class="fv-row col-6">
        <label class="col-form-label">Select Identity</label>
        <select name="identity_type" class="form-select" data-control="select2">
            <option value="ktp">KTP</option>
            <option value="sim">SIM</option>
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Identity Number</label>
        <input type="text" name="identity_number" class="form-control" placeholder="Input Identity Number">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Upload Identity Document</label>
        <div class="d-flex align-items-center">
            <label class="btn btn-secondary btn-sm">
                <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="file" class="d-none">
                Attachment
                <i class="fi fi-rr-clip"></i>
            </label>
            <span class="text-primary ms-5" data-file></span>
        </div>
        <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Place of birth</label>
        <input type="text" name="emp_tmpt_lahir" class="form-control" placeholder="Input Place of Birth">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Date of Birth</label>
        <input type="date" name="emp_lahir" placeholder="" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Citizenship</label>
        <select name="citizenship" class="form-select" data-control="select2" data-placeholder="Select Citizenship">
            <option value=""></option>
            @foreach ($countries as $item)
                <option value="{{ $item->name }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Marital Status</label>
        <select name="marital_status" class="form-select" data-control="select2" data-placeholder="Select Marital Status">
            <option value=""></option>
            @foreach ($marital_status as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Religion</label>
        <select name="religion" class="form-select" data-control="select2" data-placeholder="Select Religion">
            <option value=""></option>
            @foreach ($religion as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Sex</label>
        <select name="gender" class="form-select" data-control="select2" data-placeholder="Select Gender">
            <option value=""></option>
            @foreach ($gender as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Blood Type</label>
        <select name="blood_type" class="form-select" data-control="select2" data-placeholder="Select Blood Type">
            <option value=""></option>
            @foreach ($blood_type as $item)
                <option value="{{ $item->id }}">{{ $item->label }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Height</label>
        <div class="position-relative">
            <input type="text" name="height" placeholder="" class="form-control pe-13">
            <span class="position-absolute end-0 top-25 me-5">cm</span>
        </div>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Weight</label>
        <div class="position-relative">
            <input type="text" name="weight" placeholder="" class="form-control pe-13">
            <span class="position-absolute end-0 top-25 me-5">Kg</span>
        </div>
    </div>
    <div class="fv-row col-12">
        <label class="col-form-label">Identity Address</label>
        <textarea name="identity[address]" id="" cols="30" class="form-control" rows="5"></textarea>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Zip Code</label>
        <input type="text" name="identity[zip_code]" placeholder="Input Zip Code" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Country</label>
        <input type="text" name="identity[country]" placeholder="Input Country" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Province</label>
        <input type="text" name="identity[province]" placeholder="Input Province" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">City</label>
        <input type="text" name="identity[city]" placeholder="Input City" class="form-control">
    </div>
    <div class="fv-row">
        <div class="form-check col-form-label">
            <span class="form-check-label">
                Resident address same with identity address
                <input class="form-check-input" name="resident_identity" type="checkbox" value="1" id="" />
            </span>
        </div>
    </div>
    <div class="fv-row col-12">
        <label class="col-form-label">Residential Address</label>
        <textarea name="resident[address]" id="" cols="30" class="form-control" rows="5"></textarea>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Zip Code</label>
        <input type="text" name="resident[zip_code]" placeholder="Input Zip Code" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Country</label>
        <input type="text" name="resident[country]" placeholder="Input Country" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Province</label>
        <input type="text" name="resident[province]" placeholder="Input Province" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">City</label>
        <input type="text" name="resident[city]" placeholder="Input City" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Personal Email</label>
        <input type="text" name="personal_email" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">NPWP</label>
        <input type="text" name="npwp" placeholder="" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Upload NPWP</label>
        <div class="d-flex align-items-center">
            <label class="btn btn-secondary btn-sm">
                <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_npwp" class="d-none">
                Attachment
                <i class="fi fi-rr-clip"></i>
            </label>
            <span class="text-primary ms-5" data-file></span>
        </div>
        <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">BPJS Kesehatan</label>
        <input type="text" name="bpjs_kes" placeholder="" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Upload BPJS Kesehatan</label>
        <div class="d-flex align-items-center">
            <label class="btn btn-secondary btn-sm">
                <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_bpjs_kes" class="d-none">
                Attachment
                <i class="fi fi-rr-clip"></i>
            </label>
            <span class="text-primary ms-5" data-file></span>
        </div>
        <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">BPJS TK</label>
        <input type="text" name="bpjs_tk" placeholder="" class="form-control">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Upload BPJS TK</label>
        <div class="d-flex align-items-center">
            <label class="btn btn-secondary btn-sm">
                <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="upload_bpjs_tk" class="d-none">
                Attachment
                <i class="fi fi-rr-clip"></i>
            </label>
            <span class="text-primary ms-5" data-file></span>
        </div>
        <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
    </div>
</div>
