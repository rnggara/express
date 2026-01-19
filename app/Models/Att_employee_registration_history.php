<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_employee_registration_history extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_employee_registration_histories';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_employee_registration_histories';
	protected $dates = ['deleted_at'];

    public function reg(){
        return $this->belongsTo(Att_employee_registration::class, "reg_id", "id");
    }
}
