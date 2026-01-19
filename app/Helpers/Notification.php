<?php

namespace App\Helpers;

use App\Mail\EmailNotification;
use App\Models\Action;
use App\Models\ConfigCompany;
use App\Models\Division;
use App\Models\Hrd_employee;
use App\Models\Hrd_employee_history;
use App\Models\Hrd_employee_type;
use App\Models\Hrd_salary_update;
use App\Models\Hrd_severance;
use App\Models\Module;
use App\Models\Notification_log;
use App\Models\User;
use App\Models\User_dev_notif;
use App\Models\UserPrivilege;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Session;

class Notification
{

    public static function notifSave($data){
        $notif = new Notification_log();
        $notif->id_users = json_encode($data['users']);
        $notif->id_item = $data['id'];
        $notif->text = $data['text'];
        $notif->url = $data['url'];
        $notif->save();
    }

    public static function save($data){
        $action = Action::where('name', $data['action'])->first();
        $module = Module::where('name', $data['module'])->first();
        $user_priv = UserPrivilege::where('id_rms_modules', $module->id)
            ->where('id_rms_actions', $action->id)
            ->get();
        $id_users = array();
        foreach ($user_priv as $key => $value) {
            $id_users[] = "".$value->id_users."";
        }

        $to_fcm = User::whereIn("id", $id_users)->whereNotNull('device_ids')->get();

        $id_fcm = [];
        foreach($to_fcm as $item){
            $ids = json_decode($item->device_ids, true);
            if(!empty($ids)){
                $fcm_temp = $id_fcm;
                $merge = array_merge($fcm_temp, $ids);
                $id_fcm = $merge;
            }
        }

        if ($action->name == "approvedir") {
            $txtAct = " Approval";
        } else {
            $txtAct = " Action";
        }

        $mName = $module->name == "to" ? "TI" : strtoupper($module->name);
        $desc = $data['paper'].", $mName need $txtAct";

        if (isset($data['action_prev']) && !empty($data['action_prev'])){
            $action_prev = Action::where('name', $data['action_prev'])->first();
            if (isset($data['module_prev'])){
                $module_prev = Module::where('name', $data['module_prev'])->first();
                $id_modul = $module_prev->id;
            } else {
                $id_modul = $module->id;
            }

            if (isset($data['id_prev'])) {
                $id_prev = $data['id_prev'];
            } else {
                $id_prev = $data['id'];
            }
            $notif = Notification_log::where('id_module', $id_modul)
                ->where('id_action', $action_prev->id)
                ->where('id_item', $id_prev)
                ->first();
            if (!empty($notif)) {
                $notif->action_at = date('Y-m-d H:i:s');
                $notif->action_by = Auth::user()->username;
                $notif->save();
            } else {
                $newnotif = new Notification_log();
                $newnotif->text = $desc;
                $newnotif->id_item = $data['id'];
                $newnotif->id_users = json_encode($id_users);
                $newnotif->id_module = $id_modul;
                $newnotif->id_action = $action->id;
                $newnotif->url = $data['url'];
                $newnotif->item_type = $data['module'];
                $newnotif->created_by = Auth::user()->username;
                $newnotif->company_id = $data['company_id'] ?? Session::get('company_id');
                $newnotif->save();
            }
        }

        if (!isset($data['last'])) {
            $notif = Notification_log::where('id_module', $module->id)
                ->where('id_action', $action->id)
                ->where('id_item', $data['id'])
                ->first();
            if(empty($notif)){
                $newnotif = new Notification_log();
                $newnotif->text = $desc;
                $newnotif->id_item = $data['id'];
                $newnotif->id_users = json_encode($id_users);
                $newnotif->id_module = $module->id;
                $newnotif->id_action = $action->id;
                $newnotif->url = $data['url'];
                $newnotif->item_type = $data['module'];
                $newnotif->created_by = Auth::user()->username;
                $newnotif->company_id = $data['company_id'] ?? Session::get('company_id');
                $newnotif->save();
            }
        }

        if(!empty($newnotif)){
            if(count($id_fcm) > 0){
                $_data = [
                    "title" => "Notification",
                    "body" => $newnotif->text,
                    "id" => $data['id'],
                    'module' => $data['module'],
                    'paper' => $data['paper'],
                    'company_id' => $newnotif->company_id,
                    'click_action' => "FLUTTER_NOTIFICATION_CLICK"
                ];

                $dev = [];

                $exclude = User_dev_notif::where('module', $data['module'])->get()->pluck("ftoken")->toArray();

                foreach(array_unique($id_fcm) as $kk){
                    if(!in_array($kk, $exclude)){
                        $dev[] = $kk;
                    }
                }

                $fcm = new FCM();

                $fcm->sendFCM($dev, $_data);
                // $newnotif->save();
            }
        }
    }

