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
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="language" class="col-form-label">Language</label>
                            <select name="language[]" data-label data-placeholder="Select Language" data-control="select2" data-hide-search="true" class="form-control">
                                <option value=""></option>
                                @foreach ($languages as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="writing" class="col-form-label">Writing Ability</label>
                            <select name="writing[]" data-placeholder="Select writing ability" data-control="select2" data-hide-search="true" class="form-select">
                                <option value=""></option>
                                @for ($item = 1; $item <= 5; $item++)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="reading" class="col-form-label">Reading Ability</label>
                            <select name="reading[]" data-placeholder="Select reading ability" data-control="select2" data-hide-search="true" class="form-select">
                                <option value=""></option>
                                @for ($item = 1; $item <= 5; $item++)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="speaking" class="col-form-label">Speaking Ability</label>
                            <select name="speaking[]" data-placeholder="Select speaking ability" data-control="select2" data-hide-search="true" class="form-select">
                                <option value=""></option>
                                @for ($item = 1; $item <= 5; $item++)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
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
