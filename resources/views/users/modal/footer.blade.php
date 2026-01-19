<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
<button type="submit" class="btn btn-{{ $act == "delete" ? "danger" : "primary" }} btn-sm">{{ $act == "edit" ? "Update" : ($act == "delete" ? "Delete" : "Save") }}</button>
