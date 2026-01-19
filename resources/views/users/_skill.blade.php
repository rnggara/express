<div class="card card-custom bg-transparent">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("user.skill")." Bahasa" }}</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            @if ($data['language_skill']->count() > 0)
                <button type="button" class="btn btn-primary btn-sm" onclick="modalShow(this)" data-name="language-skill">
                    <i class="fa fa-add"></i>
                    Tambah {{ __("user.skill") }} Bahasa
                </button>
            @endif
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded border">
        <div class="row">
            <div class="col-12 text-center">
                @if ($data['language_skill']->count() == 0)
                    <button type="button" class="btn btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="language-skill">
                        <i class="ki-outline ki-plus-circle fs-2 text-primary"></i>
                        Tambahkan {{ __("user.skill")." Bahasa" }}
                    </button>
                @else
                    @foreach ($data['language_skill']->take(5) as $item)
                        @php
                            $lang = $data['language']->where("id", $item->language)->first();
                        @endphp
                        <div class="bg-light-secondary border card card-custom mb-5">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between">
                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                        <span class="mb-1 mb-md-0 fw-semibold">Bahasa</span>
                                        <span class="font-weight-bolder">{{ $lang->name }}</span>
                                    </div>
                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                        <span class="mb-1 mb-md-0 fw-semibold">Kemampuan Menulis</span>
                                        <span class="font-weight-bolder">{{ $item->writing."/5" }}</span>
                                    </div>
                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                        <span class="mb-1 mb-md-0 fw-semibold">Kemampuan Bicara</span>
                                        <span class="font-weight-bolder">{{ $item->speaking."/5" }}</span>
                                    </div>
                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                        <span class="mb-1 mb-md-0 fw-semibold">Kemampuan Membaca</span>
                                        <span class="font-weight-bolder">{{ $item->reading."/5" }}</span>
                                    </div>
                                    <div class="">
                                        <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="language-skill" data-id="{{ $item->id }}" data-act="edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="language-skill" data-id="{{ $item->id }}" data-act="delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($data['language_skill']->count() > 5)
                    <div class="accordion" id="accordionFormal">
                        <div id="collapseFormal" class="collapse" data-parent="#accordionFormal">
                            <div class="row">
                                <div class="col-12">
                                    @foreach ($data['language_skill']->skip(5) as $item)
                                        @php
                                            $lang = $data['language']->where("id", $item->language)->first();
                                        @endphp
                                        <div class="bg-light-secondary border card card-custom mb-5">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between flex-column flex-md-row">
                                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                                        <span class="fw-semibold mb-1 mb-md-0">Bahasa</span>
                                                        <span class="font-weight-bolder">{{ $lang->name }}</span>
                                                    </div>
                                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                                        <span class="fw-semibold mb-1 mb-md-0">Kemampuan Menulis</span>
                                                        <span class="font-weight-bolder">{{ $item->writing."/5" }}</span>
                                                    </div>
                                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                                        <span class="fw-semibold mb-1 mb-md-0">Kemampuan Bicara</span>
                                                        <span class="font-weight-bolder">{{ $item->speaking."/5" }}</span>
                                                    </div>
                                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                                        <span class="fw-semibold mb-1 mb-md-0">Kemampuan Membaca</span>
                                                        <span class="font-weight-bolder">{{ $item->reading."/5" }}</span>
                                                    </div>
                                                    <div class="">
                                                        <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="language-skill" data-id="{{ $item->id }}" data-act="edit">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="language-skill" data-id="{{ $item->id }}" data-act="delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
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
<div class="card card-custom bg-transparent mt-5">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("user.skill") }} Khusus</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">
            @if ($data['skill']->count() > 0)
                <button type="button" class="btn btn-primary btn-sm" onclick="modalShow(this)" data-name="skill">
                    <i class="fa fa-add"></i>
                    Tambah {{ __("user.skill") }} Khusus
                </button>
            @endif
        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded border">
        <div class="row">
            <div class="col-12 text-center">
                @if ($data['skill']->count() == 0)
                    <button type="button" class="btn btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="skill">
                        <i class="ki-outline ki-plus-circle fs-2 text-primary"></i>
                        Tambahkan {{ __("user.skill") }} Khusus
                    </button>
                @else
                    @foreach ($data['skill']->take(5) as $item)
                        @php
                            $prof = $data['proficiency']->where("id", $item->proficiency)->first();
                        @endphp
                        <div class="bg-light-secondary border card card-custom mb-5">
                            <div class="card-body">
                                <div class="d-flex justify-content-between flex-column flex-md-row">
                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                        <span class="fw-semibold mb-1 mb-md-0">Kemampuan Khusus</span>
                                        <span class="font-weight-bolder">{{ $item->skill_name }}</span>
                                    </div>
                                    <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                        <span class="fw-semibold mb-1 mb-md-0">Keahlian</span>
                                        <span class="font-weight-bolder">{{ $prof->name ?? "-" }}</span>
                                    </div>
                                    <div class="">
                                        <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="skill" data-id="{{ $item->id }}" data-act="edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="skill" data-id="{{ $item->id }}" data-act="delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($data['skill']->count() > 5)
                    <div class="accordion" id="accSkill">
                        <div id="collapseSkill" class="collapse" data-parent="#accSkill">
                            <div class="row">
                                <div class="col-12">
                                    @foreach ($data['skill']->skip(5) as $item)
                                    @php
                                        $prof = $data['proficiency']->where("id", $item->proficiency)->first();
                                    @endphp
                                    <div class="bg-light-secondary border card card-custom mb-5">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                                    <span class="fw-semibold mb-1 mb-md-0">Kemampuan Khusus</span>
                                                    <span class="font-weight-bolder">{{ $item->skill_name }}</span>
                                                </div>
                                                <div class="d-flex flex-column align-items-start mb-5 mb-md-0">
                                                    <span class="fw-semibold mb-1 mb-md-0">Keahlian</span>
                                                    <span class="font-weight-bolder">{{ $prof->name ?? "-" }}</span>
                                                </div>
                                                <div class="">
                                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="skill" data-id="{{ $item->id }}" data-act="edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="skill" data-id="{{ $item->id }}" data-act="delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="collapsed btn btn-block text-hover-primary" data-toggle="collapse" data-target="#collapseSkill">
                        Show More
                        <i class="fa fa-chevron-down ml-3 icon-md text-primary"></i>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
