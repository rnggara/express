@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Edit Project</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route("marketing.project") }}" class="btn btn-success btn-icon btn-sm">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="form" method="post" action="{{route('marketing.project.update')}}"
                              enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <br>
                        <h4>Basic Info</h4>
                        <hr>
                        <div class="form-group">
                            <label>Project Code</label>
                            <input type="text" class="form-control" name="prj_code" value="{{$prj->prj_code}}"
                                readonly/>
                        </div>
                        <div class="form-group">
                            <label>Project Name</label>
                            <input type="text" class="form-control" name="prj_name" placeholder="Project Name"
                                value="{{$prj->prj_name}}" required/>
                        </div>
                        <div class="form-group">
                            <label>Project prefix</label>
                            <input type="text" class="form-control" name="prefix" value="{{$prj->prefix}}"
                                placeholder="Project Name" required/>
                        </div>
                        <div class="form-group">
                            <label>Project Category</label>
                            <select class="form-control" name="category" value="{{$prj->category}}" required>
                                <option value="cost" {{ ($prj->category == "cost") ? "SELECTED" : "" }}>COST</option>
                                <option value="sales" {{ ($prj->category == "sales") ? "SELECTED" : "" }}>SALES</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Project Value</label>
                            <input type="number" class="form-control" name="prj_value" value="{{$prj->value}}"
                                placeholder="" required/>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-circle text-white" aria-hidden="true"></i>
                            Please note that Project Value will be related to the amount that will be generated on
                            invoice out
                        </div>
                        <div class="form-group">
                            <label>Project Client</label>
                            <select class="form-control" name="client" required>
                                @foreach($clients as $key => $client)
                                    <option value="{{$client->id}}"
                                            @if($client->id == $prj->id_client) selected @endif>{{$client->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <br>
                        <h4>Project Detail</h4>
                        <hr>
                        <div class="form-group">
                            <label>Project</label>
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="prj_start"
                                        value="{{$prj->start_time}}" placeholder="" required>
                                    <small><i>start</i></small>
                                </div>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" name="prj_end" value="{{$prj->end_time}}"
                                        placeholder="" required>
                                    <small><i>end</i></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Project Currency</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <select class="form-control" name="currency" required>
                                        @foreach($arrCurrency as $key2 => $value)
                                            <option value="{{$key2}}"
                                                    @if($key2 == $prj->currency) selected @endif>{{$key2}}
                                                - {{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Project Address</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="address" required>{{$prj->address}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Latitude</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control number-geo" name="latitude" value="{{ $prj->latitude }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Longitude</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control number-geo" name="longitude" value="{{ $prj->longitude }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>File Quotation List</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <select class="form-control" name="quotation" required>
                                        <option value="1">Q1</option>
                                        <option value="2">Q2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Attach WO</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type='file' name='wo_attach'>
                                    <span class="form-text text-muted">Max file size is 500KB </span>
                                    @if(!empty($prj->wo_attach))
                                        <a href="{{ str_replace("public", "public_html", asset("marketing\\uploads/$prj->wo_attach")) }}" class="btn btn-icon btn-xs btn-success" download>
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Agreement #</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="agreement"
                                        value="{{$prj->agreement_number}}" placeholder="" required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Agreement Title</label>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                            <textarea class="form-control" name="agreement_title"
                                                    required>{{$prj->agreement_title}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h4>Financial Transport</h4>
                        <hr>

                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 control-label">Travel</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="transport" value="{{$prj->transport}}"
                                    required placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 control-label">Taxi</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="taxi" value="{{$prj->taxi}}" required
                                    placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 control-label">Car Rent</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="rent" value="{{$prj->rent}}"
                                    placeholder="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 control-label">Airport Tax</label>
                            <div class="col-sm-12">
                                <input type="hidden" name="id" value="{{$prj->id}}">
                                <input type="number" class="form-control" name="airtax" value="{{$prj->airtax}}"
                                    placeholder="" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    @csrf
                    <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                        <i class="fa fa-check"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-title">Preparation Cost</h3>
            <div class="card-toolbar">
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('marketing.project.prep.add') }}" method="post">
                        <table class="table table-bordered display">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prep as $i => $item)
                                    <tr>
                                        <td class="text-center">{{ $i+1 }}</td>
                                        <td class="text-left">
                                            {!! $item->descriptions !!}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($item->amount, 2) }}
                                        </td>
                                        <td class="text-center">
                                            {{ date("d F Y", strtotime($item->plan_date)) }}
                                        </td>
                                        <td class="text-center">
                                            @if (empty($item->approved_at))
                                                <button type="button" onclick="_appr({{ $item->id }}, 'Approve')" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-check-circle"></i> Approve
                                                </button>
                                                <button type="button" onclick="_appr({{ $item->id }}, 'Reject')" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-times"></i> Reject
                                                </button>
                                            @else
                                            <i class="fa fa-check-circle text-{{ ($item->paid == 1) ? "primary" : "success" }}"></i>
                                            {{ $item->approved_by }} / {{ date("d/m/Y", strtotime($item->approved_at)) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>#</td>
                                    <td>
                                        <textarea name="descriptions" required class="form-control" placeholder="Budget for" id="" cols="30" rows="5"></textarea>
                                    </td>
                                    <td>
                                        <input type="text" required class="form-control number" name="amount" placeholder="Amount">
                                    </td>
                                    <td>
                                        <input type="date" required class="form-control" name="plan_date">
                                    </td>
                                    <td class="text-center">
                                        @csrf
                                        <input type="hidden" name="id_project" value="{{ $prj->id }}">
                                        <button type="submit" class="btn btn-primary btn-sm">Add New</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>

        function _appr(id, appr){
            Swal.fire({
                title: "Are you sure?",
                text: "",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: appr
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route("marketing.project.prep.appr") }}?id=" + id + "&set="+appr
                }
            });
        }

        $(document).ready(function(){
            $(".number").number(true, 2)
            $("table.display").DataTable()
        })
    </script>
@endsection
