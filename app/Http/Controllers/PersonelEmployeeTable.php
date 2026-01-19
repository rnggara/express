<?php

namespace App\Http\Controllers;

use App\Models\Asset_wh;
use App\Models\Att_employee_registration;
use App\Models\Att_leave;
use App\Models\Att_leave_employee;
use App\Models\Att_workgroup;
use Illuminate\Http\Request;
use App\Models\Hrd_employee as Personel;
use App\Models\Hrd_employee;
use App\Models\Kjk_comp_asset;
use App\Models\Kjk_comp_bank;
use App\Models\Kjk_comp_country;
use App\Models\Kjk_comp_departement;
use App\Models\Kjk_comp_job_grade;
use App\Models\Master_religion;
use App\Models\Master_marital_status;
use App\Models\Master_gender;
use App\Models\Master_educations;
use App\Models\Master_job_level;
use App\Models\Master_job_type;
use App\Models\Master_specialization;
use App\Models\Master_industry;
use App\Models\Master_language;
use App\Models\Personel_blood_type;
use App\Models\Kjk_comp_resign as Resign;
use App\Models\Kjk_comp_offence as Offence;
use App\Models\Kjk_comp_position;
use App\Models\Kjk_comp_tax_status;
use App\Models\Kjk_employee_location;
use App\Models\Kjk_uac_role;
use App\Models\Personel_asset;
use App\Models\Personel_employee_status;
use App\Models\User;
use App\Models\Personel_resign;
use App\Models\Personel_offence;
use App\Models\Personel_history;
use App\Models\User_add_family as Family;
use App\Models\User_formal_education as Education;
use App\Models\User_experience as Work_exp;
use App\Models\User_language_skill as Language;
use App\Models\User_add_license as License;
use App\Models\User_mcu as Mcu;
use App\Models\Personel_profile as Profile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataEmpExport;

class PersonelEmployeeTable extends Controller
{

    private $dir;
    public function __construct(){
        $_dir = str_replace("/", "\\", public_path("media/attachments"));
        $dir = str_replace("prototype\public_html", \Config::get("constants.ASSET_DIR"), $_dir);
        $this->dir = str_replace("\\", "/", $dir);
    }

    function add_employee(){

        $steps = [
            "primary_data" => [
                "mandatory_data" => [
                    "desc" => "Employee data for login",
                    "icon" => "fi-rr-user",
                    'skip' => false
                ],
                "attendance_registration" => [
                    "desc" => "Register data for attendance",
                    "icon" => "fi-sr-time-quarter-past",
                    'skip' => false
                ],
                "roles" => [
                    "desc" => "Roles for employee",
                    "icon" => "fi-sr-document",
                    'skip' => false
                ],
            ],
            "personal_data" => [
                "private_data" => [
                    "desc" => "Employee private data",
                    "icon" => "fi fi-rr-user",
                    'skip' => false
                ],
                "family_data" => [
                    "desc" => "Employee family data",
                    "icon" => "fi fi-rr-users",
                    'skip' => true
                ],
                "education" => [
                    "desc" => "Completed education",
                    "icon" => "fi-sr-book-alt",
                    'skip' => false
                ],
                "working_experience" => [
                    "desc" => "Employee working experience",
                    "icon" => "fi-sr-briefcase",
                    'skip' => false
                ],
                "language_skill" => [
                    "desc" => "Skill language of employee",
                    "icon" => "fi-sr-boxes",
                    'skip' => true
                ],
                "medical_record" => [
                    "desc" => "Historical medical record employee",
                    "icon" => "fi-sr-doctor",
                    'skip' => false
                ],
                "license" => [
                    "desc" => "License or certificate of employee",
                    "icon" => "fi-sr-folder",
                    'skip' => true
                ],
            ],
            "office_data" => [
                "office_data" => [
                    "desc" => "Employee office data",
                    "icon" => "fi-sr-briefcase",
                    'skip' => false
                ],
                "asset_data" => [
                    "desc" => "Employee asset data",
                    "icon" => "fi-rr-mobile-notch",
                    'skip' => true
                ],
            ],
            "payroll_data" => [
                "summary_data" => [
                    "desc" => "Data general payroll",
                    "icon" => "fi-sr-money-check-edit",
                    'skip' => false
                ],
                "personal_account" => [
                    "desc" => "Personal Bank account",
                    "icon" => "fi-sr-comment-user",
                    'skip' => false
                ],
                "company_account" => [
                    "desc" => "Company bank account",
                    "icon" => "fi-sr-briefcase",
                    'skip' => true
                ],
            ],
        ];

        $religion = Master_religion::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $marital_status = \App\Models\Master_marital_status::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $gender = Master_gender::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $blood_type = Personel_blood_type::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $degree = Master_educations::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")->get();

        $master['job_level'] = \App\Models\Kjk_comp_job_level::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['job_type'] = Kjk_comp_job_grade::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['specialization'] = Master_specialization::pluck("name", "id");
        $master['industry'] = Master_industry::hris(Session::get("company_id"))->pluck("name", "id");
        $master['class'] = \App\Models\Kjk_comp_class::where("company_id", Session::get("company_id"))->pluck("name", "id");

        $languages = Master_language::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $position = Kjk_comp_position::where("company_id", Session::get("company_id"))->get();
        $roles = Kjk_uac_role::where("company_id", Session::get("company_id"))->get();
        $departements = Kjk_comp_departement::where("company_id", Session::get("company_id"))->get();
        $locations = Asset_wh::office()->where("company_id", Session::get("company_id"))->get();
        $workgroups = Att_workgroup::where("company_id", Session::get("company_id"))->get();
        $leavegroup = Att_leave::where("company_id", Session::get("company_id"))->get();
        $emp_status = Personel_employee_status::where("company_id", Session::get("company_id"))->get();

        $assets = Kjk_comp_asset::where("company_id", Session::get('company_id'))->get();

        $loc = Asset_wh::office()->where("company_id", Session::get("company_id"))->get();

        $countries = Kjk_comp_country::where("company_id", Session::get("company_id"))->get();
        $tax_status = Kjk_comp_tax_status::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();
        $banks = Kjk_comp_bank::get();

        $class = [];

        return view("_personel.add_employee.index", compact('workgroups', 'tax_status', 'countries', 'banks', "emp_status", 'loc', 'assets', "class", 'leavegroup', 'steps', 'religion', 'marital_status', 'gender', 'blood_type', 'degree', 'master', 'languages', 'position', 'roles', 'departements', 'locations'));
    }

    function sortDate($date){
        $d = explode("/", $date);
        krsort($d);

        $d = implode("-", $d);
        return $d;
    }

