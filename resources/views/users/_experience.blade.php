<div class="card card-custom bg-transparent">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Pengalaman Kerja</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            @if ($data['experience']->count() > 0)
                <button type="button" class="btn btn-primary btn-sm" onclick="modalShow(this)" data-name="experience">
                    <i class="fa fa-add"></i>
                    Tambah Pengalaman Kerja
                </button>
            @endif
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded border">
        <div class="row">
            <div class="col-12 text-center">
                @if ($data['experience']->count() == 0)
                    <button type="button" class="btn btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="experience">
                        <i class="ki-outline ki-plus-circle fs-2 text-primary"></i>
                        Tambahkan Pengalaman Kerja
                    </button>
                @else
                    @foreach ($data['experience']->take(5) as $item)
                        @php
                            $_file = $data['documents']->where("className", "User_experience")->where("class_id", $item->id)->first();
                            $_job_level = $data['job_level']->where('id', $item->job_level)->first();
                            $_job_type = $data['job_type']->where('id', $item->job_type)->first();
                            $_specialization = $data['specialization']->where('id', $item->specialization)->first();
                            $_industry = $data['industry']->where('id', $item->industry)->first();
                        @endphp
                        <div class="bg-light-secondary border card card-custom mb-5">
                            <div class="card-body">
                                <div class="d-flex justify-content-md-end position-md-absolute right-0 text-right" style="width: 95%">
                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="experience" data-id="{{ $item->id }}" data-act="edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="experience" data-id="{{ $item->id }}" data-act="delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-start">
                                    <h3>{{ $item->company }}</h3>
                                    <span class="font-weight-bolder">{{ $item->position }} - {{ $_job_type->name ?? "" }}</span>
                                    @if (!empty($item->start_date) && $item->start_date != "-")
                                    <span>{{ $idFullMonth[date("n", strtotime($item->start_date))]." ".substr($item->start_date, 0, 4) }} - {{ $item->still ? "Sekarang" : $idFullMonth[date("n", strtotime($item->end_date))]." ".substr($item->end_date, 0, 4) }}</span>
                                    @endif
                                    <span>{{ $item->location }}</span>
                                    <br>
                                    <span>Deskripsi:</span>
                                    <p class="text-start">{!! $item->descriptions !!}</p>
                                    <span>Prestasi Kerja:</span>
                                    <p class="text-start">{!! $item->achievements !!}</p>
                                    <span>Referensi:</span>
                                    <p>{!! $item->reference.": ".$item->phone !!}</p>
                                    <span>Gaji:</span>
                                    <p>{!! number_format($item->salary, 2) !!}</p>
                                    <span>Alasan Resign:</span>
                                    <p>{!! $item->resign_reason ?? "-" !!}</p>
                                    <span>Dokumen:</span>
                                    <div>
                                        @if (!empty($_file))
                                            <a href="{{ asset($_file->file_address) }}" download>{{ $_file->file_name }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($data['experience']->count() > 5)
                    <div class="accordion" id="accordionFormal">
                        <div id="collapseFormal" class="collapse" data-parent="#accordionFormal">
                            <div class="row">
                                <div class="col-12">
                                    @foreach ($data['experience']->skip(5) as $item)
                                        @php
                                            $_file = $data['documents']->where("className", "User_experience")->where("class_id", $item->id)->first();
                                        @endphp
                                        <div class="bg-light-secondary border card card-custom mb-5">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-md-end position-md-absolute right-0 text-right" style="width: 95%">
                                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="experience" data-id="{{ $item->id }}" data-act="edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="experience" data-id="{{ $item->id }}" data-act="delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex flex-column align-items-start">
                                                    <h3>{{ $item->school_name }}</h3>
                                                    <span>{{ $item->field_of_study }}</span>
                                                    <span>{{ $idFullMonth[date("n", strtotime($item->start_date))]." ".substr($item->start_date, 0, 4) }} - {{ $item->still ? "- Sekarang" : $idFullMonth[date("n", strtotime($item->end_date))]." ".substr($item->end_date, 0, 4) }}</span>
                                                    <br>
                                                    <span>Deskripsi:</span>
                                                    <p>{!! $item->descriptions !!}</p>
                                                    <span>Dokumen:</span>
                                                    <div>
                                                        @if (!empty($_file))
                                                            <a href="{{ asset($_file->file_address) }}" download>{{ $_file->file_name }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="collapsed btn btn-block text-hover-primary" data-toggle="collapse" data-target="#collapseFormal">
                        Show More
                        <i class="fa fa-chevron-down ml-3 icon-md text-primary"></i>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
