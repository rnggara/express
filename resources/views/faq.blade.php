@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="d-flex flex-column align-items-center">
            <span class="mb-3 text-primary fw-bold">FAQs</span>
            <span class="fs-1 mb-3 fw-bold">Adakah hal yang ingin kamu tanya?</span>
            <span class="mb-3">Inilah pertanyaan-pertanyaan yang sering diajukan oleh user kami</span>
            <div class="d-flex justify-content-center">
                <ul class="nav mb-5 fs-6">
                    <li class="nav-item me-3">
                        <a class="active bg-active-primary bg-hover-primary btn btn-outline nav-link text-active-white text-hover-white text-dark" data-bs-toggle="tab" href="#kt_tab_pane_1">Pencari Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="bg-active-primary bg-hover-primary btn btn-outline nav-link text-active-white text-hover-white text-dark" data-bs-toggle="tab" href="#kt_tab_pane_2">Pemberi Kerja</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content container" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Memiliki masalah untuk login?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse1" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse1" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_1">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Bagaimana caranya saya untuk mengubah kata sandi?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse2" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse2" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_1">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Siapakah yang melihat resume saya?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse3" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse3" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_1">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Bagaimana caranya untuk mendaftar di karir.com?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse4" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse4" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_1">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Mengapa saya tidak mendapat respon setelah apply secara online?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse5" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse5" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_1">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Bagaimana caranya agar peluang saya untuk direkrut menjadi lebih besar?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse6" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse6" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_1">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Bagaimana caranya agar peluang saya untuk direkrut menjadi lebih besar?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse21" data-bs-toggle="collapse" data-bs-target="#collapse21" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse21" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_2">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Apa manfaat mengikuti semua test yang ada di portal kerjaku</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse22" data-bs-toggle="collapse" data-bs-target="#collapse22" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse22" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_2">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Kenapa saya tidak menerima informasi status lamaran saya</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse23" data-bs-toggle="collapse" data-bs-target="#collapse23" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse23" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_2">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">BMengapa saya tidak mendapat respon setelah apply secara online?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse24" data-bs-toggle="collapse" data-bs-target="#collapse24" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse24" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_2">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Apakah boleh memint ajadwal ulang interview?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse25" data-bs-toggle="collapse" data-bs-target="#collapse25" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse25" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_2">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-5 bg-white">
                        <div class="d-flex flex-column border rounded">
                            <div class="d-flex justify-content-between accordion accordion-icon-collapse p-3 align-items-center">
                                <span class="fw-bold">Bgaiaman cara merubah email saya?</span>
                                <div class="d-flex justify-content-between border-0">
                                    <div class="accordion-item border-0 bg-hover-secondary p-3 rounded">
                                        <div class="accordion-header collapsed" aria-controls="collapse26" data-bs-toggle="collapse" data-bs-target="#collapse26" aria-expanded="true">
                                            <span class="accordion-icon">
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-dark accordion-icon-off btn-circle">
                                                    <i class="fa fa-plus fs-3 accordion-icon-off text-dark"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-outline btn-icon btn-sm btn-outline-primary accordion-icon-on btn-circle">
                                                    <i class="fa fa-minus fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse26" class="border-top accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_tab_pane_2">
                                <div class="d-flex flex-column p-5">
                                    <span>Lorem ipsum dolor sit amet consectetur. Vulputate urna phasellus porttitor nec erat egestas. Dui ut egestas faucibus aenean leo diam amet quis ipsum. Auctor varius sollicitudin vel eleifend at amet sem. Cursus in tortor quis lorem. Eu massa facilisi nisl duis in non vestibulum curabitur. Nulla vitae faucibus suspendisse id ipsum sed rhoncus. </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection