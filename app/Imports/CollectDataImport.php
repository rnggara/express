<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CollectDataImport implements ToCollection, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $row = [];
        foreach($collection as $item){
            if(!empty($item[0])){
                $row[] = $item;
            }
        }

        return $row;
    }
}
