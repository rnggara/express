<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_schedule_correction extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_schedule_corrections';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_schedule_corrections';
	protected $dates = ['deleted_at'];

    protected $fillable = ['emp_id', "date"];

    public function shift(){
        return $this->belongsTo(Att_shift::class, "shift_id", "id");
    }
}
