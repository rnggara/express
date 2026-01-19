<div class="fv-row">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <div class="d-flex align-items-center" id="phone-number-sel">
        <select name="" id="" class="form-select" data-width="70%" data-control="select2" data-hide-search="true" data-dropdown-parent="#phone-number-sel" data-placeholder="Work">
            <option value=""></option>
            <option value="Work">Work</option>
            <option value="Mobile">Mobile</option>
            <option value="Home">Home</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" class="form-control mx-3 flex-fill" readonly placeholder="{{ $placeholder ?? "Input placeholder" }}">
        <input type="text" class="form-control w-25" readonly placeholder="Ext">
    </div>
</div>
{{-- <div class="fv-row">
    <div class="d-flex align-items-center" id="phone-number-sel">
        <select name="" id="" class="form-select" data-width="70%" data-control="select2" data-hide-search="true" data-dropdown-parent="#phone-number-sel" data-placeholder="Mobile">
            <option value=""></option>
            <option value="Work">Work</option>
            <option value="Mobile">Mobile</option>
            <option value="Home">Home</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" class="form-control ms-3 flex-fill" readonly placeholder="{{ $placeholder ?? "Input placeholder" }}">
    </div>
</div>
<div class="fv-row">
    <div class="d-flex align-items-center" id="phone-number-sel">
        <select name="" id="" class="form-select" data-width="70%" data-control="select2" data-hide-search="true" data-dropdown-parent="#phone-number-sel" data-placeholder="Home">
            <option value=""></option>
            <option value="Work">Work</option>
            <option value="Mobile">Mobile</option>
            <option value="Home">Home</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" class="form-control ms-3 flex-fill" readonly placeholder="{{ $placeholder ?? "Input placeholder" }}">
    </div>
</div>
<div class="fv-row">
    <div class="d-flex align-items-center" id="phone-number-sel">
        <select name="" id="" class="form-select" data-width="70%" data-control="select2" data-hide-search="true" data-dropdown-parent="#phone-number-sel" data-placeholder="Other">
            <option value=""></option>
            <option value="Work">Work</option>
            <option value="Mobile">Mobile</option>
            <option value="Home">Home</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" class="form-control ms-3 flex-fill" readonly placeholder="{{ $placeholder ?? "Input placeholder" }}">
    </div>
</div> --}}
