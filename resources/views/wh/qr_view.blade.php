@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Storage Detail</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-12 border mx-auto">
                    <table class="table table-borderless">
                        <tr>
                            <th colspan="3" class="text-center">
                                <div class="d-flex flex-column">
                                    @if (!empty($wh->featured_image))
                                        <img src="{{ str_replace("public", "public_html", asset($wh->featured_image)) }}" class="w-100 mb-3" alt="" srcset="">
                                    @endif
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalUpload">{{ empty($wh->featured_image) ? "Upload" : "Replace" }} Featured Image</button>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Storage name</th>
                            <td>:</td>
                            <td> {{ $wh->name }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>:</td>
                            <td> {!! $wh->address !!}</td>
                        </tr>
                        <tr>
                            <th>Telephone</th>
                            <td>:</td>
                            <td> {!! $wh->telephone ?? "-" !!}</td>
                        </tr>
                        <tr>
                            <th>PIC</th>
                            <td>:</td>
                            <td> {!! $wh->pic ?? "-" !!}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>:</td>
                            <td> {{ $wh_type }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalUpload" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Upload Featured Image</h1>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('wh.upload') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" value="{{ $wh->id }}">
                        <div class="custom-file">
                            <input type="file" name="featured_image" class="custom-file-input" required>
                            <span class="custom-file-label">Choose File</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
