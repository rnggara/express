@extends('_crm.preferences.index')

@section('view_content')
<form action="{{ route('crm.pref.general.basic_information.post') }}" method="post" enctype="multipart/form-data">
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Informasi</h3>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <div class="d-flex">
                    <div class="d-flex flex-column" data-toggle="imageInput">
                        <div class="w-300px img-wrapper h-250px rounded bgi-position-center bgi-no-repeat bgi-size-cover" style="background-image: url('{{ asset($user->user_img ?? "images/image_placeholder.png") }}')"></div>
                        <span class="my-3 text-muted text-center">Maximum image size is 5 MB</span>
                        <label class="btn btn-primary">
                            Unggah Foto
                            <input type="file" name="image" class="d-none">
                        </label>
                    </div>
                    <div class="border" style=" margin-left: 12px; margin-right: 12px;"></div>
                    <div class="flex-fill">
                        <div class="row">
                            <div class="col-6 fv-row">
                                <label class="col-form-label required">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" id="">
                            </div>
                            <div class="col-6 fv-row">
                                <label class="col-form-label">Jenis Kelamin</label>
                                <select name="gender" class="form-select" data-control="select2" data-placeholder="Pilih Jenis Kelamin" id="">
                                    <option value=""></option>
                                    @foreach ($genders as $item)
                                        <option value="{{ $item->id }}" {{ $user->gender == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="fv-row">
                            <label class="col-form-label required">Email</label>
                            <input type="text" name="email" disabled class="form-control" value="{{ $user->email }}" id="">
                        </div>
                        <div class="fv-row">
                            <label class="col-form-label required">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control phone-number" placeholder="Example: +62851111111" value="{{ $user->phone }}" id="">
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
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

            $("input.phone-number").each(function(){
                Inputmask({
                    "mask" : "+999999999999"
                }).mask(this);
            })
        })
    </script>
@endsection
