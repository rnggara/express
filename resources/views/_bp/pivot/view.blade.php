@extends('layouts.template')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.css" integrity="sha512-BDStKWno6Ga+5cOFT9BUnl9erQFzfj+Qmr5MDnuGqTQ/QYDO1LPdonnF6V6lBO6JI13wg29/XmPsufxmCJ8TvQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-2 mb-8">Pivot Tables - {{ ucwords($type) }}</span>
                <div id="output"></div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pivottable/2.23.0/pivot.min.js" integrity="sha512-XgJh9jgd6gAHu9PcRBBAp0Hda8Tg87zi09Q2639t0tQpFFQhGpeCgaiEFji36Ozijjx9agZxB0w53edOFGCQ0g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        var KJKPivot = function(){
            $.ajax({
                url : `{{ route('pivot.view', $type) }}?a=pivot`,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#output").pivotUI(resp.data, {
                    rows: [],
                    cols: []
                })
            })
        }

        $(document).ready(function(){
            KJKPivot()
        })
    </script>
@endsection
