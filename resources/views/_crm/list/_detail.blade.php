<div class="card-header">
    <h3 class="card-title">{{ $detail->company_name ?? ($detail->name ?? $detail->file_name) }}</h3>
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
        @if (!empty($f) && $f != $v)
            <!--begin::Close-->
            <div class="btn btn-sm" id="kt_drawer_example_advanced_close">
                <i class="fi fi-rr-x text-dark"></i>
            </div>
            <!--end::Close-->
        @else
            @if (isset($_GET['archived']))
                <a href="{{ route('crm.archive.recover', ['type' => $v, 'id' => $detail->id]) }}"
                    class="btn btn-sm btn-primary">
                    Recover
                </a>
            @else
                <!--begin::Close-->
                <button type="button" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete"
                    data-url="{{ route('crm.list.archive', ['type' => $v == 'perusahaan' ? 'company' : ($v == "file" ? "file" : 'contact'), 'id' => $detail->id]) }}"
                    data-id="{{ $detail->id }}" data-name="{{ $detail->company_name ?? ($detail->name ?? $detail->file_name) }}"
                    class="btn btn-sm text-danger">
                    Archive
                </button>
                <!--end::Close-->
            @endif
        @endif
    </div>
    <!--end::Card toolbar-->
</div>
<div class="card-body">
    <div class="d-flex flex-column mb-8">
        <div class="d-flex flex-column">
            <div class="min-w-500px bg-white">
                <form class="form" method="post" action="{{ $form }}" id="form-lead">
                    @csrf
                    <input type="hidden" name="f" value="{{ $f }}">
                    @if ($f == 'lead')
                        <input type="hidden" name="id_lead" value="{{ $_GET['id_lead'] }}">
                    @endif
                    @if (!empty($detail))
                        <input type="hidden" name="id" value="{{ $detail->id }}">
                    @endif
                    @if ($v == 'perusahaan')
                        @include('_crm.list._edit_perusahaan')
                    @elseif($v == "kontak")
                        @include('_crm.list._edit_kontak')
                    @else
                        @include('_crm.list._edit_file')
                    @endif
                </form>
            </div>
            @if ($show_detail)
            <div class="flex-fill">
                <div class="p-5 d-flex flex-column">
                    <div class="row d-flex flex-column bg-white border rounded {{ empty($detail) ? 'blockui' : '' }}"
                        id="div-activity">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn" data-bs-toggle="collapse"
                                data-bs-target="#kt_accordion_2_{{ $v }}_body_1" aria-expanded="true"
                                aria-controls="kt_accordion_2_{{ $v }}_body_1">
                                <i class="fi fi-rr-notebook"></i>
                                Add Notes
                            </button>
                            <button type="button" class="btn" data-bs-toggle="collapse"
                                data-bs-target="#kt_accordion_2_{{ $v }}_body_2" aria-expanded="true"
                                aria-controls="kt_accordion_2_{{ $v }}_body_2">
                                <i class="fi fi-rr-document"></i>
                                Add File
                            </button>
                        </div>
                        <div class="accordion px-0" id="kt_accordion_2_{{ $v }}">
                            <div class="accordion-item border-0">
                                <div id="kt_accordion_2_{{ $v }}_body_1" class="accordion-collapse collapse"
                                    aria-labelledby="kt_accordion_2_{{ $v }}_header_1"
                                    data-bs-parent="#kt_accordion_2_{{ $v }}">
                                    <form action="{{ route('crm.list.add_notes', $v) }}" method="post"
                                        enctype="multipart/form-data">
                                        <div class="border-top border-bottom">
                                            <div class="fv-row" id="opp-person_{{ $v }}">
                                                <select name="persons[]" data-control="select2" class="form-select2 px-4" data-dropdown-parent="#opp-person_{{ $v }}" data-placeholder="Add person" multiple>
                                                    @foreach ($user_hris as $userId => $item)
                                                        <option value="{{ $userId }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="separator separator-solid"></div>
                                            <div class="fv-row">
                                                <textarea name="notes" id="notes" class="form-controll ck-editor" placeholder="Input notes here..."
                                                    cols="30" rows="10"></textarea>
                                            </div>
                                            <div class="separator separator-solid"></div>
                                            <div class="fv-row p-5 upload-file">
                                                <label for="notes-file-{{ $v }}" data-toggle="upload_file"
                                                    class="btn btn-outline btn-outline-primary btn-sm">
                                                    <i class="fa fa-file"></i>
                                                    Add File
                                                </label>
                                                <span class="upload-file-label">Max 25 mb</span>
                                                <input id="notes-file-{{ $v }}" style="display: none" data-toggle="upload_file"
                                                    name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                                            </div>
                                            {{-- <div class="fv-row p-5 upload-file">
                                                <label for="notes-file" data-toggle="upload_file"
                                                    class="btn btn-outline btn-outline-primary btn-sm">
                                                    <i class="fa fa-file"></i>
                                                    Add File
                                                </label>
                                                <span class="upload-file-label">Max 25 mb</span>
                                                <input id="notes-file" style="display: none" data-toggle="upload_file"
                                                    name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                                            </div> --}}
                                        </div>
                                        <div class="d-flex p-5">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div id="kt_accordion_2_{{ $v }}_body_2" class="accordion-collapse collapse"
                                    aria-labelledby="kt_accordion_2_{{ $v }}_header_1"
                                    data-bs-parent="#kt_accordion_2_{{ $v }}">
                                    <form action="{{ route('crm.list.add_files', $v) }}" method="post"
                                        enctype="multipart/form-data">
                                        <div class="border-top border-bottom">
                                            <div class="fv-row p-5 upload-file">
                                                <label for="lead-file-{{ $v }}" data-toggle="upload_file"
                                                    class="btn btn-outline btn-outline-primary btn-sm">
                                                    <i class="fa fa-file"></i>
                                                    Add File
                                                </label>
                                                <span class="upload-file-label">Max 25 mb</span>
                                                <input id="lead-file-{{ $v }}" style="display: none" data-toggle="upload_file"
                                                    name="attachment_notes" accept=".jpg, .png, .pdf"
                                                    type="file" />
                                            </div>
                                        </div>
                                        <div class="d-flex p-5">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $detail->id ?? '' }}">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_notes_{{ $v }}">
                                    <span class="nav-icon">
                                        <i class="fa fa-note-sticky"></i>
                                    </span>
                                    <span class="nav-text">Notes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_files_detail_{{ $v }}">
                                    <span class="nav-icon">
                                        <i class="fa fa-file"></i>
                                    </span>
                                    <span class="nav-text">Files</span>
                                </a>
                            </li>
                        </ul> --}}
                        {{-- <div class="separator separator-solid"></div> --}}
                    </div>
                </div>
                @empty($detail)
                    <div class="blockui-overlay " style="z-index: 1;"></div>
                @endempty
                @if (!empty($detail))
                    <div class="py-5">
                        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_all_{{ $v }}">
                                    <span class="nav-text">All timeline</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_sales_{{ $v }}">
                                    <span class="nav-text">Sales</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_notes_{{ $v }}">
                                    <span class="nav-text">Notes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab"
                                    href="#tab_files_detail_{{ $v }}">
                                    <span class="nav-text">Files</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent_{{ $v }}" style="padding: 0">
                            <div class="tab-pane fade show active" id="tab_all_{{ $v }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach ($activity as $item)
                                            @component('_crm.list._timeline', [
                                                'item' => $item,
                                                'v' => $v,
                                                'detail' => $detail,
                                                'user_hris' => $user_hris,
                                                'comment_content' => $comment_content
                                            ])

                                            @endcomponent
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_sales_{{ $v }}" role="tabpanel">
                                @foreach ($activity as $item)
                                    @if (!in_array($item['type'], ['create', 'files', 'notes']))
                                        @component('_crm.list._timeline', [
                                            'item' => $item,
                                            'v' => $v,
                                            'detail' => $detail,
                                            'user_hris' => $user_hris,
                                            'comment_content' => $comment_content
                                        ])

                                        @endcomponent
                                    @endif
                                @endforeach
                            </div>
                            <div class="tab-pane fade" id="tab_notes_{{ $v }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach ($activity as $item)
                                            @if ($item['type'] == "notes")
                                                @component('_crm.list._timeline', [
                                                    'item' => $item,
                                                    'v' => $v,
                                                    'detail' => $detail,
                                                    'user_hris' => $user_hris,
                                                    'comment_content' => $comment_content
                                                ])

                                                @endcomponent
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab_files_detail_{{ $v }}" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        @foreach ($activity as $item)
                                            @if ($item['type'] == "files")
                                                @component('_crm.list._timeline', [
                                                    'item' => $item,
                                                    'v' => $v,
                                                    'detail' => $detail,
                                                    'user_hris' => $user_hris,
                                                    'comment_content' => $comment_content
                                                ])

                                                @endcomponent
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
