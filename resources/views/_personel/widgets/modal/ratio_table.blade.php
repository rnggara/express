<div class="d-flex flex-column gap-5 scroll-y">
    <div class="d-flex align-items-center gap-5">
        <div class="position-relative me-5">
            <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
        </div>
        @if ($tp != "division")
            <div>
                <select name="div" class="form-select min-w-200px" data-control="select2" data-allow-clear="true" data-dropdown-parent="#rt-table" data-placeholder="All Departement">
                    <option value=""></option>
                    @foreach ($master['departements'] ?? [] as $item)
                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    <table class="table table-display-2" id="table-ratio" data-page-length="5">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th class="d-none">Departement</th>
                <th class="d-none">Data</th>
                @if ($tp == "education")
                    <th>History Education</th>
                    <th>Join Date</th>
                @elseif($tp == "level")
                    <th>Kontrak Berakhir</th>
                    <th>Join Date</th>
                @elseif($tp == "location")
                    <th>Domisili</th>
                @else
                    <th>Join Date</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($emp_list as $item)
                @php
                    $user = $item->user ?? [];
                    $profile = $item->profile ?? [];
                    $dd = "";
                    if ($tp == "education") {
                        $edut = collect($emp_data[$item->id])->last();
                        if(!empty($edut)){
                            $dd = $edut->degree;
                        }
                    } elseif($tp == "level") {
                        $dd = $edata[$item->job_level_id] ?? "";
                    } elseif($tp == "division") {
                        $dd = $edata[$user->uac_departement ?? null] ?? "";
                    } elseif($tp == "contract") {
                        $dd = $edata[$item->employee_status_id] ?? "";
                    } elseif($tp == "gender") {
                        $dd = $edata[$profile->gender ?? null] ?? "";
                    } elseif($tp == "location") {
                        $dd = $edata[$user->uac_location ?? null] ?? "";
                    }

                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="symbol symbol-40px">
                                <div class="symbol-label" style="background-image: url({{ asset($user->user_img ?? "images/image_placeholder.png") }})"></div>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <span class="fw-bold">{{ $item->emp_name }}</span>
                                <span class="text-muted">{{ $item->emp_id }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">{{ $item->position->name ?? "-" }}</span>
                            <span class="text-muted">{{ $item->job_level->name ?? "-" }}</span>
                        </div>
                    </td>
                    <td class="d-none">
                        {{ $user->uacdepartement->name ?? "" }}
                    </td>
                    <td class="d-none">
                        {{ $dd }}
                    </td>
                    @if ($tp == "education")
                        @php
                            $edut = collect($emp_data[$item->id])->last();
                        @endphp
                        <td>
                            <div class="d-flex flex-column gap-2">
                                <span class="fw-bold">{{ $edut->school_name ?? "" }}</span>
                                <span class="text-muted">{{ $edut->still ? "Masih menempuh pendidikan" : "Lulus ".$edut->year_graduate }}</span>
                            </div>
                        </td>
                        <td>{{ date("d F Y", strtotime($item->join_date ?? $item->created_at)) }}</td>
                    @elseif($tp == "level")
                        <td>Kontrak Berakhir</td>
                        <td>{{ date("d F Y", strtotime($item->join_date ?? $item->created_at)) }}</td>
                    @elseif($tp == "location")
                        <td>
                            {{ $profile->resident_address ?? "-" }}
                        </td>
                    @else
                        <td>{{ date("d F Y", strtotime($item->join_date ?? $item->created_at)) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
