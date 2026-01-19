<div class="row">
    <div class="col-12">
        <table class="table table-bordered" id="table-stories">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pic</th>
                    <th>Nama User</th>
                    <th>Konten</th>
                    <th>Perusahaan</th>
                    <th>
                        <button type="button" class="btn btn-primary btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">
                            <i class="fa fa-plus"></i>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['stories'] as $i => $item)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>
                            <div class="image-input image-input-empty" data-kt-image-input="true">
                                <!--begin::Image preview wrapper-->
                                <div class="image-input-wrapper w-125px h-125px" style="background-image : url({{ asset($item->picture) }})"></div>
                            </div>
                        </td>
                        <td>
                            {{ $item->name }}
                        </td>
                        <td>
                            {!! $item->notes !!}
                        </td>
                        <td>{{ $item->specification }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-icon btn-sm"
                            onclick="editStories(this)"
                            data-id="{{ $item->id }}"
                            data-nama="{{ $item->name }}"
                            data-content="{{ $item->notes }}"
                            data-perusahaan="{{ $item->specification }}"
                            data-img="{{ asset($item->picture) }}">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" onclick="deleteStories({{ $item->id }})" class="btn btn-danger btn-icon btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form action="{{ route("cms.employer.update") }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stories</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    <div class="form-group mb-5">
                        <label for="nama" class="required form-label">Nama</label>
                        <input type="text" class="form-control form-control-solid" required name="nama" required placeholder="Nama"/>
                    </div>
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label">Konten</label>
                        <textarea name="content" id="content" class="ck-editor" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group mb-5">
                        <label for="perusahaan" class="required form-label">Perusahaan</label>
                        <input type="text" class="form-control form-control-solid" required name="perusahaan" required placeholder="Perusahaan"/>
                    </div>
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label w-100">Image</label>
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-125px h-125px" style="background-image: url()"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Upload Image">
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
                    <input type="hidden" name="type" value="stories">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form action="{{ route("cms.employer.update") }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalEdit">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Stories</h5>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    <div class="form-group mb-5">
                        <label for="nama" class="required form-label">Nama</label>
                        <input type="text" class="form-control form-control-solid" id="nama" required name="nama" required placeholder="Nama"/>
                    </div>
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label">Konten</label>
                        <textarea name="content" id="content-edit" class="ck-editor" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group mb-5">
                        <label for="perusahaan" class="required form-label">Perusahaan</label>
                        <input type="text" class="form-control form-control-solid" id="perusahaan" required name="perusahaan" required placeholder="Perusahaan"/>
                    </div>
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label w-100">Image</label>
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline" data-kt-image-input="true">
                            <!--begin::Image preview wrapper-->
                            <div class="image-input-wrapper w-125px h-125px" id="image" style="background-image: url()"></div>
                            <!--end::Image preview wrapper-->

                            <!--begin::Edit button-->
                            <label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Upload Image">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="img" accept=".png, .jpg, .jpeg" />
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
                    <input type="hidden" name="type" value="stories">
                    <input type="hidden" name="id" id="id-stories">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

@section('scripts')
    <script>
        function editStories(me){
            $("#modalEdit").modal("show")
            $("#modalEdit #nama").val($(me).data("nama"))
            $("#modalEdit #perusahaan").val($(me).data("perusahaan"))
            _editor['content-edit'].setData($(me).data("content"))
            $("#modalEdit #image").css("background-image", "url("+$(me).data("img")+")")
            $("#modalEdit #id-stories").val($(me).data("id"))
        }

        function deleteStories(id){
            Swal.fire({
                text: "Hapus Stories",
                icon: "question",
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus Stories!",
                cancelButtonText: "Batal",
                reverseButtons: true,
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton : "btn btn-secondary"
                }
            }).then(function(resp){
                if(resp.value){
                    location.href = "{{ route('cms.employer.delete', ["type" => "stories"]) }}/" + id
                }
            });
        }
    </script>
@endsection
