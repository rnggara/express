<div class="card card-custom bg-transparent">
    <div class="card-header  border-0">
        <h3 class="card-title">Akun Saya</h3>
    </div>
</div>
@if(\Config::get("constants.PORTAL_STATE") != 3)
<div class="card card-custom mb-8">
    <!--begin::Header-->
    <div class="card-header py-3 border-0 bg-transparent">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("user.account_information") }}</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            <button type="submit" class="btn btn-light-primary btn-update-profile">Simpan Perubahan</button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded">
        <form action="{{ route("account.setting.update_profile") }}" method="post">
            @csrf
            <input type="hidden" name="type" value="profile">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="name" class="col-form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control" disabled value="{{ $user->name }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="position" class="col-form-label required">Posisi</label>
                        <select name="position" id="position" class="form-select" data-control="select2">
                            <option value="HR">HR</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="phone" class="col-form-label">Nomor Telephone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="email" class="col-form-label">Email</label>
                        <input type="text" name="email" id="email" disabled class="form-control" value="{{ $user->email }}">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<div class="card card-custom mb-8">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("Password") }}</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            @csrf
            <button type="button" id="btn-change-password" class="btn btn-light-primary">Simpan Perubahan</button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded">
        <form action="{{ route('account.setting.change_password') }}" method="post" id="form-password" class="form">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="fv-row">
                        <label for="password" class="col-form-label required">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <div class="fv-row">
                        <label for="password_confirmation" class="col-form-label required">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
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
