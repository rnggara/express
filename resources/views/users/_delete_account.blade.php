<!--begin::Form-->
<div class="card card-custom card-stretch">
    <form class="form" action="{{route('account.delete')}}" id="kt_form" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <!--begin::Header-->
        <div class="card-header">
            <h3 class="card-title">{{ __("user.delete_account") }}</h3>
        </div>
        <!--end::Header-->
        <div class="card-body">
            <!--begin::Form Group-->
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{$user->email}}">
                    @error('email')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <!--begin::Form Group-->
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Password</label>
                <div class="col-lg-9">
                    <input class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" id="password" name="password">
                    @error('password')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Password Confirmation</label>
                <div class="col-lg-9">
                    <input class="form-control form-control-lg" type="password" id="password_confirmation" name="password_confirmation">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label"></label>
                <div class="col-lg-9">
                    <button type="submit" class="btn btn-primary">{{ __("user.delete_account") }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!--end::Form-->
