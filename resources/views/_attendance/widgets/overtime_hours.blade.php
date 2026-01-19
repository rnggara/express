<div class="card shadow-none h-100 flex-fill card-strech">
    <div class="card-body">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex">
                    <span class="fw-bold">{{ $widgets[$wg->widget_key]['label'] }}</span>
                    <span class="text-muted">(Total Bulan)</span>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-icon btn-sm" onclick="openModalWidget({{ $key }})">
                        <i class="fi fi-rr-settings"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="d-flex flex-column align-items-center">
                    <span class="fw-bold {{ $wg->widget_key }}_avg fs-3">-</span>
                    <span class="text-muted">Avg Overtime/Week</span>
                </div>
            </div>
            <div class="my-1 border w-100"></div>
            <div id="chart_overtime_hours" class="mh-150px"></div>
        </div>
    </div>
</div>
