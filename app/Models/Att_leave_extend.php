<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_leave_extend extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_leave_extends';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_leave_extends';
	protected $dates = ['deleted_at'];

    public function emp(){
        return $this->belongsTo(Hrd_employee::class, "emp_id", "id");
    }

    public function lg(){
        return $this->belongsTo(Att_employee_registration::class, "periode", "id");
    }
}
