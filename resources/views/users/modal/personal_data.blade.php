@csrf

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
            <div class="input-group" id="inputDatesModal" data-td-target-input="nearest" data-td-target-toggle="nearest">
                <input id="inputDatesModal_input" data-td-toggle="datetimepicker" oncfo name="birth_date" value="{{ empty($profile->birth_date) ? "" : date('d-m-Y', strtotime($profile->birth_date)) }}" type="text" class="form-control tempusDominus" data-td-target="#inputDatesModal"/>
                <span class="input-group-text" data-td-target="#inputDatesModal" data-td-toggle="datetimepicker">
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
