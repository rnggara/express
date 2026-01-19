@foreach ($myList as $item)
    <div class="card card-bordered card-stretch mb-5 cursor-pointer" onclick="javascript:;location.href='{{ $item['action'] }}'">
        <div class="card-body">
            <div class="d-flex flex-column">
                <div class="d-flex mb-5">
                    <div class="symbol symbol-40px">
                        <div class="bgi-no-repeat bgi-position-center bgi-size-contain h-40px w-40px" style="background-image: url('{{ asset($item['image']) }}')"></div>
                    </div>
                    <div class="d-flex flex-column ms-5">
                        <h3>{{$item['posisi']}}</h3>
                        <span class="fw-semibold">{{ $item['company'] }}</span>
                        <span class="text-muted mb-5">{!! $item['address'] !!}</span>
                        <div class="mb-3">
                            {!! $item['status'] !!}
                        </div>
                        <div class="mb-3">
                            <i class="fa fa-user text-dark me-3"></i>
                            <span>Recruitment Officer : {!! $item['officer'] !!}</span>
                        </div>
                    </div>
                </div>
                <span class="text-muted">Dikirim : @dateId($item['apply_date'])</span>
            </div>
        </div>
    </div>
@endforeach
