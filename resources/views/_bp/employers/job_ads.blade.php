@extends('layouts.template')

@section('content')
    <div class="d-flex flex-column">
        <a href="{{ route("bp.employers.index") }}" class="text-primary mb-5">
            <i class="fa fa-arrow-left text-primary me-3"></i>
            Back
        </a>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Job Ads List - {{$myComp->company_name}}</h3>
            </div>
            <div class="card-body">
                <table class="table rounded display" id="table-job">
                    <thead>
                        <tr>
                            <th>Nama Job Ads</th>
                            <th>Status Job Ads</th>
                            <th>Dibuat Oleh</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tanggal Tayang</th>
                            <th>Jumlah dilihat</th>
                            <th>Jumlah pelamar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($job_list as $item)
                            <tr>
                                <td>{{$item->position}}</td>
                                <td>{{ empty($item->closed) ? (empty($item->confirm_at) ? "Review" : "Tayang") : "Non Aktif" }}</td>
                                <td>{{ $users[$item->user_id] ?? "-" }}</td>
                                <td>{{ date("d/m/Y", strtotime($item->created_at)) }}</td>
                                <td>{{ empty($item->confirm_at) ? "-" : date("d/m/Y", strtotime($item->confirm_at)) }}</td>
                                <td>{{ $views->where('job_id', $item->id)->count() }}</td>
                                <td>{{ $applicant->where("job_id", $item->id)->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
