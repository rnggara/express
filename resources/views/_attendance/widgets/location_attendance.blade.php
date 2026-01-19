<div class="card shadow-none h-100 flex-fill card-strech">
    <div class="card-body">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex flex-column">
                    <span class="fw-bold">{{ $widgets[$wg->widget_key]['label'] }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-icon btn-sm" onclick="openModalWidget({{ $key }})">
                        <i class="fi fi-rr-settings"></i>
                    </button>
                </div>
            </div>
            <canvas id="chart_location_attendance" class="mh-150px"></canvas>
        </div>
    </div>
</div>
