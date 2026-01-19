<?php

namespace App\Exports;

use App\Models\Action;
use App\Models\Module;
use App\Models\RoleDivision;
use App\Models\RolePrivilege;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PrivilegeExport implements FromView, WithTitle, ShouldAutoSize
{
    private $pos;

    public function __construct($pos)
    {
        $this->pos = $pos;
    }

    public function view(): View {
        $id = $this->pos->id;
        $rolePriv = $this->pos;

        $privs = RolePrivilege::select('id_rms_modules', 'id_rms_actions')->where("id_rms_roles_divisions", $id)->get();

		$moduleList = Module::orderBy('name')->get();
        $moduleName = $moduleList->pluck("name", "id");
        $moduleDesc = $moduleList->pluck("desc", "id");

		$actionList = Action::all();
        $actionName = $actionList->pluck('name', 'id');
        $actionDesc = $actionList->pluck('desc', 'id');
        return view('position.excel', compact('rolePriv', 'moduleList', 'privs', 'actionList','moduleName','moduleDesc','actionName','actionDesc'));
    }

    public function title(): string
    {
        return $this->pos->name;
    }
}
