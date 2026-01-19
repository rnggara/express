<div class="card shadow-none h-100 flex-fill card-strech">
    <div class="card-body">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex flex-column">
                    <span class="fw-bold">{{ $widgets[$wg->widget_key]['label'] }}</span>
                    <span class="text-muted">(Total Bulan)</span>
                </div>
                <div class="d-flex align-items-center">
                    <select name="{{ $wg->widget_key }}_reason" onchange="loadChart('{{ $wg->widget_key }}')" class="form-select" data-control="select2" id="">
                        @foreach ($reasons as $item)
                            <option value="{{ $item->reason_name_id }}">{{ $item->reasonName->reason_name }}</option>
                        @endforeach
                    </select>
                    <div class="mx-2"></div>
                    <button type="button" class="btn btn-icon btn-sm" onclick="openModalWidget({{ $key }})">
                        <i class="fi fi-rr-settings"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="d-flex flex-column align-items-center">
                    <span class="fw-bold {{ $wg->widget_key }}_avg fs-3">-</span>
                    <span class="text-muted">Percentage</span>
                </div>
            </div>
            <div class="my-1 border w-100"></div>
            <div class="h-150px scroll-y">
                <div id="chart_absence_rate" data-height="400" class="mh-150px overflow-auto"></div>
            </div>
        </div>
    </div>
</div>
