@php
    $pct = \Helper_function::getProfile();
    $detailProfile = \Helper_function::getDetailProfile();
@endphp
    <div class="rside d-none d-md-inline ms-5 min-w-300px">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-column mb-3">
                        <span class="fw-bold">Kelengkapan Profil {{ $pct }}%</span>
                        <span class="text-muted">Lengkapkan profil Anda untuk meningkatkan kesempatan Anda</span>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    @foreach ($detailProfile as $key => $item)
                        <div class="menu-item">
                            <a href="{{ route("account.info") }}?v={{ $item['link'] }}" class="menu-link px-0 text-dark">
                                <div class="menu-title d-flex align-items-center">
                                    <div class="form-check form-check-custom form-check-solid form-check-sm">
                                        <input class="form-check-input" {{ $item['check'] == 1 ? "checked" : "" }} disabled type="checkbox" value="" id="ck{{ $key }}"/>
                                        <label class="form-check-label" for="ck{{ $key }}">
                                        </label>
                                    </div>
                                    <span>{{ ucwords(str_replace("_", " ", $key)) }}</span>
                                </div>
                                <span class="menu-arrow"></span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
