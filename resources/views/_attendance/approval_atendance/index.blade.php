@extends('_attendance.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-rr-layer-plus text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Persetujuan Kehadiran</span>
            <span class="text-muted">Managemen persetujuan data kehadiran</span>
        </div>
    </div>
    <div class="d-flex flex-column">
        <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
            <li class="nav-item">
                <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_collect">
                    <span class="nav-text">Send Approval</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_history">
                    <span class="nav-text">History</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab_collect" role="tabpanel">
            <div class="row">
                <div class="col-3">
                    <div class="p-10 rounded bg-secondary-crm">
                        <form action="{{ route('attendance.collect_data.process') }}" method="post" id="form-generate" enctype="multipart/form-data">
                            <div class="fv-row">
                                <label class="col-form-label">Salary Group</label>
                                <select name="workgroup" data-required class="form-select" data-control="select2" data-placeholder="Select Workgroup" id="">
                                    <option value=""></option>
                                    @foreach ($workgroup as $item)
                                        <option value="{{ $item->id }}" {{ old("workgroup") == $item->id ? "SELECTED" : "" }}>{{ $item->workgroup_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label">Periode</label>
                                <select name="periode" data-required class="form-select" data-control="select2" data-placeholder="Select Periode" id="">
                                    <option value=""></option>
                                    @foreach ($periode as $item)
                                        <option value="{{ $item->id }}" data-start-date="{{ $item->start_date }}" data-end-date="{{ $item->end_date }}" {{ date("Y-m-d") >= $item->start_date && date("Y-m-d") <= $item->end_date ? "SELECTED" : "" }} {{ old("periode") == $item->id ? "SELECTED" : "" }}>{{ date("F Y", strtotime($item->start_date)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="fv-row col-6">
                                    <label class="col-form-label text-muted">Start Date</label>
                                    <input type="date" name="start_date" value="{{ old("start_date") ?? date("Y-m-d") }}" class="form-control" readonly>
                                </div>
                                <div class="fv-row col-6">
                                    <label class="col-form-label text-muted">End Date</label>
                                    <input type="date" name="end_date" value="{{ old("start_date") ?? date("Y-m-d") }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="mt-5 d-flex justify-content-end">
                                @csrf
                                <input type="hidden" name="type">
                                <button type="submit" class="btn btn-secondary" form-input disabled>Send Approval</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-9" id="show-schedule">
                    <div class="p-10 rounded bg-secondary-crm">
                        <div class="bg-white d-flex flex-column align-items-center p-10" data-list-empty>
                            <span class="fi fi-rr-document fs-1 text-muted"></span>
                            <span class="text-muted">No data available at the moment. Begin by adding your first data entry.</span>
                        </div>
                        <div class="d-flex flex-column d-none" data-list-exist>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="tab_history" role="tabpanel">
            <table class='table table-display-2' id='table-history'>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Machine</th>
                        <th>Range Process</th>
                        <th>Total Data</th>
                        <th>Collect By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $item)
                        @php
                            $total_data = 0;
                            $detail = $item->att_data;
                            foreach ($detail as $key => $value) {
                                foreach($value as $_val){
                                    $valid = collect($_val)->where("valid", 1)->count();
                                    $total_data += $valid >= 1 ? 1 : 0;
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ date("d F Y", strtotime($item->created_at)) }}</span>
                                    <span>{{ date("H:i", strtotime($item->created_at)) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="{{ route('attendance.collect_data.history', $item->id) }}" class="fw-bold text-dark">{{ $item->machine->machine_name }}</a>
                                    <span>{{ $item->machine->program->program_name }}</span>
                                </div>
                            </td>
                            <td>
                                {{ date("d F Y", strtotime($item->start_date)) }} - {{ date("d F Y", strtotime($item->end_date)) }}
                            </td>
                            <td>
                                {{ $total_data }} Data
                            </td>
                            <td>
                                {{ $user_name[$item->created_by] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('view_script')
    <script>


        $(document).ready(function(){
            @if(Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ Session::get("tab") }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif

            var opt = $("select[name=periode]").find("option:selected")
            var start = $(opt).data("start-date")
            var end = $(opt).data("end-date")
            $("input[name=start_date]").val(start)
            $("input[name=end_date]").val(end)

            $("select[name=periode]").change(function(){
                var opt = $(this).find("option:selected")
                var start = $(opt).data("start-date")
                var end = $(opt).data("end-date")
                $("input[name=start_date]").val(start)
                $("input[name=end_date]").val(end)
            })
        })
    </script>
@endsection
