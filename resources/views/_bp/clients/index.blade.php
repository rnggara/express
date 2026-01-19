@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Client List</h3>
        </div>
        <div class="card-body">
            <table class="table display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Expired Date</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $item->company_name }}</td>
                            <td>{{ empty($item->subscribe_end) ? "-" : date("d F Y", strtotime($item->subscribe_end)) }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm" onclick="open_navigate_modal({{$item->id}})">
                                    <i class="fa fa-ellipsis-v text-dark"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form action="{{ route('bp.clients.navigate') }}" method="post">
        <div class="modal fade" tabindex="-1" id="modalNavigate">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="fv-row" id="fv1">
                            <label for="navigate_to" class="col-form-label">Navigate To</label>
                            <select name="navigate_to" id="navigate_to" class="form-select" data-control="select2" data-dropdown-parent="#fv1" data-placeholder="Select Target">
                                <option value=""></option>
                                @foreach ($list as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="company_id">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Go!</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_script')
    <script>

        function open_navigate_modal(id){
            $("#modalNavigate").modal("show")
            $("#modalNavigate input[name=company_id]").val(id)
        }

        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>
@endsection
