@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Random Questions</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="fa fa-plus"></i>
                    Add Item
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Answers</th>
                                <th>Choices</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $i => $item)
                                <tr>
                                    <td align="center">{{ $i + 1 }}</td>
                                    <td>
                                        <span>{{ $item->label }}</span>
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('rq.detail', $item->id) }}" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
                                            View Answers
                                        </a>
                                    </td>
                                    <td align="center">{{ $item->points()->count() }}</td>
                                    <td align="center">
                                        <button type="button" class="btn btn-primary btn-icon btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"><i class="fa fa-edit"></i></button>
                                        <a href="{{ route('hrd.test.delete', ["id" => $item->id, "type" => "question"]) }}"  onclick="return confirm('Delete?')" class="btn btn-danger btn-icon btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalEdit{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1>Edit Item</h1>
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('rq.add_question') }}" method="post">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="question" class="col-form-label">Question</label>
                                                        <input type="text" name="question" id="question" class="form-control" value="{{ $item->label }}" required>
                                                    </div>
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
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('rq.add_question') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="question" class="col-form-label">Question</label>
                            <input type="text" name="question" id="question" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
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
