@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Job Vacancy List</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route("job.add.view") }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        Add Job Vacancy
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-boredered display">
                        <thead>
                            <tr>
                                <th>#</th>
                                @if (\Config::get("constants.IS_BP") == 1)
                                    <th>Company</th>
                                @endif
                                <th>Job Type</th>
                                <th>Position</th>
                                <th>Years of Experience</th>
                                <th>Placement</th>
                                <th>Min. Salary</th>
                                <th>Max. Salary</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $i => $item)
                                @php
                                    $jtype = $job_type->where('id', $item->job_type)->first();
                                    $us = $users->where('id', $item->user_id)->first();
                                    $comp = $myCompany->where('id', $item->company_id ?? null)->first();
                                @endphp
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    @if (\Config::get("constants.IS_BP") == 1)
                                        <td>{{ $comp->company_name }}</td>
                                    @endif
                                    <td align="center">{{ $jtype->name }}</td>
                                    <td align="center">{{ $item->position }}</td>
                                    <td align="center">{{ $item->yoe }}</td>
                                    <td align="center">{{ $item->placement }}</td>
                                    <td align="center">{{ number_format($item->salary_min, 2, ",", ".") }}</td>
                                    <td align="center">{{ number_format($item->salary_max, 2, ",", ".") }}</td>
                                    <td align="center">
                                        <button type="button" class="btn btn-primary btn-icon btn-xs" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="{{ route("job.delete", $item->id) }}" onclick="return confirm('Delete')" class="btn btn-danger btn-icon btn-xs">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                {{-- Modal edit --}}
                                <div class="modal fade" id="modalEdit{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalEdit{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Job Vacancy</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <form method="post" action="{{route('job.add')}}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="" class="col-form-label">Company</label>
                                                                <div class="col-md-12">
                                                                    <select name="company_id" class="form-control" required>
                                                                        @foreach ($myCompany as $mComp)
                                                                            <option value="{{ $mComp->id }}" {{ $mComp->id == $item->company_id ? "SELECTED" : "" }}>{{ $mComp->company_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="" class="col-form-label">Position</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" class="form-control" value="{{ $item->position }}" required name="position">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-sm-12">
                                                                    <div class="form-group">
                                                                        <label for="" class="col-form-label">Job Type</label>
                                                                        <div class="col-md-12">
                                                                            <select name="job_type" class="form-control" required>
                                                                                @foreach ($job_type as $jbtype)
                                                                                    <option value="{{ $jbtype->id }}" {{ $jbtype->id == $item->job_type ? "SELECTED" : "" }}>{{ $jbtype->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-form-label">Years of Work Experience</label>
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" required name="yoe" value="{{ $item->yoe }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-form-label">Placement</label>
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control" required name="placement" value="{{ $item->placement }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-form-label">Salary Range</label>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <input type="text" class="form-control number" placeholder="Minimum Range" required name="salary_min" value="{{ $item->salary_min }}">
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <input type="text" class="form-control number" placeholder="Maximum Range" required name="salary_max" value="{{ $item->salary_max }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-form-label">Job Description</label>
                                                                <div class="col-md-12">
                                                                    <textarea name="job_description" class="form-control ck-editor" id="" cols="30" rows="10">{!! $item->job_description !!}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                                                        <i class="fa fa-check"></i>
                                                        Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal add --}}
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalAdd" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Job Vacancy</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>

                <form method="post" action="{{route('job.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @if ($myCompany->count() == 0)
                                <div class="col-md-12">
                                    <div class="alert alert-custom alert-danger">
                                        <span class="alert-text">Anda belum memasukan data tempat anda sedang bekerja. Harap agar dapat memasukan data di Profile Saya > Pengalaman Kerja</span>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="col-form-label">Company</label>
                                    <div class="col-md-12">
                                        <select name="company_id" class="form-control" required>
                                            @foreach ($myCompany as $mComp)
                                                <option value="{{ $mComp->id }}">{{ $mComp->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Position</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" required name="position">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Job Type</label>
                                            <div class="col-md-12">
                                                <select name="job_type" class="form-control" required>
                                                    @foreach ($job_type as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Years of Work Experience</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" required name="yoe">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Placement</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" required name="placement">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Salary Range (per month)</label>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <input type="text" class="form-control number" placeholder="Minimum Range" required name="salary_min" value="0">
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control number" placeholder="Maximum Range" required name="salary_max" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-form-label">Job Description</label>
                                    <div class="col-md-12">
                                        <textarea name="job_description" class="form-control ck-editor" id="" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.min.js") }}"></script>
    <script>
        $(document).ready(function(){
            $("input.number").number(true, 2)
            $("table.display").DataTable()
        })
    </script>
@endsection
