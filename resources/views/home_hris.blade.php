@extends('layouts.templateCrm', ["hideMenu" => true,"withoutFooter" => true, "bgWrapper" => "bg-white", 'style' => ['border' => 'border-bottom', 'box-shadow' => 'none']])

@section('content')
    <div class="card card-px-0 shadow-none">
        <div class="card-header border-0">
            <div class="card-title">
                <div class="d-flex flex-column">
                    <span class="mb-5 fs-2tx">Apa yang akan anda lakukan hari ini?</span>
                    <span class="fs-base">Banyak hal yang terjadi sejak terakhir anda disini</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="row row-cols-1 row-cols-md-5">
                        @if (!empty(\Auth::user()->emp_id))
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url='{{ "https://intranet.kerjaku.cloud" }}'>
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-success w-100 h-150px">
                                        <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.5707 11.8281L17.6423 14.8997C19.0027 16.2601 21.0035 16.7499 22.8343 16.1596C23.8317 15.8379 24.8955 15.6641 26.0001 15.6641C27.1048 15.6641 28.1686 15.8379 29.166 16.1596C30.9968 16.7499 32.9979 16.2601 34.358 14.8997L39.0674 10.1905C39.7737 11.2913 40.7065 12.2239 41.807 12.9301L37.0976 17.6393C35.7372 18.9997 35.2474 21.0005 35.8377 22.8315C36.1594 23.829 36.3332 24.8928 36.3332 25.9974C36.3332 27.102 36.1591 28.1659 35.8377 29.1633C35.2472 30.9941 35.7372 32.9951 37.0976 34.3553L41.807 39.0647C40.7063 39.771 39.7737 40.7038 39.0674 41.8043L34.358 37.0949C32.9976 35.7345 30.9968 35.2447 29.1658 35.835C28.1686 36.1566 27.1048 36.3307 26.0004 36.3307C24.0838 36.3307 22.298 35.8009 20.766 34.8856C19.099 33.8895 17.0254 33.8908 15.3788 34.9202L13.8184 35.8957C13.3741 34.6521 12.6916 33.5237 11.7853 32.5983L13.3121 31.6441C14.9272 30.6345 15.8931 28.8184 15.7115 26.9222C15.6823 26.6182 15.6671 26.3097 15.6671 25.9977C15.6671 24.893 15.8412 23.8292 16.1625 22.8318C16.7531 21.0007 16.263 18.9999 14.9027 17.6396L11.8311 14.568C10.2206 15.5176 8.26943 15.9537 6.15885 15.4569C3.17846 14.7555 0.779316 12.2636 0.277633 9.2432C-0.605609 3.92644 3.92917 -0.60834 9.24593 0.274643C12.2666 0.776326 14.7582 3.17547 15.4596 6.15586C15.9564 8.26644 15.5203 10.2176 14.5707 11.8281Z" fill="#28A845"/>
                                            <path opacity="0.3" d="M51.8337 46.6641C51.8337 49.5176 49.5205 51.8307 46.667 51.8307C43.8134 51.8307 41.5003 49.5176 41.5003 46.6641C41.5003 43.8105 43.8134 41.4974 46.667 41.4974C49.5205 41.4974 51.8337 43.8105 51.8337 46.6641ZM5.33366 44.0807C8.18721 44.0807 10.5003 41.7676 10.5003 38.9141C10.5003 36.0605 8.18721 33.7474 5.33366 33.7474C2.48011 33.7474 0.166992 36.0605 0.166992 38.9141C0.166992 41.7676 2.48011 44.0807 5.33366 44.0807ZM46.667 0.164062C43.8134 0.164062 41.5003 2.47718 41.5003 5.33073C41.5003 8.18428 43.8134 10.4974 46.667 10.4974C49.5205 10.4974 51.8337 8.18428 51.8337 5.33073C51.8337 2.47718 49.5205 0.164062 46.667 0.164062Z" fill="#28A845"/>
                                        </svg>                                          
                                    </div>
                                </div>
                                <span>Intranet</span>
                            </div>
                        </div>
                        @hasPermission("ess", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url="https://ess.kerjaku.cloud/">
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-danger w-100 h-150px">
                                        <svg width="62" height="62" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M54.25 18.0833C54.25 20.9369 51.9369 23.25 49.0833 23.25H43.9166C41.0631 23.25 38.75 20.9369 38.75 18.0833V12.9167C38.75 10.0631 41.0631 7.75 43.9166 7.75H49.0833C51.9369 7.75 54.25 10.0631 54.25 12.9167V18.0833ZM33.5833 49.0833C33.5833 51.9369 35.8964 54.25 38.75 54.25H43.9166C46.7702 54.25 49.0833 51.9369 49.0833 49.0833V43.9167C49.0833 41.0631 46.7702 38.75 43.9166 38.75H38.75C35.8964 38.75 33.5833 41.0631 33.5833 43.9167V49.0833Z" fill="#EF5DA8"/>
                                            <path d="M7.75 12.9167C7.75 10.0631 10.0631 7.75 12.9167 7.75H18.0833C20.9369 7.75 23.25 10.0631 23.25 12.9167V13.5625H34.875V17.4375H23.25V18.0833C23.25 19.0898 22.9503 20.0213 22.4525 20.8157L34.9086 35.7637C33.7177 36.3276 32.6856 37.1465 31.8484 38.146L19.3022 23.0898C18.9095 23.1852 18.5055 23.25 18.0836 23.25H12.9169C10.0634 23.25 7.75026 20.9369 7.75026 18.0833L7.75 12.9167Z" fill="#EF5DA8"/>
                                            </svg>
                                    </div>
                                </div>
                                <span>ESS</span>
                            </div>
                        </div>
                        @endPermission
                        @endif
                        @hasPermission("personel", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url='{{ route("personel.index") }}'>
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-warning w-100 h-150px">
                                        <svg width="62" height="62" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-center mx-auto"><path d="M33.05 5.166 54.562 36.74a2.611 2.611 0 0 1-.53 3.51L33.05 56.832V5.166z" fill="#FFC119"></path><path opacity=".3" d="M6.983 40.25a2.612 2.612 0 0 1-.53-3.51L27.966 5.166v51.667L6.983 40.249z" fill="#FFC119"></path></svg>
                                    </div>
                                </div>
                                <span>Personal</span>
                            </div>
                        </div>
                        @endPermission
                        @hasPermission("attendance", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url='{{ route("attendance.index") }}'>
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-danger w-100 h-150px">
                                        <svg width="62" height="62" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-center mx-auto"><path d="M23.25 36.167V25.833a2.583 2.583 0 0 1 2.583-2.583h10.334a2.583 2.583 0 0 1 2.583 2.583v10.334a2.583 2.583 0 0 1-2.583 2.583H25.833a2.583 2.583 0 0 1-2.583-2.583z" fill="#DD3545"></path><path opacity=".3" d="M31 49.083v-5.166h5.167c4.273 0 7.75-3.477 7.75-7.75V31h5.166a5.167 5.167 0 0 1 5.167 5.167v12.916a5.167 5.167 0 0 1-5.167 5.167H36.167A5.167 5.167 0 0 1 31 49.083zM7.75 12.917v12.916A5.167 5.167 0 0 0 12.917 31h5.166v-5.167c0-4.273 3.477-7.75 7.75-7.75H31v-5.166a5.167 5.167 0 0 0-5.167-5.167H12.917a5.167 5.167 0 0 0-5.167 5.167z" fill="#DD3545"></path></svg>
                                    </div>
                                </div>
                                <span>Kehadiran</span>
                            </div>
                        </div>
                        @endPermission
                        @hasPermission("attendance", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url='{{ route("emp.mt.index") }}'>
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-success w-100 h-150px">
                                        <svg width="62" height="62" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-center mx-auto"><path d="M23.25 36.167V25.833a2.583 2.583 0 0 1 2.583-2.583h10.334a2.583 2.583 0 0 1 2.583 2.583v10.334a2.583 2.583 0 0 1-2.583 2.583H25.833a2.583 2.583 0 0 1-2.583-2.583z" fill="#28A845"></path><path opacity=".3" d="M31 49.083v-5.166h5.167c4.273 0 7.75-3.477 7.75-7.75V31h5.166a5.167 5.167 0 0 1 5.167 5.167v12.916a5.167 5.167 0 0 1-5.167 5.167H36.167A5.167 5.167 0 0 1 31 49.083zM7.75 12.917v12.916A5.167 5.167 0 0 0 12.917 31h5.166v-5.167c0-4.273 3.477-7.75 7.75-7.75H31v-5.166a5.167 5.167 0 0 0-5.167-5.167H12.917a5.167 5.167 0 0 0-5.167 5.167z" fill="#28A845"></path></svg>
                                    </div>
                                </div>
                                <span>Kehadiran Mobile</span>
                            </div>
                        </div>
                        @endPermission
                        {{-- @hasPermission("personel", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url='{{ route("employee.index") }}'>
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-warning w-100 h-150px">
                                        <svg width="62" height="62" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-center mx-auto"><path d="M33.05 5.166 54.562 36.74a2.611 2.611 0 0 1-.53 3.51L33.05 56.832V5.166z" fill="#FFC119"></path><path opacity=".3" d="M6.983 40.25a2.612 2.612 0 0 1-.53-3.51L27.966 5.166v51.667L6.983 40.249z" fill="#FFC119"></path></svg>
                                    </div>
                                </div>
                                <span>Personal Mobile</span>
                            </div>
                        </div>
                        @endPermission --}}
                        @hasPermission("crm", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url='{{ route("crm.index") }}'>
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-primary w-100 h-150px">
                                        <svg width="62" height="62" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-center mx-auto"><path opacity=".3" d="M56.188 18.084c0 1.07-.868 1.938-1.938 1.938H33.584a1.938 1.938 0 0 1 0-3.875H54.25c1.07 0 1.938.867 1.938 1.937zM54.25 41.98H33.584a1.938 1.938 0 0 0 0 3.875H54.25a1.938 1.938 0 0 0 0-3.875z" fill="#6235C5"></path><path d="M18.083 34.23h-5.166a7.111 7.111 0 0 0-7.104 7.104v5.167a7.111 7.111 0 0 0 7.104 7.104h5.166a7.111 7.111 0 0 0 7.105-7.104v-5.167a7.111 7.111 0 0 0-7.105-7.104zm3.23 12.27a3.232 3.232 0 0 1-3.23 3.23h-5.166a3.232 3.232 0 0 1-3.23-3.23v-5.166a3.232 3.232 0 0 1 3.23-3.23h5.166a3.232 3.232 0 0 1 3.23 3.23v5.167zm3.875-31a7.111 7.111 0 0 0-7.105-7.104h-5.166a7.111 7.111 0 0 0-7.104 7.105v5.166a7.111 7.111 0 0 0 7.104 7.104h5.166a7.111 7.111 0 0 0 7.105-7.104v-5.166zm-3.875 5.167a3.232 3.232 0 0 1-3.23 3.23h-5.166a3.232 3.232 0 0 1-3.23-3.23v-5.166a3.232 3.232 0 0 1 3.23-3.23h5.166a3.232 3.232 0 0 1 3.23 3.23v5.166z" fill="#6235C5"></path></svg>
                                    </div>
                                </div>
                                <span>CRM</span>
                            </div>
                        </div>
                        @endPermission
                        @hasPermission("lms", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url="https://lms.kerjaku.cloud/">
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-success w-100 h-150px">
                                        <svg width="62" height="62" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-center mx-auto"><path d="M44.63 28.233 25.772 47.089c.012-.198.06-.39.06-.589V25.108l7.836-7.835a5.167 5.167 0 0 1 7.307 0l3.653 3.653a5.167 5.167 0 0 1 0 7.307zM23.25 46.5a7.75 7.75 0 0 1-8.166 7.739c-4.191-.22-7.334-3.981-7.334-8.178V12.917a5.167 5.167 0 0 1 5.167-5.167h5.167a5.167 5.167 0 0 1 5.166 5.167V46.5zm-5.166 0a2.583 2.583 0 1 0-5.167 0 2.583 2.583 0 0 0 5.167 0zm1.291-14.854h-7.75v3.875h7.75v-3.875zm0-10.334h-7.75v3.875h7.75v-3.875z" fill="#28A845"></path><path opacity=".3" d="M54.25 43.917v5.166a5.167 5.167 0 0 1-5.166 5.167H22.29c.96 0 15.476-15.5 15.476-15.5h11.318a5.167 5.167 0 0 1 5.166 5.167z" fill="#28A845"></path></svg>
                                    </div>
                                </div>
                                <span>LMS</span>
                            </div>
                        </div>
                        @endPermission
                        @hasPermission("recruitment", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" onclick="login_portal()">
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-primary w-100 h-150px">
                                        <svg width="62" height="62" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-center mx-auto"><path opacity=".3" d="M56.581 52.128c.784 1.568-.356 3.413-2.108 3.413H38.527c-1.752 0-2.892-1.845-2.108-3.413l7.973-15.95c.868-1.738 3.348-1.738 4.216 0l7.973 15.95zM6.711 8.578l7.972 15.95c.869 1.74 3.348 1.74 4.217 0l7.973-15.95c.783-1.567-.356-3.412-2.109-3.412H8.82c-1.753 0-2.893 1.845-2.109 3.413z" fill="#6235C5"></path><path d="M28.416 45.208c0 6.42-5.204 11.625-11.625 11.625-6.42 0-11.625-5.205-11.625-11.625s5.205-11.625 11.625-11.625 11.625 5.204 11.625 11.625zm23.25-40.042H41.334a5.167 5.167 0 0 0-5.166 5.167v10.333a5.167 5.167 0 0 0 5.166 5.167h10.334a5.167 5.167 0 0 0 5.166-5.167V10.333a5.167 5.167 0 0 0-5.166-5.167z" fill="#6235C5"></path></svg>
                                    </div>
                                </div>
                                <span>Job Portal</span>
                            </div>
                        </div>
                        @endPermission                        
                        @hasPermission("company_setting", "view")
                        <div class="col mb-5">
                            <div class="d-flex flex-column align-items-center hover-scale" data-toggle="nav" data-url='{{ route("company.detail", base64_encode(Session::get('company_id'))) }}'>
                                <div class="symbol mb-5 w-100">
                                    <div class="symbol-label bg-light-dark w-100 h-150px">
                                        <svg width="62" height="62" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-center mx-auto"><g clip-path="url(#a)"><path d="M36.176 40.364 31.847 39.3a13.963 13.963 0 0 0-.91-2.211l2.211-3.686c.26-.433.195-.975-.152-1.322l-3.078-3.079a1.078 1.078 0 0 0-1.322-.151l-3.686 2.211a13.963 13.963 0 0 0-2.211-.91l-1.041-4.33A1.112 1.112 0 0 0 20.596 25H16.26c-.499 0-.933.347-1.063.824l-1.04 4.328c-.759.239-1.496.543-2.212.911L8.26 28.852a1.078 1.078 0 0 0-1.322.151l-3.079 3.079a1.079 1.079 0 0 0-.152 1.322L5.92 37.09a13.965 13.965 0 0 0-.911 2.211L.824 40.364A1.068 1.068 0 0 0 0 41.404v4.336c0 .499.347.932.824 1.04l4.184 1.063c.238.759.542 1.496.91 2.211l-2.21 3.686c-.26.434-.196.976.151 1.322l3.079 3.079c.346.347.888.412 1.322.152l3.685-2.212c.716.369 1.453.672 2.212.911l1.04 4.184c.13.477.564.824 1.063.824h4.336c.498 0 .932-.347 1.062-.824l1.04-4.184a13.963 13.963 0 0 0 2.212-.91l3.686 2.21c.433.26.975.196 1.322-.151l3.078-3.078a1.08 1.08 0 0 0 .152-1.323l-2.211-3.686c.368-.715.672-1.452.91-2.21l4.33-1.063c.476-.108.823-.542.823-1.04v-4.337c0-.498-.347-.932-.824-1.04zm-17.748 8.628a5.422 5.422 0 0 1-5.42-5.42 5.422 5.422 0 0 1 5.42-5.42 5.422 5.422 0 0 1 5.42 5.42 5.422 5.422 0 0 1-5.42 5.42z" fill="#232323"></path></g><g opacity=".5" clip-path="url(#b)"><path d="m61.332 12.457-3.51-.861a11.328 11.328 0 0 0-.738-1.793l1.793-2.989a.874.874 0 0 0-.123-1.072l-2.496-2.496a.874.874 0 0 0-1.073-.123l-2.988 1.793a11.323 11.323 0 0 0-1.793-.738L49.56.668A.902.902 0 0 0 48.7 0h-3.516a.902.902 0 0 0-.862.668l-.844 3.51c-.615.193-1.212.44-1.793.738l-2.988-1.793a.874.874 0 0 0-1.072.123l-2.496 2.496a.874.874 0 0 0-.123 1.072l1.793 2.989c-.299.58-.545 1.177-.739 1.793l-3.392.861a.866.866 0 0 0-.668.844v3.515c0 .405.281.756.668.844l3.392.861c.194.616.44 1.213.739 1.793l-1.793 2.989a.874.874 0 0 0 .123 1.072l2.496 2.496a.874.874 0 0 0 1.072.123l2.988-1.793c.58.299 1.178.545 1.793.738l.844 3.393a.902.902 0 0 0 .862.668h3.515a.902.902 0 0 0 .861-.668l.844-3.393a11.33 11.33 0 0 0 1.793-.738l2.988 1.793a.874.874 0 0 0 1.073-.123l2.496-2.496a.874.874 0 0 0 .123-1.072l-1.793-2.989c.299-.58.545-1.177.738-1.793l3.51-.86a.866.866 0 0 0 .668-.845v-3.515a.866.866 0 0 0-.668-.844zm-14.39 6.996a4.396 4.396 0 0 1-4.395-4.394 4.396 4.396 0 0 1 4.394-4.395 4.396 4.396 0 0 1 4.395 4.395 4.396 4.396 0 0 1-4.395 4.394z" fill="#232323"></path></g><defs><clipPath id="a"><path fill="#fff" transform="translate(0 25)" d="M0 0h37v37H0z"></path></clipPath><clipPath id="b"><path fill="#fff" transform="translate(32)" d="M0 0h30v30H0z"></path></clipPath></defs></svg>
                                    </div>
                                </div>
                                <span>Pengaturan</span>
                            </div>
                        </div>
                        @endPermission
                    </div>
                </div>
                <div class="d-none d-md-inline col-md-3">
                    <div class="bg-secondary-crm d-flex flex-column px-5 py-7 rounded">
                        <span class="fs-3 mb-3 fw-bold">Solusi Anda</span>
                        <span class="mb-5">Anda memakai solusi gratis.</span>
                        <button type="button" class="btn btn-primary mb-5">Tingkatkan Solusi</button>
                        <span class="fs-3 mb-3 fw-bold">Perusahaan Aktif</span>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-100px me-5">
                                <img src="{{ asset(stripos(Session::get("company_app_logo"), "attachment") !== false ? Session::get("company_app_logo") : "images/".(Session::get("company_app_logo") == "" ? "image_placeholder.png" : Session::get("company_app_logo"))) }}" class="h-50px w-auto" alt="">
                            </div>
                            <span>{{ Session::get('company_name_parent') }}</span>
                        </div>
                        @hasPermission("company_setting", "view")
                        <div class="d-flex justify-content-end">
                            <a href="{{ route("crm.pref.index") }}?state=intranet" class="btn btn-primary btn-sm">
                                Pengaturan Perusahaan
                            </a>
                        </div>
                        @endPermission
                        <hr>
                        @if (count($others) > 0)
                            <span class="fs-3 mb-3 fw-bold">Perusahaan Lainnya</span>
                            @foreach ($others as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="symbol symbol-100px me-5">
                                        <img src="{{ asset(stripos($item->app_logo, "attachment") !== false ? $item->app_logo : "images/".($item->app_logo == "" ? "image_placeholder.png" : $item->app_logo)) }}" class="h-30px w-auto" alt="">
                                    </div>
                                    <span class="fs-7">{{ $item->company_name }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        $("[data-toggle=nav]").click(function(){
            var url = $(this).data("url")
            location.href = url
        })
    </script>
@endsection
