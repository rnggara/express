<div class="card-header">
    <h3 class="card-title">{{ $user->name ?? "Add User" }}</h3>
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
        <!--begin::Close-->
        <div class="btn btn-sm text-danger">
            Archive
        </div>
        <!--end::Close-->
    </div>
    <!--end::Card toolbar-->
</div>
<div class="card-body">
    <div class="rounded bg-secondary-crm p-5">
        <form action="{{ route("crm.pref.crm.user.store", empty($user) ? 'user-add' : "user-update") }}" method="post" enctype="multipart/form-data">
            <div class="fv-row mb-5">
                <label for="" class="col-form-label w-100">Profile Picture</label>
                <!--begin::Image input-->
                <div class="image-input image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(/assets/media/svg/avatars/blank.svg)">
                    <!--begin::Image preview wrapper-->
                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{ asset($user->user_img ?? "theme/assets/media/svg/avatars/blank.svg") }})"></div>
                    <!--end::Image preview wrapper-->

                    <!--begin::Edit button-->
                    <label class="btn d-none btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change"
                    data-bs-toggle="tooltip"
                    data-bs-dismiss="click"
                    title="Change Profile Picture">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" id="user_img" name="user_img" accept=".png, .jpg, .jpeg" />
                        <input type="hidden" name="user_img_remove" />
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="cancel"
                    data-bs-toggle="tooltip"
                    data-bs-dismiss="click"
                    title="Cancel Profile Picture">
                        <i class="ki-outline ki-cross fs-3"></i>
                    </span>
                    <!--end::Cancel button-->
                </div>
                <!--end::Image input-->
                <div class="d-flex align-items-center">
                    <label for="user_img" class="btn btn-primary btn-sm" data-kt-image-input-action="change">Picture upload</label>
                    <span class="ms-2">Max 1mb</span>
                </div>
            </div>
            <div class="fv-row">
                <label for="name" class="col-form-label required">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Input Full Name" value="{{ $user->name ?? "" }}">
            </div>
            <div class="fv-row">
                <label for="name" class="col-form-label">Job Title</label>
                <select name="job_title" class="form-select" data-control="select2" data-placeholder="Select Job Title">
                    <option value=""></option>
                    @foreach ($job_title as $item)
                        <option value="{{ $item->id }}" {{ $item->id == ($user->crm_job_title ?? "") ? "SELECTED" : "" }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row">
                <label for="name" class="col-form-label">Team</label>
                <select name="teams[]" class="form-select" multiple data-control="select2" data-placeholder="Select Team">
                    <option value=""></option>
                    @foreach ($teams as $item)
                        <option value="{{ $item->id }}" {{ in_array($item->id, $myTeamId) ? "SELECTED" : "" }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row">
                <label for="name" class="col-form-label">Default Pipeline</label>
                <select name="default_pipeline" class="form-select" data-control="select2" data-placeholder="Select Team">
                    <option value=""></option>
                    @foreach ($pipelines as $item)
                        <option value="{{ $item->id }}" {{ $item->id == ($user->default_pipeline ?? "") ? "SELECTED" : "" }}>{{ $item->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row">
                <label for="name" class="col-form-label">Role</label>
                <select name="role" class="form-select" data-control="select2" data-placeholder="Select Team">
                    <option value=""></option>
                    @foreach ($roles as $item)
                        <option value="{{ $item->id }}" {{ $item->id == ($user->crm_role ?? "") ? "SELECTED" : "" }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <hr>
            <div class="fv-row">
                <label for="name" class="col-form-label">Phone Number</label>
                <div class="row">
                    <div class="col-4">
                        <select name="phone_type" class="form-select" data-control="select2" data-placeholder="Work">
                            <option value=""></option>
                            <option value="Work">Work</option>
                            <option value="Mobile">Mobile</option>
                            <option value="Home">Home</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-8">
                        <div class="position-relative">
                            <input type="text" name="phone_number" class="form-control phone-number" placeholder="Input Phone Number" value="{{ $user->phone ?? "" }}">
                            <button type="button" id="d-add-phone" class="btn position-absolute top-0 end-0 btn-icon">
                                <i class="la la-plus text-primary"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="d-phone-user" class="mt-3">
                    @if (count($user->phones ?? []) > 0)
                        @foreach ($user->phones as $item)
                            <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                                <div class="d-flex align-items-center">
                                    <i class="la la-phone me-1 text-primary"></i>
                                    <span>{{ $item['type'] }} : {{ $item['phone'] }}</span>
                                </div>
                                <input type='hidden' name='phone[]' value="{{ $item['phone'] }}">
                                <input type='hidden' name='phone_types[]' value="{{ $item['type'] }}">
                                <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="fv-row">
                <label for="name" class="col-form-label {{ empty($user) ? "required" : "" }}">Email</label>
                @if (empty($user))
                    <input type="text" name="email" class="form-control pe-12" required placeholder="Input Email" value="">
                @else
                    <div class="position-relative">
                        <input type="text" name="email_input" class="form-control pe-12" placeholder="Input Email" value="">
                        <button type="button" id="d-add-email" class="btn position-absolute top-0 end-0 btn-icon">
                            <i class="la la-plus text-primary"></i>
                        </button>
                    </div>
                    <div id="d-email-user" class="mt-3">
                        <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                            <div class="d-flex align-items-center">
                                <i class="la la-mail-bulk me-1 text-primary"></i>
                                <span>{{ $user->email ?? "" }}</span>
                            </div>
                        </div>
                        @if (count($user->emails ?? []) > 0)
                            @foreach ($user->emails as $item)
                                <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                                    <div class="d-flex align-items-center">
                                        <i class="la la-mail-bulk me-1 text-primary"></i>
                                        <span>{{ $item }}</span>
                                    </div>
                                    <input type='hidden' name='email[]' value="{{ $item }}">
                                    <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>
            <hr>
            <div class="fv-row">
                <label for="alamat" class="col-form-label">Address</label>
                <div class="d-add-address">
                    @foreach ($address as $item)
                    <div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>
                            <div class="align-items-baseline align-items-center d-flex flex-column">
                                <span class="fw-bold">{{ $item->title }}</span>
                                <span>{{ $item->address }}</span>
                            </div>
                            <input type='hidden' name='address[]' value="{{ $item->address }}">
                            <input type='hidden' name='title[]' value="{{ $item->title }}">
                            <input type='hidden' name='full_address[]' value="{{$item->full_address}}">
                            <input type='hidden' name='postal_code[]' value="{{$item->postal_code}}">
                            <input type='hidden' name='country[]' value="{{$item->country}}">
                            <input type='hidden' name='province[]' value="{{$item->province}}">
                            <input type='hidden' name='city[]' value="{{$item->city}}">
                            <input type='hidden' name='subdistrict[]' value="{{$item->subdistrict}}">
                            <button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn text-primary p-0" onclick="modalAddress(this)">
                        <i class="fa fa-plus"></i>
                        Add Address
                    </button>
                </div>
            </div>
            <hr>
            <div class="fv-row">
                <label for="name" class="col-form-label">Company</label>
                <input type="text" class="form-control" readonly placeholder="Input Company" value="{{ $user_comp->company_name }}">
            </div>
            <div class="d-flex mt-5">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id ?? "" }}">
                <button type="button" id="kt_drawer_example_basic_close" class="btn text-primary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
