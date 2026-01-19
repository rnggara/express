@extends('_crm.preferences.index')

@section('view_content')
<style>
    #myDiv.fullscreen{
        z-index: 9999;
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
    }
</style>
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Organization Structure</h3>
                    <span>&nbsp;&nbsp; </span>
                    <div class="d-flex align-items-center mb-5">
                        <span class="text-muted">Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">Company <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">{{ $company->company_name }} <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <a href="{{ route("crm.pref.company.company_list.structure", base64_encode($company->id)) }}" class="text-muted">Structure Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></a>
                        <a href="{{ route("crm.pref.company.position.index", base64_encode($company->id)) }}" class="text-muted">Position <i class="fa fa-chevron-right mx-3 text-dark-75"></i></a>
                        <span class="fw-semibold">Structure</span>
                    </div>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <a href="{{ route("crm.pref.company.position.index", base64_encode($company->id)) }}" class="btn btn-sm btn-primary">
                            <i class="fi fi-rr-caret-left"></i>
                            Back
                        </a>
                    </div>
                    <div class="rounded bg-white p-10" id="myDiv">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="view-employee" />
                                    <label class="form-check-label" for="view-employee">
                                        View Employee
                                    </label>
                                </div>
                                <button type="button" class="btn btn-secondary">
                                    <i class="fi fi-rr-expand"></i>
                                    Enter full screen
                                </button>
                            </div>
                            <div id="tree">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
<script src="{{ asset("js/OrgChart/orgchart.js") }}"></script>
<script>

    function loadChart(employee = false){

        $("#tree").html("")

        $.ajax({
            url : "{{ route("crm.pref.company.position.structure", base64_encode($company->id)) }}?act=load-chart",
            type : "get",
            dataType : "json",
            data : {
                employee : employee
            }
        }).then(function(resp){
            OrgChart.templates.myTemplate = Object.assign({}, OrgChart.templates.ana);
            OrgChart.templates.myTemplate.size = [300, 80];
            OrgChart.templates.myTemplate.node = '<rect x="0" y="0" height="50" width="300" fill="var(--bs-primary)" rx="8" ry="8"></rect>';
            if(resp.employee){
                OrgChart.templates.myTemplate.node +=  '<rect x="0" y="40" height="40" width="300" fill="var(--bs-primary)" rx="8" ry="8"></rect>' +
                '<rect x="0" y="40" height="20" width="300" fill="var(--bs-primary)"></rect>' +
                '<circle cx="35" cy="40" r="30" fill="#d1d2d4"></circle>' +
                '<circle stroke="#939598" stroke-width="2" fill="#939598" cx="35" cy="25" r="8"></circle> ' +
                '<path d="M20,54 C20,32 50,32 50,54 L19,54" stroke="#939598" stroke-width="2" fill="#939598"></path>';
            }

            OrgChart.templates.myTemplate.ripple = {
                radius: 15,
                color: "#0890D3",
                rect: { x: 0, y: 0, width: 300, height: 80, rx: 15, ry: 15 }
            };

            var bindings = {
                field_0 : "name"
            }

            OrgChart.templates.myTemplate.field_0 = '<text width="210" class="field_0" font-weight="bold" fill="#fff" x="150" y="30" text-anchor="middle">{val}</text>';
            if(resp.employee){
                OrgChart.templates.myTemplate.field_1 = '<text width="210" class="field_1" fill="#fff" x="150" y="60" text-anchor="middle">{val}</text>';
                OrgChart.templates.myTemplate.img_0 = '<clipPath id="{randId}"><circle cx="35" cy="40" r="30"></circle></clipPath><image preserveAspectRatio="xMidYMid slice" clip-path="url(#{randId})" xlink:href="{val}" x="-15" y="-10"  width="100" height="100"></image>';
                bindings['field_1'] = "subname"
                bindings['img_0'] = "image"
            }

            var chart = new OrgChart(document.getElementById("tree"), {
                layout: OrgChart.mixed,
                template: "myTemplate",
                enableDragDrop: false,
                enableSearch: false,
                nodeBinding: bindings,

            });

            nodes = resp.nodes


            chart.load(nodes);
        })
    }

    $(document).ready(function(){
        $('button').click(function(e){
            $('#myDiv').toggleClass('fullscreen');
        });

        loadChart()

        $("#view-employee").click(function(){
            loadChart(this.checked)
        })
    })
</script>
@endsection
