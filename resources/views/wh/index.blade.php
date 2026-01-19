@extends('layouts.template')
@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Location List</h3><br>

            </div>
            @actionStart('warehouses', 'create')
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItem"><i
                            class="fa fa-plus"></i>Add Location</button>
                </div>
                <!--end::Button-->
            </div>
            @actionEnd
        </div>
        <div class="card-body">
            <table class="table display">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Location Name</th>
                        <th class="text-center">Address</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">PIC</th>
                        <th class="text-center">Company</th>
                        <th class="text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @actionStart('warehouses', 'read')
                    @foreach ($whs as $key => $val)
                        @php
                            if (!empty($val->office)) {
                                if ($val->office == 1) {
                                    $icon = 'fas fa-building';
                                } elseif ($val->office == 2) {
                                    $icon = 'fas fa-warehouse';
                                } elseif ($val->office == 3) {
                                    $icon = 'fas fa-map-marker';
                                } elseif ($val->office == 4) {
                                    $icon = 'fas fa-people';
                                } else {
                                    $icon = 'fa fa-users';
                                }
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">
                                <a href="{{ route("wh.view", $val->id) }}">
                                    {!! !empty($val->office) ? '<i class="' . $icon . ' text-primary"></i>' : '' !!} {{ $val->name }}
                                </a>
                            </td>
                            <div class="modal fade" id="editItem{{ $val->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="addEmployee" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Location</h5>
                                        </div>
                                        <form method="post" action="{{ route('wh.update') }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">Location Name</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" name="name"
                                                            placeholder="Location Name" value="{{ $val->name }}">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="id" value="{{ $val->id }}">
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">Address</label>
                                                    <div class="col-md-9">
                                                        <textarea name="address" id="" class="form-control" cols="30" rows="10">{!! $val->address !!}</textarea>
                                                    </div>
                                                </div>
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">Telephone</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="telephone" class="form-control"
                                                            placeholder="Telephone" value="{{ $val->telephone }}">
                                                    </div>
                                                </div>
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">PIC</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="pic" class="form-control"
                                                            placeholder="PIC" value="{{ $val->pic }}">
                                                    </div>
                                                </div>
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">Type</label>
                                                    <div class="col-md-9">
                                                        <select name="_type" class="form-control" data-control="select2"
                                                            id="" data-placeholder="Select Type">
                                                            <option value="">Others</option>
                                                            <option value="1"
                                                                {{ $val->office == 1 ? 'SELECTED' : '' }}>office</option>
                                                            <option value="2"
                                                                {{ $val->office == 2 ? 'SELECTED' : '' }}>warehouse
                                                            </option>
                                                            <option value="3"
                                                                {{ $val->office == 3 ? 'SELECTED' : '' }}>project
                                                            </option>
                                                            <option value="5"
                                                                {{ $val->office == 5 ? 'SELECTED' : '' }}>meeting room
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- <div class="fv-row row mb-3">
                                                <label class="col-md-3 col-form-label text-right">Office</label>
                                                <div class="col-md-9">
                                                    <div class="checkbox-inline">
                                                        <label class="checkbox checkbox-outline checkbox-primary col-form-label">
                                                            <input type="checkbox" name="office" {{ ($val->office == 1) ? "checked" : "" }}/>
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> --}}
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">Latitude</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="latitude" class="form-control"
                                                            value="{{ $val->latitude }}" placeholder="Latitude">
                                                    </div>
                                                </div>
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">Longitude</label>
                                                    <div class="col-md-9">
                                                        <input type="text" name="longitude" class="form-control"
                                                            value="{{ $val->longitude }}" placeholder="Longitude">
                                                    </div>
                                                </div>
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">Radius</label>
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            <input type="int" name="longitude2" class="form-control border-right-0" placeholder="Radius" value="{{ $val->longitude2 }}">
                                                            <span class="input-group-text border-left-0 bg-transparent">meters</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fv-row row mb-3">
                                                    <label class="col-md-3 col-form-label text-right">Button Color</label>
                                                    <div class="col-md-9">
                                                        <select class="form-control btn_class text-white"
                                                            name="btn_class">
                                                            <option style="background-color: #3699FF; color: white"
                                                                value="primary"
                                                                {{ $val->btn_class == 'primary' ? 'SELECTED' : '' }}>Blue
                                                            </option>
                                                            <option style="background-color: #FFA800; color: white"
                                                                value="warning"
                                                                {{ $val->btn_class == 'warning' ? 'SELECTED' : '' }}>
                                                                Yellow</option>
                                                            <option style="background-color: #1BC5BD; color: white"
                                                                value="success"
                                                                {{ $val->btn_class == 'success' ? 'SELECTED' : '' }}>
                                                                Green</option>
                                                            <option style="background-color: #F64E60; color: white"
                                                                value="danger"
                                                                {{ $val->btn_class == 'danger' ? 'SELECTED' : '' }}>Red
                                                            </option>
                                                            <option style="background-color: #8950FC; color: white"
                                                                value="info"
                                                                {{ $val->btn_class == 'info' ? 'SELECTED' : '' }}>Purple
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="submit"
                                                    class="btn btn-primary font-weight-bold">
                                                    <i class="fa fa-check"></i>
                                                    Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <td class="text-center">{{ strip_tags($val->address) }}</td>
                            <td class="text-center">{{ $val->telephone }}</td>
                            <td class="text-center">{{ $val->pic }}</td>
                            <td class="text-center">
                                {{ $view_company[$val->company_id]->tag }}
                            </td>
                            <td class="text-center" nowrap="now">
                                @actionStart('warehouses', 'update')
                                <button type="button" class="btn btn-primary btn-sm btn-icon"
                                    onclick="printqr('{{ $val->id }}')"><i class="fa fa-qrcode"></i></button>
                                <button type="button" class="btn btn-primary btn-sm btn-icon" data-bs-toggle="modal"
                                    data-bs-target="#editItem{{ $val->id }}"><i class="fa fa-edit"></i></button>
                                @actionEnd
                                @actionStart('warehouses', 'delete')
                                <a class="btn btn-danger btn-sm btn-icon"
                                    href="{{ route('wh.delete', ['id' => $val->id]) }}" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete?'); ">
                                    <i class="fa fa-trash"></i>
                                </a>
                                @actionEnd
                            </td>
                        </tr>
                    @endforeach
                    @actionEnd
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Location</h5>
                </div>
                <form method="post" action="{{ route('wh.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">Location Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Location Name"
                                    required>
                            </div>
                        </div>

                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">Address</label>
                            <div class="col-md-9">
                                <textarea name="address" id="" class="form-control" cols="30" rows="10" required></textarea>
                            </div>
                        </div>
                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">Telephone</label>
                            <div class="col-md-9">
                                <input type="text" name="telephone" class="form-control" placeholder="Telephone"
                                    required>
                            </div>
                        </div>
                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">PIC</label>
                            <div class="col-md-9">
                                <input type="text" name="pic" class="form-control" placeholder="PIC" required>
                            </div>
                        </div>
                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">Type</label>
                            <div class="col-md-9">
                                <select name="_type" class="form-control" data-control="select2" id=""
                                    data-placeholder="Select Type">
                                    <option value="">Others</option>
                                    <option value="1">office</option>
                                    <option value="2">warehouse</option>
                                    <option value="3">project</option>
                                    <option value="5">meeting room</option>
                                </select>
                            </div>
                        </div>
                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">Latitude</label>
                            <div class="col-md-9">
                                <input type="text" name="latitude" class="form-control" placeholder="Latitude">
                            </div>
                        </div>
                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">Longitude</label>
                            <div class="col-md-9">
                                <input type="text" name="longitude" class="form-control" placeholder="Longitude">
                            </div>
                        </div>
                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">Radius</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="int" name="longitude2" class="form-control border-right-0" placeholder="Radius">
                                    <span class="input-group-text border-left-0 bg-transparent">meters</span>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row row mb-3">
                            <label class="col-md-3 col-form-label text-right">Button Color</label>
                            <div class="col-md-9">
                                <select class="form-control btn_class text-white" name="btn_class">
                                    <option style="background-color: #3699FF; color: white" value="primary">Blue</option>
                                    <option style="background-color: #FFA800; color: white" value="warning">Yellow
                                    </option>
                                    <option style="background-color: #1BC5BD; color: white" value="success">Green</option>
                                    <option style="background-color: #F64E60; color: white" value="danger">Red</option>
                                    <option style="background-color: #8950FC; color: white" value="info">Purple</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <iframe src="#" width="0" height="0" name="qrprint" id="qrprint" frameborder="0"></iframe>
@endsection
@section('custom_script')
    <script>
        function printqr(id) {
            $("#qrprint").attr('src', "{{ route('wh.qr') }}/" + id)
            $('#qrprint').on('load', function() {
                $(this).show();
                window.frames["qrprint"].focus();
                window.frames["qrprint"].print();
            });
        }

        function selClass(me) {
            var val = $(me).val()
            console.log($(me).val())
            $(me).addClass("bg-" + val)
        }

        $(document).ready(function() {
            $("select.btn_class").each(function() {
                selClass(this)
            })
            $("select.btn_class").change(function() {
                console.log(this)
                selClass(this)
            })
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            $("select.select2").select2({
                width: "100%"
            })
        })
    </script>
@endsection
