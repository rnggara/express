<div class="card" style="background-color: var(--bs-page-bg)">
    <div class="card-header">
        <h3 class="card-title">Detail File Information</h3>
    </div>
    <div class="card-body">
        <div class="fv-row">
            <label for="" class="col-form-label required">File Name</label>
            <input type="text" name="file_name" class="form-control" required value="{{ $detail->file_name }}" id="">
        </div>
        <div class="fv-row upload-file">
            <label for="file" class="col-form-label required w-100">Upload File</label>
            <label for="add-file" data-toggle="upload_file"
                class="btn btn-outline btn-outline-primary btn-sm">
                <i class="fa fa-file"></i>
                Attachment
            </label>
            <span class="upload-file-label">Max 25 mb</span>
            <input id="add-file" style="display: none" data-toggle="upload_file"
                name="attachment" accept=".jpg, .png, .pdf" type="file" />
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">File URL</label>
            <input type="text" name="file_url" class="form-control" value="{{ $detail->file_url ?? "" }}" placeholder="Input File URL">
        </div>
        <hr>
        <div class="fv-row">
            <label for="pic_names-dt" class="col-form-label ">Related Opportunity</label>
            <div class="position-relative">
                <input type="text" class="form-control find-opportunity pe-15" data-multiple="false" data-name='opportunity_id' {{ $opportunity->count() > 0 ? "disabled" : "" }} placeholder="Select or Add Opportunity">
                <div class="find-result"></div>
                <div class="find-noresult"></div>
                <div class="find-add">
                    @if ($opportunity->count() > 0)
                            @foreach ($opportunity as $item)
                            <div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 opportunity-item">
                                <div class="d-flex align-items-center">
                                    <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                    <span>{{ $item->leads_name }}</span>
                                    <input type="hidden" name="opportunity_id" value="{{ $item->id }}">
                                </div>
                                <button type="button" onclick="removeOpporunity(this, {{ $item->id }})" data-multiple="false" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                    <i class="fi fi-rr-trash"></i>
                                </button>
                            </div>
                            @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Opportunity">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <hr>
        <div class="fv-row mt-5">
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
        </div>
    </div>
</div>
