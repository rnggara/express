<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_workgroup_schedule extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_workgroup_schedules';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $casts = [
        "detail" => "array"
    ];

    protected $fillable = ["workgroup", "periode"];

	protected $table = 'att_workgroup_schedules';
	protected $dates = ['deleted_at'];
}
