@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">{{ $company->company_name }}</h3>
                    <div class="d-flex align-items-center mb-5">
                        <span class="text-muted">Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">Company <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">{{ $company->company_name }} <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="fw-semibold">Structure Setting</span>
                    </div>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    @foreach ($tp as $key => $item)
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="d-flex flex-column">
                                <h3>{{ $item['label'] }}</h3>
                                <span>{{ $item['desc'] }}</span>
                            </div>
                            <div>
                                <a href="{{ $item['url'] }}" class="btn btn-primary">
                                    Go to setting
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
<script>
    $(document).ready(function(){
        @if(Session::has("modal"))
            $("#{{ Session::get("modal") }}").modal("show")
        @endif
    })
</script>
@endsection
