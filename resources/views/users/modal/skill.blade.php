@csrf
<input type="hidden" name="type" value="{{ $_name }}">
@if (!empty($_detail))
    <input type="hidden" name="id" value="{{ $_detail->id }}">
@endif

@if ($_name == "skill")
    <div class="row">
        <div class="col-md-8 col-sm-12">
            <div class="form-group">
                <label for="skill" class="col-form-label required">Kemampuan Khusus</label>
                <input type="text" class="form-control" placeholder="Masukkan Kemampuan Khusus" name="skill_name" required value="{{ $_detail->skill_name ?? "" }}">
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label for="proficiency" class="col-form-label required">Keahlian</label>
                <select name="proficiency" data-control="select2" data-dropdown-parent="#modalShow" data-hide-search="true" class="form-control" required>
                    <option value="">Keahlian</option>
                    @foreach ($proficiency as $item)
                        <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->proficiency == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
@else
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="language" class="col-form-label required">Bahasa</label>
            <select name="language" data-control="select2" data-dropdown-parent="#modalShow" data-hide-search="true" class="form-control" required>
                <option value="">Pilih Bahasa</option>
                @foreach ($languages as $item)
                    <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->language == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
            <label for="writing" class="col-form-label required">Kemampuan Menulis</label>
            <select name="writing" data-control="select2" data-dropdown-parent="#modalShow" data-hide-search="true" class="form-select" required>
                <option value="">Kemampuan</option>
                @for ($item = 1; $item <= 5; $item++)
                    <option value="{{ $item }}" {{ !empty($_detail) && $_detail->writing == $item ? "SELECTED" : "" }}>{{ $item }}</option>
                @endfor
            </select>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
            <label for="speaking" class="col-form-label required">Kemampuan Berbicara</label>
            <select name="speaking" data-control="select2" data-dropdown-parent="#modalShow" data-hide-search="true" class="form-select" required>
                <option value="">Kemampuan</option>
                @for ($item = 1; $item <= 5; $item++)
                    <option value="{{ $item }}" {{ !empty($_detail) && $_detail->speaking == $item ? "SELECTED" : "" }}>{{ $item }}</option>
                @endfor
            </select>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
            <label for="reading" class="col-form-label required">Kemampuan Membaca</label>
            <select name="reading" data-control="select2" data-dropdown-parent="#modalShow" data-hide-search="true" class="form-select" required>
                <option value="">Kemampuan</option>
                @for ($item = 1; $item <= 5; $item++)
                    <option value="{{ $item }}" {{ !empty($_detail) && $_detail->reading == $item ? "SELECTED" : "" }}>{{ $item }}</option>
                @endfor
            </select>
        </div>
    </div>
</div>
@endif
