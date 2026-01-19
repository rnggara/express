<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class OppTopExport implements FromView, ShouldAutoSize, WithTitle
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View {
        $data = $this->data;
        return view('_crm.leads.exports.top', $data);
    }

    public function title(): string
    {
        return "Topp Opp";
    }
}