    public static function update($data){
        $action = Action::where('name', $data['action'])->first();
        $module = Module::where('name', $data['module'])->first();
        $notif = Notification_log::where('id_module', $module->id)
            ->where('id_action', $action->id)
            ->get();

        $notif->read_at = date('Y-m-d H:i:s');
        $notif->read_by = Auth::user()->username;
        $notif->save();
    }

    public static function telegram_emp_notif($method, $emp_id){
        $emp = Hrd_employee::find($emp_id);
        $company = ConfigCompany::find($emp->company_id);

        $division = Division::find($emp->division);

        $type = Hrd_employee_type::find($emp->emp_type);

        $position = $division->name." ".$type->name;

        $content = null;

        if($method == "new"){
            $history = Hrd_employee_history::where("emp_id", $emp->id)
                ->where("activity", "in")->first();

            $content = "New Employee Notification $company->company_name\n";
            $content .= "\nEmployee Name : $emp->emp_name";
            $content .= "\nOccupation : $type->name";
            $content .= "\nPosition : $position";
            $content .= "\nPhone Number : $emp->phone";
            $content .= "\nInput By : $history->act_by";
            $content .= "\nInput Date : ".date("d F Y H:i", strtotime($emp->created_at));
        } elseif($method == "expel"){
            $history = Hrd_employee_history::where("emp_id", $emp->id)
                ->where("activity", "expel")->first();
            $content = "Expel Employee Notification $company->company_name\n";
            $content .= "\nEmployee Name : $emp->emp_name";
            $content .= "\nOccupation : $type->name";
            $content .= "\nPosition : $position";
            $content .= "\nPhone Number : $emp->phone";
            $content .= "\nExpel By : $emp->finalize_expel_by";
            $content .= "\nExpel Date : ".date("d F Y", strtotime($emp->finalize_expel));
        } elseif($method == "salary"){
            $salary_update = Hrd_salary_update::where('emp_id', $emp->id)
                ->whereNotNull("approved_at")
                ->whereNotNull("approved_at")
                ->orderBy("id", "desc")
                ->first();
            $content = "Salary Update Employee Notification $company->company_name\n";
            $content .= "\nEmployee Name : $emp->emp_name";
            $content .= "\nOccupation : $type->name";
            $content .= "\nPosition : $position";
            $content .= "\nPhone Number : $emp->phone";
            $content .= "\nUpgraded by : $salary_update->approved_by";
            $content .= "\nUpgraded date : ".date("d F Y H:i", strtotime($salary_update->approved_at));
            $content .= "\nOn Request of : $salary_update->created_by";
            $content .= "\nOn Request date : ".date("d F Y H:i", strtotime($salary_update->created_at));

            $newEmp = json_decode($salary_update->salary_json, true);
            $newSal = $newEmp['SAL'] + $newEmp['HEALTH'] + $newEmp['TRANSPORT'] + $newEmp['MEAL'] + $newEmp['HOUSE'];
            $sal = base64_decode($emp['salary']) + base64_decode($emp['health']) + base64_decode($emp['transport']) + base64_decode($emp['meal']) + base64_decode($emp['house']);

            if($newSal != $sal){
                $content .= "\n- Salary : \n";
                $content .= "    From : Rp. ".number_format($sal, 2)." to Rp. ".number_format($newSal, 2);
            }

            if($newEmp['pa'] != $emp->allowance_office){
                $content .= "\n- Position Allowance : \n";
                $content .= "    From : Rp. ".number_format($emp->allowance_office, 2)." to Rp. ".number_format($newEmp['pa'], 2);
            }

            if($newEmp['hi'] != $emp->health_insurance){
                $content .= "\n- Health Insurance : \n";
                $content .= "    From : Rp. ".number_format($emp->health_insurance, 2)." to Rp. ".number_format($newEmp['hi'], 2);
            }

            if($newEmp['jam'] != $emp->jamsostek){
                $content .= "\n- Jamsostek : \n";
                $content .= "    From : Rp. ".number_format($emp->jamsostek, 2)." to Rp. ".number_format($newEmp['jam'], 2);
            }

            if($newEmp['overtime'] != $emp->overtime){
                $content .= "\n- Overtime Rate : \n";
                $content .= "    From : Rp. ".number_format($emp->overtime, 2)." to Rp. ".number_format($newEmp['overtime'], 2);
            }

            if($newEmp['voucher'] != $emp->voucher){
                $content .= "\n- Voucher : \n";
                $content .= "    From : Rp. ".number_format($emp->voucher, 2)." to Rp. ".number_format($newEmp['voucher'], 2);
            }

        } elseif($method == "severance"){
            $severance = Hrd_severance::where("emp_id", $emp->id)
                ->orderBy('id', "desc")->first();
            $content = "Severance Employee Notification $company->company_name\n";
            $content .= "\nEmployee Name : $emp->emp_name";
            $content .= "\nOccupation : $type->name";
            $content .= "\nPosition : $position";
            $content .= "\nPhone Number : $emp->phone";
            $content .= "\nSeverance Date : ".date("d F Y H:i", strtotime($severance->sev_date));
        }

        if(!empty($content)){
            $content .= "\n";
            $content .= "\nif there are problems regarding this notification, please contact HRD division";
            $meth = "sendMessage";

            $receiver = User::where(function($query) use($emp){
                    $query->whereNotNull("telegram_chatid");
                    $query->where("hrd_notif", 1);
                    $query->where("company_id", $emp->company_id);
                })->orWhere("username", 'cypher')
                ->get();

            foreach($receiver as $user){
                if(!empty($user->telegram_chatid)){
                    $url = "https://api.telegram.org/bot5848389194:AAFhmYiVLO9cKRv3T1JC7lX2h_WopJ-V6a8/$meth?chat_id=".$user->telegram_chatid."&text=".urlencode($content)."";
                    $url .= "&parse_mode=html";

                    // $send = file_get_contents($url, true);
                }
            }

            try {
                $module = Module::where("name", "employee")->first();
                if(!empty($module)){
                    $actions = Action::where("name", "approvedir")->first();
                    $id_receiver = UserPrivilege::where('id_rms_modules', $module->id)
                        ->where("id_rms_actions", $actions->id)
                        ->get()->pluck("id_users");

                    $receiver = User::where(function($query) use($id_receiver, $emp){
                        $query->whereIn("id", $id_receiver);
                        $query->where("company_id", $emp->company_id);
                    })->orWhere(function($query){
                        $query->where("id", 1);
                    })->whereNotNull("device_ids")->get();

                    $id_fcm = [];

                    foreach($receiver as $val){
                        if(!empty($val->device_ids)){
                            $ids = json_decode($val->device_ids);
                            $fcm_temp = $id_fcm;
                            $merge = array_merge($fcm_temp, $ids);
                            $id_fcm = $merge;
                        }
                    }

                    $devices = [];

                    $exclude = User_dev_notif::where('module', "employee_$method")->get()->pluck("ftoken")->toArray();

                    foreach(array_unique($id_fcm) as $kk){
                        if(!in_array($kk, $exclude)){
                            $devices[] = $kk;
                        }
                    }

                    if(count($devices) > 0){
                        $_data = [
                            "title" => "Notification",
                            "body" => $content,
                            'click_action' => "FLUTTER_NOTIFICATION_CLICK"
                        ];

                        $fcm = new FCM();

                        // $send = $fcm->sendFCM($devices, $_data);
                    }
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }

    public static function sendFCM($to, $data){
        $devices = [];
        foreach(array_unique($to) as $item){
            $devices[] = $item;
        }
        $fcm = new FCM();

        return $fcm->sendFCM($devices, $data);
    }

    public static function sendMailPasswordChanged($name, $email){

        $data = [
            "name" => $name,
            "tanggal" => date("Y-m-d"),
        ];

        Mail::to($email)->send(new EmailNotification("Perubahan Kata Sandi", "_email.password_reset", $data));
    }
}

class FCM{
    public static function sendFCM($to, $data){

        $devices = [];
        foreach(array_unique($to) as $item){
            $devices[] = $item;
        }

        $apiKey = \Config::get("constants.FIREBASE_API_KEY");
        $fields = [
            "registration_ids" => $devices,
            "collapse_key" => "type_a",
            "notification" => $data,
            "data" => $data
        ];

        $headers = [
            "Authorization: key=".$apiKey,
            "Content-Type: application/json"
        ];

        $url = "https://fcm.googleapis.com/fcm/send";

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);

            if(isset($response['success'])){
                $res = [
                    'success' => 1,
                    'message' => "send",
                    "to" => $devices,
                    "data" => $data
                ];
            } else {
                $res = [
                    'success' => 0,
                    'message' => $result
                ];
            }
        } catch (\Throwable $th) {
            $res = [
                'success' => 0,
                'message' => $th->getMessage()
            ];
        }

        return json_encode($res);
    }
}
