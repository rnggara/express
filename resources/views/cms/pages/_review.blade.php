<div class="d-flex flex-column gap-2">
    <div class="d-flex justify-content-end">
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">Add List</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-display">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Occupation</th>
                    <th>Rating</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->occupation ?? "-" }}</td>
                        <td>
                            @if (!empty($item->rating) && $item->rating > 0)
                                @for ($i = 0; $i < $item->rating; $i++)
                                    <i class="fa fa-star text-warning"></i>
                                @endfor
                            @endif
                        </td>
                        <td>
                            <button 
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-occupation="{{ $item->occupation }}"
                                data-rating="{{ $item->rating }}"
                                data-content="{{ $item->description }}"
                                data-img="{{ $item->avatars ? asset($item->avatars) : "" }}"
                                class="btn btn-sm btn-primary" type="button" onclick="editList(this)">Edit</button>
                            <a href="{{ route("cms.pages.delete", ['type' => $v, 'id' => $item->id]) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form action="{{ route("cms.pages.update") }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">List</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <div class="fv-row">
                        <label class="col-form-label">Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Input Name" id="">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Occupation</label>
                        <input type="text" name="occupation" class="form-control" placeholder="Input Occupation" id="">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Rating</label>
                        <select name="rating" class="form-select" data-control="select2" data-dropdown-parent="#modalAdd">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Description</label>
                        <textarea name="content" id="content" class="form-control" cols="30" rows="10"></textarea>
                    </div>
                    <div class="fv-row mb-5">
                        <label for="content" class="required form-label w-100">Avatar</label>
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-150px h-150px" style="background-image: url()"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Upload Avatar">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="img" required accept=".png, .jpg, .jpeg" />
                                <!--end::Inputs-->
                            </label>
                            <!--end::Edit button-->

                            <!--begin::Cancel button-->
                            <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Cancel avatar">
                                <i class="ki-outline ki-cross fs-3"></i>
                            </span>
                            <!--end::Cancel button-->
                        </div>
                        <!--end::Image input-->
                    </div>
                </div>

                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="id">
                    <input type="hidden" name="type" value="{{ $v }}">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

@section('custom_script_inner')
    <script>
        function editList(el){
            const data = $(el).data()
            $("#modalAdd [name=id]").val(data.id)
            $("#modalAdd [name=name]").val(data.name)
            $("#modalAdd [name=occupation]").val(data.occupation)
            $("#modalAdd [name=rating]").val(data.rating).trigger("change")
            tinymce.get('content').setContent(data.content)
            if(data.img){
                $("#modalAdd .image-input-wrapper").css("background-image", `url('${data.img}')`)
            } else {
                $("#modalAdd .image-input-wrapper").css("background-image", `url('')`)
            }
            $("#modalAdd").modal("show")
        }
    </script>
@endsection