@extends('layouts.template')

@section('css')
    <link rel="stylesheet" href="{{ asset("assets/orgcharts/css/jquery.orgchart.min.css") }}">
@endsection

@section('content')
    @php
        function hasChild($item){
            $ul = "<ul>";
            foreach($item as $ch){
                $ul .= "<li id='".$ch['id']."'>".$ch['name']."</li>";
                if(count($ch['childs']) > 0){
                    $ul .= hasChild($ch['childs']);
                }
            }
            $ul .= "</ul>";
            echo $ul;
        }
    @endphp
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Structure Organization</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div id="chart-container" class="text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Add Item</h1>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/orgcharts/js/jquery.orgchart.min.js") }}"></script>
    <script>
        async function init_chart(){
            return $.ajax({
                url : "{{ route("company.st", $id) }}?source=chart",
                type : "get",
                dataType : "json",
            })
        }
        $(document).ready(async function(){
            var ch = await init_chart()
            var nodeTemplate = function(data) {
                return `
                    <div class="title ${data.class}" style="height: auto">${data.name}</div>
                `;
            };
            ch.forEach(element => {
                var id = element['id']
                var div = `<div id='chart-${id}' class="border rounded mt-3"></div>`
                $("#chart-container").append(div)
                $(`#chart-${id}`).orgchart({
                    'data' : element,
                    "verticalLevel": 3,
                    'depth': 2,
                    'toggleSiblingsResp': true,
                    'nodeTemplate': nodeTemplate,
                    // 'icons': {
                    //     'theme': 'fa fa-solid fa-sm',
                    //     'parentNode': 'fa-users',
                    //     'expandToUp': 'fa-angle-up text-success',
                    //     'collapseToDown': 'fa-angle-down text-success',
                    //     'collapseToLeft': 'fa-angle-left text-success',
                    //     'expandToRight': 'fa-angle-right text-success',
                    //     'collapsed': 'fa-circle-plus',
                    //     'expanded': 'fa-circle-minus'
                    // }
                });
                console.log(element)
            });

            $("div.node")
        })
    </script>
@endsection
