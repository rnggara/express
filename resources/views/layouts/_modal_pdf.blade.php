<div class="modal-header">
    <h1 class="modal-title">{{ ucwords($file_name) }}</h1>
    <button type="button" class="close" data-dismiss="modal">
        <i class="fa fa-times"></i>
    </button>
</div>
<div class="modal-body">
    <iframe src="{{ $file }}#view=FitH" width="100%" height="800px" frameborder="0"></iframe>
</div>
