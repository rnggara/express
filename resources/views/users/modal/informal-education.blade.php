@csrf
<input type="hidden" name="type" value="informal">
@if (!empty($_detail))
    <input type="hidden" name="id" value="{{ $_detail->id }}">
@endif

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Nama Pelatihan</label>
            <input type="text" name="course_name" class="form-control" value="{{ $_detail->course_name ?? "" }}" required>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Penyelenggara</label>
            <input type="text" name="vendor" class="form-control" value="{{ $_detail->vendor ?? "" }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Masa Pelatihan</label>
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
