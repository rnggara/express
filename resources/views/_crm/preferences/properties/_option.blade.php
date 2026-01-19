<div class="fv-row">
    <label for="" class="col-form-label">Option</label>
</div>
<div data-section="additional-option">
    @if(isset($additional) && !empty($additional))
        @foreach($additional as $i => $item)
            <div class="fv-row row mb-5 additional-option-items">
                <label for="" class="col-form-label col-md-3 col-12">{{ "Option ".($i+1) }}</label>
                <div class="col-12 col-md-9 position-relative">
                    <input type="text" name="option[]" value='{{$item}}' onchange="change_preview('{{str_replace('%23', '#', $modal)}}')" required class="form-control pe-10" placeholder="{{ "Option ".($i+1) }}">
                    <button type="button" class="btn end-0 position-absolute text-danger top-0" onclick="remove_additional(this, '{{str_replace('%23', '#', $modal)}}')">
                        <i class="la la-trash text-danger"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @else
        @for($i = 0; $i < 3; $i++)
            <div class="fv-row row mb-5 additional-option-items">
                <label for="" class="col-form-label col-md-3 col-12">{{ "Option ".($i+1) }}</label>
                <div class="col-12 col-md-9 position-relative">
                    <input type="text" name="option[]" onchange="change_preview('{{str_replace('%23', '#', $modal)}}')" required class="form-control pe-10" placeholder="{{ "Option ".($i+1) }}">
                    <button type="button" class="btn end-0 position-absolute text-danger top-0" onclick="remove_additional(this, '{{str_replace('%23', '#', $modal)}}')">
                        <i class="la la-trash text-danger"></i>
                    </button>
                </div>
            </div>
        @endfor
    @endif
</div>
<div class="d-flex justify-content-center">
    <button type="button" class="btn p-0 text-primary" onclick="add_additional('{{str_replace('%23', '#', $modal)}}')">
        <i class="la la-plus text-primary"></i>
        Tambah option
    </button>
</div>
