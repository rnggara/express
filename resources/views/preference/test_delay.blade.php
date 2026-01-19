<div class="card-header">
    <h3 class="card-title">Delay Re-take Test</h3>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-8 mx-auto">
            <form action="{{ route('pref.test.save') }}" method="post">
                @csrf
                <div class="form-group row">
                    <label for="" class="col-form-label col-3">Re-take Test after</label>
                    <div class="col-9">
                        <div class="input-group">
                            <input type="text" class="form-control" name="days" value="{{ $preferences->delay_retake_test ?? 0 }}" required>
                            <div class="input-group-append">
                                <span class="input-group-text">Days</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-5">
                    <label for="" class="col-form-label col-3"></label>
                    <div class="col-9">
                        <input type="hidden" name="id_comp" value="{{ $preferences->id_company }}">
                        <button type="submit" class="btn btn-primary" onclick="_post()">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
