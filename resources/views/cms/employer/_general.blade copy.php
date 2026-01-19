<form action="{{ route("cms.employer.update") }}" method="post" class="form" id="form-post">
    <div class="row">
        <div class="col-12 mb-8">
            <div class="fv-row">
                <label for="wa_no" class="required form-label">Nomor WA {{ \Config::get("constants.APP_NAME") }}</label>
                <input type="text" name="wa_no" id="wa_no" class="form-control form-input" value="{{ $lp->wa_no ?? "" }}">
            </div>
        </div>
        <div class="col-12 text-right">
            @csrf
            <input type="hidden" name="type" value="general">
            <button type="submit" class="btn btn-primary">
                <span class="indicator-label">
                    Simpan
                </span>
                <span class="indicator-progress">
                    Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
</form>


@section('scripts')
    <script>
        init_validation("form-post")
        Inputmask({
            "mask" : "9999-9999-9999"
        }).mask("#wa_no");
    </script>
@endsection
