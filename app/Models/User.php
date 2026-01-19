<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'position',
        'user_img',
        'access',
        'id_batch',
        'company_id',
        "id_rms_roles_divisions",
        'emp_hris_id',
        'company_hris_id',
        'email_verified_at',
        'complete_profile',
        'role_access',
        'phone',
        'attend_code',
        "is_owner",
        'emp_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'search_prefs' => "array",
        'phones' => "array",
        "emails" => "array",
        // "role_access" => "array"
    ];

    public function privilege()
    {
        return $this->hasMany('App\Models\UserPrivilege', 'id_users', 'id');
    }

    protected static $logAttributes = ['*'];
    protected $dates = ['deleted_at'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'user';

    public function getDescriptionForEvent(string $eventName): string {
        return "You have $eventName user";
    }

    public function scopeHris($query){
        return $query->where("role_access", "like", '%"hris"%');
    }

    public function scopeApplicant($query){
        return $query->where("role_access", "like", '%"applicant"%');
    }

    public function scopeEmployer($query){
        return $query->where("role_access", "like", '%"employer"%');
    }

    public function emp(){
        return $this->belongsTo(Hrd_employee::class, "emp_id", "id");
    }

    /**
     * Get the user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role(): HasOne
    {
        return $this->hasOne(RoleDivision::class, 'id', 'id_rms_roles_divisions');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(User_profile::class, 'user_id', 'id');
    }

    public function medsos(): HasOne
    {
        return $this->hasOne(User_medsos::class, 'user_id', 'id');
    }

    public function company(){
        return $this->belongsTo(ConfigCompany::class, "company_id", "id");
    }

    public function applied(){
        return $this->hasMany(User_job_applicant::class, "user_id", "id");
    }

    public function crmJobTitle()
    {
        return $this->belongsTo(Kjk_crm_job_title::class, 'crm_job_title', 'id');
    }

    public function crmRole(){
        return $this->belongsTo(Kjk_crm_permission::class, "crm_role", "id");
    }

    public function dPipeline(){
        return $this->belongsTo(Kjk_lead_layout::class, "default_pipeline", "id");
    }

    public function uacrole(){
        return $this->belongsTo(Kjk_uac_role::class, "uac_role", "id");
    }

    public function uaclocation(){
        return $this->belongsTo(Asset_wh::class, "uac_location", "id");
    }

    public function uacdepartement(){
        return $this->belongsTo(Kjk_comp_departement::class, "uac_departement", "id");
    }
}
