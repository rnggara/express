@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Test Questions</h3>
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
                                <th>{{ $test->category_id == 6 ? "Sentence 1" : "Question" }}</th>
                                @if ($test->category_id == 6)
                                    <th>Sentence 2</th>
                                @endif
                                <th>Answers</th>
                                <th>Preview</th>
                                <th>Image</th>
                                <th>Choices</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $i => $item)
                                <tr>
                                    <td align="center">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $test->category_id == 5 ? "" : $item->label }}</span>
                                            <div class="text-left">
                                                <div class="btn-group">
                                                    @if ($item->order_num != 1)
                                                        <a href="{{ route("hrd.test.order_change", ["id" => $item->id, "order" => "up", "type" => "question"]) }}" class="btn btn-icon btn-sm btn-primary">
                                                            <i class="fa fa-arrow-up"></i>
                                                        </a>
                                                    @endif
                                                    @if ($item->order_num != count($questions))
                                                        <a href="{{ route("hrd.test.order_change", ["id" => $item->id, "order" => "down", "type" => "question"]) }}" class="btn btn-icon btn-sm btn-danger">
                                                            <i class="fa fa-arrow-down"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @if ($test->category_id == 6)
                                        <td>{{ $item->label2 }}</td>
                                    @endif
                                    <td align="center">
                                        <a href="{{ route('hrd.test.detail', $item->id) }}" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
                                            View Answers
                                        </a>
                                    </td>
                                    <td align="center">
                                        <a href="{{ route("hrd.test.question_view", $item->id) }}" class="btn btn-icon btn-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                    <td align="center">
                                        @if (!empty($item->img))
                                        <img src="{{ asset($item->img) }}" class="img-thumbnail w-150px" srcset=""><br>
                                        @endif
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpload{{ $item->id }}"><i class="fa fa-upload"></i> Upload Image</button>
                                    </td>
                                    <td align="center">{{ $item->points()->count() }}</td>
                                    <td align="center">
                                        <button type="button" class="btn btn-primary btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"><i class="fa fa-edit"></i></button>
                                        <a href="{{ route('hrd.test.delete', ["id" => $item->id, "type" => "question"]) }}"  onclick="return confirm('Delete?')" class="btn btn-danger btn-icon btn-sm"><i class="fa fa-trash"></i></a>
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
                                            <form action="{{ route('hrd.test.question_add') }}" method="post" enctype="multipart/form-data">
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
                                                <h1>Edit Item</h1>
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('hrd.test.question_add') }}" method="post">
                                                <div class="modal-body">
                                                    @if ($test->category_id == 6)
                                                        <div class="form-group">
                                                            <label for="question" class="col-form-label">Sentence 1</label>
                                                            <input type="text" name="question" id="question" class="form-control" value="{{ $item->label }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="label2" class="col-form-label">Sentence 2</label>
                                                            <input type="text" name="label2" id="label2" class="form-control" value="{{ $item->label2 }}" required>
                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <label for="question" class="col-form-label">Question</label>
                                                            <input type="text" name="question" id="question" class="form-control" value="{{ $item->label }}" required>
                                                        </div>
                                                    @endif
                                                    @if ($test->category_id == 1 || $test->category_id == 4)
                                                    <div class="form-group">
                                                        <label for="question" class="col-form-label">Question Type</label>
                                                        <select name="q_type" class="form-select" data-control="select2" data-hide-search="true">
                                                            <option value="1" {{ $item->question_type == "1" ? "CHECKED" : "" }}>Pilihan Ganda</option>
                                                            <option value="2" {{ $item->question_type == "2" ? "CHECKED" : "" }}>Isian Bebas</option>
                                                        </select>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Edit</button>
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
                <form action="{{ route('hrd.test.question_add') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @if ($test->category_id == 6)
                            <div class="form-group">
                                <label for="question" class="col-form-label">Sentence 1</label>
                                <input type="text" name="question" id="question" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="label2" class="col-form-label">Sentence 2</label>
                                <input type="text" name="label2" id="label2" class="form-control" required>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="question" class="col-form-label">Question</label>
                                <input type="text" name="question" id="question" class="form-control" required>
                            </div>
                        @endif
                        @if ($test->category_id == 1 || $test->category_id == 4)
                        <div class="form-group">
                            <label for="question" class="col-form-label">Question Type</label>
                            <select name="q_type" class="form-select" data-control="select2" data-hide-search="true">
                                <option value="1">Pilihan Ganda</option>
                                <option value="2">Isian Bebas</option>
                            </select>
                        </div>
                        @endif
                        {{-- <div class="form-group">
                            <label for="point" class="col-form-label">Point</label>
                            <input type="number" value="0" min="0" name="point" id="point" class="form-control" required>
                        </div> --}}
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
