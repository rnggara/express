<div class="card-header">
    <h3 class="card-title">{{ $detail->company_name }}</h3>
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
        @if ($_GET['f'] == "lead")
            <!--begin::Close-->
            <div class="btn btn-sm">
                <i class="fi fi-rr-times text-dark"></i>
            </div>
            <!--end::Close-->
        @else
            <!--begin::Close-->
            <div class="btn btn-sm text-danger">
                Archive
            </div>
            <!--end::Close-->
        @endif
    </div>
    <!--end::Card toolbar-->
</div>
<div class="card-body">
    <div class="d-flex flex-column mb-8">
        <div class="d-flex flex-column">
            <div class="min-w-500px bg-white">
                <form class="form p-3" method="post" action="{{ $form }}" id="form-lead">
                    @csrf
                    @if (!empty($detail))
                        <input type="hidden" name="id" value="{{ $detail->id }}">
                    @endif
                    @if ($v == "perusahaan")
                        @include('_crm.list._edit_perusahaan')
                    @else
                        @include('_crm.list._edit_kontak')
                    @endif
                </form>
            </div>
            <div class="flex-fill">
                <div class="p-10" style="background-color: var(--bs-page-bg)">
                    <div class="row d-flex flex-column bg-white {{ empty($detail) ? 'blockui' : '' }}" id="div-activity">
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
                                <form action="{{ route('crm.list.add_notes', $v) }}" method="post" enctype="multipart/form-data">
                                    <div class="border">
                                        <div class="fv-row">
                                            <input type="text" placeholder="Add person" name="persons"
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
                                        <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="tab_files" role="tabpanel">
                                <form action="{{ route('crm.list.add_files', $v) }}" method="post" enctype="multipart/form-data">
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
                                        <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty($detail)
                            <div class="blockui-overlay " style="z-index: 1;"></div>
                        @endempty
                        @if (!empty($detail))
                        <div class="py-5" style="background-color: var(--bs-page-bg)">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="timeline-3">
                                        @foreach ($activity as $item)
                                            <li>
                                                <div class="d-flex flex-column">
                                                    <span class="mb-5">
                                                        <span class="fw-bold">{{ $item['user'] }}</span>
                                                        @if ($item['type'] == 'create')
                                                            membuat {{ $v == "kontak" ? "kontak" : "" }} <span class="fw-bold">{{ $detail->label }}</span>
                                                        @elseif($item['type'] == 'task')
                                                            membuat task di <span class="fw-bold">{{ $detail->label }}</span>
                                                        @elseif($item['type'] == 'notes')
                                                            membuat note di <span class="fw-bold">{{ $detail->label }}</span>
                                                        @elseif($item['type'] == 'files')
                                                            mengupload file di <span
                                                                class="fw-bold">{{ $detail->label }}</span>
                                                        @endif
                                                    </span>
                                                    <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold">
                                                        <li class="breadcrumb-item"><span>@dayId($item['date']), {{ date('d/m/Y H:i', strtotime($item['item']['created_at'])) }}</span></li>
                                                        @if (in_array($item['type'], ["notes", "task"]))
                                                            @if (!empty($item['item']['persons']))
                                                            <li class="breadcrumb-item">
                                                                @foreach (json_decode($item['item']['persons'], true) as $i => $pr)
                                                                    {{ $pr['value'] }}@if ($i < count(json_decode($item['item']['persons'], true)) - 1),@endif
                                                                @endforeach
                                                            </li>
                                                            @endif
                                                            @if ($item['type'] == "task")
                                                            <li class="breadcrumb-item">
                                                                <span class="text-danger">Deadline: {{ date("d/m/Y", strtotime($item['item']['due_date'])) }} | {{ date("H:i", strtotime($item['item']['due_date'])) }}</span>
                                                            </li>
                                                            @endif
                                                        @endif
                                                    </ol>
                                                        @if ($item['type'] != "create")
                                                        <div class="border bg-white rounded p-3">
                                                            @if ($item['type'] != "files")
                                                            <div class="d-flex flex-column">
                                                                <span>{!! $item['item']['descriptions'] !!}</span>
                                                            </div>
                                                            @endif
                                                            @if (!empty($item['item']['file_name']))
                                                                @if ($item['type'] != "files")
                                                                <div class="separator separator-solid mb-3"></div>
                                                                @endif
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <i class="fa fa-file-pdf me-3 text-primary fs-2"></i>
                                                                    <a href="{{ asset($item['item']['file_address']) }}" class="btn btn-link text-primary">{{ $item['item']['file_name'] }}</a>
                                                                </div>
                                                            @endif
                                                        </div>
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
                                                <span class="fw-bold fs-2">Hapus {{ $v }}</span>
                                            </div>
                                            <span>Apakah kamu yakin akan menghapus {{ $v }} {{ $detail->label }}?</span>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        <a href="{{ $delRoute }}" class="btn btn-danger">Hapus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
