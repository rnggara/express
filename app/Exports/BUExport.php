<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class BUExport implements FromView, WithTitle, ShouldAutoSize
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
        return view('_crm.leads.exports.bu', $data);
    }

    public function title(): string
    {
        return $this->title;
    }
}