    function add_employee_post(Request $request){

        $data = Session::get("add_data");

        $mandatory = $data['mandatory_data'];
        $office_data = $data['office_data'];
        $roles = $data['roles'];
        $private_data = $data['private_data'];
        $attendance_registration = $data['attendance_registration'];
        $family_data = $data['family_data'] ?? [];
        $education = $data['education'] ?? [];
        $working_experience = $data['working_experience'] ?? [];
        $language_skill = $data['language_skill'] ?? [];
        $medical_record = $data['medical_record'] ?? [];
        $license = $data['license'] ?? [];
        $asset_data = $data['asset_data'] ?? [];
        $summary_data = $data['summary_data'] ?? [];
        $personal_account = $data['personal_account'] ?? [];
        $company_account = $data['company_account'] ?? [];

        $pos = Kjk_comp_position::find($mandatory['position']);

        // User Add
            $user = new User();
            $user->email = $mandatory['email'];
            $user->name = $mandatory['comp'];
            $user->uac_departement = $mandatory['departement'];
            $user->user_img = $mandatory['image'] ?? null;
            $user->uac_location = $mandatory['location'];
            $user->uac_status = $office_data['employee_status'];
            $user->uac_role = $roles['workgroup'];
            $user->password = Hash::make($roles['pw'] ?? "kerjaku".date("Y"));
            $user->email_verified_at = date("Y-m-d H:i:s");
            $user->role_access = '["hris", "employer"]';
            $user->id_rms_roles_divisions = 45;
            $user->id_batch = explode("@",$user->email)[0]."1";
            $user->username = explode("@",$user->email)[0];
            $user->position = "FP";
            $user->access = "EP";
            $user->company_id = Session::get('company_id');
            $user->comp_id = 7;
            $user->complete_profile = 1;
            $user->phone = $private_data['phone'];
            $user->save();
        // END USER

            $estatus = Personel_employee_status::find($office_data['employee_status'] ?? null);

        // EMPLOYEE ADD
            $employee = new Hrd_employee();
            $employee->emp_id = $mandatory['emp_id'];
            $employee->emp_name = $mandatory['comp'];
            $employee->n_basic_salary = base64_encode(0);
            $employee->n_house_allow = base64_encode(0);
            $employee->n_health_allow = base64_encode(0);
            $employee->n_position_allow = base64_encode(0);
            $employee->n_transport_allow = base64_encode(0);
            $employee->n_meal_allow = base64_encode(0);
            $employee->n_performance_bonus = base64_encode(0);
            $employee->grade                 = $request->grade;
            $employee->phoneh                = $private_data['phone'];
            $employee->salary                = base64_encode(0);
            $employee->transport             = base64_encode(0);
            $employee->meal                  = base64_encode(0);
            $employee->house                 = base64_encode(0);
            $employee->health                = base64_encode(0);
            $employee->emp_position          = $pos->name;
            $employee->pension               = ($request->pensi) ? str_replace(",", "", $request->pensi) : 0;
            $employee->health_insurance      = ($request->hi) ? str_replace(",", "", $request->hi) : 0;
            $employee->jamsostek             = ($request->jam) ? str_replace(",", "", $request->jam) : 0;
            $employee->emp_type              = $request->emp_type;
            $employee->religion              = $request->religion;
            $employee->company_id            = Session::get('company_id');
            $employee->tax_status            = 0;
            $employee->fld_bonus             = ($request->fld_bonus) ? str_replace(",", "", $request->fld_bonus) : 0;
            $employee->division              = ($request->division) ? str_replace(",", "", $request->division) : 0;
            $employee->odo_bonus             = ($request->odo_bonus) ? str_replace(",", "", $request->odo_bonus) : 0;
            $employee->wh_bonus              = ($request->wh_bonus) ? str_replace(",", "", $request->wh_bonus) : 0;
            $employee->overtime              = str_replace(",", "", $request->overtime);
            $employee->voucher               = str_replace(",", "", $request->voucher);
            $employee->yearly_bonus          = ($request->yb) ? str_replace(",", "", $request->yb) : 0;
            $employee->allowance_office      = ($request->pa) ? str_replace(",", "", $request->pa) : 0;
            $employee->dom_meal              = $request->dom_meal;
            $employee->dom_spending          = $request->dom_spending;
            $employee->dom_overnight         = $request->dom_overnight;
            $employee->ovs_meal              = $request->ovs_meal;
            $employee->ovs_spending          = $request->ovs_spending;
            $employee->ovs_overnight         = $request->ovs_overnight;
            $employee->dom_transport_train   = $request->dom_transport_train;
            $employee->dom_transport_airport = $request->dom_transport_airport;
            $employee->dom_transport_bus     = $request->dom_transport_bus;
            $employee->dom_transport_cil     = $request->dom_transport_cil;
            $employee->ovs_transport_train   = $request->ovs_transport_train;
            $employee->ovs_transport_airport = $request->ovs_transport_airport;
            $employee->ovs_transport_bus     = $request->ovs_transport_bus;
            $employee->ovs_transport_cil     = $request->ovs_transport_cil;
            $employee->latitude              = $request->latitude;
            $employee->longitude             = $request->longitude;
            $employee->cuti_flag             = 0;
            $employee->max_loan              = 0;
            $employee->others                = 0;
            $employee->bank_code             = $request->bankCode;
            $employee->bank_acct             = $request->account;
            $employee->phone                 = $private_data['phone'];
            $employee->phone2                = $private_data['phone'];
            $employee->address               = $private_data['identity']['address'];
            $employee->email                 = $mandatory['email'];
            $employee->emp_lahir             = $private_data['emp_lahir'];
            $employee->created_by = Auth::user()->username;
            $employee->join_date = $this->sortDate($mandatory['join_date']);
            $employee->position_id = $mandatory['position'];
            $employee->acting_position_id = $mandatory['acting_position'] ?? null;
            $employee->job_level_id = $mandatory['job_level'];
            $employee->job_grade_id = $office_data['job_grade'] ?? null;
            $employee->employee_status_id = $estatus->id ?? null;
            $employee->employee_status_mutation_start = $this->sortDate($mandatory['join_date']);
            if(($estatus->end_date ?? 0) == 1){
                $employee->employee_status_mutation_end = $this->sortDate($office_data['emp_status_end_date']);
            }
            $employee->temp_id_card = $attendance_registration['id_card'] ?? null;
            $employee->temp_workgroup = $attendance_registration['workgroup'] ?? null;
            $employee->temp_leavegroup = $attendance_registration['leavegroup'] ?? null;
            $employee->class_id = $office_data['class'] ?? null;
            $employee->office_file = $office_data['file'] ?? null;

            $employee->tax_status_id = $summary_data['tax_status'] ?? null;
            $employee->tax_type = $summary_data['tax_type'] ?? null;
            $employee->payroll_currency = $summary_data['currency'] ?? null;
            $employee->payroll_payment_method = $summary_data['payment_method'] ?? null;
            $employee->payroll_payment_schedule = $summary_data['payment_schedule'] ?? null;
            $employee->payroll_active_period_date = $summary_data['active_period_date'] ?? null;

            $employee->personal_bank_id = $personal_account['bank_name'] ?? null;
            $employee->personal_bank_number = $personal_account['account_number'] ?? null;
            $employee->personal_bank_name = $personal_account['account_name'] ?? null;

            $employee->company_bank_id = $company_account['bank_name'] ?? null;
            $employee->company_bank_number = $company_account['account_number'] ?? null;
            $employee->company_bank_name = $company_account['account_name'] ?? null;

            $employee->save();

            $user->emp_id = $employee->id;
            $user->save();
        // EMPLOYEE END

        // REGISTRATIONS
            $employee->mobile_att = ($attendance_registration['mobile_att'] ?? null);

            if(($attendance_registration['mobile_att'] ?? null) == 1){
                $employee->anywhere = $attendance_registration['wfa'] ?? null;
            }

            $employee->save();

            $myLoc = Kjk_employee_location::where("emp_id", $employee->id)->get();

            $emp_loc = $attendance_registration['locations'] ?? [];
            foreach($emp_loc as $item){
                if(!in_array($item, $myLoc->pluck("wh_id")->toArray())){
                    $eloc = new Kjk_employee_location();
                    $eloc->wh_id = $item;
                    $eloc->emp_id = $employee->id;
                    $eloc->company_id = $employee->company_id;
                    $eloc->created_by = Auth::id();
                    $eloc->save();
                }
            }

            if(!empty($attendance_registration['id_card']) && !empty($attendance_registration['workgroup']) && !empty($attendance_registration['leavegroup'])){
                $registration = new Att_employee_registration();
                $registration->company_id = Session::get("company_id");
                $registration->created_by = Auth::id();
                $registration->emp_id = $employee->id;
                $registration->id_card = $attendance_registration['id_card'];
                $registration->workgroup = $attendance_registration['workgroup'];
                $registration->leavegroup = $attendance_registration['leavegroup'];
                $registration->date1 = $this->sortDate($attendance_registration['date_join_group']);
                $registration->date2 = $this->sortDate($attendance_registration['date_join_group']);
                $registration->date3 = $this->sortDate($attendance_registration['date_join_group']);

                $registration->save();
                $cutiControll = new KjkAttLeave();

                $cutiControll->cronCuti($registration->emp_id, Auth::id());
            }

            // $lg = Att_leave::find($registration->leavegroup);
            // $annual = $lg->annual_total_leaves ?? [];
            // $long = $lg->long_total_leaves ?? [];
            // $special = $lg->special_total_leaves ?? [];

            // $start_periode = $employee->join_date;
            // if($lg->show_type == 1){
            //     $lpr = explode("/", $lg->start_leave_periode);
            //     krsort($lpr);
            //     $start_periode = date("Y")."-".implode("-", $lpr);
            // }

            // $dyear = 0;
            // $d1 = date_create($employee->join_date);
            // $d2 = date_create(date("Y-m-d"));
            // $d3 = date_diff($d2, $d1);
            // $dyear = $d3->format("%y");

            // if(!empty($annual)){
            //     $end_periode = date("Y-m-d", strtotime("$start_periode +$lg->annual_leave_expired months"));
            //     $jatah = 0;
            //     foreach($annual as $item){
            //         if($dyear >= $item['range_from'] && $dyear <= $item['range_to']){
            //             $jatah = $item['total_leave'];
            //             break;
            //         }
            //     }
            //     $lemp = new Att_leave_employee();
            //     $lemp->emp_id = $employee->id;
            //     $lemp->type = "annual";
            //     $lemp->leavegroup = $registration->leavegroup;
            //     $lemp->start_periode = $start_periode;
            //     $lemp->end_periode = $end_periode;
            //     $lemp->jatah = $jatah;
            //     $lemp->company_id = Session::get("company_id");
            //     $lemp->created_by = Auth::id();
            //     $lemp->save();
            // }

            // if(!empty($long)){
            //     $end_periode = date("Y-m-d", strtotime("$start_periode +$lg->long_expired months"));
            //     $isExist = Att_leave_employee::where("emp_id", $request->emp)
            //         ->where("leavegroup", $registration->leavegroup)
            //         ->where("end_periode", ">=", $start_periode)
            //         ->first();
            //     if(empty($isExist)){
            //         $jatah = 0;
            //         foreach($long as $item){
            //             if($dyear >= $item['lama_kerja']){
            //                 $jatah = $item['total_leave'];
            //             }
            //         }
            //         $lemp = new Att_leave_employee();
            //         $lemp->emp_id = $employee->id;
            //         $lemp->type = "long";
            //         $lemp->leavegroup = $registration->leavegroup;
            //         $lemp->start_periode = $start_periode;
            //         $lemp->end_periode = $end_periode;
            //         $lemp->jatah = $jatah;
            //         $lemp->company_id = Session::get("company_id");
            //         $lemp->created_by = Auth::id();
            //         $lemp->save();
            //     }
            // }

            // if(!empty($special)){
            //     $end_periode = date("Y-m-d", strtotime("$start_periode +$lg->annual_leave_expired months"));
            //     $jatah = 0;
            //     foreach($special as $item){
            //         $jatah += $item['total_leaves'];
            //     }
            //     $lemp = new Att_leave_employee();
            //     $lemp->emp_id = $employee->id;
            //     $lemp->type = "special";
            //     $lemp->leavegroup = $registration->leavegroup;
            //     $lemp->start_periode = $start_periode;
            //     $lemp->end_periode = $end_periode;
            //     $lemp->company_id = Session::get("company_id");
            //     $lemp->created_by = Auth::id();
            //     $lemp->save();
            // }
        // END REGISTRATIONS

        // PROFILE START
            $profile = new Profile();
            $profile->user_id = $employee->id;

            $profile->identity_type =$private_data['identity_type'];
            $profile->identity_number =$private_data['identity_number'];
            $profile->citizenship =$private_data['citizenship'];
            $profile->marital_status =$private_data['marital_status'];
            $profile->religion =$private_data['religion'];
            $profile->gender =$private_data['gender'];
            $profile->blood_type =$private_data['blood_type'];
            $profile->height =$private_data['height'];
            $profile->weight =$private_data['weight'];
            $profile->npwp =$private_data['npwp'];
            $profile->bpjskes =$private_data['bpjs_kes'];
            $profile->bpjstk =$private_data['bpjs_tk'];
            $profile->identity_address = $private_data['identity']['address'] ?? "-";
            $profile->identity_zip_code = $private_data['identity']['zip_code'] ?? "-";
            $profile->identity_country = $private_data['identity']['country'] ?? "-";
            $profile->identity_city = $private_data['identity']['city'] ?? "-";
            $profile->identity_province = $private_data['identity']['province'] ?? "-";
            $profile->resident_address = $private_data['resident']['address'] ?? "-";
            $profile->resident_zip_code = $private_data['resident']['zip_code'] ?? "-";
            $profile->resident_country = $private_data['resident']['country'] ?? "-";
            $profile->resident_city = $private_data['resident']['city'] ?? "-";
            $profile->resident_province = $private_data['resident']['province'] ?? "-";
            $profile->identity_file = $private_data['identity_file'] ?? null;
            $profile->npwp_file = $private_data['npwp_file'] ?? null;
            $profile->bpjskes_file = $private_data['bpjskes_file'] ?? null;
            $profile->bpjstk_file = $private_data['bpjstk_file'] ?? null;

            $profile->save();

            $employee->emp_lahir = $private_data['emp_lahir'];
            $employee->emp_tmpt_lahir = $private_data['emp_tmpt_lahir'];
            $employee->phone = $private_data['phone'];
            $employee->email = $private_data['personal_email'];
            $employee->save();
        // END PROFILE

        // FAMILTY START
            $family_saved = $family_data['saved'] ?? [];
            foreach($family_saved as $i => $saved){
                if($saved == 1){
                    if(!empty($family_data['name'][$i] ?? null)){
                        $family = new Family();
                        $family->user_id = $employee->id;
                        $family->personel = 1;
                        $family->name = $family_data['name'][$i];
                        $family->hubungan = $family_data['hubungan'][$i];
                        $family->marital_id = $family_data['marital_id'][$i];
                        $family->jenis_kelamin = $family_data['gender'][$i];
                        $family->tgl_lahir = $family_data['tgl_lahir'][$i];
                        $family->no_telp = $family_data['no_telp'][$i];
                        $family->emergency_contact = $family_data['emergency'][$i] ?? 0;
                        $family->lampiran = $family_data['lampiran'][$i] ?? null;
                        $family->save();
                    }
                }
            }
        // END FAMILTY

        // EDUCATION START
            $edu_saved = $education['saved'] ?? [];
            foreach($edu_saved as $i => $saved){
                if($saved == 1){
                    if(!empty($education['degree'][$i] ?? null)){
                        $edu = new Education();
                        $edu->user_id = $employee->id;
                        $edu->personel = 1;
                        $edu->degree = $education['degree'][$i];
                        $edu->field_of_study = $education['field_of_study'][$i] ?? null;
                        $edu->grade = $education['grade'][$i] ?? null;
                        $edu->school_name = $education['school_name'][$i];
                        $edu->year_graduate = $education['year_graduate'][$i];
                        $edu->lampiran = $education['lampiran'][$i] ?? null;
                        $edu->save();
                    }
                }
            }
        // END EDUCATION

        // WORK START
            $work_saved = $working_experience['saved'] ?? [];
            foreach($work_saved as $i => $saved){
                if($saved == 1){
                    if(!empty($working_experience['company'][$i] ?? null)){
                        $data = new Work_exp();
                        $data->user_id = $employee->id;
                        $data->personel = 1;
                        $data->company = $working_experience['company'][$i];
                        $data->salary = str_replace(",", "", $working_experience['salary'][$i]);
                        $data->position = $working_experience['position'][$i];
                        $data->job_type = $working_experience['job_type'][$i];
                        $data->industry = $working_experience['industry'][$i];
                        $data->start_date = $working_experience['start_year'][$i]."-".$working_experience['start_month'][$i];
                        if(empty($working_experience['still'][$i] ?? null)){
                            $data->end_date = $working_experience['end_year'][$i]."-".$working_experience['end_month'][$i];
                        }
                        $data->still = $working_experience['still'][$i] ?? 0;
                        $data->descriptions = $working_experience['descriptions'][$i];
                        $data->reference = $working_experience['reference'][$i];
                        $data->phone = $working_experience['phone'][$i];
                        $data->save();
                    }
                }
            }
        // END WORK

        // LANG START
            $lang_saved = $language_skill['saved'] ?? [];
            foreach($lang_saved as $i => $saved){
                if($saved == 1){
                    if(!empty($language_skill['language'][$i] ?? null)){
                        $data = new Language();
                        $data->user_id = $employee->id;
                        $data->personel = 1;
                        $data->language = $language_skill['language'][$i];
                        $data->writing = $language_skill['writing'][$i];
                        $data->speaking = $language_skill['speaking'][$i];
                        $data->reading = $language_skill['reading'][$i];
                        $data->save();
                    }
                }
            }
        // END LANG

        // MCU START
            $mcu_saved = $medical_record['saved'] ?? [];
            foreach($mcu_saved as $i => $saved){
                if($saved == 1){
                    if(!empty($medical_record['descriptions'][$i] ?? null)){
                        $data = new Mcu();
                        $data->user_id = $employee->id;
                        $data->descriptions = $medical_record['descriptions'][$i];
                        $data->year = $medical_record['year'][$i];
                        $data->lampiran = $medical_record['lampiran'][$i] ?? null;
                        $data->save();
                    }
                }
            }
        // END MCU

        // LICENSE START
            $lic_saved = $license['saved'] ?? [];
            foreach($lic_saved as $i => $saved){
                if($saved == 1){
                    if(!empty($license['name'][$i])){
                        $data = new License();
                        $data->user_id = $employee->id;
                        $data->personel = 1;
                        $data->name = $license['name'][$i];
                        $data->no_lisensi = $license['number'][$i];
                        $data->tgl_kadaluarsa = $license['exp_date'][$i];
                        $data->lampiran = $license['lampiran'][$i] ?? null;
                        $data->save();
                    }
                }
            }
        // END LICENSE

        // ASSET START
            $passet = $asset_data['personal'] ?? [];
            $casset = $asset_data['company'] ?? [];

            $passaved = $passet['saved'] ?? [];
            foreach($passaved as $i => $saved){
                if($saved == 1){
                    if(!empty($passet['asset'][$i] ?? null)){
                        // $asset = new Personel_asset();
                        $data['asset'] = $passet['asset'][$i];
                        $data['description'] = $passet['description'][$i];
                        $data['date'] = $passet['date'][$i];
                        $type = "personal";
                        $asset = $this->asset_save($employee->id, $data, $type);
                        // $asset->emp_id = $employee->id;
                        // $asset->asset_id = $passet['asset'][$i];
                        // $asset->descriptions = $passet['description'][$i];
                        // $asset->type = "personal";
                        // $asset->date_received = $passet['date'][$i];
                        // $asset->company_id = Session::get("company_id");
                        // $asset->created_by = Auth::id();
                        // $asset->save();
                    }
                }
            }

            $cossaved = $casset['saved'] ?? [];
            foreach($cossaved as $i => $saved){
                if($saved == 1){
                    if(!empty($casset['asset'][$i] ?? null)){
                        // $asset = new Personel_asset();
                        $data['asset'] = $casset['asset'][$i];
                        $data['description'] = $casset['description'][$i];
                        $data['date'] = $casset['date'][$i];
                        $data['serial_num'] = $casset['serial_num'][$i];
                        $type = "company";
                        $asset = $this->asset_save($employee->id, $data, $type);
                        // $asset = new Personel_asset();
                        // $asset->emp_id = $employee->id;
                        // $asset->asset_id = $casset['asset'][$i];
                        // $asset->descriptions = $casset['description'][$i];
                        // $asset->serial_num = $casset['serial_num'][$i];
                        // $asset->type = "company";
                        // $asset->date_received = $casset['date'][$i];
                        // $asset->company_id = Session::get("company_id");
                        // $asset->created_by = Auth::id();
                        // $asset->save();
                    }
                }
            }
        // END ASSET

        return redirect()->route("personel.employee_table.index");
    }

