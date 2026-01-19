@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Test Configuration</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                        <i class="fa fa-plus"></i>
                        Add Item
                    </button>
                    <a href="{{ route("hrd.test.question", $test->id) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h3>{{ $test->category_id == 5 ? "" :  $question->label }}</h3>
                    <table class="table table-bordered display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Answers</th>
                                <th>Is True?</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($points as $i => $item)
                                <tr>
                                    <td align="center">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            {{ $item->label }}
                                            <div class="text-left">
                                                <div class="btn-group">
                                                    @if ($item->order_num != 1)
                                                        <a href="{{ route("hrd.test.order_change", ["id" => $item->id, "order" => "up", "type" => "point"]) }}" class="btn btn-icon btn-sm btn-primary">
                                                            <i class="fa fa-arrow-up"></i>
                                                        </a>
                                                    @endif
                                                    @if ($item->order_num != count($points))
                                                        <a href="{{ route("hrd.test.order_change", ["id" => $item->id, "order" => "down", "type" => "point"]) }}" class="btn btn-icon btn-sm btn-danger">
                                                            <i class="fa fa-arrow-down"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <i class="fa fa-{{ $item->is_true ? "check" : "times" }} text-{{ $item->is_true ? "success" : "danger"  }}"></i>
                                    </td>
                                    <td align="center">
                                        @if (!empty($item->img))
                                        <img src="{{ asset($item->img) }}" class="img-thumbnail max-w-150px" srcset=""><br>
                                        @endif
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpload{{ $item->id }}"><i class="fa fa-upload"></i> Upload Image</button>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-primary btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"><i class="fa fa-edit"></i></button>
                                        <a href="{{ route('hrd.test.delete', ["id" => $item->id, "type" => "point"]) }}" onclick="return confirm('Delete?')" class="btn btn-danger btn-icon btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalUpload{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1>Upload image</h1>
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('hrd.test.point_add') }}" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="image" class="col-form-label">Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="image" id="image" class="custom-file-input" required>
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
                                                <h1>Edit Choices</h1>
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('hrd.test.point_add') }}" method="post">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="question" class="col-form-label">Choice</label>
                                                        <input type="text" name="question" id="question" class="form-control" value="{{ $item->label }}" required>
                                                    </div>
                                                    @if ($question->question_type == 1)
                                                        @if ($test->category_id == 7)
                                                            <div class="form-group">
                                                                <label class="col-form-label">Tipe Buta Warna</label>
                                                                <div class="checkbox-inline">
                                                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                        <input type="radio" name="is_true" {{ $item->is_true == 1 ? "CHECKED" : "" }} value="1" />
                                                                        <span></span>
                                                                        Normal
                                                                    </label>
                                                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                        <input type="radio" name="is_true" {{ $item->is_true == 2 ? "CHECKED" : "" }} value="2" />
                                                                        <span></span>
                                                                        Red
                                                                    </label>
                                                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                        <input type="radio" name="is_true" {{ $item->is_true == 3 ? "CHECKED" : "" }} value="3" />
                                                                        <span></span>
                                                                        Green
                                                                    </label>
                                                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                        <input type="radio" name="is_true" {{ $item->is_true == 0 ? "CHECKED" : "" }} value="0" />
                                                                        <span></span>
                                                                        False
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="form-group">
                                                                <label for="is_true" class="col-form-label">Is True?</label>
                                                                <div class="checkbox-inline">
                                                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                        <input type="checkbox" name="is_true" {{ $item->is_true ? "checked" : "" }} value="1" />
                                                                        <span></span>
                                                                        Yes
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @elseif($question->question_type == 2)
                                                    <input type="hidden" name="is_true" value="1">
                                                    @endif
                                                    @if ($test->category_id == 6)
                                                        <div class="fv-row">
                                                            <label for="className" class="col-form-label">Color</label>
                                                            <select name="className" class="form-control">
                                                                <option value="success" {{ $item->className == "success" ? "SELECTED " : "" }} >Green</option>
                                                                <option value="danger" {{ $item->className == "danger" ? "SELECTED " : "" }} >Red</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    @csrf
                                                    <input type="hidden" name="qid" value="{{ $question->id }}">
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Add</button>
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
                    <h1>Add Choices</h1>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('hrd.test.point_add') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="question" class="col-form-label">Choice</label>
                            <input type="text" name="question" id="question" class="form-control" required>
                        </div>
                        @if ($question->question_type == 1)
                        @if ($test->category_id == 7)
                            <div class="form-group">
                                <label class="col-form-label">Tipe Buta Warna</label>
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="radio" name="is_true" checked value="1" />
                                        <span></span>
                                        Normal
                                    </label>
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="radio" name="is_true" value="2" />
                                        <span></span>
                                        Red
                                    </label>
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="radio" name="is_true" value="3" />
                                        <span></span>
                                        Green
                                    </label>
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="radio" name="is_true" value="0" />
                                        <span></span>
                                        False
                                    </label>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="is_true" class="col-form-label">Is True?</label>
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                        <input type="checkbox" name="is_true" value="1" />
                                        <span></span>
                                        Yes
                                    </label>
                                </div>
                            </div>
                        @endif
                        @elseif($question->question_type == 2)
                        <input type="hidden" name="is_true" value="1">
                        @endif
                        @if ($test->category_id == 6)
                            <div class="fv-row">
                                <label for="className" class="col-form-label">Color</label>
                                <select name="className" class="form-control">
                                    <option value="success">Green</option>
                                    <option value="danger">Red</option>
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="image" class="col-form-label">Image</label>
                            <div class="custom-file">
                                <input type="file" name="image" id="image" class="custom-file-input">
                                <span class="custom-file-label">Choose File</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="qid" value="{{ $question->id }}">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>
@endsection
