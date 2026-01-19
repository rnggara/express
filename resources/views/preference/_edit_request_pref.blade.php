<div class="row">
    <div class="col-12">
        <div class="form-group row">
            <div class="col-md-3 col-sm-12">Modul</div>
            <div class="col-md-9 col-sm-12">
                <input type="hidden" name="id" value="{{ $pref->id }}">
                <input type="text" name="modul" class="form-control" value="{{ $pref->modul }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3 col-sm-12">Approval</div>
            <div class="col-md-9 col-sm-12">
                <input type="text" name="approval" class="form-control" value="{{ $pref->approval }}" readonly>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3 col-sm-12">Yellow Label</div>
            <div class="col-md-3 col-sm-12">
                <input type="number" name="yellow" min="0" class="form-control" value="{{ $pref->yellow }}">
            </div>
            <div class="col-md-6 col-sm-12 col-form-label">
                <span class="switch switch-sm switch-outline switch-icon switch-success">
                    <label>
                        <input type="checkbox" value="1" {{ ($pref->yellow_status == 1) ? "checked" : "" }} name="yellow_status"/>
                        <span></span>
                    </label>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3 col-sm-12">Red Label</div>
            <div class="col-md-3 col-sm-12">
                <input type="number" name="red" min="0" class="form-control" value="{{ $pref->red }}">
            </div>
            <div class="col-md-6 col-sm-12 col-form-label">
                <span class="switch switch-sm switch-outline switch-icon switch-success">
                    <label>
                        <input type="checkbox" value="1" {{ ($pref->red_status == 1) ? "checked" : "" }} name="red_status"/>
                        <span></span>
                    </label>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3 col-sm-12">Reject</div>
            <div class="col-md-3 col-sm-12">
                <input type="number" name="reject" min="0" class="form-control" value="{{ $pref->reject }}">
            </div>
            <div class="col-md-6 col-sm-12 col-form-label">
                <span class="switch switch-sm switch-outline switch-icon switch-success">
                    <label>
                        <input type="checkbox" value="1" {{ ($pref->reject_status == 1) ? "checked" : "" }} name="reject_status"/>
                        <span></span>
                    </label>
                </span>
            </div>
        </div>
    </div>
</div>
