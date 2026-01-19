<div class="card-header">
    <h3 class="card-title">{{ $leads->leads_name }}</h3>
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
        @if (isset($_GET['archived']))
        <a href="{{ route('crm.archive.recover', ['type' => "leads", 'id' => $leads->id]) }}" class="btn btn-sm btn-primary">
            Recover
        </a>
        @else
        <!--begin::Close-->
        <button type="button" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.lead.archive', $leads->id) }}" data-id="{{ $leads->id }}" data-name="{{ $leads->leads_name }}" class="btn btn-sm text-danger">
            Archive
        </button>
        <!--end::Close-->
        @endif
    </div>
    <!--end::Card toolbar-->
</div>
<div class="card-body">
    <div class="d-flex flex-column mb-8">
        <div class="d-flex flex-column">
            <div class="min-w-500px bg-white">
                <form class="form p-3" method="post" action="{{ route("crm.lead.update") }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $leads->id }}">
                    @include('_crm.leads._drawer')
                </form>
            </div>
            <div class="flex-fill">
                <div class="p-5 d-flex flex-column">
                    <div class="row d-flex flex-column bg-white border rounded {{ empty($leads) ? 'blockui' : '' }}" id="div-activity">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn" data-bs-toggle="collapse" data-bs-target="#kt_lead_accordion1" aria-expanded="true" aria-controls="kt_lead_accordion1">
                                <i class="fi fi-rr-notebook"></i>
                                Add Notes
                            </button>
                            <button type="button" class="btn" data-bs-toggle="collapse" data-bs-target="#kt_lead_accordion2" aria-expanded="true" aria-controls="kt_lead_accordion2">
                                <i class="fi fi-rr-list-check"></i>
                                Add Task
                            </button>
                            <button type="button" class="btn" data-bs-toggle="collapse" data-bs-target="#kt_lead_accordion3" aria-expanded="true" aria-controls="kt_lead_accordion3">
                                <i class="fi fi-rr-document"></i>
                                Add File
                            </button>
                        </div>
                        <div class="accordion px-0" id="kt_lead_accordion">
                            <div class="accordion-item border-0">
                                <div id="kt_lead_accordion1" class="accordion-collapse collapse" aria-labelledby="kt_lead_accordion_header_1" data-bs-parent="#kt_lead_accordion">
                                    <form action="{{ route('crm.lead.notes.add') }}" method="post" enctype="multipart/form-data">
                                        <div class="border-top border-bottom">
                                            <div class="fv-row" id="opp-person_1">
                                                <select name="persons[]" data-control="select2" class="form-select2 px-4" data-dropdown-parent="#opp-person_1" data-placeholder="Add person" multiple>
                                                    @foreach ($users as $userId => $item)
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
                                        <div class="d-flex p-5">
                                            @csrf
                                            <input type="hidden" name="lead_id" value="{{ $leads->id ?? '' }}">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div id="kt_lead_accordion2" class="accordion-collapse collapse" aria-labelledby="kt_lead_accordion_header_1" data-bs-parent="#kt_lead_accordion">
                                    <form action="{{ route('crm.lead.task.add') }}" method="post" enctype="multipart/form-data">
                                        <div class="border-top border-bottom">
                                            <div class="fv-row">
                                                <input type="text" placeholder="Task Name" name="task" required
                                                    class="form-control border-0">
                                            </div>
                                            <div class="separator separator-solid"></div>
                                            <div class="fv-row" id="opp-person_2">
                                                <select name="persons[]" data-control="select2" class="form-select2 px-4" data-dropdown-parent="#opp-person_2" data-placeholder="Add person" multiple>
                                                    @foreach ($users as $userId => $item)
                                                        <option value="{{ $userId }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="separator separator-solid"></div>
                                            <div class="fv-row">
                                                <textarea name="notes" id="notes-task" class="form-controll ck-editor" placeholder="Input task detail here..."
                                                    cols="30" rows="10"></textarea>
                                            </div>
                                            <div class="separator separator-solid"></div>
                                            <div class="row px-5 pb-5">
                                                <div class="col-6 fv-row">
                                                    <label for="status" class="col-form-label">Status</label>
                                                    <select name="status" id="status" class="form-select" data-control="select2"
                                                        data-placeholder="Select Status">
                                                        <option value=""></option>
                                                        <option value="1">Start</option>
                                                        <option value="2">Ongoing</option>
                                                        <option value="3">Finished</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 fv-row">
                                                    <label for="prioritas" class="col-form-label">Priority</label>
                                                    <select name="prioritas" id="prioritas" class="form-select"
                                                        data-control="select2" data-placeholder="Select Priority">
                                                        <option value=""></option>
                                                        <option value="1">High</option>
                                                        <option value="2">MEdium</option>
                                                        <option value="3">Low</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="separator separator-solid"></div>
                                            <div class="row px-5 pb-5">
                                                <div class="col-4 fv-row">
                                                    <label for="tanggal_tenggat" class="col-form-label">Due Date</label>
                                                    <input type="text" name="tanggal_tenggat" placeholder="Due Date"
                                                        class="form-control tempusDominus" data-view="calendar" data-format="99/99/9999" id="tanggal_tenggat">
                                                </div>
                                                <div class="col-4 fv-row">
                                                    <label for="waktu_tenggat" class="col-form-label">Time</label>
                                                    <input type="time" name="waktu_tenggat" placeholder="Time"
                                                        class="form-control" id="waktu_tenggat">
                                                </div>
                                                <div class="col-4 fv-row">
                                                    <label for="reminders" class="col-form-label">Reminders</label>
                                                    <select name="reminders" id="reminders" class="form-select"
                                                        data-control="select2" data-placeholder="Select Reminders">
                                                        <option value=""></option>
                                                        <option value="1">1 day before</option>
                                                        <option value="2">2 days before</option>
                                                        <option value="3">3 days before</option>
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
                                        <div class="d-flex p-5">
                                            @csrf
                                            <input type="hidden" name="lead_id" value="{{ $leads->id ?? '' }}">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div id="kt_lead_accordion3" class="accordion-collapse collapse" aria-labelledby="kt_lead_accordion_header_1" data-bs-parent="#kt_lead_accordion">
                                    <form action="{{ route('crm.lead.file.add') }}" method="post" enctype="multipart/form-data">
                                        <div class="border-top border-bottom px-5 pb-5">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label">File Name</label>
                                                <input type="text" name="file_name" class="form-control" placeholder="Input File Name">
                                            </div>
                                            <div class="fv-row upload-file my-5">
                                                <label for="lead-file" data-toggle="upload_file"
                                                    class="btn btn-outline btn-outline-primary btn-sm">
                                                    <i class="fa fa-file"></i>
                                                    Add File
                                                </label>
                                                <span class="upload-file-label">Max 25 mb</span>
                                                <input id="lead-file" style="display: none" data-toggle="upload_file"
                                                    name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                                            </div>
                                            <div class="fv-row">
                                                <label for="" class="col-form-label">Upload URL</label>
                                                <input type="text" name="file_url" class="form-control" placeholder="Input URL">
                                            </div>
                                        </div>
                                        <div class="d-flex p-5">
                                            @csrf
                                            <input type="hidden" name="lead_id" value="{{ $leads->id ?? '' }}">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_notes">
                                    <span class="nav-icon">
                                        <i class="fa fa-note-sticky"></i>
                                    </span>
                                    <span class="nav-text">Notes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_files_detail">
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
                @empty($leads)
                    <div class="blockui-overlay " style="z-index: 1;"></div>
                @endempty
                @if (!empty($leads))
                <div class="py-5">
                    <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab_lead_all">
                                <span class="nav-text">All timeline</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_lead_sales">
                                <span class="nav-text">Sales</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_lead_notes">
                                <span class="nav-text">Notes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_lead_task">
                                <span class="nav-text">Task</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_lead_files_detail">
                                <span class="nav-text">Files</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent" style="padding: 0">
                        <div class="tab-pane fade show active" id="tab_lead_all" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    @foreach ($activity as $item)
                                        @component('_crm.leads._timeline', [
                                            'item' => $item,
                                            'leads' => $leads,
                                            'comment_content' => $comment_content
                                        ])
                                        @endcomponent
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_lead_sales" role="tabpanel">
                            @foreach ($activity as $item)
                                @if (!in_array($item['type'], ['task', 'notes', 'files', 'create']))
                                    @component('_crm.leads._timeline', [
                                        'item' => $item,
                                        'leads' => $leads,
                                        'comment_content' => $comment_content
                                    ])
                                    @endcomponent
                                @endif
                            @endforeach
                        </div>
                        <div class="tab-pane fade" id="tab_lead_notes" role="tabpanel">
                            @foreach ($activity as $item)
                                @if ($item['type'] == "notes")
                                    @component('_crm.leads._timeline', [
                                        'item' => $item,
                                        'leads' => $leads,
                                        'comment_content' => $comment_content
                                    ])
                                    @endcomponent
                                @endif
                            @endforeach
                        </div>
                        <div class="tab-pane fade" id="tab_lead_task" role="tabpanel">
                            @foreach ($activity as $item)
                                @if ($item['type'] == "task")
                                    @component('_crm.leads._timeline', [
                                        'item' => $item,
                                        'leads' => $leads,
                                        'comment_content' => $comment_content
                                    ])
                                    @endcomponent
                                @endif
                            @endforeach
                        </div>
                        <div class="tab-pane fade" id="tab_lead_files_detail" role="tabpanel">
                            @foreach ($activity as $item)
                                @if ($item['type'] == "files")
                                    @component('_crm.leads._timeline', [
                                        'item' => $item,
                                        'leads' => $leads,
                                        'comment_content' => $comment_content
                                    ])
                                    @endcomponent
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
