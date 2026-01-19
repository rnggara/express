<div class="row">
    <div class="fv-row col-6">
        <label class="col-form-label">Tax Status</label>
        <select name="tax_status" class="form-select" data-control="select2" data-placeholder="Select Tax Status">
            <option value=""></option>
            @foreach ($tax_status as $item)
                <option value="{{ $item->id }}">{{ $item->name."($item->code)" }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Tax Type</label>
        <select name="tax_type" class="form-select" data-control="select2" data-placeholder="Select Tax Type">
            <option value=""></option>
            @foreach (\Config::get("constants.tax_type") ?? [] as $key => $item)
                <option value="{{ $key }}">{{ ucwords($item) }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Currency</label>
        <select name="currency" class="form-select" data-control="select2" data-placeholder="Select Currency">
            <option value=""></option>
            @foreach ($countries as $item)
                <option value="{{ $item->id }}">{{ $item->currency }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Payment Method</label>
        <select name="payment_method" class="form-select" data-control="select2" data-placeholder="Select Payment Method">
            <option value=""></option>
            @foreach (\Config::get("constants.payment_method") ?? [] as $key => $item)
                <option value="{{ $key }}">{{ ucwords($item) }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Payment Schedule</label>
        <select name="payment_schedule" class="form-select" data-control="select2" data-placeholder="Select Payment Schedule">
            <option value=""></option>
            @foreach (\Config::get("constants.payment_schedule") ?? [] as $key => $item)
                <option value="{{ $key }}">{{ ucwords($item) }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Active Period Date</label>
        <input type="text" name="active_period_date" class="form-control tempusDominus" id="active_periode_date" placeholder="Input Active Period Date">
    </div>
</div>
