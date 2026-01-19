<div class="row">
    <div class="fv-row col-6">
        <label class="col-form-label">Barcode ID</label>
        <input type="text" name="id_card" class="form-control" placeholder="Input Barcode ID">
        <span>ID you will get from attendance machine</span>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Workgroup Attendance</label>
        <select name="workgroup" class="form-select" data-control="select2" data-placeholder="Select Workgroup">
            <option value=""></option>
            @foreach ($workgroups as $item)
                <option value="{{ $item->id }}">{{ $item->workgroup_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Leave Group</label>
        <select name="leavegroup" class="form-select" data-control="select2" data-placeholder="Select Leave Group">
            <option value=""></option>
            @foreach ($leavegroup as $item)
                <option value="{{ $item->id }}">{{ $item->leave_group_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Date Join Group</label>
        <input type="text" name="date_join_group" class="form-control tempusDominus" id="date_join_group" placeholder="Input Date">
    </div>
    <div class="fv-row">
        <label class="col-form-label">Apakah bisa absen melalui mobile app?</label>
        <div class="d-flex align-items-center">
            <div class="form-check form-check-custom form-check-solid">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="mobile_att" value="1"/>
                    Ya
                </label>
            </div>
            <div class="form-check form-check-custom form-check-solid">
                <label class="form-check-label">
                    <input class="form-check-input" checked type="radio" name="mobile_att" value="0"/>
                    Tidak
                </label>
            </div>
        </div>
    </div>
    <div class="fv-row d-none" data-mobile>
        <label class="col-form-label">Pilih tempat untuk melakukan absensi online</label>
        <div class="row">
            @foreach ($loc as $item)
                <div class="col-6 mb-5">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="locations[]" value="{{ $item->id }}"/>
                        {{ $item->name }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="fv-row d-none" data-mobile>
        <label class="col-form-label">Apakah bisa "work from anywhere"?</label>
        <div class="d-flex align-items-center">
            <div class="form-check form-check-custom form-check-solid">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="wfa" value="1"/>
                    Ya
                </label>
            </div>
            <div class="form-check form-check-custom form-check-solid">
                <label class="form-check-label">
                    <input class="form-check-input" checked type="radio" name="wfa" value="0"/>
                    Tidak
                </label>
            </div>
        </div>
    </div>
</div>
