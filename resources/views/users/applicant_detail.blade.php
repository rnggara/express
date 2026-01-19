@extends('layouts.template', ['withoutFooter' => true])

@section('content')
    <div class="d-flex my-5">
        <a href="{{ route("account.my_applicant") }}">
            <i class="fa fa-arrow-left text-primary"></i>
            Kembali
        </a>
    </div>
    <div class="">
        <div class="d-flex flex-column">
            <div class="card border mb-8">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="symbol symbol-30px symbol-circle symbol-md-70px me-5">
                            <img src="{{ asset($mComp->icon ?? 'theme/assets/media/avatars/blank.png') }}" class="w-50px h-auto" alt="image">
                        </div>
                        <div class="d-flex flex-column w-100">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <span class="font-size-h3 fw-bold mb-3">{{ $job_ad->position }}</span>
                                    <span class="fw-semibold mb-3">{{ $mComp->company_name ?? '-' }}</span>
                                    <span class="fw-semibold mb-3">{{ $comp_city->name ?? $job_ad->placement }} -
                                        {{ $comp_prov->name ?? '' }}</span>
                                    <span class="mb-3">
                                        <i class="fa fa-users text-dark me-2"></i>
                                        {{ $applicants->count() }} applicants
                                    </span>
                                </div>
                                <div class="d-flex flex-column align-items-end d-none d-md-inline">
                                    <span>
                                        <i class="fa fa-video me-3 text-warning"></i>
                                        {!! $status !!}
                                    </span>
                                    <span class="text-muted">Applied : @dateId($job_applicant->created_at)</span>
                                </div>
                            </div>
                            <div class="separator separator-solid my-3 d-none d-md-inline"></div>
                            <div class="d-flex align-items-md-center flex-column flex-md-row">
                                <span class="me-5">
                                    <i class="fa fa-clock text-dark mb-3 mb-md-0 me-3"></i>
                                    {{ $job_ad->job_type == 1 ? "Fulltime" : ($job_ad->job_type == 2 ? "Freelance" : "Contract") }}
                                </span>
                                <span class="me-5">
                                    <i class="fa fa-suitcase text-dark mb-3 mb-md-0 me-3"></i>
                                    {{ $job_ad->yoe }} tahun
                                </span>
                                <span class="me-5">
                                    <span class="fw-semibold text-dark mb-3 mb-md-0 me-2">Rp. </span>
                                    @if ($job_ad->show_salary == 1)
                                        {{ number_format($job_ad->salary_min, 2, ",", ".") }}{{ !empty($job_ad->salary_max) ? " - ".number_format($job_ad->salary_max, 2, ",", ".") : '' }}
                                        /month
                                    @else
                                        Kompetitif Salary
                                    @endif
                                </span>
                                <div class="d-flex flex-column align-items-start d-inline d-md-none">
                                    <span>
                                        <i class="fa fa-video me-3 mb-3 text-warning"></i>
                                        {!! $status !!}
                                    </span>
                                    <span class="text-muted">Applied : @dateId($job_applicant->created_at)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span class="fw-bold mb-8 fs-2">Proses Interview</span>
            @foreach ($interviews as $i => $item)
            @if (!empty($item->reschedule))
                <div class="border rounded bg-white">
                    <!--begin::Header-->
                    <div class="align-items-center d-flex flex-column flex-md-row justify-content-between p-5">
                        <h3 class="fs-4 fw-semibold text-secondary">
                            <i class="fa fa-video text-secondary me-3">
                            </i>
                            Interview {{ $i+1 }}
                        </h3>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-caret-down text-muted fs-4"></i>
                        </div>
                    </div>
                </div>
            @endif
            <div class="accordion accordion-icon-toggle border rounded mb-10 bg-white" id="kt_accordion_profile{{ $item->id }}">
                <!--begin::Header-->
                <div class="accordion-header align-md-items-center d-flex flex-column flex-md-row justify-content-between p-5">
                    <h3 class="fs-4 fw-semibold">
                        <i class="fa fa-video text-dark me-3">
                        </i>
                        {{ empty($item->reschedule) ? "Interview ".($i+1) : "Reschedule" }}
                    </h3>
                    <div class="d-flex align-items-md-center flex-column flex-md-row">
                        @if($job_applicant->status == 3 && empty($item->accepted))
                            @empty($item->reschedule)
                            <button type="button" onclick="modalStatus({{ $item->id }}, 1)" class="btn btn-success me-md-3 mb-3 mb-md-0">Terima</button>
                            <button type="button" onclick="modalStatus({{ $item->id }}, -1)" class="btn btn-danger me-md-3 mb-3 mb-md-0">Tolak</button>
                            <button type="button" class="btn btn-primary me-md-3 mb-3 mb-md-0" onclick="modalReschedule({{ $item->id }})">Jadwal Ulang</button>
                            @endempty
                        @endif
                        <div data-bs-toggle="collapse" data-bs-target="#kt_accordion_profile{{ $item->id }}_item_1">
                            <span class="accordion-icon">
                                <i class="fa fa-caret-right text-dark fs-4 py-3 py-md-0"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div id="kt_accordion_profile{{ $item->id }}_item_1" class="fs-6 p-5 ps-10 show border-top" data-bs-parent="#kt_accordion_profile{{ $item->id }}">
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold mb-5">Jadwal Interview</span>
                        @php
                            $t1 = date_create(date("Y-m-d")." ".$item->int_start);
                            $t2 = date_create(date("Y-m-d")." ".$item->int_end);

                            $tdiff = date_diff($t1, $t2);
                            $duration = $tdiff->format("%h jam %i menit");
                            $uAssign = $user_assign->where("id", $item->int_officer)->first();
                        @endphp
                        <div class="border rounded mb-5 p-5 d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-50px me-5">
                                    <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($uAssign->user_img ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $uAssign->name ?? "-" }}</span>
                                    <span>{{ $mComp->company_name }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-computer text-dark fs-3 me-5"></i>
                                <span>{{ $item->int_type == 1 ? "Interview Online" : "Interview Offline" }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="far fa-calendar text-dark fs-3 me-5"></i>
                                <span>@dateId($item->int_date)</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="far fa-clock text-dark fs-3 me-5"></i>
                                <span>{{ date("H:i", strtotime($item->int_start)) }} - {{ date("H:i", strtotime($item->int_end)) }} {{ "($duration)" }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-link text-dark fs-3 me-5"></i>
                                @if($item->int_type == 1)
                                <span>Link room interview : <a href="#">{{ $item->int_link }}</a></span>
                                @else
                                <span>Lokasi interview : <span class="fw-semibold">{{ $item->int_location }}</span></span>
                                @endif
                            </div>
                            <div class="d-flex mb-3 flex-column">
                                <span>Deskripsi :</span>
                                {!! $item->int_descriptions !!}
                            </div>
                            <div class="d-flex mb-3 flex-column">
                                <span>Lampiran :</span>
                                -
                            </div>
                            <div class="d-flex mb-3 flex-column">
                                <span>Catatan :</span>
                                <span>{!! $item->int_notes ?? "-" !!}</span>
                            </div>
                            <div class="d-flex mb-3 flex-column">
                                <span>Lampiran :</span>
                                @if (!empty($item->int_file_name))
                                    <a href="{{ asset($item->int_file_address) }}">{{ $item->int_file_name }}</a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
        @endforeach
        </div>
    </div>


    <form action="{{ route("account.my_applicant.reschedule") }}" method="post">
        <div class="modal fade" tabindex="-1" id="modalReschedule">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <span class="fw-semibold">Penentuan Jadwal Ulang</span>
                                <div class="cursor-pointer" data-bs-dismiss="modal">
                                    <i class="fa fa-times text-dark"></i>
                                </div>
                            </div>
                            <span>Berikan keterangan  tanggal bersedia di interview dan alasan mengapa kamu mengajukan jadwal ulang </span>
                            <span class="text-danger mb-3">*Request jadwal hanya bisa dilakukan satu kali</span>
                            <div class="fv-row">
                                <label for="reason" class="col-form-label fw-bold">Masukkan keterangan</label>
                                <textarea name="reason" id="reason" required class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id">
                        <button type="button" class="btn btn-outline btn-outline-dark bg-white" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route("account.my_applicant.update") }}" method="post">
        <div class="modal fade" tabindex="-1" id="modalUpdate">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <span class="fw-semibold mtitle"></span>
                                <div class="cursor-pointer" data-bs-dismiss="modal">
                                    <i class="fa fa-times text-dark"></i>
                                </div>
                            </div>
                            <span>Apakah Anda yakin?</span>
                        </div>
                    </div>

                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id">
                        <input type="hidden" name="val">
                        <button type="button" class="btn btn-outline btn-outline-dark bg-white" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_script')
    <script>
        function modalReschedule(id){
            $("#modalReschedule").modal("show")
            $("#modalReschedule input[name=id]").val(id)
        }

        function modalStatus(id, val){
            $("#modalUpdate").modal("show")
            $("#modalUpdate input[name=id]").val(id)
            $("#modalUpdate input[name=val]").val(val)

            var title = val == 1 ? "Terima Interview" : "Tolak Interview"
            console.log(title)

            $("#modalUpdate .mtitle").text(title)
        }
    </script>
@endsection
