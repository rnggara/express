@extends('_crm.preferences.index')

@section('view_content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fw-bold fs-3">Add Pipeline</span>
                    <div class="d-flex align-items-center">
                        <a href="{{ empty($pipeline) ? "javascript:;" : route('crm.pref.crm.opportunity.properties', $pipeline->id) }}" class="btn btn-primary {{ empty($pipeline) ? "disabled" : "" }}">
                            View properties
                        </a>
                        <a href="{{ route("crm.pref.crm.opportunity.index") }}" class="btn text-primary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center mb-5">
                <span class="text-muted">Preference <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                <span class="text-muted">Opportunity <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                <span class="fw-semibold">New Pipeline</span>
            </div>
            <form action="{{ route('crm.pref.crm.opportunity.store') }}" method="post">
                <input type="hidden" name="id" value="{{ $pipeline->id ?? "" }}">
                <div class="card-body bg-secondary-crm rounded mb-5">
                    @csrf
                    <div class="fv-row">
                        <label for="pipe_label" class="col-form-label required">Nama Pipeline</label>
                        <input type="text" class="form-control" name="pipe_label" required placeholder="Input pipeline name" value="{{ $pipeline->label ?? "" }}">
                    </div>
                    <div id="list-funnel">
                        @if (!empty($pipeline->funnel))
                            @foreach ($pipeline->funnel as $item)
                                @component('_crm.preferences.opportunity._add_funnel')
                                    @slot('funnel_name')
                                        {{ $item->label }}
                                    @endslot
                                    @slot('status')
                                        {{ $item->status_funnel }}
                                    @endslot
                                    @slot('idle_state')
                                        {{ $item->idle_state }}
                                    @endslot
                                    @slot('warning_state')
                                        {{ $item->warning_state }}
                                    @endslot
                                    @slot('id')
                                        {{ $item->id }}
                                    @endslot
                                @endcomponent
                            @endforeach
                        @endif
                    </div>
                    <hr>
                    <button type="button" onclick="add_funnel()" class="btn px-0 text-primary">
                        <i class="la la-plus text-primary fs-3"></i>
                        Tambah Funnel
                    </button>
                </div>
                <div class="d-flex justify-content-end">
                    <input type="hidden" name="funnel_delete" id="funnel_delete" value="">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('view_script')
    <script>

        var remove_id = []

        function remove_funnel(me, id = null){
            $(me).parents(".funnel-item").remove()
            if(id != "" && id != null){
                remove_id.push(id)
                $("#funnel_delete").val(JSON.stringify(remove_id))
            }
        }

        function add_funnel(){
            $.ajax({
                url : "{{ route('crm.pref.crm.opportunity.add_funnel') }}",
                type : "get"
            }).then(function(resp){
                $("#list-funnel").append(resp)
                $("#list-funnel select[data-control=select2]").select2()

                Inputmask({
                    "mask": "9{*} d\\ays"
                }).mask("#list-funnel .input-days");
                $("[data-bs-toggle=tooltip]").tooltip()
            })
        }

        Inputmask({
            "mask": "9{*} d\\ays"
        }).mask(".input-days");
    </script>
@endsection
