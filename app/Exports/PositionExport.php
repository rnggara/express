<?php

namespace App\Exports;

use App\Models\RoleDivision;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PositionExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];

        $pos = RoleDivision::get();

        foreach($pos as $item){
            $sheets[] = new PrivilegeExport($item);
        }

        return $sheets;
    }
}
