<div class="card card-custom bg-transparent mb-5">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Pendidikan Formal</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            @if ($data['formal']->count() > 0)
                <button type="button" class="btn btn-primary btn-sm" onclick="modalShow(this)" data-name="formal-education">
                    <i class="fa fa-add"></i>
                    Tambah Pendidikan Formal
                </button>
            @endif
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded border">
        <div class="row">
            <div class="col-12 text-center">
                @if ($data['formal']->count() == 0)
                    <button type="button" class="btn btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="formal-education">
                        <i class="ki-outline ki-plus-circle fs-2 text-primary"></i>
                        Tambahkan Pendidikan Formal
                    </button>
                @else
                    @foreach ($data['formal']->take(5) as $item)
                        @php
                            $_file = $data['documents']->where("className", "User_formal_education")->where("class_id", $item->id)->first();
                        @endphp
                        <div class="bg-light-secondary border card card-custom mb-5">
                            <div class="card-body">
                                <div class="d-flex justify-content-md-end position-md-absolute right-0 text-right" style="width: 95%">
                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="formal-education" data-id="{{ $item->id }}" data-act="edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="formal-education" data-id="{{ $item->id }}" data-act="delete">
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
                                            <a href="{{ str_replace("public", "public_html", asset($_file->file_address)) }}" download>{{ $_file->file_name }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($data['formal']->count() > 5)
                    <div class="accordion" id="accordionFormal">
                        <div id="collapseFormal" class="collapse" data-parent="#accordionFormal">
                            <div class="row">
                                <div class="col-12">
                                    @foreach ($data['formal']->skip(5) as $item)
                                        @php
                                            $_file = $data['documents']->where("className", "User_formal_education")->where("class_id", $item->id)->first();
                                        @endphp
                                        <div class="bg-light-secondary border card card-custom mb-5">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-end position-md-absolute right-0 text-right" style="width: 95%">
                                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="formal-education" data-id="{{ $item->id }}" data-act="edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="formal-education" data-id="{{ $item->id }}" data-act="delete">
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
                                                            <a href="{{ str_replace("public", "public_html", asset($_file->file_address)) }}" download>{{ $_file->file_name }}</a>
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

<div class="card card-custom bg-transparent">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Pendidikan Non Formal <span class="text-muted">(Optional)</span></h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            @if ($data['informal']->count() > 0)
                <button type="button" class="btn btn-primary btn-sm" onclick="modalShow(this)" data-name="informal-education">
                    <i class="fa fa-add"></i>
                    Tambah Pendidikan Non Formal
                </button>
            @endif
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded border">
        <div class="row">
            <div class="col-12 text-center">
                @if ($data['informal']->count() == 0)
                    <button type="button" class="btn btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="informal-education">
                        <i class="ki-outline ki-plus-circle fs-2 text-primary"></i>
                        Tambahkan Pendidikan Non Formal
                    </button>
                @else
                    @foreach ($data['informal']->take(5) as $item)
                        @php
                            $_file = $data['documents']->where("className", "User_informal_education")->where("class_id", $item->id)->first();
                        @endphp
                        <div class="bg-light-secondary border card card-custom">
                            <div class="card-body">
                                <div class="d-flex justify-content-end position-md-absolute right-0 text-right" style="width: 95%">
                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="informal-education" data-id="{{ $item->id }}" data-act="edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="informal-education" data-id="{{ $item->id }}" data-act="delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-start">
                                    <h3>{{ $item->vendor }}</h3>
                                    <span>{{ $item->course_name }}</span>
                                    <span>{{ $idFullMonth[date("n", strtotime($item->start_date))]." ".substr($item->start_date, 0, 4) }} - {{ $item->still ? "- Sekarang" : $idFullMonth[date("n", strtotime($item->end_date))]." ".substr($item->end_date, 0, 4) }}</span>
                                    <br>
                                    <span>Deskripsi:</span>
                                    <p>{!! $item->descriptions !!}</p>
                                    <span>Dokumen:</span>
                                    <div>
                                        @if (!empty($_file))
                                            <a href="{{ str_replace("public", "public_html", asset($_file->file_address)) }}" download>{{ $_file->file_name }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($data['informal']->count() > 5)
                    <div class="accordion" id="accordionInformal">
                        <div id="collapseInformal" class="collapse" data-parent="#accordionInformal">
                            <div class="row">
                                <div class="col-12">
                                    @foreach ($data['informal']->skip(5) as $item)
                                    @php
                                        $_file = $data['documents']->where("className", "User_informal_education")->where("class_id", $item->id)->first();
                                    @endphp
                                    <div class="bg-light-secondary border card card-custom mt-5">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-end position-md-absolute right-0 text-right" style="width: 95%">
                                                <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="informal-education" data-id="{{ $item->id }}" data-act="edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="informal-education" data-id="{{ $item->id }}" data-act="delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="d-flex flex-column align-items-start">
                                                <h3>{{ $item->vendor }}</h3>
                                                <span>{{ $item->course_name }}</span>
                                                <span>{{ $idFullMonth[date("n", strtotime($item->start_date))]." ".substr($item->start_date, 0, 4) }} - {{ $item->still ? "- Sekarang" : $idFullMonth[date("n", strtotime($item->end_date))]." ".substr($item->end_date, 0, 4) }}</span>
                                                <br>
                                                <span>Deskripsi:</span>
                                                <p>{!! $item->descriptions !!}</p>
                                                <span>Dokumen:</span>
                                                <div>
                                                    @if (!empty($_file))
                                                        <a href="{{ str_replace("public", "public_html", asset($_file->file_address)) }}" download>{{ $_file->file_name }}</a>
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
                    <div class="collapsed btn btn-block text-hover-primary" data-toggle="collapse" data-target="#collapseInformal">
                        Show More
                        <i class="fa fa-chevron-down ml-3 icon-md text-primary"></i>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
