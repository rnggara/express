<div class="fv-row">
    <label for="" class="col-form-label" name="{{ $form_name ?? "" }}" {{ $readonly ?? "readonly" }}>{{ ucwords($name ?? "Property name") }}</label>
    <div class="upload-file">
        <label for="attachment" data-toggle="upload_file"
            class="btn btn-secondary btn-sm">
            Attachment
            <i class="fa fa-paperclip"></i>
        </label>
        <span class="upload-file-label">Max 1 mb</span>
        <input id="attachment" style="display: none" data-toggle="upload_file"
            name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
    </div>
</div>
