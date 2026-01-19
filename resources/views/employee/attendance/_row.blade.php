<tr>
    <td>
        <select name="columns[]" data-control="select2" class="form-select">
            <option data-format="text" value="#">Nomor Urut</option>
            <option data-format="text" value="id">ID</option>
            <option data-format="text" value="employee_name">Nama Lengkap</option>
            <option data-format="text" value="company_id">ID Perusahaan</option>
            <option data-format="text" value="company_name">Nama Perusahaan</option>
            {{-- <option data-format="text" value="branch">Branch</option> --}}
            {{-- <option data-format="text" value="location_type">Lokasi</option> --}}
            <optgroup class="opt-line opt-single-line">
                <option data-format="datetime" value="check_in">Check in</option>
                <option data-format="datetime" value="check_out">Check out</option>
                <option data-format="datetime" value="break_in">Break in</option>
                <option data-format="datetime" value="break_in">Break out</option>
            </optgroup>
            <optgroup class="opt-line opt-separate-line">
                <option data-format="datetime" value="time">Time</option>
                <option data-format="text" value="type">Type</option>
            </optgroup>
        </select>
    </td>
    <td>
        <div class="format-columns format-text">
            <input type="text" name="format_selected[]" class="form-control" readonly value="text">
        </div>
        <div class="format-columns format-datetime" style="display: none">
            <select name="formats[]" data-control="select2" class="form-select">
                <option value="Y-m-d">Y-m-d ({{ date("Y-m-d") }})</option>
                <option value="d-m-Y">d-m-Y ({{ date("d-m-Y") }})</option>
                <option value="Y-m-d H:i:s">Y-m-d H:i:s ({{ date("Y-m-d H:i:s") }})</option>
                <option value="d-m-Y H:i:s">d-m-Y H:i:s ({{ date("d-m-Y H:i:s") }})</option>
                <option value="Y-m-d H:i">Y-m-d H:i ({{ date("Y-m-d H:i") }})</option>
                <option value="d-m-Y H:i">d-m-Y H:i ({{ date("d-m-Y H:i") }})</option>
                <option value="H:i:s">H:i:s ({{ date("H:i:s") }})</option>
                <option value="H:i">H:i ({{ date("H:i") }})</option>
            </select>
        </div>
    </td>
    <td align="center" class="text-nowrap">
        <button type="button" class="btn btn-icon btn-directions btn-sm btn-up btn-outline btn-outline-primary" onclick="change_order(this, 'prev')" style="display: none">
            <i class="fa fa-arrow-up"></i>
        </button>
        <button type="button" class="btn btn-icon btn-directions btn-sm btn-down btn-outline btn-outline-warning" onclick="change_order(this, 'next')" style="display: none">
            <i class="fa fa-arrow-down"></i>
        </button>
        <button type="button" class="btn btn-icon btn-sm btn-outline btn-outline-danger" onclick="remove_row(this)">
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