    function step(Request $request){
        $addState = Session::get("add_state");
        if(empty($addState)){
            $addState =  Str::random(40);
            Session::put("add_state", $addState);
        }

        $addData = Session::get("add_data") ?? [];

        $_mdata = $addData[$request->form_type] ?? [];

        foreach($request->all() as $key => $item){
            if($key != "_token" && $key != "form_type"){
                $isFile = $request->hasFile($key);
                $col = [];
                if($isFile){
                    $dir = str_replace("attachments", "employee_attachment", $this->dir);
                    $file = $request->file($key);
                    if(!empty($file)){
                        $name = date("YmdHis")."_".$file->getClientOriginalName();
                        if($file->move($dir, $name)){
                            $_mdata[$key] = "media/employee_attachment/$name";
                        }
                    }
                } else {
                    $_mdata[$key] = $item;
                }
            }
        }

        $addData[$request->form_type] = $_mdata;

        Session::put("add_data", $addData);

        return json_encode($addData);
    }

    function index(Request $request){

        // $state = "personel";
        // Session::put("session_state", $state);
        // Session::put("home_url", route("personel.employee_table.index"));

        $resigned = Personel_resign::where("company_id", Session::get("company_id"))
            ->where("resign_date", "<=", date("Y-m-d"))
            ->get();

        $sr = Personel_resign::where("company_id", Session::get("company_id"))
            ->where("resign_date", ">", date("Y-m-d"))
            ->get();

        $scheduled_resigned = [];
        foreach($sr as $item){
            $scheduled_resigned[$item->emp_id][] = $item;
        }

        $personel = Personel::where("company_id", Session::get("company_id"))
            ->where("emp_name", "!=", "")
            ->whereNotIn("id", $resigned->pluck("emp_id"))
            ->orderBy("emp_name")
            ->get();

        $personel_resigned = Personel::where("company_id", Session::get("company_id"))
            ->where("emp_name", "!=", "")
            ->whereIn("id", $resigned->pluck("emp_id"))
            ->orderBy("emp_name")
            ->get();

        if($request->a){
            if($request->a == "resign"){
                $pr = $personel->where("id", $request->id)->first();

                $resign_reason = Resign::where("company_id", Session::get("company_id"))->get();

                $approval = User::hris()->where("company_id", Session::get("company_id"))->orderBy("name")->get();

                $view = view("_personel.employee_table._resign", compact("pr", "resign_reason", "approval"));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->a == "offence"){
                $pr = $personel->where("id", $request->id)->first();

                $offence_reason = Offence::where("company_id", Session::get("company_id"))->get();

                $approval = User::hris()->where("company_id", Session::get("company_id"))->orderBy("name")->get();

                $view = view("_personel.employee_table._offence", compact("pr", "offence_reason", "approval"));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->a == "view-offence"){
                $pr = $personel->where("id", $request->id)->first();

                $offences = Personel_offence::where("emp_id", $pr->id)->orderBy("created_at", "desc")->get();

                $view = view("_personel.employee_table._offence_view", compact("pr", "offences"));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->a == "detail-offence"){
                $offence = Personel_offence::find($request->id);
                $pr = $personel->where("id", $offence->emp_id)->first();

                $offence_reason = Offence::where("company_id", Session::get("company_id"))->get();

                $approval = User::hris()->where("company_id", Session::get("company_id"))->orderBy("name")->get();

                $disabled = "disabled";

                $view = view("_personel.employee_table._offence", compact("pr", "offence_reason", "approval", "disabled", "offence"));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->a == "transfer"){
                $pr = Personel::find($request->id);
                $user = $pr->user ?? [];

                $reg = $pr->reg ?? [];

                $view = view("_personel.employee_table._transfer", compact("pr", 'reg', 'user'));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->a == "transfer_batch"){
                $personels = Personel::whereIn("id", $request->id ?? [])->orderBy('emp_name')->get();

                $disabled['wg'] = [];
                $disabled['job_level'] = [];
                $disabled['job_grade'] = [];
                $disabled['employee_status'] = [];
                $disabled['departement'] = [];
                $disabled['position'] = [];
                $disabled['acting_position'] = [];
                $disabled['location'] = [];
                foreach($personels as $item){
                    if(!in_array($item->job_level_id, $disabled['job_level'])){
                        $disabled['job_level'][] = $item->job_level_id;
                    }

                    if(!in_array($item->job_grade_id, $disabled['job_grade'])){
                        $disabled['job_grade'][] = $item->job_grade_id;
                    }

                    if(!in_array($item->employee_status_id, $disabled['employee_status'])){
                        $disabled['employee_status'][] = $item->employee_status_id;
                    }

                    if(!in_array($item->position_id, $disabled['position'])){
                        $disabled['position'][] = $item->position_id;
                    }

                    if(!in_array(($item->reg->workgroup ?? null), $disabled['wg'])){
                        $disabled['wg'][] = $item->reg->workgroup ?? null;
                    }

                    if(!in_array(($item->user->uac_role ?? null), $disabled['acting_position'])){
                        $disabled['acting_position'][] = $item->user->uac_role ?? null;
                    }

                    if(!in_array(($item->user->uac_location ?? null), $disabled['location'])){
                        $disabled['location'][] = $item->user->uac_location ?? null;
                    }

                    if(!in_array(($item->user->uac_departement ?? null), $disabled['departement'])){
                        $disabled['departement'][] = $item->user->uac_departement ?? null;
                    }
                }

                $view = view("_personel.employee_table._transfer_batch", compact("personels", 'personel', 'disabled'));

                return json_encode([
                    "view" => $view->render(),
                    "disabled" => $disabled,
                    'personels' => $personels
                ]);
            }

            if($request->a == "transfer_form_batch"){
                $approval = User::hris()->where("company_id", Session::get("company_id"))->orderBy("name")->get();
                $type = $request->type;
                $tpLabel = ucwords(str_replace("_", " ", $type));

                $data = [];

                $pr = Personel::find($request->id);

                if($type == "workgroup"){
                    $oldData = \App\Models\Att_workgroup::find($request->id);
                    $oldLabel = $oldData->id ?? "-";
                    $data = \App\Models\Att_workgroup::select(['id', 'workgroup_name as name'])
                        // ->where("id", "!=", $oldData->id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "job_level"){
                    $oldData = \App\Models\Kjk_comp_job_level::find($request->id);
                    $oldLabel = $oldData->id ?? "-";
                    $data = \App\Models\Kjk_comp_job_level::select(['id', 'name'])
                        ->where("id", "!=", $oldData->id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "job_grade"){
                    $oldData = \App\Models\Kjk_comp_job_grade::find($request->id);
                    $oldLabel = $oldData->id ?? "-";
                    $data = \App\Models\Kjk_comp_job_grade::select(['id', 'name'])
                        ->where("id", "!=", $oldData->id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "employee_status"){
                    $oldData = \App\Models\Personel_employee_status::find($request->id);
                    $oldLabel = $oldData->id ?? "-";
                    $data = \App\Models\Personel_employee_status::select(['id', 'label as name'])
                        ->where("id", "!=", $oldData->id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "position"){
                    $oldData = \App\Models\Kjk_comp_position::find($request->id);
                    $oldLabel = $oldData->id ?? "-";
                    $data = \App\Models\Kjk_comp_position::select(['id', 'name'])
                        ->where("id", "!=", $oldData->id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "departement"){
                    $oldData = \App\Models\Kjk_comp_departement::find($request->id);
                    $oldLabel = $oldData->id ?? "-";
                    $data = \App\Models\Kjk_comp_departement::select(['id', 'name'])
                        ->where("id", "!=", $oldData->id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "location"){
                    $oldData = \App\Models\Asset_wh::find($request->id);
                    $oldLabel = $oldData->id ?? "-";
                    $data = \App\Models\Asset_wh::select(['id', 'name'])->office()
                        ->where("id", "!=", $oldData->id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "acting_position"){
                    $oldData = \App\Models\Kjk_comp_position::find($request->id);
                    $oldLabel = $oldData->id ?? "-";
                    $data = \App\Models\Kjk_comp_position::select(['id', 'name'])
                        ->where("id", "!=", $oldData->id ?? null)->get();
                }

                $_edit = $request->edit ?? [];

                $approvalSetting = \App\Models\Kjk_pref_approval_transfer::where("key_id", $type)
                    ->where("company_id", Session::get("company_id"))->first();

                $needApproval = $approvalSetting->checked ?? 1;

                $lbl = [
                    "workgroup" => "Grup Kerja",
                    "job_grade" => "Tingkat",
                    "job_level" => "Level",
                    "employee_status" => "Status Kepegawaian",
                    "departement" => "Departemen",
                    "location" => "Lokasi",
                    "position" => "Posisi",
                    "acting_position" => "Posisi Sementara",
                ];

                $view = view("_personel.employee_table._transfer_form", compact('type', 'tpLabel', 'approval', 'data', 'oldLabel', '_edit', 'needApproval', 'lbl'));

                return json_encode([
                    "view" => $view->render()
                ]);
            }

            if($request->a == "transfer_form"){
                $pr = $personel->where("id", $request->id)->first();
                $user = $pr->user ?? [];

                $type = $request->type;
                $tpLabel = ucwords(str_replace("_", " ", $type));

                $approval = User::hris()->where("company_id", Session::get("company_id"))->orderBy("name")->get();

                $data = [];

                $oldLabel = "-";
                $reg = $pr->reg ?? [];

                if($type == "workgroup"){
                    $oldLabel = $reg->wg->workgroup_name ?? "-";
                    $data = \App\Models\Att_workgroup::select(['id', 'workgroup_name as name'])
                        // ->where("id", "!=", $reg->workgroup ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "job_level"){
                    $oldLabel = $pr->job_level->name ?? "-";
                    $data = \App\Models\Kjk_comp_job_level::select(['id', 'name'])
                        ->where("id", "!=", $pr->job_level_id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "job_grade"){
                    $oldLabel = $pr->job_grade->name ?? "-";
                    $data = \App\Models\Kjk_comp_job_grade::select(['id', 'name'])
                        ->where("id", "!=", $pr->job_grade_id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "employee_status"){
                    $oldLabel = $pr->employee_status->label ?? "-";
                    $data = \App\Models\Personel_employee_status::select(['id', 'label as name'])
                        ->where("id", "!=", $pr->employee_status_id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "position"){
                    $oldLabel = $pr->position->name ?? "-";
                    $data = \App\Models\Kjk_comp_position::select(['id', 'name'])
                        ->where("id", "!=", $pr->position_id ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "departement"){
                    $oldLabel = $user->uacdepartement->name ?? "-";
                    $data = \App\Models\Kjk_comp_departement::select(['id', 'name'])
                        ->where("id", "!=", $user->uac_departement ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "location"){
                    $oldLabel = $user->uaclocation->name ?? "-";
                    $data = \App\Models\Asset_wh::select(['id', 'name'])->office()
                        ->where("id", "!=", $user->uac_location ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                } elseif($type == "acting_position"){
                    $oldLabel = $user->uacrole->name ?? "-";
                    $data = \App\Models\Kjk_comp_position::select(['id', 'name'])
                        ->where("id", "!=", $user->uac_role ?? null)
                        ->where("company_id", Session::get("company_id"))->get();
                }

                $approvalSetting = \App\Models\Kjk_pref_approval_transfer::where("key_id", $type)
                    ->where("company_id", Session::get("company_id"))->first();

                $needApproval = $approvalSetting->checked ?? 1;

                $_edit = $request->edit ?? [];

                $lbl = [
                    "workgroup" => "Grup Kerja",
                    "job_grade" => "Tingkat",
                    "job_level" => "Level",
                    "employee_status" => "Status Kepegawaian",
                    "departement" => "Departemen",
                    "location" => "Lokasi",
                    "position" => "Posisi",
                    "acting_position" => "Posisi Sementara",
                ];

                $view = view("_personel.employee_table._transfer_form", compact("pr", 'type', 'tpLabel', 'approval', 'data', 'oldLabel', 'reg', 'user', '_edit', 'needApproval', 'lbl'));

                return json_encode([
                    "view" => $view->render()
                ]);
            }
        }

        $master['job_level'] = \App\Models\Kjk_comp_job_level::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['job_type'] = Kjk_comp_job_grade::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['position'] = Kjk_comp_position::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['roles'] = Kjk_uac_role::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['employee_status'] = Personel_employee_status::where("company_id", Session::get("company_id"))->pluck("label", "id");
        $master['departement'] = Kjk_comp_departement::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['location'] = Asset_wh::office()->where("company_id", Session::get("company_id"))->pluck('name', 'id');
        $master['class'] = \App\Models\Kjk_comp_class::where("company_id", Session::get("company_id"))->pluck("name", "id");

        $transfer_data = Personel_history::whereIn("personel_id", $personel->pluck("id"))
            ->where("start_date", ">", date("Y-m-d"))
            ->whereNotNull("approved_at")
            ->orderBy("start_date")
            ->get();
        $transfer = [];
        foreach($transfer_data as $item){
            $transfer[$item->personel_id][$item->type][] = $item;
        }

        return view("_personel.employee_table.index", compact("personel", 'master', 'personel_resigned', 'scheduled_resigned', "transfer"));
    }

    function detail($id){
        $personel = Personel::find($id);

        $resigned = Personel_resign::where("company_id", Session::get("company_id"))
            ->where("emp_id", $personel->id)
            ->where("resign_date", "<=", date("Y-m-d"))
            ->first();

        $personel->expire = $resigned->resign_date ?? null;

        $reg = $personel->reg;

        $religion = Master_religion::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $marital_status = Master_marital_status::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $gender = Master_gender::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $blood_type = Personel_blood_type::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $families = Family::where("user_id", $personel->id)
            ->where("personel", 1)
            ->get();

        $education = Education::where("user_id", $personel->id)
            ->where("personel", 1)
            ->get();

        $documents = \App\Models\Hrd_cv_u::where('user_id', $personel->id)
            ->get();

        $degree = Master_educations::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")->get();

        $experience = Work_exp::where("user_id", $personel->id)
            ->where("personel", 1)
            ->get();

        $master['job_level'] = \App\Models\Kjk_comp_job_level::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['job_type'] = Kjk_comp_job_grade::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['specialization'] = Master_specialization::pluck("name", "id");
        $master['industry'] = Master_industry::hris(Session::get("company_id"))->pluck("name", "id");
        $master['class'] = \App\Models\Kjk_comp_class::where("company_id", Session::get("company_id"))->pluck("name", "id");
        // $master['document_cat'] = \App\Models\Personel_identity::where("company_id", Session::get("company_id"))->get();
        $master['document_cat'] = ['Identity', "Official Data", "Family"];
        $position = Kjk_comp_position::where("company_id", Session::get("company_id"))->get();
        $roles = Kjk_uac_role::where("company_id", Session::get("company_id"))->get();
        $emp_status = Personel_employee_status::where("company_id", Session::get("company_id"))->get();
        $departements = Kjk_comp_departement::where("company_id", Session::get("company_id"))->get();

        $languages = Master_language::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();

        $language = Language::where('user_id', $personel->id)
            ->where("personel", 1)
            ->get();

        $mcu = Mcu::where("user_id", $personel->id)->get();

        $license = License::where('user_id', $personel->id)
            ->where("personel", 1)
            ->get();

        $profile = Profile::where("user_id", $personel->id)->first();

        $assets = Kjk_comp_asset::where("company_id", Session::get('company_id'))->get();

        $emp_asset = Personel_asset::where("emp_id", $personel->id)->get();

        $loc = Asset_wh::office()->where("company_id", Session::get("company_id"))->get();

        $countries = Kjk_comp_country::where("company_id", Session::get("company_id"))->get();
        $tax_status = Kjk_comp_tax_status::where("company_id", Session::get("company_id"))
            ->orWhereNull("company_id")
            ->get();
        $banks = Kjk_comp_bank::get();

        $_estat = Personel_employee_status::find($personel->employee_status_id);

        $offence['reason'] = Offence::where("company_id", Session::get("company_id"))->get();

        $offence['approval'] = User::hris()->where("company_id", Session::get("company_id"))->orderBy("name")->get();

        $offence['data'] = Personel_offence::where("emp_id", $personel->id)->get();

        $transfer_data = Personel_history::where("personel_id", $personel->id)
            ->whereNotNull("approved_at")
            ->orderBy("start_date", "desc")
            ->get();

        $tfMaster['job_level'] = \App\Models\Kjk_comp_job_level::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $tfMaster['job_type'] = Kjk_comp_job_grade::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $tfMaster['position'] = Kjk_comp_position::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $tfMaster['roles'] = Kjk_uac_role::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $tfMaster['employee_status'] = Personel_employee_status::where("company_id", Session::get("company_id"))->pluck("label", "id");
        $tfMaster['departement'] = Kjk_comp_departement::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $tfMaster['location'] = Asset_wh::office()->where("company_id", Session::get("company_id"))->pluck('name', 'id');
        $tfMaster['class'] = \App\Models\Kjk_comp_class::where("company_id", Session::get("company_id"))->pluck("name", "id");

        $user_approve = User::whereIn("id", $transfer_data->pluck("approved_by"))->pluck("name", "id");

        $view = view("_personel.employee_table.detail", compact("personel", "_estat", "user_approve", "transfer_data", "tfMaster", "reg",'offence', 'documents', "countries", "tax_status", "banks", "departements", 'emp_status', "emp_asset", 'roles', 'assets', "loc", "position", "religion", "profile", 'license', "mcu", "marital_status", "gender", "blood_type", "families", "education", "degree", "master", 'experience', 'languages', 'language'));

        return json_encode([
            "view" => $view->render()
        ]);
    }

    function profile_store(Request $request){

        $personel = Personel::find($request->id);

        $profile = Profile::where("user_id", $personel->id)->first();
        if(empty($profile)){
            $profile = new Profile();
            $profile->user_id = $personel->id;
        }

        $profile->identity_type = $request->identity_type;
        $profile->identity_number = $request->identity_number;
        $profile->citizenship = $request->citizenship;
        $profile->marital_status = $request->marital_status;
        $profile->religion = $request->religion;
        $profile->gender = $request->gender;
        $profile->blood_type = $request->blood_type;
        $profile->height = $request->height;
        $profile->weight = $request->weight;
        $profile->npwp = $request->npwp;
        $profile->bpjskes = $request->bpjs_kes;
        $profile->bpjstk = $request->bpjs_tk;
        $profile->identity_address = $request['identity']['address'] ?? "-";
        $profile->identity_zip_code = $request['identity']['zip_code'] ?? "-";
        $profile->identity_country = $request['identity']['country'] ?? "-";
        $profile->identity_city = $request['identity']['city'] ?? "-";
        $profile->identity_province = $request['identity']['province'] ?? "-";
        $profile->resident_address = $request['resident']['address'] ?? "-";
        $profile->resident_zip_code = $request['resident']['zip_code'] ?? "-";
        $profile->resident_country = $request['resident']['country'] ?? "-";
        $profile->resident_city = $request['resident']['city'] ?? "-";
        $profile->resident_province = $request['resident']['province'] ?? "-";

        $file = $request->file("file");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$personel->id."_identity_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $profile->identity_file = "media/attachments/$newName";
            }
        }

        $file = $request->file("upload_npwp");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$personel->id."_npwp_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $profile->npwp_file = "media/attachments/$newName";
            }
        }

        $file = $request->file("upload_bpjs_kes");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$personel->id."_bpjskes_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $profile->bpjskes_file = "media/attachments/$newName";
            }
        }

        $file = $request->file("upload_bpjs_tk");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$personel->id."_bpjstk_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $profile->bpjstk_file = "media/attachments/$newName";
            }
        }

        $profile->save();

        $personel->emp_lahir = $request->emp_lahir;
        $personel->emp_tmpt_lahir = $request->emp_tmpt_lahir;
        $personel->phone = $request->phone;
        $personel->email = $request->personal_email;
        $personel->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Private data has been updated",
                "bg" => "bg-success"
            ],
            "drawer" => $personel->id
        ]);
    }

    function resign(Request $request){
        $personel = Personel::find($request->id);

        $resign = new Personel_resign();
        $resign->emp_id = $request->id;
        $resign->resign_date = $request->resign_date;
        $resign->resign_type = $request->resign_type;
        $resign->resign_reason = $request->resign_reason;
        $resign->blacklist_flag = $request->blacklist_flag;
        $resign->remarks = $request->remarks;
        $resign->must_approved_by = $request->must_approved_by;
        $resign->created_by = Auth::id();
        $resign->company_id = Session::get("company_id");
        $resign->save();

        if($resign->resign_date < date("Y-m-d")){
            $personel->expire = $resign->resign_date;
            $personel->save();
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "$personel->emp_name has been resigned",
                "bg" => "bg-danger"
            ]
        ]);
    }

    function offence(Request $request){
        $personel = Personel::find($request->id);

        $offence = new Personel_offence();
        $offence->emp_id = $personel->id;
        $offence->offence_reason = $request->offence;
        $offence->given_by = $request->given_by;
        $offence->start_date = $request->start_date;
        $offence->end_date = $request->end_date;
        $offence->remarks = $request->remarks;
        $offence->reference = $request->reference;
        $offence->created_by = Auth::id();
        $offence->company_id = Session::get("company_id");
        $file = $request->file("file");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$personel->id."_offence_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $offence->lampiran = "media/attachments/$newName";
            }
        }

        $offence->save();

        $with = [
            "toast" => [
                "message" => "The offense has been given to $personel->emp_name",
                "bg" => "bg-danger"
            ]
        ];

        $drawer = $request->drawer ?? 0;

        if($drawer != 0){
            $with['drawer'] = $offence->emp_id;
        }

        return redirect()->back()->with($with);
    }

    function transfer(Request $request){

        $dataRequest = [];

        if($request->batch == 1){
            $personel = Personel::whereIn("id", $request->emp_id)->get();

            $update = $request->update;

            foreach($personel as $pr){
                foreach($update as $key => $item){
                    $oldId = null;
                    if($key == "job_type"){
                        $oldId = $pr->job_type->id ?? null;
                    } elseif($key == "job_level"){
                        $oldId = $pr->job_level->id ?? null;
                    } elseif($key == "job_grade"){
                        $oldId = $pr->job_grade->id ?? null;
                    } elseif($key == "employee_status"){
                        $oldId = $pr->employee_status->id ?? null;
                    } elseif($key == "position"){
                        $oldId = $pr->position->id ?? null;
                    } elseif($key == "workgroup"){
                        $oldId = $pr->reg->workgroup ?? null;
                    } elseif($key == "departement"){
                        $oldId = $pr->user->uac_departement ?? null;
                    } elseif($key == "location"){
                        $oldId = $pr->user->location ?? null;
                    } elseif($key == "acting_position"){
                        $oldId = $pr->user->uac_role ?? null;
                    }
                    $history = new Personel_history();
                    $history->personel_id = $pr->id;
                    $history->type = $key;
                    $history->old = $oldId;
                    $history->new = $item['new'] == "-" ? null : $item['new'];
                    $history->start_date = $item['start_date'];
                    $history->end_date = $item['end_date'];
                    $history->reason = $item['reason'];
                    $history->reference = $item['reference'];
                    $bypass = $item['bypass_approve'] ?? null;
                    if(!empty($bypass)){
                        $history->approved_at = date("Y-m-d H:i:s");
                        $history->approved_by = "";
                    } else {
                        $history->must_approved_by = $item['approved_by'];
                    }
                    $history->created_by = Auth::id();
                    $history->company_id = Session::get("company_id");
                    $history->save();
                    $dataRequest[] = $history;
                }
            }
        } else {
            $pr = Personel::find($request->id);

            $update = $request->update;
            foreach($update as $key => $item){
                $oldId = null;
                if($key == "job_type"){
                    $oldId = $pr->job_type->id ?? null;
                } elseif($key == "job_level"){
                    $oldId = $pr->job_level->id ?? null;
                } elseif($key == "job_grade"){
                    $oldId = $pr->job_grade->id ?? null;
                } elseif($key == "employee_status"){
                    $oldId = $pr->employee_status->id ?? null;
                } elseif($key == "position"){
                    $oldId = $pr->position->id ?? null;
                } elseif($key == "workgroup"){
                    $oldId = $pr->reg->workgroup ?? null;
                } elseif($key == "departement"){
                    $oldId = $pr->user->uac_departement ?? null;
                } elseif($key == "location"){
                    $oldId = $pr->user->location ?? null;
                } elseif($key == "acting_position"){
                    $oldId = $pr->user->uac_role ?? null;
                }

                $history = new Personel_history();
                $history->personel_id = $pr->id;
                $history->type = $key;
                $history->old = $oldId;
                $history->new = $item['new'] == "-" ? null : $item['new'];
                $history->start_date = $item['start_date'];
                $history->end_date = $item['end_date'];
                $history->reason = $item['reason'];
                $history->reference = $item['reference'];
                $bypass = $item['bypass_approve'] ?? null;
                if(!empty($bypass)){
                    $history->approved_at = date("Y-m-d H:i:s");
                    $history->approved_by = "";
                } else {
                    $history->must_approved_by = $item['approved_by'];
                }
                $history->created_by = Auth::id();
                $history->company_id = Session::get("company_id");
                $history->save();
                $dataRequest[] = $history;
            }
        }

        $dataRequest = collect($dataRequest)->whereNotNull("approved_at");

        $recController = new \App\Http\Controllers\PersonelRequest();
        $recController->implement($dataRequest);

        return redirect()->back()->with([
            "toast" => [
                "message" => "Transfer employee request has been made and waiting for approval.",
                "bg" => "bg-success"
            ],
        ]);
    }

    function reactive(Request $request){
        $personel = Personel::find($request->id);

        $history = new Personel_history();
        $history->personel_id = $personel->id;
        $history->type = "reactive";
        $history->old = $personel->expire;
        $history->new = null;
        $history->start_date = date("Y-m-d");
        $history->created_by = Auth::id();
        $history->save();

        $personel->expire = null;
        $personel->save();

        Personel_resign::where("emp_id", $personel->id)->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "$personel->emp_name has been reactived",
                "bg" => "bg-success"
            ],
        ]);
    }

    function document_store(Request $request){
        $file = $request->file("lampiran");

        $pId = $request->personel_id;

        if(!empty($file)){
            $d = date("YmdHis");
            $name = $file->getClientOriginalName();
            $newName = $d."_$pId"."_$name";
            if($file->move($this->dir, $newName)){
                $doc = new \App\Models\Hrd_cv_u();
                $doc->user_id = $request->personel_id;
                $doc->expiry_date = $this->sortDate($request->expired_date);
                $doc->category_id = $request->category;
                $doc->cv_name = $name;
                $doc->cv_address = "media/attachments/$newName";
                $doc->company_id = Session::get('company_id');
                $doc->created_by = Auth::id();
                $doc->save();
            }
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Document has been added",
                "bg" => "bg-success"
            ],
            "drawer" => $pId
        ]);
    }

    function family_store(Request $request){

        $family = Family::findOrNew($request->id);
        $family->user_id = $request->personel_id;
        $family->personel = 1;
        $family->name = $request->name;
        $family->hubungan = $request->hubungan;
        $family->marital_id = $request->marital_id;
        $family->jenis_kelamin = $request->gender;
        $family->tgl_lahir = $request->tgl_lahir;
        $family->no_telp = $request->no_telp;
        $family->emergency_contact = $request->emergency ?? 0;
        $file = $request->file("lampiran");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$family->user_id."_family_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $family->lampiran = "media/attachments/$newName";
            }
        }
        $family->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Family has been added",
                "bg" => "bg-success"
            ],
            "drawer" => $family->user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function family_delete($id, Request $request){
        $family = Family::find($id);
        $user_id = $family->user_id;
        $family->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Family has been deleted",
                "bg" => "bg-danger"
            ],
            "drawer" => $user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function education_store(Request $request){

        $edu = Education::findOrNew($request->id);
        $edu->user_id = $request->personel_id;
        $edu->personel = 1;
        $edu->degree = $request->degree;
        $edu->field_of_study = $request->field_of_study;
        $edu->grade = $request->grade;
        $edu->school_name = $request->school_name;
        $edu->year_graduate = $request->year_graduate;
        $file = $request->file("lampiran");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$edu->user_id."_education_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $edu->lampiran = "media/attachments/$newName";
            }
        }
        $edu->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Education has been added",
                "bg" => "bg-success"
            ],
            "drawer" => $edu->user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function education_delete($id, Request $request){
        $education = Education::find($id);
        $user_id = $education->user_id;
        $education->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Education has been deleted",
                "bg" => "bg-danger"
            ],
            "drawer" => $user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function work_store(Request $request){
        $data = Work_exp::findOrNew($request->id);
        $data->user_id = $request->personel_id;
        $data->personel = 1;
        $data->company = $request->company;
        $data->salary = str_replace(",", "", $request->salary);
        $data->position = $request->position;
        $data->job_type = $request->job_type;
        $data->industry = $request->industry;
        $data->start_date = $request->start_year."-".$request->start_month;
        if(empty($request->still)){
            $data->end_date = $request->end_year."-".$request->end_month;
        }
        $data->still = $request->still ?? 0;
        $data->descriptions = $request->descriptions;
        $data->reference = $request->reference;
        $data->phone = $request->phone;
        $data->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Working experience has been added",
                "bg" => "bg-success"
            ],
            "drawer" => $data->user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function work_delete($id, Request $request){
        $data = Work_exp::find($id);
        $user_id = $data->user_id;
        $data->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Working experience has been deleted",
                "bg" => "bg-danger"
            ],
            "drawer" => $user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function language_store(Request $request){
        $data = Language::findOrNew($request->id);
        $data->user_id = $request->personel_id;
        $data->personel = 1;
        $data->language = $request->language;
        $data->writing = $request->writing;
        $data->speaking = $request->speaking;
        $data->reading = $request->reading;
        $data->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Language has been added",
                "bg" => "bg-success"
            ],
            "drawer" => $data->user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function language_delete($id, Request $request){
        $data = Language::find($id);
        $user_id = $data->user_id;
        $data->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Language has been deleted",
                "bg" => "bg-danger"
            ],
            "drawer" => $user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function mcu_store(Request $request){
        $data = Mcu::findOrNew($request->id);
        $data->user_id = $request->personel_id;
        $data->descriptions = $request->descriptions;
        $data->year = $request->year;
        $file = $request->file("lampiran");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$data->user_id."_mcu_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $data->lampiran = "media/attachments/$newName";
            }
        }
        $data->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Medical record has been added",
                "bg" => "bg-success"
            ],
            "drawer" => $data->user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function mcu_delete($id, Request $request){
        $data = Mcu::find($id);
        $user_id = $data->user_id;
        $data->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Medical record has been deleted",
                "bg" => "bg-danger"
            ],
            "drawer" => $user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function license_store(Request $request){
        $data = License::findOrNew($request->id);
        $data->user_id = $request->personel_id;
        $data->personel = 1;
        $data->name = $request->name;
        $data->no_lisensi = $request->number;
        $data->tgl_kadaluarsa = $request->exp_date;
        $file = $request->file("lampiran");
        if(!empty($file)){
            $d = date("YmdHis");
            $newName = $d."_".$data->user_id."_mcu_".$file->getClientOriginalName();
            if($file->move($this->dir, $newName)){
                $data->lampiran = "media/attachments/$newName";
            }
        }
        $data->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "License has been added",
                "bg" => "bg-success"
            ],
            "drawer" => $data->user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function license_delete($id, Request $request){
        $data = License::find($id);
        $user_id = $data->user_id;
        $data->delete();

        return redirect()->back()->with([
            "toast" => [
                "message" => "License has been deleted",
                "bg" => "bg-danger"
            ],
            "drawer" => $user_id,
            "ess_section" => $request->section ?? null
        ]);
    }

    function asset_save($emp_id, $data, $type){
        $id = $data['id'] ?? null;
        $asset = Personel_asset::findOrNew($id);
        $asset->emp_id = $emp_id;
        $asset->asset_id = $data['asset'];
        $asset->descriptions = $data['description'];
        $asset->serial_num = $data['serial_num'] ?? null;
        $asset->type = $type;
        $asset->date_received = $data['date'];
        if(empty($id)){
            $asset->company_id = Session::get("company_id");
            $asset->created_by = Auth::id();
        }
        $asset->save();

        return $asset;
    }

    function asset_store(Request $request){
        $data['asset'] = $request->asset;
        $data['description'] = $request->description;
        $data['date'] = $request->date;
        $data['serial_num'] = $request->serial_num ?? null;
        $data['id'] = $request->id ?? null;
        $type = $request->type;
        $asset = $this->asset_save($request->emp_id, $data, $type);

        return redirect()->back();
    }

    function update_data(Request $request){
        $type = $request->type;
        $personel = Personel::find($request->id);
        if($type == "mandatory"){
            $personel->acting_position_id = $request->acting_position_id;
            $personel->emp_name = $request->emp_name;
            $personel->position_id = $request->position;
            $personel->job_level_id = $request->job_level;
            $personel->join_date = $this->sortDate($request->join_date);
            $personel->save();

            $user = $personel->user;
            if(!empty($user)){
                $user->uac_departement = $request->departement;
                $user->uac_location = $request->location;
                $user->email = $request->email;
                $user->save();
            }
        }

        if($type == "attendance"){
            $reg = Att_employee_registration::where("emp_id", $personel->id)->first();
            if(!empty($reg)){
                $reg->id_card = $request->card_id;
                $reg->save();
            }

            $personel->temp_id_card = $request->card_id;
            $personel->save();
        }

        if($type == "roles"){
            $user = $personel->user;
            $user->uac_role = $request->role;
            $user->save();
        }

        if($type == "office"){
            $estatus = Personel_employee_status::find($request->employee_status);
            $user = $personel->user;
            $personel->position_id = $request->position;
            $personel->class_id = $request->class;
            $personel->job_grade_id = $request->job_grade;
            $personel->employee_status_id = $estatus->id ?? null;
            if(($estatus->end_date ?? 0) == 1){
                $personel->employee_status_mutation_end = $this->sortDate($request->employee_status_date);
            } else {
                $personel->employee_status_mutation_end = null;
            }
            $dir = str_replace("attachments", "employee_attachment", $this->dir);
            $file = $request->file("file");
            if(!empty($file)){
                $name = date("YmdHis")."_".$file->getClientOriginalName();
                if($file->move($dir, $name)){
                    $personel->office_file = "media/employee_attachment/$name";
                }
            }
            $personel->save();

            $user->uac_location = $request->location;
            $user->save();
        }

        if($type == "payroll_summary"){
            $personel->tax_status_id = $request->tax_status;
            $personel->tax_type = $request->tax_type;
            $personel->payroll_currency = $request->currency;
            $personel->payroll_payment_method = $request->payment_method;
            $personel->payroll_payment_schedule = $request->payment_schedule;
            $personel->payroll_active_period_date = $this->sortDate($request->active_period_date);
            $personel->save();
        }

        if($type == "payroll_personal"){
            $personel->personal_bank_id = $request->bank_name;
            $personel->personal_bank_number = $request->account_number;
            $personel->personal_bank_name = $request->account_name;
            $personel->save();
        }

        if($type == "payroll_company"){
            $personel->company_bank_id = $request->bank_name;
            $personel->company_bank_number = $request->account_number;
            $personel->company_bank_name = $request->account_name;
            $personel->save();
        }

        return redirect()->back()->with([
            "toast" => [
                "message" => "Data personel updated",
                'bg' => "bg-success"
            ],
            'drawer' => "$personel->id"
        ]);
    }

    function formal_letter($id){
        $personel = Personel::find($id);

        $template = \App\Models\Hrd_contract_template::where('company_id', Session::get("company_id"))->get();

        $myFl = \App\Models\Hrd_contract_employee::where("emp_id", $personel->id)
            ->orderBy("created_at", "desc")->get();

        $user_name = User::whereIn("id", $myFl->pluck("created_by"))->pluck("name", "id");

        $flRequest = \App\Models\Personel_employment_letter_request::where("emp_id", $personel->id)
            ->orderBy("created_at", "desc")
            ->get();

        $lname = [1=> "Surat Keterangan Kerja Standart", "Surat Keterangan Kerja KITAS", "Surat Keterangan Kerja Agunan"];

        $view = view("_personel.employee_table._formal_letter", compact("personel", 'template', "user_name", 'myFl', "flRequest", "lname"))->render();

        return json_encode([
            "view" => $view
        ]);
    }

    function formal_letter_post(Request $request){
        $entry = \App\Models\Hrd_contract_employee::findOrNew($request->id_entry);
        $entry->contents = json_encode($request->fld);
        if(empty($request->id_entry)){
            $entry->template_id = $request->template_id;
            $entry->emp_id = $request->emp_id;
            $entry->created_by = Auth::user()->id;
            $entry->company_id = Session::get('company_id');
        }
        $entry->save();

        return redirect()->back()->with([
            "toast" => [
                "message" => "Formal Letter has been added",
                "bg" => "bg-success"
            ],
            "fl" => $request->emp_id
        ]);
    }

    function export(Request $request){
        $resigned = Personel_resign::where("company_id", Session::get("company_id"))
            ->where("resign_date", "<=", date("Y-m-d"))
            ->get();

        $personel = Personel::where("company_id", Session::get("company_id"))
            ->where("emp_name", "!=", "")
            ->whereNotIn("id", $resigned->pluck("emp_id"))
            ->orderBy("emp_name")
            ->get();

        $profile = Profile::whereIn("user_id", $personel->pluck("id"))->get();
        $empProfile = [];
        foreach($profile as $item){
            $empProfile[$item->user_id] = $item;
        }

        $user = User::whereIn("emp_id", $personel->pluck("id"))->get();
        $empUser = [];
        foreach($user as $item){
            $empUser[$item->emp_id] = $item;
        }

        $master['job_level'] = \App\Models\Kjk_comp_job_level::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['job_level_id'] = \App\Models\Kjk_comp_job_level::where("company_id", Session::get("company_id"))->pluck("record_id", "id");

        $master['job_type'] = Kjk_comp_job_grade::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['job_type_id'] = Kjk_comp_job_grade::where("company_id", Session::get("company_id"))->pluck("record_id", "id");

        $master['position'] = Kjk_comp_position::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['position_id'] = Kjk_comp_position::where("company_id", Session::get("company_id"))->pluck("record_id", "id");

        $master['class'] = \App\Models\Kjk_comp_class::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['class_id'] = \App\Models\Kjk_comp_class::where("company_id", Session::get("company_id"))->pluck("record_id", "id");

        $master['employee_status'] = Personel_employee_status::where("company_id", Session::get("company_id"))->pluck("label", "id");

        $master['departement'] = Kjk_comp_departement::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['departement_id'] = Kjk_comp_departement::where("company_id", Session::get("company_id"))->pluck("record_id", "id");

        $master['location'] = Asset_wh::office()->where("company_id", Session::get("company_id"))->pluck('name', 'id');

        $master['roles'] = Kjk_uac_role::where("company_id", Session::get("company_id"))->pluck("name", "id");
        $master['gender'] = \App\Models\Master_gender::pluck("name", "id");
        $master['religion'] = \App\Models\Master_religion::pluck("name", "id");
        $master['marital_status'] = \App\Models\Master_marital_status::pluck("name", "id");
        $master['blood'] = \App\Models\Personel_blood_type::pluck("label", "id");
        $master['company'] = \App\Models\ConfigCompany::pluck("company_name", "id");

        $data = [];
        foreach($personel as $item){

            $_profile = $empProfile[$item->id] ?? [];
            $_user = $empUser[$item->id] ?? [];

            $age = "";
            if(!empty($item->emp_lahir)){
                $age1 = date_create($item->emp_lahir);
                $age2 = date_create(date("Y-m-d"));
                $age3 = date_diff($age2, $age1);
                $age = $age3->format("%y");
            }
            $tahunKerja = 0;
            $bulanKerja = 0;
            if(!empty($item->join_date)){
                $age1 = date_create($item->join_date);
                $age2 = date_create(date("Y-m-d"));
                $age3 = date_diff($age2, $age1);
                $tahunKerja = $age3->format("%y");
                $bulanKerja = $age3->format("%m");
            }

            $col = [];
            $col[] = $master['company'][$item->company_id] ?? sprintf("%03d", $item->company_id);
            $col[] = "'$item->emp_id";
            $col[] = $item->emp_name;
            $col[] = $_profile->identity_address ?? "";
            $col[] = $_profile->resident_address ?? "";
            $col[] = $_profile->identity_city ?? "";
            $col[] = $_profile->resident_city ?? "";
            $col[] = $_profile->identity_zip_code ?? "";
            $col[] = $_profile->resident_zip_code ?? "";
            $col[] = "";
            $col[] = "";
            $col[] = "'$item->phone";
            $col[] = $_user->email ?? $item->email;
            $col[] = $item->email;
            $col[] = $item->emp_tmpt_lahir;
            $col[] = empty($item->emp_lahir) ? "" : "'".date("d-m-Y", strtotime($item->emp_lahir));
            $col[] = $age;
            $col[] = $master['gender'][$_profile->gender ?? ""] ?? "";
            $col[] = $master['blood'][$_profile->blood_type ?? ""] ?? "";
            $col[] = $_profile->height ?? "";
            $col[] = $_profile->weight ?? "";
            $col[] = $_profile->citizen ?? "";
            $col[] = $master['religion'][$_profile->religion ?? ""] ?? "";
            $col[] = $master['marital_status'][$_profile->marital_status ?? ""] ?? "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = empty($item->join_date) ? "" : "'".date("d-m-Y", strtotime($item->join_date));
            $col[] = "";
            $col[] = $tahunKerja;
            $col[] = $bulanKerja;
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "'".($_profile->npwp ?? "");
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "0";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = $master['departement_id'][$_user->uac_departement ?? ""] ?? $_user->uac_departement ?? "";
            $col[] = $master['departement'][$_user->uac_departement ?? ""] ?? "";
            $col[] = empty($_user->uac_departement_mutation_date ?? null) ? "" : "'".date("d-m-Y", strtotime($_user->uac_departement_mutation_date));
            for($i = 1; $i <= 15; $i++){
                $col[] = "";
                $col[] = "";
            }
            $col[] = $_user->uac_location ?? "";
            $col[] = $master['location'][$_user->uac_location ?? ""] ?? "";
            $col[] = empty($_user->uac_location_mutation_date ?? null) ? "" : "'".date("d-m-Y", strtotime($_user->uac_location_mutation_date));

            $col[] = $master['job_type_id'][$item->job_grade_id] ?? $item->job_grade_id;
            $col[] = $master['job_type'][$item->job_grade_id ?? ""] ?? "";
            $col[] = empty($item->job_grade_mutation_date ?? null) ? "" : "'".date("d-m-Y", strtotime($item->job_grade_mutation_date));

            $col[] = $item->employee_status_id ?? "";
            $col[] = $master['location'][$item->employee_status_id ?? ""] ?? "";
            $col[] = empty($item->employee_status_mutation_start ?? null) ? "" : "'".date("d-m-Y", strtotime($item->employee_status_mutation_start));
            $col[] = empty($item->employee_status_mutation_end ?? null) ? "" : "'".date("d-m-Y", strtotime($item->employee_status_mutation_end));

            $col[] = $master['class_id'][$item->class_id] ?? $item->class_id;
            $col[] = $master['class'][$item->class_id ?? ""] ?? "";
            $col[] = empty($item->class_mutation_date ?? null) ? "" : "'".date("d-m-Y", strtotime($item->class_mutation_date));

            $col[] = $master['position_id'][$item->position_id] ?? $item->position_id;
            $col[] = $master['position'][$item->position_id ?? ""] ?? "";
            $col[] = empty($item->position_mutation_date ?? null) ? "" : "'".date("d-m-Y", strtotime($item->position_mutation_date));

            $col[] = "";
            $col[] = "";
            $col[] = "Active";
            $col[] = "";
            $col[] = "";
            $col[] = "";
            $col[] = $master['job_level_id'][$item->job_type_id] ?? $item->job_type_id;
            $col[] = $master['job_level'][$item->job_type_id ?? ""] ?? "";
            $col[] = "";
            $data[] = $col;
        }

        return Excel::download(new DataEmpExport($data, "Business Unit"), 'business-unit.xlsx');
    }
}
