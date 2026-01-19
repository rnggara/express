<div class="card shadow-none h-100 flex-fill card-strech">
    <div class="card-body">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex flex-column">
                    <span class="fw-bold">{{ $widgets[$wg->widget_key]['label'] }}</span>
                    <span class="text-muted">(Total Jam)</span>
                </div>
                <div class="d-flex align-items-center">
                    <select name="{{ $wg->widget_key }}_departement" onchange="loadChart('{{ $wg->widget_key }}')" class="form-select" data-control="select2" id="">
                        <option value="_all">All</option>
                        @foreach ($departements as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <div class="mx-2"></div>
                    <select name="{{ $wg->widget_key }}_year" onchange="loadChart('{{ $wg->widget_key }}')" class="form-select" data-control="select2" id="">
                        @for ($year = date("Y") - 5; $year < date("Y") + 5; $year++)
                            <option value="{{ $year }}" {{ $year == date("Y") ? "SELECTED" : "" }}>{{ $year }}</option>
                        @endfor
                    </select>
                    <div class="mx-2"></div>
                    <button type="button" class="btn btn-icon btn-sm" onclick="openModalWidget({{ $key }})">
                        <i class="fi fi-rr-settings"></i>
                    </button>
                </div>
            </div>
            <div id="chart_overtime_trend" class="mh-150px"></div>
        </div>
    </div>
</div>
