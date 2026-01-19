<div class="card card-custom">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("Kata Sandi") }}</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            @csrf
            <button type="button" id="btn-change-password" class="btn btn-light-primary">Simpan Perubahan</button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded-top">
        <form action="{{ route('account.setting.change_password') }}" method="post" id="form-password" class="form">
            @csrf
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="fv-row">
                        <label for="password" class="col-form-label required">Kata Sandi Lama</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <div class="fv-row">
                        <label for="password_confirmation" class="col-form-label required">Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
