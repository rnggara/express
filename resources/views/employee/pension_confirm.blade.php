@extends('layouts.templateContract')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Receive Pension Letter</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('employee.pension.confirm') }}" method="post">
                <div class="row">
                    <div class="col-12">
                        @if (!empty($pension->received_at))
                        <div class="alert alert-custom alert-success justify-content-center">
                            <div class="d-flex flex-column align-items-center">
                                <span class="alert-text font-size-h3 font-weight-boldest">Received</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
