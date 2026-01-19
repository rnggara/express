@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Rack</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body bg-secondary">
            <div class="d-flex flex-column">
                <span>Click at the screen to start moving the camera</span>
                <span>Use W,A,S,D to move around and use your mouse to rotate the camera</span>
                <span>Use [E] to move up and [Q] to move down</span>
                <span>Click [Esc] to exit the screen</span>
            </div>
            <div class="row">
                <div class="col-12">
                    <iframe mozallowfullscreen="true" allow="autoplay; fullscreen"  src="http://cypher.vesselholding.com/rack/?id={{ $id }}" style="border:0px #000000 none;" name="Pong: Star Wars Remix" scrolling="no" msallowfullscreen="true" allowfullscreen="true" webkitallowfullscreen="true" allowtransparency="true" frameborder="0" marginheight="px" marginwidth="320px" height="768px" width="100%"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection
