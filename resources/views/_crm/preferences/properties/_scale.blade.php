<div class="fv-row">
    <label for="" class="col-form-label">Scale</label>
</div>
<div id="additional-option">
    @for($i = 0; $i < 4; $i++)
        <div class="fv-row row mb-5 additional-option-items">
            <label for="" class="col-form-label col-md-3 col-12">{{ "Scale ".($i+1) }}</label>
            <div class="col-10 col-md-7 position-relative">
                <input type="text" name="option[]" onchange="change_preview()" required class="form-control pe-10" placeholder="{{ "Scale ".($i+1) }}">
                <button type="button" class="btn end-0 position-absolute text-danger top-0" onclick="remove_additional(this)">
                    <i class="la la-trash text-danger"></i>
                </button>
            </div>
            <div class="col-2 col-md-2 d-flex align-items-center">
                <input type="color" class="border-0" onchange="change_preview()" name="color[]" value="#EBEBEB">
            </div>
        </div>
    @endfor
</div>
{{-- <div class="d-flex justify-content-center">
    <button type="button" class="btn p-0 text-primary" onclick="add_additional()">
        <i class="la la-plus text-primary"></i>
        Tambah scale
    </button>
</div> --}}
