@foreach ($comment as $item)
    @isset($user[$item->user_id])
        @php
            $replies = $child[$item->id] ?? [];
        @endphp
        <div class="d-flex flex-column">
            <div class="d-flex flex-column border rounded p-3">
                <span class="fw-bold mb-3">{{ $user[$item->user_id] }}</span>
                <span class="mb-5">{{ $item->comment }}</span>
                @if (!empty($item->file_address))
                    <div class="separator separator-solid"></div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-file-pdf me-3 text-primary fs-2"></i>
                        <a href="{{ asset($item->file_address) }}"
                            class="btn btn-link text-primary">{{ $item->file_name }}</a>
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-between comment-header">
                <div class="d-flex">
                    <button type="button" class="btn text-primary me-3" onclick="openComment(this)">
                        Reply
                    </button>
                    @if (count($replies) > 0)
                        <button type="button" class="btn me-3" data-view="{{ $view }}" data-type="{{ $item->content_type }}" data-comment="{{ $item->id }}" data-id="{{ $item->content_id }}" onclick="openReplies(this)">
                            <span class="reply-close">{{ count($replies) }} replies</span>
                            <span class="reply-show" style="display: none">Close</span>
                        </button>
                    @endif
                    @if ($item->user_id == Auth::id())
                    <button type="button" class="btn text-danger me-3" data-view="{{ $view }}" data-id="{{ $item->id }}" onclick="deleteComment(this)">
                            Delete
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
                <form action="{{ route("crm.list.comment.add", $view) }}" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column">
                        <div class="fv-row mb-3">
                            <input type="text" name="comment" class="form-control" required placeholder="Tulis pesan disini">
                        </div>
                        <div class="fv-row mb-5 upload-file">
                            <label for="file{{ $item->id }}" data-toggle="upload_file"
                                class="btn btn-outline btn-outline-primary btn-sm">
                                <i class="fa fa-file"></i>
                                Add File
                            </label>
                            <span class="upload-file-label">Max 25 mb</span>
                            <input id="file{{ $item->id }}" style="display: none" data-toggle="upload_file"
                                name="attachments" accept=".jpg, .png, .pdf" type="file" />
                        </div>
                        <div class="d-flex justify-content-end">
                            <input type="hidden" name="list_type" value="{{ $view }}">
                            <input type="hidden" name="list_id" value="{{ $item->list_id }}">
                            <input type="hidden" name="content_id" value="{{ $item->content_id }}">
                            <input type="hidden" name="content_type" value="{{ $item->content_type }}">
                            <input type="hidden" name="comment_id" value="{{ $item->id }}">
                            @csrf
                            <button type="submit" class="btn btn-primary me-5">Kirim</button>
                            <button type="button" onclick="closeComment(this)" class="btn text-primary">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="comment-data d-flex flex-column ms-10"></div>
        </div>
    @endisset
@endforeach
