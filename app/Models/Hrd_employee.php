<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_employee extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_employee';
    protected $dates =['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_employee';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    public function div() {
        return $this->belongsTo(Division::class, "division", "id");
    }

    public function dept() {
        return $this->belongsTo(Hrd_employee_type::class, "emp_type", "id");
    }

    public function reg() {
        return $this->hasOne(Att_employee_registration::class, "emp_id", "id");
    }

    public function user(){
        return $this->hasOne(User::class, "emp_id", "id");
    }

    public function comp(){
        return $this->belongsTo(ConfigCompany::class, "company_id", "id");
    }

    public function position(){
        return $this->belongsTo(Kjk_comp_position::class, "position_id", "id");
    }

    public function job_level(){
        return $this->belongsTo(Kjk_comp_job_level::class, "job_level_id", "id");
    }

    public function job_grade(){
        return $this->belongsTo(Kjk_comp_job_grade::class, "job_grade_id", "id");
    }

    public function employee_status(){
        return $this->belongsTo(Personel_employee_status::class, "employee_status_id", "id");
    }

    public function acting_position(){
        return $this->belongsTo(Kjk_comp_position::class, "acting_position_id", "id");
    }

    public function offence(){
        return $this->hasMany(Personel_offence::class, "emp_id", "id");
    }

    public function profile(){
        return $this->hasOne(Personel_profile::class, "user_id", "id");
    }
}
