@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <h3 class="card-title">Permission</h3>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <table class="table display bg-white" data-paging="false" data-searching="false" data-b-info="false" data-ordering="false" id="table-dashboard">
                    <thead>
                        <tr>
                            <th>Form Name</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $key => $item)
                            <tr>
                                <td>{{ ucwords(strtolower(str_replace("_", " ", $key))) }}</td>
                                <td>
                                    <select name="select_dashboard" class="form-select w-auto dashboard-status" data-key="{{ $key }}" data-control="select2" data-hide-search="true" id="">
                                        <option value="1" {{ isset($set[$key]) && $set[$key] == 1 ? "SELECTED" : "" }}>Show</option>
                                        <option value="0" {{ isset($set[$key]) && $set[$key] == 0 ? "SELECTED" : "" }}>Hide</option>
                                    </select>
                                </td>
                                <td>{{ \Config::get("constants.APP_NAME") }}</td>
                                <td>
                                    <button type="button" class="btn p-0">
                                        <i class="fa fa-ellipsis-v text-dark"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script>
        $(".dashboard-status").change(function(){
            var key = $(this).data("key")
            var status = $(this).val()
            $.ajax({
                url : "{{ route("crm.pref.crm.dashboard.update") }}/" + key + "/" + status,
                type : "get"
            })
        })
    </script>
@endsection
