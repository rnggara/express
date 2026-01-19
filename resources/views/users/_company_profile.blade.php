<form action="{{ route("account.comp.store") }}" method="post" enctype="multipart/form-data">
    @php
        $company = $data['company'];
    @endphp
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header py-3">
            <div class="card-title align-items-start flex-column">
                <h3 class="card-label font-weight-bolder text-dark">{{ __("user.company_profile") }}</h3>
                <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <!--end::Header-->
        <div class="card-body">
            <!--begin::Heading-->
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="" class="col-form-label">Nama Perusahaan</label>
                        <input type="text" name="company_name" class="form-control" required value="{{ $company->company_name ?? "" }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label">Email</label>
                                <input type="email" name="email" class="form-control" required value="{{ $company->email ?? "" }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label">Nomor Telepon</label>
                                <input type="text" class="form-control phone" name="phone" required value="{{ $company->phone ??"" }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label">Kota</label>
                                <select name="city_id" class="form-control" data-control="select2" required>
                                    <option value="">Select</option>
                                    @foreach ($data['city'] as $item)
                                        <option value="{{ $item->id }}" data-prov="{{ $item->prov_id }}" {{ !empty($company) && $company->city_id == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label">Provinsi</label>
                                <select name="prov_id" class="form-control" data-control="select2" required>
                                    <option value="">Select</option>
                                    @foreach ($data['prov'] as $item)
                                        <option value="{{ $item->id }}" {{ !empty($company) && $company->prov_id == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" id="" cols="30" rows="10">{!! $company->address ?? "" !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label">Deskripsi</label>
                        <textarea name="descriptions" class="form-control" id="" cols="30" rows="10">{!! $company->descriptions ?? "" !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label">Industry</label>
                        <select name="industry_id" class="form-control" data-control="select2" required>
                            <option value="">Select</option>
                            @foreach ($data['industry'] as $item)
                                <option value="{{ $item->id }}" {{ !empty($company) && $company->industry_id == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label w-100">Logo Perusahaan</label>
                        @php
                            $logo_uri = asset("theme/assets/media/svg/avatars/blank.svg");
                            if(!empty($data['company'])){
                                $logo_uri = asset("public_html/".$data['company']->icon);
                            }
                        @endphp
                        <!--begin::Image input-->
                        <div class="image-input image-input-circle" data-kt-image-input="true" style="background-image: url({{ $logo_uri }})">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ $logo_uri }})"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="image_logo" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
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
                            title="Remove avatar">
                                <i class="ki-outline ki-cross fs-3"></i>
                            </span>
                            <!--end::Remove button-->
                        </div>
                        <!--end::Image input-->
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            @csrf
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>
