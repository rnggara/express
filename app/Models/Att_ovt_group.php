<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_ovt_group extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_ovt_groups';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

	protected $table = 'att_ovt_groups';
	protected $dates = ['deleted_at'];

    public function dayCode() {
        return $this->belongsTo(Att_day_code::class, "day_code", "id");
    }

    public function calc(){
        return $this->hasMany(Att_ovt_group_calculation::class, "group_id", "id");
    }
}
