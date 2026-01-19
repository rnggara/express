    <div class="row">
        <div class="col-12">
            <h4>Image Slider</h4>
            <div>
                <div class="d-flex justify-content-end mb-5">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                        Tambah Gambar
                    </button>
                </div>
                <table class="table table-bordered table-display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Duration</th>
                            <th>Order</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slider as $item)
                            <tr>
                                <td align="center">{{ $loop->iteration }}</td>
                                <td align="center">
                                    <img src="{{ asset($item->path) }}" class="w-150px rounded" alt="" srcset="">
                                </td>
                                <td align="center">
                                    <div class="d-flex align-items-center justify-content-center gap-5">
                                        <span>{{ $item->duration }}</span>
                                        <button type="button" class="btn btn-primary btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <form action="{{ route("cms.applicant.update") }}" method="post" enctype="multipart/form-data">
                                            <div class="modal fade" tabindex="-1" id="modalEdit{{ $item->id }}">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Duration</h5>
                                        
                                                            <!--begin::Close-->
                                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                                                            </div>
                                                            <!--end::Close-->
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group mb-5">
                                                                <label for="duration" class="required form-label">Duration</label>
                                                                <input type="number" value="{{ $item->duration }}" min="1" class="form-control form-control-solid" name="duration" required placeholder="Duration"/>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            @csrf
                                                            <input type="hidden" name="type" value="slider_duration">
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                                <td align="center">{{ $item->order }}</td>
                                <td align="center">
                                    @if ($item->order > 1)
                                        <a href="{{ route('cms.applicant.order', ['type' => 'up', 'id' => $item->id]) }}" class="btn btn-icon btn-sm btn-primary">
                                            <i class="fa fa-arrow-up"></i>
                                        </a>
                                    @endif
                                    @if ($item->order < $slider->count())
                                        <a href="{{ route('cms.applicant.order', ['type' => 'down', 'id' => $item->id]) }}" class="btn btn-icon btn-sm btn-info">
                                            <i class="fa fa-arrow-down"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('cms.applicant.delete', ['type' => 'slider', 'id' => $item->id]) }}" class="btn btn-icon btn-sm btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border p-3">
                <div class="form-group">
                    <label for="test_id" class="col-form-label">Title</label>
                    <input type="text" class="form-control" form="form-main" name="sec_title" value="{{ $lp["sec_title"] ?? "" }}">
                </div>
                <div class="form-group">
                    <label for="test_desc" class="col-form-label">Deskripsi</label>
                    <input type="text" class="form-control" form="form-main" name="sec_desc" value="{{ $lp["sec_desc"] ?? "" }}">
                </div>
            </div>
        </div>
        <div class="col-12 text-right  mt-5 d-flex justify-content-end">
            <form action="{{ route("cms.applicant.update") }}" id="form-main" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="sec">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>


<form action="{{ route("cms.applicant.update") }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Gambar</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    <div class="form-group mb-5">
                        <label for="content" class="required form-label w-100">Image</label>
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
                            title="Upload Image">
                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                                <!--begin::Inputs-->
                                <input type="file" name="image" required accept=".png, .jpg, .jpeg" />
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
                        @error("image")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group mb-5">
                        <label for="duration" class="required form-label">Duration</label>
                        <input type="number" value="5" min="1" class="form-control form-control-solid" name="duration" required placeholder="Duration"/>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="type" value="slider">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>