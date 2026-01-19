@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">{{ $company->company_name }}</h3>
                    <div class="d-flex align-items-center mb-5">
                        <span class="text-muted">Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">Company <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <span class="text-muted">Company Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                        <a href="{{ route("crm.pref.company.company_list.detail", base64_encode($company->id)) }}" class="text-muted">{{ $company->company_name }} <i class="fa fa-chevron-right mx-3 text-dark-75"></i></a>
                        <span class="fw-semibold">Company Detail</span>
                    </div>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <form action="{{ route('crm.pref.company.company_list.update_detail', ['type' => 'company-detail', 'id' => $company->id]) }}" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column">
                        <div class="d-flex">
                            <div class="d-flex flex-column" data-toggle="imageInput">
                                <div class="w-300px img-wrapper h-200px rounded bgi-position-center bgi-no-repeat bgi-size-contain" style="background-image: url('{{ asset((stripos("media/attachments", $company->app_logo) !== false ? "$company->app_logo" : "images/".$company->app_logo) ?? "image_placeholder.png") }}')"></div>
                                <span class="my-3 text-muted text-center">Maximum image size is 5 MB</span>
                                <label class="btn btn-primary">
                                    Upload Photo
                                    <input type="file" name="image" class="d-none">
                                </label>
                            </div>
                            <div class="border" style=" margin-left: 12px; margin-right: 12px;"></div>
                            <div class="flex-fill">
                                <div class="row">
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label required">Company Name</label>
                                        <input type="text" name="company_name" class="form-control" value="{{ $company->company_name }}" id="">
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">Website</label>
                                        <input type="text" name="website" placeholder="Input Website" class="form-control" value="{{ $company->website }}" id="">
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">Industry</label>
                                        <select name="industry" class="form-select" data-control="select2" data-placeholder="Select Industry" id="">
                                            <option value=""></option>
                                            @foreach ($industry as $item)
                                                <option value="{{ $item->id }}" {{ $company->industry == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">Phone Number</label>
                                        <input type="text" name="phone" placeholder="Example: +62851111111" class="form-control phone-number" value="{{ $company->phone[0] == 0 ? "62".substr($company->phone,1) : $company->phone }}" id="">
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">Company Mail</label>
                                        <input type="text" name="email" placeholder="Input Company Mail" class="form-control" value="{{ $company->email }}" id="">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12 fv-row">
                                        <label class="col-form-label">Address</label>
                                        <input type="text" name="address" placeholder="Input Address" class="form-control" value="{{ $company->address }}" id="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5 fv-row">
                                        <label class="col-form-label">Zip Code</label>
                                        <input type="text" name="zip_code" placeholder="Input Zip Code" class="form-control" value="{{ $company->zip_code }}" id="">
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">Country</label>
                                        <input type="text" name="country" placeholder="Input country" class="form-control" value="{{ $company->country }}" id="">
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">State/Province</label>
                                        <input type="text" name="province" placeholder="Input province" class="form-control" value="{{ $company->province }}" id="">
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">City</label>
                                        <input type="text" name="city" placeholder="Input city" class="form-control" value="{{ $company->city }}" id="">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">BPJS Ketenagakerjaan</label>
                                        <input type="text" name="bpjs_tk" placeholder="Input BPJS Ketenagakerjaan Number" class="form-control" value="{{ $company->bpjs_tk }}" id="">
                                    </div>
                                    <div class="col-6 fv-row">
                                        <label class="col-form-label">Company NPWP</label>
                                        <input type="text" name="npwp" placeholder="Input Company NPWP Number" class="form-control" value="{{ $company->npwp }}" id="">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12 fv-row d-flex flex-column">
                                        <label class="col-form-label">Upload Document</label>
                                        <span class="text-muted mb-3">Upload your scanned signature with company stamp to be attached on 1721-A1 document.</span>
                                        <div class="d-flex align-items-center">
                                            <label class="btn btn-secondary">
                                                <input type="file" name="signature" class="d-none">
                                                Attachment
                                                <i class="fi fi-rr-clip"></i>
                                            </label>
                                            <div class="ms-5 text-primary" data-file>
                                                @if (!empty($company->signature))
                                                    @php
                                                        $signature = explode("/", $company->signature);
                                                        $fileName = explode("_", end($signature));
                                                        unset($fileName[0]);
                                                        $_fname = array_values($fileName);
                                                        $fname = implode("_", $_fname);
                                                    @endphp
                                                    {{ $fname }}
                                                @endif
                                            </div>
                                        </div>
                                        <span class="text-muted mt-3">Suggested size 100x50px, format jpg, jpeg, png</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
<script>
    $(document).ready(function(){
        $("div[data-toggle=imageInput]").each(function(){
            var input = $(this).find("input[type=file]")
            var wrapper = $(this).find("div.img-wrapper")
            $(input).change(function(){
                const file = this.files[0];
                let reader = new FileReader();
                reader.onload = function(event){
                    wrapper.css("background-image", "url("+event.target.result+")")
                    wrapper.css("background-size", "cover")
                }
                reader.readAsDataURL(file);
            })
        })

        $("input[name=signature]").change(function(){
            var val = $(this).val().split("\\")

            $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
        })

        $("input.phone-number").each(function(){
            Inputmask({
                "mask" : "+999999999999"
            }).mask(this);
        })
    })
</script>
@endsection
