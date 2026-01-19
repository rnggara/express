<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User_job_vacancy;
use App\Models\User_job_applicant;
use App\Models\User_interview_group;
use App\Models\User;
use App\Models\User_experience;
use App\Models\User_formal_education;
use App\Models\User_informal_education;
use App\Models\User_language_skill;
use App\Models\User_skill;
use App\Models\User_portofolio;
use App\Models\User_medsos;
use App\Models\User_add_family;
use App\Models\User_add_id_card;
use App\Models\User_add_license;
use App\Models\Hrd_employee_test;
use App\Models\Hrd_employee_test_result;
use App\Models\User_profile;
use App\Models\Master_city;
use App\Models\Master_province;
use App\Models\Master_language;
use App\Models\Master_proficiency;
use App\Models\Master_company;
use App\Models\User_job_interview;
use App\Mail\EmailNotification;
use Illuminate\Support\Facades\Mail;

class KjkCalendarEmployer extends Controller
{

    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function index(Request $request){

        $job_sel = $request->job;

        $jobCollabs = User_job_vacancy::where("collaborators", "like", '%"'.Auth::id().'"%')->get();

        $job_ads = User_job_vacancy::where("user_id", Auth::id())
            ->orWhereIn("id", $jobCollabs->pluck("id"))
            ->get();

        $job_pos = $job_ads->pluck("position", "id");

        $user_name = User::pluck("name", "id");
        $user_img = User::pluck("user_img", "id");

        $_users = User::where("comp_id", Auth::user()->comp_id)
            ->get();

        $job_id = $job_ads->pluck('id');
        if(!empty($job_sel)){
            $job_id = [$job_sel];
        }

        $kandidat = User_job_applicant::whereIn("job_id", $job_id)
            ->whereNull("group_code")
            ->whereNull("need_reschedule")
            ->where("status", 2)
            ->orderBy("created_at")
            ->get();

        $kandidatGroup = User_interview_group::where("user_id", Auth::id())
            ->where("status", 2)
            ->get();

        $data_kandidat = [];
        foreach($kandidat as $item){
            $pos = $job_pos[$item->job_id] ?? "";
            $img = $user_img[$item->user_id] ?? null;

            $int = $item->interview;
            $isRescheduled = false;
            $date = date("Y-m-d H:i:s", strtotime($item->backlog_at));
            if(!empty($int)){
                if(!empty($int->reschedule_confirm_at)){
                    $isRescheduled = true;
                    $date = $int->reschedule_confirm_at;
                }
            }

            $col['id'] = $item->id;
            $col['img'] = $img;
            $col['type'] = "n";
            $col['pos'] = $pos;
            $col['name'] = $user_name[$item->user_id] ?? "-";
            $col['date'] = $date;
            $col['rescheduled'] = $isRescheduled;
            $col['status'] = $item->status;
            $col['rejected_by_applicant'] = $item->rejected_by_applicant ? true : false;
            if(isset($user_name[$item->user_id])) $data_kandidat[] =  $col;
        }

        foreach($kandidatGroup as $item){
            $pos = $job_pos[$item->job_id] ?? "-";
            $col = [];
            $col['id'] = $item->id;
            $col['img'] = null;
            $col['type'] = "m";
            $col['pos'] = $pos;
            $col['name'] = "Interview Group";
            $col['date'] = date("Y-m-d H:i:s", strtotime($item->created_at));
            $col['rescheduled'] = false;
            $col['rejected_by_applicant'] = false;
            $data_kandidat[] = $col;
        }

        $kandidatReschedule = User_job_applicant::whereIn("job_id", $job_id)
            ->where("need_reschedule", 1)
            ->orderBy("created_at")
            ->get();

        $data_reschedule = [];
        foreach($kandidatReschedule as $item){
            $pos = $job_pos[$item->job_id] ?? "";
            $img = $user_img[$item->user_id] ?? null;
            $col['id'] = $item->id;
            $col['img'] = $img;
            $col['type'] = "n";
            $col['pos'] = $pos;
            $col['name'] = $user_name[$item->user_id] ?? "-";
            $col['date'] = date("Y-m-d H:i:s", strtotime($item->backlog_at));
            $col['status'] = $item->status;
            $data_reschedule[] = $col;
        }


        if(count($data_kandidat) > 0){
            usort($data_kandidat, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }

        $hours = [];
        for($i = 1; $i <= 24; $i++){
            $h = sprintf("%02d", $i).":00";
            $hours[] = $h;
        }

        $days = ["Senin", "Selasa", "Rabu", "Kamis", "Jum\'at", "Sabtu", "Minggu"];

        $monday = date("Y-m-d", strtotime("monday this week"));
        $sunday = date("Y-m-d", strtotime("sunday this week"));
        $d1 = $monday;
        $dates = [];
        while(strtotime($d1) <= strtotime($sunday)){
            $N = date("N", strtotime($d1));
            $dates[$N]['date'] = $d1;
            $dates[$N]['day'] = $days[$N - 1];
            $d1 = date("Y-m-d", strtotime("+1day $d1"));
        }

        if($request->a){
            if($request->a == "modal"){

                $mKandidat = [];
                if($request->type == "n"){
                    $mKandidat = User_job_applicant::where("id", $request->id)->get();
                } else {
                    $mGroup = User_interview_group::find($request->id);
                    if(!empty($mGroup)){
                        $mKandidat = User_job_applicant::where("group_code", $mGroup->group_code)->where("job_id", $mGroup->job_id)->get();
                    }
                }

                $start_time = date("H:i", strtotime($request->date));
                $end_time = date("H:i", strtotime($request->date." +1 hour"));
                $d = date("N", strtotime($request->date));
                $date_time = $days[$d-1].",". date("d F y", strtotime($request->date));

                $id = $request->id;
                $type = $request->type;

                $e = $request->e ?? null;

                $view = view("_employer.calendar.modal", compact("user_name", "user_img", "mKandidat", "hours", "dates", "_users", "start_time", "end_time", "id", "type", "date_time", "e"));

                return json_encode([
                    "view" => $view->render(),
                ]);
            }
        }

        $all_applicant = User_job_applicant::whereIn("job_id", $job_id)
            ->where("status", ">=", 2)
            ->with("interview")
            ->orderBy("status", "desc")
            ->get();

        return view("_employer.calendar.index", compact("job_ads", "data_kandidat", "hours", "dates", "_users", "all_applicant", "user_name", "user_img", "job_pos", "job_sel", "data_reschedule"));
    }

    function add(Request $request){

        $id_month = [1=>"Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $expdate = explode(", ", $request->date);
        $date = explode(" ", $expdate[1]);
        $y = end($date);
        $d = sprintf("%02d", $date[0]);
        $m = sprintf("%02d", array_keys($id_month, $date[1])[0]);
        $dateTime = "$y-$m-$d";
        $start = date("H:i:s", strtotime($request->start_time." +1 minutes"));
        $end = $request->end_time;

        $conflict = User_job_interview::where("int_date", $dateTime)
            ->whereNull("reschedule_confirm_at")
            ->where(function($q) use($start, $end){
                $q->whereBetween("int_start", [$start, $end]);
                $q->orWhereBetween("int_end", [$start, $end]);
            })->first();
        if(!empty($conflict)){
            return redirect()->back()->withErrors(["conflict" => "Jadwal konflik"]);
        }

        $job_applicant = User_job_vacancy::pluck("position", "id");

        $user_email = User::pluck("email", "id");
        $user_name = User::pluck("name", "id");

        $tp = $request->type;
        if($tp == "n"){
            $applicants = User_job_applicant::where('id', $request->id)->get();
        } else {
            $group = User_interview_group::find($request->id);
            if(!empty($group)){
                $applicants = User_job_applicant::where("group_code", $group->group_code)
                    ->where("job_id", $group->job_id)
                    ->get();
                $group->interview_date = $dateTime;
                $group->interview_start = $request->start_time;
                $group->interview_end = $request->end_time;
                $group->status = 3;
                $group->save();
            }
        }

        $e = $request->e ?? null;

        foreach($applicants as $item){

            $interview = User_job_interview::where("job_app_id", $item->id)
                ->whereNotNull("reschedule_confirm_at")
                ->first();
            if(empty($interview) || (!empty($interview) && $e == "new")){
                $interview = new User_job_interview();
                $interview->job_app_id = $item->id;
                $interview->group_code = $item->group_code;
            }

            $interview->int_date = $dateTime;
            $interview->int_start = $request->start_time;
            $interview->int_end = $request->end_time;
            $interview->int_type = $request->int_type;
            $interview->int_link = $request->int_link;
            $interview->int_location = $request->int_lokasi;
            $interview->int_descriptions = $request->int_deskripsi;
            $interview->int_test = $request->int_test;
            $interview->int_officer = $request->int_assign_to;
            $interview->save();
            $item->status = 3;
            $item->rejected_by_applicant = null;
            $item->save();

            $isRescheduled = false;
            $date = date("Y-m-d H:i:s", strtotime($item->backlog_at));
            if(!empty($interview->reschedule_confirm_at)){
                $isRescheduled = true;
            }

            $data = [];

            $data['users'][] = "$item->user_id";

            $data['text'] = "Jadwal Interview untuk job ad ".$job_applicant[$item->job_id]." pada jam $interview->int_start sampai $interview->int_end tanggal ".date("d-m-Y", strtotime($interview->int_date));

            if($isRescheduled){
                $data['text'] = "Permintaan Jadwal Ulang Interview untuk job ad ".$job_applicant[$item->job_id]." disetujui dan akan dilaksanakan pada jam $interview->int_start sampai $interview->int_end tanggal ".date("d-m-Y", strtotime($interview->int_date));
            }

            $data['url'] = route("account.my_applicant_detail", $item->id);

            $data['id'] = $item->id;

            \Notif_kjk::notifSave($data);

            $_email = $user_email[$item->user_id];

            $data = [
                "name" => $user_name[$item->user_id],
                "posisi" => $job_applicant[$item->job_id],
                "int_date" => $interview->int_date,
                "int_start" => $interview->int_start,
                "isRescheduled" => $isRescheduled,
                "link" => route("account.my_applicant_detail", $item->id)
            ];

            Mail::to($_email)->send(new EmailNotification("Undangan Interview", "_email.interview_invitation", $data));
        }

        return redirect()->back();
    }

    function event(Request $request){
        $job_sel = $request->job;
        $job_ads = User_job_vacancy::where("user_id", Auth::id())
            ->get();

        $job_id = $job_ads->pluck("id");
        if(!empty($job_sel)){
            $job_id = [$job_sel];
        }

        $job_pos = $job_ads->pluck("position", "id");

        $user_name = User::pluck("name", "id");
        $user_img = User::pluck("user_img", "id");

        $interview = User_job_interview::whereBetween("int_date", [date("Y-m-d H:i:s", strtotime($request->start)), date("Y-m-d H:i:s", strtotime($request->end))])
            ->where(function($q){
                $q->whereNull("reschedule");
                $q->orWhereRaw("(reschedule is not null and reschedule_confirm_at is not null)");
            })
            ->orderBy("int_date", "asc")
            ->orderBy("int_start", "asc")
            ->get();

        $int_n = [];
        $int_m = [];
        $int_detail = [];

        foreach($interview as $item){
            $col = [];
            $col['start'] = $item->int_start;
            $col['end'] = $item->int_end;
            $col['date'] = $item->int_date;
            if(empty($item->group_code)){
                $int_n[] = $item->job_app_id;
                $int_detail[$item->job_app_id] = $col;
            } else {
                $int_m[] = $item->group_code;
                $int_detail[$item->group_code] = $col;
            }
        }

        $kandidat = User_job_applicant::whereIn("job_id", $job_id)
            ->whereNull("group_code")
            ->where("status", 3)
            ->whereIn("id", array_unique($int_n))
            ->orderBy("created_at")
            ->get();

        $kandidatGroup = User_interview_group::whereIn("job_id", $job_id)
            ->where("status", 3)
            ->whereIn("group_code", array_unique($int_m))
            ->get();

        $applicantGroup = User_job_applicant::whereIn("group_code", $kandidatGroup->pluck("group_code"))
            ->get();

        foreach($applicantGroup as $item){
            $item->name = $user_name[$item->user_id] ?? "-";
        }

        $data_kandidat = [];
        foreach($kandidat as $item){
            $pos = $job_pos[$item->job_id] ?? "";
            $img = $user_img[$item->user_id] ?? null;

            $int_d = $int_detail[$item->id] ?? [];
            if(!empty($int_d)){
                $start_date = date("Y-m-d", strtotime($int_d['date']));
                $start_time = date("H:i:s", strtotime($int_d['start']));
                $end_time = date("H:i:s", strtotime($int_d['end']));
                $item->name = $user_name[$item->user_id] ?? "-";
                $col['id'] = $item->id;
                $col['img'] = $img;
                $col['type'] = "n";
                $col['pos'] = $pos;
                $col['name'] = $user_name[$item->user_id] ?? "-";
                $col['date'] = date("Y-m-d H:i:s", strtotime($item->backlog_at));
                $col['id_int'] = $item->id;
                $col['status'] = $item->status;
                $col['title'] = $user_name[$item->user_id] ?? "-";
                $col['start'] = "$start_date $start_time";
                $col['end'] = "$start_date $end_time";
                $col['description'] = $pos;
                $col['start_label'] = date("H:i", strtotime($start_time));
                $col['end_label'] = date("H:i", strtotime($end_time));
                if(isset($user_name[$item->user_id])) $data_kandidat[] = $col;
            }
        }

        foreach($kandidatGroup as $item){
            $pos = $job_pos[$item->job_id] ?? "-";
            $col = [];
            $int_d = $int_detail[$item->group_code] ?? [];
            if(!empty($int_d)){
                $start_date = date("Y-m-d", strtotime($int_d['date']));
                $start_time = date("H:i:s", strtotime($int_d['start']));
                $end_time = date("H:i:s", strtotime($int_d['end']));
                $col['id'] = $item->id;
                $col['title'] = "Interview Group";
                $col['start'] = "$start_date $start_time";
                $col['end'] = "$start_date $end_time";
                $col['description'] = $pos;
                $col['id_int'] = $item->id;
                $col['img'] = null;
                $col['type'] = "m";
                $col['pos'] = $pos;
                $col['name'] = "Interview Group";
                $col['date'] = date("Y-m-d H:i:s", strtotime($item->created_at));
                $col['applicants'] = $applicantGroup->where('group_code', $item->group_code)->toArray();
                $col['status'] = $item->status;
                $col['start_label'] = date("H:i", strtotime($start_time));
                $col['end_label'] = date("H:i", strtotime($end_time));
                $data_kandidat[] = $col;
            }
        }

        if(count($data_kandidat) > 0){
            usort($data_kandidat, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }

        return json_encode($data_kandidat);
    }


    function applicant($id){
        $job_applicant = User_job_applicant::find($id);
        $job = User_job_vacancy::find($job_applicant->job_id);
        $applicant = User::find($job_applicant->user_id);

        $interview = User_job_interview::where("job_app_id", $job_applicant->id)->get();
        // dd($interview);

        $experience = User_experience::where('user_id', $applicant->id)
            ->orderBy("start_date", "desc")
            ->get();
        foreach($experience as $item){
            $end_date = $item->end_date != null ? $item->end_date : date("Y-m-d");
            $d1 = date_create(date("Y-m-d H:i:s", strtotime($item->start_date)));
            $d2 = date_create(date("Y-m-d H:i:s", strtotime($end_date)));
            $diff = date_diff($d1, $d2);
            $y = $diff->format("%y");
            $item->yoe = $y;
        }

        $edu_formal = User_formal_education::where('user_id', $applicant->id)
            ->orderBy("start_date", "desc")
            ->get();

        $edu_informal = User_informal_education::where('user_id', $applicant->id)
            ->orderBy("start_date", "desc")
            ->get();

        $languages = User_language_skill::where('user_id', $applicant->id)
            ->get();

        $skills = User_skill::where('user_id', $applicant->id)
            ->get();

        $portofolio = User_portofolio::where('user_id', $applicant->id)
            ->first();
        $medsos = User_medsos::where('user_id', $applicant->id)
            ->get();
        $add_family = User_add_family::where('user_id', $applicant->id)
            ->get();
        $add_id_card = User_add_id_card::where('user_id', $applicant->id)
            ->get();
        $add_license = User_add_license::where('user_id', $applicant->id)
            ->get();

        $test = Hrd_employee_test::where("category_id", 1)
            ->get();

        $test_result = Hrd_employee_test_result::where('user_id', $applicant->id)
            ->whereIn('test_id', $test->pluck("id"))
            ->whereNotNull("result_detail")
            ->whereNotNull("result_point")
            ->where("result_point","<=", "100")
            ->orderBy("result_point", "desc")
            ->get();
        $test_list = [];
        foreach($test as $item){
            $test_list[$item->test_id][] = $item;
        }

        $exp = $experience->first() ?? [];

        $profile = User_profile::where("user_id", $applicant->id)->first();

        $data['prov'] = Master_province::get();
        $data['city'] = Master_city::get();
        $data['language'] = Master_language::get()->pluck("name", "id");
        $data['proficiency'] = Master_proficiency::get()->pluck("name", "id");

        $user_assign = User::whereIn("id",$interview->pluck("int_officer"));

        $mComp = Master_company::find(Auth::user()->comp_id);

        return view("_employer.calendar.detail_applicant", compact("data", "interview", "user_assign", "mComp", "profile", "exp", "test_list", "test_result", "job", "job_applicant", "applicant", "experience", "edu_formal", "edu_informal", "languages", "skills", "portofolio", "add_family", "add_id_card", "add_license"));
    }

    function applicant_update($id, Request $request){
        $applicant = User_job_applicant::find($id);

        $applicant->status = $request->status;

        $applicant->save();

        return json_encode($applicant);
    }

    function review(Request $request){

        $applicant = User_job_interview::find($request->id);
        $applicant->int_notes = $request->interview_notes;
        $applicant->int_review_by = Auth::id();
        $applicant->int_review_at = date("Y-m-d H:i:s");
        $file = $request->file("attachment_task");
        if(!empty($file)){
            $name = date("YmdHis")."-review-".$applicant->id."-".$file->getClientOriginalName();
            if($file->move($this->dir, $name)){
                $applicant->int_file_name = $file->getClientOriginalName();
                $applicant->int_file_address = "media/attachments/$name";
            }
        }

        $applicant->save();

        return redirect()->back();
    }

    function reschedule($id){

        $job_app = User_job_applicant::find($id);

        $job_ads = User_job_vacancy::find($job_app->job_id);

        $user = User::find($job_app->user_id);

        $interview = User_job_interview::where("job_app_id", $job_app->id)
            ->where("reschedule", 1)
            ->first();

        $view = view("_employer.calendar.modal_reschedule", compact("job_app", "job_ads", "user", "interview"));

        $resp = [
            "view" => $view->render()
        ];

        return json_encode($resp);
    }

    function reschedule_update(Request $request){
        $job_app = User_job_applicant::find($request->id);

        $interview = User_job_interview::where("job_app_id", $job_app->id)
            ->where("reschedule", 1)
            ->first();

        if($request->submit == 0){
            $job_app->need_reschedule = null;
            $interview->reschedule = null;
            $interview->reschedule_reasons = null;

            $job_app->save();
            $interview->save();
        } else {

            if(!empty($job_app->group_code)){
                $group = User_interview_group::where("group_code", $job_app->group_code)
                    ->where("user_id", $job_app->user_id)
                    ->where("job_id", $job_app->job_id)
                    ->delete();
            }

            $job_app->need_reschedule = null;
            $job_app->status = 2;
            $job_app->group_code = null;
            $job_app->save();

            $interview->reschedule_confirm_at = date("Y-m-d H:i:s");
            $interview->reschedule_confirm_by = Auth::id();
            $interview->save();
        }

        return redirect()->back();
    }
}
