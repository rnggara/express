<form action="{{ route("cms.employer.update") }}" method="post" class="form" id="form-post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-12 mb-8">
            <div class="fv-row">
                <label for="title" class="col-form-label w-100">Logo</label>
                <!--begin::Image input-->
                <div class="image-input image-input-outline" data-kt-image-input="true">
                    <!--begin::Image preview wrapper-->
                    <div class="bgi-position-center h-150px image-input-wrapper w-150px" style="background-size: contain; background-image: url({{ asset($lp->logo ?? \Config::get("constants.APP_LOGO")) }})"></div>
                    <!--end::Image preview wrapper-->

                    <!--begin::Edit button-->
                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change"
                    data-bs-toggle="tooltip"
                    data-bs-dismiss="click"
                    title="Change img">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
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
        </div>
        <div class="col-12 mb-8">
            <div class="fv-row">
                <label for="title" class="col-form-label w-100">Favicon</label>
                <!--begin::Image input-->
                <div class="image-input image-input-outline" data-kt-image-input="true">
                    <!--begin::Image preview wrapper-->
                    <div class="bgi-position-center h-150px image-input-wrapper w-150px" style="background-size: contain; background-image: url({{ asset($lp->favicon ?? \Config::get("constants.APP_ICON")) }})"></div>
                    <!--end::Image preview wrapper-->

                    <!--begin::Edit button-->
                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change"
                    data-bs-toggle="tooltip"
                    data-bs-dismiss="click"
                    title="Change img">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="favicon" accept=".png, .jpg, .jpeg" />
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
        </div>
        <div class="col-12 mb-8">
            <div class="fv-row">
                <label for="app_name" class="required form-label">Application Name</label>
                <input type="text" name="app_name" id="app_name" class="form-control form-input" value="{{ \Config::get("constants.APP_NAME") }}">
            </div>
        </div>
        <div class="col-12 mb-8">
            <div class="fv-row">
                <label for="company_name" class="required form-label">Company Name</label>
                <input type="text" name="company_name" id="company_name" class="form-control form-input" value="{{ $lp->company_name }}">
            </div>
        </div>
        <div class="col-12 mb-8">
            <div class="fv-row">
                <label for="app_name" class="required form-label">Branding Color Primary</label>
                <div class="input-group">
                    <input type="color" name="branding_color_primary" id="branding_color_primary" class="form-control form-input h-50px" value="{{ $lp->branding_color ?? '#fb8500' }}">
                    <input type="text" name="branding_color_primary_hex" id="branding_color_primary_hex" class="form-control form-input" value="{{ $lp->branding_color ?? '#fb8500' }}" maxlength="7">
                </div>
            </div>
        </div>
        <div class="col-12 mb-8">
            <div class="fv-row">
                <label for="app_name" class="required form-label">Branding Color Accent</label>
                <div class="input-group">
                    <input type="color" name="branding_color_accent" id="branding_color_accent" class="form-control form-input h-50px" value="{{ $lp->branding_color_accent ?? '#ffcd96' }}">
                    <input type="text" name="branding_color_accent_hex" id="branding_color_accent_hex" class="form-control form-input" value="{{ $lp->branding_color_accent ?? '#ffcd96' }}" maxlength="7">
                </div>
            </div>
        </div>
        <div class="col-12 text-right">
            @csrf
            <input type="hidden" name="type" value="general">
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">
                    Simpan
                </span>
                <span class="indicator-progress">
                    Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
</form>


@section('scripts')
    <script>
        init_validation("form-post")
        Inputmask({
            "mask" : "9999-9999-9999"
        }).mask("#wa_no");

        function colorpicker(name){
            document.getElementById(name).addEventListener('input', function() {
                document.getElementById(name + '_hex').value = this.value;
            });

            document.getElementById(name + '_hex').addEventListener('input', function() {
                if(/^#[0-9A-F]{6}$/i.test(this.value)) {
                    document.getElementById(name).value = this.value;
                }
            });
        }

        $(document).ready(function(){
            colorpicker("branding_color_primary")
            colorpicker("branding_color_accent")
        })

    </script>
@endsection
