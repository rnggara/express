<div class="modal-body">
    <div class="card h-100">
        <div class="card-header border-0 px-0">
            <h3 class="card-title">
                <div class="d-flex gap-3 align-items-center">
                    <div class="symbol symbol-40px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-file-chart-line text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">Turnover Report</span>
                        <span class="text-muted fs-base fw-normal">Menyajikan laporan data yang berkaitan dengan turnover dalam periode tertentu</span>
                    </div>
                </div>
            </h3>
        </div>
        <div class="card-body rounded">
            <div class="d-flex flex-column gap-8 h-100">
                <div class="row gx-8 gy-0">
                    <div class="col-4">
                        <div class="card shadow-none border card-stretch">
                            <div class="card-body p-4">
                                <div class="d-flex flex-column gap-5">
                                    <span class="fs-3 fw-bold">Employee Turnover</span>
                                    <span class="fs-3tx">{{ $to->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card shadow-none border card-stretch">
                            <div class="card-body p-4">
                                <div class="d-flex flex-column gap-5">
                                    <span class="fs-3 fw-bold">Turnover Rate</span>
                                    <span class="fs-3tx">{{ number_format($toRate, 2) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card shadow-none border card-stretch">
                            <div class="card-body p-4">
                                <div class="d-flex flex-column gap-5">
                                    <span class="fs-3 fw-bold">Avg Years Turnover</span>
                                    <span class="fs-3tx">
                                        {{ $avg['year'] }}<span class="fs-3 text-muted">Tahun</span> {{ $avg['month'] }}<span class="fs-3 text-muted">Bulan</span> {{ $avg['day'] }}<span class="fs-3 text-muted">Hari</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-fill row gx-8 gy-0">
                    <div class="col-4">
                        <div class="d-flex flex-column h-100 gap-8">
                            <div class="card shadow-none h-50 bg-secondary-crm">
                                <div class="card-body p-4">
                                    <div class="d-flex flex-column gap-5 h-100">
                                        <span class="fs-3 fw-bold">Turnover Per Quartal</span>
                                        <div id="turnover_quartal_chart" style="height: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-none h-50 bg-secondary-crm">
                                <div class="card-body p-4">
                                    <div class="d-flex flex-column gap-5 h-100">
                                        <span class="fs-3 fw-bold">Tenure Range</span>
                                        <div id="turnover_tenure_chart" style="height: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-column gap-8 h-100">
                            <div class="card shadow-none h-50 bg-secondary-crm">
                                <div class="card-body p-4">
                                    <div class="d-flex flex-column gap-5 h-100">
                                        <span class="fs-3 fw-bold">Turnover Departement Percentage</span>
                                        <div id="turnover_dep_avg_chart" style="height: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow-none h-50 bg-secondary-crm">
                                <div class="card-body p-4">
                                    <div class="d-flex flex-column gap-5 h-100">
                                        <span class="fs-3 fw-bold">Turnover Level Percentage</span>
                                        <div id="turnover_level_avg_chart" style="height: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card shadow-none h-100 bg-secondary-crm">
                            <div class="card-body p-4">
                                <div class="d-flex flex-column gap-5 h-100">
                                    <span class="fs-3 fw-bold">Turnover Type</span>
                                    <div class="d-flex flex-column align-items-center gap-5 h-100">
                                        <div id="turnover_type_chart" style="height: 150px; width: 150px"></div>
                                        <div data-type="phk" class="d-flex justify-content-between bg-white w-100 rounded p-4">
                                            <span>Total PHK</span>
                                            <span class="fw-bold">{{ $toType['phk']->count() }}</span>
                                        </div>
                                        <div data-type="non_phk" class="d-flex justify-content-between bg-white w-100 rounded p-4 d-none">
                                            <span>Total Non PHK</span>
                                            <span class="fw-bold">{{ $toType['non_phk']->count() }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center bg-white rounded p-4 w-100">
                                            <span class="fw-bold">Turnover Reason</span>
                                            <div>
                                                <select name="turnover_type" class="form-select min-w-100px" data-control="select2" data-dropdown-parent="#modalView">
                                                    <option value="phk">PHK</option>
                                                    <option value="non_phk">Non PHK</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div data-type="phk" class="w-100 h-100 scroll-x d-flex flex-column gap-5">
                                            @foreach ($toReason['phk'] as $rId => $item)
                                                <div class="d-flex justify-content-between bg-white w-100 rounded p-4">
                                                    <span>{{ $rname[$rId] }}</span>
                                                    <span class="fw-bold">{{ count($item) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div data-type="non_phk" class="w-100 h-100 scroll-x d-flex flex-column gap-5 d-none">
                                            @foreach ($toReason['non_phk'] as $rId => $item)
                                                <div class="d-flex justify-content-between bg-white w-100 rounded p-4">
                                                    <span>{{ $rname[$rId] }}</span>
                                                    <span class="fw-bold">{{ count($item) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="border-0 modal-footer">
</div>
