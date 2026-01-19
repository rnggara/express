<div class="card-header">
    <h3 class="card-title">{{$product->label }}</h3>
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
        <!--begin::Close-->
        @if (isset($_GET['archived']))
        <a href="{{ route('crm.archive.recover', ['type' => "product", 'id' => $product->id]) }}" class="btn btn-sm btn-primary">
            Recover
        </a>
        @else
        <button type="button" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.products.archive', $product->id) }}" data-id="{{ $product->id }}" data-name="{{ $product->label }}" class="btn btn-sm text-danger">
            Archive
        </button>
        @endif
        <!--end::Close-->
    </div>
    <!--end::Card toolbar-->
</div>
<div class="card-body">
    <div class="d-flex flex-column mb-8">
        <div class="d-flex flex-column">
            <div class="min-w-500px bg-white">
                <form action="{{ route('crm.products.add') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    @if (!empty($product))
                        <input type="hidden" name="id" value="{{ $product->id }}">
                    @endif
                    <div class="px-10 py-5 rounded mb-5 row" style="background-color: rgba(247, 248, 250, 1)">
                        <div class="fv-row col-6">
                            <label for="sku" class="col-form-label required">Nama Produk</label>
                            <input type="text" name="sku" id="sku" value="{{ $product->label }}" class="form-control" required placeholder="Masukan Nama Produk">
                        </div>
                        <div class="fv-row col-6">
                            <label for="brand" class="col-form-label">Brand</label>
                            <input type="text" name="brand" id="brand" value="{{ $product->brand }}" class="form-control" placeholder="Masukan Brand">
                        </div>
                        <div class="fv-row col-6">
                            <label for="kategori" class="col-form-label">Kategori</label>
                            <input type="text" name="kategori" id="kategori" value="{{ $product->kategori }}" class="form-control" placeholder="Masukan Kategori">
                        </div>
                        <div class="fv-row col-6">
                            <label for="harga" class="col-form-label">Harga</label>
                            <input type="text" name="harga" id="harga" value="{{ number_format($product->harga, 2, ",", ".") }}" class="form-control number" placeholder="IDR 0">
                        </div>
                        <div class="fv-row col-12">
                            <label for="deskripsi" class="col-form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" cols="30" placeholder="Masukan Deskripsi" rows="10">{{ $product->deskripsi }}</textarea>
                        </div>
                        <div class="fv-row upload-file mt-5">
                            <label for="file" class="col-form-label w-100">Upload File</label>
                            <label for="add-file" data-toggle="upload_file"
                                class="btn btn-outline btn-outline-primary btn-sm">
                                <i class="fa fa-file"></i>
                                Attachment
                            </label>
                            <span class="upload-file-label">Max 25 mb</span>
                            <input id="add-file" style="display: none" data-toggle="upload_file"
                                name="attachment" accept=".jpg, .png, .pdf" type="file" />
                        </div>
                        <div class="mt-3">
                            @if (!empty($product->file_address))
                                <a class="d-block overlay" onclick="preview_img('{{ asset($product->file_address) }}')" data-fslightbox="lightbox-basic" href="javascript:;">
                                    {{ $product->file_name }}
                                </a>
                            @endif
                        </div>
                        {{-- <div class="fv-row col-12 upload-file mt-5">
                            <label for="attachment" data-toggle="upload_file"
                                class="btn btn-secondary btn-sm">
                                Attachment
                                <i class="fa fa-paperclip"></i>
                            </label>
                            <span class="upload-file-label">Max 1 mb</span>
                            <input id="attachment" style="display: none" data-toggle="upload_file"
                                name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                        </div> --}}
                        <hr class="mt-5">
                        <div class="fv-row col-12">
                            <label for="company" class="col-form-label">Company</label>
                            <div class="position-relative">
                                <input type="text" class="form-control find-company pe-15" data-name='id_client[]' data-multiple="true" placeholder="Select or add company">
                                <div class="find-result"></div>
                                <div class="find-noresult"></div>
                                <div class="find-add">
                                    @if (!empty($product->comps))
                                        @php
                                            $comps = json_decode($product->comps ?? "[]", true);
                                        @endphp
                                        @if (is_array($comps))
                                            @foreach ($comps as $cm)
                                            <div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 company-item">
                                                <div class="d-flex align-items-center">
                                                    <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                                    <span class='cursor-pointer' onclick="show_detail('company', {{ $cm }}, {{ $product->id }})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_list_extend">{{ $clients[$cm] ?? "-" }}</span>
                                                    <input type="hidden" name="company" value="{{ $cm }}">
                                                </div>
                                                <button type="button" onclick="removeCompany(this)" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                                    <i class="fi fi-rr-trash"></i>
                                                </button>
                                            </div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                                <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Contact">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 separator my-5"></div>
                        @foreach ($properties as $item)
                            @php
                                $pName = "property[$item->id]";
                                if(in_array($item->property_type, [5,6,7,8,9,10,11])){
                                    $pName = "property[$item->id][]";
                                } elseif(in_array($item->property_type, [18, 20])){
                                    $pName = [
                                        "property[$item->id][start]",
                                        "property[$item->id][end]",
                                    ];
                                }

                                $propVal = $prop[$item->id] ?? null;
                                $value = $propVal->property_value ?? "";
                                $propArr = json_decode($propVal->property_value ?? "");
                                if(is_array($propArr)){
                                    $value = $propArr;
                                }
                            @endphp
                            @component("_crm.preferences.properties.preview._$item->property_type", [
                                "name" => $item->property_name,
                                "placeholder" => $item->property_placeholder,
                                "readonly" => "",
                                "form_name" => $pName,
                                "test" => "hello",
                                "value" => $value,
                                "contacts" => $contacts,
                                "clients" => $clients,
                                "additional" => [
                                    "option" => json_decode($item->additional ?? "[]")
                                ]
                            ])
                            @endcomponent
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-end">
                        @csrf
                        <button type="button" class="btn btn-white me-5" id='kt_drawer_example_basic_close'>Batal</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
