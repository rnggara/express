<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ConfigCompany;
use App\Models\RolePrivilege;
use App\Models\User;
use App\Models\UserPrivilege;
use App\Models\Master_province;
use App\Models\Master_industry;
use App\Models\Master_company;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = "/registration-complete";

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $redirect = $this->redirectPath()."?email=$request->email";

        event(new Registered($user = $this->create($request->all())));

        // // $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: json_encode(["success" => true, "redirectPath" => $redirect]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $id_role = $data['register_as'];
        $emailExp = explode("@", $data['email']);
        $username = $emailExp[0];
        $company_id = 1;
        $id_batch = $username."1";
        return User::create([
            'name' => $data['fullname'],
            'email' => $data['email'],
            'username' => $username,
            'id_rms_roles_divisions' => 44,
            'company_id' => $company_id,
            'id_batch' => $id_batch,
            "position" => "FP",
            "role_access" => json_encode(["applicant"]),
            'password' => Hash::make($data['password']),
            "complete_profile"  => 1
        ]);
    }

    public function showRegistrationForm()
    {
        $kdp = [];

        $parent = ConfigCompany::whereNull('id_parent')->orderBy('id')->get();

        $all_company = ConfigCompany::all();
            $kdp = "";
            $parent = $parent->first();

        $prov = Master_province::get();
        $industry = Master_industry::get();

        return view('auth.register',[
            'companies' => $all_company,
            'who' => $kdp,
            'parent_comp' => $parent,
            'prov' => $prov,
            'industry' => $industry
        ]);
    }

    protected function createEmployer(array $data)
    {
        $emailExp = explode("@", $data['email']);
        $username = $emailExp[0];
        $company_id = 1;
        $id_batch = $username."1";
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $username,
            'id_rms_roles_divisions' => 45,
            'company_id' => $company_id,
            'id_batch' => $id_batch,
            "position" => "FP",
            'password' => Hash::make($data['password']),
            "role_access" => json_encode(["employer"]),
            "phone" => $data['phone'],
            "attend_code" => $data['position'],
            'access' => "EP",
            'is_owner' => 1
        ]);
    }

    public function registerEmployerRequest(Request $request)
    {

        event(new Registered($user = $this->createEmployer($request->all())));

        $company = $this->createCompany($request->all(), $user->id);

        $user->comp_id = $company->id;
        $user->save();

        return $user;
    }

    function createCompany($data, $user_id){
        $company = new Master_company();
        $company->company_name = $data['company_name'];
        $company->prov_id = $data['prov_id'] ?? null;
        $company->industry_id = $data['industry_id'] ?? null;
        $company->skala_usaha = $data['skala_usaha'] ?? null;
        $company->scale = $data['scale'] ?? null;
        $company->email = $data['email'] ?? null;
        $company->owner_id = $user_id;
        $company->save();

        return $company;
    }

    public function showRegisterEmployerForm(){

        $prov = Master_province::get();
        $industry = Master_industry::get();

        return view("auth.register_employer", compact("prov", "industry"));
    }

    function checkEmail(Request $request){
        $user = User::where("email", $request->email)->first();

        $role_access = json_decode($user->role_access ?? "[]", true);

        $data = [
            "success" => empty($user) ? false : true,
            "user" => $user->id ?? null,
            "isRegistered" => in_array("employer", $role_access) ? true : false
        ];

        return json_encode($data);
    }

    function registerEmployer(Request $request){
        if($request->state == 0){
            $user = $this->registerEmployerRequest($request);
        } else {
            $user = User::where("email", $request->email)->first();
            $role_access = json_decode($user->role_access ?? "[]", true);
            $role_access[] = "employer";
            $user->role_access = json_encode($role_access);
            $user->save();

            $company = $this->createCompany($request->all(), $user->id);

            $user->comp_id = $company->id;
            $user->save();
        }

        $this->registered($request, $user);

        $redirect = $this->redirectPath()."?email=$request->email";

        return redirect($redirect);
    }

    public function showCompleteRegister(Request $request){
        $email = $request->email;
        return view("auth.verify", compact("email"));
    }

    protected function registered(Request $request, $user)
    {
        $id_role = $user->id_rms_roles_divisions;
        $roleDivPriv = RolePrivilege::select('id_rms_modules', 'id_rms_actions')
            ->where('id_rms_roles_divisions', $id_role)
            ->get();
        foreach ($roleDivPriv as $key => $valDivPriv) {
            $addUserRole = new UserPrivilege();
            $addUserRole->id_users = $user->id;
            $addUserRole->id_rms_modules = $valDivPriv->id_rms_modules;
            $addUserRole->id_rms_actions = $valDivPriv->id_rms_actions;
            $addUserRole->save();
        }
    }
}
