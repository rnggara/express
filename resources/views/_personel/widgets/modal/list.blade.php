<div class="modal-body overflow-hidden">
    <div class="card h-100">
        <div class="card-header border-0 px-0">
            <h3 class="card-title">
                <div class="d-flex gap-3 align-items-center">
                    <div class="symbol symbol-40px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-chart-pie text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">List Request & Update Personel</span>
                        <span class="text-muted fs-base fw-normal">Menyajikan rasio karyawan berdasarkan parameter yang dapat dipilih</span>
                    </div>
                </div>
            </h3>
        </div>
        <div class="card-body rounded h-100">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6 nav-fill">
                <li class="nav-item">
                    <a class="nav-link text-active-primary active" data-bs-toggle="tab" href="#tab_onboarding">Onboarding</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary" data-bs-toggle="tab" href="#tab_promotion">Promotion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary" data-bs-toggle="tab" href="#tab_expired_contract">Expired Contract</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary" data-bs-toggle="tab" href="#tab_resign">Resign</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary" data-bs-toggle="tab" href="#tab_update_data">Update Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary" data-bs-toggle="tab" href="#tab_document">Document</a>
                </li>
            </ul>
            <div class="tab-content h-100 scroll-y" id="myTabContent">
                <div class="tab-pane fade show active" id="tab_onboarding" role="tabpanel">
                    <div class="card shadow-none bg-secondary-crm">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-5">
                                <div class="fv-row w-25">
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" id="table-list-onboarding">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Position</th>
                                            <th>Join Date</th>
                                            <th>End Date</th>
                                            <th>Phase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($emp->whereIn("id", array_keys($emp_id)) as $item)
                                            @php
                                                $user_id = $emp_id[$item->id];
                                                $ob = $detail['onboarding']->where("user_id", $user_id)->first();
                                                $_do = collect($do[$ob->id] ?? []);
                                                $ld = $_do->sortByDesc("desc")->first() ?? [];
                                                $due_date = $ld->due_date ?? null;
                                                $_approved = $_do->whereNotNull("approved_at")->count();
                                                $phase = "";
                                                $phaseClass = "";
                                                $d1 = date_create($ob->created_at);
                                                $d2 = date_create(date("Y-m-d"));
                                                $d3 = date_diff($d2, $d1);
                                                $m = $d3->format("%m");
                                                $d = $d3->format("%d");
                                                if($_approved == $_do->count()){
                                                    $phase = "Finish";
                                                    $phaseClass = "success";
                                                } else {
                                                    if($m > 0){
                                                        $phase = "$m Bulan ";
                                                    }
                                                    $phase .= "$d Hari";
                                                    $phaseClass = "warning";
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <div class="symbol symbol-40px">
                                                            <div class="symbol-label" style="background-image: url('{{ asset($user_img[$item->id] ?? 'images/image_placeholder.png') }}')"></div>
                                                        </div>
                                                        <div class="d-flex flex-column gap-1">
                                                            <span class="fw-bold">{{ $item->emp_name }}</span>
                                                            <span class="text-muted">{{ $item->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $item->position->name ?? "-" }}
                                                </td>
                                                <td>{{ date("d F Y", strtotime($item->join_date ?? $item->created_at)) }}</td>
                                                <td>
                                                    {{ date("d F Y", strtotime($due_date)) }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-outline badge-{{ $phaseClass }}">{{ $phase }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_promotion" role="tabpanel">
                    <div class="card shadow-none bg-secondary-crm">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-5">
                                <div class="fv-row w-25">
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" id="table-list-promotion">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Manager</th>
                                            <th>Departement</th>
                                            <th>Before</th>
                                            <th>After</th>
                                            <th>Start Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detail['promotion'] as $pm)
                                            @php
                                                $item = $emp->where("id", $pm->personel_id)->first();
                                            @endphp
                                            @if (!empty($item))
                                                <tr>
                                                    <td>
                                                        <div class="d-flex gap-3">
                                                            <div class="symbol symbol-40px">
                                                                <div class="symbol-label" style="background-image: url('{{ asset($user_img[$item->id] ?? 'images/image_placeholder.png') }}')"></div>
                                                            </div>
                                                            <div class="d-flex flex-column gap-1">
                                                                <span class="fw-bold">{{ $item->emp_name }}</span>
                                                                <span class="text-muted">{{ $item->emp_id }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>-</td>
                                                    <td>{{ $item->user->uacdepartement->name ?? "-" }}</td>
                                                    <td>{{ $dm[$pm->old] ?? "-" }}</td>
                                                    <td>{{ $dm[$pm->new] ?? "-" }} {{ $pm->new }}</td>
                                                    <td>{{ date("d F Y", strtotime($pm->start_date)) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_expired_contract" role="tabpanel">
                    <div class="card shadow-none bg-secondary-crm">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-5">
                                <div class="fv-row w-25">
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" id="table-list-resign">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Position</th>
                                            <th>Departement</th>
                                            <th>Manager</th>
                                            <th>Type Contract</th>
                                            <th>Exp Contract</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($emp_expired as $item)
                                            @php
                                                $pm = $detail['resign']->where("emp_id", $item->id)->first();
                                                $user = $item->user ?? [];
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <div class="symbol symbol-40px">
                                                            <div class="symbol-label" style="background-image: url('{{ asset($user_img[$item->id] ?? 'images/image_placeholder.png') }}')"></div>
                                                        </div>
                                                        <div class="d-flex flex-column gap-1">
                                                            <span class="fw-bold">{{ $item->emp_name }}</span>
                                                            <span class="text-muted">{{ $item->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->position->name ?? "-" }}</td>
                                                <td>{{ $user->uacdepartement->name ?? "-" }}</td>
                                                <td>{{ "-" }}</td>
                                                <td>{{ $item->employee_status->name ?? "-" }}</td>
                                                <td>{{ date("d F Y", strtotime($item->employee_status_mutation_end)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_resign" role="tabpanel">
                    <div class="card shadow-none bg-secondary-crm">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-5">
                                <div class="fv-row w-25">
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" id="table-list-resign">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Position</th>
                                            <th>Type</th>
                                            <th>Manager</th>
                                            <th>Request Date</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($emp->whereIn("id", $detail['resign']->pluck("emp_id")) as $item)
                                            @php
                                                $pm = $detail['resign']->where("emp_id", $item->id)->first();
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <div class="symbol symbol-40px">
                                                            <div class="symbol-label" style="background-image: url('{{ asset($user_img[$item->id] ?? 'images/image_placeholder.png') }}')"></div>
                                                        </div>
                                                        <div class="d-flex flex-column gap-1">
                                                            <span class="fw-bold">{{ $item->emp_name }}</span>
                                                            <span class="text-muted">{{ $item->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item->position->name ?? "-" }}</td>
                                                <td>{{ $rtype[$pm->resign_type] ?? "-" }}</td>
                                                <td>-</td>
                                                <td>{{ date("d F Y", strtotime($pm->created_at)) }}</td>
                                                <td>{{ $res_reason[$pm->resign_reason] ?? "-" }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_update_data" role="tabpanel">
                    <div class="card shadow-none bg-secondary-crm">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-5">
                                <div class="fv-row w-25">
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" id="table-list-update-data">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Time Update</th>
                                            <th>Update</th>
                                            <th>Before</th>
                                            <th>After</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detail['update_data'] as $pm)
                                            @php
                                                $item = $emp->where("id", $pm->personel_id)->first();
                                            @endphp
                                            @if (!empty($item))
                                            <tr>
                                                <td>
                                                    <div class="d-flex gap-3">
                                                        <div class="symbol symbol-40px">
                                                            <div class="symbol-label" style="background-image: url('{{ asset($user_img[$item->id] ?? 'images/image_placeholder.png') }}')"></div>
                                                        </div>
                                                        <div class="d-flex flex-column gap-1">
                                                            <span class="fw-bold">{{ $item->emp_name }}</span>
                                                            <span class="text-muted">{{ $item->emp_id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ date("d F Y", strtotime($pm->created_at)) }}</td>
                                                <td>{{ ucwords(str_replace("_", " ", $pm->type)) }}</td>
                                                <td>{{ $req_data[$pm->type ?? ''][$pm->old] ?? ($pm->old ?? "-") }}</td>
                                                <td>{{ $req_data[$pm->type ?? ''][$pm->new] ?? ($pm->new ?? "-") }}</td>
                                                <td>
                                                    <span class="badge badge-outline badge-{{ empty($pm->approved_at) ? "warning" : "success" }}">{{ empty($pm->approved_at) ? "Persetujuan" : "Approved" }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_document" role="tabpanel">
                    <div class="card shadow-none bg-secondary-crm">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-5">
                                <div class="fv-row w-25">
                                    <div class="position-relative">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                </div>
                                <table class="table table-display-2 bg-white" id="table-list-document">
                                    <thead>
                                        <tr>
                                            <th>Document Name</th>
                                            <th>ID Document</th>
                                            <th>Pemegang</th>
                                            <th>Exp Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detail['document'] as $item)
                                            @php
                                                $_emp = $emp->where("id", $item->user_id)->first();
                                            @endphp
                                            @if (!empty($_emp))
                                                <tr>
                                                    <td>{{ $item->cv_name }}</td>
                                                    <td>{{ sprintf("%06d", $item->id) }}</td>
                                                    <td>{{ $_emp->emp_name }}</td>
                                                    <td>{{ date("d M Y", strtotime($item->expiry_date)) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
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
