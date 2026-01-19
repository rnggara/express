<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class AppExport implements FromView, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $view, $title;

    public function __construct($view, $title) {
        $this->view = $view;
        $this->title = $title;
    }

    public function view() : View {
        return $this->view;
    }

    public function title() : string {
        return $this->title;
    }
}
