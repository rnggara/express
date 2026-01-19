@php
    $el = $item['item'];
@endphp
<div class="d-flex align-items-baseline mb-5">
    <i class="fa fa-plus-circle me-5"></i>
    <div class="d-flex flex-column">
        <span class="mb-3">
            <span class="fw-bold">{{ $item['user'] }}</span>
            @if ($item['type'] == 'create')
                is making <span class="fw-bold">{{ $leads->leads_name }}</span>
            @elseif($item['type'] == 'task')
                adding task <span class="fw-bold">{{ $leads->leads_name }}</span>
            @elseif($item['type'] == 'notes')
                adding note <span class="fw-bold">{{ $leads->leads_name }}</span>
            @elseif($item['type'] == 'files')
                uploading file <span class="fw-bold">{{ $leads->leads_name }}</span>
            @elseif($item['type'] == 'update_sales_confidence')
                change <span class="fw-bold">Sales Confidence</span>
            @elseif($item['type'] == 'update_nominal')
                change <span class="fw-bold">Estimated Revenue (IDR)</span>
            @elseif($item['type'] == 'update_estimasi_profit')
                change <span class="fw-bold">Estimated Gross Profit (IDR)</span>
            @elseif($item['type'] == 'update_end_date')
                change <span class="fw-bold">Estimated Closing Date</span>
            @elseif($item['type'] == 'update_level_priority')
                change <span class="fw-bold">Level Priority</span>
            @elseif($item['type'] == 'update_status_deal')
                change <span class="fw-bold">Status Deal</span>
            @elseif($item['type'] == 'update_id_client')
                is added <span class="fw-bold">{{ $clients[$leads->id_client] ?? '-' }}</span> to <span
                    class="fw-bold">{{ $leads->leads_name }}</span>
            @elseif($item['type'] == 'update_contributors')
                @php
                    $collabBefore = json_decode($el['before'] ?? '[]', true);
                    $collabAfter = json_decode($el['after'] ?? '[]', true);
                @endphp
                @if (count($collabAfter) > 0)
                    @php
                        $list_collab = '';
                        foreach ($collabAfter as $ctbId) {
                            if (!in_array($ctbId, $collabBefore)) {
                                $list_collab .= isset($users[$ctbId]) ? $users[$ctbId] . ',' : '';
                            }
                        }

                        $list_collab = rtrim($list_collab, ', ');
                    @endphp
                    invites <span class="fw-bold">{{ $list_collab }}</span> to opportunity as <span
                        class="fw-bold">Collaborators</span>
                @else
                    removing <span class="fw-bold">Collaborators</span>
                @endif
            @elseif($item['type'] == 'update_contacts')
                @php
                    $contactBefore = json_decode($el['before'] ?? '[]', true);
                    $contactAfter = json_decode($el['after'] ?? '[]', true);
                @endphp
                @if (count($contactAfter) > 0)
                    @php
                        $list_collab = '';
                        foreach ($contactAfter as $ctbId) {
                            if (!in_array($ctbId, $contactBefore)) {
                                $list_collab .= isset($contacts[$ctbId]) ? $contacts[$ctbId] . ',' : '';
                            }
                        }

                        $list_collab = rtrim($list_collab, ', ');
                    @endphp
                    is added <span class="fw-bold">{{ $list_collab }}</span> to <span class="fw-bold">Contact
                        Person</span> at <span class="fw-bold">{{ $leads->leads_name }}</span>
                @else
                    removing <span class="fw-bold">Contacts</span>
                @endif
            @elseif($item['type'] == 'update_products')
                @php
                    $contactBefore = json_decode($el['before'] ?? '[]', true);
                    $contactAfter = json_decode($el['after'] ?? '[]', true);
                @endphp
                @if (count($contactAfter) > 0)
                    @php
                        $list_collab = '';
                        foreach ($contactAfter as $ctbId) {
                            if (!in_array($ctbId, $contactBefore)) {
                                $list_collab .= isset($products[$ctbId]) ? $products[$ctbId] . ',' : '';
                            }
                        }

                        $list_collab = rtrim($list_collab, ', ');
                    @endphp
                    is added <span class="fw-bold">{{ $list_collab }}</span> to <span class="fw-bold">Products</span>
                    at <span class="fw-bold">{{ $leads->leads_name }}</span>
                @else
                    removing <span class="fw-bold">Contacts</span>
                @endif
            @endif
        </span>
        {{-- @dd($item) --}}
        <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item"><span>@dayId($item['date']),
                    {{ date('d/m/Y H:i', strtotime($item['item']['created_at'])) }}</span></li>
            @if (in_array($item['type'], ['notes', 'task']))
                @if (!isset($item['item']['persons']) && !empty($item['item']['persons']))
                    <li class="breadcrumb-item">
                        @foreach (json_decode($item['item']['persons'], true) as $i => $pr)
                            {{ $users[$pr] }}@if ($i < count(json_decode($item['item']['persons'], true)) - 1)
                                ,
                            @endif
                        @endforeach
                    </li>
                @endif
                @if ($item['type'] == 'task')
                    <li class="breadcrumb-item">
                        <span class="text-danger">Deadline: {{ date('d/m/Y', strtotime($item['item']['due_date'])) }} |
                            {{ date('H:i', strtotime($item['item']['due_date'])) }}</span>
                    </li>
                @endif
            @endif
        </ol>
        @if ($item['type'] != 'create' && stripos($item['type'], 'update_') === false)
            <div class="border bg-white rounded p-3">
                @if ($item['type'] != 'files')
                    <div class="d-flex flex-column">
                        <span>{!! $item['item']['notes'] ?? '' !!}</span>
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
            @php
                $_comment = $comment_content[$item['type']] ?? [];
                $replies = [];
                if (!empty($_comment)) {
                    $replies = $_comment[$item['item']['id']] ?? [];
                }
            @endphp
            <div class="d-flex justify-content-between comment-header">
                <div class="d-flex">
                    <button type="button" class="btn text-primary me-3" onclick="openComment(this)">
                        Reply
                    </button>
                    @if (count($replies) > 0)
                        <button type="button" class="btn" data-type="{{ $item['type'] }}"
                            data-id="{{ $item['item']['id'] }}" onclick="openReplies(this)">
                            <span class="reply-close">{{ count($replies) }} replies</span>
                            <span class="reply-show" style="display: none">Close</span>
                        </button>
                    @endif
                </div>
                <div class="d-flex">
                    @if (Auth::id() == $item['uid'])
                        <a href="{{ route("crm.lead.delete_detail", ['type' => $item['type'], "id" => $item['item']["id"]]) }}" class="btn text-danger">
                            Hapus
                        </a>
                    @endif
                </div>
            </div>
            <div class="comment-section mb-5" style="display: none;">
                <form action="{{ route('crm.comment.add') }}" method="post" enctype="multipart/form-data">
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
</div>
