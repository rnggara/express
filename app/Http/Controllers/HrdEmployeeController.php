<?php

namespace App\Http\Controllers;

//use Faker\Provider\File;
use DB;
use Artisan;
use Session;
use App\Models\User;
use App\Models\Hrd_cv;
use App\Models\Asset_wh;
use App\Models\Division;
use App\Models\Hrd_cv_u;
use App\Models\Asset_item;
use App\Models\General_do;
use App\Models\Pref_email;
use App\Models\Asset_qty_wh;
use App\Models\Hrd_employee;
use Illuminate\Http\Request;
use App\Models\ConfigCompany;
use App\Models\Rms_divisions;
use App\Models\UserPrivilege;
use App\Models\RolePrivilege;
use App\Models\Master_var_emp;
use App\Models\Preference_ppe;
use App\Helpers\FileManagement;
use App\Models\File_Management;
use App\Models\Hrd_employee_ppe;
use App\Models\General_do_detail;
use App\Models\Hrd_employee_loan;
use App\Models\Hrd_employee_type;
use App\Models\Hrd_salary_update;
use App\Models\Preference_config;
use App\Models\Hrd_salary_archive;
use App\Models\Hrd_att_transaction;
use App\Models\Hrd_employee_history;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Hrd_contract_employee;
use App\Models\Master_variables_model;
use App\Models\Hrd_employee_history_edit;
use App\Models\Hrd_employee_loan_payment;
use App\Models\Master_banks;
use App\Models\Hrd_employee_bank;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\ConfigCompany as Config_Company;
use App\Models\Hrd_employee_pension;
use App\Models\Hrd_salary_history;
use App\Models\RoleDivision;
use App\Models\Role;
use App\Models\Career_path;
use App\Models\Hrd_employee_career_update;
use App\Models\Master_religion;
use App\Models\Master_educations;
use App\Models\Master_company;
use App\Models\Master_marital_status;
use Mpdf\Mpdf;
use App\Models\Hrd_severance;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CompanyExport;
use App\Models\Hrd_employee_attach_category;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailConfig;

class HrdEmployeeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function getEmp(Request $request){
        $type = $request->type;
        $divisions = Rms_divisions::where('name','not like','%admin%')
            ->get();
        if ($type == 0){
            $employees = Hrd_employee::whereNull('finalize_expel')
                ->where('company_id', Session::get('company_id'))
                ->orderBy('emp_name')
                ->get();
        } else {
            if ($type == -1) {
                $employees = Hrd_employee::whereNotNull('finalize_expel')
                    ->where('company_id', Session::get('company_id'))
                    ->orderBy('emp_name')
                    ->get();
            } else {
                $employees = Hrd_employee::whereNull('finalize_expel')
                    ->where('emp_type', $type)
                    ->where('company_id', Session::get('company_id'))
                    ->orderBy('emp_name')
                    ->get();
            }
        }

        $divName = [];
        $divName = $divisions->pluck("name", 'id');
        // foreach ($divisions as $key => $val){
        //     $divName['name'][$val->id] = $val->name;
        // }

        $emptypes = Hrd_employee_type::all();
        $emp_type = $emptypes->pluck("name", 'id');

        $file_isset = [];

        $row = [];
        $emp = [];

        $ct = Hrd_contract_employee::where('company_id', Session::get("company_id"))
            ->whereNull('approved_by')
            ->get();
        $ct_emp = [];
        $ctid = [];
        foreach($ct as $item){
            if(empty($item->approved_at)){
                $ct_emp[$item->emp_id] = $item->links;
            } else {
                $ctid[$item->id] = 1;
            }
        }

        $canDelete = 0;
        $rc = Session::get('company_user_rc');
        if(!empty($rc)){
            if(isset($rc['employee'])){
                if(isset($rc['employee']['approvedir'])){
                    $canDelete = 1;
                }
            }
        }

        $log_directory = public_path('media/employee_attachment');

        $fname = scandir($log_directory, 1);

        $mod = \App\Models\Module::all()->pluck('name', 'id');

        $enum =1;

        $users = User::whereIn("emp_id", $employees->pluck("id"))->get();

        $user_email = $users->pluck("email", "emp_id");
        $user_id = $users->pluck("id", "emp_id");

        $lastLogin = DB::table("activity_log")
            ->whereIn('causer_id', $users->pluck("id"))
            ->where("log_name", "login")
            ->orderBy("created_at", 'desc')
            ->get();
        $userLogin = [];
        foreach($lastLogin as $item){
            $userLogin[$item->causer_id][] = $item->created_at;
        }

