@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Test List</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalCategory">
                        <i class="fa fa-cogs"></i>
                        Category
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                        <i class="fa fa-plus"></i>
                        Add Item
                    </button>
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
                                <th>Test Name</th>
                                <th>Category</th>
                                <th>Questions</th>
                                {{-- <th>Materials</th> --}}
                                <th>Question per Quiz</th>
                                {{-- <th>Treshold</th>
                                <th>Share Link</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tests as $i => $item)
                                <tr>
                                    <td align="center">{{ $i + 1 }}</td>
                                    <td>
                                        {{ $item->label }}
                                    </td>
                                    <td align="center">
                                        @php
                                            if($item->category_id == 1) {
                                                echo "Custom Test";
                                            } elseif($item->category_id == 2) {
                                                echo "MBTI";
                                            } elseif($item->category_id == 3) {
                                                echo "PAPI KOSTICK";
                                            } elseif($item->category_id == 4) {
                                                echo "WPT";
                                            } elseif($item->category_id == 5) {
                                                echo "DISC";
                                            } elseif($item->category_id == 6) {
                                                echo "Attention to detail";
                                            } elseif($item->category_id == 7) {
                                                echo "Color Blind";
                                            } else {
                                                echo "-";
                                            }
                                        @endphp
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('hrd.test.question', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                            View Questions
                                        </a>
                                    </td>
                                    {{-- <td align="center">
                                        <a href="{{ route('hrd.test.materials', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                            View Materials
                                        </a>
                                    </td> --}}
                                    <td align="center">
                                        {{ $item->question_per_quiz ?? 10 }}
                                    </td>
                                    {{-- <td align="center">
                                        {{ $item->treshold ?? 70 }} %
                                    </td> --}}
                                    {{-- <td align="center">
                                        <a href="{{ route('hrd.test.activate', $item->id) }}" onclick="return confirm('Are you sure?{{ !$item->is_active ? ' You can`t change the question and choices untill you deactivate this test' : '' }}')" class="btn btn-{{ $item->is_active ? "danger" : "success" }}">
                                            {{ $item->is_active ? "Deactivate" : "Activate" }}
                                        </a>
                                    </td> --}}
                                    {{-- <td align="center">
                                        <button type="button" class="btn btn-success" onclick="copyLink('{{ route('hrd.test.exam', base64_encode($item->id)) }}')">
                                            Copy to Clipboard
                                        </button>
                                    </td> --}}
                                    <td align="center">
                                        <button type="button" class="btn btn-primary btn-icon btn-xs" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}"><i class="fa fa-edit"></i></button>
                                        <a href="{{ route('hrd.test.delete', ["id" => $item->id, "type" => "test"]) }}" onclick="return confirm('Delete?')" class="btn btn-danger btn-icon btn-xs"><i class="fa fa-trash"></i></a>
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
                                            <form action="{{ route('hrd.test.add') }}" method="post">
                                                <div class="modal-body">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="label" class="col-form-label">Test Name</label>
                                                            <input type="text" name="label" id="label" class="form-control" value="{{ $item->label }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="descriptions" class="col-form-label">Descriptions</label>
                                                            <textarea name="descriptions" id="descriptions" class="form-control" cols="30" rows="10">{!! $item->descriptions !!}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="instructions" class="col-form-label">Instructions</label>
                                                            <textarea name="instructions" id="instructions" class="form-control" cols="30" rows="10">{!! $item->instructions !!}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="category" class="col-form-label">Category</label>
                                                            <select name="category" class="form-control" data-control="select2" required data-placeholder="Select Category">
                                                                <option value=""></option>
                                                                <option value="1" {{ $item->category_id == 1 ? "SELECTED" : "" }}>Custom Test</option>
                                                                <option value="2" {{ $item->category_id == 2 ? "SELECTED" : "" }}>MBTI</option>
                                                                <option value="3" {{ $item->category_id == 3 ? "SELECTED" : "" }}>PAPI KOSTICK</option>
                                                                <option value="4" {{ $item->category_id == 4 ? "SELECTED" : "" }}>WPT</option>
                                                                <option value="5" {{ $item->category_id == 5 ? "SELECTED" : "" }}>DISC</option>
                                                                <option value="6" {{ $item->category_id == 6 ? "SELECTED" : "" }}>Attention to Detail</option>
                                                                <option value="7" {{ $item->category_id == 7 ? "SELECTED" : "" }}>Color Blind</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="total" class="col-form-label">Total Question per Quiz</label>
                                                            <input type="number" name="total" id="total" class="form-control" required value="{{ $item->question_per_quiz ?? 10 }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="treshold" class="col-form-label">Treshold Pass *)in percentage</label>
                                                            <input type="number" name="treshold" id="treshold" class="form-control" required value="{{ $item->treshold ?? 70 }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="time_limit" class="col-form-label">Time Limit <span class="text-center">*)in minutes</span></label>
                                                            <input type="number" name="time_limit" id="time_limit" class="form-control" required value="{{ $item->treshold ?? 60 }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="take_limit" class="col-form-label">Max. User can take the test</label>
                                                            <input type="number" name="take_limit" id="take_limit" class="form-control" required value="{{ $item->take_limit ?? 60 }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="random_order" class="col-form-label">Random Order</label>
                                                            <div class="checkbox-inline">
                                                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                    <input type="checkbox" name="random_order" {{ $item->random_order ? "checked" : "" }} value="1" />
                                                                    <span></span>
                                                                    Yes
                                                                </label>
                                                            </div>
                                                        </div>
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
                    <h1>Add Test</h1>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('hrd.test.add') }}" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label" class="col-form-label">Test Name</label>
                            <input type="text" name="label" id="label" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descriptions" class="col-form-label">Descriptions</label>
                            <textarea name="descriptions" id="descriptions" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="instructions" class="col-form-label">Instructions</label>
                            <textarea name="instructions" id="instructions" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="category" class="col-form-label">Category</label>
                            <select name="category" id="category" class="form-control" data-control="select2" required data-placeholder="Select Category">
                                <option value=""></option>
                                <option value="1">Custom Test</option>
                                <option value="2">MBTI</option>
                                <option value="3">PAPI KOSTICK</option>
                                <option value="4">WPT</option>
                                <option value="5">DISC</option>
                                <option value="6">Attention to Detail</option>
                                <option value="7">Color Blind</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="total" class="col-form-label">Total Question per Quiz</label>
                            <input type="number" name="total" id="total" class="form-control" required value="10">
                        </div>
                        {{-- <div class="form-group">
                            <label for="treshold" class="col-form-label">Treshold Pass <span class="text-center">*)in percentage</span></label>
                            <input type="number" name="treshold" id="treshold" class="form-control" required value="70">
                        </div> --}}
                        <div class="form-group">
                            <label for="time_limit" class="col-form-label">Time Limit <span class="text-center">*)in minutes</span></label>
                            <input type="number" name="time_limit" id="time_limit" class="form-control" required value="60">
                        </div>
                        <div class="form-group">
                            <label for="take_limit" class="col-form-label">Max. User can take the test</label>
                            <input type="number" name="take_limit" id="take_limit" class="form-control" required value="5">
                        </div>
                        <div class="form-group">
                            <label for="random_order" class="col-form-label">Random Order</label>
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="random_order" value="1" />
                                    <span></span>
                                    Yes
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCategory" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Category</h1>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('hrd.test.category.add') }}" method="post">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Category Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="form-group text-right">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Add</button>
                        </div>
                    </form>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered" id="table-category">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function copyLink(text) {
            // Copy the text inside the text field
            navigator.clipboard.writeText(text);

            // Alert the copied text
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            toastr.success("Copied the text: " + text);
        }

        function edit_category(me, id){
            var tr = $(me).parents("tr")
            var inp = $(tr).find("input[name=name]")
            $.ajax({
                url : "{{ route("hrd.test.category.update") }}/" + id,
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : id,
                    name : inp.val()
                },
                success : function(resp){
                    location.reload()
                }
            })
        }

        $(document).ready(function(){
            $("table.display").DataTable()
            $("select.select2").select2({
                width : "100%"
            })

            $("#table-category").DataTable({
                ajax : {
                    url : "{{ route("hrd.test.category.get") }}",
                    dataType : "json",
                    type : "get"
                },
                columns : [
                    {"data" : "i"},
                    {"data" : "name"},
                    {"data" : "action"},
                ]
            })
        })
    </script>
@endsection
