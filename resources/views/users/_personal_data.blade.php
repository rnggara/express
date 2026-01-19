<form action="{{ route("account.edit.about") }}" method="post">
    <div class="card card-custom bg-transparent">
        <!--begin::Header-->
        <div class="card-header border-0 py-3">
            <div class="card-title align-items-start flex-column">
                <h3 class="card-label font-weight-bolder text-dark">{{ __("user.personal_data") }}</h3>
                <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
            </div>
            <div class="card-toolbar">
            </div>
        </div>
        <!--end::Header-->
        <div class="card-body bg-white rounded border">
            @if (empty($profile))
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="personal_data">
                            <i class="ki-outline ki-plus-circle fs-2 text-primary"></i>
                            Tambahkan {{ __("user.personal_data") }}
                        </button>
                    </div>
                </div>
            @else
            @if ($data['act'] == "profile")
            <div class="d-flex justify-content-end">
                <div class="row flex-fill">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Nama Lengkap</label>
                                    <span>{{ $profile->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Jenis Kelamin</label>
                                    <span>{{ $profile->gender }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Tanggal Lahir</label>
                                    <span>@dateId($profile->birth_date)</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Nomor Telepon</label>
                                    <span>{{ $profile->phone }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Email</label>
                                    <span>{{ $profile->email }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Status Pernikahan</label>
                                    <span>{{ $profile->marital_status }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Agama</label>
                                    <span>{{ $profile->religion }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Provinsi</label>
                                    <span>{{ $data['prov']->where("id", $profile->prov_id)->first()->name ?? "-" }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Kota</label>
                                    <span>{{ $data['city']->where("id", $profile->city_id)->first()->name ?? "-" }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Alamat</label>
                                    <span>{{ $profile->address }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="d-flex flex-column">
                                    <label for="" class="col-form-label">Gaji yang diharapkan</label>
                                    <span>Rp. {{ number_format($profile->salary_expect ?? 0, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-baseline">
                    <a href="{{ route("account.info")."?v=$v&act=edit" }}" class="btn btn-link me-5">
                        <i class="fa fa-edit text-dark"></i>
                    </a>
                    <button type="button" class="btn btn-link" onclick="modalShow(this)" data-name="personal_data" data-id="{{ $profile->id }}" data-act="delete">
                        <i class="fa fa-trash text-danger"></i>
                    </button>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" required value="{{ $profile->name ?? $user->name }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Jenis Kelamin</label>
                                <select name="gender" class="form-control" data-control="select2" data-hide-search="true" required>
                                    <option value="">Select</option>
                                    @foreach ($data['gender'] as $item)
                                        <option value="{{ $item->name }}" {{ !empty($profile) && $profile->gender == $item->name ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Tanggal Lahir</label>
                                <div class="input-group" id="inputDates" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input id="inputDates_input" readonly name="birth_date" value="{{ empty($profile->birth_date) ? "" : date('d-m-Y', strtotime($profile->birth_date)) }}" type="text" class="form-control" data-td-target="#inputDates"/>
                                    <span class="input-group-text" data-td-target="#inputDates" data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Nomor Telepon</label>
                                <input type="text" class="form-control phone" name="phone" required value="{{ $profile->phone ??$user->phone }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Email</label>
                                <input type="email" name="email" class="form-control" required value="{{ $profile->email ?? $user->email }}">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Status Pernikahan</label>
                                <select name="marital_status" class="form-control" data-control="select2" data-hide-search="true" required>
                                    <option value="">Select</option>
                                    @foreach ($data['marital_status'] as $item)
                                        <option value="{{ $item->name }}" {{ !empty($profile) && $profile->marital_status == $item->name ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Agama</label>
                                <select name="religion" class="form-control" data-control="select2" data-hide-search="true" required>
                                    <option value="">Select</option>
                                    @foreach ($data['religion'] as $item)
                                        <option value="{{ $item->name }}" {{ !empty($profile) && $profile->religion == $item->name ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Kota</label>
                                <select name="city_id" class="form-control" data-control="select2" required>
                                    <option value="">Select</option>
                                    @foreach ($data['city'] as $item)
                                        <option value="{{ $item->id }}" data-prov="{{ $item->prov_id }}" {{ !empty($profile) && $profile->city_id == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="" class="col-form-label required">Provinsi</label>
                                <select name="prov_id" class="form-control" data-control="select2" required>
                                    <option value="">Select</option>
                                    @foreach ($data['prov'] as $item)
                                        <option value="{{ $item->id }}" {{ !empty($profile) && $profile->prov_id == $item->id ? "selected" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label required">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" id="" cols="30" rows="10">{!! $profile->address ?? "" !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-form-label">Gaji yang diharapkan</label>
                        <input type="number" name="salary_expect" class="form-control" value="{{ number_format($profile->salary_expect ?? 0, 0, ".", "") }}">
                    </div>
                </div>
            </div>
            @endif
            @endif
            <!--begin::Heading-->
        </div>
        @if (!empty($profile) && $data['act'] == "edit")
        <div class="card-footer border-0 text-right">
            @csrf
            <div class="d-flex justify-content-end">
                <a href="{{ route("account.info")."?v=$v" }}" class="btn btn-outline btn-outline-primary me-5">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
        @endif
    </div>
</form>
