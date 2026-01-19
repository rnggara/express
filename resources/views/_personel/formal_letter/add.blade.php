@extends('_personel.layout')

@section('css')

@endsection

@section('view_content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Add Template Contract</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('personel.fl.index') }}" class="btn btn-icon btn-sm btn-success"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('personel.fl.save') }}" method="post">
                <div class="row">
                    <div class="col-6">
                        <div class="fv-row">
                            <label class="col-form-label">Template Name</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ $tp->name ?? "" }}" required name="template_name">
                            </div>
                        </div>
                        @if (!empty($tp))
                            <div class="fv-row">
                                <label class="col-form-label">Attachments</label>
                                <div class="col-9">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddAttachments">Add Attachment</button>
                                </div>
                            </div>
                            <div class="mt-3">
                                <table class="table table-hover table-striped table-display-2">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Field Name</th>
                                            <th class="text-center">Field Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($row_image ?? [] as $item)
                                            <tr>
                                                <td>{{ $item['value'] }}</td>
                                                <td>
                                                    {!! $item['text'] !!}
                                                    <a href="{{ $item['uri'] }}" target="_blank" class="btn btn-icon btn-xs btn-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="col-6">
                        <table class="table table-hover table-striped table-display-2">
                            <thead>
                                <tr>
                                    <th class="text-center">Field Name</th>
                                    <th class="text-center">Field Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($row ?? [] as $item)
                                    <tr>
                                        <td>{{ $item['value'] }}</td>
                                        <td>{!! $item['text'] !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6 mb-2">
                        <span class="font-weight-bold">Notes</span>
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold">* Type @ to add Field</span>
                            <span class="font-weight-bold">* Add {{ "<--new_break-->" }} to add a page break</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <textarea name="content" id="txt-content" cols="30" rows="50">{!! (!empty($tp)) ? $tp->content : "" !!}</textarea>
                    </div>
                </div>
                <div class="fv-row row mt-5">
                    <label class="col-form-label col-1"></label>
                    <div class="col-11 text-right">
                        @csrf
                        @if (!empty($tp))
                            <input type="hidden" name="id_tp" value="{{ $tp->id }}">
                        @endif
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (!empty($tp))
        <form action="{{ route("personel.fl.upload_attachment") }}" method="post" enctype="multipart/form-data">
            <div class="modal fade" id="modalAddAttachments" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">Add Attachments</h1>
                            <button class="btn btn-icon" data-bs-dismiss="modal"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="fv-row">
                                <label class="col-form-label w-100">File Browser</label>
                                <input type="file" name="attachment" class="custom-file-input" accept="image/*" id="customFile"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            @csrf
                            <input type="hidden" name="tp_id" value="{{ $tp->id }}">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
@endsection

@section('view_script')
    <script src="https://cdn.tiny.cloud/1/ud1vs4lf9nvq9ssp1nq0ccdvigyclmgai7xeakf2rufvrd6d/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        var specialChars = []
        function add_to_sc(data){
            specialChars.push(data)
        }
        $(document).ready(function(){
            $.ajax({
                url : "{{ route('personel.fl.get_field') }}",
                type : "get",
                data : {
                    tp_id : "{{ $tp->id ?? "" }}"
                },
                dataType : "json",
                success : function(specialChars){
                    console.log(specialChars)
                    tinymce.init({
                        selector : "#txt-content",
                        plugins: 'codesample code ',
                        codesample_languages: [
                            {text: 'HTML/XML', value: 'markup'},
                            {text: 'JavaScript', value: 'javascript'},
                            {text: 'CSS', value: 'css'},
                            {text: 'PHP', value: 'php'},
                            {text: 'Ruby', value: 'ruby'},
                            {text: 'Python', value: 'python'},
                            {text: 'Java', value: 'java'},
                            {text: 'C', value: 'c'},
                            {text: 'C#', value: 'csharp'},
                            {text: 'C++', value: 'cpp'}
                        ],
                        toolbar: 'codesample code undo redo styleselect bold italic alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
                        setup: function (editor) {
                        var onAction = function (autocompleteApi, rng, value) {
                            editor.selection.setRng(rng);
                            editor.insertContent(value);
                            autocompleteApi.hide();
                        };

                        var getMatchedChars = function (pattern) {
                            return specialChars.filter(function (char) {
                                return char.value.indexOf(pattern) !== -1;
                            });
                        };

                        /**
                        * An autocompleter that allows you to insert special characters.
                        * Items are built using the CardMenuItem.
                        */
                        editor.ui.registry.addAutocompleter('specialchars_cardmenuitems', {
                            ch: '@',
                            minChars: 0,
                            columns: 1,
                            highlightOn: ['char_name'],
                            onAction: onAction,
                            fetch: function (pattern) {
                                return new tinymce.util.Promise(function (resolve) {
                                var results = getMatchedChars(pattern).map(function (char) {
                                    return {
                                        type: 'cardmenuitem',
                                        value: char.value,
                                        label: char.text,
                                        items: [
                                            {
                                                type: 'cardcontainer',
                                                direction: 'vertical',
                                                items: [
                                                    {
                                                        type: 'cardtext',
                                                        text: char.value
                                                    },
                                                    {
                                                        type: 'cardtext',
                                                        text: char.text
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                });
                                resolve(results);
                                });
                        }
                        });
                    }
                    })
                }
            })

            $("select.select2").select2({
                width : "100%",
                allowClear : true,
            })

            $("table.table-display").DataTable()

        })
    </script>
@endsection
