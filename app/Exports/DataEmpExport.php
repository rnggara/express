<?php

namespace App\Exports;

use App\Models\Division;
use App\Models\Hrd_employee;
use App\Models\Hrd_employee_history;
use App\Models\Hrd_employee_type;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataEmpExport implements FromView, WithTitle, ShouldAutoSize
{

    private $data;
    private $title;

    public function __construct($data, $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function view(): View {

        $data = $this->data;

        return view('_personel.employee_table.export', compact('data'));
    }

    public function title(): string
    {
        return $this->title;
    }
}
