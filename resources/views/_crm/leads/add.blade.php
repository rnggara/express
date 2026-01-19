@extends('layouts.templateCrm', ['withoutFooter' => true])

@section('content')
    @php
        $disabled = "";
        $isArchive = $leads->archive_at ?? null;
        if(!empty($isArchive)){
            $disabled = "disabled";
        }
    @endphp
    <div class="d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between bg-white p-5">
            <div class="d-flex align-items-center">
                <a href="{{ route('crm.lead.index') }}" class="btn btn-link">
                    <i class="fa fa-chevron-left fw-bold fs-3 me-5"></i>
                </a>
                {{ $leads->leads_name ?? 'Tambah Lead' }}

            </div>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-primary me-5" id="btn-simpan" {{$disabled}}>Simpan</button>
                @if (!empty($leads) && !empty($leads->status_deal))
                    <button type="button" class="btn btn-success me-5" {{$disabled}} data-bs-toggle="modal"
                        data-bs-target="#modalArsip">Arsipkan</button>
                @endif
                @if (!empty($leads))
                    <button type="button" class="btn btn-danger" {{$disabled}} data-bs-toggle="modal"
                        data-bs-target="#modalDelete">Hapus</button>
                @else
                    <button type="button" class="btn btn-danger" disabled>Hapus</button>
                @endif
            </div>
        </div>
    </div>
    <div class="d-flex {{ !empty($disabled) ? "blockui" : "" }} mb-8">
        @if($disabled != "")
            <div class="blockui-overlay ms-3" style="z-index: 1;"></div>
        @endif
        <div class="min-w-500px bg-white">
            <form class="form p-3" method="post" action="{{ route('crm.lead.store') }}" id="form-lead">
                @csrf
                @if (!empty($leads))
                    <input type="hidden" name="lead_id" value="{{ $leads->id }}">
                @endif
                <input type="hidden" name="layout_id" value="{{ $layoutid }}">
                <div class="fv-row">
                    <label for="nama_project" class="col-form-label required">Nama Pipeline</label>
                    <input type="text" class="form-control" required placeholder="Masukan Nama Pipeline"
                        value="{{ $leads->leads_name ?? '' }}" name="name">
                </div>
                <div class="fv-row">
                    <label for="tag" class="col-form-label">Tag</label>
                    <input type="text" class="form-control" id="tag-tagify" placeholder="Masukan tag"
                        value="{{ !empty($leads) ? ($leads->tags == "null" ? ""  : $leads->tags) : "" }}" name="tag">
                </div>
                <div class="fv-row">
                    <label for="funnels" class="col-form-label required">Lokasi Sales Funnel</label>
                    <select name="funnels" id="funnels" class="form-select" data-control="select2" required
                        data-placeholder="Pilih lokasi sales Funnel">
                        <option value=""></option>
                        @foreach ($funnels as $item)
                            <option value="{{ $item->id }}"
                                {{ $fid == $item->id ? 'SELECTED' : '' }}>{{ $item->label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row">
                    <label for="nominal_proyek" class="col-form-label">Nominal Proyek</label>
                    <input type="text" class="form-control number" placeholder="Masukan nominal proyek (IDR)"
                        value="{{ number_format($leads->nominal ?? 0, 2, ',', '.') }}" name="nominal_proyek">
                </div>
                <div class="fv-row">
                    <label for="estimasi_profit" class="col-form-label">Estimasi Profit</label>
                    <input type="text" class="form-control number" placeholder="Masukan estimasi profit (IDR)"
                        value="{{ number_format($leads->estimasi_profit ?? 0, 2, ',', '.') }}" name="estimasi_profit">
                </div>
                <div class="fv-row">
                    <label for="monitored_by" class="col-form-label required">Diawasi Oleh</label>
                    <select name="monitored_by" id="monitored_by" class="form-select" data-control="select2" required
                        data-placeholder="Pilih nama kontak">
                        <option value=""></option>
                        @foreach ($emp as $item)
                            <option value="{{ $item->id }}"
                                {{ !empty($leads) && $leads->partner == $item->id ? 'SELECTED' : '' }}>
                                {{ $item->emp_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row">
                    <label for="instruct_to" class="col-form-label">Kolaborator</label>
                    <select name="instruct_to" id="instruct_to" class="form-select mb-5" data-control="select2"
                        data-placeholder="Pilih nama Kolaborator">
                        <option value=""></option>
                        @foreach ($emp as $item)
                            <option value="{{ $item->id }}">{{ $item->emp_name }}</option>
                        @endforeach
                    </select>
                    <div id="div-instruct">
                        @if (!empty($leads))
                            @php
                                $kolabs = json_decode($leads->contributors);
                            @endphp
                            @foreach ($kolabs as $contr)
                                @php
                                    $_emp = $emp->where('id', $contr)->first();
                                @endphp
                                @if (!empty($_emp))
                                    <div class="d-flex flex-column mb-3">
                                        <span>{{ $_emp->emp_name }}</span>
                                        <span class="text-danger cursor-pointer"
                                            onclick="remove_instruct(this)">Hapus</span>
                                        <input type="hidden" name="instruct[]" value="{{ $contr }}">
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="fv-row">
                    <label for="sumber_project" class="col-form-label">Sumber Proyek</label>
                    <select name="sumber_project" id="sumber_project" class="form-select" data-control="select2"
                        data-placeholder="Pilih sumber proyek">
                        <option value=""></option>
                        <option value="Telpon" {{ !empty($leads) && $leads->sumber == 'Telpon' ? 'SELECTED' : '' }}>Telpon
                        </option>
                    </select>
                </div>
                <div class="fv-row">
                    <label for="start_date" class="col-form-label">Pilih Tanggal Mulai</label>
                    <input type="text" name="start_date" id="start_date" class="form-control tempusDominus"
                        value="{{ !empty($leads) ? date('d/m/Y', strtotime($leads->start_date)) : date('d/m/Y', strtotime('now')) }}"
                        placeholder="Pilih Tanggal Mulai">
                </div>
                <div class="fv-row">
                    <label for="end_date" class="col-form-label">Tanggal target closing</label>
                    <input type="text" name="end_date" id="end_date" class="form-control tempusDominus"
                        value="{{ !empty($leads) ? date('d/m/Y', strtotime($leads->end_date)) : '' }}"
                        placeholder="Pilih Tanggal target closing">
                </div>
                <div class="fv-row">
                    <label for="sales_confidence" class="col-form-label">Sales Confidence</label>
                    <select name="sales_confidence" id="sales_confidence" class="form-select" data-control="select2"
                        data-placeholder="Pilih Sales Confidence">
                        <option value=""></option>
                        @if (empty($leads->sales_confidence))
                            <option value="1">
                                Commit</option>
                            <option value="2">
                                Best case</option>
                            <option value="3">
                                Run through</option>
                            <option value="4" SELECTED>
                                Nice to</option>
                        @else
                            <option value="1"
                                {{ !empty($leads) && $leads->sales_confidence == '1' ? 'SELECTED' : '' }}>
                                Commit</option>
                            <option value="2"
                                {{ !empty($leads) && $leads->sales_confidence == '2' ? 'SELECTED' : '' }}>
                                Best case</option>
                            <option value="3"
                                {{ !empty($leads) && $leads->sales_confidence == '3' ? 'SELECTED' : '' }}>
                                Run through</option>
                            <option value="4"
                                {{ !empty($leads) && $leads->sales_confidence == '4' ? 'SELECTED' : '' }}>
                                Nice to</option>
                        @endif

                    </select>
                </div>
                <div class="fv-row">
                    <label for="level_priority" class="col-form-label">Tingkat Prioritas</label>
                    <select name="level_priority" id="level_priority" class="form-select" data-control="select2"
                        data-placeholder="Pilih Tingkat Prioritas">
                        <option value=""></option>
                        @if (empty($leads->sales_confidence))
                            <option value="1">
                                Tinggi</option>
                            <option value="2">
                                Sedang</option>
                            <option value="3" SELECTED>
                                Rendah</option>
                        @else
                            <option value="1"
                                {{ !empty($leads) && $leads->sales_confidence == '1' ? 'SELECTED' : '' }}>
                                Tinggi</option>
                            <option value="2"
                                {{ !empty($leads) && $leads->sales_confidence == '2' ? 'SELECTED' : '' }}>
                                Sedang</option>
                            <option value="3"
                                {{ !empty($leads) && $leads->sales_confidence == '3' ? 'SELECTED' : '' }}>
                                Rendah</option>
                        @endif
                    </select>
                </div>
                <div class="fv-row">
                    <label for="status_deal" class="col-form-label">Status Deal</label>
                    <select name="status_deal" id="status_deal" class="form-select" data-control="select2"
                        data-placeholder="Pilih Status Deal">
                        <option value=""></option>
                        <option value="1" {{ !empty($leads) && $leads->status_deal == '1' ? 'SELECTED' : '' }}>
                            Berhasil</option>
                        <option value="-1" {{ !empty($leads) && $leads->status_deal == '-1' ? 'SELECTED' : '' }}>
                            Gagal</option>
                    </select>
                </div>
                <div class="fv-row">
                    <label for="deal_date" class="col-form-label">Tanggal Deal</label>
                    <input type="text" name="deal_date" id="deal_date" class="form-control tempusDominus"
                        value="{{ !empty($leads) ? date('d/m/Y', strtotime($leads->deal_date)) : '' }}"
                        placeholder="Pilih Tanggal Deal">
                </div>
                <div class="fv-row">
                    <label for="failed_reasons" class="col-form-label">Alasan Gagal</label>
                    <input type="text" name="failed_reasons" id="failed_reasons" class="form-control"
                        value="{{ $leads->failed_reason ?? '' }}" placeholder="Masukkan Alasan Gagal">
                </div>
                <div class="separator separator-solid my-5"></div>
                <div class="fv-row">
                    <label for="company_name" class="col-form-label required">Nama Perusahaan</label>
                    <div class="input-group">
                        <input type="text" name="company_name" id="company_name"
                            value="{{ $leadComp->company_name ?? '' }}" class="form-control border-right-0" required
                            placeholder="Masukkan Nama Perusahaan" autocomplete="off">
                        <div class="input-group-text bg-transparent">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-add-company"
                                style="display: none">Tambah</button>
                        </div>
                    </div>
                    <input type="hidden" name="comp_id" value="{{ $leadComp->id ?? '' }}" id="comp_id">
                    <div id="autocomplete-div"></div>
                </div>
                <div class="separator separator-solid my-5"></div>
                <div class="fv-row">
                    <label for="pic_names" class="col-form-label required">Kontak Perorangan</label>
                    <div class="input-group mb-5">
                        <input type="text" name="pic_names" id="pic_names" data-target="contacts[]" class="form-control border-right-0"
                            required placeholder="Masukkan Kontak Perorangan">
                        <div class="input-group-text bg-transparent">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-add-contact"
                                style="display: none">Tambah</button>
                        </div>
                    </div>
                    <input type="hidden" name="con_id" id="con_id">
                    <div id="autocomplete-div-contact"></div>
                    <div id="div-contact">
                        @foreach ($contacts as $item)
                            <div class="d-flex flex-column mb-3">
                                <span>{{ $item->name }}</span>
                                <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                                <input type="hidden" name="contacts[]" value="{{ $item->id }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="separator separator-solid my-5"></div>
                <div class="fv-row">
                    <label for="products" class="col-form-label required">Produk</label>
                    <select name="products" id="products" class="form-select mb-5" data-control="select2" required
                        data-placeholder="Pilih Produk">
                        <option value=""></option>
                        @foreach ($products as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach
                    </select>
                    <div id="div-products">
                        @foreach ($leadProducts as $item)
                            <div class="d-flex border rounded p-3 mb-3 justify-content-between align-items-center">
                                <div class="d-flex">
                                    <i class="ki-duotone ki-cube-3 fs-2 me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                    <span>{{ $item->label }}</span>
                                </div>
                                <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                                <input type="hidden" name="products_id[]" value="{{ $item->id }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
        <div class="flex-fill p-10" style="background-color: var(--bs-page-bg)">
            <div class="row d-flex flex-column bg-white {{ $disabled != "" ? 'blockui' : "" }} {{ empty($leads) ? 'blockui' : '' }}" id="div-activity">
                <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_notes">
                            <span class="nav-icon">
                                <i class="fa fa-note-sticky"></i>
                            </span>
                            <span class="nav-text">Notes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_task">
                            <span class="nav-icon">
                                <i class="fa fa-tasks"></i>
                            </span>
                            <span class="nav-text">Task</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_files">
                            <span class="nav-icon">
                                <i class="fa fa-file"></i>
                            </span>
                            <span class="nav-text">Files</span>
                        </a>
                    </li>
                </ul>
                <div class="separator separator-solid"></div>

                <div class="tab-content" id="myTabContent" style="padding: 0">
                    <div class="tab-pane fade show active" id="tab_notes" role="tabpanel">
                        <form action="{{ route('crm.lead.notes.add') }}" method="post" enctype="multipart/form-data">
                            <div class="border">
                                <div class="fv-row">
                                    <input type="text" placeholder="Add person" name="persons" required
                                        id="notes_persons" class="form-control border-0">
                                </div>
                                <div class="separator separator-solid"></div>
                                <div class="fv-row">
                                    <textarea name="notes" id="notes" class="form-controll ck-editor" placeholder="Masukan notes disini"
                                        cols="30" rows="10"></textarea>
                                </div>
                                <div class="separator separator-solid"></div>
                                <div class="fv-row p-5 upload-file">
                                    <label for="notes-file" data-toggle="upload_file"
                                        class="btn btn-outline btn-outline-primary btn-sm">
                                        <i class="fa fa-file"></i>
                                        Add File
                                    </label>
                                    <span class="upload-file-label">Max 25 mb</span>
                                    <input id="notes-file" style="display: none" data-toggle="upload_file"
                                        name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end pt-5" style="background-color: var(--bs-page-bg)">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $leads->id ?? '' }}">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="tab_task" role="tabpanel">
                        <form action="{{ route('crm.lead.task.add') }}" method="post" enctype="multipart/form-data">
                            <div class="border">
                                <div class="fv-row">
                                    <input type="text" placeholder="Nama Task" name="task" required
                                        class="form-control border-0">
                                </div>
                                <div class="separator separator-solid"></div>
                                <div class="fv-row">
                                    <input type="text" placeholder="Add person" name="persons" required
                                        id="task_persons" class="form-control border-0">
                                </div>
                                <div class="separator separator-solid"></div>
                                <div class="fv-row">
                                    <textarea name="notes" id="notes" class="form-controll ck-editor" placeholder="Masukan detail task disini"
                                        cols="30" rows="10"></textarea>
                                </div>
                                <div class="separator separator-solid"></div>
                                <div class="row px-5 pb-5">
                                    <div class="col-6 fv-row">
                                        <label for="status" class="col-form-label">Status</label>
                                        <select name="status" id="status" class="form-select" data-control="select2"
                                            data-placeholder="Pilih Status">
                                            <option value=""></option>
                                            <option value="1">Belum Dimulai</option>
                                            <option value="2">Proses Pengerjaan</option>
                                            <option value="3">Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label for="prioritas" class="col-form-label">Prioritas</label>
                                        <select name="prioritas" id="prioritas" class="form-select"
                                            data-control="select2" data-placeholder="Pilih Prioritas">
                                            <option value=""></option>
                                            <option value="1">Tinggi</option>
                                            <option value="2">Sedang</option>
                                            <option value="3">Rendah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="separator separator-solid"></div>
                                <div class="row px-5 pb-5">
                                    <div class="col-4 fv-row">
                                        <label for="tanggal_tenggat" class="col-form-label">Tanggal Tenggat</label>
                                        <input type="text" name="tanggal_tenggat" placeholder="Tanggal Tenggat"
                                            class="form-control tempusDominus" id="tanggal_tenggat">
                                    </div>
                                    <div class="col-4 fv-row">
                                        <label for="waktu_tenggat" class="col-form-label">Waktu Tenggat</label>
                                        <input type="time" name="waktu_tenggat" placeholder="Waktu Tenggat"
                                            class="form-control" id="waktu_tenggat">
                                    </div>
                                    <div class="col-4 fv-row">
                                        <label for="reminders" class="col-form-label">Pengingat</label>
                                        <select name="reminders" id="reminders" class="form-select"
                                            data-control="select2" data-placeholder="Pilih Prioritas">
                                            <option value=""></option>
                                            <option value="1">1 hari sebelumnya</option>
                                            <option value="2">2 hari sebelumnya</option>
                                            <option value="3">3 hari sebelumnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="separator separator-solid"></div>
                                <div class="fv-row p-5 upload-file">
                                    <label for="task-file" data-toggle="upload_file"
                                        class="btn btn-outline btn-outline-primary btn-sm">
                                        <i class="fa fa-file"></i>
                                        Add File
                                    </label>
                                    <span class="upload-file-label">Max 25 mb</span>
                                    <input id="task-file" style="display: none" data-toggle="upload_file"
                                        name="attachment_task" accept=".jpg, .png, .pdf" type="file" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end pt-5" style="background-color: var(--bs-page-bg)">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $leads->id ?? '' }}">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="tab_files" role="tabpanel">
                        <form action="{{ route('crm.lead.file.add') }}" method="post" enctype="multipart/form-data">
                            <div class="border">
                                <div class="fv-row p-5 upload-file">
                                    <label for="lead-file" data-toggle="upload_file"
                                        class="btn btn-outline btn-outline-primary btn-sm">
                                        <i class="fa fa-file"></i>
                                        Add File
                                    </label>
                                    <span class="upload-file-label">Max 25 mb</span>
                                    <input id="lead-file" style="display: none" data-toggle="upload_file"
                                        name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end pt-5" style="background-color: var(--bs-page-bg)">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $leads->id ?? '' }}">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty($leads)
                    <div class="blockui-overlay " style="z-index: 1;"></div>
                @endempty
                @if (!empty($leads))
                    <div class="py-5" style="background-color: var(--bs-page-bg)">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="timeline-3">
                                    @foreach ($activity as $item)
                                        <li>
                                            <div class="d-flex flex-column">
                                                <span class="mb-5">
                                                    <span class="fw-bold">{{ $item['user'] }}</span>
                                                    @if ($item['type'] == 'create')
                                                        membuat project <span
                                                            class="fw-bold">{{ $leads->leads_name }}</span>
                                                    @elseif($item['type'] == 'task')
                                                        membuat task di <span
                                                            class="fw-bold">{{ $leads->leads_name }}</span>
                                                    @elseif($item['type'] == 'notes')
                                                        membuat note di <span
                                                            class="fw-bold">{{ $leads->leads_name }}</span>
                                                    @elseif($item['type'] == 'files')
                                                        mengupload file di <span
                                                            class="fw-bold">{{ $leads->leads_name }}</span>
                                                    @endif
                                                </span>
                                                <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold">
                                                    <li class="breadcrumb-item"><span>@dayId($item['date']),
                                                            {{ date('d/m/Y H:i', strtotime($item['item']['created_at'])) }}</span>
                                                    </li>
                                                    @if (in_array($item['type'], ['notes', 'task']))
                                                        @if (!empty($item['item']['persons']))
                                                            <li class="breadcrumb-item">
                                                                @foreach (json_decode($item['item']['persons'], true) as $i => $pr)
                                                                    {{ $pr['value'] }}@if ($i < count(json_decode($item['item']['persons'], true)) - 1)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </li>
                                                        @endif
                                                        @if ($item['type'] == 'task')
                                                            <li class="breadcrumb-item">
                                                                <span class="text-danger">Deadline:
                                                                    {{ date('d/m/Y', strtotime($item['item']['due_date'])) }}
                                                                    |
                                                                    {{ date('H:i', strtotime($item['item']['due_date'])) }}</span>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ol>
                                                @if ($item['type'] != 'create')
                                                    <div class="border bg-white rounded p-3">
                                                        @if ($item['type'] == 'notes')
                                                            {!! $item['item']['notes'] !!}
                                                        @elseif($item['type'] == 'task')
                                                            <div class="d-flex flex-column">
                                                                <span class="fw-bold mb-3">{!! $item['item']['title'] !!}</span>
                                                                <span>{!! $item['item']['notes'] !!}</span>
                                                            </div>
                                                        @endif
                                                        @if (!empty($item['item']['file_name']))
                                                            @if ($item['type'] != 'files')
                                                                <div class="separator separator-solid mb-3"></div>
                                                            @endif
                                                            <div class="d-flex align-items-center mb-3">
                                                                <i class="fa fa-file-pdf me-3 text-primary fs-2"></i>
                                                                <a href="{{ asset($item['item']['file_address']) }}"
                                                                    class="btn btn-link text-primary">{{ $item['item']['file_name'] }}</a>
                                                            </div>
                                                        @endif
                                                        @if ($item['type'] == 'task')
                                                            @php
                                                                $prty = '-';
                                                                $ptyClass = '';
                                                                $el = $item['item'];
                                                                if ($el['priority'] == 1) {
                                                                    $ptyClass = 'success';
                                                                    $prty = 'Tinggi';
                                                                } elseif ($el['priority'] == 2) {
                                                                    $ptyClass = 'warning';
                                                                    $prty = 'Sedang';
                                                                } elseif ($el['priority'] == 3) {
                                                                    $ptyClass = 'danger';
                                                                    $prty = 'Rendah';
                                                                }

                                                                $stts = '-';
                                                                $stClass = '-';
                                                                if ($el['status'] == 1) {
                                                                    $stClass = 'red';
                                                                    $stts = 'Belum dimulai';
                                                                } elseif ($el['status'] == 2) {
                                                                    $stClass = 'yellow';
                                                                    $stts = 'Proses Pengerjaan';
                                                                } elseif ($el['status'] == 3) {
                                                                    $stClass = 'green';
                                                                    $stts = 'Selesai';
                                                                }
                                                            @endphp
                                                            <div class="separator separator-solid mb-3"></div>
                                                            <div class="d-flex align-items-center mt-3">
                                                                <span class="me-5">Status : <span
                                                                        class="badge badge-outline badge-{{ $stClass }}">{{ $stts }}</span></span>
                                                                <span class="me-5">Prioritas : <span
                                                                        class="badge badge-outline badge-{{ $ptyClass }}">{{ $prty }}</span></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @php
                                                        $_comment = $comment_content[$item['type']] ?? [];
                                                        $replies = [];
                                                        if(!empty($_comment)){
                                                            $replies = $_comment[$item['item']['id']] ?? [];
                                                        }
                                                    @endphp
                                                    <div class="d-flex justify-content-between comment-header">
                                                        <div class="d-flex">
                                                            <button type="button" class="btn text-primary me-3" onclick="openComment(this)">
                                                                Reply
                                                            </button>
                                                            @if (count($replies) > 0)
                                                                <button type="button" class="btn" data-type="{{ $item['type'] }}" data-id="{{ $item['item']['id'] }}" onclick="openReplies(this)">
                                                                    <span class="reply-close">{{ count($replies) }} replies</span>
                                                                    <span class="reply-show" style="display: none">Close</span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex">
                                                            {{-- <button type="button" class="btn text-danger">
                                                                Delete
                                                            </button> --}}
                                                        </div>
                                                    </div>
                                                    <div class="comment-section mb-5" style="display: none;">
                                                        <form action="{{ route("crm.comment.add") }}" method="post" enctype="multipart/form-data">
                                                            <div class="d-flex flex-column">
                                                                <div class="fv-row mb-3">
                                                                    <input type="text" name="comment" class="form-control" required placeholder="Tulis pesan disini">
                                                                </div>
                                                                <div class="fv-row mb-5 upload-file">
                                                                    <label for="{{ $item['item']['id'] }}" data-toggle="upload_file"
                                                                        class="btn btn-outline btn-outline-primary btn-sm">
                                                                        <i class="fa fa-file"></i>
                                                                        Add File
                                                                    </label>
                                                                    <span class="upload-file-label">Max 25 mb</span>
                                                                    <input id="{{ $item['item']['id'] }}" style="display: none" data-toggle="upload_file"
                                                                        name="attachments" accept=".jpg, .png, .pdf" type="file" />
                                                                </div>
                                                                <div class="d-flex justify-content-end">
                                                                    <input type="hidden" name="lead_id" value="{{ $leads->id }}">
                                                                    <input type="hidden" name="content_id" value="{{ $item['item']['id'] }}">
                                                                    <input type="hidden" name="content_type" value="{{ $item['type'] }}">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary me-5">Kirim</button>
                                                                    <button type="button" onclick="closeComment(this)" class="btn text-primary">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="comment-data d-flex flex-column ms-10"></div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="modalDelete">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                                            <span class="fw-bold fs-2">Hapus Lead</span>
                                        </div>
                                        <span>Apakah kamu yakin akan menghapus {{ $leads->leads_name }}?</span>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <a href="{{ route('crm.lead.delete', $leads->id) }}" class="btn btn-danger">Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="modalArsip">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-info-circle text-success fs-2 me-3"></i>
                                            <span class="fw-bold fs-2">Arsipkan Lead</span>
                                        </div>
                                        <span>Apakah kamu yakin akan mengarsipkan {{ $leads->leads_name }}?</span>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <a href="{{ route('crm.lead.archive', $leads->id) }}"
                                        class="btn btn-success">Arsipkan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" tabindex="-1" id="modalDeleteComment">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                                            <span class="fw-bold fs-2">Hapus Komentar</span>
                                        </div>
                                        <span>Apakah kamu yakin akan menghapus komentar?</span>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <a href="#" class="btn btn-danger">Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <link href="{{ asset('theme/jquery-ui/jquery-ui.css') }}" rel="Stylesheet">
    <script src="{{ asset('theme/jquery-ui/jquery-ui.js') }}"></script>
    <script>

        var validator = validate_form("form-lead")

        function deleteComment(me){
            var id = $(me).data('id');
            $('#modalDeleteComment').modal('show');
            $('.modal-footer a').attr('href', "{{ route('crm.comment.delete') }}/" + id);
        }

        function openReplies(me){
            var show = false;
            if($(me).find(".reply-show").is(":hidden")){
                $(me).find(".reply-show").show()
                $(me).find(".reply-close").hide()
                show = true
            } else {
                $(me).find(".reply-show").hide()
                $(me).find(".reply-close").show()
            }

            console.log(show)

            var tp = $(me).data("type")
            var id = $(me).data("id")
            var comment = $(me).data("comment")

            var chead = $(me).parents(".comment-header")
            var csec = $(chead).parent().find(".comment-data")

            var url = "{{ route("crm.comment.view") }}/" + tp + "/" + id
            if(comment != undefined){
                url += "?comment=" + comment
            }

            csec.html("")
            csec.addClass("spinner-border")
            if(show){
                $.ajax({
                    url : url,
                    type : "get",
                    dataType : "json",
                    success : function(data){
                        console.log(data)
                        csec.removeClass("spinner-border")
                        csec.html(data.view)
                    }
                })
            } else {
                csec.removeClass("spinner-border")
            }
        }

        function closeComment(me){
            $(me).parents(".comment-section").hide()
        }

        function openComment(me){
            var chead = $(me).parents(".comment-header")
            var csec = $(chead).next()
            csec.show()
        }

        function init_tag() {
            $.ajax({
                url: encodeURI(
                    "{{ route('crm.lead.add', ['layoutid' => $layoutid, 'fid' => $fid, 'rowid' => $rowid]) }}?a=tags"
                    ),
                type: "get",
                dataType: "json"
            }).then(function(resp) {
                var tagify = new Tagify(document.querySelector("#tag-tagify"), {
                    whitelist: resp.tags,
                    dropdown: {
                        maxItems: 20, // <- mixumum allowed rendered suggestions
                        classname: "", // <- custom classname for this dropdown, so it could be targeted
                        enabled: 0, // <- show suggestions on focus
                        closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                    },
                    transformTag : function (tagData) {
                        console.log(tagData);
                        tagData.color = "--tag-bg: var(--bs-primary)"
                        tagData.style = "--tag-bg: var(--bs-primary); --tag-hover: var(--bs-primary); --tag-remove-bg: var(--bs-primary); --tag-text-color: #fff; --tag-remove-btn-bg--hover: var(--bs-primary)"
                    },
                    callbacks: {
                        "change": function(e) {}
                    }
                })
            })
        }

        function init_contacts(id) {
            $.ajax({
                url: encodeURI(
                    "{{ route('crm.lead.add', ['layoutid' => $layoutid, 'fid' => $fid, 'rowid' => $rowid]) }}?a=contact&e=tag"
                    ),
                type: "get",
                dataType: "json"
            }).then(function(resp) {
                var tagify = new Tagify(document.querySelector(id), {
                    whitelist: resp,
                    enforceWhitelist: true,
                    dropdown: {
                        maxItems: 20, // <- mixumum allowed rendered suggestions
                        classname: "", // <- custom classname for this dropdown, so it could be targeted
                        enabled: 0, // <- show suggestions on focus
                        closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                    },
                    callbacks: {
                        "change": function(e) {}
                    }
                })
            })
        }

        function init_comp() {
            $("#company_name").autocomplete({
                source: encodeURI(
                    "{{ route('crm.lead.add', ['layoutid' => $layoutid, 'fid' => $fid, 'rowid' => $rowid]) }}?a=comp"
                    ),
                minLength: 1,
                appendTo: "#autocomplete-div",
                response: function(event, ui) {
                    console.log(event)
                },
                select: function(event, ui) {
                    $("#comp_id").val(ui.item.id)
                    $("#btn-add-company").show()
                }
            });
        }

        function init_contact() {
            $("#pic_names").autocomplete({
                source: encodeURI(
                    "{{ route('crm.lead.add', ['layoutid' => $layoutid, 'fid' => $fid, 'rowid' => $rowid]) }}?a=contact"
                    ),
                minLength: 1,
                appendTo: "#autocomplete-div-contact",
                select: function(event, ui) {
                    $("#con_id").val(ui.item.id)
                }
            });
        }

        function add_contact(item) {
            var el = `<div class="d-flex flex-column mb-3">
                <span>${item.name}</span>
                <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                <input type="hidden" name="contacts[]" value="${item.id}">
            </div>`

            $("#div-contact").append(el)
            $("#pic_names").val("")
        }

        function remove_instruct(me) {
            $(me).parent().remove()
            var inp = $("#div-contact").find("input[name='contacts[]']")
            if (inp.length == 0) {
                $("#pic_names").prop("required", true)
                validator.addField(
                    "pic_names",
                    {
                        validators: {
                            callback : {
                                message: `Kontak harus diisi`,
                                callback : function(value, validator, $field){
                                    var inp = $("#div-contact").find("input[name='contacts[]']")
                                    if(inp.length == 0){
                                        return false
                                    } else {
                                        return true
                                    }
                                }
                            }
                        }
                    }
                )
            }

            var ins = $("#div-products").find("input[name='products_id[]']")
            if (ins.length == 0) {
                $("#products").prop("required", true)
                validator.addField(
                    "products",
                    {
                        validators: {
                            notEmpty: {
                                message: `Produk harus diisi`
                            }
                        }
                    }
                )
            }
        }

        function check_contacts(target){
            var inp = $("#div-contact").find("input[name='"+target+"']")
            if(inp.length == 0){
                return false
            } else {
                return true
            }
        }

        function validate_form(form) {
            var f = document.getElementById(form)

            var _contatcs = $("input[name='contacts[]']")
            if (_contatcs.length > 0) {
                $("#pic_names").prop('required', false)
            }

            var _products = $("input[name='products_id[]']")
            if (_products.length > 0) {
                $("#products").prop('required', false)
            }

            var requireds = $(f).find(":required")
            var fields = {}
            requireds.each(function() {
                var _name = $(this).attr("name")
                var _row = $(this).parents("div.fv-row")
                var _label = $(_row).find('label')
                var attr = {
                    validators: {
                        notEmpty: {
                            message: `${_label.text()} harus diisi`
                        }
                    }
                }

                var target = $(this).data('target')
                if(target != undefined){
                    var attr = {
                    validators: {
                        callback: {
                            message: `${_label.text()} harus diisi`,
                            callback : function(value, validator, $field){
                                var inp = $("#div-contact").find("input[name='"+target+"']")
                                if(inp.length == 0){
                                    return false
                                } else {
                                    return true
                                }
                            }
                        }
                    }
                }
                }

                fields[_name] = attr
            })

            console.log(fields)

            var validator = FormValidation.formValidation(f, {
                fields: fields,
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            })

            $(f).find("[data-control=select2]").on("change", function() {
                // Revalidate the field when an option is chosen
                if ($(this).attr("id") == "instruct_to") {
                    var ins = $("#div-instruct").find("input[name='instruct[]']")
                    console.log(ins)
                    if (ins.length > 0) {
                        var _row = $(this).parents("div.fv-row")
                        var _fv = $(_row).find("div.fv-plugins-message-container")
                        _fv.html("")
                    }
                } else if ($(this).attr("id") == "products") {
                    var ins = $("#div-products").find("input[name='products_id[]']")
                    console.log(ins)
                    if (ins.length > 0) {
                        var _row = $(this).parents("div.fv-row")
                        var _fv = $(_row).find("div.fv-plugins-message-container")
                        _fv.html("")
                    }
                } else {
                    validator.revalidateField($(this).attr("name"));
                }
            });

            return validator;
        }

        $(document).ready(function() {
            init_tag()
            init_comp()
            init_contact()

            $("#company_name").on("change keyup", function() {
                if ($(this).val() != "") {
                    $("#btn-add-company").show()
                } else {
                    $("#btn-add-company").hide()
                }
            })

            $("#pic_names").on("keyup change", function() {
                if ($(this).val() != "") {
                    $("#btn-add-contact").show()
                } else {
                    $("#btn-add-contact").hide()
                }
            })

            $("#btn-add-company").click(function() {
                $.ajax({
                    url: "{{ route('crm.company.add') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        company_name: $("#company_name").val(),
                        comp_id: $("#comp_id").val()
                    }
                }).then(function(resp) {
                    $("#btn-add-company").hide()
                    $("#comp_id").val(resp.id)
                    $("#company_name").prop("required", false)
                })
            })

            $("#btn-add-contact").click(function() {
                $.ajax({
                    url: "{{ route('crm.contact.add') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: $("#pic_names").val(),
                        con_id: $("#con_id").val()
                    }
                }).then(function(resp) {
                    add_contact(resp)
                    $("#btn-add-contact").hide()
                    validator.removeField("pic_names")
                    $("#pic_names").prop("required", false)
                })
            })

            $("#instruct_to").change(function() {
                if ($(this).val() != "") {
                    var opt = $("#instruct_to option:selected")
                    var el = `<div class="d-flex flex-column mb-3">
                            <span>${opt.text()}</span>
                            <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                            <input type="hidden" name="instruct[]" value="${$(this).val()}">
                        </div>`

                    $("#div-instruct").append(el)
                    $("#instruct_to").val("").trigger("change")
                    $("#instruct_to").prop("required", false)
                }
            })

            $("#products").change(function() {
                if ($(this).val() != "") {
                    var opt = $("#products option:selected")
                    var el = `<div class="d-flex border rounded p-3 mb-3 justify-content-between align-items-center">
                            <div class="d-flex">
                                <i class="ki-duotone ki-cube-3 fs-2 me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                <span>${opt.text()}</span>
                            </div>
                            <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                            <input type="hidden" name="products_id[]" value="${$(this).val()}">
                        </div>`

                    $("#div-products").append(el)
                    $("#products").val("").trigger("change")
                    validator.removeField("products")
                    $("#products").prop("required", false)
                }
            })

            $("#btn-simpan").click(function() {
                validator.validate().then(function(resp) {
                    console.log(resp)
                    if (resp == "Valid") {
                        $(`#form-lead`).submit()
                    }
                })
            })

            init_contacts("#notes_persons")
            init_contacts("#task_persons")

            // var targetBlock = document.querySelector("#div-activity")
            // var blockUI = new KTBlockUI(targetBlock, {
            //     onBlock: function(){
            //         console.log("heho")
            //     }
            // });
            // blockUI.block()
        })
    </script>
@endsection
