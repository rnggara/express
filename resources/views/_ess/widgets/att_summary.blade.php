<div class="d-flex justify-content-between flex-column flex-md-row gap-3">
    <div class="d-flex flex-column gap-1">
        <span class="text-muted">Persentase Kehadiran (%)</span>
        <span class="fs-1 fw-bold text-primary">{{ $pctg_att ?? 0 }}%</span>
    </div>
    <div class="d-flex flex-column gap-1">
        <span class="text-muted">Persentase tidak hadir (%)</span>
        <span class="fs-1 fw-bold text-primary">{{ $pctg_not_att ?? 0 }}%</span>
    </div>
    <div class="d-flex flex-column gap-1">
        <span class="text-muted">Hadir (hari)</span>
        <span class="fs-1 fw-bold text-primary">{{ $att_day ?? 0 }}</span>
    </div>
    <div class="d-flex flex-column gap-1">
        <span class="text-muted">Tidak hadir (hari)</span>
        <span class="fs-1 fw-bold text-primary">{{ $not_att_day ?? 0 }}</span>
    </div>
    <div class="d-flex flex-column gap-1">
        <span class="text-muted">Lembur (Jam)</span>
        <span class="fs-1 fw-bold text-primary">{{ $ovt ?? 0 }}</span>
    </div>
    <div class="d-flex flex-column gap-1">
        <span class="text-muted">Telat (Jam)</span>
        <span class="fs-1 fw-bold text-primary">{{ $late ?? 0 }}</span>
    </div>
</div>
