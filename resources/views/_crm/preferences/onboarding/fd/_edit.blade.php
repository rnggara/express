<ul class="fs-6 mb-5 nav">
    <li class="nav-item">
        <div class="{{ $form->form_type == "upload_file" ? "active" : "" }} nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_1_edit">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="form_type" value="upload_file" {{ $form->form_type == "upload_file" ? "checked" : "" }}/>
                Upload File
            </label>
        </div>
    </li>
    <li class="nav-item">
        <div class="{{ $form->form_type == "download_file" ? "active" : "" }} nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_2_edit">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="form_type" value="download_file" {{ $form->form_type == "download_file" ? "checked" : "" }}/>
                Download File
            </label>
        </div>
    </li>
    <li class="nav-item">
        <div class="{{ $form->form_type == "task" ? "active" : "" }} nav-link form-check form-check-custom" data-bs-toggle="tab" href="#kt_tab_pane_3_edit">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="form_type" value="task" {{ $form->form_type == "task" ? "checked" : "" }}/>
                Task
            </label>
        </div>
    </li>
</ul>

<div class="tab-content mt-5" id="myTabContent">
    <input type="hidden" name="id" value="{{ $form->id }}">
    <div class="tab-pane fade {{ $form->form_type == "upload_file" ? "show active" : "" }}" id="kt_tab_pane_1_edit" role="tabpanel">
        <span class="text-muted my-3">Chosing “Upload file” means you ask for candidate to upload specific files you needed for hiring process</span>
        <div class="fv-row">
            <label class="required col-form-label">Document Name</label>
            <input type="text" name="upload[document]" class="form-control" placeholder="Input Document Name" value="{{ $form->form_name }}" required>
        </div>
        <div class="fv-row">
            <label class="col-form-label">Description</label>
            <textarea name="upload[description]" class="form-control" cols="30" rows="5" placeholder="Input Description">{{ $form->descriptions }}</textarea>
        </div>
        <div class="fv-row">
            <label class="col-form-label">File Format</label>
            <select name="upload[file_format]" class="form-select" data-control="select2" data-dropdown-parent="#kt_tab_pane_1_edit" data-placeholder="Select File Format">
                <option value=""></option>
                <option value=".pdf, .jpg, .jpeg, .png" {{ $form->file_format == '.pdf, .jpg, .jpeg, .png' ? "SELECTED" : "" }}>PDF, JPG, JPEG, PNG</option>
                <option value=".docx, .xlsx, .csv, .pptx" {{ $form->file_format == '.docx, .xlsx, .csv, .pptx' ? "SELECTED" : "" }}>DOC, XLS, CSV, PPT</option>
            </select>
        </div>
        <div class="fv-row">
            <label class="col-form-label w-100">Add Example File</label>
            <span class="text-muted">Upload example file for this document, so the user can use it for guidance to fill the document you asked. Or if you need a signature from the candidate, you can upload the document here.</span>
            <div class="d-flex align-items-center">
                <label class="btn btn-secondary">
                    <input type="file" name="upload[attachment]" data-attachment class="d-none">
                    Attachment
                    <i class="fi fi-rr-clip"></i>
                </label>
                <div class="ms-5 text-primary" data-file>
                    {{ $form->file_name }}
                </div>
            </div>
            <span class="text-muted mt-3">File format PDF, JPG, PNG, PPT, xls, doc, pptx File Format</span>
        </div>
        <div class="fv-row">
            <label class="col-form-label">Due Date (days)</label>
            <input type="number" name="upload[due_date]" class="form-control" value="{{ $form->due_date }}" placeholder="Input Day. Ex: 14">
        </div>
    </div>
    <div class="tab-pane fade {{ $form->form_type == "download_file" ? "show active" : "" }}" id="kt_tab_pane_2_edit" role="tabpanel">
        <span class="text-muted my-3">Chosing “Downloadable file” means you ask for candidate to download files that you upload for the information on onboarding process.</span>
        <div class="fv-row">
            <label class="required col-form-label">Document Name</label>
            <input type="text" name="download[document]" class="form-control" value="{{ $form->form_name }}" placeholder="Input Document Name">
        </div>
        <div class="fv-row">
            <label class="col-form-label">Description</label>
            <textarea name="download[description]" class="form-control" cols="30" rows="5" placeholder="Input Description">{{ $form->descriptions }}</textarea>
        </div>
        <div class="fv-row d-flex align-items-center mt-5">
            <div class="nav-link form-check form-check-custom active">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="download[type]" {{ $form->file_format == "file" ? "checked" : "" }} value="file"/>
                    File
                </label>
            </div>
            <div class="nav-link form-check form-check-custom active">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="download[type]" {{ $form->file_format == "link" ? "checked" : "" }} value="link"/>
                    Link
                </label>
            </div>
        </div>
        <div class="fv-row {{ $form->file_format == "file" ? "" : "d-none" }}" data-download-type="file">
            <label class="col-form-label w-100">File *</label>
            <span class="text-muted">Upload example file for this document, so the user can use it for guidance to fill the document you asked. Or if you need a signature from the candidate, you can upload the document here.</span>
            <div class="d-flex align-items-center">
                <label class="btn btn-secondary">
                    <input type="file" name="download[attachment]" data-attachment class="d-none">
                    Attachment
                    <i class="fi fi-rr-clip"></i>
                </label>
                <div class="ms-5 text-primary" data-file>
                    {{ $form->file_name }}
                </div>
            </div>
            <span class="text-muted mt-3">File format PDF, JPG, PNG, PPT, xls, doc, pptx File Format</span>
        </div>
        <div class="fv-row {{ $form->file_format == "link" ? "" : "d-none" }}" data-download-type="link">
            <label class="col-form-label w-100">Link *</label>
            <input type="text" name="download[link]" class="form-control" placeholder="Input Link" value="{{ $form->file_address }}">
        </div>
        <div class="fv-row">
            <label class="col-form-label">Due Date (days)</label>
            <input type="number" name="download[due_date]" class="form-control" value="{{ $form->due_date }}" placeholder="Input Day. Ex: 14">
        </div>
    </div>
    <div class="tab-pane fade {{ $form->form_type == "task" ? "show active" : "" }}" id="kt_tab_pane_3_edit" role="tabpanel">
        <span class="text-muted my-3">Chosing “Task” means you can give task to the employee or candidate.</span>
        <div data-content>
            <div class="fv-row">
                <label class="required col-form-label">Task Name</label>
                <input type="text" name="task[document][]" class="form-control" value="{{ $form->form_name }}" placeholder="Input Document Name">
            </div>
            <div class="fv-row">
                <label class="col-form-label">Description</label>
                <textarea name="task[description][]" class="form-control" cols="30" rows="5" placeholder="Input Description">{{ $form->descriptions }}</textarea>
            </div>
            <div class="fv-row">
                <label class="col-form-label">PIC</label>
                <select name="task[pic][]" class="form-select" data-control="select2" data-dropdown-parent="#kt_tab_pane_3_edit" data-placeholder="Select PIC">
                    <option value=""></option>
                    @foreach ($pic as $item)
                        @if ($item->name != "")
                            <option value="{{ $item->id }}" {{ $form->pic == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="fv-row">
                <label class="col-form-label">Due Date (days)</label>
                <input type="number" name="task[due_date][]" value="{{ $form->due_date }}" class="form-control" placeholder="Input Day. Ex: 14">
            </div>
            <div class="separator my-3"></div>
        </div>
    </div>
</div>