        foreach ($employees as $key => $value){
            $nik = explode("-", $value->emp_id);
            $status = substr(end($nik),0,1);
            $et = $emptypes->where("id", $value->emp_type)->first();
            if(!empty($et)){
                $action = (isset($mod[$value->rms_id])) ? $mod[$value->rms_id] : "payroll_".str_replace(" ","_", strtolower($et->name));
                if(isset($rc[$action])){
                    if($rc[$action]['access'] == 1){

                    }
                }

                $last = "-";
                $uId = $user_id[$value->id] ?? -1;
                if(isset($userLogin[$uId])){
                    $last = date("d/m/Y H:i", strtotime($userLogin[$uId][0]));
                }

                $emp['no'] = '<div class="form-check">
                <input class="form-check-input" name="select" data-name="'.$value->emp_name.'" data-check data-emp="'.$value->id.'" type="checkbox" value="1" id="" />
            </div>';
                $emp['emp_type'] = $emp_type[$value->emp_type] ?? "-";
                $emp['emp_id'] = $value->emp_id;
                $emp['email'] = $user_email[$value->id] ?? $value->email;
                $emp['last_login'] = $last;
                $emp['job_level'] = "-";
                $emp['branch'] = "-";
                if ($status != 'K' && $status != 'C'){
                    $emp['status'] = "<center><label class='text-center text-success'>Pegawai Tetap</label></center>";
                    $btnBg = ($type == - 1) ? "btn-danger" : "btn-primary";
                } else {
                    if ($value->expire == null || $value->expire == "0000-00-00"){
                        $btnBg = ($type == - 1) ? "btn-danger" : "btn-primary";
                        if(isset($ct_emp[$value->id])){
                            $emp['status']  = "<center><button type='button' onclick='_link(this)' data-toggle='tooltip' data-link='".$ct_emp[$value->id]."' title='Click here to copy the link' class='btn btn-info btn-sm'>Waiting approval</button></center>";
                        } else {
                            $emp['status'] = "<center>";
                            // $emp['status'] .= "<button type='button' data-target='#modalcontract-".$value->id."' data-toggle='modal' class='btn btn-sm btn-success'>
                            //                             <i class='fa fa-plus icon-nm'></i> [add contract]
                            //                         </button><br><br>";
                            $emp['status'] .= "<button type='button' data-target='#modalGenerate' onclick='_contract($value->id)' data-toggle='modal' class='btn btn-sm btn-success'>
                                                    <i class='fa fa-plus icon-nm'></i> create
                                                </button>";
                            $emp['status'] .= "</center>


                                            ";

                                        //     <div class='modal fade' id='modalcontract-".$value->id."' tabindex='-1' role='dialog' aria-labelledby='modalcontract-".$value->id."' aria-hidden='true'>
                                        //     <div class='modal-dialog modal-dialog-centered modal-xl' role='document'>
                                        //         <div class='modal-content'>
                                        //             <div class='modal-header'>
                                        //                 <h5 class='modal-title' id='exampleModalLabel'>Add Contract</h5>
                                        //                 <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        //                     <i aria-hidden='true' class='ki ki-close'></i>
                                        //                 </button>
                                        //             </div>
                                        //             <form method='post' action='".route('employee.addcontract')."' enctype='multipart/form-data'>
                                        //                 <input type='hidden' name='_token' value='".csrf_token()."'>
                                        //                 <input type='hidden' name='id' value='".$value->id."'>
                                        //                 <div class='modal-body'>
                                        //                     <br>
                                        //                     <h4>Upload a contract for $value->emp_name</h4><hr>
                                        //                     <div class='row'>
                                        //                         <div class='form col-md-12'>
                                        //                             <div class='form-group'>
                                        //                                 <label>Document</label>
                                        //                                 <input type='file' class='form-control' name='contract_file' required id='contract_file' placeholder=''>
                                        //                             </div>
                                        //                             <div class='form-group'>
                                        //                                 <label>This contract expires on</label>
                                        //                                 <input type='date' class='form-control' required name='date_exp' placeholder='' />
                                        //                             </div>
                                        //                             <div class='form-group'>
                                        //                                 <label></label>
                                        //                                 <label or='as' class='control-label'>
                                        //                                     <input type='radio' name='opt' value='1' id='opt' checked />
                                        //                                     Renew Contract
                                        //                                 </label>
                                        //                                 &nbsp;&nbsp;
                                        //                                 <label for='int' class='control-label'>
                                        //                                     <input type='radio' name='opt' value='2' id='opt' />
                                        //                                     Permanent Employee
                                        //                                 </label>
                                        //                             </div>

                                        //                         </div>
                                        //                     </div>
                                        //                 </div>

                                        //                 <div class='modal-footer'>
                                        //                     <button type='button' class='btn btn-light-primary font-weight-bold' data-dismiss='modal'>Close</button>
                                        //                     <button type='submit' name='submit' value='1' class='btn btn-primary font-weight-bold'>
                                        //                         <i class='fa fa-check'></i>
                                        //                         Add Contract</button>
                                        //                 </div>
                                        //             </form>
                                        //         </div>
                                        //     </div>
                                        // </div>
                        }
                    } else {
                        $date2 = date('Y-m-d', strtotime('-1 month', strtotime($value->expire)));
                        $date1 = date('Y-m-d');

                        $_date1=date_create($date2);
                        $_date2=date_create($date1);
                        $_diff=date_diff($_date2,$_date1);
                        $_months = round(intval($_diff->format('%R%a'))/ 30);

                        $diff = abs(strtotime($date2) - strtotime($date1));
                        $years = floor($diff / (365*60*60*24));
                        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                        if ($_months <= 1) {
                            $bg = "text-danger";
                            $btnBg = "btn-danger";
                        } elseif($_months > 1 && $_months <= 6) {
                            $bg = ($type == - 1) ? "text-danger" : "text-warning";
                            $btnBg = ($type == - 1) ? "btn-danger" : "btn-warning";
                        } else {
                            $bg = ($type == - 1) ? "text-danger" : "text-primary";
                            $btnBg = ($type == - 1) ? "btn-danger" : "btn-primary";
                        }
                        $yeari = substr($value->expire, 0, 4);

                        $monthn = date("m");
                        $monthi = substr($value->expire,5,2);
                        $selmonth = $monthi - $monthn;

                        $contract = "";

                        $neednewcontract = 0;

                        $isctid = 0;

                        if(!empty($value->contract_file) && isset($file_isset[$value->contract_file])){
                            $_file = str_replace("public", "public_html", asset($file_isset[$value->contract_file]));
                            $f = str_replace(" ", "%20", $_file);
                            // $handle = @fopen($f, 'r');
                            if(in_array($f, $fname)){
                                $contract = "<a href='" . route('download', $value->contract_file) . "' class='btn btn-xs btn-icon btn-light-success' target='_blank'><i class='fa fa-download'></i></a>";
                                // clearstatcache();
                            } else {
                                $neednewcontract = 1;
                            }
                        } else {
                            if(empty($value->expire)){
                                $neednewcontract = 1;
                            } else {
                                $contract = "<a href='".route('hrd.contract.pdf', base64_encode($value->contract_file))."' class='btn btn-xs btn-icon btn-light-success' download target='_blank'><i class='fa fa-download'></i></a>";
                            }
                        }

                        $emp['status'] = "<center>
                                                <label class='$bg font-weight-bolder'>exp: ".$value->expire."</label>
                                                $contract
                                            </center>";

                        if ((((date("Y") == $yeari && $selmonth <= 1) || date("Y") > $yeari || (date("Y") < $yeari && $selmonth <= -11)) && $value->expire != "0000-00-00") || $neednewcontract){
                            $_lb = ($neednewcontract == 1) ? "renew contract" : "renew contract";
                            $expire = ($neednewcontract == 1) ? $value->expire : "";
                            if(isset($ct_emp[$value->id])){
                                $emp['status']  = "<center><button type='button' onclick='_link(this)' data-toggle='tooltip' data-link='".$ct_emp[$value->id]."' title='Click here to copy the link' class='btn btn-info btn-sm'>Waiting approval</button></center>";
                            } else {
                                $emp['status'] .= "<br><center>
                                                    <button type='button' data-target='#modalGenerate' onclick='_contract($value->id)' data-toggle='modal' class='btn btn-sm btn-success'>
                                                        <i class='fa fa-plus icon-nm'></i> [$_lb]
                                                    </button>
                                                </center>";

                                                // <div class='modal fade' id='modalrenewcontract-".$value->id."' tabindex='-1' role='dialog' aria-labelledby='modalrenewcontract-".$value->id."' aria-hidden='true'>
                                                //     <div class='modal-dialog modal-dialog-centered modal-xl' role='document'>
                                                //         <div class='modal-content'>
                                                //             <div class='modal-header'>
                                                //                 <h5 class='modal-title' id='exampleModalLabel'>Renew Contract</h5>
                                                //                 <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                //                     <i aria-hidden='true' class='ki ki-close'></i>
                                                //                 </button>
                                                //             </div>
                                                //             <form method='post' action='".route('employee.addcontract')."' enctype='multipart/form-data'>
                                                //                 <input type='hidden' name='_token' value='".csrf_token()."'>
                                                //                 <input type='hidden' name='id' value='".$value->id."'>
                                                //                 <div class='modal-body'>
                                                //                     <br>
                                                //                     <h4>Upload a contract for ".$value->emp_name."</h4><hr>
                                                //                     <div class='row'>
                                                //                         <div class='form col-md-12'>
                                                //                             <div class='form-group'>
                                                //                                 <label>Document</label>
                                                //                                 <input type='file' class='form-control' name='contract_file' required id='contract_file' placeholder=''>
                                                //                             </div>
                                                //                             <div class='form-group'>
                                                //                                 <label>This contract expires on</label>
                                                //                                 <input type='date' class='form-control' value='$expire' name='date_exp' required placeholder='' />
                                                //                             </div>
                                                //                             <div class='form-group'>
                                                //                                 <label></label>
                                                //                                 <label or='as' class='control-label'>
                                                //                                     <input type='radio' name='opt' value='1' id='opt' checked />
                                                //                                     ".ucwords($_lb)."
                                                //                                 </label>
                                                //                                 &nbsp;&nbsp;
                                                //                                 <label for='int' class='control-label'>
                                                //                                     <input type='radio' name='opt' value='2' id='opt' />
                                                //                                     Permanent Employee
                                                //                                 </label>
                                                //                             </div>

                                                //                         </div>
                                                //                     </div>
                                                //                 </div>

                                                //                 <div class='modal-footer'>
                                                //                     <button type='button' class='btn btn-light-primary font-weight-bold' data-dismiss='modal'>Close</button>
                                                //                     <button type='submit' name='submit' value='1' class='btn btn-primary font-weight-bold'>
                                                //                         <i class='fa fa-check'></i>
                                                //                         Add Contract</button>
                                                //                 </div>
                                                //             </form>
                                                //         </div>
                                                //     </div>
                                                // </div>
                                                // ";
                            }


                        }
                    }
                }
                $emp['emp_name'] = "<div class='d-flex align-items-center'>" .
                                "<div class='symbol symbol-40px me-5'><div class='symbol-label' style='background-image: url(".asset($value->user->user_img ?? "images/image_placeholder.png").")'></div></div>" .
                                "<div class='d-flex flex-column'><a href='".route('employee.detail',['id'=>$value->id])."' class='text-dark fw-bold'>".$value->emp_name."</a><span>$value->emp_id</span></div>" .
                                "</div>";
                $emp['cv'] = "<a href='".route('employee.grade.change',['type' => 'promote', 'id'=>$value->id])."' class='btn btn-warning btn-icon btn-sm'><i class='fa fa-angle-double-up icon-nm'></i></a>";
                $emp['document'] = "<a href='".route('employee.grade.change',['type' => 'demote', 'id'=>$value->id])."' class='btn btn-secondary btn-icon btn-sm'><i class='fa fa-angle-double-down icon-nm'></i></a>";
                if(empty($value->expel)){
                    $emp['quit'] = "<button type='button' onclick='_expel($value->id)' class='btn btn-sm btn-danger'><i class='fa fa-times icon-nm'></i> Expel</button>";
                } else {
                    $emp['quit'] = "<a href='#' class='btn btn-sm btn-danger'><i class='fa fa-times icon-nm'></i> Waiting for Fired Confirmation</a>";
                    if($canDelete == 1){
                        $emp['quit'] = "<a href='".route('employee.fired.confirmation',['id' =>$value->id])."' class='btn btn-sm btn-danger is-expel'><i class='fa fa-times icon-nm'></i> Confirm Fired</a>";
                    }

                }

                $freeze = (empty($value->freeze)) ? "Freeze" : "Unfreeze";

                $emp['training_point'] = " <button type='button' onclick=\"freeze_status($value->id, '$freeze')\" class='btn btn-primary btn-sm ".strtolower($freeze)."'>$freeze</button>";
                $emp['action'] = "<form method='post' action='".route('employee.delete',['id'=>$value->id])."'>
                                    <input type='hidden' name='_token' value='".csrf_token()."'>
                                        <button type='submit' class='btn btn-sm btn-icon btn-default' onclick='return confirm(\"Hapus data pegawai?\");'>
                                            <i class='fa fa-trash text-danger'></i>
                                        </button>
                                </form>";
                $pb = 0;
                if(!empty($value->n_performance_bonus)){
                    $pb = base64_decode($value->n_performance_bonus ?? "MA==");
                    $pb = $pb == "-" ? 0 : $pb;
                }
                $emp['pb'] = "<button type='button' onclick='edit_pb($value->id, ".$pb.")' class='btn btn-sm btn-primary'>".number_format($pb)."</button>";
                $checked_anywhere = $value->anywhere == 1 ? "checked" : "";
                $emp['anywhere'] = '<div class="d-flex justify-content-center"><div class="form-check form-switch form-check-custom form-check-success form-check-solid">
                    <input class="form-check-input " '.$checked_anywhere.' type="checkbox" value="" onclick="toggleAnywhere(this)" data-id="'.$value->id.'" id="ckSwitch'.$value->id.'"/>
                    <label class="form-check-label" for="ckSwitch'.$value->id.'">
                    </label>
                </div></div>';
                $row[] = $emp;
            }
        }
        $data = [
            'data' => $row,
        ];
        return json_encode($data);

    }

    function freeze_update($id){
        $emp = Hrd_employee::find($id);

        $emp->freeze = (empty($emp->freeze)) ? 1 : NULL;

        $emp->save();

        return redirect()->back();
    }

    function fired_confirmation($id){
        $employee = Hrd_employee::find($id);

        $loans = [];
        $assets = [];
        $wh = [];
        $account = [];

        $loans = Hrd_employee_loan::where("emp_id", $employee->id)->get();
        foreach($loans as $item){
            $payments = Hrd_employee_loan_payment::where("loan_id", $item->id)
                ->where("date_of_payment", "<", date("Y-m-t"))
                ->get()->sum('amount');
            if(!empty($payments)){
                $item->amount_left = $item->loan_amount - $payments;
            } else {
                $item->amount_left = $item->loan_amount;
            }
        }

        $wh = Asset_wh::where("emp_id", $employee->id)->first();
        if(!empty($wh)){
            $item = Asset_item::get();
            $item_name = $item->pluck("name", "id");
            $item_uom = $item->pluck("uom", "id");
            $item_code = $item->pluck("item_code", "id");
            $assets = Asset_qty_wh::where("wh_id", $wh->id)->where("qty", ">", 0)->get();
            foreach($assets as $value){
                $name = (isset($item_name[$value->item_id])) ? $item_name[$value->item_id] : NULL;
                $uom = (isset($item_uom[$value->item_id])) ? $item_uom[$value->item_id] : NULL;
                $code = (isset($item_code[$value->item_id])) ? $item_code[$value->item_id] : NULL;
                $value->item_code = $code;
                $value->item_name = $name;
                $value->item_uom = $uom;
            }
        }

        $account = User::where("emp_id", $employee->id)->first();

        $users = User::where("company_id", $employee->company_id)
            ->where("id_rms_roles_divisions", "!=", 1)->get();

        return view("employee.confirmation", compact("employee", "loans", "assets", "account", "wh", "users"));
    }

    public function index(Request $request){

        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }

        $divisions = Rms_divisions::where('name','not like','%admin%')
            ->whereNull('deleted_at')
            ->get();
        $employees = Hrd_employee::whereNull('expel')
            ->where('company_id', Session::get("company_id"))
            ->get();
        $divName = [];
        foreach ($divisions as $key => $val){
            $divName['name'][$val->id] = $val->name;
        }

        $comp_ids = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $comp_ids[] = $ids->id;
            }
        } else {
            $comp_ids[] = $comp->id_parent;
        }

        $comp_ids[] = Session::get('company_id');


        $emptypes = Hrd_employee_type::whereIn('company_id', $comp_ids)
            ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();
        $emp_type = [];
        foreach ($emptypes as $key => $val){
            $emp_type[$val->id] = $val->name;
        }

        $userEmp = User::whereIn("emp_id", $employees->pluck("id"))
            ->whereNull("file_signature")
            ->where("company_id", Session::get("company_id"))
            ->get();

        $roles = Role::where("show_career", 1)
            ->orderBy("career_num")
            ->get();
        $cp = Career_path::whereIn("role_id", $roles->pluck("id"))
            ->orderBy("order_num")
            ->get();

        $grades = [];
        foreach($roles as $rl){
            $cpr = $cp->where("role_id", $rl->id);
            foreach($cpr as $cps){
                $grades[] = $cps;
            }
        }

        $religion = Master_religion::get();
        $edu = Master_educations::get();

        if($request->act){
            if($request->act == "email"){
                $em = $request->v;
                $user = User::where("email", $em)->first();

                $data = [
                    "success" => true,
                    "message" => ""
                ];

                if(!empty($user)){
                    $role_access = json_decode($user->role_access ?? "[]", true);

                    if(!empty($user->emp_id) && in_array("hris", $role_access)){
                        $data['success'] = false;
                        $data['message'] = "Email sudah terdaftar";
                    }
                }

                return json_encode($data);
            }
        }

        if($request->t){
            if($request->t == "toggle"){
                if($request->a == "anywhere"){
                    $emp = Hrd_employee::find($request->i);
                    $emp->anywhere = $request->enable == "true" ? 1 : 0;
                    $emp->save();

                    return json_encode([
                        "success" => true,
                        "enabled" => $emp->anywhere
                    ]);
                }
            }
        }

        return view('employee.index',[
            'employees' => $employees,
            'emptypes' => $emptypes,
            'divisions' => $divisions,
            'divName' => $divName,
            'emp_type' => $emp_type,
            'userEmp' => $userEmp,
            'grades' => $grades,
            'religion' => $religion,
            'edu' => $edu
        ]);
    }

    public function getIndexEmployeeLoan(){
        $employees = Hrd_employee::where('company_id', \Session::get('company_id'))
            ->whereNull('expel')
            ->whereNull('deleted_at')
            ->get();
        // dd($payment[17][42]);
        $data_emp = array();
        foreach ($employees as $item){
            $data_emp[$item->id] = $item;
            $id[] = $item->id;
        }

        $loan = Hrd_employee_loan::where('company_id', \Session::get('company_id'))
            ->whereIn('emp_id', $id)
            ->get();

        $loan_payment = Hrd_employee_loan_payment::orderBy('date_of_payment','DESC')
            ->whereIn("loan_id", $loan->whereNotNull('approved_at')->pluck("id"))
            ->where('date_of_payment', '<=' , date("Y-m-t", strtotime(date("Y-m"))))
            // ->orderBy('id', 'desc')
            ->get();
        $payment = array();
            foreach ($loan_payment as $item){
                $payment[$item->company_id][$item->loan_id][] = $item->amount;
            }

        return view('employee.loan',[
            'employees' => $employees,
            'loans' => $loan,
            'payments' => $payment,
            'data_emp' => $data_emp,
        ]);
    }
    public function loandelete($id){
        Hrd_employee_loan::find($id)->delete();
        Hrd_employee_loan_payment::where('loan_id',$id)->delete();
        return redirect()->route('employee.loan');

    }

    public function submitNeedsec(Request $request){
        $this->validate($request,[
            'searchInput' => 'required'
        ]);
        if ($request['searchInput'] == 'koi999'){
            Session::put('seckey_empfin', 99);
            return redirect()->back()->with('message_needsec_success_empfin', 'Access Granted!');
        } else {
            return redirect()->back()->with('message_needsec_fail_empfin', 'Access Denied!');
        }
    }

    public function nextDocNumber($code,$db){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        if ($db == "loan"){
            $cek = Hrd_employee_loan::where('loan_id','like','%'.$code.'%')
                ->whereIn('company_id', $id_companies)
                ->whereNull('deleted_at')
                ->orderBy('id','DESC')
                ->get();

            if (count($cek) > 0){
                $loanId = $cek[0]->loan_id;
                $str = explode('/', $loanId);
                $number = intval($str[0]);
                $number+=1;
                if ($number > 99){
                    $no = strval($number);
                } elseif ($number > 9) {
                    $no = "0".strval($number);
                } else {
                    $no = "00".strval($number);
                }
            } else {
                $no = "001";
            }
        } else {
            $cek = Hrd_employee_loan_payment::where('payment_id','like','%'.$code.'%')
                ->whereIn('company_id', $id_companies)
                ->whereNull('deleted_at')
                ->orderBy('id','DESC')
                ->get();

            if (count($cek) > 0){
                $payId = $cek[0]->payment_id;
                $str = explode('/', $payId);
                $number = intval($str[0]);
                $number+=1;
                if ($number > 99){
                    $no = strval($number);
                } elseif ($number > 9) {
                    $no = "0".strval($number);
                } else {
                    $no = "00".strval($number);
                }
            } else {
                $no = "001";
            }
        }
        return $no;

    }

    function monthDiff($date1, $date2) {
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        return $diff;
    }

    public function addContract(Request $request){
        $emp = Hrd_employee::where('id', $request['id'])->first();
        $file = $request->file('contract_file');
        if (!empty($file)){
            $file = $request->file('contract_file');

            $newFile = str_replace(" ", "_", $emp->emp_name).'_'.date('Y_m_d_H_i_s')."-contract_file.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                Hrd_employee::where('id',$request['id'])
                    ->update([
                        'expire' =>$request['date_exp'],
                        'contract_file' => $hashFile,
                    ]);
            }
        }

        if ($request['opt'] == '2'){
            $str = explode('-',$emp->emp_id);

            $status = substr($emp->emp_id,4,1);
            $str1_new = str_replace($status,'',$str[1]);
            $new_empid = $str[0].'-'.$str1_new;
            Hrd_employee::where('id',$request['id'])
                ->update([
                    'emp_id' => $new_empid,
                ]);
        }


        return redirect()->back();
    }

    public function storeCV(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        $emp = Hrd_employee::find($request->id_emp);
        if ($request->hasFile('document')){
            $file = $request->file('document');
            for ($i=0; $i < count($file); $i++) {
                $hrd_cv = new Hrd_cv_u();
                $hrd_cv->user_id = $request['id_emp'];
                $dup = date("Y_m_d_H_i_s");
                $emp_name = str_replace(" ", "_", $emp->emp_name);
                $newFile = "[$emp_name-$emp->id-$dup]_".$file[$i]->getClientOriginalName();
                $hashFile = Hash::make($newFile);
                $hashFile = str_replace("/", "", $hashFile);

                $upload = FileManagement::save_file_management($hashFile, $file[$i], $newFile, "media/employee_attachment");
                if ($upload == 1){
                    $hrd_cv->category_id = $request->cat;
                    $hrd_cv->cv_address = $hashFile;
                    $hrd_cv->expiry_date = $request->expiry_date;
                    $hrd_cv->cv_name = $newFile;
                    $hrd_cv->date_time = date('Y-m-d H:i:s');
                    $hrd_cv->whom = Auth::user()->username;
                    $hrd_cv->created_at = date('Y-m-d H:i:s');
                }
                $hrd_cv->save();
            }
        }
        return redirect()->back();
    }

    public function file_category(Request $request){
        $cat = new Hrd_employee_attach_category();
        $cat->name = $request->cat;
        $cat->company_id = Session::get("company_id");
        $cat->save();
        return redirect()->back();
    }

    public function deleteCV($id){
        Hrd_cv_u::find($id)->delete();
        return redirect()->back();
    }

    public function addLoan(Request $request){
        $this->validate($request,[
            'employee' => 'required',
            'start' => 'required',
            'end'=> 'required',
            'amount' => 'required'
        ]);

        $arrRomawi  = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $loan_num = $this->nextDocNumber("LN","loan");
        $loanID = str_pad($loan_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/LN/'.$arrRomawi[date("n")].'/'.date("y");

        $loan = new Hrd_employee_loan();
        $loan->loan_id = $loanID;
        $loan->emp_id = $request['employee'];
        $loan->loan_amount = $request['amount'];
        $loan->loan_start = $request['start'];
        $loan->loan_end = $request['end'];
        $loan->notes = ($request['notes']!=null) ? $request['notes']:'';
        $loan->given_by = Auth::user()->username;
        $loan->given_time = date('Y-m-d H:i:s');
        $loan->date_given = date('Y-m-d');
        $loan->company_id = \Session::get('company_id');
        $loan->save();

        if (isset($request['autopay'])){
            list($d1, $m1, $y1) = explode('-', $request['start']);
            list($d2, $m2, $y2) = explode('-', $request['end']);

            $bonusStart = sprintf("%s-%02s-%02s", $y1, $m1, $d1);
            $bonusEnd = sprintf("%s-%02s-%02s", $y2, $m2, $d2);
            $monthDiff = $this->monthDiff($bonusStart, $bonusEnd);
            if($monthDiff == 0){
                $monthDiff = 1;
            }

            $balance = $loan->loan_amount;
            $cicil_draft = $loan->loan_amount / intval($monthDiff);

            // dd($cicil_draft,$loan->loan_amount, $monthDiff);

            for ($i = 0; $i < $monthDiff; $i++){
                $payment_num = $this->nextDocNumber("LNPAY","loan_payment");
                $id_loan = $loan->id;
                $payment_id = str_pad($payment_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/LNPAY/'.$arrRomawi[date("n")].'/'.date("y");
                $date_of_payment_repeat = strtotime($bonusStart);
                $dates = date('Y-m-d', strtotime("+".$i." month", $date_of_payment_repeat));
                $dates2 = explode('-',$dates);
                $date_of_payment = $dates2[0].'-'.$dates2[1].'-17';

                if(($i + 1) == $monthDiff){
                    $cicil_now = $balance;
                } else {
                    $cicil_now = $cicil_draft;
                }

                $amount = $cicil_now;

                $loan_pay = new Hrd_employee_loan_payment();
                $loan_pay->loan_id = $id_loan;
                $loan_pay->amount = round($amount);
                $loan_pay->payment_id = $payment_id;
                $loan_pay->date_of_payment = $date_of_payment;
                $loan_pay->remark = 'insert by autopay';
                $loan_pay->receive_by = Auth::user()->username;
                $loan_pay->receive_time = date('Y-m-d H:i:s');
                $loan_pay->company_id = \Session::get('company_id');
                $loan_pay->save();
                $balance -= $cicil_now;
            }
        }
        return redirect()->route('employee.loan');
    }

    function expel_emp($id){
        $emp = Hrd_employee::find($id);
        $emp->expel = date('Y-m-d');
        // $emp->finalize_expel_by = Auth::user()->username;

        if($emp->save()){

            $period = date("n")."-".date("Y");

            $archive = Hrd_salary_archive::where("emp_id", $emp->id)
                ->where("archive_period", $period)
                ->first();

            if(!empty($archive)){
                $archive->delete();
            }

            $history = new Hrd_employee_history();
            $history->emp_id = $emp->id;
            $history->activity = 'expel';
            $history->act_date = date('Y-m-d');
            $history->act_by   = Auth::user()->username;
            $history->company_id = Session::get('company_id');
            $history->save();

            $comp_name = strtoupper(\Session::get('company_name_parent'));

            $subject = "Employee Removal Notification of $comp_name";

            $content = "<!DOCTYPE html><html><body><p>Hello</p>";
            $content .= "<p>This mail is notifying you about the firing of <b>$emp->emp_name</b> performed by <b>".Auth::user()->username."</b> on <b>".date("Y-m-d H:i:s")."</b></p>";
            $content .= "<p>Right now, the payroll process of <b>$emp->emp_name</b> is paused until the process is finalized.</p>";
            $content .= "<p>Please finalized the firing process on Cypher <b>$comp_name</b>.</p>";
            $content .= "<p>You can also choose an option where the payroll of Achmad Fauzi will be released this month for the last time.</p>";
            $content .= "<p>Thank you.</p>";
            $content .= "</body></html>";

            $receipent = Pref_email::where("company_id", Session::get('company_id'))
                ->where("status", 1)
                ->where("email_type", "like", '%"1"%')
                ->get();
            $sent_to = [];
            foreach($receipent as $item){
                $col['name'] = $item->name;
                $col['email'] = $item->email;
                $sent_to[] = $col;
            }

            $email = \Helper_function::instance()->send_mail($subject, $sent_to, $content);
        }

        return redirect()->back();
    }

    function expelEmp($id, Request $request){
        if($request->submit == "confirm"){
            if($request->loan == 1 && $request->asset == 1 && $request->email == 1){
                $emp = Hrd_employee::find($id);
                $emp->finalize_expel = date('Y-m-d');
                $emp->finalize_expel_by = Auth::user()->username;

                if ($emp->save()) {

                    if(isset($request->payroll) && $request->payroll == 1){
                        $period = date("n")."-".date("Y");

                        $archive = Hrd_salary_archive::where("emp_id", $emp->id)
                            ->where("archive_period", $period)
                            ->withTrashed()
                            ->first();

                        if(!empty($archive)){
                            $archive->deleted_at = null;
                            $archive->save();
                            // $archive->delete();
                        }
                    }

                    $history = new Hrd_employee_history();
                    $history->emp_id = $emp->id;
                    $history->activity = 'fired';
                    $history->act_date = date('Y-m-d');
                    $history->act_by   = Auth::user()->username;
                    $history->company_id = $emp->company_id;
                    $history->save();

                    // find user
                    $user = User::where("emp_id", $emp->id)->first();
                    if(!empty($user)){
                        $user_id = $user->id;
                        if(!empty($user)){
                            UserPrivilege::where('id_users', $user_id)->forceDelete();
                            $user->delete();
                        }
                    }
                }

                if(!empty($request->account)){
                    $user = User::find($request->account);
                    if(!empty($user)){
                        $userBatch = User::where("username", $user->username)
                            ->where("id_batch", $user->id_batch)
                            ->get();
                        foreach($userBatch as $item){
                            $item->deleted_by = Auth::user()->username."[".Auth::id()."]";
                            $item->save();
                            $item->delete();
                        }
                    }
                }

                $comp_name = \Session::get('company_name_parent');

                $subject = "Employee Removal Notification of $comp_name";

                $content = "<!DOCTYPE html><html><body><p>Hello</p>";
                $content .= "<p>This mail is notifying you about the firing of <b>$emp->emp_name</b> from employee list of <b>$comp_name</b>.</p>";
                $content .= "<p>This action is performed by <b>".Auth::user()->username."</b> on <b>".date("Y-m-d H:i:s")."</b>.</p>";
                $content .= "<p>Thank you.</p>";
                $content .= "</body></html>";

                $receipent = Pref_email::where("company_id", Session::get('company_id'))
                    ->where("status", 1)
                    ->where("email_type", "like", '%"1"%')
                    ->get();
                $sent_to = [];
                foreach($receipent as $item){
                    $col['name'] = $item->name;
                    $col['email'] = $item->email;
                    $sent_to[] = $col;
                }

                $email = \Helper_function::instance()->send_mail($subject, $sent_to, $content);

                \App\Helpers\Notification::telegram_emp_notif("expel", $emp->id);

                return redirect()->route('employee.index');
            } else {
                return redirect()->back()->with("error", "outstanding");
            }
        } else {
            $emp = Hrd_employee::find($id);
            $emp->expel = null;
            if($emp->save()){

                $period = date("n")."-".date("Y");

                $archive = Hrd_salary_archive::where("emp_id", $emp->id)
                    ->where("archive_period", $period)
                    ->withTrashed()
                    ->first();

                if(!empty($archive)){
                    $archive->deleted_at = null;
                    $archive->save();
                    // $archive->delete();
                }

                $history = new Hrd_employee_history();
                $history->emp_id = $emp->id;
                $history->activity = 'fired cancel';
                $history->act_date = date('Y-m-d');
                $history->act_by   = Auth::user()->username;
                $history->company_id = Session::get('company_id');
                $history->save();
            }

            return redirect()->route('employee.index');
        }
    }

    public function approvalLoan($id){
        $loan = Hrd_employee_loan::where('id',$id)
            ->first();

        $emps = Hrd_employee::all();
        $data_emp = array();
        foreach ($emps as $item){
            $data_emp[$item->id] = $item;
        }

        $emp = $data_emp[$loan->emp_id];

        $loan_balance = intval($loan->loan_amount);

        $id_loan = (empty($loan->old_id)) ? $id : $loan->old_id;

        $loan_payments = Hrd_employee_loan_payment::where('loan_id', $id)
            // ->where('company_id', \Session::get('company_id'))
            // ->whereNull('deleted_at')
            ->orderBy("date_of_payment")
            ->get();

        $paid_loan = Hrd_employee_loan_payment::where('loan_id', $id)
            ->where('date_of_payment', '<=', date('Y-m-t', strtotime(date("Y-m"))))
            // ->where('company_id', \Session::get('company_id'))
            ->whereNull('deleted_at')
            ->get();

        foreach ($paid_loan as $key => $val){
            $loan_balance -= intval($val->amount);
        }

        return view('employee.loan_approval',[
            'emp' => $emp,
            'payments' => $loan_payments,
            'balance' => $loan_balance,
            'loan' => $loan
        ]);
    }

    public function approve_loan(Request $request){
        $loan = Hrd_employee_loan::find($request->id);
        $loan->approved_at = date("Y-m-d H:i:s");
        $loan->approved_by = Auth::user()->username;
        $loan->save();

        return redirect()->back();
    }

    public function getDetailLoan($id){
        $loan = Hrd_employee_loan::where('id',$id)
            ->whereNull('deleted_at')
            ->first();

        $emps = Hrd_employee::all();
        $data_emp = array();
        foreach ($emps as $item){
            $data_emp[$item->id] = $item;
        }

        $emp = $data_emp[$loan->emp_id];

        $loan_balance = intval($loan->loan_amount);

        $id_loan = (empty($loan->old_id)) ? $id : $loan->old_id;

        $loan_payments = Hrd_employee_loan_payment::where('loan_id', $id)
            // ->where('company_id', \Session::get('company_id'))
            // ->whereNull('deleted_at')
            ->orderBy("date_of_payment")
            ->get();

        $paid_loan = Hrd_employee_loan_payment::where('loan_id', $id)
            ->where('date_of_payment', '<=', date('Y-m-t', strtotime(date("Y-m"))))
            // ->where('company_id', \Session::get('company_id'))
            ->whereNull('deleted_at')
            ->get();

        foreach ($paid_loan as $key => $val){
            $loan_balance -= intval($val->amount);
        }

        return view('employee.loan_payment',[
            'emp' => $emp,
            'payments' => $loan_payments,
            'balance' => $loan_balance,
            'loan' => $loan
        ]);
    }

    public function storeLoanPayment(Request $request){
        $arrRomawi  = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
        $payment_num = $this->nextDocNumber("LNPAY","loan_payment");
        $payment_id = str_pad($payment_num, 3, '0', STR_PAD_LEFT).'/'.strtoupper(\Session::get('company_tag')).'/LNPAY/'.$arrRomawi[date("n")].'/'.date("y");
        $loan_pay = new Hrd_employee_loan_payment();
        $loan_pay->loan_id = $request->loan_id;
        $loan_pay->amount = str_replace(",", "", $request->amount);
        $loan_pay->payment_id = $payment_id;
        $loan_pay->date_of_payment = $request->date_of_payment;
        $loan_pay->remark = $request->memo;
        $loan_pay->receive_by = Auth::user()->username;
        $loan_pay->receive_time = date('Y-m-d H:i:s');
        $loan_pay->company_id = \Session::get('company_id');
        $loan_pay->save();
        return redirect()->route('employee.loan.detail',[$request['loan_id']]);
    }

    function get_initials($arr, $dgt, $n){
        $int = "";
        $n = 1;
        $m = 0;
        $ints = [];
        foreach($arr as $k => $r){
            $c = strlen($r);
            $ints[$k] = 1;
            if($c <= 1) {
                if(isset($ints[$k-1])){
                    $ints[$k-1] += $dgt - ($k + 1);
                }
            }
        }
        foreach($arr as $k => $r){
            $int .= substr($r, 0, $ints[$k]);
        }

        return strtoupper($int);
    }

    public function initials($name, $edu, $n = 0){
        $dgt = 4;
        $arr = ["S1", "S2", "S3"];
        if(in_array($edu, $arr)){
            $dgt = 2;
        } elseif(strtolower($edu) == "d3"){
            $dgt = 3;
        }

        $tempExp = explode(" ", $name);
        $tempExp = array_filter($tempExp, fn($value) => !is_null($value) && $value !== '');
        $exp = [];
        foreach($tempExp as $item){
            $exp[] = $item;
        }
        if(count($exp) == 1){
            $int = substr($name, 0, $dgt);
        } elseif(count($exp) == 2){
            if($dgt == 2){
                $int = $exp[0][0].$exp[1][$n];
            } else {
                $int = $this->get_initials($exp, $dgt, $n);
            }
        } elseif(count($exp) == 3 && $dgt >= 3){
            if($dgt == 2){
                $int = $exp[0][0].$exp[1][$n];
            } else {
                $int = $this->get_initials($exp, $dgt, $n);
            }
        } else {
            if($dgt == 2){
                $int = $exp[0][0].$exp[1][$n];
            } else {
                $int = $this->get_initials($exp, $dgt, $n);
            }
        }

        $exist = Hrd_employee::where("initials", $int)
            ->where('company_id', Session::get("company_id"))
            ->whereNull("expel")
            ->get();
        $count = $exist->count();
        while($count > 0){
            $int = $this->initials($name, $edu, $n);
            $count -= 1;
        }

        return strtoupper($int);
    }

    public function store(Request $request){

        $conflict = Hrd_employee::where("emp_id", stripslashes($request->emp_id))->first();

        if(!empty($conflict)){
            return redirect()->back()->withInput($request->all())->withErrors([
                "emp_id" => "Employee ID sudah digunakan"
            ])
                ->with(['modal' => "addEmployee"]);
        }

        $uploaddir = public_path('hrd\\uploads');
        $employee = new Hrd_employee();
        $employee_history = new Hrd_employee_history();

        // $initials = $this->initials($request->full_name, $request->edu);

        $employee->emp_id = stripslashes($request->emp_id);
        $employee->emp_name = stripslashes($request->full_name);
        $employee->initials = "";
        $employee->edu = $request->edu;


        $thp       = $request->thp;
        $SAL       = str_replace(",", "", $request->SAL ?? 0);
        $HEALTH    = str_replace(",", "", $request->HEALTH ?? 0);
        $TRANSPORT = str_replace(",", "", $request->TRANSPORT ?? 0);
        $MEAL      = str_replace(",", "", $request->MEAL ?? 0);
        $HOUSE     = str_replace(",", "", $request->HOUSE ?? 0);

        $employee->n_basic_salary = base64_encode(str_replace(",", "", $request->n_basic_salary));
        $employee->n_house_allow = base64_encode(str_replace(",", "", $request->n_house_allow));
        $employee->n_health_allow = base64_encode(str_replace(",", "", $request->n_health_allow));
        $employee->n_position_allow = base64_encode(str_replace(",", "", $request->n_position_allow));
        $employee->n_transport_allow = base64_encode(str_replace(",", "", $request->n_transport_allow));
        $employee->n_meal_allow = base64_encode(str_replace(",", "", $request->n_meal_allow));
        $employee->n_performance_bonus = base64_encode(str_replace(",", "", $request->n_performance_bonus));
        $employee->grade = $request->grade;

        $employee->phoneh                = $request->phone_home;
        $employee->salary                = base64_encode($SAL);
        $employee->transport             = base64_encode($TRANSPORT);
        $employee->meal                  = base64_encode($MEAL);
        $employee->house                 = base64_encode($HOUSE);
        $employee->health                = base64_encode($HEALTH);
        $employee->emp_position          = $request->position;
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

        $employee->phone                 = $request->phone;
        $employee->phone2                = $request->phone_2;
        $employee->address               = $request->address;
        $employee->email                 = $request->email;
        $employee->emp_lahir             = $request->date_birth;

        $employee->created_by = Auth::user()->username;
        $employee->join_date = date("Y-m-d");

        $employee->save();

        $userImg = null;

        if ($request->hasFile('picture')){
            $file = $request->file('picture');
            $newFile = stripslashes($employee->emp_id)."-$employee->id-picture.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                $employee->picture = $newFile;
                $userImg = "media/employee_attachment/$newFile";
            }
        }

        if ($request->hasFile('ktp')){
            $file = $request->file('ktp');
            $newFile = stripslashes($employee->emp_id)."-$employee->id-ktp.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                $employee->ktp = $newFile;
            }
        }

        if ($request->hasFile('serti1')){
            $file = $request->file('serti1');
            $newFile = stripslashes($employee->emp_id)."-$employee->id-serti1.".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                $employee->serti1 = $newFile;
            }
        }

        $employee->save();

        $bank_master = Master_banks::where("bank_code", $request->bankCode)->first();

        $bank_acct = new Hrd_employee_bank();
        $bank_acct->emp_id = $employee->id;
        $bank_acct->bank_number = $request->account;
        $bank_acct->bank_id = $bank_master->id;
        $bank_acct->save();

        $employee_history->emp_id        = $employee->id;
        $employee_history->activity      = "in";
        $employee_history->act_date      = date("Y-m-d");
        $employee_history->act_by        = Auth::user()->username;
        $employee_history->company_id    = \Session::get('company_id');

        $employee_history->save();

        \App\Helpers\Notification::telegram_emp_notif("new", $employee->id);

        $userEmp = User::where("email", $employee->email)->first();
        if(empty($userEmp)){

            $mComp = Master_company::where("company_id", $employee->company_id)->first();

            $email = $employee->email;
            $name = $employee->emp_name;
            $_email = explode("@", $email);
            $password = Hash::make("Sukses2023");
            $username = $_email[0];
            $user = new User();
            $user->id_batch = $username."1";
            $user->emp_id = $employee->id;
            $user->name = $name;
            $user->email = $email;
            $user->username = $username;
            $user->password = $password;
            // $user->position = $position;
            $user->id_rms_roles_divisions = 45;
            $user->company_id = $employee->company_id;
            $user->comp_id = $mComp->id ?? null;
            $user->role_access = ["hris"];
            $user->email_verified_at = date("Y-m-d H:i:s");
            $user->role_access = json_encode(["hris", "employer"]);
            $user->complete_profile = 1;
            $user->user_img = $userImg;
            $user->access = "EP";
            $user->save();

            //Add user privilege based on position
            $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
            ->where('id_rms_roles_divisions', $user->id_rms_roles_divisions)
            ->get();
            foreach ($roleDivPriv as $key => $valDivPriv) {
                $addUserRole = new UserPrivilege;
                $addUserRole->id_users = $user->id;
                $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
                $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
                $addUserRole->save();
            }
        } else {
            $role_access = json_decode($userEmp->role_access ?? "[]", true);
            if(!in_array("hris", $role_access)){
                $role_access[] = "hris";
            }

            if(!in_array("employer", $role_access)){
                $role_access[] = "employer";
            }

            $mComp = \App\Models\Master_company::where("company_id", Session::get('company_id'))->first();

            $userEmp->role_access = json_encode($role_access);
            $userEmp->access = "EP";
            if(empty($userEmp->comp_id)){
                $userEmp->comp_id = $mComp->id ?? 7;
            }
            $userEmp->emp_id = $employee->id;
            $userEmp->save();
        }

        return redirect()->route('employee.detail', $employee->id);


    }

    public function nikFunction(Request $request){
        $emp_status = $request->emp_status;
        switch($emp_status) {
            case "tetap": $type = ""; break;
            case "kontrak": $type = "K"; break;
            case "konsultan": $type = "C"; break;
        }
        $date = explode("-",date("Y-m-d"));
        $nik_exist = strtoupper(Session::get('company_tag'))."-".$type.$date[2].$date[1].$date[0];
        $r_s1 = Hrd_employee::select('emp_id')
            ->where('emp_id','like','%'.$nik_exist.'%')
            ->whereNull('expel')
            ->orderBy('id','DESC')
            ->get();


        $count_emp_id = $r_s1->count();
        if ($count_emp_id > 0){
            $emp_id =$r_s1[0]['emp_id'];
            $lastdigit = substr($emp_id, -2);
            $nextdigit = intval($lastdigit)+1;
            if($nextdigit < 10)
            {
                $nextdigit = "0".$nextdigit;
            }
            $NIK = strtoupper(Session::get('company_tag'))."-".$type.$date[2].$date[1].$date[0].$nextdigit;

        } else {
            $NIK = strtoupper(Session::get('company_tag'))."-".$type.$date[2].$date[1].$date[0]."01";
        }
        $data = [
            'data' => $NIK,
        ];
        return json_encode($data);
    }

    public function thpBreakdown(Request $request){
        $thp = $request->thp;
        $SAL = (intval($thp*0.4));
        $HEALTH = (intval($thp*0.15));
        $TRANSPORT = (intval($thp*0.15));
        $MEAL = (intval($thp*0.20));
        $HOUSE = (intval($thp*0.10));

        $data = [
            'data' => "<br>
                        <table class='table table-hover' width='20%'>
                            <thead>
                                <tr>
                                    <th class='text-center' colspan='3'><b>Break Down</b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class='text-left' width='20px'>Salary</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($SAL)."</td>
                                </tr>
                                <tr>
                                    <td class='text-left' width='20px'>Health</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($HEALTH)."</td>
                                </tr>
                                <tr>
                                    <td class='text-left' width='20px'>Transport</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($TRANSPORT)."</td>
                                </tr>
                                <tr>
                                    <td class='text-left' width='20px'>Meal</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($MEAL)."</td>
                                </tr>
                                <tr>
                                    <td class='text-left' width='20px'>House</td>
                                    <td class='text-center'>&nbsp;:&nbsp;&nbsp;</td>
                                    <td class='text-right'>".number_format($HOUSE)."</td>
                                </tr>
                            </tbody>
                        </table>",
        ];

        return json_encode($data);
    }

    public function getDetail($id, Request $request){
        $emp_cv_u = Hrd_cv_u::where('user_id', $id)
            ->where('vaccine', 0)->get();
        $id_companies = array();
        $id_companies_type = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $id_companies_type[] = $ids->id;
            }
        } else {
            $id_companies_type[] = $comp->id_parent;
        }

        $id_companies_type[] = Session::get('company_id');


        $emptypes = Hrd_employee_type::whereIn('company_id', $id_companies_type)
            ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();

        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }
        $getDetailData = Hrd_employee::where('id', $id)->first();
        $getDetailData_history = Hrd_employee_history::where('emp_id',$id)
            ->where('activity','in')->first();
        // dd($getDetailData_history);
        $status = substr($getDetailData->emp_id,4,1);
        $divisions = Rms_divisions::where('name','not like','%admin%')
            ->get();

        $yos = "0 tahun 0 bulan";
        if(!empty($getDetailData_history)){
            $d1 = date_create($getDetailData_history->act_date);
            $d2 = date_create(date("Y-m-d"));
            $dff = date_diff($d1, $d2);
            $y = $dff->format("%y");
            $m = $dff->format("%m");
            $yos = "$y tahun $m bulan";
        }

        $cv = Hrd_cv::where('emp_id', $id)
            ->orderBy('end_date')
            ->get();

        $office = Asset_wh::where('company_id', Session::get('company_id'))
            ->where('office', 1)
            ->get()->pluck('name', 'id');

        $vaccine = Hrd_cv_u::where('user_id', $id)
            ->where('vaccine', 1)->get();

        $variables = Master_variables_model::where("company_id", Session::get("company_id"))
            ->whereNotIn("var_type", ['contact', 'company'])
            ->get();

        $var_val = Master_var_emp::where("id_emp", $id)->get()->pluck('values', 'id_var');

        $curr_comp = Config_Company::find($getDetailData->company_id);

        if(!empty($curr_comp->id_parent)){
            $par_comp = Config_Company::find($curr_comp->id_parent);
        } else {
            $par_comp = $curr_comp;
        }

        $child_company = Config_Company::where('id', '!=', $getDetailData->company_id)
            ->where(function($query) use($par_comp){
                $query->orWhere('id', $par_comp->id);
                $query->orWhere('id_parent', $par_comp->id);
            })
            ->get()->pluck('company_name', 'id');

        $emp_sister = [];
        if(!empty($getDetailData->emp_id_sister)){
            $emp_sister = Hrd_Employee::find($getDetailData->emp_id_sister);
        }

        $fpath = scandir(public_path('media/employee_attachment'));

        $file = File_Management::whereIn('hash_code', $cv->pluck("cv_address"))
            ->orWhereIn('hash_code', $emp_cv_u->pluck("cv_address"))
            ->orWhereIn('hash_code', $vaccine->pluck("cv_address"))
            ->get()->pluck('file_name', "hash_code");

        $ppe = Hrd_employee_ppe::where("emp_id", $id)->first();
        $do = [];
        if(!empty($ppe->do_id)){
            $do = General_do::find($ppe->do_id);
        }

        $users = User::where('company_id', Session::get('company_id'))
            ->whereNull("emp_id")
            ->orWhere('emp_id', $id)
            ->get();
        $empUser = User::where('emp_id', $getDetailData->id)->first();

        $clockin = null;
        $clockout = null;
        if(!empty($empUser)){
            $session = Hrd_att_transaction::where("emp_id", $getDetailData->id)->orderBy('id')->get();
            if(count($session) > 0){
                $clockin = $session[0]->trans_time;
                if(count($session) > 1){
                    $clockout = $session[count($session) - 1]->trans_time;
                }
            }
        }

        $pension = Hrd_employee_pension::emp($id)->first();
        $empSign = User::where("emp_id", $id)
            ->whereNotNull("file_signature")
            ->first();
        if($request->act == "issue_date"){
            $issue_no = 1;
            $last_plan = Hrd_employee_pension::where("company_id", Session::get("company_id"))
                ->orderBy("issue_no", "desc")
                ->first();
            if(!empty($last_plan)){
                $issue_no = $last_plan->issue_no + 1;
            }
            $dd = date("my", strtotime($request->date));
            $issue_num = sprintf("%03d", $issue_no)."/".Session::get('company_tag')."/SP/MPP/$dd";
            $pension_date = date("Y-m-d", strtotime($request->date." +1 year"));
            $arr = [
                "issue_number" => $issue_num,
                "pension_date" => $pension_date
            ];

            return json_encode($arr);
        }

        $sev = Hrd_severance::where("emp_id", $id)
            ->whereNotNull('approved_at')
            ->orderBy('id', 'desc')
            ->first();

        $initials = $getDetailData->initials;
        if(empty($initials)){
            // $initials = $this->initials($getDetailData->emp_name, $getDetailData->edu);
        }

        $docCat = Hrd_employee_attach_category::where("company_id", Session::get('company_id'))
            ->orderBy("name")
            ->get();

        $religion = Master_religion::get();
        $edu = Master_educations::get();
        $marital = Master_marital_status::get();

        return view('employee.detail',[
            'emptypes' => $emptypes,
            'emp_detail' => $getDetailData,
            'emp_detail_history' => $getDetailData_history,
            'status' => $status,
            'divisions' => $divisions,
            'emp_cv' => $emp_cv_u,
            'cv' => $cv,
            'office' => $office,
            'vaccine' => $vaccine,
            'variables' => $variables,
            'var_val' => $var_val,
            'child_comp' => $child_company,
            'emp_sister' => $emp_sister,
            'file_name' => $file,
            "do" => $do,
            "ppe" => $ppe,
            'user_list' => $users,
            'user_emp' => $empUser,
            'clockin' => $clockin,
            'clockout' => $clockout,
            'fpath' => $fpath,
            'pension' => $pension,
            'empSign' => $empSign,
            'sev' => $sev,
            'initials' => $initials,
            'yos' => $yos,
            'docCat' => $docCat,
            'religion' => $religion,
            'edu' => $edu,
            "marital" => $marital
        ]);

    }

    public function empCompany($id, Request $request){
        $whereEmpName = " 1";
        if(isset($request->term) && !empty($request->term)){
            $whereEmpName = "emp_name like '%$request->term%'";
        }

        $emp = Hrd_Employee::where('company_id', $id)
            ->whereRaw($whereEmpName)
            ->whereNull('expel')
            ->orderBy('emp_name')
            ->get()->pluck('emp_name', 'id');

        $row = [];
        foreach($emp as $id => $name){
            $col = [];
            $col['id'] = $id;
            $col['text'] = $name;
            $row[] = $col;
        }

        $data = [
            "results" => $row
        ];

        return json_encode($data);
    }

    public function delete($id){
        $emp = Hrd_employee::where('id',$id)->first();
        $pict_path = "/hrd/uploads/".$emp->picture;
        $ktp_path = "/hrd/uploads/".$emp->ktp;
        $serti1_path = "/hrd/uploads/".$emp->serti1;
        if (File::exists($pict_path)){
            File::delete($pict_path);
        }
        if (File::exists($ktp_path)){
            File::delete($ktp_path);
        }
        if (File::exists($serti1_path)){
            File::delete($serti1_path);
        }
        Hrd_employee::find($id)->delete();

        $comp_name = \Session::get('company_name_parent');

        $subject = "Employee Removal Notification of $comp_name";

        $content = "<!DOCTYPE html><html><body><p>Hello</p>";
        $content .= "<p>This mail is notifying you about the deletion of <b>$emp->emp_name</b> from employee list of <b>$comp_name</b>.</p>";
        $content .= "<p>This action is performed by <b>".Auth::user()->username."</b> on <b>".date("Y-m-d H:i:s")."</b>.</p>";
        $content .= "<p>Thank you.</p>";
        $content .= "</body></html>";

        $receipent = Pref_email::where("company_id", Session::get('company_id'))
            ->where("status", 1)
            ->where("email_type", "like", '%"1"%')
            ->get();
        $sent_to = [];
        foreach($receipent as $item){
            $col['name'] = $item->name;
            $col['email'] = $item->email;
            $sent_to[] = $col;
        }

        $email = \Helper_function::instance()->send_mail($subject, $sent_to, $content);

        return redirect()->route('employee.index');
    }

    public function update(Request $request,$id){
        $emp_sister = null;
        if(isset($request->emp_sister)){
            $emp_sister = $request->emp_sister;
        }
        $office = null;
        if(isset($request->office)){
            $office = $request->office;
        }
        $emp = Hrd_employee::find($id);
        // $initials = $emp->initials;
        // if(empty($emp->initials)){
        //     $initials = $this->initials($request->emp_name, $request->edu);
        // }

        $emp = Hrd_employee::find($id);

        $emp->emp_name = $request->emp_name;
        $emp->email = $request->email;
        $emp->address = $request->address;
        $emp->religion = $request->religion;
        $emp->emp_lahir = $request->lahir;
        $emp->phone = $request->phone;
        $emp->phone2 = $request->phone2;
        $emp->phoneh = $request->phoneh;
        $emp->bank_code = $request->bankCode;
        $emp->bank_acct = $request->bank_acct;
        $emp->emp_id = $request->emp_id;
        $emp->emp_position = $request->emp_position;
        $emp->emp_type = $request->emp_type;
        $emp->division = $request->division;
        $emp->id_wh = $office;
        $emp->emp_id_sister = $emp_sister;
        $emp->is_driver = (isset($request->is_driver)) ? 1 : 0;
        $emp->edu = $request->edu;
        $emp->nik = $request->nik;
        $emp->latitude = $request->latitude;
        $emp->longitude = $request->longitude;
        $emp->radius = $request->radius;
        $emp->marital_status = $request->marital_status;
        $emp->emp_tmpt_lahir = $request->emp_tmpt_lahir;
        $emp->save();

        $param = $request->param;
        if(!empty($param)){
            foreach ($param as $key => $value) {
                $detail_variables = Master_var_emp::find($key);
                if(emptY($detail_variables)){
                    $detail_variables = new Master_var_emp();
                    $detail_variables->created_by = Auth::user()->username;
                    $detail_variables->company_id = Session::get('company_id');
                }
                $detail_variables->id_var = $key;
                $detail_variables->id_emp = $id;
                $detail_variables->values = $value;
                $detail_variables->updated_by = Auth::user()->username;
                $detail_variables->save();
            }
        }

        if(!empty($request->user_emp)){
            $user = User::find($request->user_emp);
            $user->emp_id = $id;
            if(empty($user->attend_code)){
                $code = random_int(100000, 999999);
                $attend_codeExist = User::where("attend_code", $code)->first();
                while(!empty($attend_codeExist)){
                    $code = random_int(100000, 999999);
                    $attend_codeExist = User::where("attend_code", $code)->first();
                }

                $user->attend_code = $code;
            }
            // $user->absence = $request->radabsen;
            $user->save();
        } else {
            // $user = User::where('emp_id', $id)->first();
            // if(!empty($user)){
            //     $user->emp_id = null;
            //     // $user->absence = 0;
            //     $user->save();
            // }
        }

        // $userEmp = User::where("email", $emp->email)->first();
        // if(empty($userEmp)){
        //     $email = $emp->email;
        //     $name = $emp->emp_name;
        //     $_email = explode("@", $email);
        //     $password = Hash::make("Sukses2023");
        //     $username = $_email[0];
        //     $user = new User();
        //     $user->id_batch = $username."1";
        //     $user->emp_id = $emp->id;
        //     $user->name = $name;
        //     $user->email = $email;
        //     $user->username = $username;
        //     $user->password = $password;
        //     // $user->position = $position;
        //     $user->id_rms_roles_divisions = 45;
        //     $user->company_id = $emp->company_id;
        //     $user->comp_id = $mComp->id ?? null;
        //     $user->role_access = ["hris"];
        //     $user->email_verified_at = date("Y-m-d H:i:s");
        //     $user->role_access = json_encode(["hris", "employer"]);
        //     $user->complete_profile = 1;
        //     $user->access = "EP";
        //     $user->save();

        //     //Add user privilege based on position
        //     $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
        //     ->where('id_rms_roles_divisions', $user->id_rms_roles_divisions)
        //     ->get();
        //     foreach ($roleDivPriv as $key => $valDivPriv) {
        //         $addUserRole = new UserPrivilege;
        //         $addUserRole->id_users = $user->id;
        //         $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
        //         $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
        //         $addUserRole->save();
        //     }
        // }


        $userEmp = User::where("emp_id", $emp->id)->first();
        if(!empty($userEmp)){
            $userEmp->email = $emp->email;
            $userEmp->save();
        }

        return redirect()->route('employee.detail',['id'=>$id]);
    }

    public function updateAttach(Request $request,$id){
        Artisan::call('cache:clear');

        $employee = Hrd_employee::find($id);
        $employee = Hrd_employee::where('id',$id)->first();

        if ($request->hasFile('picture')){
            $file = $request->file('picture');

            $newFile = $employee->emp_id."-picture(".date('YmdHis').").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                Hrd_employee::where('id',$id)
                    ->update([
                        'picture' => $newFile,
                    ]);
            }
        }

        if(isset($request->delete_picture)){
            Hrd_employee::where('id',$id)
                    ->update([
                        'picture' => null,
                    ]);
        }

        if(isset($request->delete_ktp)){
            Hrd_employee::where('id',$id)
                    ->update([
                        'ktp' => null,
                    ]);
        }

        if(isset($request->delete_sertif)){
            Hrd_employee::where('id',$id)
                    ->update([
                        'serti1' => null,
                    ]);
        }

        if ($request->hasFile('ktp')){
            $file = $request->file('ktp');

            $newFile = $employee->emp_id."-ktp(".date('YmdHis').").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                Hrd_employee::where('id',$id)
                    ->update([
                        'ktp' =>$newFile,
                    ]);
            }
        }

        if ($request->hasFile('serti1')){
            $file = $request->file('serti1');

            $newFile = $employee->emp_id."-serti1(".date('YmdHis').").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                Hrd_employee::where('id',$id)
                    ->update([
                        'serti1' =>$newFile,
                    ]);
            }
        }

        return redirect()->route('employee.detail',['id'=>$id]);
    }

    public function updateJoinDate(Request $request, $id){
        Hrd_employee_history::where('emp_id',$id)
            ->where('activity', 'in')
            ->update([
                'act_date' => $request['date']
            ]);
        return redirect()->route('employee.detail',['id'=>$id]);
    }

    public function salary_approval(){
        $emp = Hrd_employee::where("company_id", Session::get('company_id'))->get();
        $emp_name = $emp->pluck("emp_name", "id");

        $list = Hrd_salary_update::where("company_id", Session::get('company_id'))
            ->orderBy("created_at", "desc")
            ->get();
        foreach($list as $item){
            $item->emp_name = "";
            if(isset($emp_name[$item->emp_id])){
                $item->emp_name = $emp_name[$item->emp_id];
            }
        }

        return view("employee.sal_approval", compact("list"));
    }

    public function salary_approval_post($id, Request $request){
        $list = Hrd_salary_update::find($id);
        $list->implement_date = $request->implement_date;
        $list->approved_at = date("Y-m-d H:i:s");
        $list->approved_by = Auth::user()->username;

        $emp = Hrd_employee::find($list->emp_id);

        $newEmp = json_decode($list->salary_json, true);
        $newSal = $newEmp['SAL'] + $newEmp['HEALTH'] + $newEmp['TRANSPORT'] + $newEmp['MEAL'] + $newEmp['HOUSE'];
        $sal = base64_decode($emp['salary']) + base64_decode($emp['health']) + base64_decode($emp['transport']) + base64_decode($emp['meal']) + base64_decode($emp['house']);

        $comp_name = \Session::get('company_name_parent');

        $subject = "Employee Salary Change Notification of $comp_name";

        $change = "";

        if($newSal != $sal){
            $change .= "- Salary : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($sal, 2)." to ".number_format($newSal, 2);
        }

        if($newEmp['pa'] != $emp->allowance_office){
            $change .= "<br>- Position Allowance : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->allowance_office, 2)." to ".number_format($newEmp['pa'], 2);
        }

        if($newEmp['hi'] != $emp->health_insurance){
            $change .= "<br>- Health Insurance : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->health_insurance, 2)." to ".number_format($newEmp['hi'], 2);
        }

        if($newEmp['jam'] != $emp->jamsostek){
            $change .= "<br>- Jamsostek : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->jamsostek, 2)." to ".number_format($newEmp['jam'], 2);
        }

        if($newEmp['overtime'] != $emp->overtime){
            $change .= "<br>- Overtime Rate : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->overtime, 2)." to ".number_format($newEmp['overtime'], 2);
        }

        if($newEmp['voucher'] != $emp->voucher){
            $change .= "<br>- Voucher : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->voucher, 2)." to ".number_format($newEmp['voucher'], 2);
        }

        $content = "<!DOCTYPE html><html><body><p>Hello, </p>";
        $content .= "<p>This mail is notifying you that there are changes to the benefit structure of <b>$emp->emp_name</b> perfromed by <b>".Auth::user()->username."</b> on <b>".date("Y-m-d H:i:s")."</b>.</p>";
        $content .= "<p>The changes are :</p>";
        $content .= $change;
        $content .= "<p>And this changes will be implemented for payroll periode <b>".date("F Y", strtotime($list->implement_date." +1 month"))."</b></p>";
        $content .= "<p>Thank you.</p>";
        $content .= "</body></html>";

        $receipent = Pref_email::where("company_id", Session::get('company_id'))
            ->where("status", 1)
            ->where("email_type", "like", '%"4"%')
            ->get();
        $sent_to = [];
        foreach($receipent as $item){
            $col['name'] = $item->name;
            $col['email'] = $item->email;
            $sent_to[] = $col;
        }

        $list->save();

        $_y = date("Y");
        $_m = date("m");
        if($_m == 0){
            $_y -= 1;
            $_m = 12;
        }
        $period = "$_y-".sprintf("%02d", $_m);

        if($period == date("Y-m", strtotime($request->implement_date))){
            $salnew = empty($list->salary_json) ? [] : json_decode($list->salary_json, true);
            $emp['salary'] = base64_encode($salnew['SAL']);
            $emp['transport'] = base64_encode($salnew['TRANSPORT']);
            $emp['meal'] = base64_encode($salnew['MEAL']);
            $emp['house'] = base64_encode($salnew['HOUSE']);
            $emp['health'] = base64_encode($salnew['HEALTH']);
            $emp['pension'] = ($salnew['pensi']) ? $salnew['pensi'] : 0;
            $emp['health_insurance'] = ($salnew['hi']) ? $salnew['hi'] : 0;
            $emp['jamsostek'] = ($salnew['jam']) ? $salnew['jam'] : 0;
            $emp['overtime'] = $salnew['overtime'];
            $emp['voucher'] = $salnew['voucher'];
            $emp['yearly_bonus'] = ($salnew['yb']) ? $salnew['yb'] : 0;
            $emp['allowance_office'] = ($salnew['pa']) ? $salnew['pa'] : 0;
            $emp->save();

            $TSAL = $salnew['SAL'] + $salnew['TRANSPORT'] + $salnew['MEAL'] + $salnew['HOUSE'] + $salnew['HEALTH'];
            $otsal = $salnew;
            unset($otsal['voucher']);
            unset($otsal['pa']);
            unset($otsal['SAL']);
            unset($otsal['TRANSPORT']);
            unset($otsal['MEAL']);
            unset($otsal['HOUSE']);
            unset($otsal['HEALTH']);

            $sal_history = new Hrd_salary_history();
            $sal_history->user = Auth::user()->username;
            $sal_history->target = $list->emp_id;
            $sal_history->basic = $TSAL;
            $sal_history->voucher = $salnew['voucher'];
            $sal_history->position = $salnew['pa'];
            $sal_history->others = json_encode($otsal);
            $sal_history->execute_time = date('Y-m-d H:i:s');
            $sal_history->created_at = date('Y-m-d H:i:s');
            $sal_history->save();

            $list->implement_at = date("Y-m-d H:i:s");
            $list->implement_status = 1;
            $list->implement_by = Auth::user()->username;
            $list->save();
        }

        $email = \Helper_function::instance()->send_mail($subject, $sent_to, $content);

        \App\Helpers\Notification::telegram_emp_notif("salary", $emp->id);

        return redirect()->route("employee.salary.approval");
    }

    function salary_approval_email($id){
        $list = Hrd_salary_update::find($id);
        $emp = Hrd_employee::find($list->emp_id);

        $newEmp = json_decode($list->salary_json, true);
        $newSal = $newEmp['SAL'] + $newEmp['HEALTH'] + $newEmp['TRANSPORT'] + $newEmp['MEAL'] + $newEmp['HOUSE'];
        $sal = base64_decode($emp['salary']) + base64_decode($emp['health']) + base64_decode($emp['transport']) + base64_decode($emp['meal']) + base64_decode($emp['house']);

        $comp_name = \Session::get('company_name_parent');

        $subject = "Employee Salary Change Notification of $comp_name";

        $change = "";

        if($newSal != $sal){
            $change .= "- Salary : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($sal, 2)." to ".number_format($newSal, 2);
        }

        if($newEmp['pa'] != $emp->allowance_office){
            $change .= "<br>- Position Allowance : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->allowance_office, 2)." to ".number_format($newEmp['pa'], 2);
        }

        if($newEmp['hi'] != $emp->health_insurance){
            $change .= "<br>- Health Insurance : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->health_insurance, 2)." to ".number_format($newEmp['hi'], 2);
        }

        if($newEmp['jam'] != $emp->jamsostek){
            $change .= "<br>- Jamsostek : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->jamsostek, 2)." to ".number_format($newEmp['jam'], 2);
        }

        if($newEmp['field_rate'] != $emp->fld_bonus){
            $change .= "<br>- Field Rate : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->fld_bonus, 2)." to ".number_format($newEmp['field_rate'], 2);
        }

        if($newEmp['wh_rate'] != $emp->wh_bonus){
            $change .= "<br>- Warehouse Rate : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->wh_bonus, 2)." to ".number_format($newEmp['wh_rate'], 2);
        }

        if($newEmp['odo_rate'] != $emp->odo_bonus){
            $change .= "<br>- ODO Rate : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->odo_bonus, 2)." to ".number_format($newEmp['odo_rate'], 2);
        }

        if($newEmp['overtime'] != $emp->overtime){
            $change .= "<br>- Overtime Rate : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->overtime, 2)." to ".number_format($newEmp['overtime'], 2);
        }

        if($newEmp['voucher'] != $emp->voucher){
            $change .= "<br>- Voucher : <br>";
            $change .= "&nbsp;&nbsp;&nbsp;&nbsp; From : ".number_format($emp->voucher, 2)." to ".number_format($newEmp['voucher'], 2);
        }

        $content = "<!DOCTYPE html><html><body><p>Hello, </p>";
        $content .= "<p>This mail is notifying you that there are changes to the benefit structure of <b>$emp->emp_name</b> perfromed by <b>".$list->approved_by."</b> on <b>".$list->approved_at."</b>.</p>";
        $content .= "<p>The changes are :</p>";
        $content .= $change;
        $content .= "<p>And this changes will be implemented for payroll periode <b>".date("F Y", strtotime($list->implement_date." +1 month"))."</b></p>";
        $content .= "<p>Thank you.</p>";
        $content .= "</body></html>";

        $receipent = Pref_email::where("company_id", Session::get('company_id'))
            ->where("status", 1)
            ->where("email_type", "like", '%"4"%')
            ->get();
        $sent_to = [];
        foreach($receipent as $item){
            $col['name'] = $item->name;
            $col['email'] = $item->email;
            $sent_to[] = $col;
        }

        $list->save();

        $email = \Helper_function::instance()->send_mail($subject, $sent_to, $content);

        return redirect()->route("employee.salary.approval");
    }

    public function salary_approval_approve($id){
        $list = Hrd_salary_update::find($id);
        $emp = Hrd_employee::find($list->emp_id);

        return view("employee.sal_view", compact("list", "emp"));
    }

    public function salary_approval_delete($id){
        $list = Hrd_salary_update::find($id);
        $list->deleted_by = Auth::user()->username;
        $list->save();
        $list->delete();

        return redirect()->back();
    }

    public function updateFinMan(Request $request){
        $msg = "";
        $emp = Hrd_employee::find($request['id']);
        if($request->submit == "salary"){
            $json = [];
            foreach($request->input() as $key => $item){
                if(!in_array($key, ['id', "_token", "submit"])){
                    if($key == "thp"){
                        $json['SAL']       = intval($item*0.4);
                        $json['HEALTH']    = intval($item*0.15);
                        $json['TRANSPORT'] = intval($item*0.15);
                        $json['MEAL']      = intval($item*0.20);
                        $json['HOUSE']     = intval($item*0.10);
                    } else {
                        $json[$key] = str_replace(",", "", $item);
                    }
                }
            }

            $upd = new Hrd_salary_update();
            $upd->emp_id = $request->id;
            $upd->salary_json = json_encode($json);
            $upd->created_by = Auth::user()->username;
            $upd->company_id = Session::get("company_id");
            $upd->save();
            $msg = "Please wait for the approval";
        } elseif($request->submit == "n_salary"){
            foreach($request->all() as $key => $req){
                if(stripos($key, "n_") !== false){
                    $emp[$key] = base64_encode(str_replace(",", "", $req));
                }
            }

            $emp->save();
            $msg = "Data Updated";
        } else {
            $emp['dom_meal'] = ($request->dom_meal) ? str_replace(",", "", $request->dom_meal) : 0;
            $emp['dom_spending'] = ($request->dom_spending) ? str_replace(",", "", $request->dom_spending) : 0;
            $emp['dom_overnight'] = ($request->dom_overnight) ? str_replace(",", "", $request->dom_overnight) : 0;
            $emp['dom_transport_airport'] = ($request->dom_transport_airport) ? str_replace(",", "", $request->dom_transport_airport) : 0;
            $emp['dom_transport_train'] = ($request->dom_transport_train) ? str_replace(",", "", $request->dom_transport_train) : 0;
            $emp['dom_transport_bus'] = ($request->dom_transport_bus) ? str_replace(",", "", $request->dom_transport_bus) : 0;
            $emp['dom_transport_cil'] = ($request->dom_transport_cil) ? str_replace(",", "", $request->dom_transport_cil) : 0;
            $emp['ovs_meal'] = ($request->ovs_meal) ? str_replace(",", "", $request->ovs_meal) : 0;
            $emp['ovs_spending'] = ($request->ovs_spending) ? str_replace(",", "", $request->ovs_spending) : 0;
            $emp['ovs_overnight'] = ($request->ovs_overnight) ? str_replace(",", "", $request->ovs_overnight) : 0;
            $emp['ovs_transport_airport'] = ($request->ovs_transport_airport) ? str_replace(",", "", $request->ovs_transport_airport) : 0;
            $emp['ovs_transport_train'] = ($request->ovs_transport_train) ? str_replace(",", "", $request->ovs_transport_train) : 0;
            $emp['ovs_transport_bus'] = ($request->ovs_transport_bus) ? str_replace(",", "", $request->ovs_transport_bus) : 0;
            $emp['ovs_transport_cil'] = ($request->ovs_transport_cil) ? str_replace(",", "", $request->ovs_transport_cil) : 0;
            $emp->save();
            $msg = "Data Updated";
        }
        $return = [
            "success" => 1,
            "message" => $msg
        ];

        return redirect()->back()->with("salary", $return);
    }

    public function updateFinManOld(Request $request){
        $thp       = $request->thp;
        $SAL       = intval($thp*0.4);
        $HEALTH    = intval($thp*0.15);
        $TRANSPORT = intval($thp*0.15);
        $MEAL      = intval($thp*0.20);
        $HOUSE     = intval($thp*0.10);

        $emp = Hrd_employee::find($request->id);
        $emp['salary'] = base64_encode($SAL);
        $emp['transport'] = base64_encode($TRANSPORT);
        $emp['meal'] = base64_encode($MEAL);
        $emp['house'] = base64_encode($HOUSE);
        $emp['health'] =base64_encode($HEALTH);
        $emp['fld_bonus'] = ($request->field_rate) ? $request->field_rate : 0;
        $emp['wh_bonus'] = ($request->wh_rate) ? $request->wh_rate : 0;
        $emp['odo_bonus'] = ($request->odo_rate) ? $request->odo_rate : 0;
        $emp['pension'] = ($request->pensi) ? $request->pensi : 0;
        $emp['health_insurance'] = ($request->hi) ? $request->hi : 0;
        $emp['jamsostek'] = ($request->jam) ? $request->jam : 0;
        $emp['overtime'] = $request->overtime;
        $emp['voucher'] = $request->voucher;
        $emp['yearly_bonus'] = ($request->yb) ? $request->yb : 0;
        $emp['allowance_office'] = ($request->pa) ? $request->pa : 0;
        $emp['dom_meal'] = ($request->dom_meal) ? $request->dom_meal : 0;
        $emp['dom_spending'] = ($request->dom_spending) ? $request->dom_spending : 0;
        $emp['dom_overnight'] = ($request->dom_overnight) ? $request->dom_overnight : 0;
        $emp['dom_transport_airport'] = ($request->dom_transport_airport) ? $request->dom_transport_airport : 0;
        $emp['dom_transport_train'] = ($request->dom_transport_train) ? $request->dom_transport_train : 0;
        $emp['dom_transport_bus'] = ($request->dom_transport_bus) ? $request->dom_transport_bus : 0;
        $emp['dom_transport_cil'] = ($request->dom_transport_cil) ? $request->dom_transport_cil : 0;
        $emp['ovs_meal'] = ($request->ovs_meal) ? $request->ovs_meal : 0;
        $emp['ovs_spending'] = ($request->ovs_spending) ? $request->ovs_spending : 0;
        $emp['ovs_overnight'] = ($request->ovs_overnight) ? $request->ovs_overnight : 0;
        $emp['ovs_transport_airport'] = ($request->ovs_transport_airport) ? $request->ovs_transport_airport : 0;
        $emp['ovs_transport_train'] = ($request->ovs_transport_train) ? $request->ovs_transport_train : 0;
        $emp['ovs_transport_bus'] = ($request->ovs_transport_bus) ? $request->ovs_transport_bus : 0;
        $emp['ovs_transport_cil'] = ($request->ovs_transport_cil) ? $request->ovs_transport_cil : 0;
        $emp->save();
        return redirect()->route('employee.detail',['id'=>$request['id']]);
    }

    public function updateInsurance(Request $request){
        Hrd_employee::where('id',$request['id'])
            ->update([
                'allow_bpjs_tk' => ($request->allow_bpjs_tk) ? $request->allow_bpjs_tk : 0,
                'deduc_bpjs_tk' => ($request->deduc_bpjs_tk) ? $request->deduc_bpjs_tk : 0,
                'allow_bpjs_kes' => ($request->allow_bpjs_kes) ? $request->allow_bpjs_kes : 0,
                'deduc_bpjs_kes' => ($request->deduc_bpjs_kes) ? $request->deduc_bpjs_kes : 0,
                'allow_jshk' => ($request->allow_jshk) ? $request->allow_jshk : 0,
                'deduc_jshk' => ($request->deduc_jshk) ? $request->deduc_jshk : 0,
                'deduc_pph21' => ($request->deduc_pph21) ? $request->deduc_pph21 : 0,
            ]);
        return redirect()->route('employee.detail',['id'=>$request['id']]);
    }

    public function generate_data($id){
        $emp = Hrd_employee::where('company_id', $id)
            ->orderBy('old_id')
            ->get();
        foreach ($emp as $value){
            if (!empty($value['old_id'])){
                $query = "UPDATE employee SET ";
                $query .= "dom_meal = '".$value['dom_meal']."', dom_spending = '".$value['dom_spending']."', dom_overnight = '".$value['dom_overnight']."', ";
                $query .= "ovs_meal = '".$value['ovs_meal']."', ovs_spending = '".$value['ovs_spending']."', ovs_overnight = '".$value['ovs_overnight']."', ";
                $query .= "dom_transport_airport = '".$value['dom_transport_airport']."', dom_transport_bus = '".$value['dom_transport_bus']."', dom_transport_cil = '".$value['dom_transport_cil']."', dom_transport_train = '".$value['dom_transport_train']."', ";
                $query .= "ovs_transport_airport = '".$value['ovs_transport_airport']."', ovs_transport_bus = '".$value['ovs_transport_bus']."', ovs_transport_cil = '".$value['ovs_transport_cil']."', ovs_transport_train = '".$value['ovs_transport_train']."' ";
                $query .= " where id = '".$value['old_id']."' ; <br>";
                echo $query;
            }
        }
    }

    // CV
    function cv(Request $request){
        $cv = new Hrd_cv();
        $cv->emp_id = $request->emp_id;
        $cv->description = $request->description;
        $cv->start_date = $request->start_date;
        $cv->type = $request->type;
        $cv->end_date = $request->end_date;
        $cv->created_by = Auth::user()->username;
        $cv->company_id = Session::get('company_id');
        if ($request->file('document')){
            // do the upload file
            $file = $request->file('document');
            $filename = explode(".", $file->getClientOriginalName());
            array_pop($filename);
            $filename = str_replace(" ", "_", implode("_", $filename));

            $newFile = "(CV)".$filename."-".date('Y_m_d_H_i_s')."(".$request->emp_id.").".$file->getClientOriginalExtension();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);
            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "hrd\uploads");
            if ($upload == 1){
                $cv->document = $hashFile;
            }
        }

        if ($cv->save()){
            return redirect(route('employee.detail',['id'=>$request->emp_id])."#cv-management");
        }
    }

    function cv_delete($id){
        $cv = Hrd_cv::find($id);
        $empId = $cv->emp_id;

        if ($cv->delete()){
            return redirect(route('employee.detail',['id'=>$empId])."#cv-management");
        }
    }

    function cv_print($id){
        $emp = Hrd_employee::find($id);
        $cv = Hrd_cv::where('emp_id', $id)->get();
        $company = ConfigCompany::find($emp->company_id);

        return view('employee.print_cv', [
            "emp" =>$emp,
            "cv" =>$cv,
            'company' => $company
        ]);
    }

    function storeVaccine(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        if ($request->hasFile('document')){
            $file = $request->file('document');

            $newFile = $file->getClientOriginalName();
            $hashFile = Hash::make($newFile);
            $hashFile = str_replace("/", "", $hashFile);

            $upload = FileManagement::save_file_management($hashFile, $file, $newFile, "media/employee_attachment");
            if ($upload == 1){
                $hrd_cv = new Hrd_cv_u();
                $hrd_cv->user_id = $request['id_emp'];
                $hrd_cv->date_time = $request['_date'];
                $hrd_cv->vaccine = 1;
                $hrd_cv->vaccine_i = $request->_count;
                $hrd_cv->vaccine_type = $request->_type;
                $hrd_cv->vaccine = 1;
                $hrd_cv->cv_address = $hashFile;
                $hrd_cv->cv_name = $newFile;
                $hrd_cv->created_by = Auth::user()->username;
                $hrd_cv->company_id = Session::get('company_id');
                $hrd_cv->save();
            }
        }
        return redirect()->back();
    }

    // BPJS

    function bpjs(){

        $emp = Hrd_Employee::where('company_id', Session::get('company_id'))
            ->whereNull('expel')
            ->where(function($query){
                $query->WhereRaw('(allow_bpjs_kes != 0.00)');
                $query->orWhereRaw('(deduc_bpjs_kes != 0.00)');
            })
            ->orderBy('emp_name')
            ->get();

        return view("employee.bpjs", compact('emp'));
    }

    function bpjs_tk(){

        $emp = Hrd_Employee::where('company_id', Session::get('company_id'))
            ->whereNull('expel')
            ->where(function($query){
                $query->WhereRaw('(allow_bpjs_tk != 0)');
                $query->orWhereRaw('(deduc_bpjs_tk != 0)');
            })
            ->orderBy('emp_name')
            ->get();

        return view("employee.bpjs_tk", compact('emp'));
    }

    function disable_ppe(Request $request){
        $ppe = Hrd_employee_ppe::where("emp_id", $request->emp_id)->first();

        $data = [
            "success" => true
        ];

        if(!empty($ppe)){
            if($ppe->enable == 1){
                $ppe->enable = 0;
            } else {
                $ppe->enable = 1;
            }

            if($ppe->save()){
                $data = [
                    "success" => true,
                    "enable" => $ppe->enable
                ];
            } else {
                $data = [
                    "success" => false
                ];
            }
        }

        return json_encode($data);
    }

    function generate_ppe(Request $request){
        if($request->ajax()){
            $data = [];
            $ppe = Hrd_employee_ppe::where("emp_id", $request->emp_id)
                ->first();
            if(empty($ppe)){
                try {
                    $link = "";
                    $url = 'https://api-ssl.bitly.com/v4/shorten';
                    $ch = curl_init($url);
                    $post = [];
                    $post['domain'] = "bit.ly";
                    $post['long_url'] = route('hrd.ppe', $request->emp_id);

                    $_post = json_encode($post);

                    $authorization = "Authorization: Bearer ed7f3babde7c15d258ee1501a84360e99bea0c12";

                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FAILONERROR, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$_post);
                    $result = curl_exec($ch);
                    $js = json_decode($result, true);
                    if(isset($js['link'])){
                        $emp = Hrd_employee::find($request->emp_id);
                        $link = $js['link'];
                        $ppe = new Hrd_employee_ppe();
                        $ppe->emp_id = $request->emp_id;
                        $ppe->link = $link;
                        $ppe->created_by = Auth::user()->username;
                        $ppe->company_id = $emp->company_id;
                        if($ppe->save()){
                            $data = [
                                "success" => true,
                                "link" => $ppe->link
                            ];
                        }
                    } else {
                        $data = [
                            "success" => false,
                        ];
                    }
                } catch (\Throwable $th) {
                    $data = [
                        "success" => false,
                        "message" => $th->getMessage()
                    ];
                }
            } else {
                $data = [
                    "success" => true,
                    "link" => $ppe->link
                ];
            }

            return json_encode($data);
        }
    }

    function bank_emp($id){
        $id_companies = [];
        $id_companies[] = Session::get("company_id");
        if(!empty(Session::get("company_id_parent"))){
            $id_companies[] = Session::get("company_id_parent");
        }

        $emp = Hrd_employee::find($id);

        $pref_bank = Master_banks::get();

        $banks = Hrd_employee_bank::where("emp_id", $emp->id)
            ->get();

        return view("employee.bank", compact("emp", "pref_bank", "banks"));
    }

    function bank_emp_add(Request $request){
        $bank = new Hrd_employee_bank();
        if(isset($request->id)){
            $bank = Hrd_employee_bank::find($request->id);
        } else {
            $bank->emp_id = $request->emp_id;
        }
        $bank->bank_id = $request->bank_id;
        $bank->bank_number = $request->bank_number;
        $bank->save();

        $override = $request->override;
        if($override){
            Hrd_employee_bank::where("emp_id", $bank->emp_id)
                ->update([
                    "override" => 0
                ]);
            $bank->override = 1;
            $bank->save();
        }

        return redirect()->back();
    }

    function bank_emp_delete($id){
        $bank = Hrd_employee_bank::find($id);
        $bank->delete();
        return redirect()->back();
    }

    function pension_plan(Request $request){
        $issue_no = 1;
        $last_plan = Hrd_employee_pension::where("company_id", Session::get("company_id"))
            ->orderBy("issue_no", "desc")
            ->first();
        if(!empty($last_plan)){
            $issue_no = $last_plan->issue_no + 1;
        }
        $dd = date("my", strtotime($request->issue_date));
        $issue_num = sprintf("%03d", $issue_no)."/".Session::get('company_tag')."/SP/MPP/$dd";
        $emp = Hrd_employee::find($request->emp_id);

        $plan = new Hrd_employee_pension();
        $plan->emp_id = $request->emp_id;
        $plan->issue_date = $request->issue_date;
        $plan->pension_date = $request->pension_date;
        $plan->issue_number = $issue_num;
        $plan->issue_no = $issue_no;
        $plan->created_by = Auth::user()->username;
        $plan->company_id = $emp->company_id;
        $plan->save();

        return redirect()->back();
    }

    function pension_issue($type, $id, Request $request){
        if($type == "mpp"){
            $pension = Hrd_employee_pension::where('id', $id)
                ->withTrashed()->first();
        } else {
            $pension = Hrd_severance::where('id', $id)
                ->withTrashed()->first();
        }
        $emp = Hrd_employee::find($pension->emp_id);

        $comp = ConfigCompany::find($emp->company_id);

        $img_comp = "<img src='".str_replace('public', 'public_html', asset('images/'.$comp->p_logo))."' width='150px'>";

        $pos = Hrd_employee_type::where("name", 'Manager')->first();
        $div = Division::where("name", "HRD")->first();
        $hrdm = "";
        $signimg = "";
        $mompath = scandir(public_path('media/sign_mom'));
        $userpath = scandir(public_path('media/user/signature'));
        if(!empty($pos) && !empty($div)){
            $mhrd = Hrd_employee::where("emp_type", $pos->id)->where('division', $div->id)->where("company_id", $emp->company_id)->first();
            if(!empty($mhrd)){
                $hrdm = $mhrd->emp_name;
                $userM = User::where("emp_id", $mhrd->id)->whereNotNull("file_signature")->first();
                if(!empty($userM)){
                    if(in_array($userM->file_signature, $mompath)){
                        $signimg = str_replace("public", "public_html", asset("media/sign_mom/$userM->file_signature"));
                    } elseif(in_array($userM->file_signature, $userpath)){
                        $signimg = str_replace("public", "public_html", asset("media/user/signature/$userM->file_signature"));
                    }
                }
            }
        }

        $empUser = User::where("emp_id", $emp->id)->get();
        $empSign = "";
        if(!empty($empUser->whereNotNull("file_signature")->first())){
            $euser = $empUser->whereNotNull("file_signature")->first();
            if(in_array($euser->file_signature, $mompath)){
                $empSign = str_replace("public", "public_html", asset("media/sign_mom/$euser->file_signature"));
            } elseif(in_array($euser->file_signature, $userpath)){
                $empSign = str_replace("public", "public_html", asset("media/user/signature/$euser->file_signature"));
            }
        }

        if($type == "mpp"){
            $v = "employee.pension_mpp_issue";
        } else {
            $v = "employee.pension_issue";
        }

        $view = view("$v", compact("pension", "emp", 'comp', 'img_comp', 'hrdm', 'signimg', 'pos', 'div', 'empUser', 'empSign'));

        if($request->pdf == 1){
            $mpdf = new Mpdf(['en-GB-x','A4','','',10,10,10,10,6,3, 'tempDir'=>storage_path('tempdir')]);

            $mpdf->SetAuthor($comp->company_name);
            $mpdf->SetTitle($comp->tag.' Pension Issue');
            $mpdf->SetKeywords('archive, PDF');
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetFooter("link");

            $mpdf->list_indent_first_level = 0;

            $mpdf->WriteHtml($view);

            $mpdf->output();
        } else {
            return $view;
        }
    }

    function pension_receive($type, $id){
        if($type == "mpp"){
            $pension = Hrd_employee_pension::where('id', $id)
                ->withTrashed()->first();
        } else {
            $pension = Hrd_severance::where('id', $id)
                ->withTrashed()->first();
        }
        $emp = Hrd_employee::find($pension->emp_id);

        $comp = ConfigCompany::find($emp->company_id);

        $img_comp = "<img src='".str_replace('public', 'public_html', asset('images/'.$comp->p_logo))."' class='w-75px w-md-150px' style='object-fit: contain;'>";

        $pos = Hrd_employee_type::where("name", 'Manager')->first();
        $div = Division::where("name", "HRD")->first();
        $hrdm = "";
        $signimg = "";
        $mompath = scandir(public_path('media/sign_mom'));
        $userpath = scandir(public_path('media/user/signature'));
        if(!empty($pos) && !empty($div)){
            $mhrd = Hrd_employee::where("emp_type", $pos->id)->where('division', $div->id)->where("company_id", $emp->company_id)->first();
            if(!empty($mhrd)){
                $hrdm = $mhrd->emp_name;
                $userM = User::where("emp_id", $mhrd->id)->whereNotNull("file_signature")->first();
                if(!empty($userM)){
                    if(in_array($userM->file_signature, $mompath)){
                        $signimg = str_replace("public", "public_html", asset("media/sign_mom/$userM->file_signature"));
                    } elseif(in_array($userM->file_signature, $userpath)){
                        $signimg = str_replace("public", "public_html", asset("media/user/signature/$userM->file_signature"));
                    }
                }
            }
        }

        $empUser = User::where("emp_id", $emp->id)->get();
        $empSign = "";
        if(!empty($empUser->whereNotNull("file_signature")->first())){
            $euser = $empUser->whereNotNull("file_signature")->first();
            if(in_array($euser->file_signature, $mompath)){
                $empSign = str_replace("public", "public_html", asset("media/sign_mom/$euser->file_signature"));
            } elseif(in_array($euser->file_signature, $userpath)){
                $empSign = str_replace("public", "public_html", asset("media/user/signature/$euser->file_signature"));
            }
        }

        if($type == "mpp"){
            $v = "employee.pension_mpp_receive";
        } else {
            $v = "employee.pension_receive";
        }

        $view = view($v, compact("pension", "emp", 'comp', 'img_comp', 'hrdm', 'signimg', 'pos', 'div', 'empUser', 'empSign', 'type'));
        return $view;
    }

    function pension_confirm(Request $request){
        if($request->type == "mpp"){
            $pension = Hrd_employee_pension::where('id', $request->id)
                ->withTrashed()->first();
        } else {
            $pension = Hrd_severance::where('id', $request->id)
                ->withTrashed()->first();
        }
        if(!empty($pension->deleted_at)){
            return redirect()->back();
        }
        $emp = Hrd_employee::find($pension->emp_id);

        if($request->submit == 1 && empty($pension->received_at)){
            $pension->received_at = date("Y-m-d H:i:s");
            $pension->received_by = $emp->emp_name;
            $pension->save();
        }

        $comp = ConfigCompany::find($emp->company_id);

        return view("employee.pension_confirm", compact("emp", "pension", "comp"));
    }

    function pension_cancel($id){
        Hrd_employee_pension::find($id)->delete();

        return redirect()->back();
    }

    function user_outstanding(Request $request){
        $emp = Hrd_employee::where("company_id", Session::get("company_id"))
            ->whereNull("expel")
            ->get();
        $userEmp = User::whereIn("emp_id", $emp->pluck("id"))
            ->whereNull("file_signature")
            ->where("company_id", Session::get("company_id"))
            ->orderBy("name")
            ->get();
        return view("employee.user_outstanding", compact("emp", "userEmp"));
    }

    function user_outstanding_update(Request $request){
        $user = User::find($request->user_id);
        $dd = date("Y_m_d_Hi");
        if($request->signature == "upload"){
            $file = $request->file("image");
            $newName = "[signature_$user->id]$dd-".$file->getClientOriginalName();
            $file->move(public_path("media/user/signature"), $newName);
        } else {
            $folderPath = public_path("media/user/signature/");

            $image_parts = explode(";base64,", $request->imgUri);

            $image_type_aux = explode("image/", $image_parts[0]);

            $image_type = $image_type_aux[1];

            $image_base64 = base64_decode($image_parts[1]);

            $newName = "[signature_$user->id]$dd.".$image_type;

            $file = $folderPath . $newName;
            try {
                file_put_contents($file, $image_base64);
            } catch (\Throwable $th) {
                return json_encode([
                    "success" => 0,
                    "message" => $th->getMessage()
                ]);
            }

        }
        User::where("id_batch", $user->id_batch)
            ->update([
                "file_signature" => $newName
            ]);
        return json_encode([
            "success" => 1
        ]);
    }

    function export_emp(){
        return Excel::download(new CompanyExport, 'employee.xlsx');
    }

    function report_emp(){
        $comp_ids = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $comp_ids[] = $ids->id;
            }
        } else {
            $comp_ids[] = $comp->id_parent;
        }

        $comp_ids[] = Session::get('company_id');
        $emp_type = Hrd_employee_type::whereIn('company_id', $comp_ids)
            ->get();

        $cv_doc_type = Hrd_employee_attach_category::whereIn("company_id", $comp_ids)
            ->orderBy("name")
            ->get();

        $cols = DB::getSchemaBuilder()->getColumnListing('hrd_employee');
        $colsEx = ["id", "old_id", "emp_id_sister", "emp_level", "pension", "pendidikan", "max_loan", "others", "loan_balance", "picture", "created_at", "created_by", "updated_at", "updated_by", "freeze", "is_driver", "company_id", "deleted_at", "deleted_by", "performa_update", "gender", "finalize_expel", "finalize_expel_by", "expel", "npwp", "yearly_bonus", "fx_yearly_bonus", "expire", "ktp", "serti1", "cuti", "cuti_flag", "phoneh", "phoneh2", "phone2", "thr", "emp_birth", "emp_tmpt_lahir", "mcu_id", "gender", "point", "point_mandatory", "bank_save", "contract_file", "pro_hire", "id_wh", "bank_code2", "bank_acct2"];
        return view("employee.report", compact('emp_type', 'cols', 'colsEx', 'cv_doc_type'));
    }

    function report_emp_post(Request $request){
        $cols = $request->cols;
        $seltype = $request->emp_type;
        $view = null;
        if(empty($cols)){
            $status = false;
            $msg = "Please select at least 1 column(s)";
        } else {
            $status = true;
            $msg = "success";
            $docs = $request->docs;
            $filterByDoc = [];
            $addCols = [];
            $hasDoc = [];
            $with_expiry = $request->with_expiry;
            $expiry_date = $request->expiry_date;
            if(!empty($docs)){
                $hasDoc = Hrd_cv_u::whereIn("category_id", $docs)
                    ->where(function($q) use($with_expiry, $expiry_date){
                        if($with_expiry == 1){
                            $q->where("expiry_date", "<=", $expiry_date);
                        }
                    })
                    ->get();
                $filterByDoc = $hasDoc->pluck("user_id");
                $colTp = Hrd_employee_attach_category::whereIn('id', $docs)
                    ->get();
                $addCols = $colTp->pluck("name", "id")->toArray();
            }
            $employee = Hrd_employee::where(function($q) use ($seltype){
                if(!empty($seltype)){
                    $q->whereIn("emp_type", $seltype);
                }
            })->where(function($q) use($filterByDoc, $docs){
                if(!empty($docs) && count($docs) > 0){
                    $q->whereIn('id', $filterByDoc);
                }
            })
            ->whereNull("expel")
            ->whereNull("finalize_expel")
            ->whereNull("freeze")
            ->where("company_id", Session::get("company_id"))
            ->orderBy("emp_name")
            ->get();
            $etype = Hrd_employee_type::whereIn("id", $employee->pluck("emp_type"))->get();
            $divs =  Division::whereIn("id", $employee->pluck("division"))->get();
            $jdate = Hrd_employee_history::whereIn("emp_id", $employee->pluck("id"))
                ->where("activity", "in")
                ->get();
            $mod = \App\Models\Module::all()->pluck('name', 'id');
            $typeshow = [];
            $rc = Session::get('company_user_rc');
            foreach($etype as $value){
                $action = (isset($mod[$value->rms_id])) ? $mod[$value->rms_id] : "payroll_".str_replace(" ","_", strtolower($value->name));
                if(isset($rc[$action])){
                    if($rc[$action]['access'] == 1){
                        $typeshow[] = $value->id;
                    }
                }
            }
            $view = view("employee.report_view", compact("cols", "employee", "etype", "divs", "jdate", "mod", "typeshow", "addCols", "hasDoc", "with_expiry"))->render();
        }

        $arr = [
            "status" => $status,
            "message" => $msg,
            'view' => $view
        ];
        return $arr;
    }

    function grade_change($type, $id){
        $emp = Hrd_employee::find($id);
        $grade = Career_path::find($emp->grade);
        $pos = Hrd_employee_type::find($emp->emp_type);
        $div = Division::find($emp->division);

        $grades = Career_path::where(function($q) use($type, $emp, $grade){
            if(!empty($emp->grade)){
                if($type == "promote"){
                    $q->where('order_num', "<=", $grade->order_num);
                } else {
                    $q->where('order_num', ">=", $grade->order_num);
                }
            }
        })
        ->orderBy("order_num")
        ->get();

        return view("employee.grade_change", compact("emp", "type", "grade", "pos", "div", "grades"));
    }

    function grade_submit(Request $request){
        $emp = Hrd_employee::find($request->emp_id);
        $old = [
            "wages" => [
                "n_basic_salary" => $emp->n_basic_salary,
                "n_house_allow" => $emp->n_house_allow,
                "n_health_allow" => $emp->n_health_allow,
                "n_position_allow" => $emp->n_position_allow,
            ],
            "non_wages" => [
                "n_transport_allow" => $emp->n_transport_allow,
                "n_meal_allow" => $emp->n_meal_allow,
            ],
            "n_performance_bonus" => $emp->n_performance_bonus
        ];

        $new = [
            "wages" => [
                "n_basic_salary" => base64_encode(str_replace(",", "", $request->salary)),
                "n_house_allow" => base64_encode(str_replace(",", "", $request->house)),
                "n_health_allow" => base64_encode(str_replace(",", "", $request->health)),
                "n_position_allow" => base64_encode(str_replace(",", "", $request->position)),
            ],
            "non_wages" => [
                "n_transport_allow" => base64_encode(str_replace(",", "", $request->transport)),
                "n_meal_allow" => base64_encode(str_replace(",", "", $request->meal)),
            ],
            "n_performance_bonus" => base64_encode(str_replace(",", "", $request->performance_bonus))
        ];


        $update = new Hrd_employee_career_update();
        $update->emp_id = $emp->id;
        $update->grade_old = $emp->grade;
        $update->grade_new = $request->grade;
        $update->detail_old = json_encode($old);
        $update->detail_new = json_encode($new);
        $update->created_by = Auth::user()->username;
        $update->company_id = $emp->company_id;
        $update->status = $request->status;
        $update->save();

        // $emp->n_basic_salary = base64_encode(str_replace(",", "", $request->salary));
        // $emp->n_house_allow = base64_encode(str_replace(",", "", $request->house));
        // $emp->n_health_allow = base64_encode(str_replace(",", "", $request->health));
        // $emp->n_position_allow = base64_encode(str_replace(",", "", $request->position));
        // $emp->n_transport_allow = base64_encode(str_replace(",", "", $request->transport));
        // $emp->n_meal_allow = base64_encode(str_replace(",", "", $request->meal));
        // $emp->n_performance_bonus = base64_encode(str_replace(",", "", $request->performance_bonus));
        // $emp->grade = $request->grade;
        // $emp->save();

        return redirect()->route("employee.index")->with("career", "Please wait for the authorities to approved this update");
    }

    function career_path(){
        $s = Hrd_employee_career_update::where("company_id", Session::get("company_id"))
            ->orderBy('created_at', "desc")
            ->get();
        $emp = Hrd_employee::whereIn('id', $s->pluck("emp_id"))
            ->get();
        return view("employee.career_path", compact("s", "emp"));
    }

    function career_path_detail($id){
        $cp = Hrd_employee_career_update::find($id);
        $emp = Hrd_employee::find($cp->emp_id);

        $grades = Career_path::whereIn('id', [$cp->grade_old, $cp->grade_new])
            ->get();

        $old = $grades->where("id", $cp->grade_old)->first();
        $new = $grades->where("id", $cp->grade_new)->first();

        $detail_old = json_decode($cp->detail_old, true);
        $detail_new = json_decode($cp->detail_new, true);

        return view("employee.career_detail", compact("cp", "emp", "old", "new", "detail_old", "detail_new"));
    }

    function career_path_delete($id){
        Hrd_employee_career_update::find($id)->delete();

        return redirect()->back();
    }

    function career_path_approve(Request $request){
        $now = date("Y-m");
        $cp = Hrd_employee_career_update::find($request->cp);
        $cp->approved_at = date("Y-m-d H:i:s");
        $cp->approved_by = Auth::user()->username;
        $cp->effective_date = $request->implement_date;
        $cp->save();

        $emp = Hrd_employee::find($cp->emp_id);

        $eff = date("Y-m", strtotime($request->implement_date));
        if($now <= $eff){
            $new = json_decode($cp->detail_new, true);
            $wages = $new['wages'];
            $nonwages = $new['non_wages'];
            $pb = $new['n_performance_bonus'];
            $emp->n_basic_salary = $wages['n_basic_salary'];
            $emp->n_house_allow = $wages['n_house_allow'];
            $emp->n_health_allow = $wages['n_health_allow'];
            $emp->n_position_allow = $wages['n_position_allow'];
            $emp->n_transport_allow = $nonwages['n_transport_allow'];
            $emp->n_meal_allow = $nonwages['n_meal_allow'];
            $emp->n_performance_bonus = $pb;
            $emp->grade = $request->grade;
            $emp->save();
        }

        return redirect()->route("employee.career.index");
    }

    function pb(){
        $id_companies = array();
        if (Session::get('company_child') != null){
            foreach (Session::get('company_child') as $item) {
                $id_companies[] = $item->id;
            }
            array_push($id_companies, Session::get('company_id'));
        } else {
            array_push($id_companies, Session::get('company_id'));
        }

        $divisions = Rms_divisions::where('name','not like','%admin%')
            ->whereNull('deleted_at')
            ->get();
        $employees = Hrd_employee::whereNull('expel')
            ->where('company_id', Session::get("company_id"))
            ->get();
        $divName = [];
        foreach ($divisions as $key => $val){
            $divName['name'][$val->id] = $val->name;
        }

        $comp_ids = [];
        $comp = ConfigCompany::find(Session::get('company_id'));
        if (empty($comp->id_parent)) {
            $childCompany = ConfigCompany::select("id")
                ->where('id_parent', $comp->id)
                ->get();
            foreach($childCompany as $ids){
                $comp_ids[] = $ids->id;
            }
        } else {
            $comp_ids[] = $comp->id_parent;
        }

        $comp_ids[] = Session::get('company_id');


        $emptypes = Hrd_employee_type::whereIn('company_id', $comp_ids)
            ->where('company_exclude', 'not like', '%"'.$comp->id.'"%')
            ->orWhereNull("company_exclude")
            ->get();
        $emp_type = [];
        foreach ($emptypes as $key => $val){
            $emp_type[$val->id] = $val->name;
        }

        $userEmp = User::whereIn("emp_id", $employees->pluck("id"))
            ->whereNull("file_signature")
            ->where("company_id", Session::get("company_id"))
            ->get();

        $roles = Role::where("show_career", 1)
            ->orderBy("career_num")
            ->get();
        $cp = Career_path::whereIn("role_id", $roles->pluck("id"))
            ->orderBy("order_num")
            ->get();

        $grades = [];
        foreach($roles as $rl){
            $cpr = $cp->where("role_id", $rl->id);
            foreach($cpr as $cps){
                $grades[] = $cps;
            }
        }

        return view('employee.pb',[
            'employees' => $employees,
            'emptypes' => $emptypes,
            'divisions' => $divisions,
            'divName' => $divName,
            'emp_type' => $emp_type,
            'userEmp' => $userEmp,
            'grades' => $grades
        ]);
    }

    function pb_update(Request $request){
        $pb = base64_encode(str_replace(",", "", $request->pb));
        $emp = Hrd_employee::find($request->emp_id);
        $emp->n_performance_bonus = $pb;
        $emp->save();

        return redirect()->back()->with('career', "Performance Bonus $emp->emp_name Updated");
    }

    function setAddressPage($id){
        $emp = Hrd_employee::find($id);

        return view("employee.address", compact("emp"));
    }

    function saveCoordinates($id, Request $request){
        $emp = Hrd_employee::find($id);

        $emp->latitude = $request->latitude;
        $emp->longitude = $request->longitude;
        $emp->radius = $request->radius;

        $emp->save();

        return redirect()->route("employee.detail", $emp->id);
    }

    function changePassword($id, Request $request){
        $link = $request->submit;
        $emp = Hrd_employee::find($id);
        $user = User::where("email", $emp->email)->first();
        if(empty($link)){
            if(!empty($user)){
                $user->password = Hash::make($request->password);
                $user->save();
            }
        } else {
            if(!empty($user)){
                try {

                    // $compact = [
                    //     "link" => "",
                    //     ""
                    // ]

                    Mail::to($collabs->email)->send(new SendMailConfig("change_password", $compact));

                    return redirect()->back()->with(["msg" => "Undangan terkirim"]);
                } catch (\Throwable $th) {
                    return redirect()->back()->withErrors(['error' => "Undangan gagal terkirim"]);
                }
            }
            dd($request->all());
        }

        return redirect()->back();
    }
}

