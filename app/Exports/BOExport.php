<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class BOExport implements WithMultipleSheets
{

    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new OppMatExport($this->data);
        $sheets[] = new OppFunExport($this->data);
        $sheets[] = new OppTopExport($this->data);

        return $sheets;
    }
}
