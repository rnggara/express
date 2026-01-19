<form action="{{ route("cms.applicant.update") }}" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="title" class="col-form-label w-100">Big Image</label>
                <!--begin::Image input-->
                <div class="image-input image-input-outline" data-kt-image-input="true">
                    <!--begin::Image preview wrapper-->
                    <div class="image-input-wrapper h-750px w-900px" style="background-image: url({{ asset($lp["sec_img"] ?? "") }})"></div>
                    <!--end::Image preview wrapper-->

                    <!--begin::Edit button-->
                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change"
                    data-bs-toggle="tooltip"
                    data-bs-dismiss="click"
                    title="Change img">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="img" accept=".png, .jpg, .jpeg" />
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="cancel"
                    data-bs-toggle="tooltip"
                    data-bs-dismiss="click"
                    title="Cancel avatar">
                        <i class="ki-outline ki-cross fs-3"></i>
                    </span>
                    <!--end::Cancel button-->
                </div>
                <!--end::Image input-->
            </div>
            <div class="border p-3">
                <div class="form-group">
                    <label for="test_id" class="col-form-label">Title</label>
                    <input type="text" class="form-control" name="sec_title" value="{{ $lp["sec_title"] ?? "" }}">
                </div>
                <div class="form-group">
                    <label for="test_desc" class="col-form-label">Deskripsi</label>
                    <input type="text" class="form-control" name="sec_desc" value="{{ $lp["sec_desc"] ?? "" }}">
                </div>
            </div>
        </div>
        <div class="col-12 text-right">
            @csrf
            <input type="hidden" name="type" value="sec">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
