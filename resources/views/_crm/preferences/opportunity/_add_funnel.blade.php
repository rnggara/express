<div class="funnel-item">
    <hr>
    <div class="row row-cols-1 row-cols-md-2">
        <div class="col">
            <div class="fv-row">
                <label for="funnel_name" class="col-form-label required">Funnel</label>
                <input type="text" class="form-control" name="funnel_name[]" value="{{ $funnel_name ?? "" }}" required placeholder="Input funnel name">
            </div>
        </div>
        <div class="col">
            <div class="fv-row">
                <label for="status_funnel" class="col-form-label">Status Funnel</label>
                <select name="status_funnel[]" class="form-select" data-hide-search="true" data-control="select2" data-placeholder="Lose">
                    <option value=""></option>
                    <option value="-1" {{ !empty($status) && $status == "-1" ? "SELECTED" : "" }} >Lose</option>
                    <option value="0" {{ !empty($status) && $status == "0" ? "SELECTED" : "" }} >Process</option>
                    <option value="1" {{ !empty($status) && $status == "1" ? "SELECTED" : "" }} >Won</option>
                </select>
            </div>
        </div>
        <div class="col">
            <div class="fv-row">
                <label for="idle_state" class="col-form-label">Set idle state <i class="la la-info-circle text-dark fs-4" data-bs-toggle="tooltip" data-bs-placement="top" title="An 'idle state' is when an opportunity doesn't progress to the next stage within a set number of days. When this happens, the date it was last updated will turn yellow as a reminder."></i></label>
                <input type="text" class="form-control input-days" value="{{ $idle_state ?? "" }}"  name="idle_state[]" placeholder="0 day">
            </div>
        </div>
        <div class="col">
            <div class="fv-row">
                <label for="warning_state" class="col-form-label">Set warning state <i class="la la-info-circle text-dark fs-4" data-bs-toggle="tooltip" data-bs-placement="top" title="A 'warning state' means the opportunity hasn't moved to the next stage within a set number of days. When this happens, the date it was last updated turns red to alert you."></i></label>
                <input type="text" class="form-control input-days" value="{{ $warning_state ?? "" }}"  name="warning_state[]" placeholder="0 day">
            </div>
        </div>
        <div class="col">
            <input type="hidden" name="funnel_id[]" value="{{ $id ?? "" }}">
            <button type="button" onclick="remove_funnel(this, '{{ $id ?? '' }}')" class="btn px-0 text-danger">
                <i class="la la-trash text-danger fs-3"></i>
                Delete Funnel
            </button>
        </div>
    </div>
</div>
