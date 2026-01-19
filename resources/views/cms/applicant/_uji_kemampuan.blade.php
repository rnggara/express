<form action="{{ route("cms.applicant.update") }}" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="title" class="col-form-label w-100">Big Image</label>
                <!--begin::Image input-->
                <div class="image-input image-input-outline" data-kt-image-input="true">
                    <!--begin::Image preview wrapper-->
                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset($lp["um_img"] ?? "") }})"></div>
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
            @for($i = 1; $i <= 3; $i++)
                <div class="border p-3">
                    <div class="form-group">
                        <label for="test_id" class="col-form-label">Test {{ $i }}</label>
                        <select name="test_id[{{ $i }}]" class="form-control" data-control="select2" required>
                            @foreach ($list_test as $test)
                                <option value="{{ $test->id }}" {{ !empty($lp) && $lp["um_test_id$i"] == $test->id ? "SELECTED" : "" }}>{{ $test->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="test_desc" class="col-form-label">Deskripsi {{ $i }}</label>
                        <input type="text" class="form-control" name="test_desc[{{ $i }}]" value="{{ $lp["um_test_desc$i"] ?? "" }}">
                    </div>
                </div>
            @endfor
        </div>
        <div class="col-12 text-right">
            @csrf
            <input type="hidden" name="type" value="um">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>
