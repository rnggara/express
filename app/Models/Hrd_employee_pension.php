<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hrd_employee_pension extends Model
{
    use SoftDeletes;
    protected $table = 'hrd_employee_pension';
    protected $dates = ['deleted_at'];

    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'hrd_employee_pension';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    public function scopeEmp($query, $value){
        return $query->where("emp_id", $value);
    }
}
