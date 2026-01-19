<?php

namespace App\Exports;

use App\Models\Hrd_employee_test;
use App\Models\Hrd_employee_test_result;
use App\Models\Master_city;
use App\Models\Master_province;
use App\Models\Master_religion;
use App\Models\User;
use App\Models\User_experience;
use App\Models\User_formal_education;
use App\Models\User_job_applicant;
use App\Models\User_job_vacancy;
use App\Models\User_medsos;
use App\Models\User_profile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class JobReportExport implements FromView, WithTitle, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $columns, $columnSelected, $jobIds, $type;

    public function __construct($columns, $columnSelected, $jobIds, $type) {
        $this->columns = $columns;
        $this->columnSelected = $columnSelected;
        $this->jobIds = $jobIds;
        $this->type = $type;
    }

    public function view(): View {
        $job = User_job_vacancy::whereIn("id",$this->jobIds)->get();

        $applicant = User_job_applicant::whereIn("job_id", $job->pluck("id"))->where("status", "<=", 3)->orderBy("created_at", "desc")->get();
        $type = $this->type;

        $users = User::where(function($q) use($applicant, $type){
            if($type != "bp"){
                $q->whereIn('id', $applicant->pluck("user_id"));
            }
        })->where("role_access", "like", '%"applicant"%')->whereNotNull("email_verified_at")->orderBy("name")->get();

        $exp = User_experience::whereIn("user_id", $users->pluck("id"))
            ->orderBy("start_date")
            ->get();
        $exp_list = [];
        foreach($exp as $item){
            $exp_list[$item->user_id][] = $item;
        }

        $edu = User_formal_education::whereIn("user_id", $users->pluck("id"))
            ->orderBy("start_date", "desc")
            ->get();
        $edu_list = [];
        foreach($edu as $item){
            $edu_list[$item->user_id][] = $item;
        }

        $testSel = Hrd_employee_test::get();

        $test = Hrd_employee_test_result::whereIn('user_id', $applicant->pluck("user_id"))
            ->whereIn('test_id', $testSel->pluck("id"))
            ->whereNotNull("result_detail")
            ->whereNotNull("result_point")
            ->where("result_point","<=", "100")
            ->orderBy("result_point", "desc")
            ->get();
        $test_list = [];
        foreach($test as $item){
            $test_list[$item->user_id][] = $item->result_point;
        }

        $province = Master_province::pluck("name", "id");
        $city = Master_city::pluck("name", "id");
        $religion = Master_religion::pluck("name", "id");

        foreach($users as $item){
            $uExp = $exp_list[$item->id] ?? [];
            $item->yoe = 0;
            $item->salary = 0;
            $item->exp = "";
            if(!empty($uExp)){
                $first = $uExp[0];
                $last = end($uExp);
                if(!empty($first) && ($first->start_date != "-" && !empty($first->start_date))){
                    $d1 = date_create($first->start_date);
                    $d2 = date_create(date("Y-m-d"));
                    $diff = date_diff($d1, $d2);
                    $item->yoe = $diff->format("%y");
                }

                $item->salary = $last->salary;
                $item->exp = $last->position;
            }

            $item->edu = "-";
            $uEdu = $edu_list[$item->id] ?? [];
            if(!empty($uEdu)){
                $first = $uEdu[0];
                $item->edu = $first->degree." ".$first->field_of_study;
            }

            $item->birth_date = $item->profile->birth_date ?? "-";
            $item->religion = $item->profile->religion ?? "-";
            $item->gender = $item->profile->gender ?? "-";
            $item->phone = $item->profile->phone ?? "-";
            $item->married = $item->profile->marital_status ?? "-";
            $item->province = $province[$item->profile->prov_id ?? null] ?? "-";
            $item->city = $city[$item->profile->city_id ?? null] ?? "-";
            $item->address = $item->profile->address ?? "-";
            $item->website = $item->medsos->website ??"-";
            $item->behance = $item->medsos->behance ??"-";
            $item->dribble = $item->medsos->dribble ??"-";
            $item->github = $item->medsos->github ??"-";
            $item->mobile = $item->medsos->mobile ??"-";
            $item->other_link = $item->medsos->other_link ??"-";
            $item->test_result = $test_list[$item->id] ?? [];
        }

        $data = [];

        foreach($applicant as $item){
            $col = [];
        }

        $test_name = $testSel->pluck("label", "id");
        $columns = $this->columns;
        $columnSelected = $this->columnSelected;
        return view("_employer.job_report.excel", compact("columns", "columnSelected", "users", "test_name"));
    }

    public function title() : string {
        return "Job Report - ".date("Y-m-d");
    }
}
