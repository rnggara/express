<div class="modal-body overflow-hidden">
    <div class="card h-100">
        <div class="card-header border-0 px-0">
            <h3 class="card-title">
                <div class="d-flex gap-3 align-items-center">
                    <div class="symbol symbol-40px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-chart-histogram text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">Ratio Employee</span>
                        <span class="text-muted fs-base fw-normal">Menyajikan rasio karyawan berdasarkan parameter yang dapat dipilih</span>
                    </div>
                </div>
            </h3>
        </div>
        <div class="card-body rounded h-100">
            <div class="d-flex h-100 gap-5">
                <div class="d-flex flex-column h-100 gap-5">
                    <div class="card shadow-none border me-4">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-5">
                                <select name="retype" class="form-select" data-control="select2" id="">
                                    <option value="education">Education Level</option>
                                    <option value="level">Employee Level</option>
                                    <option value="division">Division</option>
                                    <option value="gender">Gender</option>
                                    <option value="location">Location</option>
                                    <option value="contract">Contract Type</option>
                                    {{-- <option value="location">Location</option> --}}
                                </select>
                                <div class="flex-fill">
                                    <div id="ratio_employee_chart_modal" style="height: 200px; width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-fill h-100 scroll-y" data-content="tab"></div>
                </div>
                <div class="flex-fill h-100 scroll-y" data-content="table" id="rt-table"></div>
            </div>
        </div>
    </div>
</div>
<div class="border-0 modal-footer">
</div>
