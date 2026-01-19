<div class="card-header">
    <h3 class="card-title">Annual Holiday</h3>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-12">
            <div id="kt_calendar"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAddHoliday" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1>Add Item</h1>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <form action="{{ route("pref.holiday.save") }}" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title" class="col-form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="start_date" class="col-form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="col-form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                    <input type="hidden" name="act" id="act">
                    <input type="hidden" name="id" id="holiday-id">
                    <a href="" id="delete-event" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
