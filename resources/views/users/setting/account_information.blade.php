<div class="card card-custom">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("Informasi Akun") }}</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            <button type="submit" form="form-account" class="btn btn-light-primary">Simpan Perubahan</button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded-top">
        <form action="{{ route('account.setting.update_profile') }}" method="post" id="form-account">
            @csrf
            <input type="hidden" name="type" value="account">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="email" class="col-form-label required">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="email" class="col-form-label required">Email</label>
                        <input type="text" name="email" id="email" class="form-control" disabled value="{{ $user->email }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="email" class="col-form-label">Nomor Handphone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="email" class="col-form-label">NIK / No. Passport / NPWP</label>
                        <input type="text" name="about" id="about" class="form-control" value="{{ $user->about }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="email" class="col-form-label">Alamat Lengkap</label>
                        <textarea name="address" id="address" cols="30" rows="10" class="form-control">{{ $user->address }}</textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom mt-5">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("Foto Profile") }}</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            @csrf
            <button type="button" id="btn-change-profile" class="btn btn-light-primary btn-update-profile">Simpan Perubahan </button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded">
        <form action="{{ route('account.setting.update_profile') }}" enctype="multipart/form-data" method="post" id="form-profile" class="form">
            @csrf
            <input type="hidden" name="type" value="image">
            <div class="fv-row">
                <!--begin::Image input-->
                <div class="image-input image-input-empty border" data-kt-image-input="true">
                    <!--begin::Image preview wrapper-->
                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset($user->user_img ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                    <!--end::Image preview wrapper-->

                    <!--begin::Edit button-->
                    <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change"
                    data-bs-toggle="tooltip"
                    data-bs-dismiss="click"
                    title="Ganti foto profile">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="profile_img" accept=".png, .jpg, .jpeg" />
                        <input type="hidden" name="profile_img_remove" />
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="cancel"
                    data-bs-toggle="tooltip"
                    data-bs-dismiss="click"
                    title="Cancel">
                        <i class="ki-outline ki-cross fs-3"></i>
                    </span>
                    <!--end::Cancel button-->
                </div>
                <!--end::Image input-->
            </div>
        </form>
    </div>
</div>

<div class="card card-custom mt-5">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("Informasi Bank (Optional)") }}</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            <button type="submit" form="form-bank" class="btn btn-light-primary">Simpan Perubahan</button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded-top">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="bank_name" class="col-form-label">Bank</label>
                    <input form="form-bank" type="text" name="bank_name" id="bank_name" class="form-control" value="{{ $user->bank_name }}">
                </div>
                <div class="form-group">
                    <label for="rek_no" class="col-form-label">No. Rekening</label>
                    <input form="form-bank" type="text" name="rek_no" id="rek_no" class="form-control" value="{{ $user->rek_no }}">
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('account.setting.update_profile') }}" method="post" id="form-bank">
    @csrf
    <input type="hidden" name="type" value="bank">
</form>