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
                        <span class="fw-bold">Employement</span>
                        <span class="text-muted fs-base fw-normal">Menyajikan tren pergerakan Employee baru & resign tiap bulan</span>
                    </div>
                </div>
            </h3>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end gap-3">
                    <select name="etloc" class="form-select min-w-200px" onchange="loadEmpTrendChart()" data-control="select2" data-allow-clear="true" data-placeholder="All Location" id="">
                        <option value=""></option>
                        @foreach ($master['locations'] ?? [] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <select name="etdep" class="form-select min-w-200px" onchange="loadEmpTrendChart()" data-control="select2" data-allow-clear="true" data-placeholder="All Departement" id="">
                        <option value=""></option>
                        @foreach ($master['departements'] ?? [] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <select name="etyear" class="form-select" onchange="loadEmpTrendChart()" data-control="select2" data-placeholder="{{ date("Y") }}" id="">
                        @for ($i = date("Y") - 5; $i < (date("Y") + 5) ; $i++)
                            <option value="{{ $i }}" {{ $i == date("Y") ? "SELECTED" : "" }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body rounded h-100">
            <div id="emp_trend_chart_modal" class="min-h-500px" style="height: 500px;"></div>
        </div>
    </div>
</div>
<div class="border-0 modal-footer">
</div>
