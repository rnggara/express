<div class="row">
    <div class="fv-row col-12">
        <label class="col-form-label">Roles</label>
        <select name="workgroup" class="form-select" data-control="select2" data-placeholder="Select Roles">
            <option value=""></option>
            @foreach ($roles as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-12">
        <label class="col-form-label">Kerjaku akan membuatkan akun untuk email dengan password : </label>
        <input type="text" readonly name="pw" class="form-control" value="kerjaku{{ date("Y") }}">
    </div>
</div>
