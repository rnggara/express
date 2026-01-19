<div class="card card-custom bg-transparent mb-3">
    <div class="card-header border-bottom-0">
        <h3 class="card-title">Informasi Keluarga <span class="text-muted">(Optional)</span></h3>
        <div class="card-toolbar">
            @if ($data['family']->count() > 0)
                <button type="button" class="btn btn-primary btn-sm" onclick="modalShow(this)" data-name="family">
                    Tambahkan Informasi Keluarga
                </button>
            @endif
        </div>
    </div>
    <div class="card card-body bg-white rounded border">
        <div class="row accordion" id="accordion-family">
            @if ($data['family']->count() === 0)
                <button type="button" class="btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="family">
                    <i class="ki-outline ki-plus-circle fs-2 text-primary">
                    </i>
                    Tambahkan Informasi Keluarga
                </button>
            @else
            @foreach ($data['family'] as $i => $item)
                <div class="col-12 accordion mb-5">
                    <div class="d-flex flex-column border rounded">
                        <div class="d-flex justify-content-between accordion accordion-icon-collapse p-5 align-items-center">
                            <span class="fw-bold">Informasi Keluarga {{ $i+1 }}</span>
                            <div class="d-flex justify-content-between border-0">
                                <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="family" data-id="{{ $item->id }}" data-act="edit">
                                    <i class="fa fa-edit text-dark"></i>
                                </span>
                                <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="family" data-id="{{ $item->id }}" data-act="delete">
                                    <i class="fa fa-trash text-danger"></i>
                                </span>
                                <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                    <div class="accordion-heder collapsed" data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}" aria-controls="collapse{{ $item->id }}">
                                        <span class="accordion-icon">
                                            <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="collapse{{ $item->id }}" class="collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-family">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Nama</span>
                                        <span class="fw-semibold">{{ $item->name }}</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Relasi</span>
                                        <span class="fw-semibold">{{ $item->hubungan }}</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Status Pernikahan</span>
                                        <span class="fw-semibold">{{ $item->hubungan }}</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Jenis Kelamin</span>
                                        <span class="fw-semibold">{{ $item->hubungan }}</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Tanggal Lahir</span>
                                        <span class="fw-semibold">@dateId($item->tgl_lahir)</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Nomor Telepon</span>
                                        <span class="fw-semibold">{{ $item->no_telp }}</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">Upload Dokumen</span>
                                        <span class="fw-semibold">
                                            @if (!empty($item->lampiran))
                                            <a href="{{ asset($item->lampiran) }}">
                                                @php
                                                    $_lampiran = explode("/", $item->lampiran);
                                                    $_fname = explode("_", end($_lampiran));
                                                @endphp
                                                {{ implode("_", array_slice($_fname, 3)) }}
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<div class="card card-custom bg-transparent mb-3">
    <div class="card-header border-bottom-0">
        <h3 class="card-title">Lisensi <span class="text-muted">(Optional)</span></h3>
        <div class="card-toolbar">
            @if ($data['license']->count() > 0)
                <button type="button" class="btn btn-primary btn-sm" onclick="modalShow(this)" data-name="license">
                    Tambahkan Lisensi
                </button>
            @endif
        </div>
    </div>
    <div class="card card-body bg-white rounded border">
        <div class="row accordion" id="accordion-license">
            @if ($data['license']->count() === 0)
                <button type="button" class="btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="license">
                    <i class="ki-outline ki-plus-circle fs-2 text-primary">
                    </i>
                    Tambahkan Lisensi
                </button>
            @else
            @foreach ($data['license'] as $i => $item)
                <div class="col-12 accordion mb-5">
                    <div class="d-flex flex-column border rounded">
                        <div class="d-flex justify-content-between accordion accordion-icon-collapse p-5 align-items-center">
                            <span class="fw-bold">Lisensi {{ $i+1 }}</span>
                            <div class="d-flex justify-content-between border-0">
                                <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="license" data-id="{{ $item->id }}" data-act="edit">
                                    <i class="fa fa-edit text-dark"></i>
                                </span>
                                <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="license" data-id="{{ $item->id }}" data-act="delete">
                                    <i class="fa fa-trash text-danger"></i>
                                </span>
                                <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                    <div class="accordion-heder collapsed" data-bs-toggle="collapse" data-bs-target="#collapseLicense{{ $item->id }}" aria-controls="collapseLicense{{ $item->id }}">
                                        <span class="accordion-icon">
                                            <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="collapseLicense{{ $item->id }}" class="collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-license">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Nama</span>
                                        <span class="fw-semibold">{{ $item->name }}</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Organisasi Penerbit</span>
                                        <span class="fw-semibold">{{ $item->organisasi }}</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Tanggal Penerbitan</span>
                                        <span class="fw-semibold">@dateId($item->tgl_penerbitan)</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Jenis Kelamin</span>
                                        <span class="fw-semibold">
                                            @if (!empty($item->tgl_kadaluarsa))
                                            @dateId($item->tgl_kadaluarsa)
                                            @else
                                            -
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">Nomor Lisensi</span>
                                        <span class="fw-semibold">{{ $item->no_lisensi }}</span>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        <span class="fw-bold">URL</span>
                                        <span class="fw-semibold">{{ $item->url ?? "-" }}</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">Upload Dokumen</span>
                                        <span class="fw-semibold">
                                            @if (!empty($item->lampiran))
                                            <a href="{{ asset($item->lampiran) }}">
                                                @php
                                                    $_lampiran = explode("/", $item->lampiran);
                                                    $_fname = explode("_", end($_lampiran));
                                                @endphp
                                                {{ implode("_", array_slice($_fname, 3)) }}
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<div class="card card-custom bg-transparent">
    <div class="card-header border-bottom-0">
        <h3 class="card-title">KTP <span class="text-muted">(Optional)</span></h3>
    </div>
    <div class="card card-body bg-white rounded border">
        <div class="row accordion" id="accordion-ktp">
            @if (empty($data['id_card']))
                <button type="button" class="btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="ktp">
                    <i class="ki-outline ki-plus-circle fs-2 text-primary">
                    </i>
                    Tambahkan KTP
                </button>
            @else
            @php
                $item = $data['id_card']
            @endphp
            <div class="col-12 accordion mb-5">
                <div class="d-flex flex-column border rounded">
                    <div class="d-flex justify-content-between accordion accordion-icon-collapse p-5 align-items-center">
                        <span class="fw-bold">KTP</span>
                        <div class="d-flex justify-content-between border-0">
                            <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="ktp" data-id="{{ $item->id }}" data-act="edit">
                                <i class="fa fa-edit text-dark"></i>
                            </span>
                            <span class="me-3 bg-hover-secondary p-3 rounded" onclick="modalShow(this)" data-name="ktp" data-id="{{ $item->id }}" data-act="delete">
                                <i class="fa fa-trash text-danger"></i>
                            </span>
                            <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                <div class="accordion-heder collapsed" data-bs-toggle="collapse" data-bs-target="#collapseKTP{{ $item->id }}" aria-controls="collapseKTP{{ $item->id }}">
                                    <span class="accordion-icon">
                                        <i class="fa fa-caret-down fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <i class="fa fa-caret-up fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="collapseKTP{{ $item->id }}" class="collapse border-top" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#accordion-ktp">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="d-flex flex-column mb-3">
                                    <span class="fw-bold">Nomor KTP</span>
                                    <span class="fw-semibold">{{ $item->no_kartu }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Upload Dokumen</span>
                                    <span class="fw-semibold">
                                        @if (!empty($item->lampiran))
                                        <a href="{{ asset($item->lampiran) }}">
                                            @php
                                                $_lampiran = explode("/", $item->lampiran);
                                                $_fname = explode("_", end($_lampiran));
                                            @endphp
                                            {{ implode("_", array_slice($_fname, 3)) }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
