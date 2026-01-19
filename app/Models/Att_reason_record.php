<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Att_reason_record extends Model
{
	use SoftDeletes;
    use LogsActivity;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'att_reason_records';

    public function getDescriptionForEvent(string $eventName): string {
        return "This model has been $eventName";
    }

    protected $casts = [
        "reason_values" => "array",
        "reasons" => "array",
        'autoOvtInDetail' => "array",
        'autoOvtOutDetail' => "array",
    ];

    protected $fillable = [
        "emp_id",
        "att_date",
        "reason_id",
        "reason_values",
        "timin",
        "timout",
        "day_code",
        "shift_id",
        "created_at",
        "created_by",
        "company_id",
        "reasons",
        "ovtstart",
        'ovtend',
        'break_start',
        'break_end',
        'ovthours',
        'is_holiday',
        'holiday_id',
        "autoOvtIn",
        "autoOvtInId",
        "autoOvtInDetail",
        "autoOvtOut",
        "autoOvtOutId",
        "autoOvtOutDetail",
        "ovtstartin",
        'ovtendin',
        'ovthoursin',
    ];

	protected $table = 'att_reason_records';
	protected $dates = ['deleted_at'];

    public function reason_condition(){
        return $this->belongsTo(Att_reason_name::class,
        "reason_id", "id");
    }

    public function shift(){
        return $this->belongsTo(Att_shift::class, "shift_id", "id");
    }

    public function day_code(){
        return $this->belongsTo(Att_day_code::class, "day_code", "id");
    }

    public function emp(){
        return $this->belongsTo(Hrd_employee::class, "emp_id", "id");
    }
}
