<div class="row row-cols-1 row-cols-md-2 g-1">
    @foreach ($job_ads as $item)
        <div class="cols p-3">
            <div class="card card-stretch">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="symbol me-5">
                            <img src="{{ asset($company->icon) }}" class="w-50px h-auto">
                        </div>
                        <div class="flex-fill d-flex flex-column">
                            <span class="fw-bold mb-3">{{ $item->position }}</span>
                            <div class="d-flex align-items-md-center flex-column flex-md-row">
                                <span class="me-5">
                                    <i class="fa fa-clock text-dark me-3"></i>
                                    {{ $item->job_type == 1 ? "Fulltime" : ($item->job_type == 2 ? "Freelance" : "Contract") }}
                                </span>
                                <span class="me-5">
                                    <i class="fa fa-suitcase text-dark me-3"></i>
                                    {{ $item->yoe }} years
                                </span>
                                <span class="me-5">
                                    <i class="fas fa-dollar-sign text-dark me-2"></i>
                                    @if ($item->show_salary == 1)
                                        {{ number_format($item->salary_min, 2, ",", ".") }}{{ !empty($item->salary_max) ? " - ".number_format($item->salary_max, 2, ",", ".") : '' }}
                                        /month
                                    @else
                                        Kompetitif Salary
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
