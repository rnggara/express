@component('layouts._crm_modal', [
        "modalId" => "modal_detail",
        "modalSize" => "modal-lg"
    ])
    @slot('modalTitle')
        <div class="d-flex align-items-center mb-5">
            <div class="d-flex flex-column">
                <span class="fs-3 fw-bold">{{ $user->name }}</span>
            </div>
        </div>
    @endslot
    @slot('modalToolbar')
        <button type="button" class="btn btn-{{ empty($board->last_notify) || $board->last_notify != date("Y-m-d") ? 'primary' : "secondary disabled" }}">Notify User</button>
    @endslot
    @slot('modalContent')
        <div class="d-flex flex-column h-450px scroll-y">
            <div class="card bg-secondary-crm shadow-none">
                <div class="card-header border-0 px-0">
                    <h3 class="card-title">Upload File</h3>
                </div>
                <div class="rounded border card-body bg-white">
                    <div class="d-flex flex-column">
                        @foreach ($brDetail->where("type", "upload_file") as $item)
                            @php
                                $form = $item['detail'];
                            @endphp
                            <form action="{{ route("personel.onboarding.update") }}" class="mb-3" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item['id'] }}">
                                <input type="hidden" name="type" value="upload_file">
                                <div class="fv-row">
                                    <label class="col-form-label w-100">
                                        {{ $form->form_name }}
                                        @if (!empty($item['action_at']))
                                            <span class="text-success">
                                                <i class="fi fi-sr-check-circle text-success"></i>
                                                Uploaded on {{ date("d-m-Y", strtotime($item['action_at'])) }}
                                            </span>
                                        @endif
                                    </label>
                                    <div class="d-flex align-items-center mb-3 gap-3">
                                        <label class="btn btn-{{ empty($item['file_name']) ? 'secondary' : "primary" }}">
                                            <input type="file" name="attachment" onchange="onboardAction('upload_file', '{{ $item['id'] }}', this)" accept="{{ $form->file_format }}" data-attachment class="d-none">
                                            {{ $item['file_name'] ?? "Attachment" }}
                                            <i class="fi fi-rr-clip"></i>
                                        </label>
                                        @if (empty($item['action_at']))
                                            <div class="ms-5 text-{{ empty($item['action_at']) ? "danger" : "success" }}">
                                                Deadline on {{ date("d-m-Y", strtotime($item['due_date'])) }}
                                            </div>
                                        @else
                                            <a href="{{ asset($item['file_address']) }}" download target="_blank" class="btn btn-primary btn-icon">
                                                <i class="fi fi-rr-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <span class="text-muted">Max 2mb {{ strtoupper(str_replace(".", "", $form->file_format)) }}</span>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="my-3"></div>
            <div class="card bg-secondary-crm shadow-none">
                <div class="card-header border-0 px-0">
                    <h3 class="card-title">Download File</h3>
                </div>
                <div class="rounded border card-body bg-white">
                    @foreach ($brDetail->where("type", "download_file") as $item)
                        @php
                            $form = $item['detail'];
                        @endphp
                        <form action="" class="mb-3" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                            <input type="hidden" name="type" value="download_file">
                            <div class="fv-row">
                                <label class="col-form-label w-100">{{ $form->form_name }}</label>
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-primary" onclick="onboardAction('download_file', '{{ $item['id'] }}', this)">
                                        {{ $form->file_name }}
                                        <i class="fi fi-rr-download"></i>
                                    </label>
                                    <div class="ms-5 text-{{ !empty($item['action_at']) ? 'primary' : "muted" }}">
                                        @if (!empty($item['action_at']))
                                            <i class="fi fi-sr-check-circle text-primary"></i> Downloaded by User
                                        @else
                                            Not downloaded
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
            <div class="my-3"></div>
            <div class="card bg-secondary-crm shadow-none">
                <div class="card-header border-0 px-0">
                    <h3 class="card-title">Task</h3>
                </div>
                <div class="rounded border card-body bg-white p-3">
                    @foreach ($brDetail->where("type", "task") as $item)
                        @php
                            $form = $item['detail'];
                        @endphp
                        <form action="" class="mb-3" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                            <input type="hidden" name="type" value="task">
                            <div class="d-flex align-items-start rounded border p-5">
                                <div class="form-check me-3">
                                    <label class="form-check-label">
                                        <input class="form-check-input" {{ empty($item['approved_at']) ? "" : "checked" }} onclick="onboardAction('task_appr', '{{ $item['id'] }}', this)" type="checkbox" value="1" />
                                    </label>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fs-3">{{ $form->form_name }}</span>
                                    <span class="text-muted my-3">{{ $form->descriptions }}</span>
                                    <div class="fv-row">
                                        <div class="d-flex align-items-center mb-3 gap-3">
                                            <label class="btn btn-{{ empty($item['file_name']) ? 'secondary' : "primary" }}">
                                                <input type="file" name="attachment" onchange="onboardAction('task', '{{ $item['id'] }}', this)" accept=".pdf, .jpg, .png" data-attachment class="d-none">
                                                {{ $item['file_name'] ?? "Attachment" }}
                                                <i class="fi fi-rr-clip"></i>
                                            </label>
                                            @if (!empty($item['action_at']))
                                                <a href="{{ asset($item['file_address']) }}" download target="_blank" class="btn btn-primary btn-icon">
                                                    <i class="fi fi-rr-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <span class="text-muted">5MB PDF, JPG, PNG</span>
                                    </div>
                                    <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold my-3">
                                        <li class="breadcrumb-item"><span class="fi fi-rr-user"></span></li>
                                        <li class="breadcrumb-item"><span class="">{{ $pic_name[$form->pic] ?? "" }}</span></li>
                                        <li class="breadcrumb-item text-{{ empty($item['action_at']) ? "danger" : "success" }}">
                                            @if (empty($item['action_at']))
                                                Deadline on {{ date("d-m-Y", strtotime($item['due_date'])) }}
                                            @else
                                                <i class="fi fi-sr-check-circle text-success"></i>
                                                Uploaded on {{ date("d-m-Y", strtotime($item['action_at'])) }}
                                            @endif
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="my-3"></div>
        <div class="d-flex flex-column w-100 align-items-center">
            <button type="button" class="btn text-primary" onclick="addFormDetail({{ $user->id }})" data-bs-stacked-modal="#modal_add_detail_form">
                <i class="fa fa-add"></i>
                Add form
            </button>
        </div>
        {{-- <div class="d-flex justify-content-center">
            <button type="button" class="btn text-primary">
                <i class="fa fa-plus text-primary"></i>
                Add Form
            </button>
        </div> --}}
    @endslot
    @slot('modalFooter')
        @csrf
        <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
        <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
    @endslot
@endcomponent
