@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-2 mb-8">Pivot Tables</span>
                <div class="row row-cols-3">
                    <div class="cols p-5">
                        <div class="card card-border">
                            <div class="card-body border rounded bg-hover-secondary" data-toggle="navigation" data-target="applicant">
                                <div class="d-flex justify-content-center">Applicant</div>
                            </div>
                        </div>
                    </div>
                    <div class="cols p-5">
                        <div class="card card-border">
                            <div class="card-body border rounded bg-hover-secondary" data-toggle="navigation" data-target="#">
                                <div class="d-flex justify-content-center">Employers</div>
                            </div>
                        </div>
                    </div>
                    <div class="cols p-5">
                        <div class="card card-border">
                            <div class="card-body border rounded bg-hover-secondary" data-toggle="navigation" data-target="#">
                                <div class="d-flex justify-content-center">Job Ad</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            console.log("hello")
                $("div[data-toggle=navigation]").click(function(){
                var target = $(this).data("target")
                console.log(target)
                if(target != "#"){
                    location.href = `{{ route('pivot.view') }}/${target}`
                }
            })
        })
    </script>
@endsection
