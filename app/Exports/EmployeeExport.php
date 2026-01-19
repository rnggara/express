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

class EmployeeExport implements FromView, WithTitle, ShouldAutoSize
{

    private $comp;

    public function __construct($comp)
    {
        $this->comp = $comp;
    }

    public function view(): View {
        $id = $this->comp->id;

        $emp = Hrd_employee::where("company_id", $id)
            ->whereNull("finalize_expel")
            // ->whereNull("freeze")
            ->orderBy("emp_name")
            ->get();
        $join_date = Hrd_employee_history::where("activity", "in")
            ->whereIn("emp_id", $emp->pluck("id"))
            ->get();

        $div = Division::whereIn("id", $emp->pluck("division"))->get();
        $type = Hrd_employee_type::whereIn("id", $emp->pluck("emp_type"))->get();

        return view('employee.export', compact('emp', 'join_date', 'div', 'type'));
    }

    public function title(): string
    {
        return $this->comp->company_name;
    }
}
