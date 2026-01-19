<div class="row">
    <div class="col-12">
        <table class="table table-bordered" id="table-artikel">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul Artikel</th>
                    <th>Category</th>
                    <th>Banner</th>
                    <th>Featured Image</th>
                    <th>Click count</th>
                    <th>
                        <button type="button" class="btn btn-primary btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
                            <i class="fa fa-plus"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['artikel'] as $i => $item)
                    @php
                        $cat = $data['artikel_category']->where("id", $item->category)->first();
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $item->subject }}</td>
                        <td>{{ $cat->category_name }}</td>
                        <td>
                            <img src="{{ asset($item->drawing) }}" class="w-100px" alt="">
                        </td>
                        <td>
                            <img src="{{ asset($item->thumbnail) }}" class="w-100px" alt="">
                        </td>
                        <td>{{ $item->status }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-icon btn-sm"
                            onclick="editArtikel(this)"
                            data-author="{{ $item->created_by }}"
                            data-id="{{ $item->id }}"
                            data-title="{{ $item->subject }}"
                            data-category="{{ $item->category }}"
                            data-banner="{{ asset($item->drawing) }}"
                            data-featured="{{ asset($item->thumbnail) }}"
                            data-content='{{ $item->description }}'>
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" onclick="deleteArtikel({{ $item->id }})" class="btn btn-danger btn-icon btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form action="{{ route("cms.applicant.update") }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Artikel</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    <div class="form-group mb-5">
                        <label for="title" class="required form-label">Judul</label>
                        <input type="text" class="form-control form-control-solid" name="title" required placeholder="Judul"/>
                    </div>
                    <div class="form-group mb-5">
                        <label for="created_by" class="required form-label">Author</label>
                        <input type="text" class="form-control form-control-solid" name="created_by" required placeholder="Author"/>
                    </div>
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label">Konten</label>
                        <textarea name="content" id="content" class="ck-editor" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group mb-5">
                        <label for="category" class="required form-label">Kategory</label>
                        <select name="category" id="category" data-control="select2" required class="form-control">
                            @foreach ($data['artikel_category'] as $item)
                                <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label w-100">Banner Image</label>
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-700px h-400px" style="background-image: url()"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Upload Banner Image">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="img[banner]" required accept=".png, .jpg, .jpeg" />
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
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label w-100">Featured Image</label>
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-300px h-150px" style="background-image: url()"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Upload Featured Image">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="img[featured]" required accept=".png, .jpg, .jpeg" />
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
                    <input type="hidden" name="type" value="artikel">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form action="{{ route("cms.applicant.update") }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalEdit">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Artikel</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    <div class="form-group mb-5">
                        <label for="title" class="required form-label">Judul</label>
                        <input type="text" class="form-control form-control-solid" id="title" name="title" required placeholder="Judul"/>
                    </div>
                    <div class="form-group mb-5">
                        <label for="created_by" class="required form-label">Author</label>
                        <input type="text" class="form-control form-control-solid" id="author" name="created_by" required placeholder="Author"/>
                    </div>
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label">Konten</label>
                        <textarea name="content" id="content-edit" class="ck-editor" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group mb-5">
                        <label for="category" class="required form-label">Kategory</label>
                        <select name="category" id="category" data-control="select2" required class="form-control">
                            @foreach ($data['artikel_category'] as $item)
                                <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label w-100">Banner Image</label>
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-700px h-400px" id="banner-image" style="background-image: url()"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Upload Banner Image">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="img[banner]" accept=".png, .jpg, .jpeg" />
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
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label w-100">Featured Image</label>
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-300px h-150px" id="featured-image" style="background-image: url()"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Upload Featured Image">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="img[featured]" accept=".png, .jpg, .jpeg" />
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
                    <input type="hidden" name="type" value="artikel">
                    <input type="hidden" name="id" id="id-artikel">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

@section('scripts')
    <script>
        function editArtikel(me){
            $("#modalEdit").modal("show")
            $("#modalEdit #title").val($(me).data("title"))
            $("#modalEdit #author").val($(me).data("author"))
            // $("#modalEdit #content").val($(me).data("content"))
            _editor['content-edit'].setData($(me).data("content"))
            $("#modalEdit #category").val($(me).data("category")).trigger('change')
            $("#modalEdit #banner-image").css("background-image", "url("+$(me).data("banner")+")")
            $("#modalEdit #featured-image").css("background-image", "url("+$(me).data("featured")+")")
            $("#modalEdit #id-artikel").val($(me).data("id"))
        }

        function deleteArtikel(id){
            Swal.fire({
                text: "Hapus Artikel",
                icon: "question",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus Artikel!",
                cancelButtonText: "Batal",
                reverseButtons: true,
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton : "btn btn-secondary"
                }
            }).then(function(resp){
                if(resp.value){
                    location.href = "{{ route('cms.applicant.delete', ["type" => "artikel"]) }}/" + id
                }
            });
        }
    </script>
@endsection
