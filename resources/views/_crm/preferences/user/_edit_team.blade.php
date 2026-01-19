<div class="fv-row">
    <label for="team_name" class="col-form-label required">Team Name</label>
    <input type="text" class="form-control" name="team_name" value="{{ $team->name }}" required placeholder="Input name">
</div>
<div class="fv-row" id="sel-pipeline-edit">
    <label for="pipeline_id_edit" class="col-form-label required">Pipeline</label>
    <select name="pipeline_id[]" multiple class="form-select" data-control="select2" data-dropdown-parent="#sel-pipeline-edit" id="pipeline_id_edit" data-placeholder="Select pipeline">
        <option value=""></option>
        @php
            $team_id = json_decode($team->pipeline_id ?? "[]", true);
        @endphp
        @foreach ($pipelines as $item)
            <option value="{{ $item->id }}" {{ in_array($item->id, $team_id) ? "SELECTED" : "" }}>{{ $item->label }}</option>
        @endforeach
    </select>
</div>
<div class="fv-row" id="sel-member-edit">
    <label for="team_member" class="col-form-label required">Add Member</label>
    <select name="team_member_sel" class="form-select" data-dropdown-parent="#sel-member-edit" id="team_member_edit" data-placeholder="Input member (search by name or job title)">
        <option value=""></option>
        <option value="_all" data-job="Select All User">Select All</option>
        @foreach ($users as $item)
            <option value="{{ $item->id }}"
                data-email="{{ $item->email }}"
                data-company="{{ $item->company->company_name }}"
                data-phone="{{ $item->phone ?? "-" }}"
                data-job="{{ $item->crmRole->name ?? "-" }}">{{ $item->name }}</option>
        @endforeach
    </select>
    <div id="member-list-edit" class="mt-3">
    </div>
</div>
<input type="hidden" name="id" value="{{ $team->id }}">
