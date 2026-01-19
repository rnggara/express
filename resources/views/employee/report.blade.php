@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Employee Report</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="" id="form-filter">
                <div class="row">
                    <div class="col-6 mx-auto border rounded p-3">
                        <h3>Filter</h3>
                        <hr>
                        <div class="form-group">
                            <label for="emp_type" class="form-col-label">Employee Type</label>
                            <select name="emp_type[]" data-placeholder="Show All" id="emp_type" class="form-control select2" multiple>
                                @foreach ($emp_type as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cols" class="form-col-label">Columns</label>
                            <div>
                                <select id="kt_dual_listbox_1" multiple>
                                    @foreach ($cols as $item)
                                        @if (!in_array($item, $colsEx))
                                            <option value="{{ $item }}">{{ ucwords(str_replace("_", " ", $item)) }}</option>
                                        @endif
                                    @endforeach
                                    <option value="join_date">Join Date</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cols" class="form-col-label">Documents</label>
                            <div>
                                <select class="form-control select2" name="docs[]" id="docs" multiple data-placeholder="Select Document Type">
                                    @foreach ($cv_doc_type as $ctype)
                                        <option value="{{ $ctype->id }}">{{ $ctype->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="with-document">
                            <label for="cols" class="form-col-label">Document Expiry Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <label class="checkbox checkbox-inline checkbox-success">
                                            <input type="checkbox" id="with_expiry" onclick="is_expiry()" name="with_expiry" value="1">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <input type="text" id="expiry_date" name="expiry_date" class="form-control" placeholder="Show All">
                            </div>
                        </div>
                        <div class="form-group">
                            @csrf
                            <div id="div-col"></div>
                            <button id="btn-search" type="button" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row mt-5">
                <div class="col-12" id="table-result"></div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        function is_expiry(){
            var ck = document.getElementById("with_expiry")
            var fl = $("#expiry_date")
            if(ck.checked){
                var date = new Date()
                var dateFormat = date.getFullYear() + "-" + ((date.getMonth()+1).length != 2 ? "0" + (date.getMonth() + 1) : (date.getMonth()+1)) + "-" + (date.getDate().length < 2 ? "0" + date.getDate() : date.getDate());
                console.log(dateFormat)
                $(fl).prop("type", "date").val(dateFormat)
            } else {
                $(fl).prop("type", "text").val("")
            }
        }

        function with_doc(count){
            console.log(count)
            var isWith = true;
            if(count > 0){
                isWith = false
            } else {
                isWith = true
            }
            var ck = document.getElementById("with_expiry")
            if(isWith){
                ck.checked = false
                is_expiry()
            }
            $("#with-document").find("input").prop('disabled', isWith)
        }

        $(document).ready(function(){
            with_doc(0)
            $("#docs").change(function(){
                var val = $(this).val()
                with_doc(val.length)
            })

            $("select.select2").select2({
                width : "100%"
            })
            // KTDualListbox.init();
            let dlist = new DualListbox("#kt_dual_listbox_1", {
                addEvent: function (value) {
                    // Should use the event listeners
                    console.log(value);
                    var ih = `<input type="hidden" name="cols[]" value="${value}">`
                    $("#div-col").append(ih)
                },
                removeEvent: function (value) {
                    // Should use the event listeners
                    var ih = $("#div-col").find(`input[type=hidden][value=${value}]`)
                    ih.remove()
                    console.log(ih);
                    console.log(value);
                },
                availableTitle: "Columns",
                selectedTitle: "Selected Columns",
                addButtonText: ">",
                removeButtonText: "<",
                addAllButtonText: ">>",
                removeAllButtonText: "<<",
                sortable: true,
                upButtonText: "ᐱ",
                downButtonText: "ᐯ",
                draggable: true,
            })

            $("#btn-search").click(function(){
                $("#table-result").html("")
                Swal.fire({
                    title: "Searching",
                    text: "Please wait until the data show up",
                    onOpen: function() {
                        Swal.showLoading()
                    }
                })
                $.ajax({
                    url : "{{ route('employee.report.post') }}",
                    type : "post",
                    data : $("#form-filter").serialize(),
                    success : function(resp){
                        swal.close()
                        if(!resp.status){
                            Swal.fire("Error", resp.message, "error")
                        } else {
                            $("#table-result").html(resp.view)
                            $("#table-result table.display").DataTable({
                                dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
                                    <'row'<'col-sm-12'tr>>
                                    <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
                                buttons: [
                                    'excelHtml5'
                                ],
                            })
                        }
                    }
                })
            })
        })
    </script>
@endsection
