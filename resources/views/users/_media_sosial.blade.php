<div class="card card-custom bg-transparent">
    <!--begin::Header-->
    <div class="card-header py-3 border-0">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">{{ __("user.media_sosial") }}</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
        </div>
        <div class="card-toolbar">

        </div>
    </div>
    <!--end::Header-->
    <div class="card-body bg-white rounded border">
        <div class="row">
            <div class="col-12 text-center">
                @if ($data['media_sosial']->count() == 0)
                    <button type="button" class="btn btn btn-link text-primary text-hover-dark" onclick="modalShow(this)" data-name="media_sosial">
                        <i class="ki-outline ki-plus-circle fs-2 text-primary"></i>
                        Tambahkan {{ __("user.media_sosial") }}
                    </button>
                @else
                    @foreach ($data['media_sosial'] as $item)
                        <div class="bg-light-secondary border card card-custom mb-5">
                            <div class="card-body">
                                <div class="d-flex justify-content-end position-absolute ms-n5 ms-md-0 text-right" style="width: 95%">
                                    <button type="button" class="btn btn-circle btn-hover-bg-light btn-icon" onclick="modalShow(this)" data-name="media_sosial" data-id="{{ $item->id }}" data-act="edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-start">
                                    @foreach ($item->toArray() as $key => $val)
                                        @if (!in_array($key, ['id', 'user_id', "created_at", "deleted_at", "updated_at"]))
                                            @if (!empty($val))
                                                <span class="font-weight-bolder">{{ ucwords($key == "others" ? "Link lain" : $key) }}</span>
                                                <span class="mb-5">{{ $val }}</span>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
