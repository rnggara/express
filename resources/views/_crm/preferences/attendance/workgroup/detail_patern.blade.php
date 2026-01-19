<div class='card-body'>
    <form action="{{route("crm.pref.attendance.workgroup.store")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Patern</h3>
                    <span class="text-muted fs-base">Atur Detail Patern</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($patern->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Patern ID</label>
                        <input type="text" name="patern_id" value="{{$patern->patern_id}}" readonly class="form-control" placeholder="PP05">
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Patern Name</label>
                        <input type="text" name="patern_name" value="{{$patern->patern_name}}" class="form-control" placeholder="Input Data">
                        @error('patern_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Shifting Type</label>
                        <select name="shifting_type" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail" data-placeholder="Select Type" id="">
                            <option value=""></option>
                            <option value="1" {{$patern->type == 1 ? "SELECTED" : ""}}>Shifting</option>
                            <option value="-1" {{$patern->type == -1 ? "SELECTED" : ""}}>No Shifting</option>
                        </select>
                        @error('shifting_type')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex bg-white rounded p-2 justify-content-between mb-5">
                    @php
                        $day = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
                    @endphp
                    @foreach ($day as $i => $item)
                    <div class="d-flex flex-column h-40px justify-content-center w-40px">
                        <span class="text-center">{{ $i + 1 }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex flex-column repeater">
                    <div class="form-group">
                        <div data-repeater-list="sequences">
                            @if(empty($patern->sequences) || count($patern->sequences) == 0)
                            <div class="row" data-repeater-item>
                                <div class="d-flex justify-content-between mb-5">
                                    @foreach ($day as $i => $item)
                                        <input type="hidden" name="cell[{{ $i }}]">
                                        <button type="button" data-repeater-shift onclick="openModalShift(this, '#kt_drawer_detail')" class="btn bg-white btn-lg">
                                            <i class="fi fi-rr-plus text-primary"></i>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @else
                                @foreach($patern->sequences as $seq)
                                    <div class="row" data-repeater-item>
                                        <div class="d-flex justify-content-between mb-5">
                                            @foreach ($day as $i => $item)
                                                @php
                                                    $idSeq = $seq[$i] ?? null;
                                                @endphp
                                                <input type="hidden" name="cell[{{ $i }}]" value="{{$shift_data[$idSeq]['id'] ?? null}}">
                                                <button type="button" data-repeater-shift onclick="openModalShift(this, '#kt_drawer_detail')" @if(isset($shift_data[$idSeq])) style="background-color: {{$shift_data[$idSeq]['shift_color']}} " @endif class="border border-5 border-white btn btn-icon btn-lg rounded btn-lg {{ isset($shift_data[$idSeq]) ? "" : "bg-white btn-lg" }}">
                                                    @if(isset($shift_data[$idSeq]))
                                                    <span class="{{ $shift_data[$idSeq]['shift_id'] == "OFF" ? "text-dark" : "text-white" }}">{{$shift_data[$idSeq]['shift_id']}}</span>
                                                    @else
                                                    <i class="fi fi-rr-plus text-primary"></i>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="bg-white d-flex justify-content-center rounded">
                        <button type="button" class="btn text-primary" data-repeater-create>
                            <i class="fa fa-plus text-primary"></i>
                            Add Sequence
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="type" value="patern">
                <input type="hidden" name="id" value="{{$patern->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_detail_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
