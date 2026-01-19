<form action="{{ route("cms.employer.update") }}" method="post" enctype="multipart/form-data">
    <div class="row">
        @for($i = 1; $i <= 5; $i++)
            <div class="col-md-12 col-sm-12 mb-8">
                <h3>Section {{ $i }}</h3>
                <div class="">
                    <div class="form-group">
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-450px h-150px" style="background-image: url({{ asset($lp["partner$i"] ?? "") }})"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Change img">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="img[{{ $i }}]" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="img_remove[{{ $i }}]" />
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

                            <!--begin::Remove button-->
                            <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Remove image">
                                <i class="ki-outline ki-cross fs-3"></i>
                            </span>
                            <!--end::Remove button-->
                        </div>
                        <!--end::Image input-->
                    </div>
                </div>
                <div class="separator separator-solid my-8"></div>
            </div>
        @endfor
        <div class="col-12 text-right">
            @csrf
            <input type="hidden" name="type" value="partner">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
