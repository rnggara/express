@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Test Materials</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                        <i class="fa fa-plus"></i>
                        Add Item
                    </button>
                    <a href="{{ route("hrd.test.index") }}" class="btn btn-success">
                        <i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $i => $item)
                                <tr>
                                    <td align="center">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            {{ $item->title }}
                                            <div class="text-left">
                                                <div class="btn-group">
                                                    @if ($item->order_num != 1)
                                                        <a href="{{ route("hrd.test.order_change", ["id" => $item->id, "order" => "up", "type" => "materials"]) }}" class="btn btn-icon btn-xs btn-primary">
                                                            <i class="fa fa-arrow-up"></i>
                                                        </a>
                                                    @endif
                                                    @if ($item->order_num != count($materials))
                                                        <a href="{{ route("hrd.test.order_change", ["id" => $item->id, "order" => "down", "type" => "materials"]) }}" class="btn btn-icon btn-xs btn-danger">
                                                            <i class="fa fa-arrow-down"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td align="center">
                                        @if (!empty($item->file))
                                            <a href="{{ str_replace("public", "public_html", asset($item->file)) }}" class="btn btn-primary btn-icon btn-sm">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpload{{ $item->id }}"><i class="fa fa-upload"></i> Upload File</button>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-primary btn-icon btn-xs" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"><i class="fa fa-edit"></i></button>
                                        <a href="{{ route('hrd.test.delete', ["id" => $item->id, "type" => "materials"]) }}"  onclick="return confirm('Delete?')" class="btn btn-danger btn-icon btn-xs"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalUpload{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1>Upload File</h1>
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('hrd.test.materials_add') }}" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="file" class="col-form-label">File</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="file" id="file" class="custom-file-input" required>
                                                            <span class="custom-file-label">Choose File</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    @csrf
                                                    <input type="hidden" name="act" value="upload">
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Edit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modalEdit{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1>Edit Item</h1>
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('hrd.test.materials_add') }}" method="post">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="title" class="col-form-label">Title</label>
                                                        <input type="text" name="title" id="title" class="form-control" value="{{ $item->title }}" required>
                                                    </div>
                                                    {{-- <div class="form-group">
                                                        <label for="point" class="col-form-label">Point</label>
                                                        <input type="number" value="{{ $item->point }}" min="0" name="point" id="point" class="form-control" required>
                                                    </div> --}}
                                                </div>
                                                <div class="modal-footer">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit"  class="btn btn-primary btn-sm">Edit</button>
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
    </div>
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Add Item</h1>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('hrd.test.materials_add') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title" class="col-form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        {{-- <div class="form-group">
                            <label for="point" class="col-form-label">Point</label>
                            <input type="number" value="0" min="0" name="point" id="point" class="form-control" required>
                        </div> --}}
                        <div class="form-group">
                            <label for="file" class="col-form-label">File</label>
                            <div class="custom-file">
                                <input type="file" name="file" id="file" class="custom-file-input">
                                <span class="custom-file-label">Choose File</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="test_id" value="{{ $test->id }}">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $(document).ready(function(){
                $("table.display").DataTable()
            })
        })
    </script>
@endsection
