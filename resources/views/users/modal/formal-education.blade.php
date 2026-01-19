@csrf
<input type="hidden" name="type" value="formal">
@if (!empty($_detail))
    <input type="hidden" name="id" value="{{ $_detail->id }}">
@endif

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Tingkat Pendidikan</label>
            <select name="degree" class="form-control" required data-control="select2" data-hide-search="true">
                <option value="">Pilih</option>
                @foreach ($ledu as $item)
                    <option value="{{ $item->name }}" {{ !empty($_detail) && $_detail->degree == $item->name ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Jurusan</label>
            <input type="text" name="field_of_study" class="form-control" value="{{ $_detail->field_of_study ?? "" }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">IPK</label>
            <input type="text" name="grade" class="form-control number" value="{{ $_detail->grade ?? "" }}">
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Nama Institusi</label>
            <input type="text" name="school_name" class="form-control" value="{{ $_detail->school_name ?? "" }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Masa Belajar</label>
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    <select name="start_month" class="form-control" required data-control="select2" data-hide-search="true">
                        <option value="">Bulan</option>
                        @foreach ($idFullMonth as $i => $item)
                            <option value="{{ sprintf("%02d", $i) }}" {{ !empty($_detail) && date("n", strtotime($_detail->start_date)) == $i ? "SELECTED" : "" }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-12">
                    <select name="start_year" class="form-control" required data-control="select2" data-hide-search="true">
                        <option value="">Tahun</option>
                        @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                            <option value="{{ $i }}" {{ !empty($_detail) && date("Y", strtotime($_detail->start_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 col-sm-12 text-center">
                    Hingga
                </div>
                <div class="col-md-2 col-sm-12">
                    <select name="end_month" class="form-control" required data-control="select2" data-hide-search="true">
                        <option value="">Bulan</option>
                        @foreach ($idFullMonth as $i => $item)
                            <option value="{{ sprintf("%02d", $i) }}" {{ !empty($_detail) && date("n", strtotime($_detail->end_date)) == $i ? "SELECTED" : "" }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-12">
                    <select name="end_year" class="form-control" required data-control="select2" data-hide-search="true">
                        <option value="">Tahun</option>
                        @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                            <option value="{{ $i }}" {{ !empty($_detail) && date("Y", strtotime($_detail->end_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <div class="checkbox-inline col-form-label">
                <label class="checkbox checkbox-success">
                    <input type="checkbox" name="still" {{ !empty($_detail) && $_detail->still ? "CHECKED" : "" }} id="still"/>
                    <span></span>
                    Saat ini saya sedang mengejar gelar ini
                </label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Deskripsi <span class="text-muted">(optional)</span></label>
            <textarea name="descriptions" class="form-control" id="" cols="30" rows="10">{!! $_detail->descriptions ?? "" !!}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label col-12">Upload Dokumen</label>
            <label for="file-upload" class="btn btn-secondary btn-sm">
                <i class="flaticon-attachment"></i>
                Attachments
            </label>
            <span class="text-muted">format : JPG, PNG, PDF</span>
            <input id="file-upload" style="display: none" name="attachments" accept=".jpg, .png, .pdf" type="file"/>
        </div>
    </div>
</div>
