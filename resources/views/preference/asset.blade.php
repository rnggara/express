<div class="card-header">
    <h3 class="card-title">Asset</h3>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-8">
            <form action="{{ route('pref.asset.save') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="" class="col-form-label">Exclude From Parent</label>
                    <div class="checkbox-inline">
                        <label class="checkbox checkbox-primary">
                            <input type="checkbox" value="1" {{$preferences->asset_exclude_from_parent ? "CHECKED" : ""}} name="exclude"/>
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <input type="hidden" name="id_comp" value="{{ $preferences->id_company }}">
                    <button type="submit" class="btn btn-primary" onclick="_post()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
