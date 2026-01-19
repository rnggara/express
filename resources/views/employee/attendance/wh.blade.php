@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Work Hour</h3>
        </div>
        <div class="card-body">
            <form class="form" method="POST" action="{{route('emp.wh.save')}}">
                @csrf
                <input type="hidden" name="id_company" value="{{\Session::get('company_id')}}">
                <input type="hidden" name="id" value="{{($preferences != null) ?$preferences->id : ''}}">
                <div class="card-body">
                    <div class="row">
                        <label class="col-xl-3"></label>
                        <div class="col-lg-9 col-xl-6">
                            <!-- <h5 class="font-weight-bold mb-6">Customer Info</h5> -->
                        </div>
                    </div>

                    <div class="form-group mb-5 row">
                        <label class="col-xl-1 col-lg-12 col-form-label text-right">Clock In</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control" type="time" name="clock_in" value="{{($preferences != null) ? $preferences->clock_in : ''}}" />
                        </div>
                    </div>
                    <div class="form-group mb-5 row">
                        <label class="col-xl-1 col-lg-12 col-form-label text-right">Clock Out</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control" type="time" name="clock_out" value="{{($preferences != null) ? $preferences->clock_out : ''}}" />
                        </div>
                    </div>
                    <div class="form-group mb-5 row">
                        <label class="col-xl-1 col-lg-12 col-form-label text-right">Break Out</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control" type="time" name="break_out" value="{{($preferences != null) ? $preferences->break_out : ''}}" />
                        </div>
                        <div class="col-xl-3 d-flex align-items-center">
                            <div class="form-check col-form-label me-3">
                                <input class="form-check-input" {{ ($preferences != null) && $preferences->hide_break_session == 1 ? "CHECKED" : '' }} type="checkbox" name="hide_break" value="1" id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault">
                                    Hide on ESS
                                </label>
                            </div>
                            <div class="symbol symbol-20px symbol-circle border">
                                <div class="symbol-label fw-semibold bg-hover-light-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="If this option is checked, Break out and Break in on KerjaKu Mobile app will not show, only Check in and Check out will show.">
                                    <i class="fa fa-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-5 row">
                        <label class="col-xl-1 col-lg-12 col-form-label text-right">Break in</label>
                        <div class="col-lg-9 col-xl-6">
                            <input class="form-control" type="time" name="break_in" value="{{($preferences != null) ? $preferences->break_in : ''}}" />
                        </div>
                    </div>
                    <div class="form-group mb-5 row">
                        <label class="col-xl-1 col-lg-12 col-form-label text-right"></label>
                        <div class="col-lg-9 col-xl-6">
                            <button type="submit" name="savePayrollPeriod" value="save" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
