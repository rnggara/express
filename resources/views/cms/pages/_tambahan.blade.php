<form action="{{ route("cms.pages.update") }}" method="post" enctype="multipart/form-data">
    <div class="d-flex flex-column gap-2">
        <div class="border">
            <textarea name="content" id="content" class="form-control" cols="30" rows="10">{!! $data->content ?? "" !!}</textarea>
        </div>
        <div class="d-flex justify-content-end">
            @csrf
            <input type="hidden" name="type" value="{{ $v }}">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</form>