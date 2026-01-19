<div class="modal-body overflow-hidden">
    <div class="card h-100">
        <div class="card-header border-0 px-0">
            <h3 class="card-title">
                <div class="d-flex gap-3 align-items-center">
                    <div class="symbol symbol-40px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-cake-birthday text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">Employee Birthday</span>
                        <span class="text-muted fs-base fw-normal">Data ini menyajikan list ulang tahun employee</span>
                    </div>
                </div>
            </h3>
        </div>
        <div class="card-body rounded h-100">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6 nav-fill">
                @foreach ($months as $i => $item)
                    <li class="nav-item">
                        <a class="nav-link text-active-primary {{ $i == date("n") ? "active" : "" }}" data-bs-toggle="tab" href="#tab_{{ $i }}">{{ $item }}</a>
                    </li>
                @endforeach
                {{-- <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Link 1</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Link 2</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Link 3</a>
                </li> --}}
            </ul>
            <div class="tab-content scroll-y h-100" id="myTabContent">
                @foreach ($months as $i => $item)
                    <div class="tab-pane fade {{ $i == date("n") ? "show active" : "" }}" id="tab_{{ $i }}" role="tabpanel">
                        <div class="card shadow-none bg-secondary-crm">
                            <div class="card-body">
                                <div class="d-flex flex-column gap-5">
                                    <div class="fv-row w-25">
                                        <div class="position-relative">
                                            <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                            <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                        </div>
                                    </div>
                                    <table class="table table-display-2 bg-white" id="table-list-{{ $i }}">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Position</th>
                                                <th>Tanggal Ulang Tahun</th>
                                                <th>Umur</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($emp_birth->where("month_lahir", $i) as $item)
                                                @php
                                                    $d1 = date_create($item->emp_lahir);
                                                    $d2 = date_create(date("Y-m-d"));
                                                    $d3 = date_diff($d2, $d1);
                                                    $y = $d3->format("%y")
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
                                                        <span>{{ $item->position->name ?? "-" }}</span>
                                                    </td>
                                                    <td>
                                                        {{ date("d F Y", strtotime($item->emp_lahir)) }}
                                                    </td>
                                                    <td>{{ $y }} Tahun</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                    ...
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                    ...
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                    ...
                </div> --}}
            </div>
        </div>
    </div>
</div>
<div class="border-0 modal-footer">
</div>
