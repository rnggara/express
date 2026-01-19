@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
            <h3 class="card-title">Job Ads</h3>
        </div>
        <div class="card-body">
            <table class="table display table-row-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Job Ads</th>
                        <th>Creator</th>
                        <th>Tanggal dibuat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($job_ads as $i => $item)
                        @php
                            $bgBadge = "dark";
                            $txtBadge = "Non Aktif";
                            if(!empty($item->activate_at)){
                                $bgBadge = "warning";
                                $txtBadge = "Waiting";
                                if(!empty($item->confirm_at)){
                                    $bgBadge = "success";
                                    $txtBadge = "Tayang";
                                }

                                if(!empty($item->rejected_at)){
                                    $bgBadge = "danger";
                                    $txtBadge = "Tolak";
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $item->position }}</span>
                                    <span>{{ $company[$item->company_id] ?? "-" }}</span>
                                </div>
                            </td>
                            <td>{{ $user[$item->user_id] ?? "-" }}</td>
                            <td>@dateId($item->created_at)</td>
                            <td>
                                <a href="{{ route("bp.job_ads.review", $item->id) }}" class="badge badge-light-{{$bgBadge}} border border-{{ $bgBadge }}">{{$txtBadge}}</a>
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
        $("table.display").DataTable({
            dom : `t<"d-flex align-items-center  justify-content-end justify-content-md-end"<"dataTable-length-info-label me-3">lip>`
        })
    </script>
@endsection
