<form action="{{ route("cms.applicant.update") }}" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="p-3 col-12 mb-3">
            <div class="form-group">
                <label for="test_id" class="col-form-label">Title</label>
                <input type="text" class="form-control" name="mk_title" value="{{ $lp["mk_title"] ?? "" }}">
            </div>
            <div class="form-group">
                <label for="test_desc" class="col-form-label">Deskripsi</label>
                <input type="text" class="form-control" name="mk_subtitle" value="{{ $lp["mk_subtitle"] ?? "" }}">
            </div>
        </div>
        @for($i = 1; $i <= 3; $i++)
            <div class="col-12 mb-8">
                <h3>Section {{ $i }}</h3>
                <div class="border p-5 rounded">
                    <div class="form-group">
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset($lp["mk_img$i"] ?? "") }})"></div>
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
                    <div class="form-group">
                        <label for="title" class="col-form-label">Judul</label>
                        <input type="text" name="title[{{ $i }}]" id="title" class="form-control" value="{{ $lp["mk_title$i"] ?? "" }}" required>
                    </div>
                    <div class="form-group">
                        <label for="content" class="col-form-label">Konten</label>
                        <textarea name="content[{{ $i }}]" id="content" class="form-control" cols="30" rows="10" required>{!! $lp["mk_content$i"] ?? "" !!}</textarea>
                    </div>
                </div>
                <div class="separator separator-solid my-8"></div>
            </div>
        @endfor
        <div class="col-12 text-right">
            @csrf
            <input type="hidden" name="type" value="mk">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
