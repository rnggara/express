<?php

namespace App\Exports;

use App\Models\ConfigCompany;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CompanyExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];

        $comp = ConfigCompany::whereIn('id', [17])->get();

        foreach($comp as $item){
            $sheets[] = new EmployeeExport($item);
        }

        return $sheets;
    }
}
