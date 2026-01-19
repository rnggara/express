<div class="card card-custom card-stretch gutter-b">
    <!--begin::Header-->
    <div class="card-header border-0">
        <div class="d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Mobile Toggle-->
            <button class="burger-icon burger-icon-left mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
                <span></span>
            </button>
            <!--end::Mobile Toggle-->
        </div>
        <!--end::Info-->
        <h3 class="card-title font-weight-bolder text-dark">
            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h5 class="text-dark font-weight-bold my-1 mr-5">
                    {{ __("view.master_study") }} </h5>
                <!--end::Page Title-->
            </div>
            <!--end::Page Heading-->
        </h3>
        </div>
        <div class="card-toolbar">
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                <i class="fa fa-plus"></i>
                Add
            </button>
        </div>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body pt-2">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['list'] as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td align="center">
                                    <button type="button" class="btn btn-primary btn-icon btn-xs" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a href="{{ route("master_data.location.delete", ["state" => $data['state'], "id" => $item->id]) }}" class="btn btn-danger btn-icon btn-xs" onclick="return confirm('Delete?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            {{-- ModalAdd --}}
                            <div class="modal fade" id="modalEdit{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1>Edit {{ ucwords(__("view.".$data['state'])) }}</h1>
                                            <button type="button" class="close" data-bs-dismiss="modal">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <form action="{{ route("master_data.location.store") }}" method="post">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="" class="col-form-label">Name</label>
                                                    <input type="text" name="name" id="name" class="form-control" value="{{ $item->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $data['id'] ?? null }}">
                                                <input type="hidden" name="id_edit" value="{{ $item->id }}">
                                                <input type="hidden" name="state" value="{{ $data['state'] }}">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!--end::Body-->

    {{-- ModalAdd --}}
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Add {{ ucwords(__("view.".$data['state'])) }}</h1>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route("master_data.location.store") }}" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="col-form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id" value="{{ $data['id'] ?? null }}">
                        <input type="hidden" name="state" value="{{ $data['state'] }}">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
