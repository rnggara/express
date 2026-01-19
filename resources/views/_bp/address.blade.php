@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Address</h3>
            <div class="card-toolbar">
                <button type="submit" form="form-address" class="btn btn-outline btn-outline-primary">
                    Simpan
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('be.address_post') }}" method="post" id="form-address">
                <input type="hidden" name="id" value="{{ $comp->id }}">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="fv-row">
                            <label class="col-form-label required">Address</label>
                            <textarea name="address" id="address" cols="30" rows="10" class="form-control" required>{{ $comp->address }}</textarea>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row">
                            <label class="col-form-label required">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" required value="{{ $comp->phone }}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row">
                            <label class="col-form-label required">Business Hours</label>
                            <textarea name="business_hours" id="business_hours" cols="30" rows="10" class="form-control">{!! $comp->p_subtitle !!}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.min.js") }}"></script>
    <script src="{{ asset("theme/assets/plugins/custom/tinymce/tinymce.bundle.js") }}"></script>
    <script>
        var options = {selector: "#business_hours", height : "480"};

        if ( KTThemeMode.getMode() === "dark" ) {
            options["skin"] = "oxide-dark";
            options["content_css"] = "dark";
        }

        tinymce.init(options);
        
    </script>
@endsection
