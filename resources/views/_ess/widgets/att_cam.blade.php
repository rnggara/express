<div class="modal-body">
    <div class="d-flex flex-column gap-3">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-icon btn-sm" onclick="resetWebcam()" data-bs-dismiss="modal">
                <i class="fi fi-rr-x fs-5"></i>
            </button>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-5 align-items-center">
                <div class="symbol symbol-40px">
                    <div class="symbol-label bg-light-primary">
                        <i class="fi fi-sr-clock-eight-thirty fs-3 mt-2 text-primary"></i>
                    </div>
                </div>
                <span class="fs-3 fw-bold">Ambil Absen</span>
            </div>
            <div class="rounded bg-light-primary p-3 d-flex align-items-center gap-3">
                <span class="fi fi-sr-marker text-primary fs-3"></span>
                <div class="d-flex flex-column">
                    <span class="fw-bold">Office</span>
                    <span>{{ Auth::user()->uaclocation->name ?? "-" }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center flex-column">
            <div id="camera" style="height:auto;width:auto; text-align:left;"></div>
            <input type="hidden" name="__ss" id="file__">
            <div class="bgi-size-contain bgi-no-repeat" id="snapShot"></div>
            <button type="button" onclick="takeSnapShot()" id="btnSnap" class="btn btn-primary btn-icon btn-circle">
                <i class="fi fi-sr-camera"></i>
            </button>
            <div class="d-none d-flex flex-column gap-3 w-100" id="uploadDiv">
                <button type="button" class="btn btn-primary" id="btnUp" onclick="take_attendance('upload')">
                    {{ ucwords(str_replace("_", " ", Session::get('att_state'))) }}
                </button>
                <button type="button" id="btnClose" onclick="takeSnapAgain()" class="btn text-primary">
                    Take another photo
                </button>
            </div>
        </div>
    </div>
</div>