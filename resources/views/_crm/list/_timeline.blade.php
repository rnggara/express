<div class="d-flex align-items-baseline mb-5">
    <i class="fa fa-plus-circle me-5"></i>
    <div class="d-flex flex-column w-100">
        <span class="mb-3">
            <span class="fw-bold">{{ $item['user'] }}</span>
            @if ($item['type'] == 'create')
                is making {{ $v == 'kontak' ? 'kontak' : '' }} <span class="fw-bold">{{ $detail->label }}</span>
            @elseif($item['type'] == 'task')
                is making task at <span class="fw-bold">{{ $detail->label }}</span>
            @elseif($item['type'] == 'notes')
                is making notes at <span class="fw-bold">{{ $detail->label }}</span>
            @elseif($item['type'] == 'files')
                is uploading file at <span class="fw-bold">{{ $detail->label }}</span>
            @else
                @php
                    $ex = explode("_", $item['type']);
                    $_ex = array_shift($ex);
                    $lbl = ucwords(implode(" ", $ex));
                @endphp
                is updating <span class="fw-bold">{{ $lbl }}</span>
            @endif
        </span>
        <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item"><span>@dayId($item['date']),
                    {{ date('d/m/Y H:i', strtotime($item['item']['created_at'])) }}</span></li>
            @if (in_array($item['type'], ['notes', 'task']))
                @if (!empty($item['item']['persons']))
                    <li class="breadcrumb-item">
                        @php
                            $pers = json_decode($item['item']['persons'] ?? "[]", true);
                        @endphp
                        @if (is_array($pers))
                        @foreach ($pers as $i => $pr)
                            {{ $user_hris[$pr] }}@if ($i < count(json_decode($item['item']['persons'], true)) - 1)
                                ,
                            @endif
                        @endforeach
                        @endif
                    </li>
                @endif
            @endif
        </ol>
        @if ($item['type'] != 'create' && stripos($item['type'], 'update_') === false)
            <div class="border bg-white rounded p-3">
                @if ($item['type'] != 'files')
                    <div class="d-flex flex-column">
                        <span>{!! $item['item']['descriptions'] !!}</span>
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
            </div>
            <div class="d-flex justify-content-between comment-header">
                @php
                    $_comment = $comment_content[$item['type']] ?? [];
                    $replies = [];
                    if (!empty($_comment)) {
                        $replies = $_comment[$item['item']['id']] ?? [];
                    }
                @endphp
                <div class="d-flex">
                    <button type="button" class="btn text-primary me-3" onclick="openComment(this)">
                        Reply
                    </button>
                    @if (count($replies) > 0)
                        <button type="button" class="btn" data-type="{{ $item['type'] }}"
                            data-view="{{ $v == "perusahaan" ? "company" : "contacts" }}"
                            data-id="{{ $item['item']['id'] }}" onclick="openReplies(this)">
                            <span class="reply-close">{{ count($replies) }} replies</span>
                            <span class="reply-show" style="display: none">Close</span>
                        </button>
                    @endif
                </div>
                <div class="d-flex">
                    @if (Auth::id() == $item['uid'])
                        <a href="{{ route("crm.list.delete_detail", ['view' => $v == "perusahaan" ? "company" : "contacts",'type' => $item['type'], "id" => $item['item']["id"]]) }}" class="btn text-danger">
                            Hapus
                        </a>
                    @endif
                </div>
            </div>
            <div class="comment-section mb-5" style="display: none;">
                <form action="{{ route('crm.list.comment.add', $v == "perusahaan" ? "company" : "contacts") }}" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column">
                        <div class="fv-row mb-3">
                            <input type="text" name="comment" class="form-control" required
                                placeholder="Tulis pesan disini">
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
                            <input type="hidden" name="list_type" value="{{ $v }}">
                            <input type="hidden" name="list_id" value="{{ $detail->id }}">
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
</div>
