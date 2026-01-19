@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Title</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route("employee.index") }}" class="btn btn-sm btn-success btn-icon">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-responsive-lg display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Employee Name</th>
                                <th>Employee Position</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userEmp as $i => $item)
                                @php
                                    $uemp = $emp->where("id", $item->emp_id)->first();
                                @endphp
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ $uemp->emp_name }}</td>
                                    <td>{{ $uemp->emp_position }}</td>
                                    <td align="center">
                                        <button type="button" data-toggle="modal" data-target="#modalAdd" onclick="modalAdd({{ $item->id }})" class="btn btn-sm btn-primary">
                                            Add Signature
                                        </button>
                                    </td>
                                </tr>
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
                    <h1>Upload Signature</h1>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('employee.user.update') }}" method="post" id="form-sign" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label text-right">Signature</label>
                            <div class="col-md-8">
                                <div class="radio-inline">
                                    <label class="radio radio-outline radio-success col-form-label">
                                        <input type="radio" name="signature" onclick="sketchOrUpload()" id="upload-attend" checked value="upload"/>
                                        <span></span>
                                        Upload
                                    </label>
                                    <label class="radio radio-outline radio-success col-form-label">
                                        <input type="radio" name="signature" onclick="sketchOrUpload()" id="sketch-attend" value="sketch"/>
                                        <span></span>
                                        Sketch
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="group-file-attend">
                            <label class="col-md-2 col-form-label text-right">Signature File</label>
                            <div class="col-sm-8">
                                <div class="custom-file">
                                    <input type="file" name="image" id="image" class="custom-file-input" required>
                                    <span class="custom-file-label">Choose File</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="group-sketch-attend">
                            <label class="col-md-2 col-form-label text-right">Signature Sketch</label>
                            <div class="d-flex flex-column">
                                <div class="wrapper border border-dark rounded">
                                    <canvas class="signature-pad"></canvas>
                                </div>
                                <br>
                                <button type="button" class="btn btn-primary btn-xs clear">Clear</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="user_id" id="user-id">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{asset('theme/assets/js/signature_pad.js')}}"></script>
    <script>
        function modalAdd(id){
            $("#user-id").val(id)
        }

        function sketchOrUpload(){
            var rad = $("input[name=signature]:checked").val()
            if(rad == "upload"){
                $("#group-file-attend").show()
                $("#group-sketch-attend").hide()
                $("#group-file-attend").find("input[type=file]").prop('required', true)
            } else {
                $("#group-file-attend").hide()
                $("#group-sketch-attend").show()
                $("#group-file-attend").find("input[type=file]").prop('required', false)
            }
        }
        $(document).ready(function(){
            $("table.display").DataTable()
            sketchOrUpload()
            var wrapper     = document.getElementById("form-sign"),
                saveButton  = wrapper.querySelector("[name=submit_guest]"),
                canvas      = wrapper.querySelector("canvas"),
                signaturePad;

            signaturePad    = new SignaturePad(canvas);

            $('button.clear').click(function() {
                signaturePad.clear();
            });

            $("#form-sign").submit(function(e){
                e.preventDefault()
                console.log()
                var ardata = null
                var rad = $("input[name=signature]:checked").val()
                var ctype = "application/x-www-form-urlencoded"
                var cc = true
                var pd = true
                if(rad == "upload"){
                    ctype = false
                    cc = false
                    pd = false
                    ardata = new FormData(wrapper)
                } else {
                    if (signaturePad.isEmpty()) {
                        return Swal.fire("Signature empty", "Please draw your signature", "error")
                    }
                    ardata = {
                        _token : "{{ csrf_token() }}",
                        user_id : $(this).find("input[name=user_id]").val(),
                        imgUri : signaturePad.toDataURL(),
                        signature : rad
                    }
                }
                $.ajax({
                    type    : 'POST',
                    url     : $(this).attr("action"),
                    data    : ardata,
                    dataType : "json",
                    contentType : ctype,
                    cache       :  cc,
                    processData :  pd,
                    success : function(result) {
                        if(result.success){
                            location.reload()
                        }
                    }
                });
            })
        })
    </script>
@endsection
