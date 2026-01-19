<div class="d-flex flex-column gap-2">
    <div class="d-flex justify-content-end">
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">Add List</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-display" id="table-list">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Order</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{!! $item->description !!}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                @if ($item->order > 1)
                                    <a href="{{ route("cms.pages.order", ['type' => 'faq', 'id' => $item->id, 'direction' => 'up']) }}" class="btn btn-sm btn-primary btn-icon">
                                        <i class="fa fa-arrow-up"></i>
                                    </a>
                                @endif
                                @if ($item->order < count($data))
                                    <a href="{{ route("cms.pages.order", ['type' => 'faq', 'id' => $item->id, 'direction' => 'down']) }}" class="btn btn-sm btn-danger btn-icon">
                                        <i class="fa fa-arrow-down"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-title="{{$item->title}}" data-content='{{addSlashes($item->description)}}' data-id="{{$item->id}}" onclick="editFaq(this)">Edit</button>
                            <a href="{{ route("cms.pages.delete", ['type' => 'faq', 'id' => $item->id]) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form action="{{ route("cms.pages.update") }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-centered">
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
                        <label class="col-form-label">Question</label>
                        <input type="text" name="title" class="form-control" required placeholder="Input Question" id="">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Description</label>
                        <textarea name="content" id="content" class="form-control" cols="30" rows="10"></textarea>
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
        var tb = $("#table-list").DataTable()
        

        function editFaq(el){
            var data = $(el).data()
            $('#modalAdd [name=id]').val(data.id);
            $('#modalAdd [name=title]').val(data.title);
            tinymce.get('content').setContent(data.content);

            $("#modalAdd").modal("show");
        }
    </script>
@endsection