@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Employers List</h3>
        </div>
        <div class="card-body">
            <table class="table display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Account Owner</th>
                        <th>Industry</th>
                        <th>Total Job Ads</th>
                        <th>Job Ad Tayang</th>
                        <th>Review</th>
                        <th>HRIS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comps as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $item->company_name }}</span>
                                    <span>{{ ucwords(strtolower($prov[$item->prov_id] ?? "")) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $employers_name[$item->owner_id] ?? "-" }}</span>
                                    <span>{{ $employers_email[$item->owner_id] ?? "" }}</span>
                                </div>

                            </td>
                            <td>{{ $indus[$item->industry_id] ?? "-" }}</td>
                            <td>{{ $job_ads->where("company_id", $item->id)->count() }}</td>
                            <td>{{ $job_ads->where("company_id", $item->id)->whereNotNull("confirm_at")->count() }}</td>
                            <td>{{ $reviews->where("company_id", $item->id)->count() }}</td>
                            <td>
                                <span class="badge badge-{{ empty($item->company_id) ? "secondary" : "light-success" }}
                                    border border-{{ empty($item->company_id) ? "secondary" : "success" }}">{{ empty($item->company_id) ? "Not Connected" : "Connected" }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm btn-active-secondary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 10px">
                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                </button>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('bp.employers.job_ads', $item->id) }}" class="menu-link px-3">
                                            Job Ads
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('bp.employers.company', $item->id) }}" class="menu-link px-3">
                                            Company Profile
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>
@endsection
