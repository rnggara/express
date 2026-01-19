@csrf
<input type="hidden" name="type" value="experience">
@if (!empty($_detail))
    <input type="hidden" name="id" value="{{ $_detail->id }}">
@endif

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Perusahaan</label>
            <input type="text" name="company" placeholder="Input nama perusahaan" class="form-control" value="{{ $_detail->company ?? "" }}" required>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Gaji Terakhir</label>
            <input type="text" name="salary" placeholder="Input Gaji" class="form-control number" value="{{ number_format($_detail->salary ?? 0, 0, ".", "") }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Posisi</label>
            <input type="text" name="position" placeholder="Input Posisi" class="form-control" value="{{ $_detail->position ?? "" }}" required>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Jabatan</label>
            <select name="job_level" data-control="select2" data-hide-search="true" class="form-control" required>
                <option value="">Pilih Jabatan</option>
                @foreach ($_jabatan as $i => $item)
                    <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->job_level == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Tipe Pekerjaan</label>
            <select name="job_type" class="form-control" data-control="select2" data-hide-search="true" required>
                <option value="">Pilih tipe pekerjaan</option>
                @foreach ($_job_type as $i => $item)
                    <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->job_type == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Spesialisasi</label>
            <select name="specialization" class="form-control" data-control="select2" data-hide-search="true" required>
                <option value="">Pilih spesialisasi</option>
                @foreach ($_specialization as $i => $item)
                    <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->specialization == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Industri</label>
            <select name="industry" class="form-control" data-control="select2" required>
                <option value="">Pilih Industri</option>
                @foreach ($_industry as $i => $item)
                    <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->industry == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Periode Kerja</label>
            <div class="row">
                <div class="col-md-2 col-sm-12">
                    <select name="start_month" data-control="select2" data-hide-search="true" class="form-control" data-placeholder="Bulan" required>
                        <option value=""></option>
                        @foreach ($idFullMonth as $i => $item)
                            <option value="{{ sprintf("%02d", $i) }}" {{ !empty($_detail) && date("n", strtotime($_detail->start_date)) == $i ? "SELECTED" : "" }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-12">
                    <select name="start_year" data-control="select2" data-hide-search="true" class="form-control" data-placeholder="Tahun" required>
                        <option value=""></option>
                        @for ($i = date("Y"); $i >= date("Y") - 50; $i--)
                            <option value="{{ $i }}" {{ !empty($_detail) && date("Y", strtotime($_detail->start_date)) == $i ? "SELECTED" : "" }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 col-sm-12 text-center">
                    Hingga
                </div>
                <div class="col-md-2 col-sm-12">
                    <select name="end_month" data-control="select2" data-hide-search="true" data-placeholder="Bulan" class="form-control" required>
                        <option value=""></option>
                        @foreach ($idFullMonth as $i => $item)
                            <option value="{{ sprintf("%02d", $i) }}" {{ !empty($_detail) && date("n", strtotime($_detail->end_date)) == $i ? "SELECTED" : "" }}>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-12">
                    <select name="end_year" data-control="select2" data-hide-search="true" data-placeholder="Tahun" class="form-control" required>
                        <option value=""></option>
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
                    Masih bekerja di sini
                </label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label required">Lokasi</label>
            <input type="text" name="location" placeholder="Input lokasi" class="form-control" required value="{{ $_detail->location ?? "" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Deskripsi</label>
            <textarea name="descriptions" placeholder="Masukkan deskripsi pekerjaan" class="form-control" id="" cols="30" rows="10" required>{!! $_detail->descriptions ?? "" !!}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Prestasi Kerja <span class="text-muted">(optional)</span></label>
            <textarea name="achievements" placeholder="Masukkan prestasi kerja" class="form-control" id="" cols="30" rows="10">{!! $_detail->achievements ?? "" !!}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Referensi</label>
            <input type="text" name="reference" placeholder="Input nama referensi" class="form-control" value="{{ $_detail->reference ?? "" }}">
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Nomor Telepon</label>
            <input type="text" name="phone" placeholder="Input nomor telepon referensi" class="form-control" required value="{{ $_detail->phone ?? "" }}">
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Posisi / Jabatan</label>
            <input type="text" name="ref_pos" placeholder="Input nama posisi / jabatan" class="form-control" value="{{ $_detail->ref_pos ?? "" }}">
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Alasan resign</label>
            <input type="text" name="resign_reason" placeholder="Input alasan resign" class="form-control" value="{{ $_detail->resign_reason ?? "" }}">
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
