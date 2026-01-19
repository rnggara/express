<div class="d-flex flex-column gap-5">
    <div class="d-flex align-items-center justify-content-between">
        <span class="fs-3 fw-bold">{{ date("F Y", strtotime($dt)) }}</span>
        <button type="button" class="btn btn-primary btn-sm" onclick="printIframe('printPayroll')">
            <i class="fi fi-rr-download"></i>
            Unduh Slip Gaji
        </button>
    </div>
    <div class="d-flex w-100 align-items-center justify-content-center">
        <iframe src="{{ $url }}" name="printPayroll" id="printPayroll" class="w-50 rounded" onload="resizeIframe(this)" style="min-height: 779px" frameborder="0"></iframe>
    </div>
</div>

