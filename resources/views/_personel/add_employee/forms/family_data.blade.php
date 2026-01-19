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
                <input type="hidden" name="saved[]">
                <div class="fv-row col-6">
                    <label class="col-form-label">Name</label>
                    <input type="text" name="name[]" data-label value="" class="form-control" placeholder="Input Name">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Relation Family</label>
                    <select name="hubungan[]" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Select Relation">
                        <option value=""></option>
                        <option value="Orang Tua">Orang Tua</option>
                        <option value="Saudara">Saudara</option>
                        <option value="Suami">Suami</option>
                        <option value="Istri">Istri</option>
                        <option value="Anak">Anak</option>
                    </select>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Marital Status</label>
                    <select name="marital_id[]" class="form-select" data-control="select2" data-placeholder="Select Marital Status">
                        <option value=""></option>
                        @foreach ($marital_status as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Sex</label>
                    <select name="gender[]" class="form-select" data-control="select2" data-placeholder="Select Gender">
                        <option value=""></option>
                        @foreach ($gender as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Date of birth</label>
                    <input type="date" name="tgl_lahir[]" value="" class="form-control">
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label">Phone Number</label>
                    <input type="text" name="no_telp[]" value="" placeholder="Input Phone Number" class="form-control">
                </div>
                <div class="fv-row col-12">
                    <label class="col-form-label">Upload Document</label>
                    <div class="d-flex align-items-center">
                        <label class="btn btn-secondary btn-sm">
                            <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="lampiran[]" class="d-none">
                            Attachment
                            <i class="fi fi-rr-clip"></i>
                        </label>
                        <span class="text-primary ms-5" data-file></span>
                    </div>
                    <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                </div>
                <div class="fv-row col-12">
                    <div class="form-check">
                        <label class="form-check-label">
                            Add as emergency contact
                            <input class="form-check-input" type="checkbox" name="emergency[]" value="1" />
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer border-0">
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
