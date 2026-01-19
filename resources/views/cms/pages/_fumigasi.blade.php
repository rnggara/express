<form action="{{ route("cms.pages.update") }}" method="post" enctype="multipart/form-data">
    <div class="d-flex flex-column gap-2">
        <div class="fv-row">
            <label for="" class="col-form-label">Harga Dasar</label>
            <input type="text" name="base_price" class="form-control number" value="{{$data->fumigasi_base_price ?? 0}}" id="">
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">Harga Tambahan</label>
            <input type="text" name="additional_price" class="form-control number" value="{{$data->fumigasi_additional_price ?? 0}}" id="">
        </div>
        <div class="fv-row">
            <label for="" class="col-form-label">Kontent</label>
            <textarea name="content" id="content" class="form-control" cols="30" rows="10">{!! $data->content ?? "" !!}</textarea>
        </div>
        <div class="d-flex justify-content-end">
            @csrf
            <input type="hidden" name="type" value="{{ $v }}">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>