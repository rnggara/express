<div class="d-flex">
    <div class="d-flex flex-column" data-toggle="imageInput">
        <div class="w-300px img-wrapper h-250px rounded bgi-position-center bgi-no-repeat bgi-size-cover" style="background-image: url({{ asset($item->user_img ?? "images/image_placeholder.png") }})"></div>
        <span class="my-3 text-muted text-center">Maximum image size is 5 MB</span>
        <label class="btn btn-primary">
            Upload Photo
            <input type="file" name="image" accept="image/*" class="d-none">
        </label>
    </div>
    <div class="border" style=" margin-left: 12px; margin-right: 12px;"></div>
    <div class="flex-fill">
        <div class="row">
            {{-- <div class="col-6 fv-row">
                <label class="col-form-label required">User ID</label>
                <input type="text" name="user_id" class="form-control" placeholder="Input Employee ID" value="{{ $item->emp->emp_id ?? "" }}" id="">
                @error('user_id')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div> --}}
            <div class="col-6 fv-row">
                <label class="col-form-label required">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Input name Employee" value="{{ $item->name }}" id="">
                @error('name')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-6 fv-row">
                <label class="col-form-label">Departement</label>
                <select name="departement" class="form-select" data-control="select2" data-dropdown-parent="#modal_edit"  data-placeholder="Select Departement" id="">
                    <option value=""></option>
                    @foreach ($departements as $val)
                        <option value="{{ $val->id }}" {{ $item->uac_departement == $val->id ? "SELECTED" : "" }}>{{ $val->name }}</option>
                    @endforeach
                </select>
                @error('departement')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-6 fv-row">
                <label class="col-form-label">Location</label>
                <select name="location" class="form-select" data-control="select2" data-dropdown-parent="#modal_edit" data-placeholder="Select location" id="">
                    <option value=""></option>
                    @foreach ($locations as $val)
                        <option value="{{ $val->id }}" {{ $item->uac_location == $val->id ? "SELECTED" : "" }}>{{ $val->name }}</option>
                    @endforeach
                </select>
                @error('location')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-6 fv-row">
                <label class="col-form-label required">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Input employee email" value="{{ $item->email }}" id="">
                @error('email')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-6 fv-row">
                <label class="col-form-label required">Role</label>
                <select name="role" class="form-select" data-control="select2" data-dropdown-parent="#modal_edit" data-placeholder="Select role" id="">
                    <option value=""></option>
                    @foreach ($roles as $val)
                        <option value="{{ $val->id }}" {{ $item->uac_role == $val->id ? "SELECTED" : "" }}>{{ $val->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-6 fv-row">
                <label class="col-form-label">Password</label>
                <input type="text" name="password" class="form-control" readonly placeholder="Generated Password" value="{{ $item->uac_password }}" id="">
                @error('password')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-primary btn-sm" onclick="generatePassword(this)">Generate Password</button>
                </div>
            </div>
            <div class="col-6 fv-row">
                <label class="col-form-label">Status</label>
                <select name="status" class="form-select" data-control="select2" data-dropdown-parent="#modal_edit" data-placeholder="Active" id="">
                    <option value="1" {{ $item->uac_status == "1" ? "SELECTED" : "" }}>Active</option>
                    <option value="0" {{ $item->uac_status == "0" ? "SELECTED" : "" }}>Non Active</option>
                </select>
            </div>
            <div class="col-6 fv-row">
                <label class="col-form-label">Connect Employee</label>
                <select name="emp_id" class="form-select" data-control="select2" data-placeholder="Select Employee" id="">
                    <option value=""></option>
                    @foreach ($personel as $val)
                        <option value="{{ $val->id }}" {{ $item->emp_id == $val->id ? "SELECTED" : "" }}>{{ $val->emp_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>