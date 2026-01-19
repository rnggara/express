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
                    <div class="form-group">
                        <label for="" class="col-form-label">Disease</label>
                        <input type="text" data-label name="descriptions[]" class="form-control" placeholder="Input disease">
                    </div>
                </div>
                <div class="fv-row col-6">
                    <div class="form-group">
                        <label for="" class="col-form-label">Historical year</label>
                        <select name="year[]" data-placeholder="Select year" data-control="select2" data-hide-search="true" class="form-select">
                            <option value=""></option>
                            @for ($item = date("Y") - 10; $item <= date("Y"); $item++)
                                <option value="{{ $item }}" {{ date("Y") == $item ? "SELECTED" : "" }}>{{ $item }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="fv-row col-6">
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
