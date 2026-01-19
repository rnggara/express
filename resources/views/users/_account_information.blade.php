<!--begin::About-->
<div class="card card-custom">
    <!--begin::Header-->
    <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Data personal</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            <button type="button" onclick="modalShow(this)" data-name="about"
                class="btn btn-circle btn-hover-bg-secondary btn-icon mr-2">
                <i class="fa fa-pen"></i>
            </button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body">
        <!--begin::Heading-->
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="" class="col-form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required value="{{ $user->name }}">
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Email</label>
                            <input type="email" name="email" class="form-control" required value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Nomor Telepon</label>
                            <input type="text" class="form-control phone" required value="{{ $user->phone }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Gender</label>
                            <input type="email" name="email" class="form-control" required value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Tanggal Ulang Tahun</label>
                            <input type="date" class="form-control" required value="{{ $user->phone }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Status Pernikahan</label>
                            <input type="email" name="email" class="form-control" required value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Agama</label>
                            <input type="date" class="form-control" required value="{{ $user->phone }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Kota</label>
                            <input type="email" name="email" class="form-control" required value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Provinsi</label>
                            <input type="date" class="form-control" required value="{{ $user->phone }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-form-label">Alamat Lengkap</label>
                    <textarea name="address" class="form-control" id="" cols="30" rows="10"></textarea>
                </div>
                <div class="form-group">
                    <label for="" class="col-form-label">Ekpekasti Gaji</label>
                    <input type="text" class="form-control" value="{{ $user->salary_expect ?? 0 }}">
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::About-->
<!--begin::About-->
<div class="card card-custom mt-5">
    <!--begin::Header-->
    <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">About</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            <button type="button" onclick="modalShow(this)" data-name="about"
                class="btn btn-circle btn-hover-bg-secondary btn-icon mr-2">
                <i class="fa fa-pen"></i>
            </button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body">
        <!--begin::Heading-->
        <div class="row">
            <div class="col-12">
                <p>{!! substr($user->about, 0, 255) !!}<span id="dot">...</span><span id="more" style="display: none">{!! substr($user->about, 255) !!}</span><a href="javascript:;" onclick="readMore(this)">Read more</a></p>
            </div>
        </div>
    </div>
</div>
<!--end::About-->
{{-- being::Experience --}}
<div class="card card-custom mt-5">
    <!--begin::Header-->
    <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Experience</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            <button type="button" onclick="modalShow(this)" data-name="experience"
                class="btn btn-circle btn-hover-bg-secondary btn-icon mr-2">
                <i class="fa fa-plus"></i>
            </button>
            <button type="button" onclick="modalShow(this)" data-name="experience"
                class="btn btn-circle btn-hover-bg-secondary btn-icon mr-2">
                <i class="fa fa-pen"></i>
            </button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body">
        <!--begin::Heading-->
        <div class="row">
            <div class="col-12">

            </div>
        </div>
    </div>
</div>
{{-- end::Experience --}}
{{-- being::Education --}}
<div class="card card-custom mt-5">
    <!--begin::Header-->
    <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Education</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            <button type="button" onclick="modalShow(this)" data-name="education"
                class="btn btn-circle btn-hover-bg-secondary btn-icon mr-2">
                <i class="fa fa-plus"></i>
            </button>
            <button type="button" onclick="modalShow(this)" data-name="education"
                class="btn btn-circle btn-hover-bg-secondary btn-icon mr-2">
                <i class="fa fa-pen"></i>
            </button>
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body">
        <!--begin::Heading-->
        <div class="row">
            <div class="col-12">

            </div>
        </div>
    </div>
</div>
{{-- end::Education --}}
