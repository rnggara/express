<div class="row">
    <div class="fv-row col-12">
        <label class="col-form-label">Bank Name</label>
        <select name="bank_name" class="form-select" data-control="select2" data-placeholder="Select Bank Name">
            <option value=""></option>
            @foreach ($banks as $item)
                <option value="{{ $item->id }}">{{ sprintf("%03d", $item->id)."-".$item->bank_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Account Number</label>
        <input type="text" name="account_number" class="form-control" placeholder="Input Account Number">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Company Bank Account</label>
        <input type="text" name="account_name" class="form-control" placeholder="Input Company Bank Account">
    </div>
</div>
