<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_leave_request extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_leave_requests';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_leave_requests';
	protected $dates = ['deleted_at'];

    public function emp(){
        return $this->belongsTo(Hrd_employee::class, "emp_id", "id");
    }

    public function rt(){
        return $this->belongsTo(Att_reason_type::class, "reason_type", "id");
    }
}
