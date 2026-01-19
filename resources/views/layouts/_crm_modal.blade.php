<div class="modal fade" tabindex="-1" id="{{ $modalId }}">
    <div class="modal-dialog modal-dialog-centered {{ $modalSize ?? "" }}">
        <div class="modal-content px-10">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header border-0 px-0">
                        <h3 class="card-title">{{ $modalTitle }}</h3>
                        <div class="card-toolbar">{{ $modalToolbar ?? "" }}</div>
                    </div>
                    <div class="card-body rounded {{ $modalBg ?? "bg-secondary-crm" }} {{ $modalPadding ?? "" }}">
                        {!! $modalContent !!}
                    </div>
                </div>
                {!! $modalAdditional ?? "" !!}
            </div>
            <div class="border-0 modal-footer">
                {!! $modalFooter !!}
            </div>
        </div>
    </div>
</div>
